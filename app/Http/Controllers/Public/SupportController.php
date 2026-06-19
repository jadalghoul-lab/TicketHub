<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class SupportController extends Controller
{
    public function index()
    {
        return view('public.support');
    }
}
