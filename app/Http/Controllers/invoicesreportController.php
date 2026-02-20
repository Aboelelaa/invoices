<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use Illuminate\Http\Request;
use Carbon\Carbon;
class invoicesreportController extends Controller
{
    //
    public function index(){
        return view('reports.invoices_report');
    }

    public function Search_invoices(Request $request){
        $rdio=$request->rdio;

        // في حالة عدم تحديد تاريخ
        if($rdio==1){
            if($request->type&&$request->start_at==''&&$request->end_at==''){
                 $invoices=invoices::select('*')->where('status','=',$request->type)->get();
                $type=$request->type;
                return view('reports.invoices_report',compact('type','invoices'));
            }else{
            // في حالة تحديد تاريخ
            $start_at=Carbon::parse($request->start_at)->startOfDay();
            $end_at= Carbon::parse($request->end_at)->endOfDay();
            $type=$request->type;
            $invoices=invoices::wherebetween('invoice_date',[$start_at,$end_at])->where('Status','=',$request->type)->get();
            return view('reports.invoices_report',compact('type','invoices','start_at','end_at'));
        }
        }else{

            $invoices=invoices::select('*')->where('invoice_number','=',$request->invoice_number)->get();
            return view('reports.invoices_report',compact('invoices'));
            
            //في حالة البحث برقم الفاتورة
            
            }

    }
}
