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

## License

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see <https://www.gnu.org/licenses/>.

## Support

For issues and questions, please open an issue on the GitHub repository.

## Changelog

### 1.0.0
- Initial release
- Shortcode support for embedding ChatKit
- Cloudflare Worker integration for token management
- Conditional script loading
- Responsive design with basic styling