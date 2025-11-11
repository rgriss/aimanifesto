<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ApiCategoryController extends Controller
{
    /**
     * Display a listing of all categories.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Category::withCount('tools')->active()->ordered();

            // Optional search filter
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Get all or paginate
            if ($request->boolean('paginate', false)) {
                $perPage = min((int) $request->get('per_page', 15), 100);
                $categories = $query->paginate($perPage);

                return response()->json([
                    'success' => true,
                    'data' => $categories->map(fn ($category) => $this->formatCategoryResponse($category)),
                    'meta' => [
                        'current_page' => $categories->currentPage(),
                        'last_page' => $categories->lastPage(),
                        'per_page' => $categories->perPage(),
                        'total' => $categories->total(),
                    ],
                ]);
            } else {
                $categories = $query->get();

                return response()->json([
                    'success' => true,
                    'data' => $categories->map(fn ($category) => $this->formatCategoryResponse($category)),
                ]);
            }

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve categories', $e);
        }
    }

    /**
     * Display the specified category.
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $category = Category::withCount('tools')->where('slug', $slug)->first();

            if (! $category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $this->formatCategoryResponse($category, true),
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve category', $e);
        }
    }

    /**
     * Store a newly created category in the database.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validated = $this->validateCategoryRequest($request);

            // Check for duplicate by name
            $existing = Category::where('name', $validated['name'])->first();
            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'A category with this name already exists.',
                    'existing_category' => [
                        'id' => $existing->id,
                        'name' => $existing->name,
                        'slug' => $existing->slug,
                        'url' => route('categories.show', $existing->slug),
                    ],
                ], 409);
            }

            // Generate unique slug
            $slug = $this->generateUniqueSlug($validated['name']);

            // Get next sort order
            $sortOrder = Category::max('sort_order') + 1 ?? 0;

            // Create category
            $category = Category::create([
                'name' => $validated['name'],
                'slug' => $slug,
                'description' => $validated['description'],
                'icon' => $validated['icon'] ?? '🔧',
                'is_active' => $validated['is_active'] ?? true,
                'sort_order' => $validated['sort_order'] ?? $sortOrder,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'data' => $this->formatCategoryResponse($category, true),
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while creating the category', $e);
        }
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, string $slug): JsonResponse
    {
        try {
            $category = Category::where('slug', $slug)->first();

            if (! $category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                ], 404);
            }

            // Validate with all fields optional for updates
            $validated = $this->validateCategoryRequest($request, false);

            // Handle name change - regenerate slug
            if (isset($validated['name']) && $validated['name'] !== $category->name) {
                $validated['slug'] = $this->generateUniqueSlug($validated['name'], $category->id);
            }

            $category->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'data' => $this->formatCategoryResponse($category, true),
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while updating the category', $e);
        }
    }

    /**
     * Remove the specified category.
     */
    public function destroy(string $slug): JsonResponse
    {
        try {
            $category = Category::withCount('tools')->where('slug', $slug)->first();

            if (! $category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                ], 404);
            }

            // Prevent deleting category with tools
            if ($category->tools_count > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot delete category with {$category->tools_count} associated tools. Please move or delete the tools first.",
                ], 422);
            }

            $categoryName = $category->name;
            $category->delete();

            return response()->json([
                'success' => true,
                'message' => "Category '{$categoryName}' deleted successfully",
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while deleting the category', $e);
        }
    }

    /**
     * Validate the incoming category request.
     *
     * @param  bool  $isCreating  Whether this is a creation (required fields) or update (optional fields)
     */
    private function validateCategoryRequest(Request $request, bool $isCreating = true): array
    {
        $required = $isCreating ? 'required' : 'sometimes';

        return $request->validate([
            'name' => [$required, 'string', 'max:255'],
            'description' => [$required, 'string'],
            'icon' => ['nullable', 'string', 'max:10'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ], [
            'name.required' => 'The category name is required.',
            'name.max' => 'The category name cannot exceed 255 characters.',
            'description.required' => 'A description is required.',
            'sort_order.min' => 'Sort order must be 0 or greater.',
        ]);
    }

    /**
     * Generate a unique slug for the category.
     *
     * @param  int|null  $excludeId  Category ID to exclude (for updates)
     */
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        // Keep trying until we find a unique slug
        while (Category::where('slug', $slug)->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Format category data for API response.
     */
    private function formatCategoryResponse(Category $category, bool $detailed = false): array
    {
        $data = [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'description' => $category->description,
            'icon' => $category->icon,
            'is_active' => $category->is_active,
            'sort_order' => $category->sort_order,
            'tools_count' => $category->tools_count ?? 0,
            'url' => route('categories.show', $category->slug),
        ];

        // Include timestamps when detailed
        if ($detailed) {
            $data['created_at'] = $category->created_at;
            $data['updated_at'] = $category->updated_at;
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
