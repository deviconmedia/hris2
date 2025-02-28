<?php

namespace App\Http\Controllers\Admin;

use App\Models\JenisCuti;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\JenisCutiStoreRequest;
use App\Http\Requests\JenisCutiUpdateRequest;
use App\ResponseMessages;
use Illuminate\Validation\ValidationException;

class JenisCutiController extends Controller
{
    public function index()
    {
        return view('adminpanel.cuti.jenis_cuti.manage');
    }

    public function getData()
    {
        $jenisCuti = JenisCuti::all();

        return DataTables::of($jenisCuti)
            ->addIndexColumn()
            ->addColumn('opsi', function ($jenisCuti) {
                $editUrl = route('jenis_cuti.edit', $jenisCuti->id);
                return '
                    <a href="' . $editUrl . '" class="btn btn-outline-primary btn-sm">Edit</a>
                    <button class="btn btn-outline-danger btn-sm" onclick="deleteData(' . $jenisCuti->id . ')">Hapus</button>
                ';
            })
            ->rawColumns(['opsi'])
            ->make(true);
    }

    public function create()
    {
        return view('adminpanel.cuti.jenis_cuti.create');
    }

    public function store(JenisCutiStoreRequest $request)
    {
        try {
            $validated = $request->validated();

            $save = JenisCuti::create($validated);

            return response()->json([
                'success' => true,
                'message' => ResponseMessages::TambahDataBerhasil
            ], 200);

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

    public function edit($id)
    {
        $jenisCuti = JenisCuti::findOrFail($id);
        return view('adminpanel.cuti.jenis_cuti.edit', compact('jenisCuti'));
    }


    public function update(JenisCutiUpdateRequest $request, $id)
    {
        try {
            $validated = $request->validated();

            $update = JenisCuti::findOrFail($id)->update($validated);
            return response()->json([
                'success' => true,
                'message' => ResponseMessages::UpdateBerhasil
            ], 200);
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

    public function destroy($id)
    {
        try {
            $delete = JenisCuti::findOrFail($id)->delete();
            return response()->json([
                'success' => true,
                'message' => ResponseMessages::DeleteBerhasil
            ], 200);

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
