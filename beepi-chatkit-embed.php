<?php
/**
 * Plugin Name: Beepi ChatKit Embed
 * Plugin URI: https://github.com/zulfi-git/beepi-chatkit-wp
 * Description: Embeds an OpenAI ChatKit agent on WordPress pages using Cloudflare Worker endpoints for token generation.
 * Version: 1.2.0
 * Author: Beepi
 * Author URI: https://beepi.no
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: beepi-chatkit-embed
 *
 * @package Beepi_ChatKit_Embed
 */

/*
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
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
