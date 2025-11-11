<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tool;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ApiToolController extends Controller
{
    /**
     * Display a listing of all tools.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Tool::with('category')->active();

            // Optional filters
            if ($request->has('category')) {
                $query->whereHas('category', function ($q) use ($request) {
                    $q->where('slug', $request->category)
                        ->orWhere('name', $request->category);
                });
            }

            if ($request->has('featured')) {
                $query->featured();
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');

            if ($sortBy === 'rating') {
                $query->highestRated();
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }

            // Pagination
            $perPage = min((int) $request->get('per_page', 15), 100);
            $tools = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $tools->map(fn ($tool) => $this->formatToolResponse($tool)),
                'meta' => [
                    'current_page' => $tools->currentPage(),
                    'last_page' => $tools->lastPage(),
                    'per_page' => $tools->perPage(),
                    'total' => $tools->total(),
                ],
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve tools', $e);
        }
    }

    /**
     * Display the specified tool.
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $tool = Tool::with('category')->where('slug', $slug)->first();

            if (! $tool) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tool not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $this->formatToolResponse($tool, true),
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve tool', $e);
        }
    }

    /**
     * Store a newly created tool in the database.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validated = $this->validateToolRequest($request);

            // Check for duplicate by name
            $existing = Tool::where('name', $validated['name'])->first();
            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'A tool with this name already exists.',
                    'existing_tool' => [
                        'id' => $existing->id,
                        'name' => $existing->name,
                        'slug' => $existing->slug,
                        'url' => route('tools.show', $existing->slug),
                    ],
                ], 409);
            }

            // Find or create category
            $category = $this->findOrCreateCategory($validated['category']);

            // Generate unique slug
            $slug = $this->generateUniqueSlug($validated['name']);

            // Create tool
            $tool = Tool::create([
                'category_id' => $category->id,
                'name' => $validated['name'],
                'slug' => $slug,
                'description' => $validated['description'],
                'long_description' => $validated['long_description'] ?? null,
                'website_url' => $validated['website_url'],
                'documentation_url' => $validated['documentation_url'] ?? null,
                'logo_url' => $validated['logo_url'] ?? null,
                'pricing_model' => $validated['pricing_model'] ?? 'free',
                'price_description' => $validated['price_description'] ?? null,
                'features' => $validated['features'] ?? [],
                'use_cases' => $validated['use_cases'] ?? [],
                'integrations' => $validated['integrations'] ?? [],
                'ryan_rating' => $validated['ryan_rating'] ?? null,
                'ryan_notes' => $validated['ryan_notes'] ?? null,
                'ryan_last_used' => $validated['ryan_last_used'] ?? null,
                'is_featured' => $validated['is_featured'] ?? false,
                'is_active' => $validated['is_active'] ?? true,
                'first_reviewed_at' => isset($validated['ryan_rating']) ? now() : null,
                'views_count' => 0,
            ]);

            $tool->load('category');

            return response()->json([
                'success' => true,
                'message' => 'Tool created successfully',
                'data' => $this->formatToolResponse($tool, true),
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while creating the tool', $e);
        }
    }

    /**
     * Update the specified tool.
     */
    public function update(Request $request, string $slug): JsonResponse
    {
        try {
            $tool = Tool::where('slug', $slug)->first();

            if (! $tool) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tool not found',
                ], 404);
            }

            // Validate with all fields optional for updates
            $validated = $this->validateToolRequest($request, false);

            // Handle category update
            if (isset($validated['category'])) {
                $category = $this->findOrCreateCategory($validated['category']);
                $validated['category_id'] = $category->id;
                unset($validated['category']);
            }

            // Handle name change - regenerate slug
            if (isset($validated['name']) && $validated['name'] !== $tool->name) {
                $validated['slug'] = $this->generateUniqueSlug($validated['name'], $tool->id);
            }

            // Update first_reviewed_at if ryan_rating is being set for the first time
            if (isset($validated['ryan_rating']) && ! $tool->first_reviewed_at) {
                $validated['first_reviewed_at'] = now();
            }

            $tool->update($validated);
            $tool->load('category');

            return response()->json([
                'success' => true,
                'message' => 'Tool updated successfully',
                'data' => $this->formatToolResponse($tool, true),
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while updating the tool', $e);
        }
    }

    /**
     * Remove the specified tool.
     */
    public function destroy(string $slug): JsonResponse
    {
        try {
            $tool = Tool::where('slug', $slug)->first();

            if (! $tool) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tool not found',
                ], 404);
            }

            $toolName = $tool->name;
            $tool->delete();

            return response()->json([
                'success' => true,
                'message' => "Tool '{$toolName}' deleted successfully",
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while deleting the tool', $e);
        }
    }

    /**
     * Validate the incoming tool request.
     *
     * @param  bool  $isCreating  Whether this is a creation (required fields) or update (optional fields)
     */
    private function validateToolRequest(Request $request, bool $isCreating = true): array
    {
        $required = $isCreating ? 'required' : 'sometimes';

        return $request->validate([
            'name' => [$required, 'string', 'max:255'],
            'description' => [$required, 'string'],
            'long_description' => ['nullable', 'string'],
            'website_url' => [$required, 'url', 'max:500'],
            'documentation_url' => ['nullable', 'url', 'max:500'],
            'logo_url' => ['nullable', 'url', 'max:500'],
            'category' => [$required, 'string', 'max:255'],
            'pricing_model' => ['nullable', 'string', 'in:free,freemium,paid,enterprise'],
            'price_description' => ['nullable', 'string'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string'],
            'use_cases' => ['nullable', 'array'],
            'use_cases.*' => ['string'],
            'integrations' => ['nullable', 'array'],
            'integrations.*' => ['string'],
            'ryan_rating' => ['nullable', 'integer', 'min:1', 'max:10'],
            'ryan_notes' => ['nullable', 'string'],
            'ryan_last_used' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'The tool name is required.',
            'name.max' => 'The tool name cannot exceed 255 characters.',
            'description.required' => 'A brief description is required.',
            'website_url.required' => 'The website URL is required.',
            'website_url.url' => 'The website URL must be a valid URL.',
            'category.required' => 'A category is required.',
            'pricing_model.in' => 'Pricing model must be one of: free, freemium, paid, enterprise.',
            'ryan_rating.min' => 'Rating must be between 1 and 10.',
            'ryan_rating.max' => 'Rating must be between 1 and 10.',
        ]);
    }

    /**
     * Find an existing category or create a new one.
     */
    private function findOrCreateCategory(string $categoryName): Category
    {
        // Try exact match first (case-insensitive)
        $category = Category::whereRaw('LOWER(name) = ?', [strtolower($categoryName)])->first();

        if ($category) {
            return $category;
        }

        // Create new category
        // We use firstOrCreate to prevent race conditions if multiple requests
        // try to create the same category simultaneously
        return Category::firstOrCreate(
            ['slug' => Str::slug($categoryName)],
            [
                'name' => $categoryName,
                'description' => "Tools in the {$categoryName} category",
                'icon' => '🔧', // Default icon
                'is_active' => true,
                'sort_order' => Category::max('sort_order') + 1 ?? 0,
            ]
        );
    }

    /**
     * Generate a unique slug for the tool.
     *
     * @param  int|null  $excludeId  Tool ID to exclude (for updates)
     */
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        // Keep trying until we find a unique slug
        while (Tool::where('slug', $slug)->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Format tool data for API response.
     */
    private function formatToolResponse(Tool $tool, bool $detailed = false): array
    {
        $data = [
            'id' => $tool->id,
            'name' => $tool->name,
            'slug' => $tool->slug,
            'description' => $tool->description,
            'category' => [
                'id' => $tool->category->id,
                'name' => $tool->category->name,
                'slug' => $tool->category->slug,
            ],
            'website_url' => $tool->website_url,
            'pricing_model' => $tool->pricing_model,
            'is_active' => $tool->is_active,
            'is_featured' => $tool->is_featured,
            'created_at' => $tool->created_at,
            'url' => route('tools.show', $tool->slug),
        ];

        // Include detailed fields only when requested (show/create/update)
        if ($detailed) {
            $data = array_merge($data, [
                'long_description' => $tool->long_description,
                'documentation_url' => $tool->documentation_url,
                'logo_url' => $tool->logo_url,
                'price_description' => $tool->price_description,
                'features' => $tool->features,
                'use_cases' => $tool->use_cases,
                'integrations' => $tool->integrations,
                'ryan_rating' => $tool->ryan_rating,
                'ryan_notes' => $tool->ryan_notes,
                'ryan_last_used' => $tool->ryan_last_used,
                'first_reviewed_at' => $tool->first_reviewed_at,
                'views_count' => $tool->views_count,
                'updated_at' => $tool->updated_at,
            ]);
        }

        return $data;
    }

    /**
     * Return a standardized error response.
     */
    private function errorResponse(string $message, \Exception $e): JsonResponse
    {
        \Log::error('API Error: '.$message, [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
        ], 500);
    }
}
