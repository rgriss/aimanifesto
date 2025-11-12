<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tool;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $featuredTools = Tool::with('category')
            ->active()
            ->featured()
            ->highestRated()
            ->limit(6)
            ->get();

        $categories = Category::active()
            ->ordered()
            ->withCount('activeTools')
            ->get();

        $totalToolCount = Tool::active()->count();

        return Inertia::render('Home', [
            'featuredTools' => $featuredTools,
            'categories' => $categories,
            'totalToolCount' => $totalToolCount,
        ]);
    }
}