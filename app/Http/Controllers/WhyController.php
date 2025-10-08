<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class WhyController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Why');
    }
}
