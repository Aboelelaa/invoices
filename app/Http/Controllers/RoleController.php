<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    //




    function __construct()
            {
        //           $this->middleware('auth');
        //     $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        //     $this->middleware('permission:role-create', ['only' => ['create','store']]);
        //     $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        //     $this->middleware('permission:role-delete', ['only' => ['destroy']]);
        
        $this->middleware('auth');
        $this->middleware('permission:عرض صلاحية',['only'=>['index','show']]);
        $this->middleware('permission:اضافة صلاحية',['only'=>['create','store']]);
        $this->middleware('permission:تعديل صلاحية',['only'=>['edit','update']]);
        $this->middleware('permission:حذف صلاحية',['only'=>['destroy']]);
        

        }


    public function index(Request $request){
        // $roles=User::all();
        $roles=Role::orderby('id','desc')->paginate(5);
        
        return view('roles.index',compact('roles'))->with('i',($request->input('page',1)-1)*5);
        }



        public function show($id)
                {
                $role = Role::findorfail($id);
                $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
                ->where("role_has_permissions.role_id",$id)
                ->get();
                return view('roles.show',compact('role','rolePermissions'));
                }




        public function create()
        {
                $permission = Permission::get();
                return view('roles.create',compact('permission'));
                }


       public function store(Request $request)
                {
                $request->validate( [
                'name' => 'required|unique:roles,name',
                'permission' => 'required',
                ]);
                $role = Role::create(['name' => $request->input('name')]);
                $role->syncPermissions($request->input('permission'));
                return redirect()->route('roles.index')
                ->with('success','Role created successfully');
                }         


         public function edit($id)
                {
                $role = Role::find($id);
                $permission = Permission::get();
                $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                ->all();
                return view('roles.edit',compact('role','permission','rolePermissions'));
                }     


        //   public function update(Request $request, $id)
        //         {
        //         $request->validate( [
        //         'name' => 'required',
        //         'permission' => 'required',
        //         ]);
        //         $role = Role::find($id);
        //         $role->name = $request->input('name');
        //         $role->save();
        //         $role->syncPermissions($request->input('permission'));
        //         return redirect()->route('roles.index')
        //         ->with('success','Role updated successfully');
        //         }

         public function update(Request $request, $id)
                {
                $request->validate( [
                'name' => 'required',
                'permission' => 'required',
                ]);
                $role = Role::find($id);
                $role->name = $request->input('name');
                $role->save();
                $permissions = Permission::whereIn('id', $request->input('permission'))->pluck('name');
                $role->syncPermissions($permissions);

                // $role->syncPermissions($request->input('permission'));
                return redirect()->route('roles.index')
                ->with('success','Role updated successfully');
                }



          public function destroy($id)
                                {
               Role::findorfail($id)->delete();
                return redirect()->route('roles.index')
                ->with('success','Role deleted successfully');
                }
     


 }

        
       
