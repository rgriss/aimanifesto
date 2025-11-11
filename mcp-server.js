#!/usr/bin/env node

/**
 * MCP Server for AI Manifesto Tool Creation API
 *
 * This server exposes the AI Manifesto API to Claude via MCP protocol.
 * It allows Claude to create tool entries directly in your directory.
 */

import { Server } from '@modelcontextprotocol/sdk/server/index.js';
import { StdioServerTransport } from '@modelcontextprotocol/sdk/server/stdio.js';
import {
  CallToolRequestSchema,
  ListToolsRequestSchema,
} from '@modelcontextprotocol/sdk/types.js';

// Read API token and base URL from environment
const API_TOKEN = process.env.AIMANIFESTO_API_TOKEN;
const API_BASE_URL = process.env.AIMANIFESTO_API_URL || 'http://localhost:8000';

if (!API_TOKEN) {
  console.error('Error: AIMANIFESTO_API_TOKEN environment variable is required');
  process.exit(1);
}

// Create MCP server
const server = new Server(
  {
    name: 'aimanifesto',
    version: '1.0.0',
  },
  {
    capabilities: {
      tools: {},
    },
  }
);

// Define all available tools
server.setRequestHandler(ListToolsRequestSchema, async () => {
  return {
    tools: [
      // ===== TOOL OPERATIONS =====
      {
        name: 'list_ai_tools',
        description: 'List all AI tools in the directory. Supports filtering by category, search term, featured status, and pagination.',
        inputSchema: {
          type: 'object',
          properties: {
            category: {
              type: 'string',
              description: 'Filter by category slug or name (optional)',
            },
            search: {
              type: 'string',
              description: 'Search tools by name or description (optional)',
            },
            featured: {
              type: 'boolean',
              description: 'Show only featured tools (optional)',
            },
            per_page: {
              type: 'integer',
              description: 'Number of tools per page (default: 15, max: 100)',
            },
          },
        },
      },
      {
        name: 'get_ai_tool',
        description: 'Get detailed information about a specific AI tool by its slug.',
        inputSchema: {
          type: 'object',
          properties: {
            slug: {
              type: 'string',
              description: 'The slug of the tool (e.g., "claude-code", "chatgpt")',
            },
          },
          required: ['slug'],
        },
      },
      {
        name: 'add_ai_tool',
        description: 'Add a new AI tool to the AI Manifesto directory. Use this when the user wants to add a tool they\'re describing or researching.',
        inputSchema: {
          type: 'object',
          properties: {
            name: {
              type: 'string',
              description: 'Name of the AI tool',
            },
            description: {
              type: 'string',
              description: 'Brief description of the tool (1-2 sentences)',
            },
            long_description: {
              type: 'string',
              description: 'Detailed description with more context (optional)',
            },
            website_url: {
              type: 'string',
              description: 'Main website URL for the tool',
            },
            documentation_url: {
              type: 'string',
              description: 'Documentation URL (optional)',
            },
            logo_url: {
              type: 'string',
              description: 'Logo image URL (optional)',
            },
            screenshot_url: {
              type: 'string',
              description: 'Screenshot image URL (optional)',
            },
            category: {
              type: 'string',
              description: 'Category name (e.g., "AI Assistants", "Code Generation", "Image Generation"). Will be created if it doesn\'t exist.',
            },
            pricing_model: {
              type: 'string',
              enum: ['free', 'freemium', 'paid', 'enterprise'],
              description: 'Pricing model',
            },
            price_description: {
              type: 'string',
              description: 'Details about pricing (e.g., "$20/month for Pro")',
            },
            company_name: {
              type: 'string',
              description: 'Parent company/organization behind the tool (e.g., "OpenAI", "Google", "Anthropic")',
            },
            popularity_tier: {
              type: 'string',
              enum: ['mainstream', 'well_known', 'growing', 'niche', 'emerging'],
              description: 'Market recognition level: mainstream (household name), well_known (known in industry), growing (gaining recognition), niche (specialized audience), emerging (new/unknown)',
            },
            momentum_score: {
              type: 'integer',
              minimum: 1,
              maximum: 5,
              description: 'Trajectory assessment (1-5): 1=Strongly declining, 2=Declining, 3=Stable, 4=Growing, 5=Rapidly growing',
            },
            features: {
              type: 'array',
              items: { type: 'string' },
              description: 'Array of key features',
            },
            use_cases: {
              type: 'array',
              items: { type: 'string' },
              description: 'Array of use cases',
            },
            integrations: {
              type: 'array',
              items: { type: 'string' },
              description: 'Array of available integrations',
            },
            ryan_rating: {
              type: 'integer',
              minimum: 1,
              maximum: 10,
              description: 'Personal rating from 1-10 (optional)',
            },
            ryan_notes: {
              type: 'string',
              description: 'Personal notes about the tool (optional)',
            },
            is_featured: {
              type: 'boolean',
              description: 'Whether to feature this tool (default: false)',
            },
            is_active: {
              type: 'boolean',
              description: 'Whether the tool is active (default: true)',
            },
          },
          required: ['name', 'description', 'website_url', 'category'],
        },
      },
      {
        name: 'update_ai_tool',
        description: 'Update an existing AI tool. All fields are optional - only provide the fields you want to update.',
        inputSchema: {
          type: 'object',
          properties: {
            slug: {
              type: 'string',
              description: 'The slug of the tool to update',
            },
            name: {
              type: 'string',
              description: 'Updated name',
            },
            description: {
              type: 'string',
              description: 'Updated brief description',
            },
            long_description: {
              type: 'string',
              description: 'Updated detailed description',
            },
            website_url: {
              type: 'string',
              description: 'Updated website URL',
            },
            documentation_url: {
              type: 'string',
              description: 'Updated documentation URL',
            },
            logo_url: {
              type: 'string',
              description: 'Updated logo URL',
            },
            screenshot_url: {
              type: 'string',
              description: 'Updated screenshot URL',
            },
            category: {
              type: 'string',
              description: 'Updated category name',
            },
            pricing_model: {
              type: 'string',
              enum: ['free', 'freemium', 'paid', 'enterprise'],
              description: 'Updated pricing model',
            },
            price_description: {
              type: 'string',
              description: 'Updated pricing details',
            },
            company_name: {
              type: 'string',
              description: 'Updated company name',
            },
            popularity_tier: {
              type: 'string',
              enum: ['mainstream', 'well_known', 'growing', 'niche', 'emerging'],
              description: 'Updated popularity tier',
            },
            momentum_score: {
              type: 'integer',
              minimum: 1,
              maximum: 5,
              description: 'Updated momentum score (1-5)',
            },
            features: {
              type: 'array',
              items: { type: 'string' },
              description: 'Updated features array',
            },
            use_cases: {
              type: 'array',
              items: { type: 'string' },
              description: 'Updated use cases array',
            },
            integrations: {
              type: 'array',
              items: { type: 'string' },
              description: 'Updated integrations array',
            },
            ryan_rating: {
              type: 'integer',
              minimum: 1,
              maximum: 10,
              description: 'Updated rating',
            },
            ryan_notes: {
              type: 'string',
              description: 'Updated personal notes',
            },
            is_featured: {
              type: 'boolean',
              description: 'Updated featured status',
            },
            is_active: {
              type: 'boolean',
              description: 'Updated active status',
            },
          },
          required: ['slug'],
        },
      },
      {
        name: 'delete_ai_tool',
        description: 'Delete an AI tool from the directory. This action cannot be undone.',
        inputSchema: {
          type: 'object',
          properties: {
            slug: {
              type: 'string',
              description: 'The slug of the tool to delete',
            },
          },
          required: ['slug'],
        },
      },

      // ===== CATEGORY OPERATIONS =====
      {
        name: 'list_categories',
        description: 'List all categories in the directory. Shows tool counts for each category.',
        inputSchema: {
          type: 'object',
          properties: {
            search: {
              type: 'string',
              description: 'Search categories by name or description (optional)',
            },
          },
        },
      },
      {
        name: 'get_category',
        description: 'Get detailed information about a specific category by its slug.',
        inputSchema: {
          type: 'object',
          properties: {
            slug: {
              type: 'string',
              description: 'The slug of the category (e.g., "ai-assistants", "code-generation")',
            },
          },
          required: ['slug'],
        },
      },
      {
        name: 'add_category',
        description: 'Add a new category to the directory.',
        inputSchema: {
          type: 'object',
          properties: {
            name: {
              type: 'string',
              description: 'Name of the category',
            },
            description: {
              type: 'string',
              description: 'Description of the category',
            },
            icon: {
              type: 'string',
              description: 'Emoji icon for the category (optional, default: 🔧)',
            },
            is_active: {
              type: 'boolean',
              description: 'Whether the category is active (default: true)',
            },
          },
          required: ['name', 'description'],
        },
      },
      {
        name: 'update_category',
        description: 'Update an existing category. All fields are optional - only provide the fields you want to update.',
        inputSchema: {
          type: 'object',
          properties: {
            slug: {
              type: 'string',
              description: 'The slug of the category to update',
            },
            name: {
              type: 'string',
              description: 'Updated name',
            },
            description: {
              type: 'string',
              description: 'Updated description',
            },
            icon: {
              type: 'string',
              description: 'Updated emoji icon',
            },
            is_active: {
              type: 'boolean',
              description: 'Updated active status',
            },
            sort_order: {
              type: 'integer',
              description: 'Updated sort order',
            },
          },
          required: ['slug'],
        },
      },
      {
        name: 'delete_category',
        description: 'Delete a category from the directory. Cannot delete categories that have associated tools.',
        inputSchema: {
          type: 'object',
          properties: {
            slug: {
              type: 'string',
              description: 'The slug of the category to delete',
            },
          },
          required: ['slug'],
        },
      },

      // ===== TOOL INTELLIGENCE OPERATIONS =====
      {
        name: 'get_tool_intelligence',
        description: 'Get business intelligence and market research data for a specific AI tool.',
        inputSchema: {
          type: 'object',
          properties: {
            slug: {
              type: 'string',
              description: 'The slug of the tool (e.g., "claude", "chatgpt")',
            },
          },
          required: ['slug'],
        },
      },
      {
        name: 'update_tool_intelligence',
        description: 'Update business intelligence and market research data for an AI tool. Creates intelligence record if it doesn\'t exist. All fields are optional.',
        inputSchema: {
          type: 'object',
          properties: {
            slug: {
              type: 'string',
              description: 'The slug of the tool to update',
            },
            // Company Metadata
            founded_year: {
              type: 'integer',
              description: 'Year company was founded',
            },
            tool_launched_year: {
              type: 'integer',
              description: 'Year the tool was launched',
            },
            company_status: {
              type: 'string',
              enum: ['private', 'public', 'acquired', 'subsidiary', 'open_source'],
              description: 'Company ownership/status type',
            },
            stock_ticker: {
              type: 'string',
              description: 'Stock ticker symbol (for public companies)',
            },
            parent_company: {
              type: 'string',
              description: 'Parent company name (for subsidiaries)',
            },
            acquisition_date: {
              type: 'string',
              description: 'Date of acquisition',
            },
            headquarters: {
              type: 'string',
              description: 'Headquarters location',
            },
            employee_count_range: {
              type: 'string',
              enum: ['1-10', '11-50', '51-200', '201-500', '501-1000', '1000-5000', '5000-10000', '10000+'],
              description: 'Company employee count range',
            },
            // Market Position
            estimated_users: {
              type: 'string',
              enum: ['< 10K', '10K-100K', '100K-1M', '1M-10M', '10M-50M', '50M-100M', '100M+'],
              description: 'Estimated user base size',
            },
            target_market: {
              type: 'array',
              items: {
                type: 'string',
                enum: ['individual', 'small_business', 'mid_market', 'enterprise', 'developer', 'creative_professional'],
              },
              description: 'Target market segments',
            },
            market_position: {
              type: 'string',
              enum: ['market_leader', 'major_player', 'challenger', 'niche_specialist', 'emerging'],
              description: 'Market position classification',
            },
            primary_competitors: {
              type: 'array',
              items: { type: 'string' },
              description: 'Array of primary competitor names',
            },
            // Momentum & Sentiment
            momentum_notes: {
              type: 'string',
              description: 'Explanation of momentum score from primary table',
            },
            customer_sentiment: {
              type: 'string',
              enum: ['very_positive', 'positive', 'mixed', 'negative', 'very_negative'],
              description: 'Customer sentiment assessment',
            },
            sentiment_notes: {
              type: 'string',
              description: 'Notes about customer sentiment',
            },
            last_major_update: {
              type: 'string',
              description: 'Date/description of last major update',
            },
            // Financial
            funding_stage: {
              type: 'string',
              enum: ['bootstrapped', 'seed', 'series_a', 'series_b', 'series_c+', 'public', 'profitable', 'acquired'],
              description: 'Current funding stage',
            },
            latest_funding_amount: {
              type: 'string',
              description: 'Latest funding round amount (e.g., "$50M")',
            },
            latest_funding_date: {
              type: 'string',
              description: 'Date of latest funding',
            },
            estimated_annual_revenue: {
              type: 'string',
              enum: ['< $1M', '$1M-$10M', '$10M-$50M', '$50M-$100M', '$100M-$500M', '$500M-$1B', '$1B+'],
              description: 'Estimated annual revenue range',
            },
            // Competitive Intelligence
            key_differentiators: {
              type: 'array',
              items: { type: 'string' },
              description: 'Array of key differentiators',
            },
            strengths: {
              type: 'array',
              items: { type: 'string' },
              description: 'Array of strengths',
            },
            weaknesses: {
              type: 'array',
              items: { type: 'string' },
              description: 'Array of weaknesses',
            },
            market_threats: {
              type: 'string',
              description: 'Market threats description',
            },
            growth_opportunities: {
              type: 'string',
              description: 'Growth opportunities description',
            },
            // Analyst Notes
            strategic_notes: {
              type: 'string',
              description: 'Strategic analysis notes',
            },
            analyst_summary: {
              type: 'string',
              description: 'Analyst summary',
            },
            last_researched_at: {
              type: 'string',
              description: 'When this data was last researched (ISO 8601 date)',
            },
          },
          required: ['slug'],
        },
      },
    ],
  };
});

// Helper function to make API calls
async function makeApiCall(endpoint, method = 'GET', body = null) {
  const options = {
    method,
    headers: {
      'Authorization': `Bearer ${API_TOKEN}`,
      'Content-Type': 'application/json',
    },
  };

  if (body && method !== 'GET') {
    options.body = JSON.stringify(body);
  }

  const response = await fetch(`${API_BASE_URL}${endpoint}`, options);
  const data = await response.json();

  return { response, data };
}

// Helper function to format tool list
function formatToolsList(tools) {
  if (!tools || tools.length === 0) {
    return 'No tools found.';
  }

  return tools.map((tool, i) =>
    `${i + 1}. ${tool.name} (${tool.slug})
   📁 ${tool.category.name}
   💰 ${tool.pricing_model}
   ${tool.is_featured ? '⭐ Featured' : ''}
   🔗 ${tool.url}`
  ).join('\n\n');
}

// Helper function to format category list
function formatCategoriesList(categories) {
  if (!categories || categories.length === 0) {
    return 'No categories found.';
  }

  return categories.map((cat, i) =>
    `${i + 1}. ${cat.icon} ${cat.name} (${cat.slug})
   ${cat.description}
   📊 ${cat.tools_count} tools
   🔗 ${cat.url}`
  ).join('\n\n');
}

// Handle tool calls
server.setRequestHandler(CallToolRequestSchema, async (request) => {
  const toolName = request.params.name;
  const args = request.params.arguments || {};

  try {
    // ===== TOOL OPERATIONS =====

    if (toolName === 'list_ai_tools') {
      const params = new URLSearchParams();
      if (args.category) params.append('category', args.category);
      if (args.search) params.append('search', args.search);
      if (args.featured) params.append('featured', 'true');
      if (args.per_page) params.append('per_page', args.per_page);

      const { response, data } = await makeApiCall(`/api/tools?${params}`);

      if (!response.ok) {
        return {
          content: [{ type: 'text', text: `Error: ${data.message || 'Failed to list tools'}` }],
          isError: true,
        };
      }

      const toolsList = formatToolsList(data.data);
      const meta = data.meta ? `\n\nPage ${data.meta.current_page} of ${data.meta.last_page} (${data.meta.total} total tools)` : '';

      return {
        content: [{
          type: 'text',
          text: `📚 AI Tools Directory\n\n${toolsList}${meta}`,
        }],
      };
    }

    if (toolName === 'get_ai_tool') {
      const { response, data } = await makeApiCall(`/api/tools/${args.slug}`);

      if (!response.ok) {
        return {
          content: [{ type: 'text', text: data.message || 'Tool not found' }],
          isError: true,
        };
      }

      const tool = data.data;
      let details = `🔧 ${tool.name}\n\n`;
      details += `📝 ${tool.description}\n\n`;
      if (tool.long_description) details += `${tool.long_description}\n\n`;
      details += `📁 Category: ${tool.category.name}\n`;
      details += `🔗 Website: ${tool.website_url}\n`;
      if (tool.documentation_url) details += `📖 Docs: ${tool.documentation_url}\n`;
      details += `💰 Pricing: ${tool.pricing_model}`;
      if (tool.price_description) details += ` - ${tool.price_description}`;
      details += `\n`;
      if (tool.ryan_rating) details += `⭐ Rating: ${tool.ryan_rating}/10\n`;
      if (tool.ryan_notes) details += `💭 Notes: "${tool.ryan_notes}"\n`;
      if (tool.features && tool.features.length > 0) {
        details += `\n✨ Features:\n${tool.features.map(f => `  • ${f}`).join('\n')}\n`;
      }
      if (tool.use_cases && tool.use_cases.length > 0) {
        details += `\n🎯 Use Cases:\n${tool.use_cases.map(u => `  • ${u}`).join('\n')}\n`;
      }
      if (tool.integrations && tool.integrations.length > 0) {
        details += `\n🔌 Integrations: ${tool.integrations.join(', ')}\n`;
      }
      details += `\n👁️ Views: ${tool.views_count || 0}`;
      details += `\n${tool.is_featured ? '⭐ Featured Tool' : ''}`;
      details += `\n\n🔗 View online: ${tool.url}`;

      return {
        content: [{ type: 'text', text: details }],
      };
    }

    if (toolName === 'add_ai_tool') {
      const { response, data } = await makeApiCall('/api/tools', 'POST', args);

      if (!response.ok) {
        if (response.status === 409) {
          return {
            content: [{
              type: 'text',
              text: `Tool "${args.name}" already exists!\n\nExisting tool: ${data.existing_tool.url}\n\nYou can view or edit it in the admin panel.`,
            }],
          };
        }

        if (response.status === 422) {
          const errors = Object.entries(data.errors || {})
            .map(([field, messages]) => `- ${field}: ${messages.join(', ')}`)
            .join('\n');
          return {
            content: [{ type: 'text', text: `Validation failed:\n\n${errors}` }],
            isError: true,
          };
        }

        return {
          content: [{ type: 'text', text: `Error: ${data.message || 'Unknown error'}` }],
          isError: true,
        };
      }

      const tool = data.data;
      return {
        content: [{
          type: 'text',
          text: `✓ Successfully added "${tool.name}" to your AI tools directory!

📁 Category: ${tool.category.name}
🔗 View it here: ${tool.url}
💰 Pricing: ${tool.pricing_model || 'Not specified'}
${tool.is_featured ? '⭐ Featured tool!' : ''}

You can edit or add more details in your admin panel at ${API_BASE_URL}/admin/tools`,
        }],
      };
    }

    if (toolName === 'update_ai_tool') {
      const { slug, ...updateData } = args;
      const { response, data } = await makeApiCall(`/api/tools/${slug}`, 'PUT', updateData);

      if (!response.ok) {
        if (response.status === 404) {
          return {
            content: [{ type: 'text', text: `Tool "${slug}" not found.` }],
            isError: true,
          };
        }

        if (response.status === 422) {
          const errors = Object.entries(data.errors || {})
            .map(([field, messages]) => `- ${field}: ${messages.join(', ')}`)
            .join('\n');
          return {
            content: [{ type: 'text', text: `Validation failed:\n\n${errors}` }],
            isError: true,
          };
        }

        return {
          content: [{ type: 'text', text: `Error: ${data.message || 'Unknown error'}` }],
          isError: true,
        };
      }

      const tool = data.data;
      return {
        content: [{
          type: 'text',
          text: `✓ Successfully updated "${tool.name}"!

🔗 View it here: ${tool.url}

Updated fields: ${Object.keys(updateData).join(', ')}`,
        }],
      };
    }

    if (toolName === 'delete_ai_tool') {
      const { response, data } = await makeApiCall(`/api/tools/${args.slug}`, 'DELETE');

      if (!response.ok) {
        return {
          content: [{ type: 'text', text: data.message || 'Failed to delete tool' }],
          isError: true,
        };
      }

      return {
        content: [{
          type: 'text',
          text: `✓ ${data.message}\n\n⚠️ This action cannot be undone.`,
        }],
      };
    }

    // ===== CATEGORY OPERATIONS =====

    if (toolName === 'list_categories') {
      const params = new URLSearchParams();
      if (args.search) params.append('search', args.search);

      const { response, data } = await makeApiCall(`/api/categories?${params}`);

      if (!response.ok) {
        return {
          content: [{ type: 'text', text: `Error: ${data.message || 'Failed to list categories'}` }],
          isError: true,
        };
      }

      const categoriesList = formatCategoriesList(data.data);
      return {
        content: [{
          type: 'text',
          text: `📂 Categories\n\n${categoriesList}`,
        }],
      };
    }

    if (toolName === 'get_category') {
      const { response, data } = await makeApiCall(`/api/categories/${args.slug}`);

      if (!response.ok) {
        return {
          content: [{ type: 'text', text: data.message || 'Category not found' }],
          isError: true,
        };
      }

      const cat = data.data;
      const details = `${cat.icon} ${cat.name}

${cat.description}

📊 ${cat.tools_count} tools in this category
📌 Sort order: ${cat.sort_order}
${cat.is_active ? '✓ Active' : '✗ Inactive'}

🔗 View online: ${cat.url}`;

      return {
        content: [{ type: 'text', text: details }],
      };
    }

    if (toolName === 'add_category') {
      const { response, data } = await makeApiCall('/api/categories', 'POST', args);

      if (!response.ok) {
        if (response.status === 409) {
          return {
            content: [{
              type: 'text',
              text: `Category "${args.name}" already exists!\n\nExisting category: ${data.existing_category.url}`,
            }],
          };
        }

        if (response.status === 422) {
          const errors = Object.entries(data.errors || {})
            .map(([field, messages]) => `- ${field}: ${messages.join(', ')}`)
            .join('\n');
          return {
            content: [{ type: 'text', text: `Validation failed:\n\n${errors}` }],
            isError: true,
          };
        }

        return {
          content: [{ type: 'text', text: `Error: ${data.message || 'Unknown error'}` }],
          isError: true,
        };
      }

      const cat = data.data;
      return {
        content: [{
          type: 'text',
          text: `✓ Successfully added category "${cat.icon} ${cat.name}"!

🔗 View it here: ${cat.url}`,
        }],
      };
    }

    if (toolName === 'update_category') {
      const { slug, ...updateData } = args;
      const { response, data } = await makeApiCall(`/api/categories/${slug}`, 'PUT', updateData);

      if (!response.ok) {
        if (response.status === 404) {
          return {
            content: [{ type: 'text', text: `Category "${slug}" not found.` }],
            isError: true,
          };
        }

        if (response.status === 422) {
          const errors = Object.entries(data.errors || {})
            .map(([field, messages]) => `- ${field}: ${messages.join(', ')}`)
            .join('\n');
          return {
            content: [{ type: 'text', text: `Validation failed:\n\n${errors}` }],
            isError: true,
          };
        }

        return {
          content: [{ type: 'text', text: `Error: ${data.message || 'Unknown error'}` }],
          isError: true,
        };
      }

      const cat = data.data;
      return {
        content: [{
          type: 'text',
          text: `✓ Successfully updated "${cat.name}"!

🔗 View it here: ${cat.url}

Updated fields: ${Object.keys(updateData).join(', ')}`,
        }],
      };
    }

    if (toolName === 'delete_category') {
      const { response, data } = await makeApiCall(`/api/categories/${args.slug}`, 'DELETE');

      if (!response.ok) {
        return {
          content: [{ type: 'text', text: data.message || 'Failed to delete category' }],
          isError: true,
        };
      }

      return {
        content: [{
          type: 'text',
          text: `✓ ${data.message}\n\n⚠️ This action cannot be undone.`,
        }],
      };
    }

    // ===== TOOL INTELLIGENCE OPERATIONS =====

    if (toolName === 'get_tool_intelligence') {
      const { response, data } = await makeApiCall(`/api/tools/${args.slug}/intelligence`);

      if (!response.ok) {
        return {
          content: [{ type: 'text', text: data.message || 'Failed to get intelligence data' }],
          isError: true,
        };
      }

      if (!data.data) {
        return {
          content: [{
            type: 'text',
            text: `📊 No intelligence data found for this tool.\n\nUse update_tool_intelligence to add business intelligence and market research data.`,
          }],
        };
      }

      const intel = data.data;
      let details = `📊 Business Intelligence for ${args.slug}\n\n`;
      details += `Data Completeness: ${intel.data_completeness_score}%\n\n`;

      // Company Metadata
      if (intel.founded_year || intel.company_status || intel.headquarters) {
        details += `🏢 Company Information:\n`;
        if (intel.founded_year) details += `  • Founded: ${intel.founded_year}\n`;
        if (intel.tool_launched_year) details += `  • Tool Launched: ${intel.tool_launched_year}\n`;
        if (intel.company_status) details += `  • Status: ${intel.company_status}\n`;
        if (intel.stock_ticker) details += `  • Ticker: ${intel.stock_ticker}\n`;
        if (intel.parent_company) details += `  • Parent: ${intel.parent_company}\n`;
        if (intel.headquarters) details += `  • HQ: ${intel.headquarters}\n`;
        if (intel.employee_count_range) details += `  • Employees: ${intel.employee_count_range}\n`;
        details += `\n`;
      }

      // Market Position
      if (intel.estimated_users || intel.market_position) {
        details += `📈 Market Position:\n`;
        if (intel.estimated_users) details += `  • Users: ${intel.estimated_users}\n`;
        if (intel.market_position) details += `  • Position: ${intel.market_position}\n`;
        if (intel.target_market && intel.target_market.length > 0) {
          details += `  • Target Markets: ${intel.target_market.join(', ')}\n`;
        }
        if (intel.primary_competitors && intel.primary_competitors.length > 0) {
          details += `  • Competitors: ${intel.primary_competitors.join(', ')}\n`;
        }
        details += `\n`;
      }

      // Financial
      if (intel.funding_stage || intel.estimated_annual_revenue) {
        details += `💰 Financial:\n`;
        if (intel.funding_stage) details += `  • Funding: ${intel.funding_stage}\n`;
        if (intel.latest_funding_amount) details += `  • Latest Round: ${intel.latest_funding_amount} (${intel.latest_funding_date || 'date unknown'})\n`;
        if (intel.estimated_annual_revenue) details += `  • Revenue: ${intel.estimated_annual_revenue}\n`;
        details += `\n`;
      }

      // Sentiment
      if (intel.customer_sentiment) {
        details += `💭 Sentiment: ${intel.customer_sentiment}\n`;
        if (intel.sentiment_notes) details += `   "${intel.sentiment_notes}"\n`;
        details += `\n`;
      }

      // Competitive Intelligence
      if (intel.key_differentiators && intel.key_differentiators.length > 0) {
        details += `✨ Key Differentiators:\n${intel.key_differentiators.map(d => `  • ${d}`).join('\n')}\n\n`;
      }
      if (intel.strengths && intel.strengths.length > 0) {
        details += `💪 Strengths:\n${intel.strengths.map(s => `  • ${s}`).join('\n')}\n\n`;
      }
      if (intel.weaknesses && intel.weaknesses.length > 0) {
        details += `⚠️ Weaknesses:\n${intel.weaknesses.map(w => `  • ${w}`).join('\n')}\n\n`;
      }

      // Analyst Notes
      if (intel.analyst_summary) {
        details += `📝 Analyst Summary:\n${intel.analyst_summary}\n\n`;
      }

      if (intel.last_researched_at) {
        details += `🔍 Last Researched: ${new Date(intel.last_researched_at).toLocaleDateString()}\n`;
      }

      return {
        content: [{ type: 'text', text: details }],
      };
    }

    if (toolName === 'update_tool_intelligence') {
      const { slug, ...intelligenceData } = args;
      const { response, data } = await makeApiCall(`/api/tools/${slug}/intelligence`, 'PUT', intelligenceData);

      if (!response.ok) {
        if (response.status === 404) {
          return {
            content: [{ type: 'text', text: `Tool "${slug}" not found.` }],
            isError: true,
          };
        }

        if (response.status === 422) {
          const errors = Object.entries(data.errors || {})
            .map(([field, messages]) => `- ${field}: ${messages.join(', ')}`)
            .join('\n');
          return {
            content: [{ type: 'text', text: `Validation failed:\n\n${errors}` }],
            isError: true,
          };
        }

        return {
          content: [{ type: 'text', text: `Error: ${data.message || 'Unknown error'}` }],
          isError: true,
        };
      }

      const intel = data.data;
      return {
        content: [{
          type: 'text',
          text: `✓ Successfully updated intelligence data for "${slug}"!

📊 Data Completeness: ${intel.data_completeness_score}%
🔗 View tool: /tools/${slug}

Updated ${Object.keys(intelligenceData).length} field(s)`,
        }],
      };
    }

    // Unknown tool
    throw new Error(`Unknown tool: ${toolName}`);

  } catch (error) {
    return {
      content: [{
        type: 'text',
        text: `Failed to execute ${toolName}: ${error.message}`,
      }],
      isError: true,
    };
  }
});

// Start the server
async function main() {
  const transport = new StdioServerTransport();
  await server.connect(transport);
  console.error('AI Manifesto MCP server running on stdio');
}

main().catch((error) => {
  console.error('Fatal error:', error);
  process.exit(1);
});
