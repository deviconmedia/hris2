<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ResponseMessages;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\DataTables;



class ActivityLogController extends Controller
{
    public function index()
    {
        return view('adminpanel.logs.index');
    }

    public function getData()
    {
        $logs = Activity::latest();

        return DataTables::of($logs)
            ->addIndexColumn()
            ->addColumn('causer', function ($log) {
                return $log->causer ? $log->causer->name : 'Guest';
            })
            ->addColumn('properties', function ($log) {
                $formattedProperties = json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

                $formattedProperties = str_replace(["\n", "\r"], ' ', $formattedProperties);

                return htmlspecialchars($formattedProperties, ENT_QUOTES, 'UTF-8');
            })
            ->editColumn('created_at', function ($log) {
                return $log->created_at->format('Y-m-d H:i:s');
            })
            ->rawColumns(['properties'])
            ->make(true);
    }

    /**
     * Truncate logs table
     */
    public function truncate()
    {
        try {
            Activity::truncate();

            return response()->json([
               'success' => true,
                'message' => ResponseMessages::DeleteBerhasil
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => ResponseMessages::DeleteGagal
            ], 500);
        }

    }

}
