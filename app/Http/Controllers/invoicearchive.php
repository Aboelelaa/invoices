<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use App\Models\invoices_attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class invoicearchive extends Controller
{
    public function index(){
        $invoices=invoices::onlyTrashed()->get();
        return view('invoices.archive_invoices',compact('invoices'));
    
    }


    public function update(Request $request){
       $id=$request->invoice_id;
        $invoices=invoices::withTrashed()->where('id',$id)->restore();

        session()->flash('restore_invoice');
        return redirect('/invoices');
    }


    public function destroy(Request $request){
        $id=$request->invoice_id;
        $invoices=invoices::withTrashed()->where('id',$id)->first();
        $ataachments=invoices_attachments::findorfail($id);
        $path=$ataachments->invoice_number.'/'.$ataachments->file_name;
        if(Storage::disk('public_uploads')->exists($path)){
            Storage::disk('public_uploads')->delete($path);
        }
        $invoices->forceDelete();
        session()->flash('delete_invoice');
        return redirect('/invoices');
    }


}
