<?php
/**
 * PHPUnit bootstrap file for Beepi ChatKit Embed tests
 *
 * @package Beepi_ChatKit_Embed
 */

// Define ABSPATH for WordPress compatibility.
if ( ! defined( 'ABSPATH' ) ) {
define( 'ABSPATH', dirname( __DIR__ ) . '/' );
}

// Mock WordPress functions for testing.
if ( ! function_exists( 'register_activation_hook' ) ) {
	function register_activation_hook( $file, $function ) {
		// Mock implementation for testing.
	}
}

if ( ! function_exists( 'get_option' ) ) {
	function get_option( $option, $default = false ) {
		return $default;
	}
}

if ( ! function_exists( 'add_option' ) ) {
	function add_option( $option, $value ) {
		return true;
	}
}

if ( ! function_exists( 'is_admin' ) ) {
	function is_admin() {
		return false;
	}
}

if ( ! function_exists( 'plugin_dir_path' ) ) {
	function plugin_dir_path( $file ) {
		return dirname( $file ) . '/';
	}
}

if ( ! function_exists( 'add_shortcode' ) ) {
	function add_shortcode( $tag, $callback ) {
		// Mock implementation for testing.
	}
}

if ( ! function_exists( 'add_action' ) ) {
	function add_action( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
		// Mock implementation for testing.
	}
}

if ( ! function_exists( 'add_filter' ) ) {
	function add_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
		// Mock implementation for testing.
	}
}

// Load the plugin main file for testing.
require_once dirname( __DIR__ ) . '/beepi-chatkit-embed.php';
