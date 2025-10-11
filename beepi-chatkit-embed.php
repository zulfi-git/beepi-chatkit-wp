<?php
/**
 * Plugin Name: Beepi ChatKit Embed
 * Plugin URI: https://github.com/zulfi-git/beepi-chatkit-wp
 * Description: Embeds an OpenAI ChatKit agent on WordPress pages using Cloudflare Worker endpoints for token generation.
 * Version: 1.3.0
 * Author: Beepi
 * Author URI: https://beepi.no
 * License: Proprietary
 * Text Domain: beepi-chatkit-embed
 *
 * @package Beepi_ChatKit_Embed
 */

/*
 * Copyright (C) 2025 Beepi
 *
 * This software is proprietary and confidential. Unauthorized copying,
 * distribution, modification, or use of this software, via any medium,
 * is strictly prohibited without the express written permission of Beepi.
 *
 * All rights reserved.
 */

// Prevent direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get plugin option with fallback to default value.
 *
 * @param string $key     Option key.
 * @param string $default Default value if option is not set.
 * @return string Option value or default.
 */
function beepi_chatkit_get_option( $key, $default = '' ) {
	$options = get_option( 'beepi_chatkit_options', array() );
	return isset( $options[ $key ] ) ? $options[ $key ] : $default;
}

/**
 * Fetch health status from Cloudflare Worker.
 *
 * @return array Health status data with keys: success, status, version, error.
 */
function beepi_chatkit_get_health_status() {
	// Get the base URL from start_url by extracting the domain.
	$start_url = beepi_chatkit_get_option( 'start_url', Beepi_ChatKit_Embed::DEFAULT_START_URL );
	
	// Parse the URL to get the base.
	$parsed_url = wp_parse_url( $start_url );
	if ( ! $parsed_url || ! isset( $parsed_url['scheme'] ) || ! isset( $parsed_url['host'] ) ) {
		return array(
			'success' => false,
			'error'   => 'Invalid start URL configuration',
		);
	}
	
	// Construct health URL using the same base as start_url.
	$health_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . '/api/health';
	
	// Fetch health status.
	$response = wp_remote_get(
		$health_url,
		array(
			'timeout' => 5,
			'headers' => array(
				'Accept' => 'application/json',
			),
		)
	);
	
	// Check for errors.
	if ( is_wp_error( $response ) ) {
		return array(
			'success' => false,
			'error'   => $response->get_error_message(),
		);
	}
	
	// Get response code.
	$response_code = wp_remote_retrieve_response_code( $response );
	if ( 200 !== $response_code ) {
		return array(
			'success' => false,
			'error'   => sprintf( 'HTTP %d: Unable to fetch health status', $response_code ),
		);
	}
	
	// Parse JSON response.
	$body = wp_remote_retrieve_body( $response );
	$data = json_decode( $body, true );
	
	if ( json_last_error() !== JSON_ERROR_NONE ) {
		return array(
			'success' => false,
			'error'   => 'Invalid JSON response from health endpoint',
		);
	}
	
	// Validate required fields.
	if ( ! isset( $data['status'] ) ) {
		return array(
			'success' => false,
			'error'   => 'Missing status field in health response',
		);
	}
	
	return array(
		'success' => true,
		'status'  => isset( $data['status'] ) ? sanitize_text_field( $data['status'] ) : '',
		'version' => isset( $data['version'] ) ? sanitize_text_field( $data['version'] ) : '',
	);
}

/**
 * Plugin activation hook.
 */
function beepi_chatkit_activate() {
	// Set default options only if they don't exist.
	if ( false === get_option( 'beepi_chatkit_options' ) ) {
		$default_options = array(
			'start_url'   => Beepi_ChatKit_Embed::DEFAULT_START_URL,
			'refresh_url' => Beepi_ChatKit_Embed::DEFAULT_REFRESH_URL,
			'workflow_id' => '',
		);
		add_option( 'beepi_chatkit_options', $default_options );
	}
}
register_activation_hook( __FILE__, 'beepi_chatkit_activate' );

/**
 * Include admin settings if in admin area.
 */
if ( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/admin-settings.php';
	if ( class_exists( 'Beepi_ChatKit_Settings' ) ) {
		Beepi_ChatKit_Settings::init();
	}
	
	// Add AJAX handler for health status check.
	add_action( 'wp_ajax_beepi_chatkit_health_check', 'beepi_chatkit_ajax_health_check' );
}

/**
 * AJAX handler for health status check.
 */
function beepi_chatkit_ajax_health_check() {
	// Check nonce for security.
	check_ajax_referer( 'beepi_chatkit_health_nonce', 'nonce' );
	
	// Check permissions.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => 'Insufficient permissions' ) );
		return;
	}
	
	// Get health status.
	$health = beepi_chatkit_get_health_status();
	
	if ( $health['success'] ) {
		wp_send_json_success( $health );
	} else {
		wp_send_json_error( $health );
	}
}

/**
 * Main plugin class for Beepi ChatKit Embed.
 */
class Beepi_ChatKit_Embed {

	/**
	 * Default URL for ChatKit start endpoint.
	 *
	 * @var string
	 */
	const DEFAULT_START_URL = 'https://chatkit.beepi.no/api/chatkit/start';

	/**
	 * Default URL for ChatKit refresh endpoint.
	 *
	 * @var string
	 */
	const DEFAULT_REFRESH_URL = 'https://chatkit.beepi.no/api/chatkit/refresh';

	/**
	 * Default URL for ChatKit health check endpoint.
	 *
	 * @var string
	 */
	const DEFAULT_HEALTH_URL = 'https://chatkit.beepi.no/api/health';

	/**
	 * Track whether the shortcode is used on the current page.
	 *
	 * @var bool
	 */
	private static $shortcode_used = false;

	/**
	 * Initialize the plugin.
	 */
	public static function init() {
		// Register the shortcode.
		add_shortcode( 'chatkit', array( __CLASS__, 'render_chatkit_shortcode' ) );

		// Enqueue scripts conditionally.
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );

		// Check for shortcode in content.
		add_filter( 'the_content', array( __CLASS__, 'check_for_shortcode' ), 1 );
	}

	/**
	 * Check if the shortcode is present in the content.
	 *
	 * @param string $content The post content.
	 * @return string The unmodified content.
	 */
	public static function check_for_shortcode( $content ) {
		if ( has_shortcode( $content, 'chatkit' ) ) {
			self::$shortcode_used = true;
		}
		return $content;
	}

	/**
	 * Render the ChatKit shortcode.
	 *
	 * @param array $atts Shortcode attributes (currently unused).
	 * @return string The HTML for the chat container.
	 */
	public static function render_chatkit_shortcode( $atts ) {
		self::$shortcode_used = true;
		return '<div id="chatkit-container"></div>';
	}

	/**
	 * Enqueue scripts and styles only when the shortcode is used.
	 */
	public static function enqueue_scripts() {
		// Check if we're on a singular post/page and the shortcode is used.
		if ( ! is_singular() ) {
			return;
		}

		global $post;
		if ( ! $post || ! has_shortcode( $post->post_content, 'chatkit' ) ) {
			return;
		}

		// Enqueue the ChatKit library from OpenAI CDN.
		wp_enqueue_script(
			'openai-chatkit',
			'https://cdn.platform.openai.com/deployments/chatkit/chatkit.js',
			array(),
			null,
			true
		);

		// Enqueue the custom initialization script.
		wp_enqueue_script(
			'beepi-chatkit-init',
			plugins_url( 'assets/js/chatkit-init.js', __FILE__ ),
			array( 'openai-chatkit' ),
			'1.2.0',
			true
		);

		// Pass configuration to the JavaScript.
		wp_localize_script(
			'beepi-chatkit-init',
			'beepichatKitConfig',
			array(
				'startUrl'   => beepi_chatkit_get_option( 'start_url', self::DEFAULT_START_URL ),
				'refreshUrl' => beepi_chatkit_get_option( 'refresh_url', self::DEFAULT_REFRESH_URL ),
				'workflowId' => beepi_chatkit_get_option( 'workflow_id', '' ),
			)
		);

		// Enqueue optional custom styles.
		wp_enqueue_style(
			'beepi-chatkit-style',
			plugins_url( 'assets/css/chatkit.css', __FILE__ ),
			array(),
			'1.2.0'
		);
	}
}

// Initialize the plugin.
Beepi_ChatKit_Embed::init();
