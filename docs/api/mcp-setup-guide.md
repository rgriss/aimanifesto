# Claude MCP Setup Guide

This guide walks you through setting up Claude Desktop to use the AI Manifesto Tool Creation API.

## Prerequisites

- Node.js 18+ installed
- Claude Desktop app installed
- API token configured in your `.env` file

## Installation Steps

### 1. Install MCP Server Dependencies

From your project directory:

```bash
cd F:\code\aimanifesto

# Install MCP SDK
npm install --prefix . -E @modelcontextprotocol/sdk
```

Or create a separate package:

```bash
# Copy the MCP files to a new directory
mkdir mcp-server
cp mcp-server.js mcp-server/
cp package-mcp.json mcp-server/package.json
cd mcp-server

# Install dependencies
npm install
```

### 2. Configure Claude Desktop

Claude Desktop reads MCP server configurations from a JSON file.

**Location of config file:**
- **Windows:** `%APPDATA%\Claude\claude_desktop_config.json`
- **macOS:** `~/Library/Application Support/Claude/claude_desktop_config.json`
- **Linux:** `~/.config/Claude/claude_desktop_config.json`

**Create or edit the file:**

```json
{
  "mcpServers": {
    "aimanifesto": {
      "command": "node",
      "args": ["F:\\code\\aimanifesto\\mcp-server.js"],
      "env": {
        "AIMANIFESTO_API_TOKEN": "your-api-token-here",
        "AIMANIFESTO_API_URL": "http://localhost:8000"
      }
    }
  }
}
```

**Important:**
- Replace `your-api-token-here` with your actual API token from `.env`
- Use double backslashes (`\\`) in Windows paths
- For production, change `AIMANIFESTO_API_URL` to your production URL

### 3. Restart Claude Desktop

Close and reopen Claude Desktop completely for changes to take effect.

### 4. Verify MCP Server is Loaded

In a new conversation with Claude, look for the MCP indicator (usually a tool icon or status indicator showing available tools).

You can also ask Claude:
```
What tools do you have access to?
```

Claude should mention the `add_ai_tool` tool.

## Testing the Integration

### Test 1: Simple Tool Addition

In Claude Desktop, try:

```
Add a test tool to my directory:
- Name: Test Tool
- Website: https://example.com
- Description: A tool for testing
- Category: Testing
```

Claude should:
1. Use the `add_ai_tool` tool
2. Call your API
3. Confirm the tool was created
4. Provide a link to view it

### Test 2: Complete Tool Addition

Try a more detailed request:

```
Add ChatGPT to my AI tools directory:
- Made by OpenAI
- Website: https://chat.openai.com
- It's a conversational AI assistant
- Pricing: Free tier available, $20/month for Plus
- Category: AI Assistants
- Features: Natural conversations, Code generation, Image analysis
- My rating: 8/10
- My notes: Great for general use, sometimes verbose
```

### Test 3: Duplicate Handling

Try adding the same tool twice - Claude should tell you it already exists.

## Troubleshooting

### "MCP server not found"

**Issue:** Claude can't find or start the MCP server.

**Solutions:**
1. Check the path in `claude_desktop_config.json` is correct
2. Verify Node.js is in your PATH: `node --version`
3. Test the server manually:
   ```bash
   cd F:\code\aimanifesto
   node mcp-server.js
   ```
   Should output: "AI Manifesto MCP server running on stdio"

### "API token invalid"

**Issue:** API returns 401 Unauthorized.

**Solutions:**
1. Verify token in `.env` matches token in `claude_desktop_config.json`
2. Check Laravel is running: `php artisan serve`
3. Test API directly with curl:
   ```bash
   curl -X POST http://localhost:8000/api/tools \
     -H "Authorization: Bearer YOUR-TOKEN" \
     -H "Content-Type: application/json" \
     -d '{"name":"Test","description":"Test","website_url":"https://test.com","category":"Test"}'
   ```

### "Tool creation fails"

**Issue:** API returns validation errors.

**Solutions:**
1. Check Claude provided all required fields (name, description, website_url, category)
2. Look at the error message Claude shows you
3. Check Laravel logs: `storage/logs/laravel.log`

### MCP server crashes

**Issue:** Server stops responding.

**Solutions:**
1. Check Node.js version: `node --version` (must be 18+)
2. Reinstall dependencies:
   ```bash
   npm install @modelcontextprotocol/sdk
   ```
3. Check for syntax errors in `mcp-server.js`

### Changes not taking effect

**Solutions:**
1. Restart Claude Desktop completely (not just closing the window)
2. Clear Claude's cache (varies by OS)
3. Try removing and re-adding the MCP server config

## Advanced Configuration

### Using Production URL

For production, update your config:

```json
{
  "mcpServers": {
    "aimanifesto": {
      "command": "node",
      "args": ["F:\\code\\aimanifesto\\mcp-server.js"],
      "env": {
        "AIMANIFESTO_API_TOKEN": "your-production-token",
        "AIMANIFESTO_API_URL": "https://aimanifesto.com"
      }
    }
  }
}
```

### Multiple Environments

You can configure multiple MCP servers for different environments:

```json
{
  "mcpServers": {
    "aimanifesto-local": {
      "command": "node",
      "args": ["F:\\code\\aimanifesto\\mcp-server.js"],
      "env": {
        "AIMANIFESTO_API_TOKEN": "local-token",
        "AIMANIFESTO_API_URL": "http://localhost:8000"
      }
    },
    "aimanifesto-production": {
      "command": "node",
      "args": ["F:\\code\\aimanifesto\\mcp-server.js"],
      "env": {
        "AIMANIFESTO_API_TOKEN": "production-token",
        "AIMANIFESTO_API_URL": "https://aimanifesto.com"
      }
    }
  }
}
```

## Usage Examples

### Natural Language Requests

Claude understands natural requests like:

**Simple:**
```
Add Midjourney to my tools directory. It's an AI image generator at midjourney.com
```

**Detailed:**
```
I want to add a new tool I've been using:

Name: Perplexity
Website: perplexity.ai
Category: AI Search
Description: AI-powered search engine with citations
Pricing: Free with Pro plan at $20/month
Features:
- Real-time web search
- Source citations
- Follow-up questions
- Multiple AI models

My rating: 7/10
Notes: Better than Google for research, but sometimes misses obvious results
```

**Conversational:**
```
I just tried this cool new tool called Runway. It does AI video generation.
Website is runwayml.com. It's got a free tier but Pro is $15/month.
Add it to my directory under Video Generation.
```

### What Claude Does Automatically

Claude will:
- ✓ Extract tool details from your description
- ✓ Format the API request properly
- ✓ Handle required vs optional fields
- ✓ Validate URLs before sending
- ✓ Ask for clarification if missing required info
- ✓ Show you the result with a link to view it
- ✓ Handle errors gracefully (duplicates, validation, etc.)

## Security Notes

- **Never commit** `claude_desktop_config.json` to git (it contains your API token)
- **Keep tokens secret** - don't share them in screenshots or public forums
- **Rotate tokens** if you suspect they've been compromised
- **Use different tokens** for local and production environments

## Updating the MCP Server

If you make changes to `mcp-server.js`:

1. Save the file
2. Restart Claude Desktop
3. Test the changes

No need to reinstall dependencies unless you change `package.json`.

## Getting Help

If you encounter issues:

1. Check the troubleshooting section above
2. Test the API directly with curl (bypass Claude)
3. Check Laravel logs: `storage/logs/laravel.log`
4. Check Node.js console output (if running manually)
5. Verify all prerequisites are met

## Next Steps

Once MCP is working:

1. Try adding a few real tools to your directory
2. Experiment with different ways of describing tools
3. Check the tools appear correctly on your site
4. Edit any details in the admin panel if needed
5. Configure production MCP when ready to deploy

---

**Last Updated:** November 11, 2025
**MCP Server Version:** 1.0.0
**Compatible with:** Claude Desktop (latest)
