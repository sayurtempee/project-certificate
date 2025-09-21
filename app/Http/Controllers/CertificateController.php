<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        return view('certificates.index');
    }
}
