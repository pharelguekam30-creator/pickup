<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Avis;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::latest()->take(5)->get();
        $avis = Avis::latest()->take(5)->get();
        return view('home', compact('services', 'avis'));
    }
}
