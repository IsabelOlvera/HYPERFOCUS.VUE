<?php
namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class WeekController extends Controller
{
    public function index()
    {
        return Inertia::render('Week');
    }
}

