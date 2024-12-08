<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocalizationController extends Controller
{
    public function setLocale(Request $request)
    {
        if ($request->has('locale')) {
            $locale = $request->locale;
            app()->setLocale($locale);
            session(['locale' => app()->getLocale()]);
        }
    }
}
