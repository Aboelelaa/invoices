<?php

namespace App\Http\Controllers;

use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections=sections::all();
        return view('sections.sections',compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $validatedate=$request->validate([

            'section_name'=>'required|unique:sections|max:255',
            'description'=>'required'
        ],[
            'section_name.required'=>'يرجي ادخال اسم القسم',
            'section_name.unique'=>'اسم القسم مسجل مسبقا',
            'description.required'=>'يرجي ادخال الوصف'
        ]);

        //
        // $input=$request->all();
        //  $exist=sections::where('section_name','=',$input['section_name'])->exists();
        //  if($exist){
        //     session()->flash('Error','خطا القسم مسجل مسبقا');
        //     return Redirect('/sections');
        //  }else{

            sections::create([
                'section_name'=>$request->section_name,
                'description'=>$request->description,
                'created_by'=>(Auth::User()->name)
            
            ]);
            session()->flash('Add','تم اضافة القسم بنجاح');

            return Redirect('/sections');


        //  }
    }

    /**
     * Display the specified resource.
     */
    public function show(sections $sections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(sections $sections)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
               'section_name'=>'unique:sections|max:255',
            'description'=>'required'
        ],[
            'section_name.required'=>'يرجي ادخال اسم القسم',
            'section_name.unique'=>'اسم القسم مسجل مسبقا',
            'description.required'=>'يرجي ادخال الوصف'
        ]);


        $id=$request->id;
        $sections=sections::findOrFail($id);
        $sections->update([
           'section_name'=>$request->section_name,
           'description'=>$request->description
        ]);
         session()->flash('edit','تم تعديل القسم بنجاح');
            return Redirect('/sections');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $id=$request->id;
        $sections=sections::findOrFail($id);
        $sections->delete();
        session()->flash('delete','تم حذف القسم بنجاح');
        return Redirect('/sections');

        

    }

    public function getproducts($id){
        $status=DB::table('products')->where('section_id',$id)->pluck('product_name','id');
        return json_encode($status);
    }


}
