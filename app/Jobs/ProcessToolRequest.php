<?php

namespace App\Jobs;

use App\Models\ToolRequest;
use App\Services\ToolResearchService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessToolRequest implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ToolRequest $toolRequest
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ToolResearchService $researchService): void
    {
        $researchService->process($this->toolRequest);
    }
}
