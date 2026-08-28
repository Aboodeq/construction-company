<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuoteRequestController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('quote-requests.view');

        $requestsQuery = QuoteRequest::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            });

        $quoteRequests = $requestsQuery->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => QuoteRequest::count(),
            'new' => QuoteRequest::where('status', 'new')->count(),
            'in_progress' => QuoteRequest::where('status', 'in_progress')->count(),
            'closed' => QuoteRequest::where('status', 'closed')->count(),
        ];

        return view('admin.quote-requests.index', compact('quoteRequests', 'stats'));
    }

    public function show(QuoteRequest $quoteRequest)
    {
        $this->authorize('quote-requests.view');

        $quoteRequest->markAsRead();
        $quoteRequest->load('files');

        return view('admin.quote-requests.show', compact('quoteRequest'));
    }

    public function updateStatus(Request $request, QuoteRequest $quoteRequest)
    {
        $this->authorize('quote-requests.edit');

        $request->validate([
            'status' => ['required', Rule::in(['new', 'read', 'in_progress', 'closed'])],
        ]);

        $quoteRequest->update(['status' => $request->input('status')]);

        return back()->with('success', 'تم تحديث حالة الطلب.');
    }

    public function destroy(QuoteRequest $quoteRequest)
    {
        $this->authorize('quote-requests.delete');

        foreach ($quoteRequest->files as $file) {
            Storage::disk('public')->delete($file->file_path);
        }

        $quoteRequest->delete();

        return redirect()
            ->route('admin.quote-requests.index')
            ->with('success', 'تم حذف الطلب.');
    }

    public function export(Request $request): StreamedResponse
    {
        $this->authorize('quote-requests.export');

        $requestsQuery = QuoteRequest::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->latest();

        $filename = 'quote-requests-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($requestsQuery) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['الاسم', 'الهاتف', 'البريد الإلكتروني', 'نوع المشروع', 'المدينة', 'المساحة', 'الميزانية التقديرية', 'الحالة', 'تاريخ الإرسال']);

            $statusLabels = ['new' => 'جديد', 'read' => 'تمت المشاهدة', 'in_progress' => 'قيد المعالجة', 'closed' => 'مغلق'];

            $requestsQuery->chunk(200, function ($chunk) use ($handle, $statusLabels) {
                foreach ($chunk as $quoteRequest) {
                    fputcsv($handle, [
                        $quoteRequest->name,
                        $quoteRequest->phone,
                        $quoteRequest->email,
                        $quoteRequest->project_type,
                        $quoteRequest->city,
                        $quoteRequest->area,
                        $quoteRequest->estimated_budget,
                        $statusLabels[$quoteRequest->status] ?? $quoteRequest->status,
                        $quoteRequest->created_at?->format('Y-m-d H:i'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
