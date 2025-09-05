<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Models\Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaffController extends Controller
{
    public function show(Staff $staff)
    {
        return view('frontend.staff.waiter.show', compact('staff'));
    }
}
