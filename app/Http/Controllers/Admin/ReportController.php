<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Food;
use App\Models\Donor;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Bar chart: food per hari dalam seminggu terakhir
        $days = collect(range(6, 0))->map(fn($i) => Carbon::now()->subDays($i));
        $foodDist = [
            'labels' => $days->map(fn($d) => $d->format('D'))->toArray(),
            'data'   => $days->map(fn($d) => Food::whereDate('created_at', $d)->count())->toArray(),
        ];

        // Donut: delivery status
        $total = Delivery::count() ?: 1;
        $completed  = Delivery::where('status','completed')->count();
        $delivered  = Delivery::where('status','delivered')->count();
        $onDelivery = Delivery::where('status','on_delivery')->count();
        $deliveryStats = [
            'completed'       => $completed,
            'delivered'       => $delivered,
            'on_delivery'     => $onDelivery,
            'completed_pct'   => round($completed  / $total * 100),
            'delivered_pct'   => round($delivered  / $total * 100),
            'on_delivery_pct' => round($onDelivery / $total * 100),
        ];

        // Line: donor growth per bulan (4 bulan terakhir)
        $months = collect(range(3, 0))->map(fn($i) => Carbon::now()->subMonths($i));
        $donorGrowth = [
            'labels' => $months->map(fn($m) => $m->format('M'))->toArray(),
            'data'   => $months->map(fn($m) => Donor::whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count())->toArray(),
        ];

        // Line: delivery trend per bulan
        $deliveryTrend = [
            'labels' => $months->map(fn($m) => $m->format('M'))->toArray(),
            'data'   => $months->map(fn($m) => Delivery::whereYear('created_at', $m->year)->whereMonth('created_at', $m->month)->count())->toArray(),
        ];

        return view('admin.reports.index', compact('foodDist','deliveryStats','donorGrowth','deliveryTrend'));
    }
}
