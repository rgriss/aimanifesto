<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                background-color: oklch(1 0 0);
            }

            html.dark {
                background-color: oklch(0.145 0 0);
            }
        </style>

        <title inertia>{{ config('app.name', 'AI Manifesto') }}</title>

        <!-- Favicon -->
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/favicon.png" type="image/png">
        <link rel="apple-touch-icon" href="/images/Polaris Pixels Star Logo.png">

        <!-- Meta Tags -->
        <meta name="description" content="AI Manifesto - A framework for responsible AI development. Curated tools, ethical principles, and guidance for building with artificial intelligence.">
        <meta name="keywords" content="AI, artificial intelligence, AI tools, AI ethics, AI manifesto, responsible AI, machine learning, AI development">
        <meta name="author" content="Polaris Pixels">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:title" content="AI Manifesto - Responsible AI Development Framework">
        <meta property="og:description" content="A framework for responsible AI development. Curated tools, ethical principles, and guidance for building with artificial intelligence.">
        <meta property="og:image" content="{{ url('/images/Polaris Pixels Star Logo.png') }}">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url('/') }}">
        <meta property="twitter:title" content="AI Manifesto - Responsible AI Development Framework">
        <meta property="twitter:description" content="A framework for responsible AI development. Curated tools, ethical principles, and guidance for building with artificial intelligence.">
        <meta property="twitter:image" content="{{ url('/images/Polaris Pixels Star Logo.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
