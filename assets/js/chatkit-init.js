/**
 * Beepi ChatKit Initialization Script
 *
 * Loads the ChatKit script and connects to Cloudflare Worker endpoints
 * for token generation and refresh.
 *
 * @package Beepi_ChatKit_Embed
 * @license Proprietary
 *
 * Copyright (C) 2025 Beepi. All rights reserved.
 *
 * This software is proprietary and confidential. Unauthorized copying,
 * distribution, modification, or use of this software, via any medium,
 * is strictly prohibited without the express written permission of Beepi.
 */

(function() {
	'use strict';

	// Wait for DOM to be ready.
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', waitForChatKitSDK);
	} else {
		waitForChatKitSDK();
	}

	/**
	 * Wait for the ChatKit SDK to load before initializing.
	 * 
	 * This function waits for the custom element to be defined,
	 * which handles the race condition where the SDK script hasn't finished
	 * executing even though it's been loaded.
	 * 
	 * ChatKit uses Web Components, so we need to wait for the custom element
	 * to be registered rather than polling for window.ChatKit.
	 * 
	 * @returns {void}
	 */
	function waitForChatKitSDK() {
		// ChatKit uses a custom element named 'openai-chatkit'
		// We'll check for the custom element to be defined.
		const elementName = 'openai-chatkit';
		
		// Use customElements.whenDefined() to wait for the element
		if (typeof customElements !== 'undefined' && customElements.whenDefined) {
			customElements.whenDefined(elementName)
				.then(() => {
					console.log('Beepi ChatKit: Custom element defined, initializing...');
					initChatKit();
				})
				.catch((error) => {
					console.error('Beepi ChatKit: Error waiting for custom element:', error);
					// Fallback to polling with timeout
					pollForCustomElement(elementName);
				});
		} else {
			// Fallback for older browsers
			pollForCustomElement(elementName);
		}
	}
	
	/**
	 * Fallback polling mechanism for custom element.
	 * 
	 * @param {string} elementName - Name of the custom element to wait for
	 * @returns {void}
	 */
	function pollForCustomElement(elementName) {
		let attempts = 0;
		const maxAttempts = 50; // 5 seconds total (50 * 100ms)
		const pollInterval = 100; // 100ms between checks
		const timeoutSeconds = maxAttempts * pollInterval / 1000;

		const checkElement = function() {
			attempts++;

			if (customElements && customElements.get(elementName)) {
				// Custom element is defined, proceed with initialization
				console.log('Beepi ChatKit: Custom element found via polling, initializing...');
				initChatKit();
			} else if (attempts >= maxAttempts) {
				// Timeout after max attempts
				console.error(`Beepi ChatKit: ChatKit custom element failed to load after ${timeoutSeconds} seconds. Expected element: ${elementName}`);
			} else {
				// Keep polling
				setTimeout(checkElement, pollInterval);
			}
		};

		// Start polling
		checkElement();
	}

	/**
	 * Initialize the ChatKit instance.
	 * 
	 * This function sets up the ChatKit interface by:
	 * 1. Validating configuration
	 * 2. Finding the openai-chatkit web component
	 * 3. Configuring it via setOptions() method with proper API structure
	 * 
	 * The openai-chatkit element should already exist in the DOM
	 * (rendered by the shortcode), and we configure it here.
	 * 
	 * @returns {void}
	 */
	function initChatKit() {
		// Ensure the configuration object exists.
		if (typeof beepichatKitConfig === 'undefined') {
			console.error('Beepi ChatKit: Configuration not found.');
			return;
		}

		// Find the openai-chatkit element that was rendered by the shortcode
		const chatkitWidget = document.getElementById('chatkit-container');
		if (!chatkitWidget) {
			console.error('Beepi ChatKit: openai-chatkit element not found.');
			return;
		}

		// Initialize ChatKit with configuration using Web Components.
		try {
			// Add workflow ID if configured
			if (beepichatKitConfig.workflowId) {
				chatkitWidget.setAttribute('workflow-id', beepichatKitConfig.workflowId);
			}

			// Build the theme configuration object dynamically
			const chatkitConfig = {
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
				theme: {
					colorScheme: 'light',
					color: {
						accent: {
							primary: '#FF4500',
							level: 2
						}
					},
					radius: 'round',
					density: 'normal',
					typography: {
						baseSize: 16,
						fontFamily: '"OpenAI Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif'
					}
				},
				composer: {
					attachments: {
						enabled: false
					}
				},
				startScreen: {
					greeting: beepichatKitConfig.startScreenGreeting || 'How can I help you today?',
					prompts: [
						{
							icon: 'circle-question',
							label: beepichatKitConfig.startScreenPromptLabel || 'Get Started',
							prompt: beepichatKitConfig.startScreenPromptText || 'Hi! How can you assist me today?'
						}
					]
				}
			};

			// Configure ChatKit using setOptions() method
			chatkitWidget.setOptions(chatkitConfig);

			console.log('Beepi ChatKit: Initialized successfully with web component.');
		} catch (error) {
			console.error('Beepi ChatKit: Initialization error:', error);
		}
	}
})();
