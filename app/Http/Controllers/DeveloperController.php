<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Inertia\Response;

class DeveloperController extends Controller
{
    /**
     * Display the Tool schema documentation
     */
    public function toolSchema(): Response
    {
        $schemaPath = base_path('docs/api/tool-schema.json');
        $typeDefsPath = base_path('docs/api/tool.d.ts');

        // Load and parse the JSON schema
        $schema = json_decode(File::get($schemaPath), true);

        // Load TypeScript definitions
        $typeDefinitions = File::exists($typeDefsPath) ? File::get($typeDefsPath) : null;

        // Extract properties with enhanced metadata
        $properties = [];
        foreach ($schema['properties'] as $key => $prop) {
            $properties[] = [
                'name' => $key,
                'type' => $this->formatType($prop['type']),
                'required' => in_array($key, $schema['required'] ?? []),
                'description' => $prop['description'] ?? '',
                'enum' => $prop['enum'] ?? null,
                'minLength' => $prop['minLength'] ?? null,
                'maxLength' => $prop['maxLength'] ?? null,
                'minimum' => $prop['minimum'] ?? null,
                'maximum' => $prop['maximum'] ?? null,
                'format' => $prop['format'] ?? null,
                'pattern' => $prop['pattern'] ?? null,
                'items' => $prop['items'] ?? null,
                'examples' => $prop['examples'] ?? [],
            ];
        }

        // Group properties by category
        $grouped = [
            'required' => [
                'title' => 'Required Fields',
                'description' => 'These fields must be provided when creating a tool',
                'properties' => array_filter($properties, fn ($p) => $p['required']),
            ],
            'basic_info' => [
                'title' => 'Optional: Basic Information',
                'description' => 'Additional descriptive fields',
                'properties' => array_filter($properties, fn ($p) => !$p['required'] && in_array($p['name'], ['long_description', 'logo_url'])),
            ],
            'pricing' => [
                'title' => 'Optional: Pricing Information',
                'description' => 'Pricing model and cost details',
                'properties' => array_filter($properties, fn ($p) => !$p['required'] && in_array($p['name'], ['pricing_model', 'price_description'])),
            ],
            'technical' => [
                'title' => 'Optional: Technical Details',
                'description' => 'Features, use cases, and integrations',
                'properties' => array_filter($properties, fn ($p) => !$p['required'] && in_array($p['name'], ['features', 'use_cases', 'integrations'])),
            ],
            'personal' => [
                'title' => 'Optional: Personal Rating & Notes',
                'description' => 'Your personal assessment of the tool',
                'properties' => array_filter($properties, fn ($p) => !$p['required'] && in_array($p['name'], ['ryan_rating', 'ryan_notes', 'ryan_last_used'])),
            ],
            'metadata' => [
                'title' => 'Optional: Metadata',
                'description' => 'Visibility and featured status',
                'properties' => array_filter($properties, fn ($p) => !$p['required'] && in_array($p['name'], ['is_featured', 'is_active', 'documentation_url'])),
            ],
        ];

        // Remove empty groups and reindex properties
        $grouped = array_filter($grouped, fn ($g) => !empty($g['properties']));
        foreach ($grouped as &$group) {
            $group['properties'] = array_values($group['properties']);
        }

        return Inertia::render('Developer/ToolSchema', [
            'schema' => [
                'title' => $schema['title'] ?? 'Tool Schema',
                'description' => $schema['description'] ?? '',
                'version' => '1.0.0',
                'grouped' => $grouped,
                'examples' => $schema['examples'] ?? [],
                'type_definitions' => $typeDefinitions,
            ],
        ]);
    }

    /**
     * Format type information for display
     */
    protected function formatType(mixed $type): string
    {
        if (is_array($type)) {
            return implode(' | ', array_map('ucfirst', $type));
        }

        return ucfirst((string) $type);
    }
}
