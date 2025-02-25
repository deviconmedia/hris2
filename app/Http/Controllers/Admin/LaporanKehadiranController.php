<?php

namespace App\Http\Controllers\Admin;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Models\RekamKehadiran;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class LaporanKehadiranController extends Controller
{
    public function index()
    {
        $data['staffs'] = Karyawan::where('status', 1)->get(['id', 'nama']);

        return view('adminpanel.laporan.kehadiran.index', compact('data'));
    }

    public function getData(Request $request)
    {
        $dateRecord = $request->input('date_record');
        $karyawanId = $request->input('karyawan_id');

        $presensi = RekamKehadiran::latest();

        if ($dateRecord) {
            if ($dateRecord === 'all') {
               $presensi;
            } elseif ($dateRecord === 'today') {
                $presensi->whereDate('tgl_rekam', now()->format('Y-m-d'));
            } elseif ($dateRecord === 'last_7_days') {
                $presensi->whereBetween('tgl_rekam', [now()->subDays(7), now()]);
            } elseif ($dateRecord === 'last_30_days') {
                $presensi->whereBetween('tgl_rekam', [now()->subDays(30), now()]);
            } elseif ($dateRecord === 'this_month') {
                $presensi->whereBetween('tgl_rekam', [now()->startOfMonth(), now()->endOfMonth()]);
            } elseif ($dateRecord === 'last_month') {
                $presensi->whereBetween('tgl_rekam', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()]);
            }
        }

        if ($karyawanId) {
            $presensi->where('karyawan_id', $karyawanId);
        }else{
            $presensi;
        }


        return DataTables::of($presensi)
            ->addIndexColumn()
            ->addColumn('opsi', function ($presensi) {
                $editUrl = route('laporan_kehadiran.show', $presensi->id);
                return '
                    <a href="' . $editUrl . '" class="btn btn-outline-primary btn-sm"><i class="bi bi-info-circle"></i> Detail</a>
                ';
            })
            ->addColumn('nama_shift', function($presensi){
                return $presensi->shift->nama_shift ?? 'Tidak Diketahui';
            })
            ->addColumn('nama_karyawan', function($presensi){
                return $presensi->karyawan->nama ?? 'Tidak Diketahui';
            })
            ->rawColumns(['opsi'])
            ->make(true);
    }

    public function show($id)
    {
        $presensi = RekamKehadiran::findOrFail($id);
        return view('adminpanel.laporan.kehadiran.detail', compact('presensi'));
    }

}
