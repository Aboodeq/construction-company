<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // إعادة تعيين الكاش الخاص بالصلاحيات
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // تعريف الصلاحيات الأساسية
        $permissions = [
            // الخدمات
            'services.view',
            'services.create',
            'services.edit',
            'services.delete',
            // المشاريع
            'projects.view',
            'projects.create',
            'projects.edit',
            'projects.delete',
            // المدونة
            'blog.view',
            'blog.create',
            'blog.edit',
            'blog.delete',
            // الشهادات
            'testimonials.view',
            'testimonials.create',
            'testimonials.edit',
            'testimonials.delete',
            // الأسئلة الشائعة
            'faqs.view',
            'faqs.create',
            'faqs.edit',
            'faqs.delete',
            // فريق العمل
            'team.view',
            'team.create',
            'team.edit',
            'team.delete',
            // الطلبات
            'quote-requests.view',
            'quote-requests.delete',
            'quote-requests.export',
            'bookings.view',
            'bookings.edit',
            'bookings.delete',
            'contact-messages.view',
            'contact-messages.delete',
            // الصفحة الرئيسية
            'homepage.edit',
            // المستخدمين والصلاحيات
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            // الإعدادات
            'settings.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // إنشاء الأدوار
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        $editorRole = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $editorRole->syncPermissions(
            Permission::whereIn('name', [
                'services.view',
                'services.create',
                'services.edit',
                'projects.view',
                'projects.create',
                'projects.edit',
                'blog.view',
                'blog.create',
                'blog.edit',
                'testimonials.view',
                'testimonials.create',
                'testimonials.edit',
                'faqs.view',
                'faqs.create',
                'faqs.edit',
                'team.view',
                'team.create',
                'team.edit',
            ])->get()
        );
    }
}
