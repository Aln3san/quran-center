<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // إعادة ضبط الكاش
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // تحديد الـ Guard الأساسي لمشروع الـ API
        $guardName = config('auth.defaults.guard');

        $permissions = [
            // الـ Admin
            ['name' => 'create-global-post', 'guard_name' => $guardName],
            ['name' => 'manage-payments', 'guard_name' => $guardName],
            ['name' => 'manage-users', 'guard_name' => $guardName],
            ['name' => 'manage-groups', 'guard_name' => $guardName],
            
            // الـ Teacher
            ['name' => 'mark-attendance', 'guard_name' => $guardName], // ده اللي بيملا المربعات
            ['name' => 'create-group-post', 'guard_name' => $guardName],
            
            // الـ Student
            ['name' => 'view-own-progress', 'guard_name' => $guardName], // يشوف مربعاته
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate($permission);
        }

        // إنشاء الـ Roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => $guardName]);
        $adminRole->syncPermissions(Permission::all());

        $teacherRole = Role::firstOrCreate(['name' => 'Teacher', 'guard_name' => $guardName]);
        $teacherRole->syncPermissions(['mark-attendance', 'create-group-post', 'view-own-progress']);

        $studentRole = Role::firstOrCreate(['name' => 'Student', 'guard_name' => $guardName]);
        $studentRole->syncPermissions(['view-own-progress']);

        // حساب الشيخ (الـ Super Admin)
        $admin = User::firstOrCreate(
            ['email' => 'admin@quran.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('12345678'),
                'phone' => '01000000000'
            ]
        );
        
        // مسح الأدوار القديمة وإضافة الجديد لضمان عدم التكرار
        $admin->syncRoles(['Admin']);
    }
}