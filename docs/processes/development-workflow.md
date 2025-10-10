# Development Workflow

## Philosophy

**Code to the interface, not the implementation.**

This means:
- Write clear specifications before code
- Focus on contracts and behavior, not internal details  
- Design systems that are easy to replace components
- Document the "what" and "why", not just the "how"

---

## Feature Development Lifecycle

### 1. Feature Request (Product)
**Owner:** Product Manager / Feature Requestor

**Process:**
1. Copy `/docs/features/TEMPLATE.md`
2. Name it `FEAT-XXX-feature-name.md`
3. Fill out all sections completely
4. Tag status as "Draft"
5. Share for feedback

**Key Artifacts:**
- Problem statement
- User story
- Acceptance criteria
- Success metrics

**Checklist:**
- [ ] Problem clearly defined
- [ ] User value articulated
- [ ] Acceptance criteria are testable
- [ ] Dependencies identified
- [ ] Out-of-scope explicitly stated

---

### 2. Technical Specification (Engineering)
**Owner:** Lead Engineer / Technical Lead

**Process:**
1. Read and understand the feature request
2. Copy `/docs/technical-specs/TEMPLATE.md`
3. Name it `SPEC-XXX-feature-name.md`
4. Design the solution
5. Get peer review on approach
6. Link to feature request

**Key Artifacts:**
- Architecture design
- API contracts
- Database schemas
- Testing strategy

**Checklist:**
- [ ] Architecture decisions explained
- [ ] API contracts defined with examples
- [ ] Database changes documented
- [ ] Security reviewed
- [ ] Performance considered
- [ ] Testing strategy outlined
- [ ] Rollback plan documented

---

### 3. Implementation (Development)
**Owner:** Implementing Engineer

**Process:**
1. Create feature branch: `feature/FEAT-XXX-short-name`
2. Reference spec document in code comments
3. Write tests first (TDD when appropriate)
4. Implement to spec
5. Update docs if implementation differs from spec
6. Self-review before PR

**Key Artifacts:**
- Working code
- Tests (unit, integration)
- Updated documentation

**Checklist:**
- [ ] All acceptance criteria met
- [ ] Tests written and passing
- [ ] Code follows style guide
- [ ] No hardcoded values (use config)
- [ ] Error handling implemented
- [ ] Logging added for debugging
- [ ] Comments explain "why" not "what"

---

### 4. Code Review
**Owner:** Reviewing Engineer(s)

**Process:**
1. Read feature request and technical spec
2. Review code against spec
3. Check tests cover edge cases
4. Verify security considerations addressed
5. Approve or request changes

**Focus Areas:**
- Does it solve the stated problem?
- Is it maintainable?
- Are there obvious bugs or edge cases?
- Is it secure?
- Does it follow our standards?

**Checklist:**
- [ ] Code matches spec
- [ ] Tests are meaningful
- [ ] No security vulnerabilities
- [ ] Performance is acceptable
- [ ] Documentation updated
- [ ] No merge conflicts

---

### 5. Testing & QA
**Owner:** QA / Developer

**Process:**
1. Deploy to staging environment
2. Run through manual test cases
3. Verify acceptance criteria
4. Test edge cases and error scenarios
5. Performance/load test if applicable

**Checklist:**
- [ ] All acceptance criteria pass
- [ ] Edge cases handled gracefully
- [ ] Error messages are clear
- [ ] No regressions in existing features
- [ ] Performance meets requirements

---

### 6. Deployment
**Owner:** DevOps / Lead Engineer

**Process:**
1. Review deployment checklist
2. Deploy to production
3. Verify deployment successful
4. Monitor for errors/issues
5. Update feature status to "Deployed"

**Checklist:**
- [ ] Database migrations run
- [ ] Environment variables set
- [ ] Feature flags configured (if used)
- [ ] Monitoring/alerts active
- [ ] Rollback plan ready
- [ ] Stakeholders notified

---

### 7. Post-Deployment
**Owner:** Entire Team

**Process:**
1. Monitor metrics for success criteria
2. Gather user feedback
3. Document lessons learned
4. Add to changelog
5. Celebrate! 🎉

**Checklist:**
- [ ] Metrics tracking working
- [ ] User feedback collected
- [ ] Known issues documented
- [ ] Future improvements noted

---

## Branch Strategy

### Main Branches
- `main` - Production-ready code
- `develop` - Integration branch (if needed for larger team)

### Feature Branches
- `feature/FEAT-XXX-short-name` - New features
- `fix/description` - Bug fixes
- `docs/description` - Documentation only
- `refactor/description` - Code refactoring

### Branch Lifecycle
1. Create from `main` (or `develop`)
2. Develop with frequent commits
3. Keep branch up to date with rebase
4. PR when ready for review
5. Merge and delete after deployment

---

## Commit Messages

### Format
```
type(scope): brief description

Longer description if needed.

Refs: FEAT-XXX, SPEC-XXX
```

### Types
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation
- `style`: Formatting, no code change
- `refactor`: Code restructuring
- `test`: Adding tests
- `chore`: Maintenance tasks

### Examples
```
feat(api): add tool creation endpoint

Implements SPEC-001 for AI tool creation via API.
Includes authentication, validation, and error handling.

Refs: FEAT-001, SPEC-001
```

---

## Code Review Standards

### What We Look For
1. **Correctness:** Does it work as specified?
2. **Clarity:** Is it easy to understand?
3. **Maintainability:** Can someone else modify it?
4. **Performance:** Is it efficient enough?
5. **Security:** Are there vulnerabilities?
6. **Tests:** Are edge cases covered?

### Giving Feedback
- Be kind and constructive
- Explain the "why" behind suggestions
- Distinguish between "must fix" and "nice to have"
- Approve when it's good enough, not perfect

### Receiving Feedback
- Assume good intent
- Ask for clarification if unclear
- Explain your reasoning if you disagree
- Thank reviewers for their time

---

## Documentation Standards

### When to Document
- **Always:** Public APIs, complex algorithms
- **Usually:** Non-obvious code, architectural decisions
- **Sometimes:** Self-explanatory code
- **Never:** What the code obviously does

### Where to Document
1. **Feature Docs:** `/docs/features/` - Product requirements
2. **Tech Specs:** `/docs/technical-specs/` - Design decisions
3. **Architecture:** `/docs/architecture/` - System design
4. **Code Comments:** In the code itself - Why, not what
5. **API Docs:** Generated from code or separate API docs
6. **README:** Getting started, setup instructions

### Writing Style
- Clear and concise
- Assume the reader is smart but unfamiliar
- Use examples
- Keep it up to date (or delete it)

---

## Testing Philosophy

### Test Pyramid
```
        /\
       /  \      E2E Tests (Few)
      /____\
     /      \    Integration Tests (Some)
    /________\
   /          \  Unit Tests (Many)
  /____________\
```

### What to Test
- **Unit Tests:** Business logic, edge cases, utility functions
- **Integration Tests:** API endpoints, database interactions
- **E2E Tests:** Critical user flows

### When to Write Tests
- Before code (TDD) for complex logic
- Alongside code for most features
- After code for bug fixes

---

## Questions?

This is a living document. If something is unclear:
1. Ask in team chat/discussion
2. Update this document with the answer
3. Submit a PR with improvements

Remember: **The best process is the one we actually follow.**
