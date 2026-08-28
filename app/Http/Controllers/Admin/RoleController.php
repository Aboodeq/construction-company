<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Permission name prefixes, grouped for display, in a sensible reading order.
     *
     * @var array<string, string>
     */
    private const GROUP_LABELS = [
        'services' => 'الخدمات',
        'projects' => 'المشاريع',
        'blog' => 'المدونة',
        'testimonials' => 'الشهادات',
        'faqs' => 'الأسئلة الشائعة',
        'team' => 'فريق العمل',
        'quote-requests' => 'طلبات الأسعار',
        'bookings' => 'الحجوزات',
        'contact-messages' => 'رسائل التواصل',
        'homepage' => 'الصفحة الرئيسية',
        'users' => 'المستخدمون',
        'roles' => 'الأدوار والصلاحيات',
        'settings' => 'الإعدادات',
    ];

    public function index()
    {
        $this->authorize('roles.view');

        $roles = Role::withCount(['permissions', 'users'])->orderBy('name')->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $this->authorize('roles.create');

        $groupedPermissions = $this->groupedPermissions();

        return view('admin.roles.create', compact('groupedPermissions'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = Role::create(['name' => $request->string('name')]);
        $role->syncPermissions($request->input('permissions', []));

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'تم إنشاء الدور بنجاح.');
    }

    public function edit(Role $role)
    {
        $this->authorize('roles.edit');

        $groupedPermissions = $this->groupedPermissions();
        $rolePermissions = $role->permissions->pluck('name')->all();

        return view('admin.roles.edit', compact('role', 'groupedPermissions', 'rolePermissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        if ($role->name === 'admin') {
            return back()->with('error', 'لا يمكن تعديل صلاحيات دور المدير العام - يملك جميع الصلاحيات دائمًا.');
        }

        $role->update(['name' => $request->string('name')]);
        $role->syncPermissions($request->input('permissions', []));

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'تم حفظ التغييرات.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->authorize('roles.delete');

        if (in_array($role->name, ['admin', 'editor'], true)) {
            return back()->with('error', 'لا يمكن حذف الأدوار الأساسية في النظام.');
        }

        if ($role->users()->exists()) {
            return back()->with('error', 'لا يمكن حذف دور مرتبط بمستخدمين. انقل المستخدمين إلى دور آخر أولًا.');
        }

        $role->delete();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'تم حذف الدور.');
    }

    /**
     * @return array<string, array{label: string, permissions: \Illuminate\Support\Collection}>
     */
    private function groupedPermissions(): array
    {
        $permissions = Permission::orderBy('name')->get();

        $grouped = [];
        foreach (self::GROUP_LABELS as $prefix => $label) {
            $groupPermissions = $permissions->filter(fn ($permission) => str_starts_with($permission->name, $prefix.'.'));

            if ($groupPermissions->isNotEmpty()) {
                $grouped[$prefix] = ['label' => $label, 'permissions' => $groupPermissions->values()];
            }
        }

        return $grouped;
    }
}
