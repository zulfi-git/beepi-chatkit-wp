/**
 * Admin Health Status Check for Beepi ChatKit
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

(function($) {
	'use strict';

	/**
	 * Fetch and display health status.
	 */
	function fetchHealthStatus() {
		const container = $('#beepi-chatkit-health-status');
		
		// Show loading state
		container.html('<p class="beepi-health-loading">Checking health status...</p>');
		
		// Make AJAX request
		$.ajax({
			url: beepichatKitAdmin.ajaxUrl,
			type: 'POST',
			data: {
				action: 'beepi_chatkit_health_check',
				nonce: beepichatKitAdmin.nonce
			},
			success: function(response) {
				if (response.success && response.data) {
					displayHealthStatus(response.data);
				} else {
					displayError(response.data && response.data.error ? response.data.error : 'Unknown error');
				}
			},
			error: function(xhr, status, error) {
				displayError('Failed to fetch health status: ' + error);
			}
		});
	}

	/**
	 * Display health status information.
	 *
	 * @param {Object} data Health status data.
	 */
	function displayHealthStatus(data) {
		const container = $('#beepi-chatkit-health-status');
		const status = data.status || 'unknown';
		const version = data.version || 'N/A';
		
		// Determine status class
		const statusClass = status === 'ok' ? 'status-ok' : 'status-error';
		
		// Build HTML
		const html = `
			<div class="beepi-health-info">
				<div class="beepi-health-item">
					<span class="beepi-health-label">Status:</span>
					<span class="beepi-health-value beepi-health-status ${statusClass}">${escapeHtml(status)}</span>
				</div>
				<div class="beepi-health-item">
					<span class="beepi-health-label">Version:</span>
					<span class="beepi-health-value">${escapeHtml(version)}</span>
				</div>
			</div>
		`;
		
		container.html(html);
	}

	/**
	 * Display error message.
	 *
	 * @param {string} message Error message.
	 */
	function displayError(message) {
		const container = $('#beepi-chatkit-health-status');
		container.html(`
			<div class="beepi-health-error">
				<p><strong>Error:</strong> ${escapeHtml(message)}</p>
			</div>
		`);
	}

	/**
	 * Escape HTML to prevent XSS.
	 *
	 * @param {string} text Text to escape.
	 * @return {string} Escaped text.
	 */
	function escapeHtml(text) {
		const div = document.createElement('div');
		div.textContent = text;
		return div.innerHTML;
	}

	// Initialize on document ready
	$(document).ready(function() {
		// Fetch health status on page load
		fetchHealthStatus();
		
		// Refresh button click handler
		$('#beepi-chatkit-refresh-health').on('click', function() {
			fetchHealthStatus();
		});
	});

})(jQuery);
