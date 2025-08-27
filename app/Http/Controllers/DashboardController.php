<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Kirim user ke view
        return view('dashboard', [
            'user' => Auth::user()
        ]);
    }
}
