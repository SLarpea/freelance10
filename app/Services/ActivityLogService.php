<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class ActivityLogService
{
    public function getData($filters)
    {
        $data = [
            'logs' => Activity::select('*')
                ->addSelect(DB::raw('NULL as role'), DB::raw('NULL as email'))
                ->latest()
                ->paginate($filters['per_page'] ?? 10)
                ->withQueryString()
                ->through(function ($log) {
                    $user = User::findOrFail($log->causer_id);
                    $log->role = implode(" ", $user->roles->pluck('name')->toArray());
                    $log->subject_type = class_basename($log->subject_type);
                    $log->email = $user->email;
                    return $log;
                })
        ];
        return $data;
    }
}