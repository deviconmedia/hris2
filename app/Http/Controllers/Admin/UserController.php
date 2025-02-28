<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(): View
    {
        return view('adminpanel.users.manage');
    }

    public function getData()
    {
        $currentUserLogged = auth()->user()->id;
        $users = User::all();

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('role_name', function($users){
                return $users->role->role_name ?? 'Tidak Diketahui';
            })
            ->addColumn('is_active', function ($users) use ($currentUserLogged) {
                $checked = $users->is_active ? 'checked' : '';
                $status = $users->is_active ? 'Aktif' : 'Tidak Aktif';
                $disabled = $users->id == $currentUserLogged ? 'disabled' : '';
                return '
                    <div class="custom-control custom-switch">
                        <input class="form-check-input toggle-active" type="checkbox" data-id="' . $users->id . '" ' . $checked . ' ' . $disabled . '>
                        ' . $status . '
                    </div>
                ';
            })
            ->rawColumns(['is_active'])
            ->make(true);
    }

    public function toggleActive(Request $request, User $user)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $user->is_active = $request->is_active;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Status Aktif Pengguna Berhasil Diubah.',
            'is_active' => $user->is_active,
        ]);
    }

}
