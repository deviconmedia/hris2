<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RekamKehadiranController extends Controller
{
    public function index()
    {
        $data = [
            'currentDay' => Carbon::now()->format('d F Y'),
        ];
        return view('adminpanel.presensi.rekam', compact('data'));
    }
}
