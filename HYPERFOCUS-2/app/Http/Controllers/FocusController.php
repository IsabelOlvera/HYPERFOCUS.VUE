<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class FocusController extends Controller
{
    public function index()
    {
        return Inertia::render('Focus');
    }
}

