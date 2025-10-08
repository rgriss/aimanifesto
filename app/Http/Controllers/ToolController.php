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

        // Search functionality
        if ($request->filled('search')) {
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
            'filters' => [
                'search' => $request->get('search', ''),
                'sort' => $request->get('sort', 'name'),
            ],
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