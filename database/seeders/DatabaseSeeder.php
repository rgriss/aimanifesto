<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Categories
        $categories = [
            [
                'name' => 'Code Assistants',
                'slug' => 'code-assistants',
                'description' => 'AI tools that help you write, review, and improve code',
                'icon' => '💻',
                'sort_order' => 1,
            ],
            [
                'name' => 'Chat & Reasoning',
                'slug' => 'chat-reasoning',
                'description' => 'Conversational AI and advanced reasoning models',
                'icon' => '💬',
                'sort_order' => 2,
            ],
            [
                'name' => 'Content Creation',
                'slug' => 'content-creation',
                'description' => 'Tools for writing, editing, and creating content',
                'icon' => '✍️',
                'sort_order' => 3,
            ],
            [
                'name' => 'Development Tools',
                'slug' => 'development-tools',
                'description' => 'IDEs, editors, and development environments',
                'icon' => '🛠️',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);

            // Add tools based on category
            if ($category->slug === 'code-assistants') {
                Tool::create([
                    'category_id' => $category->id,
                    'name' => 'GitHub Copilot',
                    'slug' => 'github-copilot',
                    'description' => 'AI pair programmer that helps you write code faster',
                    'long_description' => 'GitHub Copilot uses OpenAI Codex to suggest code and entire functions in real-time. Works in your editor.',
                    'website_url' => 'https://github.com/features/copilot',
                    'documentation_url' => 'https://docs.github.com/copilot',
                    'pricing_model' => 'paid',
                    'price_description' => '$10/month individual, $19/month business',
                    'ryan_rating' => 9,
                    'ryan_notes' => 'Required all my developers to use this at Roof Maxx. 2-10x productivity gains. Absolutely essential.',
                    'ryan_last_used' => now(),
                    'features' => ['Code completion', 'Multi-file context', 'Comment to code', 'Test generation'],
                    'use_cases' => ['Writing boilerplate', 'Learning new frameworks', 'Speeding up development'],
                    'integrations' => ['VS Code', 'JetBrains', 'Neovim'],
                    'is_featured' => true,
                    'first_reviewed_at' => now()->subMonths(24),
                ]);
            }

            if ($category->slug === 'chat-reasoning') {
                Tool::create([
                    'category_id' => $category->id,
                    'name' => 'Claude',
                    'slug' => 'claude',
                    'description' => 'Anthropic\'s AI assistant focused on helpful, harmless, and honest interactions',
                    'long_description' => 'Claude excels at analysis, coding, writing, and complex reasoning. Strong safety features and nuanced understanding.',
                    'website_url' => 'https://claude.ai',
                    'documentation_url' => 'https://docs.anthropic.com',
                    'pricing_model' => 'freemium',
                    'price_description' => 'Free tier + Pro ($20/month)',
                    'ryan_rating' => 10,
                    'ryan_notes' => 'My favorite AI. I have a ChatGPT tattoo but Claude is actually better for deep work. The extended context is incredible.',
                    'ryan_last_used' => now(),
                    'features' => ['200K context window', 'Code analysis', 'Document analysis', 'Artifacts'],
                    'use_cases' => ['Technical documentation', 'Code review', 'Strategic thinking', 'Content creation'],
                    'integrations' => ['API', 'Claude Code', 'Cursor'],
                    'is_featured' => true,
                    'first_reviewed_at' => now()->subMonths(18),
                ]);

                Tool::create([
                    'category_id' => $category->id,
                    'name' => 'ChatGPT',
                    'slug' => 'chatgpt',
                    'description' => 'OpenAI\'s conversational AI that started the revolution',
                    'long_description' => 'ChatGPT brought AI to the mainstream. Great for general tasks, brainstorming, and quick questions.',
                    'website_url' => 'https://chat.openai.com',
                    'documentation_url' => 'https://platform.openai.com/docs',
                    'pricing_model' => 'freemium',
                    'price_description' => 'Free + Plus ($20/month) + Team ($25/user/month)',
                    'ryan_rating' => 8,
                    'ryan_notes' => 'Required for dev team at Roof Maxx. GPT-4 is solid. I got a tattoo of the logo to show commitment to AI.',
                    'ryan_last_used' => now()->subDays(2),
                    'features' => ['GPT-4 access', 'Image generation (DALL-E)', 'Web browsing', 'Code interpreter'],
                    'use_cases' => ['Brainstorming', 'Quick research', 'Content drafts', 'Code help'],
                    'integrations' => ['API', 'Custom GPTs', 'Plugins'],
                    'is_featured' => true,
                    'first_reviewed_at' => now()->subMonths(24),
                ]);
            }

            if ($category->slug === 'development-tools') {
                Tool::create([
                    'category_id' => $category->id,
                    'name' => 'Cursor',
                    'slug' => 'cursor',
                    'description' => 'AI-first code editor built on VS Code',
                    'long_description' => 'Cursor brings AI directly into your editor with Claude integration, multi-file editing, and codebase understanding.',
                    'website_url' => 'https://cursor.sh',
                    'documentation_url' => 'https://docs.cursor.sh',
                    'pricing_model' => 'freemium',
                    'price_description' => 'Free + Pro ($20/month)',
                    'ryan_rating' => 9,
                    'ryan_notes' => 'Using this to build AI Manifesto. The Claude integration is exceptional. Multi-file edits are powerful.',
                    'ryan_last_used' => now(),
                    'features' => ['Claude integration', 'Codebase chat', 'Multi-file editing', 'VS Code compatible'],
                    'use_cases' => ['Building with AI', 'Refactoring', 'Learning codebases', 'Rapid development'],
                    'integrations' => ['All VS Code extensions', 'Claude', 'GPT-4'],
                    'is_featured' => true,
                    'first_reviewed_at' => now()->subMonths(6),
                ]);

                Tool::create([
                    'category_id' => $category->id,
                    'name' => 'Claude Code',
                    'slug' => 'claude-code',
                    'description' => 'Command-line tool for agentic coding with Claude',
                    'long_description' => 'Claude Code lets you delegate entire coding tasks to Claude from your terminal. Perfect for automation and scripting.',
                    'website_url' => 'https://docs.claude.com/en/docs/claude-code',
                    'pricing_model' => 'paid',
                    'price_description' => 'Requires Claude API access',
                    'ryan_rating' => 8,
                    'ryan_notes' => 'Great for automating repetitive tasks. Using it alongside Cursor for this project.',
                    'ryan_last_used' => now(),
                    'features' => ['Terminal integration', 'File manipulation', 'Automated coding', 'Claude 4 access'],
                    'use_cases' => ['Build scripts', 'Mass refactoring', 'Project setup', 'Automation'],
                    'integrations' => ['Any terminal', 'Git workflows'],
                    'is_featured' => false,
                    'first_reviewed_at' => now()->subMonths(3),
                ]);
            }

            if ($category->slug === 'content-creation') {
                Tool::create([
                    'category_id' => $category->id,
                    'name' => 'Grammarly',
                    'slug' => 'grammarly',
                    'description' => 'AI-powered writing assistant for grammar, tone, and clarity',
                    'website_url' => 'https://grammarly.com',
                    'pricing_model' => 'freemium',
                    'price_description' => 'Free + Premium ($12/month)',
                    'ryan_rating' => 7,
                    'ryan_notes' => 'Good for catching mistakes. The tone detection is helpful for professional communication.',
                    'ryan_last_used' => now()->subWeek(),
                    'features' => ['Grammar checking', 'Tone adjustment', 'Plagiarism detection', 'Style suggestions'],
                    'use_cases' => ['Email writing', 'Content editing', 'Professional communication'],
                    'is_featured' => false,
                ]);
            }
        }
    }
}