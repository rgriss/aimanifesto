<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'AI Assistants',
                'slug' => 'ai-assistants',
                'description' => 'Conversational AI tools and chatbots that can help with a wide range of tasks, from answering questions to creative writing.',
                'icon' => '🤖',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Code Generation',
                'slug' => 'code-generation',
                'description' => 'AI-powered tools that help developers write, review, and debug code across multiple programming languages.',
                'icon' => '💻',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Image Generation',
                'slug' => 'image-generation',
                'description' => 'Create stunning images, artwork, and graphics from text descriptions using AI models.',
                'icon' => '🎨',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Video & Audio',
                'slug' => 'video-audio',
                'description' => 'AI tools for creating, editing, and enhancing video and audio content.',
                'icon' => '🎬',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Writing & Content',
                'slug' => 'writing-content',
                'description' => 'Tools that assist with writing, editing, and creating various types of content.',
                'icon' => '✍️',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Productivity',
                'slug' => 'productivity',
                'description' => 'AI-powered tools to boost productivity, automate tasks, and streamline workflows.',
                'icon' => '⚡',
                'is_active' => true,
                'sort_order' => 6,
            ],
            [
                'name' => 'Data & Analytics',
                'slug' => 'data-analytics',
                'description' => 'Tools for analyzing data, generating insights, and creating visualizations with AI assistance.',
                'icon' => '📊',
                'is_active' => true,
                'sort_order' => 7,
            ],
            [
                'name' => 'Design & UI/UX',
                'slug' => 'design-ui-ux',
                'description' => 'AI tools for designers, including UI/UX design, prototyping, and design systems.',
                'icon' => '🎯',
                'is_active' => true,
                'sort_order' => 8,
            ],
            [
                'name' => 'Research & Learning',
                'slug' => 'research-learning',
                'description' => 'AI-powered research assistants and learning tools for students and professionals.',
                'icon' => '📚',
                'is_active' => true,
                'sort_order' => 9,
            ],
            [
                'name' => 'Marketing & SEO',
                'slug' => 'marketing-seo',
                'description' => 'AI tools for digital marketing, SEO optimization, and audience engagement.',
                'icon' => '📈',
                'is_active' => true,
                'sort_order' => 10,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('✓ Created '.count($categories).' categories');
    }
}
