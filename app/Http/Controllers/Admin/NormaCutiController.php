<?php

namespace App\Http\Controllers\Admin;

use App\Models\Karyawan;
use App\Models\JenisCuti;
use App\Models\NormaCuti;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\NormaCutiStoreRequest;
use App\ResponseMessages;

class NormaCutiController extends Controller
{
    public function index()
    {
        $data['staffs'] = Karyawan::where('status', 1)->get(['id', 'nama']);
        $data['jenisCuti'] = JenisCuti::get(['id', 'nama_cuti']);
        return view('adminpanel.cuti.norma_cuti.manage', compact('data'));
    }

    public function getData(Request $request)
    {
        $staffId = $request->input('karyawan_id');
        $currentStaff = auth()->user()->karyawan->id;

        $normaCuti = NormaCuti::query();

        if ($staffId) {
            $normaCuti->where('karyawan_id', $staffId);
        } else {
            $normaCuti->where('karyawan_id', $currentStaff);
        }

        $normaCuti = $normaCuti->get();

        return DataTables::of($normaCuti)
            ->addIndexColumn()
            ->addColumn('jenis_cuti', function($normaCuti){
                return $normaCuti->jenisCuti->nama_cuti ?? 'Tidak Diketahui';
            })
            ->addColumn('cuti_max', function($normaCuti){
                return $normaCuti->jenisCuti->jml_hari ?? 0 ;
            })
            ->make(true);
    }

    public function create()
    {
        $data['staffs'] = Karyawan::where('status', 1)->get(['id', 'nama']);
        $data['jenisCuti'] = JenisCuti::get(['id', 'nama_cuti', 'jml_hari']);

        return view('adminpanel.cuti.norma_cuti.create', compact('data'));
    }

    public function store(NormaCutiStoreRequest $request)
    {
        try {
            $validated = $request->validated();

            $karyawan = Karyawan::where('id', $validated['karyawan_id'])->first();

            foreach ($validated['jenis_cuti_id'] as $jenisCutiId) {
                $jenisCuti = JenisCuti::where('id', $jenisCutiId)->first();
                $jmlHari = $jenisCuti->jml_hari;

                $karyawan->normaCuti()->attach(
                    $jenisCutiId,
                    ['jml_hari' => $jmlHari, 'created_at' => now(), 'updated_at' => now()],
                );
            }

            return response()->json([
                'success' => true,
                'message' => ResponseMessages::TambahDataBerhasil
            ], 200);


        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => ResponseMessages::TambahDataGagal . $th->getMessage()
            ], 500);
        }
    }



}
