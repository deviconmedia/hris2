<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DivisiStoreRequest;
use App\Http\Requests\DivisiUpdateRequest;
use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\DataTables;

class DivisiController extends Controller
{
    public function index()
    {
        return view('adminpanel.divisi.manage');
    }

    public function getData()
    {
        $divisi = Divisi::all();

        return DataTables::of($divisi)
            ->addIndexColumn()
            ->addColumn('opsi', function ($divisi) {
                $editUrl = route('divisi.edit', $divisi->id);
                return '
                    <a href="' . $editUrl . '" class="btn btn-outline-primary btn-sm">Edit</a>
                    <button class="btn btn-outline-danger btn-sm" onclick="deleteData(' . $divisi->id . ')">Hapus</button>
                ';
            })
            ->rawColumns(['opsi'])
            ->make(true);
    }

    public function create()
    {
        return view('adminpanel.divisi.create');
    }

    public function store(DivisiStoreRequest $request)
    {
        try {
            $validated = $request->validated();

            $save = Divisi::create($validated);

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
        $divisi = Divisi::findOrFail($id);

        return view('adminpanel.divisi.edit', compact('divisi'));
    }

    public function update(DivisiUpdateRequest $request, $id)
    {
        try {
            $validated = $request->validated();

            $update = Divisi::findOrFail($id)->update($validated);

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
            $delete = Divisi::findOrFail($id)->delete();
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
