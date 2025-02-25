<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\RekamKehadiran;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class LaporanKehadiranController extends Controller
{
    public function index()
    {
        return view('adminpanel.laporan.kehadiran.index');
    }

    public function getData()
    {
        $presensi = RekamKehadiran::latest()->get();
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
