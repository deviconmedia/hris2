<?php

namespace App\Http\Controllers\Admin;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\JabatanStoreRequest;
use App\Http\Requests\JabatanUpdateRequest;
use Illuminate\Validation\ValidationException;

class JabatanController extends Controller
{
    public function index()
    {
        return view('adminpanel.jabatan.manage');
    }

    public function getData()
    {
        $jabatan = Jabatan::all();

        return DataTables::of($jabatan)
            ->addIndexColumn()
            ->addColumn('opsi', function ($jabatan) {
                $editUrl = route('jabatan.edit', $jabatan->id);
                return '
                    <a href="' . $editUrl . '" class="btn btn-outline-primary btn-sm">Edit</a>
                    <button class="btn btn-outline-danger btn-sm" onclick="deleteData(' . $jabatan->id . ')">Hapus</button>
                ';
            })
            ->rawColumns(['opsi'])
            ->make(true);
    }

    public function create()
    {
        return view('adminpanel.jabatan.create');
    }

    public function store(JabatanStoreRequest $request)
    {
        try {
            $validated = $request->validated();

            $save = Jabatan::create($validated);

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Data berhasil disimpan',
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
                    'message' => 'Terjadi kesalahan saat menyimpan data: ' . $th->getMessage(),
                ],
                500,
            );
        }
    }

    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);

        return view('adminpanel.jabatan.edit', compact('jabatan'));
    }

    public function update(JabatanUpdateRequest $request, $id)
    {
        try {
            $validated = $request->validated();

            $update = Jabatan::findOrFail($id)->update($validated);

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Data berhasil diupdate',
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
                    'message' => 'Terjadi kesalahan saat mengupdate data: ' . $th->getMessage(),
                ],
                500,
            );
        }
    }

    public function destroy($id)
    {
        try {
            $delete = Jabatan::findOrFail($id)->delete();
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Data berhasil dihapus',
                ],
                200,
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data: ' . $th->getMessage(),
                ],
                500,
            );
        }
    }

}
