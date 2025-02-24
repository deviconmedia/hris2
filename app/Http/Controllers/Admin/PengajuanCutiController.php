<?php

namespace App\Http\Controllers\Admin;

use App\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\PengajuanCutiStoreRequest;
use App\Models\JenisCuti;
use App\Models\Karyawan;
use App\Models\NormaCuti;
use App\Models\PengajuanCuti;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class PengajuanCutiController extends Controller
{
    public function index()
    {
        return view('adminpanel.cuti.pengajuan_cuti.manage');
    }

    public function getData(Request $request)
    {
        $staffId = $request->input('karyawan_id');
        $currentStaff = auth()->user()->karyawan->id;

        $pengajuanCuti = PengajuanCuti::query();

        if ($staffId) {
            $pengajuanCuti->where('karyawan_id', $staffId);
        } else {
            $pengajuanCuti->where(function($query) use ($currentStaff) {
                $query->where('karyawan_id', $currentStaff)
                      ->orWhere('send_to', $currentStaff);
            });
        }

        $pengajuanCuti = $pengajuanCuti->get();

        return DataTables::of($pengajuanCuti)
            ->addIndexColumn()
            ->addColumn('nama_karyawan', function($pengajuanCuti){
                return $pengajuanCuti->karyawan->nama ?? 'Tidak Diketahui';
            })
            ->addColumn('jenis_cuti', function($pengajuanCuti){
                return $pengajuanCuti->jenisCuti->nama_cuti ?? 0 ;
            })
            ->addColumn('tgl_mulai', function($pengajuanCuti){
                return date('d-m-Y', strtotime($pengajuanCuti->tgl_mulai)) ;
            })
            ->addColumn('tgl_selesai', function($pengajuanCuti){
                return date('d-m-Y', strtotime($pengajuanCuti->tgl_selesai)) ;
            })
            ->addColumn('tgl_pengajuan', function($pengajuanCuti){
                return date('d-m-Y', strtotime($pengajuanCuti->tgl_pengajuan)) ;
            })
            ->addColumn('penyetuju', function($pengajuanCuti){
                return $pengajuanCuti->penyetuju->nama ?? 'Tidak Diketahui';
            })
            ->addColumn('opsi', function ($pengajuanCuti) {
                $showUrl = route('pengajuan_cuti.show', $pengajuanCuti->id);
                $opsi = '
                    <div class="dropdown-center">
                        <button class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Opsi
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="' . $showUrl . '"><i class="bi bi-info-circle"></i> Detail</a></li>';

                if ($pengajuanCuti->status == 'menunggu konfirmasi') {
                    $opsi .= '<li><a class="dropdown-item text-danger" href="#" onclick="rejectData(' . $pengajuanCuti->id . ')"><i class="bi bi-x-lg"></i> Tolak</a></li>';
                }

                $opsi .= '<li><a class="dropdown-item text-danger" href="#" onclick="deleteData(' . $pengajuanCuti->id . ')"><i class="bi bi-trash"></i> Hapus</a></li>
                        </ul>
                    </div>
                ';

                return $opsi;
            })
            ->rawColumns(['opsi'])
            ->make(true);
    }

    public function create()
    {
        $data['currentStaffId'] = auth()->user()->karyawan->id;
        $data['normaCuti'] = NormaCuti::where('karyawan_id', $data['currentStaffId'])->get();
        $data['staffs'] = Karyawan::where('status', 1)->get(['id', 'nama']);

        return view('adminpanel.cuti.pengajuan_cuti.create', compact('data'));
    }

    public function store(PengajuanCutiStoreRequest $request)
    {
        try {
            $validated = $request->validated();

            //Cek tanggal pengajuan
            $tglMulai = $validated['tgl_mulai'];
            $today = Carbon::now()->format('Y-m-d');

            if($tglMulai <= $today){
                return response()->json(
                    [
                        'success' => false,
                        'message' => "Tanggal pengajuan maksimal H-1 sebelum tanggal mulai cuti",
                    ],
                    422,
                );
            }

            //Hitung jumlah hari yang diajukan
            $jmlHari = Carbon::parse($validated['tgl_mulai'])->diffInDays(Carbon::parse($validated['tgl_selesai'])) + 1;
            //Cek sisa cuti
            $normaCuti = NormaCuti::where('karyawan_id', $validated['karyawan_id'])
                        ->where('jenis_cuti_id', $validated['jenis_cuti_id'])
                        ->first();

            $sisaCuti = $normaCuti->jml_hari;

            if($jmlHari > $sisaCuti){
                return response()->json(
                    [
                        'success' => false,
                        'message' => "Sisa cuti Anda tidak mencukupi.",
                    ],
                    422,
                );
            }

            //Cek file lampiran
            if($request->hasFile('lampiran')){
                $image = $request->file('lampiran');
                $fileName = time() . rand(1000, 9999) . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('uploads/cuti/files');

                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $image->move($destinationPath, $fileName);
                $validated['lampiran'] = 'uploads/cuti/files/' . $fileName;
            }
            $validated['tgl_pengajuan'] = Carbon::now()->format('Y-m-d');

            $save = PengajuanCuti::create($validated);

            return response()->json(
                [
                    'success' => true,
                    'message' => ResponseMessages::TambahDataBerhasil,
                ],
                200,
            );


        } catch(ValidationException $e)
        {
            return response()->json(
                [
                    'success' => false,
                    'errors' => $e->errors(),
                ],
                422,
            );

        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => ResponseMessages::TambahDataGagal . $th->getMessage(),
                ],
                500,
            );
        }
    }

    public function show($id)
    {
        $data = PengajuanCuti::findOrFail($id);

        return view('adminpanel.cuti.pengajuan_cuti.show', compact('data'));
    }

    public function approvedCuti($id)
    {
        try {
            $data = PengajuanCuti::findOrFail($id);
            $jmlHari = Carbon::parse($data->tgl_mulai)->diffInDays(Carbon::parse($data->tgl_selesai)) + 1;
            $normaCuti = NormaCuti::where('karyawan_id', $data->karyawan_id)
            ->where('jenis_cuti_id', $data->jenis_cuti_id)
            ->first();

            $sisaHari = $normaCuti->jml_hari - $jmlHari;
            $normaCuti->update(['jml_hari' => $sisaHari]);
            $pengajuanCuti = $data->update([
                'status' => 'disetujui',
                'approved_at' => Carbon::now()->format('Y-m-d, H:i:s'),
                'approved_by' => auth()->user()->karyawan->id,
            ]);

            return response()->json(
                [
                    'success' => true,
                    'message' => ResponseMessages::UpdateBerhasil,
                ],
                200,
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => ResponseMessages::UpdateGagal . $th->getMessage(),
                ],
                500,
            );
        }
    }

    public function rejectedCuti($id)
    {
        try {
           $pengajuanCuti = PengajuanCuti::findOrFail($id)->update(['status' => 'ditolak']);

            return response()->json(
                [
                    'success' => true,
                    'message' => ResponseMessages::UpdateBerhasil,
                ],
                200,
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => ResponseMessages::UpdateGagal . $th->getMessage(),
                ],
                500,
            );
        }
    }

    public function destroy($id)
    {
        try {
            $delete = PengajuanCuti::findOrFail($id)->delete();

            return response()->json(
                [
                    'success' => true,
                    'message' => ResponseMessages::DeleteBerhasil,
                ],
                200,
            );

        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => ResponseMessages::DeleteGagal . $th->getMessage(),
                ],
                500,
            );
        }

    }

}
