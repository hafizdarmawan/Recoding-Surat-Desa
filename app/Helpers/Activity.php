<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ActivityLog;

class Activity
{
    public static function add(Request $request, $data)
    {
        $log = new ActivityLog();

        if (auth('admin')->check()) {
            $log->user_type = 'admin';
            $log->user_id   = auth('admin')->user()->id;
        } else {
            $log->user_type = 'user';
            $log->user_id   = auth()->user()->id;
        }

        $log->page          = $data['page'];
        $log->description   = $data['description'];
        $log->method        = $request->method();
        $log->ip            = $request->ip();
        $log->agent         = $request->header('user-agent');
        $log->save();
    }
}
