# AI Manifesto MCP Server

Model Context Protocol (MCP) server that allows Claude Desktop to create tool entries in your AI Manifesto directory via natural conversation.

## Quick Start

### 1. Install Dependencies

```bash
npm install @modelcontextprotocol/sdk
```

### 2. Get Your API Token

From your `.env` file, copy the value of `API_TOKEN`.

### 3. Configure Claude Desktop

**Windows:** Edit `%APPDATA%\Claude\claude_desktop_config.json`

```json
{
  "mcpServers": {
    "aimanifesto": {
      "command": "node",
      "args": ["F:\\code\\aimanifesto\\mcp-server.js"],
      "env": {
        "AIMANIFESTO_API_TOKEN": "paste-your-token-here",
        "AIMANIFESTO_API_URL": "http://localhost:8000"
      }
    }
  }
}
```

### 4. Restart Claude Desktop

Close and reopen the app completely.

### 5. Test It

In Claude, say:
```
Add a test tool to my directory:
- Name: Test Tool
- Website: https://test.com
- Description: Testing the MCP integration
- Category: Testing
```

Claude should create the tool and give you a link!

## Usage Examples

**Simple:**
```
Add GitHub Copilot to my tools. It's at github.com/copilot
```

**Detailed:**
```
Add Claude to my AI tools directory:
- Made by Anthropic
- Website: claude.ai
- It's an AI assistant with a huge context window
- Pricing: Free tier, $20/month for Pro
- Category: AI Assistants
- Features: 200K context, Artifacts, Computer use
- My rating: 9/10
```

## Documentation

- **Full Setup Guide:** [docs/api/mcp-setup-guide.md](docs/api/mcp-setup-guide.md)
- **API Reference:** [docs/api/tool-creation-api.md](docs/api/tool-creation-api.md)
- **Feature Spec:** [docs/features/FEAT-001-ai-tool-creation-api.md](docs/features/FEAT-001-ai-tool-creation-api.md)

## Troubleshooting

**MCP server not loading?**
- Check the file path in your config is correct (use `\\` on Windows)
- Verify Node.js is installed: `node --version`
- Make sure Laravel is running: `php artisan serve`

**401 Unauthorized?**
- Token in config must match token in `.env`
- Restart Claude Desktop after changing config

**Validation errors?**
- Make sure you include: name, description, website_url, category
- URLs must be valid (include https://)

## Security

- ⚠️ Never commit your `claude_desktop_config.json` (contains API token)
- ⚠️ Use different tokens for local and production
- ⚠️ Rotate tokens if compromised

## Files

- `mcp-server.js` - Main MCP server implementation
- `package-mcp.json` - Dependencies (if installing separately)
- `docs/api/mcp-setup-guide.md` - Comprehensive setup instructions
- `MCP-README.md` - This file (quick reference)

---

**Version:** 1.0.0
**Last Updated:** November 11, 2025
