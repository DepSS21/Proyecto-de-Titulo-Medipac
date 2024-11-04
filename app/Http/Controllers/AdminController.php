<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function showFunciones()
    {
        return view('adminFunciones');
    }
}