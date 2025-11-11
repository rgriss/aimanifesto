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

// Define the tool
server.setRequestHandler(ListToolsRequestSchema, async () => {
  return {
    tools: [
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
    ],
  };
});

// Handle tool calls
server.setRequestHandler(CallToolRequestSchema, async (request) => {
  if (request.params.name !== 'add_ai_tool') {
    throw new Error(`Unknown tool: ${request.params.name}`);
  }

  const args = request.params.arguments;

  try {
    // Make API call to create tool
    const response = await fetch(`${API_BASE_URL}/api/tools`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${API_TOKEN}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(args),
    });

    const data = await response.json();

    if (!response.ok) {
      // Handle error responses
      if (response.status === 409) {
        return {
          content: [
            {
              type: 'text',
              text: `Tool "${args.name}" already exists!\n\nExisting tool: ${data.existing_tool.url}\n\nYou can view or edit it in the admin panel.`,
            },
          ],
        };
      }

      if (response.status === 422) {
        const errors = Object.entries(data.errors || {})
          .map(([field, messages]) => `- ${field}: ${messages.join(', ')}`)
          .join('\n');

        return {
          content: [
            {
              type: 'text',
              text: `Validation failed:\n\n${errors}`,
            },
          ],
        };
      }

      return {
        content: [
          {
            type: 'text',
            text: `Error creating tool: ${data.message || 'Unknown error'}`,
          },
        ],
      };
    }

    // Success! Return friendly message
    const tool = data.data;
    return {
      content: [
        {
          type: 'text',
          text: `✓ Successfully added "${tool.name}" to your AI tools directory!

📁 Category: ${tool.category.name}
🔗 View it here: ${tool.url}
💰 Pricing: ${tool.pricing_model || 'Not specified'}
${tool.is_featured ? '⭐ Featured tool!' : ''}

You can edit or add more details in your admin panel at ${API_BASE_URL}/admin/tools`,
        },
      ],
    };

  } catch (error) {
    return {
      content: [
        {
          type: 'text',
          text: `Failed to create tool: ${error.message}`,
        },
      ],
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
