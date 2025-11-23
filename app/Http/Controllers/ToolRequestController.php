<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessToolRequest;
use App\Models\ToolRequest;
use App\Services\ToolRequestValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToolRequestController extends Controller
{
    public function store(Request $request, ToolRequestValidationService $validationService)
    {
        $request->validate([
            'user_input' => 'required|string|min:3|max:1000',
        ]);

        $userInput = $request->input('user_input');

        // Stage 1: Real-time Validation
        $validation = $validationService->validate($userInput);

        if (!$validation['valid']) {
            // Log the rejected request
            ToolRequest::create([
                'user_id' => Auth::id(),
                'user_input' => $userInput,
                'status' => 'rejected',
                'rejection_reason' => $validation['reason'],
                'validation_result' => $validation,
            ]);

            return response()->json([
                'message' => $validation['reason'],
                'valid' => false,
            ], 422);
        }

        // Create the request record
        $toolRequest = ToolRequest::create([
            'user_id' => Auth::id(),
            'user_input' => $userInput,
            'status' => 'approved', // Initially approved by validation
            'validation_result' => $validation,
        ]);

        // Dispatch Stage 2: Async Research
        ProcessToolRequest::dispatch($toolRequest);

        return response()->json([
            'message' => 'Request approved! We are researching this tool now.',
            'valid' => true,
        ]);
    }
}
