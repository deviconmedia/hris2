<?php

namespace App\Http\Controllers\Admin;

use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ChangeProfileImageRequest;
use App\Http\Requests\KaryawanStoreRequest;
use App\Http\Requests\KaryawanUpdateRequest;
use App\Models\User;
use App\ResponseMessages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class KaryawanController extends Controller
{
    public function index()
    {
        return view('adminpanel.karyawan.manage');
    }

    public function getData()
    {
        $staff = Karyawan::all();

        return DataTables::of($staff)
            ->addIndexColumn()
            ->addColumn('opsi', function ($staff) {
                $showUrl = route('karyawan.show', $staff->id);
                return '
                    <a href="' . $showUrl . '" class="btn btn-outline-primary btn-sm"><i class="bi bi-info-circle"></i> Detail</a>
                ';
            })
            ->rawColumns(['opsi'])
            ->make(true);
    }

    public function create()
    {
        $data = [
            'divisi' => Divisi::where('status', 1)->get(),
            'jabatan' => Jabatan::where('status', 1)->get(),
        ];
        return view('adminpanel.karyawan.form_create', compact('data'));
    }

    public function checkNik(Request $request)
    {
        $request->validate([
            'nik' => 'required|numeric',
        ]);

        $nik = $request->input('nik');

        $exists = Karyawan::where('nik', $nik)->exists();

        return response()->json(
            [
                'exists' => $exists,
            ],
            200,
        );
    }

    public function checkPhoneNumber(Request $request)
    {
        $request->validate([
            'telepon' => 'required|numeric',
        ]);

        $telepon = $request->input('telepon');

        $exists = Karyawan::where('telepon', $telepon)->exists();

        return response()->json(
            [
                'exists' => $exists,
            ],
            200,
        );
    }

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        $exists = Karyawan::where('email', $email)->exists();

        return response()->json(
            [
                'exists' => $exists,
            ],
            200,
        );
    }

    private function generateEmployeeCode()
    {
        $code = date('dmY') . rand(1000, 9999);
        $exists = Karyawan::where('kode', $code)->exists();
        if ($exists == true) {
            $this->generateEmployeeCode();
        }
        return $code;
    }


    public function store(KaryawanStoreRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['kode'] = $this->generateEmployeeCode();
            $name = urlencode($validated['nama']);
            $validated['image_uri'] = "https://ui-avatars.com/api/?background=random&name={$name}";

            DB::transaction(function () use ($validated) {
                $employee = Karyawan::create($validated);

                $user = User::create([
                    'name' => $validated['nama'],
                    'email' => $validated['email'],
                    'phone' => $validated['telepon'],
                    'password' => bcrypt('iconmedia#' . date('Y')),
                    'image_uri' => $validated['image_uri'],
                    'role_id' => 3, //Staff Role Id
                    'karyawan_id' => $employee->id,
                ]);

                //Send user profile information to WhatsApp
            });

            return response()->json(
                [
                    'success' => true,
                    'message' => ResponseMessages::TambahDataBerhasil,
                ],
                200,
            );
        } catch (ValidationException $e) {
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
        $karyawan = Karyawan::findOrFail($id);
        return view('adminpanel.karyawan.karyawan', compact('karyawan'));
    }

    public function changeProfileImage(ChangeProfileImageRequest $request, $id)
    {
        try {
            $validated = $request->validated();

            if ($request->hasFile('image_uri')) {
                $image = $request->file('image_uri');
                $fileName = time() . rand(1000, 9999) . '_' . $image->getClientOriginalName();
                $destinationPath = public_path('uploads/images/employee_images');

                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $image->move($destinationPath, $fileName);
                $data['image_uri'] = 'uploads/images/employee_images/' . $fileName;
            }

            $employee = Karyawan::where('id', $id)->first();
            $employee->update(['image_uri' => $data['image_uri']]);
            $user = User::where('karyawan_id', $id)->first();
            $user->update(['image_uri' => $data['image_uri']]);

            return to_route('karyawan.show', $id)->withSuccess('Foto profil berhasil diubah');

        } catch (\Throwable $th) {
            return to_route('karyawan.show', $id)->withError('Terjadi kesalahan saat mengubah foto profil: ' . $th->getMessage());
        }

    }

    public function edit($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $data = [
            'divisi' => Divisi::where('status', 1)->get(),
            'jabatan' => Jabatan::where('status', 1)->get(),
        ];
        return view('adminpanel.karyawan.form_edit', compact('karyawan', 'data'));
    }

    public function update(KaryawanUpdateRequest $request, $id)
    {
        try {
            $validated = $request->validated();

            DB::transaction(function () use ($validated, $id) {
                $employee = Karyawan::findOrFail($id);
                $employee->update($validated);

                $user = User::where('karyawan_id', $id)->first();
                if($user){
                    $user->update([
                        'name' => $validated['nama'],
                        'email' => $validated['email'],
                        'phone' => $validated['telepon'],
                    ]);
                }
            });

            return response()->json(
                [
                    'success' => true,
                    'message' => ResponseMessages::UpdateBerhasil,
                ],
                200,
            );
        } catch (ValidationException $e) {
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
                    'message' => ResponseMessages::UpdateGagal . $th->getMessage(),
                ],
                500,
            );
        }
    }

    public function changePassword(ChangePasswordRequest $request, $karyawanId)
    {
        try {
            $validated = $request->validated();

            $user = User::where('karyawan_id', $karyawanId)->first();
            $user->update([
                'password' => bcrypt($validated['password']),
            ]);

            return response()->json(
                [
                    'success' => true,
                    'message' => ResponseMessages::UpdatePasswordBerhasil,
                ],
                200,
            );
        } catch (ValidationException $e) {
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
                    'message' => ResponseMessages::UpdatePasswordGagal . $th->getMessage(),
                ],
                500,
            );
        }
    }

    public function changeStatus($id)
    {
        try {
            $employee = Karyawan::findOrFail($id);
            $employee->update([
                'status' => $employee->status == 1 ? 0 : 1,
            ]);

            //ubah status user
            $user = User::where('karyawan_id', $id)->first();
            $user->update([
                'is_active' => $employee->status,
            ]);

            return response()->json(
                [
                    'success' => true,
                    'message' => ResponseMessages::UpdateStatusBerhasil,
                ],
                200,
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => ResponseMessages::UpdateStatusGagal . $th->getMessage(),
                ],
                500,
            );
        }
    }

    public function destroy($id)
    {
        return view('admin.karyawan.index');
    }
}
