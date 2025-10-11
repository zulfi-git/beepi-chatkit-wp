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
		const container = document.getElementById('chatkit-container');
		if (!container) {
			console.error('Beepi ChatKit: Container element not found.');
			return;
		}

		// Ensure ChatKit library is loaded.
		if (typeof window.ChatKit === 'undefined') {
			console.error('Beepi ChatKit: ChatKit library not loaded.');
			return;
		}

		// Initialize ChatKit with configuration.
		try {
			window.ChatKit.init({
				container: container,
				workflowId: beepichatKitConfig.workflowId,
				tokenProvider: {
					// Function to get initial token from Cloudflare Worker.
					getToken: async function() {
						try {
							const response = await fetch(beepichatKitConfig.startUrl, {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json'
								}
							});

							if (!response.ok) {
								throw new Error('Failed to get token: ' + response.status);
							}

							const data = await response.json();
							return data.token;
						} catch (error) {
							console.error('Beepi ChatKit: Error getting token:', error);
							throw error;
						}
					},
					// Function to refresh token from Cloudflare Worker.
					refreshToken: async function(oldToken) {
						try {
							const response = await fetch(beepichatKitConfig.refreshUrl, {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json'
								},
								body: JSON.stringify({ token: oldToken })
							});

							if (!response.ok) {
								throw new Error('Failed to refresh token: ' + response.status);
							}

							const data = await response.json();
							return data.token;
						} catch (error) {
							console.error('Beepi ChatKit: Error refreshing token:', error);
							throw error;
						}
					}
				}
			});

			console.log('Beepi ChatKit: Initialized successfully.');
		} catch (error) {
			console.error('Beepi ChatKit: Initialization error:', error);
		}
	}
})();
