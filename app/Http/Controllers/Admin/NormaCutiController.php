<?php

namespace App\Http\Controllers\Admin;

use App\Models\NormaCuti;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\JenisCuti;
use App\Models\Karyawan;

class NormaCutiController extends Controller
{
    public function index()
    {
        $data['staffs'] = Karyawan::where('status', 1)->get(['id', 'nama']);
        $data['jenisCuti'] = JenisCuti::get(['id', 'nama_cuti']);
        return view('adminpanel.cuti.norma_cuti.manage', compact('data'));
    }

    public function getData()
    {
        $normaCuti = NormaCuti::all();

        return DataTables::of($normaCuti)
            ->addIndexColumn()
            ->addColumn('opsi', function ($normaCuti) {
                $editUrl = route('jenis_cuti.edit', $normaCuti->id);
                return '
                    <a href="' . $editUrl . '" class="btn btn-warning btn-sm">Edit</a>
                    <button class="btn btn-danger btn-sm" onclick="deleteData(' . $normaCuti->id . ')">Hapus</button>
                ';
            })
            ->addColumn('jenis_cuti', function($normaCuti){
                return $normaCuti->jenisCuti->nama_cuti ?? 'Tidak Diketahui';
            })
            ->addColumn('cuti_max', function($normaCuti){
                return $normaCuti->jenisCuti->jml_hari ?? 0 ;
            })
            ->addColumn('karyawan', function($normaCuti){
                return $normaCuti->karywan->nama ?? 'Tidak Diketahui';
            })
            ->rawColumns(['opsi'])
            ->make(true);
    }

}
