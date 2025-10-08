<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class BrandController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Brand');
    }
}
