# Beepi ChatKit Embed

A WordPress plugin for embedding an OpenAI ChatKit agent on WordPress pages using Cloudflare Worker endpoints for token generation.

## Description

This plugin allows you to easily embed a ChatKit-powered chat interface on your WordPress site. It connects to your Cloudflare Worker endpoints for secure token generation and refresh, ensuring seamless authentication with the OpenAI ChatKit service.

## Features

- 🚀 Easy integration via `[chatkit]` shortcode
- 🔒 Secure token management through Cloudflare Worker endpoints
- 📱 Responsive design with mobile support
- ⚡ Conditional script loading (only loads on pages using the shortcode)
- 🎨 Customizable styling with included CSS
- 🔧 Minimal configuration required

## Installation

1. Upload the `beepi-chatkit-wp` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the `CHATKIT_WORKFLOW_ID` constant in `beepi-chatkit-embed.php` with your ChatKit workflow ID

## Configuration

Open `beepi-chatkit-embed.php` and locate the configuration constants near the top of the file:

```php
define( 'CHATKIT_START_URL', 'https://chatkit.beepi.no/api/chatkit/start' );
define( 'CHATKIT_REFRESH_URL', 'https://chatkit.beepi.no/api/chatkit/refresh' );
define( 'CHATKIT_WORKFLOW_ID', '' ); // Add your workflow ID here
```

Update the `CHATKIT_WORKFLOW_ID` with your actual ChatKit workflow ID.

## Usage

To embed the ChatKit interface on any page or post, simply use the shortcode:

```
[chatkit]
```

The chat interface will appear where you place the shortcode.

## Files Structure

```
beepi-chatkit-wp/
├── beepi-chatkit-embed.php    # Main plugin file
├── assets/
│   ├── js/
│   │   └── chatkit-init.js    # JavaScript initialization
│   └── css/
│       └── chatkit.css        # Optional styling
└── README.md
```

## Customization

### Styling

You can customize the appearance of the chat interface by editing `assets/css/chatkit.css` or by adding custom CSS to your theme.

### JavaScript

The ChatKit initialization logic is in `assets/js/chatkit-init.js`. This file handles:
- Loading the ChatKit library
- Connecting to your Cloudflare Worker endpoints
- Token generation and refresh

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Active internet connection (for loading ChatKit from OpenAI CDN)
- Valid Cloudflare Worker endpoints for token management

## Documentation

- **[ASSESSMENT.md](./ASSESSMENT.md)** - Comprehensive codebase assessment and improvement roadmap
- **[ASSESSMENT-SUMMARY.md](./ASSESSMENT-SUMMARY.md)** - Quick reference for assessment findings
- **[CONTRIBUTING.md](./CONTRIBUTING.md)** - Guidelines for contributing to the project

## License

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see <https://www.gnu.org/licenses/>.

## Troubleshooting

### ChatKit doesn't appear on the page

1. **Check if the shortcode is present:**
   - Ensure you've added `[chatkit]` to your page/post
   - Check the page source to verify the `chatkit-container` div is present

2. **Verify configuration:**
   - Open `beepi-chatkit-embed.php` and ensure `CHATKIT_WORKFLOW_ID` is set
   - Verify the Cloudflare Worker URLs are correct

3. **Check browser console:**
   - Open browser DevTools (F12)
   - Look for error messages in the Console tab
   - Common errors:
     - "Configuration not found" - WordPress localization issue
     - "Container element not found" - Shortcode not rendering
     - "Failed to get client secret" - API endpoint issue

4. **Verify Cloudflare Worker endpoints:**
   - Test the start endpoint: `POST https://chatkit.beepi.no/api/chatkit/start`
   - Test the refresh endpoint: `POST https://chatkit.beepi.no/api/chatkit/refresh`
   - Ensure they return valid JSON with `client_secret` field

### Scripts not loading

1. **Check page type:**
   - Scripts only load on singular pages (posts/pages)
   - They won't load on archive pages, home page, etc.

2. **Clear cache:**
   - Clear browser cache
   - Clear WordPress cache (if using a caching plugin)
   - Clear any CDN cache

3. **Check for conflicts:**
   - Temporarily disable other plugins
   - Switch to a default WordPress theme (Twenty Twenty-Three)
   - Test if ChatKit loads

### API errors

**"Failed to get client secret: 401"**
- Check your Cloudflare Worker authentication
- Verify API credentials are correct

**"Failed to refresh client secret: 403"**
- Check token refresh endpoint permissions
- Verify the refresh logic in Cloudflare Worker

**Network errors**
- Check internet connectivity
- Verify Cloudflare Worker is running
- Check for CORS issues in browser console

### Performance issues

**Slow loading:**
- Verify CDN availability: `https://cdn.platform.openai.com/deployments/chatkit/chatkit.js`
- Check Cloudflare Worker response times
- Consider implementing caching

**Multiple instances:**
- Plugin currently supports one ChatKit instance per page
- Using multiple `[chatkit]` shortcodes may cause conflicts

## Support

For issues and questions:
- Check the [Troubleshooting](#troubleshooting) section above
- Review the [ASSESSMENT.md](./ASSESSMENT.md) for known limitations
- Open an issue on the GitHub repository with:
  - WordPress version
  - PHP version
  - Browser and version
  - Error messages from console
  - Steps to reproduce

## Changelog

### 1.2.0 (Assessment & Documentation Update)
- ✅ Comprehensive codebase assessment completed
- ✅ Added phased work packages for future improvements
- ✅ Enhanced documentation with troubleshooting guide
- ✅ Added development configuration files (.editorconfig, .gitattributes, .gitignore)
- ✅ Added CONTRIBUTING.md for contributors
- ✅ Improved JSDoc comments in JavaScript
- ✅ Added ASSESSMENT.md with detailed quality analysis
- ✅ Added ASSESSMENT-SUMMARY.md for quick reference

### 1.1.0
- Updated to follow official OpenAI ChatKit embedding guide
- Changed from tokenProvider pattern to api.getClientSecret pattern
- Updated API integration to use client_secret instead of token
- Improved compatibility with OpenAI's recommended implementation

### 1.0.0
- Initial release
- Shortcode support for embedding ChatKit
- Cloudflare Worker integration for token management
- Conditional script loading
- Responsive design with basic styling