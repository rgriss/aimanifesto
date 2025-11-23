<?php

namespace App\Http\Controllers\Admin;

use App\Models\ToolRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController
{
    public function index(): Response
    {
        // Tool Request Statistics
        $toolRequestStats = [
            'total' => ToolRequest::count(),
            'pending' => ToolRequest::where('status', 'pending')->count(),
            'approved' => ToolRequest::where('status', 'approved')->count(),
            'rejected' => ToolRequest::where('status', 'rejected')->count(),
            'completed' => ToolRequest::where('status', 'completed')->count(),
            'failed' => ToolRequest::where('status', 'failed')->count(),
        ];

        // Recent Tool Requests
        $recentRequests = ToolRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(fn($request) => [
                'id' => $request->id,
                'user_input' => $request->user_input,
                'status' => $request->status,
                'user_name' => $request->user->name ?? 'Unknown',
                'created_at' => $request->created_at->diffForHumans(),
            ]);

        // Queue Statistics
        $queueStats = [
            'pending' => DB::table('jobs')->count(),
            'failed' => DB::table('failed_jobs')->count(),
            'processed_today' => DB::table('jobs')
                ->where('created_at', '>=', now()->startOfDay())
                ->count(),
        ];

        // Recent Failed Jobs
        $failedJobs = DB::table('failed_jobs')
            ->orderBy('failed_at', 'desc')
            ->limit(10)
            ->get()
            ->map(fn($job) => [
                'id' => $job->id,
                'queue' => $job->queue,
                'exception' => mb_substr($job->exception, 0, 200) . '...',
                'failed_at' => \Carbon\Carbon::parse($job->failed_at)->diffForHumans(),
            ]);

        return Inertia::render('Dashboard', [
            'toolRequestStats' => $toolRequestStats,
            'recentRequests' => $recentRequests,
            'queueStats' => $queueStats,
            'failedJobs' => $failedJobs,
        ]);
    }
}
