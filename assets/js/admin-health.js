/**
 * Admin Health Status Check for Beepi ChatKit
 *
 * @package Beepi_ChatKit_Embed
 * @license GPL-3.0-or-later
 *
 * Copyright (C) 2025 Beepi
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
		const uptime = data.uptime || 0;
		const version = data.version || 'N/A';
		
		// Determine status class
		const statusClass = status === 'ok' ? 'status-ok' : 'status-error';
		
		// Format uptime
		const uptimeFormatted = formatUptime(uptime);
		
		// Build HTML
		const html = `
			<div class="beepi-health-info">
				<div class="beepi-health-item">
					<span class="beepi-health-label">Status:</span>
					<span class="beepi-health-value beepi-health-status ${statusClass}">${escapeHtml(status)}</span>
				</div>
				<div class="beepi-health-item">
					<span class="beepi-health-label">Uptime:</span>
					<span class="beepi-health-value">${uptimeFormatted}</span>
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
	 * Format uptime in seconds to human-readable format.
	 *
	 * @param {number} seconds Uptime in seconds.
	 * @return {string} Formatted uptime.
	 */
	function formatUptime(seconds) {
		if (seconds < 60) {
			return seconds + ' seconds';
		} else if (seconds < 3600) {
			const minutes = Math.floor(seconds / 60);
			return minutes + ' minute' + (minutes !== 1 ? 's' : '');
		} else if (seconds < 86400) {
			const hours = Math.floor(seconds / 3600);
			return hours + ' hour' + (hours !== 1 ? 's' : '');
		} else {
			const days = Math.floor(seconds / 86400);
			return days + ' day' + (days !== 1 ? 's' : '');
		}
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
