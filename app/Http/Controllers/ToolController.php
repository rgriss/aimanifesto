<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ToolController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Tool::with('category')
            ->active();

        // Filter by category if provided
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter by pricing model if provided
        if ($request->has('pricing')) {
            $query->where('pricing_model', $request->pricing);
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort options
        $sort = $request->get('sort', 'name');
        match ($sort) {
            'rating' => $query->highestRated(),
            'views' => $query->orderByDesc('views_count'),
            'recent' => $query->latest('first_reviewed_at'),
            default => $query->orderBy('name'),
        };

        $tools = $query->paginate(12);

        return Inertia::render('Tools/Index', [
            'tools' => $tools,
            'filters' => $request->only(['category', 'pricing', 'search', 'sort']),
        ]);
    }

    public function show(Tool $tool): Response
    {
        $tool->load('category');
        $tool->incrementViews();

        // Get related tools from same category
        $relatedTools = Tool::active()
            ->inCategory($tool->category_id)
            ->where('id', '!=', $tool->id)
            ->limit(3)
            ->get();

        return Inertia::render('Tools/Show', [
            'tool' => $tool,
            'relatedTools' => $relatedTools,
        ]);
    }
}