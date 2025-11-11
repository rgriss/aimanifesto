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
