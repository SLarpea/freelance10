<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PulseConroller extends Controller
{
    public function index()
    {
        $data = [
            'appURL' => config('app.url'),
        ];
        return Inertia::render('Module/Pulse', $data);
    }
}
