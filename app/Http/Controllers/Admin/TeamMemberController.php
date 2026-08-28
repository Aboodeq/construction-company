<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTeamMemberRequest;
use App\Http\Requests\Admin\UpdateTeamMemberRequest;
use App\Models\TeamMember;
use App\Services\ImageUploader;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function __construct(private readonly ImageUploader $images)
    {
    }

    public function index(Request $request)
    {
        $this->authorize('team.view');

        $membersQuery = TeamMember::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->input('status'));
            });

        $members = $membersQuery
            ->orderBy('order')
            ->latest('updated_at')
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => TeamMember::count(),
            'published' => TeamMember::where('status', 'published')->count(),
            'drafts' => TeamMember::where('status', 'draft')->count(),
        ];

        return view('admin.team.index', compact('members', 'stats'));
    }

    public function create()
    {
        $this->authorize('team.create');

        return view('admin.team.create');
    }

    public function store(StoreTeamMemberRequest $request)
    {
        $data = $request->validated();
        $data['social_links'] = array_filter($data['social_links'] ?? []);

        $member = TeamMember::create($data);

        return redirect()
            ->route('admin.team.edit', $member)
            ->with('success', 'تمت إضافة عضو الفريق بنجاح. يمكنك الآن إضافة صورته.');
    }

    public function edit(TeamMember $teamMember)
    {
        $this->authorize('team.edit');

        return view('admin.team.edit', ['member' => $teamMember]);
    }

    public function update(UpdateTeamMemberRequest $request, TeamMember $teamMember)
    {
        $data = $request->safe()->only(['name', 'position', 'bio', 'status', 'order']);
        $data['social_links'] = array_filter($request->input('social_links', []));

        if ($request->boolean('remove_image') && $teamMember->image) {
            $this->images->delete($teamMember->image);
            $data['image'] = null;
        }

        if ($request->hasFile('image')) {
            $this->images->delete($teamMember->image);
            $data['image'] = $this->images->store($request->file('image'), 'team');
        }

        $teamMember->update($data);

        return redirect()
            ->route('admin.team.edit', $teamMember)
            ->with('success', 'تم حفظ التغييرات بنجاح.');
    }

    public function destroy(TeamMember $teamMember)
    {
        $this->authorize('team.delete');

        $this->images->delete($teamMember->image);
        $teamMember->delete();

        return redirect()
            ->route('admin.team.index')
            ->with('success', 'تم حذف عضو الفريق.');
    }

    public function togglePublished(TeamMember $teamMember)
    {
        $this->authorize('team.edit');

        $teamMember->update(['status' => $teamMember->status === 'published' ? 'draft' : 'published']);

        return back()->with('success', $teamMember->status === 'published' ? 'تم النشر.' : 'تم التحويل إلى مسودة.');
    }
}
