<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('bookings.view');

        $bookingsQuery = Booking::query()
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

        $bookings = $bookingsQuery->orderBy('preferred_date')->paginate(15)->withQueryString();

        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'upcoming' => Booking::whereIn('status', ['pending', 'confirmed'])->where('preferred_date', '>=', today())->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function edit(Booking $booking)
    {
        $this->authorize('bookings.view');

        return view('admin.bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $this->authorize('bookings.edit');

        $data = $request->validate([
            'preferred_date' => ['required', 'date'],
            'preferred_time' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['pending', 'confirmed', 'completed', 'cancelled'])],
        ]);

        $booking->update($data);

        return redirect()
            ->route('admin.bookings.edit', $booking)
            ->with('success', 'تم حفظ التغييرات بنجاح.');
    }

    public function destroy(Booking $booking)
    {
        $this->authorize('bookings.delete');

        $booking->delete();

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'تم حذف الحجز.');
    }
}
