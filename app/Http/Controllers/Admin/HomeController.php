<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Shift;
use Illuminate\Http\Request;
use App\Models\RekamKehadiran;
use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\PengajuanCuti;

class HomeController extends Controller
{
    public function index()
    {
        $currentUser = auth()->user();
        $todayDate = Carbon::now()->format('Y-m-d');
        $currentEmployee = auth()->user()->karyawan->id;
        $data['absen'] = RekamKehadiran::where('karyawan_id', $currentEmployee)
                        ->where('tgl_rekam', $todayDate)
                        ->first();

        if(!$data['absen']){
            $shifts = Shift::where('status', 1)->get();
        }else{
            $shifts = Shift::where('id', $data['absen']['shift_id'])->get();
        }

        $data = [
            'currentUser' => $currentUser,
            'shifts' => $shifts,
            'currentDay' => Carbon::now()->format('d F Y'),
        ];
        return view('adminpanel.home', compact('data'));
    }

    public function countData()
    {
        $today = Carbon::now()->format('Y-m-d');
        $data = [
            'staffs' => Karyawan::where('status', 1)->count(),
            'checkins' => RekamKehadiran::where('tgl_rekam', $today)->count(),
            'cutis' => PengajuanCuti::where('status', 'disetujui')->count(),
            'totalCutis' => PengajuanCuti::count()
        ];

        return response()->json([
            'staffs' => $data['staffs'],
            'checkins' => $data['checkins'],
            'cutis' => $data['cutis'],
            'totalCutis' => $data['totalCutis'],
        ]);
    }

}
