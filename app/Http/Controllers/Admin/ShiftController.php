<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shift;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShiftStoreRequest;
use App\Http\Requests\ShiftUpdateRequest;
use Illuminate\Validation\ValidationException;

class ShiftController extends Controller
{
    public function index()
    {
        return view('adminpanel.shifts.manage');
    }

    public function getData()
    {
        $shifts = Shift::all();

        return DataTables::of($shifts)
            ->addIndexColumn()
            ->addColumn('opsi', function ($shifts) {
                $editUrl = route('shifts.edit', $shifts->id);
                return '
                    <a href="' . $editUrl . '" class="btn btn-warning btn-sm">Edit</a>
                    <button class="btn btn-danger btn-sm" onclick="deleteData(' . $shifts->id . ')">Hapus</button>
                ';
            })
            ->rawColumns(['opsi'])
            ->make(true);
    }

    public function create()
    {
        return view('adminpanel.shifts.create');
    }

    public function store(ShiftStoreRequest $request)
    {
        try {
            $validated = $request->validated();

            $save = Shift::create($validated);

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
        $shift = Shift::findOrFail($id);

        return view('adminpanel.shifts.edit', compact('shift'));
    }

    public function update(ShiftUpdateRequest $request, $id)
    {
        try {
            $validated = $request->validated();

            $update = Shift::findOrFail($id)->update($validated);

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
            $delete = Shift::findOrFail($id)->delete();
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
