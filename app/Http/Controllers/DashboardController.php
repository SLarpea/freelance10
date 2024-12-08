<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'activities' => Activity::select('*')
                ->addSelect(DB::raw('NULL as role'), DB::raw('NULL as email'))
                ->latest()
                ->get()
                ->each(function ($log) {
                    $user = User::findOrFail($log->causer_id);
                    $log->role = implode(" ", $user->roles->pluck('name')->toArray());
                    $log->subject_type = class_basename($log->subject_type);
                    $log->email = $user->email;
                    return $log;
                })
        ];
        return Inertia::render('Dashboard', $data);
    }
}
