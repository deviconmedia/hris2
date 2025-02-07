<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Shift;
use Illuminate\Http\Request;
use App\Models\RekamKehadiran;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\RekamKehadiranStoreRequest;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class RekamKehadiranController extends Controller
{
    public function index()
    {
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
            'shifts' => $shifts,
            'currentDay' => Carbon::now()->format('d F Y'),
            'absen' => $myAbsen
        ];
        return view('adminpanel.presensi.rekam', compact('data'));
    }

    public function myAttendances()
    {
        return view('adminpanel.presensi.manage');
    }

    public function getData()
    {
        $presensi = RekamKehadiran::where('karyawan_id', auth()->user()->karyawan->id)->get();
        return DataTables::of($presensi)
            ->addIndexColumn()
            ->addColumn('opsi', function ($presensi) {
                $editUrl = route('presensi.show', $presensi->id);
                return '
                    <a href="' . $editUrl . '" class="btn btn-outline-primary btn-sm"><i class="bi bi-info-circle"></i> Detail</a>
                ';
            })
            ->addColumn('nama_shift', function($presensi){
                return $presensi->shift->nama_shift ?? 'Tidak Diketahui';
            })
            ->rawColumns(['opsi'])
            ->make(true);
    }

    public function show($id)
    {
        $presensi = RekamKehadiran::findOrFail($id);
        return view('adminpanel.presensi.detail', compact('presensi'));
    }

    public function store(RekamKehadiranStoreRequest $request)
    {
        try {
            $validated = $request->validated();

            if(is_null($validated['latitude']) || is_null($validated['longitude'])){
                return response()->json([
                    'success' => false,
                    'message' => 'Lokasi tidak ditemukan',
                ], 200);
            }

            //cek tipe absensi
            $shift = Shift::where('id', $validated['shift_id'])->first();

            if(!$shift){
                return response()->json([
                    'success' => false,
                    'message' => 'Shift tidak ditemukan',
                ], 200);
            }


            $clockNow = strtotime(Carbon::now()->format('H:i:s'));
            $today  = Carbon::now()->format('Y-m-d');
            $employeeId = auth()->user()->karyawan->id;
            $lokasi = $validated['latitude'] . "," . $validated['longitude'];

            if($clockNow < strtotime($shift->jam_mulai)){
                return response()->json([
                    'success' => false,
                    'message' => "Gagal merekam data! Coba lagi pada Pkl.  $shift->jam_mulai",
                ], 200);
            }elseif($clockNow >= strtotime($shift->jam_mulai) && $clockNow <= strtotime($shift->jam_batas_mulai)){
                //cek ketersediaan presensi
                $dataExists = RekamKehadiran::where('karyawan_id', $employeeId)
                            ->where('tgl_rekam', $today)
                            ->exists();
                if($dataExists){
                    return response()->json([
                        'success' => true,
                        'message'   => 'Anda sudah melakukan presensi!'
                    ], 200);
                }else{
                    RekamKehadiran::create([
                        'karyawan_id'   => $employeeId,
                        'shift_id'      => $validated['shift_id'],
                        'tgl_rekam'     => $today,
                        'jam_masuk'     => Carbon::now()->format('H:i:s'),
                        'lokasi'        => $lokasi,
                        'status'        => 'hadir'
                    ]);

                    return response()->json([
                        'success' => true,
                        'message'   => 'Presensi Anda berhasil direkam'
                    ], 200);
                }
            }elseif($clockNow >= strtotime($shift->jam_batas_mulai) && $clockNow < strtotime($shift->jam_selesai)){
                $dataExists = RekamKehadiran::where('karyawan_id', $employeeId)
                            ->where('tgl_rekam', $today)
                            ->exists();
                if($dataExists){
                   $absen = RekamKehadiran::where('karyawan_id', $employeeId)->where('tgl_rekam', $today)->first();
                   if($absen->jam_pulang != null){
                        return response()->json([
                            'success' => false,
                            'message'   => 'Anda sudah melakukan presensi!'
                    ]);
                   }
                //    $absen->update([
                //         'jam_pulang' => Carbon::now()->format('H:i:s'),
                //         'status'    => 'pulang cepat'
                //    ]);

                   return response()->json([
                        'success' => false,
                        'message'   => "Gagal merekam data. Coba lagi pada Pkl. $shift->jam_selesai"
                   ]);

                }else{
                    RekamKehadiran::create([
                        'karyawan_id' => $employeeId,
                        'shift_id' => $validated['shift_id'],
                        'tgl_rekam' => $today,
                        'jam_masuk' => Carbon::now()->format('H:i:s'),
                        'lokasi'    => $lokasi,
                        'status'    => 'terlambat'
                    ]);

                    return response()->json([
                        'success' => true,
                        'message'   => 'Presensi Anda berhasil direkam'
                    ], 200);
                }
            }elseif($clockNow >= strtotime($shift->jam_selesai)){
                //Jam pulang
                $absen = RekamKehadiran::where('karyawan_id', $employeeId)->where('tgl_rekam', $today)->first();
                if($absen){
                    $absen->update([
                        'jam_pulang'    => Carbon::now()->format('H:i:s'),
                    ]);

                    return response()->json([
                        'success'   => true,
                        'message'   => 'Presensi Anda berhasil direkam'
                    ]);
                }else{
                    return response()->json([
                        'success'   => false,
                        'message'   => 'Gagal merekam data atau sesi telah berakhir'
                    ]);
                }
            }

        } catch (\Throwable $th) {
           return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $th->getMessage(),
           ], 500);
        }

    }

}
