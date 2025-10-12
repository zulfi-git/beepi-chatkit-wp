<?php
/**
 * Shortcode test cases for Beepi ChatKit Embed
 *
 * @package Beepi_ChatKit_Embed
 */

/*
 * Copyright (C) 2025 Beepi. All rights reserved.
 *
 * This software is proprietary and confidential. Unauthorized copying,
 * distribution, modification, or use of this software, via any medium,
 * is strictly prohibited without the express written permission of Beepi.
 */

/**
 * Test shortcode functionality.
 */
class Test_Shortcode extends PHPUnit\Framework\TestCase {

	/**
	 * Test that the shortcode renders with default container ID.
	 */
	public function test_shortcode_renders_default_container() {
		$output = Beepi_ChatKit_Embed::render_chatkit_shortcode( array() );
		$this->assertStringContainsString( 'id="chatkit-container"', $output );
		$this->assertStringContainsString( '<div', $output );
		$this->assertStringContainsString( '</div>', $output );
	}

	/**
	 * Test that the shortcode renders with custom container ID.
	 */
	public function test_shortcode_renders_custom_container() {
		$output = Beepi_ChatKit_Embed::render_chatkit_shortcode( array( 'container_id' => 'my-custom-chat' ) );
		$this->assertStringContainsString( 'id="my-custom-chat"', $output );
		$this->assertStringNotContainsString( 'id="chatkit-container"', $output );
	}

	/**
	 * Test that the container ID is sanitized properly.
	 */
	public function test_shortcode_sanitizes_container_id() {
		// Test with invalid characters that should be sanitized
		$output = Beepi_ChatKit_Embed::render_chatkit_shortcode( array( 'container_id' => 'my chat<script>' ) );
		// sanitize_html_class removes spaces and special characters
		$this->assertStringNotContainsString( '<script>', $output );
		$this->assertStringNotContainsString( ' ', $output );
	}

	/**
	 * Test that shortcode returns a non-empty string.
	 */
	public function test_shortcode_returns_non_empty() {
		$output = Beepi_ChatKit_Embed::render_chatkit_shortcode( array() );
		$this->assertNotEmpty( $output );
		$this->assertIsString( $output );
	}
}
