<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\invoices_attachments;
use App\Models\invoices_details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoicesDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        // $invoices_details=invoices_details::where('invoice_id',$id)->get();
        $invoices=invoices::where('id',$id)->first();
        $details=invoices_details::where('invoice_id',$id)->get();
        $attachments=invoices_attachments::where('invoice_id',$id)->get();
        return view("invoices.details_invoices",compact('invoices','details','attachments'));
        // return view("tabs");
        // return$id;
        // echo"jn";
    

       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoices_details $invoices_details)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices_details $invoices_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
       $id=$request->id_file;
       $attachments=invoices_attachments::findorfail($id);
       $attachments->delete();
       $path=$request->invoice_number.'/'.$request->filename;
       if (Storage::disk('public_uploads')->exists($path)) {
       storage::disk('public_uploads')->delete($path);


       return back()->with('delete','تم حذف المرفق بنجاح');
       }
    }

    public function View_file($invoice_number,$filename){
        $path=$invoice_number.'/'.$filename;
         if(!storage::disk('public_uploads')->exists($path)){
             abort(404);
         }
         $files=storage::disk('public_uploads')->path($path);
        

         return response()->file($files);

    }




    public function download($invoice_number,$filename){
        $path=$invoice_number.'/'.$filename;
        if(!storage::disk('public_uploads')->exists($path)){
            abort(404);
        }
        $files=storage::disk('public_uploads')->path($path);
        return response()->download($files);
    }

}
