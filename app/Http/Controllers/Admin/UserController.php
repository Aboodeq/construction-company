<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('users.view');

        $usersQuery = User::query()
            ->with('roles')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->role($request->input('role'));
            });

        $users = $usersQuery->latest()->paginate(15)->withQueryString();
        $roles = Role::orderBy('name')->get();

        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'admins' => User::role('admin')->count(),
        ];

        return view('admin.users.index', compact('users', 'roles', 'stats'));
    }

    public function create()
    {
        $this->authorize('users.create');

        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'is_active' => $request->boolean('is_active', true),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($request->input('role'));

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح.');
    }

    public function edit(User $user)
    {
        $this->authorize('users.edit');

        $roles = Role::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        // The last admin can't demote themselves out of the role - it would
        // leave the system with no one able to manage users or roles at all.
        if ($user->hasRole('admin') && $request->input('role') !== 'admin' && User::role('admin')->count() <= 1) {
            return back()->withErrors(['role' => 'لا يمكن تغيير دور آخر مستخدم يملك صلاحيات المدير العام.']);
        }

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->input('password'))]);
        }

        $user->syncRoles([$request->input('role')]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'تم حفظ بيانات المستخدم.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->authorize('users.delete');

        if ($user->id === $request->user()->id) {
            return back()->with('error', 'لا يمكنك حذف حسابك الخاص.');
        }

        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            return back()->with('error', 'لا يمكن حذف آخر مستخدم يملك صلاحيات المدير العام.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'تم حذف المستخدم.');
    }

    public function toggleActive(Request $request, User $user): RedirectResponse
    {
        $this->authorize('users.edit');

        if ($user->id === $request->user()->id) {
            return back()->with('error', 'لا يمكنك تعطيل حسابك الخاص.');
        }

        if ($user->is_active && $user->hasRole('admin') && User::role('admin')->where('is_active', true)->count() <= 1) {
            return back()->with('error', 'لا يمكن تعطيل آخر مستخدم نشط يملك صلاحيات المدير العام.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', $user->is_active ? 'تم تفعيل المستخدم.' : 'تم تعطيل المستخدم.');
    }
}
