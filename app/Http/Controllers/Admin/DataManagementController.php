<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DataManagementController extends Controller
{
    public function index()
    {
        // Get list of export files
        $exports = collect(Storage::files('exports'))
            ->filter(fn ($file) => str_ends_with($file, '.json'))
            ->map(function ($file) {
                return [
                    'name' => basename($file),
                    'size' => Storage::size($file),
                    'lastModified' => Storage::lastModified($file),
                    'path' => $file,
                ];
            })
            ->sortByDesc('lastModified')
            ->values();

        // Get database stats
        $stats = [
            'categories' => Category::count(),
            'tools' => Tool::count(),
            'activeTools' => Tool::where('is_active', true)->count(),
            'featuredTools' => Tool::where('is_featured', true)->count(),
        ];

        return Inertia::render('Admin/DataManagement', [
            'exports' => $exports,
            'stats' => $stats,
        ]);
    }

    public function export(Request $request)
    {
        $filename = $request->input('filename') ?? 'export-'.now()->format('Y-m-d-His').'.json';

        // Run export command
        Artisan::call('db:export', ['--file' => $filename]);

        $output = Artisan::output();

        return back()->with('success', 'Database exported successfully to '.$filename);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json',
            'merge' => 'boolean',
        ]);

        $file = $request->file('file');
        $merge = $request->boolean('merge');

        // Save uploaded file to exports directory
        $filename = 'import-'.now()->format('Y-m-d-His').'.json';
        $path = $file->storeAs('exports', $filename);

        DB::beginTransaction();

        try {
            if (! $merge) {
                // Clear existing data
                Tool::query()->delete();
                Category::query()->delete();
            }

            // Read and import data
            $content = Storage::get($path);
            $data = json_decode($content, true);

            if (! $data || ! isset($data['categories'])) {
                throw new \Exception('Invalid JSON structure');
            }

            $categoriesCreated = 0;
            $categoriesUpdated = 0;
            $toolsCreated = 0;
            $toolsUpdated = 0;

            foreach ($data['categories'] as $categoryData) {
                $tools = $categoryData['tools'] ?? [];
                unset($categoryData['tools']);

                $category = Category::where('slug', $categoryData['slug'])->first();

                if ($category) {
                    $category->update($categoryData);
                    $categoriesUpdated++;
                } else {
                    $category = Category::create($categoryData);
                    $categoriesCreated++;
                }

                foreach ($tools as $toolData) {
                    $toolData['category_id'] = $category->id;
                    $tool = Tool::where('slug', $toolData['slug'])->first();

                    if ($tool) {
                        $tool->update($toolData);
                        $toolsUpdated++;
                    } else {
                        Tool::create($toolData);
                        $toolsCreated++;
                    }
                }
            }

            DB::commit();

            $message = "Import completed! Categories: {$categoriesCreated} created, {$categoriesUpdated} updated. Tools: {$toolsCreated} created, {$toolsUpdated} updated.";

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Import failed: '.$e->getMessage());
        }
    }

    public function download(Request $request, string $filename)
    {
        $path = 'exports/'.$filename;

        if (! Storage::exists($path)) {
            abort(404);
        }

        return Storage::download($path);
    }

    public function delete(Request $request, string $filename)
    {
        $path = 'exports/'.$filename;

        if (! Storage::exists($path)) {
            abort(404);
        }

        Storage::delete($path);

        return back()->with('success', 'Export file deleted successfully');
    }
}
