<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SupportContactMail;

class SupportController extends Controller
{
    public function index()
    {
        return view('public.support');
    }
}
