<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class createadminuserseeder extends Seeder
{



//     /**
//      * Run the database seeds.
//      */
//     public function run(): void
//     {
//         //
//         $owner=User::firstOrCreate([
//              'email'=>'aboelelaali47@gmail.com'
//            ],
//             [
//             'name'=>'aboelela ali',
//             'password'=>bcrypt('00000000'),
//             'roles_name'=>'["owner"]',
//             'status'=>'مفعل'
//         ]);
//          $admin=User::firstOrCreate([
//              'email'=>'maimohamed@gmail.com'
//            ],
//             [
//             'name'=>'mai mohamed',
//             'password'=>bcrypt('00000000'),
//             'roles_name'=>'["admin"]',
//             'status'=>'مفعل'
//         ]);


//         $role_owner=Role::firstOrCreate(['name'=>'owner','guard_name'=>'web']);
        
//         $role_admin=Role::firstOrCreate(['name'=>'admin',
//           'guard_name' => 'web',]);



//         $permissions=Permission::pluck('name')->toArray();

//         $role_owner->syncPermissions($permissions);
//         $owner->assignrole($role_owner);
//         $admin->assignrole($role_admin);
//     }


public function run(): void
{
    
  

    // 2️⃣ Roles
    $role_owner = Role::firstOrCreate(['name'=>'owner','guard_name'=>'web']);
    // $role_admin = Role::firstOrCreate(['name'=>'admin','guard_name'=>'web']);
    // $role_user = Role::firstOrCreate(['name'=>'user','guard_name'=>'web']);

    // 3️⃣ ربط الصلاحيات
    $role_owner->syncPermissions(Permission::pluck('name')->toArray());
    // $role_admin->syncPermissions(['حذف صلاحية','تعديل صلاحية','عرض صلاحية']); // أو الصلاحيات اللي عايزها
    //  $role_user->syncPermissions(['حذف صلاحية','تعديل صلاحية','عرض صلاحية']); // أو الصلاحيات اللي عايزها
    // 4️⃣ Users
    $owner = User::firstOrCreate(
        ['email'=>'aboelelaali47@gmail.com'],
        ['name'=>'aboelela ali','password'=>bcrypt('00000000'), 'roles_name'=>'["owner"]','status'=>'مفعل']
    );
    // $admin = User::firstOrCreate(
    //     ['email'=>'maimohamed@gmail.com'],
    //     ['name'=>'mai mohamed','password'=>bcrypt('00000000'), 'roles_name'=>'["admin"]','status'=>'مفعل']
    // );
    //    $user = User::firstOrCreate(
    //     ['email'=>'osos@gmail.com'],
    //     ['name'=>'osos','password'=>bcrypt('00000000'), 'roles_name'=>'["user"]','status'=>'غير مفعل']
    // );

    // 5️⃣ Assign Roles
    $owner->assignRole($role_owner);
    // $admin->assignRole($role_admin);
    // $user->assignRole($role_user);

    // 6️⃣ مسح cache
    // \Artisan::call('permission:cache-reset');
}



}
