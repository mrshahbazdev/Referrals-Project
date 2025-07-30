<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $latestAnnouncement = Announcement::where('is_active', true)->latest()->first();
        return view('home', compact('latestAnnouncement'));
    }
}
