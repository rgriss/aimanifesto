<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tool;
use Illuminate\Database\Seeder;

class ToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tools = [
            // AI Assistants
            [
                'name' => 'ChatGPT',
                'slug' => 'chatgpt',
                'description' => 'Conversational AI by OpenAI that can assist with writing, coding, analysis, and creative tasks.',
                'long_description' => 'ChatGPT is a state-of-the-art language model developed by OpenAI. It excels at understanding context, providing detailed explanations, writing code, and engaging in natural conversations across a wide range of topics.',
                'website_url' => 'https://chat.openai.com',
                'documentation_url' => 'https://platform.openai.com/docs',
                'category' => 'AI Assistants',
                'pricing_model' => 'freemium',
                'price_description' => 'Free tier available, Plus at $20/month',
                'features' => ['Natural conversation', 'Code generation', 'Creative writing', 'Analysis and research', 'Multiple languages'],
                'use_cases' => ['Content creation', 'Programming assistance', 'Learning and education', 'Business analysis'],
                'integrations' => ['API', 'Web', 'Mobile apps'],
                'ryan_rating' => 9,
                'ryan_notes' => 'Incredibly versatile. Use it daily for everything from code review to brainstorming.',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Claude',
                'slug' => 'claude',
                'description' => 'Anthropic\'s AI assistant focused on being helpful, harmless, and honest.',
                'long_description' => 'Claude is designed with Constitutional AI principles, making it particularly good at nuanced tasks, long-form content, and following complex instructions while maintaining safety.',
                'website_url' => 'https://claude.ai',
                'documentation_url' => 'https://docs.anthropic.com',
                'category' => 'AI Assistants',
                'pricing_model' => 'freemium',
                'price_description' => 'Free tier, Pro at $20/month',
                'features' => ['Long context window', 'Complex reasoning', 'Code analysis', 'Document understanding', 'Safety-focused'],
                'use_cases' => ['Research and analysis', 'Technical writing', 'Code review', 'Content moderation'],
                'integrations' => ['API', 'Web', 'Slack'],
                'ryan_rating' => 10,
                'ryan_notes' => 'Best for complex analytical tasks. The extended context window is a game-changer.',
                'is_featured' => true,
                'is_active' => true,
            ],

            // Code Generation
            [
                'name' => 'GitHub Copilot',
                'slug' => 'github-copilot',
                'description' => 'AI pair programmer that suggests code completions as you type.',
                'long_description' => 'GitHub Copilot uses OpenAI Codex to provide intelligent code suggestions directly in your editor. It understands context from your codebase and can generate entire functions, tests, and documentation.',
                'website_url' => 'https://github.com/features/copilot',
                'documentation_url' => 'https://docs.github.com/en/copilot',
                'category' => 'Code Generation',
                'pricing_model' => 'paid',
                'price_description' => '$10/month for individuals, $19/user/month for business',
                'features' => ['Real-time suggestions', 'Multi-language support', 'Context awareness', 'IDE integration'],
                'use_cases' => ['Faster coding', 'Learning new languages', 'Boilerplate generation', 'Test writing'],
                'integrations' => ['VS Code', 'JetBrains IDEs', 'Neovim', 'Visual Studio'],
                'ryan_rating' => 8,
                'ryan_notes' => 'Speeds up coding significantly. Sometimes suggestions need tweaking but overall very helpful.',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Cursor',
                'slug' => 'cursor',
                'description' => 'AI-first code editor built for pair programming with AI.',
                'long_description' => 'Cursor is a fork of VS Code designed from the ground up for AI integration. It offers natural language editing, codebase-wide understanding, and seamless AI collaboration.',
                'website_url' => 'https://cursor.sh',
                'documentation_url' => 'https://docs.cursor.sh',
                'category' => 'Code Generation',
                'pricing_model' => 'freemium',
                'price_description' => 'Free tier, Pro at $20/month',
                'features' => ['Natural language editing', 'Codebase chat', 'Multi-file editing', 'Terminal integration'],
                'use_cases' => ['Full-stack development', 'Refactoring', 'Bug fixing', 'Learning codebases'],
                'integrations' => ['Git', 'Extensions marketplace', 'Terminal'],
                'ryan_rating' => 9,
                'ryan_notes' => 'The future of coding. Being able to describe changes in natural language is incredible.',
                'is_featured' => true,
                'is_active' => true,
            ],

            // Image Generation
            [
                'name' => 'Midjourney',
                'slug' => 'midjourney',
                'description' => 'AI art generator creating stunning images from text descriptions.',
                'long_description' => 'Midjourney specializes in creating artistic, imaginative images. It\'s particularly strong at producing creative and aesthetically pleasing visuals with unique artistic styles.',
                'website_url' => 'https://midjourney.com',
                'documentation_url' => 'https://docs.midjourney.com',
                'category' => 'Image Generation',
                'pricing_model' => 'paid',
                'price_description' => 'Basic $10/month, Standard $30/month, Pro $60/month',
                'features' => ['High-quality outputs', 'Multiple aspect ratios', 'Style variations', 'Upscaling'],
                'use_cases' => ['Concept art', 'Marketing materials', 'Book covers', 'Social media content'],
                'integrations' => ['Discord'],
                'ryan_rating' => 8,
                'ryan_notes' => 'Creates beautiful, artistic images. The Discord interface takes some getting used to.',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'DALL-E 3',
                'slug' => 'dall-e-3',
                'description' => 'OpenAI\'s image generation model with excellent prompt understanding.',
                'long_description' => 'DALL-E 3 excels at accurately interpreting complex prompts and generating coherent, detailed images. Integrated directly into ChatGPT Plus.',
                'website_url' => 'https://openai.com/dall-e-3',
                'documentation_url' => 'https://platform.openai.com/docs/guides/images',
                'category' => 'Image Generation',
                'pricing_model' => 'paid',
                'price_description' => 'Included with ChatGPT Plus ($20/month) or via API',
                'features' => ['Prompt accuracy', 'Safety features', 'Multiple sizes', 'ChatGPT integration'],
                'use_cases' => ['Product mockups', 'Illustrations', 'Educational content', 'Presentations'],
                'integrations' => ['ChatGPT', 'API', 'Bing Image Creator'],
                'ryan_rating' => 7,
                'is_featured' => false,
                'is_active' => true,
            ],

            // Writing & Content
            [
                'name' => 'Jasper',
                'slug' => 'jasper',
                'description' => 'AI writing assistant for marketing copy, blog posts, and content creation.',
                'long_description' => 'Jasper (formerly Jarvis) is tailored for marketers and content creators. It offers templates, brand voice customization, and team collaboration features.',
                'website_url' => 'https://jasper.ai',
                'documentation_url' => 'https://help.jasper.ai',
                'category' => 'Writing & Content',
                'pricing_model' => 'paid',
                'price_description' => 'Creator at $49/month, Teams at $125/month',
                'features' => ['50+ templates', 'Brand voice', 'SEO optimization', 'Team collaboration', 'Plagiarism checker'],
                'use_cases' => ['Blog writing', 'Ad copy', 'Email campaigns', 'Social media posts'],
                'integrations' => ['Chrome extension', 'Surfer SEO', 'Grammarly'],
                'ryan_rating' => 7,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Grammarly',
                'slug' => 'grammarly',
                'description' => 'AI-powered writing assistant for grammar, spelling, and style.',
                'long_description' => 'Grammarly goes beyond spell-check to help you write clearly and effectively. It provides real-time suggestions for grammar, punctuation, style, and tone.',
                'website_url' => 'https://grammarly.com',
                'documentation_url' => 'https://support.grammarly.com',
                'category' => 'Writing & Content',
                'pricing_model' => 'freemium',
                'price_description' => 'Free tier, Premium at $12/month, Business at $15/user/month',
                'features' => ['Grammar checking', 'Style suggestions', 'Tone detection', 'Plagiarism detection', 'Writing insights'],
                'use_cases' => ['Professional emails', 'Academic writing', 'Content editing', 'Business communication'],
                'integrations' => ['Chrome', 'Microsoft Office', 'Gmail', 'Slack', 'Google Docs'],
                'ryan_rating' => 8,
                'ryan_notes' => 'Essential for professional communication. Catches subtle errors I always miss.',
                'is_featured' => false,
                'is_active' => true,
            ],

            // Productivity
            [
                'name' => 'Notion AI',
                'slug' => 'notion-ai',
                'description' => 'AI writing assistant integrated into Notion workspace.',
                'long_description' => 'Notion AI helps you write, brainstorm, edit, and summarize directly in your Notion pages. It understands your workspace context for more relevant suggestions.',
                'website_url' => 'https://notion.so/product/ai',
                'documentation_url' => 'https://notion.so/help/guides/what-is-notion-ai',
                'category' => 'Productivity',
                'pricing_model' => 'paid',
                'price_description' => '$10/user/month as add-on to Notion',
                'features' => ['Context-aware writing', 'Summarization', 'Translation', 'Action items extraction'],
                'use_cases' => ['Meeting notes', 'Project documentation', 'Content drafting', 'Knowledge management'],
                'integrations' => ['Notion'],
                'ryan_rating' => 7,
                'is_featured' => false,
                'is_active' => true,
            ],

            // Data & Analytics
            [
                'name' => 'Julius AI',
                'slug' => 'julius-ai',
                'description' => 'AI-powered data analyst that can chat with your data.',
                'long_description' => 'Julius AI allows you to upload data files and ask questions in natural language. It can create visualizations, perform statistical analysis, and generate insights.',
                'website_url' => 'https://julius.ai',
                'category' => 'Data & Analytics',
                'pricing_model' => 'freemium',
                'price_description' => 'Free tier, Pro at $20/month',
                'features' => ['Natural language queries', 'Data visualization', 'Statistical analysis', 'Export results'],
                'use_cases' => ['Data exploration', 'Report generation', 'Trend analysis', 'Business intelligence'],
                'integrations' => ['CSV', 'Excel', 'Google Sheets'],
                'ryan_rating' => 8,
                'is_featured' => false,
                'is_active' => true,
            ],

            // Design & UI/UX
            [
                'name' => 'Figma AI',
                'slug' => 'figma-ai',
                'description' => 'AI features in Figma for design automation and assistance.',
                'long_description' => 'Figma\'s AI capabilities help designers work faster with features like auto-layout suggestions, content generation, and design system recommendations.',
                'website_url' => 'https://figma.com',
                'documentation_url' => 'https://help.figma.com',
                'category' => 'Design & UI/UX',
                'pricing_model' => 'freemium',
                'price_description' => 'Free tier, Professional at $12/editor/month',
                'features' => ['Design suggestions', 'Auto-layout', 'Content generation', 'Component recommendations'],
                'use_cases' => ['UI design', 'Prototyping', 'Design systems', 'Collaboration'],
                'integrations' => ['Plugins ecosystem', 'Dev Mode', 'FigJam'],
                'ryan_rating' => 9,
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        $created = 0;
        foreach ($tools as $toolData) {
            $categoryName = $toolData['category'];
            unset($toolData['category']);

            $category = Category::where('name', $categoryName)->first();
            if (! $category) {
                $this->command->warn("Category '{$categoryName}' not found for tool '{$toolData['name']}'");
                continue;
            }

            Tool::updateOrCreate(
                ['slug' => $toolData['slug']],
                array_merge($toolData, [
                    'category_id' => $category->id,
                    'first_reviewed_at' => isset($toolData['ryan_rating']) ? now() : null,
                    'views_count' => rand(10, 1000),
                ])
            );

            $created++;
        }

        $this->command->info("✓ Created {$created} tools");
    }
}
