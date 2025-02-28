<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Shift;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Models\PengajuanCuti;
use App\Models\RekamKehadiran;
use App\Helpers\TelegramHelper;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $currentUser = auth()->user();
        $todayDate = Carbon::now()->format('Y-m-d');
        $currentEmployee = auth()->user()->karyawan->id;
        $myAbsen = RekamKehadiran::where('karyawan_id', $currentEmployee)
                        ->where('tgl_rekam', $todayDate)
                        ->first();

        if(!$myAbsen){
            $shifts = Shift::where('status', 1)->get();
        }else{
            $shifts = Shift::where('id', $myAbsen->shift_id)->get();
        }
        $data = [
            'currentUser' => $currentUser,
            'shifts' => $shifts,
            'currentDay' => Carbon::now()->format('d F Y'),
            'absen' => $myAbsen
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

    public function getLastCheckin()
    {
        $lastCheckin = RekamKehadiran::latest('updated_at')->take(3)->get()->map(function ($checkin) {
            return [
                'image_uri' => $checkin->karyawan->image_uri,
                'name' => $checkin->karyawan->nama,
                'time' => $checkin->updated_at->diffForHumans(),
            ];
        });

        return response()->json([
            'activities' => $lastCheckin
        ]);
    }

    public function sendTestMessage()
    {
        $response = TelegramHelper::sendMessage("Testing!");
        return response()->json($response);
    }

}
