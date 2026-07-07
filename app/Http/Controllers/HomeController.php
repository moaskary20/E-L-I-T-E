<?php

namespace App\Http\Controllers;

use App\Models\ClinicSetting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'clinicHours' => ClinicSetting::hoursMap(),
            'conditions' => config('clinic.conditions'),
        ]);
    }

    public function privacy(): View
    {
        return view('privacy');
    }
}
