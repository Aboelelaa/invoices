<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    //
//     public function __construct()
//         {
//             $this->middleware('auth');

//             $this->middleware('permission:view users')->only('index');
//             $this->middleware('permission:add users')->only(['create','store']);
//             $this->middleware('permission:edit users')->only(['edit','update']);
//             $this->middleware('permission:delete users')->only('destroy');
// }

        public function index(Request $request){
            $data=User::orderby('id','desc')->paginate(5);
            // $data=User::all();
            return view('users.index',compact('data'))->with('i',($request->input('page',1)-1)*5);

        }



        public function show($id)
        {
        $user = User::findorfail($id);
        return view('users.show',compact('user'));
        }







        public function create()
        {
        // $roles = Role::pluck('name','name')->all();
        $roles = Role::pluck('name','id')->toarray();
        // dd($roles);
        return view('users.create',compact('roles'));

        }



        public function store(Request $request)
        {
        $request->validate( [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|same:confirm-password',
        'roles_name'=> 'required'
        
        ]);

          $input = $request->except('roles_name');

        $request['status']= $request->has('status') ? 'مفعل' : 'غير مفعل';
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
           $user->assignRole($request->roles_name);


        //    نحفظ الدور كـ نص للعرض فقط
            $user->update([
                'roles_name' => implode(',', $request->roles_name)
            ]);

            // User::create([
            //     'name' => $request->name,
            //     'email' => $request->email,
            //     'password' => Hash::make($request->password),
            //     'status' => $request->status,
            //     'roles_name' => implode(',', $request->roles_name)
            // ]);


        return redirect()->route('users.index')
        ->with('success','تم اضافة المستخدم بنجاح');
        }




        public function edit($id)
        {
        $user = User::findorfail($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        return view('users.edit',compact('user','roles','userRole'));
        
        }


        public function update(Request $request, $id)
        {
        $request->validate( [
        'name' => 'required',
        'email' => 'required|email|unique:users,email,'.$id,
        'password' => 'same:confirm-password',
        'roles' => 'required'
        ]);
       $input = $request->except('roles');
        if(!empty($input['password'])){
        $input['password'] = Hash::make($input['password']);
        }else{
        $input = Arr::except($input, ['password']);
        }
        // $input['status'] = $request->input('status') ? 'مفعل' : 'غير مفعل';
        $input['status'] = $request->status;

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index')
        ->with('success','تم تحديث معلومات المستخدم بنجاح');
        }



        public function destroy(Request $request)
        {
        User::findorfail($request->user_id)->delete();
        return redirect()->route('users.index')->with('success','تم حذف المستخدم بنجاح');
        }


}
