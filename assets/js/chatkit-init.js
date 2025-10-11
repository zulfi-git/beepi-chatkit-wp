/**
 * Beepi ChatKit Initialization Script
 *
 * Loads the ChatKit script and connects to Cloudflare Worker endpoints
 * for token generation and refresh.
 *
 * @package Beepi_ChatKit_Embed
 * @license GPL-3.0-or-later
 *
 * Copyright (C) 2025 Beepi
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

(function() {
	'use strict';

	// Wait for DOM to be ready.
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initChatKit);
	} else {
		initChatKit();
	}

	/**
	 * Initialize the ChatKit instance.
	 */
	function initChatKit() {
		// Ensure the configuration object exists.
		if (typeof beepichatKitConfig === 'undefined') {
			console.error('Beepi ChatKit: Configuration not found.');
			return;
		}

		// Ensure the container element exists.
		const chatkit = document.getElementById('chatkit-container');
		if (!chatkit) {
			console.error('Beepi ChatKit: Container element not found.');
			return;
		}

		// Initialize ChatKit with configuration using the official OpenAI pattern.
		try {
			chatkit.setOptions({
				api: {
					getClientSecret: async function(currentClientSecret) {
						try {
							if (!currentClientSecret) {
								// Get initial client secret.
								const res = await fetch(beepichatKitConfig.startUrl, { 
									method: 'POST' 
								});
								
								if (!res.ok) {
									throw new Error('Failed to get client secret: ' + res.status);
								}
								
								const {client_secret} = await res.json();
								return client_secret;
							}
							
							// Refresh client secret.
							const res = await fetch(beepichatKitConfig.refreshUrl, {
								method: 'POST',
								body: JSON.stringify({ currentClientSecret }),
								headers: {
									'Content-Type': 'application/json',
								},
							});
							
							if (!res.ok) {
								throw new Error('Failed to refresh client secret: ' + res.status);
							}
							
							const {client_secret} = await res.json();
							return client_secret;
						} catch (error) {
							console.error('Beepi ChatKit: Error with client secret:', error);
							throw error;
						}
					}
				},
			});

			console.log('Beepi ChatKit: Initialized successfully.');
		} catch (error) {
			console.error('Beepi ChatKit: Initialization error:', error);
		}
	}
})();
