<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Inertia\Response;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

class DocsController extends Controller
{
    protected MarkdownConverter $converter;

    public function __construct()
    {
        // Configure markdown converter with GitHub Flavored Markdown
        $environment = new Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        $this->converter = new MarkdownConverter($environment);
    }

    /**
     * Display a listing of all documentation files
     */
    public function index(): Response
    {
        $docsPath = base_path('docs');
        $documents = [];

        if (File::isDirectory($docsPath)) {
            $files = File::files($docsPath);

            foreach ($files as $file) {
                if ($file->getExtension() === 'md') {
                    $slug = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                    $content = File::get($file->getPathname());

                    // Extract title from first h1 heading
                    preg_match('/^#\s+(.+)$/m', $content, $matches);
                    $title = $matches[1] ?? $this->formatTitle($slug);

                    // Extract description from content (first paragraph after title)
                    preg_match('/^#\s+.+\n\n(.+)$/m', $content, $descMatches);
                    $description = $descMatches[1] ?? '';

                    // Get file metadata
                    $lastModified = date('F d, Y', $file->getMTime());
                    $size = $this->formatFileSize($file->getSize());

                    $documents[] = [
                        'slug' => $slug,
                        'title' => $title,
                        'description' => strip_tags($description),
                        'last_modified' => $lastModified,
                        'size' => $size,
                        'filename' => $file->getFilename(),
                    ];
                }
            }

            // Sort by filename
            usort($documents, fn ($a, $b) => strcmp($a['filename'], $b['filename']));
        }

        return Inertia::render('Docs/Index', [
            'documents' => $documents,
        ]);
    }

    /**
     * Display the specified documentation file
     */
    public function show(string $slug): Response
    {
        $filePath = base_path("docs/{$slug}.md");

        abort_if(! File::exists($filePath), 404, 'Documentation not found');

        // Security: prevent path traversal
        $realPath = realpath($filePath);
        $docsPath = realpath(base_path('docs'));

        abort_if(
            ! $realPath || ! str_starts_with($realPath, $docsPath),
            404,
            'Invalid document path'
        );

        $markdown = File::get($filePath);

        // Convert markdown to HTML
        $html = $this->converter->convert($markdown)->getContent();

        // Extract metadata
        preg_match('/^#\s+(.+)$/m', $markdown, $matches);
        $title = $matches[1] ?? $this->formatTitle($slug);

        $fileInfo = new \SplFileInfo($filePath);

        return Inertia::render('Docs/Show', [
            'document' => [
                'slug' => $slug,
                'title' => $title,
                'html' => $html,
                'last_modified' => date('F d, Y', $fileInfo->getMTime()),
                'filename' => $fileInfo->getFilename(),
            ],
        ]);
    }

    /**
     * Format slug into human-readable title
     */
    protected function formatTitle(string $slug): string
    {
        return ucwords(str_replace(['-', '_'], ' ', $slug));
    }

    /**
     * Format file size to human-readable format
     */
    protected function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        }

        return $bytes.' B';
    }
}
