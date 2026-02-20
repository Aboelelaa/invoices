<?php

namespace App\Http\Controllers;

use App\Models\invoices;
use Illuminate\Http\Request;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class DashboardController extends Controller
{
    public function index()
    {
    
        $labels_bar = ['إجمالي الفواتير','الفواتير المدفوعة','الفواتير الغير مدفوعة','الفواتير المدفوعة جزئيًا'];
        $labels_pie = ['الفواتير المدفوعة','الفواتير الغير مدفوعة','الفواتير المدفوعة جزئيًا'];
    



    $totalInvoices=invoices::count();
    $paidInvoices=invoices::where('value_status',1)->count();
    $unpaidInvoices=invoices::where('value_status',2)->count();
    // $unpaidInvoices = invoices::withTrashed()
    // ->where('value_status',2)
    // ->count();
    // $unpaidInvoices = invoices::onlyTrashed()
    // ->where('value_status',2)
    // ->restore();

    $partialInvoices=invoices::where('value_status',3)->count();
    $unpaidTotal= invoices::where('value_status',2)->sum('total');
    $paidPercentage = $totalInvoices ? round(($paidInvoices / $totalInvoices) * 100 ) : 0 ;
    $unpaidPercentage = $totalInvoices ? round(($unpaidInvoices / $totalInvoices) * 100) : 0;
    $partialPercentage = $totalInvoices ? round(($partialInvoices / $totalInvoices) * 100) : 0;


    $chart = new Chart;
    $chart->labels($labels_bar);

    $chart->dataset('نسب الفواتير', 'bar', [
        100, 
        $paidPercentage, 
        $unpaidPercentage, 
        $partialPercentage
    ])
    ->backgroundColor([
        'rgba(54, 162, 235, 0.5)',
        'rgba(75, 192, 192, 0.5)',
        'rgba(255, 99, 132, 0.5)',
        'rgba(255, 206, 86, 0.5)'
    ])
    ->color([
        'rgba(54, 162, 235, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(255, 99, 132, 1)',
        'rgba(255, 206, 86, 1)'
    ]);




     $charts = new Chart;
    $charts->labels($labels_pie);

    $charts->dataset('نسب الفواتير', 'pie', [
         
        $paidPercentage, 
        $unpaidPercentage, 
        $partialPercentage
    ])
    ->backgroundColor([
      
        'rgba(75, 192, 192, 0.5)',
        'rgba(255, 99, 132, 0.5)',
        'rgba(255, 206, 86, 0.5)'
    ])
    ->color([
     
        'rgba(75, 192, 192, 1)',
        'rgba(255, 99, 132, 1)',
        'rgba(255, 206, 86, 1)'
    ]);






    return view('dashboard', compact('chart', 'charts','totalInvoices','paidInvoices','unpaidInvoices','partialInvoices','paidPercentage','unpaidPercentage','partialPercentage'));;
    }
}
