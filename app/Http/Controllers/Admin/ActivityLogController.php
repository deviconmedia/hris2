<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
                return json_encode($log->properties, JSON_PRETTY_PRINT);
            })
            ->editColumn('created_at', function ($log) {
                return $log->created_at->format('Y-m-d H:i:s');
            })
            ->rawColumns(['properties'])
            ->make(true);
    }

}
