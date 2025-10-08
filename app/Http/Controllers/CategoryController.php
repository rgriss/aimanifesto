<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(): Response
    {
        $categories = Category::active()
            ->ordered()
            ->withCount('activeTools')
            ->get();

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
        ]);
    }

    public function show(Category $category): Response
    {
        $category->load(['activeTools' => function ($query) {
            $query->highestRated();
        }]);

        $toolCount = $category->activeTools->count();

        return Inertia::render('Categories/Show', [
            'category' => $category,
            'toolCount' => $toolCount,
        ]);
    }
}