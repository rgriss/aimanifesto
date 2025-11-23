<?php

namespace App\Http\Controllers\Admin;

use App\Models\ToolRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ToolRequestController
{
    public function index(Request $request): Response
    {
        $query = ToolRequest::with(['user', 'tool'])
            ->orderBy('created_at', 'desc');

        // Search filter
        if ($search = $request->input('search')) {
            $query->where('user_input', 'like', "%{$search}%");
        }

        // Status filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $toolRequests = $query->paginate(20)->withQueryString();

        return Inertia::render('Admin/ToolRequests/Index', [
            'toolRequests' => $toolRequests,
            'filters' => [
                'search' => $search ?? '',
                'status' => $status ?? '',
            ],
        ]);
    }

    public function show(ToolRequest $toolRequest): Response
    {
        $toolRequest->load(['user', 'tool']);

        return Inertia::render('Admin/ToolRequests/Show', [
            'toolRequest' => $toolRequest,
        ]);
    }
}
