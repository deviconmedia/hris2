<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RekamKehadiranController extends Controller
{
    public function index()
    {
        return view('adminpanel.presensi.rekam');
    }
}
