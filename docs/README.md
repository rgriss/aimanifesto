# AI Manifesto - Documentation

This directory contains all product, technical, and process documentation for the AI Manifesto project.

## Philosophy

**Code to the interface, not the implementation.**

We believe in:
- Clear specifications before implementation
- Documentation that lives with the code
- Examples that demonstrate our standards
- Processes that scale from 1 to 100 team members

## Directory Structure

### `/api`
API documentation and schemas:
- [Tool Creation API](./api/tool-creation-api.md) - REST API for creating tools
- [Tool Intelligence API](./api/tool-intelligence-api.md) - REST API for business intelligence data
- [MCP Setup Guide](./api/mcp-setup-guide.md) - Claude Desktop integration
- TypeScript definitions and JSON schemas

### `/features`
Feature requests and product requirements documents (PRDs). Each feature gets a document that describes:
- User story and motivation
- Acceptance criteria
- User experience considerations
- Dependencies and risks

### `/technical-specs`
Technical design documents for features. These bridge the gap between product requirements and implementation:
- Architecture decisions
- API contracts
- Database schemas
- Testing strategies

### `/architecture`
High-level architectural documentation:
- System design principles
- Technology choices and rationale
- Infrastructure patterns
- Integration approaches

### `/processes`
Team processes and best practices:
- Development workflow
- Code review standards
- Testing requirements
- Deployment procedures

## Document Lifecycle

1. **Feature Request** → Created in `/features` with PRD template
2. **Technical Design** → Engineer creates spec in `/technical-specs`
3. **Implementation** → Code references the spec documents
4. **Review** → PRs link back to feature and spec docs
5. **Completion** → Documents updated with any deviations or learnings

## Getting Started

New team members should read:
1. This README
2. `/processes/development-workflow.md`
3. `/architecture/system-overview.md`
4. [CHANGELOG.md](../CHANGELOG.md) - Version history and recent changes

## Quick Links

### For Developers
- [API Documentation](./api/) - REST APIs and integrations
- [Tool Creation API](./api/tool-creation-api.md) - Add tools programmatically
- [Tool Intelligence API](./api/tool-intelligence-api.md) - Add business intelligence data
- [MCP Setup Guide](./api/mcp-setup-guide.md) - Claude Desktop integration

### For Product/Business
- [CHANGELOG](../CHANGELOG.md) - Feature releases and changes
- [System Overview](./architecture/system-overview.md) - High-level architecture

## Version History

- 2025-11-11: Added API documentation, CHANGELOG, and tool intelligence docs
- 2025-10-10: Documentation structure created
