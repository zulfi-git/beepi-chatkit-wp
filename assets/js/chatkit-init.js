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
	 * 
	 * This function sets up the ChatKit interface by:
	 * 1. Validating configuration and DOM elements
	 * 2. Configuring the ChatKit API with client secret handlers
	 * 3. Setting up token generation and refresh mechanisms
	 * 
	 * @returns {void}
	 */
	function initChatKit() {
		// Ensure the configuration object exists.
		if (typeof beepichatKitConfig === 'undefined') {
			console.error('Beepi ChatKit: Configuration not found.');
			return;
		}

		// Ensure the container element exists.
		const container = document.getElementById('chatkit-container');
		if (!container) {
			console.error('Beepi ChatKit: Container element not found.');
			return;
		}

		// Ensure the ChatKit SDK is loaded.
		if (typeof window.ChatKit === 'undefined') {
			console.error('Beepi ChatKit: ChatKit SDK not loaded.');
			return;
		}

		// Initialize ChatKit with configuration using the official OpenAI pattern.
		try {
			// Create a ChatKit instance
			const chatkit = window.ChatKit.create();
			
			// Configure the instance
			chatkit.setOptions({
				api: {
					/**
					 * Get or refresh client secret for ChatKit API authentication.
					 * Follows the official OpenAI ChatKit pattern for token management.
					 * 
					 * @param {string|null} currentClientSecret - Current client secret if refreshing, null for initial request
					 * @returns {Promise<string>} New client secret
					 * @throws {Error} If API request fails
					 */
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
			
			// Mount the ChatKit widget
			chatkit.mount();

			console.log('Beepi ChatKit: Initialized successfully.');
		} catch (error) {
			console.error('Beepi ChatKit: Initialization error:', error);
		}
	}
})();
