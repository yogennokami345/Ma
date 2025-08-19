<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardUserController extends Controller
{
    public function index()
    {
        return Inertia::render('User/Dashboard/Show');
    }
}
