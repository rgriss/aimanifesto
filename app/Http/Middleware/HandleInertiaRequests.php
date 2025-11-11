<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Process;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'manifestoVersion' => $this->getManifestoVersion(),
            'manifestoLastUpdated' => $this->getManifestoLastUpdated(),
        ];
    }

    /**
     * Get the current manifesto version.
     *
     * Uses the version defined in config/app.php for reliability.
     */
    protected function getManifestoVersion(): string
    {
        return config('app.version', '0.0.0');
    }

    /**
     * Get the last commit date for the manifesto.
     */
    protected function getManifestoLastUpdated(): string
    {
        return Cache::remember('manifesto_last_updated', 3600, function () {
            $result = Process::path(base_path())->run('git log -1 --format=%cd --date=format:%B_%d_%Y');

            if ($result->successful() && ! empty($result->output())) {
                // Replace underscores with proper formatting: October_10_2025 -> October 10, 2025
                $date = trim($result->output());
                $parts = explode('_', $date);
                if (count($parts) === 3) {
                    return $parts[0].' '.$parts[1].', '.$parts[2];
                }

                return str_replace('_', ' ', $date);
            }

            return now()->format('F d, Y');
        });
    }
}
