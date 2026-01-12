<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function change(Request $request)
    {
        $locale = $request->input('locale');
        if (in_array($locale, config('app.allowed_locales'))) {
            session(['locale' => $locale]);
        }
        return redirect()->back();
    }
}
