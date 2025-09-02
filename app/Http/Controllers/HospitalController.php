<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::all();
        return view('hospital', compact('hospitals'));
    }

    public function show($slug)
    {
        $hospital = Hospital::where('slug', $slug)->firstOrFail();
        return view('hospital-detail', compact('hospital'));
    }
}
