<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tool;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ToolController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Tool::with('category');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $tools = $query->orderBy('name')->paginate(15);

        $categories = Category::ordered()->get();

        return Inertia::render('Admin/Tools/Index', [
            'tools' => $tools,
            'categories' => $categories,
            'filters' => [
                'search' => $request->get('search', ''),
                'category' => $request->get('category', ''),
                'status' => $request->get('status', ''),
            ],
        ]);
    }

    public function create(): Response
    {
        $categories = Category::active()->ordered()->get();

        return Inertia::render('Admin/Tools/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:tools,slug'],
            'description' => ['required', 'string'],
            'long_description' => ['nullable', 'string'],
            'website_url' => ['nullable', 'url', 'max:500'],
            'documentation_url' => ['nullable', 'url', 'max:500'],
            'logo_url' => ['nullable', 'url', 'max:500'],
            'screenshot_url' => ['nullable', 'url', 'max:500'],
            'screenshot' => ['nullable', 'image', 'max:5120'], // Max 5MB
            'pricing_model' => ['required', 'in:free,freemium,paid,enterprise'],
            'price_description' => ['nullable', 'string'],
            'ryan_rating' => ['nullable', 'integer', 'min:1', 'max:10'],
            'ryan_notes' => ['nullable', 'string'],
            'ryan_last_used' => ['nullable', 'date'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string'],
            'use_cases' => ['nullable', 'array'],
            'use_cases.*' => ['string'],
            'integrations' => ['nullable', 'array'],
            'integrations.*' => ['string'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            'first_reviewed_at' => ['nullable', 'date'],
        ]);

        // Handle screenshot file upload
        if ($request->hasFile('screenshot')) {
            $path = $request->file('screenshot')->store('screenshots', 'public');
            $validated['screenshot_url'] = '/storage/' . $path;
        }

        // Remove the screenshot key as it's not in the database
        unset($validated['screenshot']);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Set defaults
        $validated['is_featured'] = $validated['is_featured'] ?? false;
        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['views_count'] = 0;

        Tool::create($validated);

        return redirect()->route('admin.tools.index')
            ->with('success', 'Tool created successfully.');
    }

    public function edit(Tool $tool): Response
    {
        $tool->load('category');
        $categories = Category::active()->ordered()->get();

        return Inertia::render('Admin/Tools/Edit', [
            'tool' => $tool,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Tool $tool): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('tools')->ignore($tool->id)],
            'description' => ['required', 'string'],
            'long_description' => ['nullable', 'string'],
            'website_url' => ['nullable', 'url', 'max:500'],
            'documentation_url' => ['nullable', 'url', 'max:500'],
            'logo_url' => ['nullable', 'url', 'max:500'],
            'screenshot_url' => ['nullable', 'url', 'max:500'],
            'screenshot' => ['nullable', 'image', 'max:5120'], // Max 5MB
            'pricing_model' => ['required', 'in:free,freemium,paid,enterprise'],
            'price_description' => ['nullable', 'string'],
            'ryan_rating' => ['nullable', 'integer', 'min:1', 'max:10'],
            'ryan_notes' => ['nullable', 'string'],
            'ryan_last_used' => ['nullable', 'date'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string'],
            'use_cases' => ['nullable', 'array'],
            'use_cases.*' => ['string'],
            'integrations' => ['nullable', 'array'],
            'integrations.*' => ['string'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            'first_reviewed_at' => ['nullable', 'date'],
        ]);

        // Handle screenshot file upload
        if ($request->hasFile('screenshot')) {
            // Delete old screenshot if it exists and was uploaded (not external URL)
            if ($tool->screenshot_url && str_starts_with($tool->screenshot_url, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $tool->screenshot_url);
                \Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('screenshot')->store('screenshots', 'public');
            $validated['screenshot_url'] = '/storage/' . $path;
        }

        // Remove the screenshot key as it's not in the database
        unset($validated['screenshot']);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $tool->update($validated);

        return redirect()->route('admin.tools.index')
            ->with('success', 'Tool updated successfully.');
    }

    public function destroy(Tool $tool): RedirectResponse
    {
        $tool->delete();

        return redirect()->route('admin.tools.index')
            ->with('success', 'Tool deleted successfully.');
    }
}
