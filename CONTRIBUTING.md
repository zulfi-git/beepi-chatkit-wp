# Contributing to Beepi ChatKit Embed

Thank you for your interest in contributing! This is a personal project, but contributions are welcome.

## Getting Started

1. Fork the repository
2. Clone your fork locally
3. Create a feature branch: `git checkout -b feature/my-feature`
4. Make your changes
5. Test your changes thoroughly
6. Commit with clear messages
7. Push to your fork
8. Open a Pull Request

## Development Setup

### Prerequisites
- WordPress 5.0 or higher
- PHP 7.4 or higher
- Basic understanding of WordPress plugin development
- OpenAI ChatKit account and workflow ID

### Local Development
1. Clone the repository into your WordPress plugins directory:
   ```bash
   cd wp-content/plugins/
   git clone https://github.com/zulfi-git/beepi-chatkit-wp.git
   ```

2. Configure the plugin by editing `beepi-chatkit-embed.php`:
   ```php
   define( 'CHATKIT_WORKFLOW_ID', 'your-workflow-id' );
   ```

3. Activate the plugin in WordPress admin

4. Create a test page with the `[chatkit]` shortcode

## Code Standards

### PHP
- Follow [WordPress PHP Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/)
- Use tabs for indentation
- Include DocBlocks for all functions and classes
- Always escape output and sanitize input
- Check for `ABSPATH` in all PHP files

### JavaScript
- Use modern ES6+ syntax where appropriate
- Include JSDoc comments for functions
- Use strict mode: `'use strict';`
- Handle errors gracefully
- Use meaningful variable names

### CSS
- Follow WordPress CSS Coding Standards
- Use tabs for indentation
- Include comments for complex sections
- Ensure responsive design
- Test on multiple browsers

## What to Contribute

### Bug Fixes
- Always welcome!
- Include steps to reproduce
- Add tests if possible

### Features
- Discuss large features in an issue first
- Keep features focused and minimal
- Maintain backward compatibility
- Update documentation

### Documentation
- Improve README clarity
- Add code examples
- Fix typos
- Improve inline documentation

### Testing
- Add unit tests
- Add integration tests
- Improve test coverage

## Code Review Process

1. All PRs must pass basic checks:
   - Code follows WordPress standards
   - No breaking changes
   - Documentation is updated
   - Changes are minimal and focused

2. PRs will be reviewed for:
   - Code quality
   - Security implications
   - Performance impact
   - Compatibility with WordPress versions

3. Response time:
   - Bug fixes: 1-2 days
   - Features: 3-7 days
   - Documentation: 1-3 days

## Testing Your Changes

### Manual Testing
1. Test in a clean WordPress installation
2. Test with different themes
3. Test on different browsers
4. Test responsive design
5. Check browser console for errors

### WordPress Compatibility
- Test with WordPress 5.0+
- Test with PHP 7.4+
- Ensure no conflicts with popular plugins

## Commit Messages

Use clear, descriptive commit messages:

```
Good:
✅ Add admin settings page for configuration
✅ Fix script loading on non-singular pages
✅ Update documentation for v1.2.0

Bad:
❌ Update file
❌ Fix bug
❌ Changes
```

### Commit Message Format
```
<type>: <description>

[optional body]

[optional footer]
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting)
- `refactor`: Code refactoring
- `test`: Adding tests
- `chore`: Maintenance tasks

## Pull Request Guidelines

### PR Title Format
```
[Type] Brief description of changes
```

Examples:
- `[Feature] Add WordPress admin settings page`
- `[Fix] Resolve script loading issue`
- `[Docs] Update installation instructions`

### PR Description Should Include:
- What changes were made
- Why the changes were needed
- How to test the changes
- Screenshots (if UI changes)
- Related issue numbers

### PR Checklist
- [ ] Code follows project standards
- [ ] Self-reviewed the code
- [ ] Commented complex code sections
- [ ] Updated documentation
- [ ] No breaking changes (or documented)
- [ ] Tested on clean WordPress install
- [ ] Checked browser console for errors

## Security

### Reporting Security Issues
**Do not** open public issues for security vulnerabilities.

Instead:
1. Email security concerns to the maintainer
2. Include detailed description
3. Include steps to reproduce
4. Allow time for fix before disclosure

### Security Best Practices
- Always sanitize user input
- Always escape output
- Use WordPress security functions
- Add nonce verification for forms
- Validate and sanitize all data
- Use prepared statements for database queries

## Code of Conduct

### Be Respectful
- Be kind and courteous
- Respect different viewpoints
- Accept constructive criticism
- Focus on what's best for the project

### Be Professional
- Use welcoming language
- Keep discussions on-topic
- No harassment or trolling
- No personal attacks

## Questions?

- Open an issue for general questions
- Check existing issues first
- Be specific and provide context
- Include code examples when relevant

## License

By contributing, you agree that your contributions will be licensed under the GNU General Public License v3.0.

---

## Additional Resources

- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [WordPress Coding Standards](https://make.wordpress.org/core/handbook/best-practices/coding-standards/)
- [OpenAI ChatKit Documentation](https://platform.openai.com/docs/guides/chatkit)
- [Git Best Practices](https://git-scm.com/book/en/v2)

---

Thank you for contributing! 🎉
