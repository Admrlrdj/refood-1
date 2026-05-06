<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Delivery;
use App\Models\Donor;
use App\Models\Receiver;
use App\Models\Volunteer;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalFoods'       => Food::count(),
            'totalVolunteers'  => Volunteer::count(),
            'totalDonors'      => Donor::count(),
            'totalReceivers'   => Receiver::count(),
            'recentDeliveries' => Delivery::with(['food','volunteer','receiver'])
                                    ->latest()->paginate(5),
        ]);
    }
}
