<?php
/**
 * Test cases for startScreen configuration
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
 * Test startScreen configuration functionality.
 */
class Test_Startscreen extends PHPUnit\Framework\TestCase {

	/**
	 * Test that default startScreen greeting is returned when not set.
	 */
	public function test_default_start_screen_greeting() {
		delete_option( 'beepi_chatkit_options' );
		$greeting = beepi_chatkit_get_option( 'start_screen_greeting', 'How can I help you today?' );
		$this->assertEquals( 'How can I help you today?', $greeting );
	}

	/**
	 * Test that default startScreen prompt label is returned when not set.
	 */
	public function test_default_start_screen_prompt_label() {
		delete_option( 'beepi_chatkit_options' );
		$label = beepi_chatkit_get_option( 'start_screen_prompt_label', 'Get Started' );
		$this->assertEquals( 'Get Started', $label );
	}

	/**
	 * Test that default startScreen prompt text is returned when not set.
	 */
	public function test_default_start_screen_prompt_text() {
		delete_option( 'beepi_chatkit_options' );
		$text = beepi_chatkit_get_option( 'start_screen_prompt_text', 'Hi! How can you assist me today?' );
		$this->assertEquals( 'Hi! How can you assist me today?', $text );
	}

	/**
	 * Test that custom startScreen greeting can be saved and retrieved.
	 */
	public function test_custom_start_screen_greeting() {
		$options = array(
			'start_screen_greeting' => 'Welcome! How may I assist?',
		);
		update_option( 'beepi_chatkit_options', $options );
		
		$greeting = beepi_chatkit_get_option( 'start_screen_greeting', 'How can I help you today?' );
		$this->assertEquals( 'Welcome! How may I assist?', $greeting );
	}

	/**
	 * Test that custom startScreen prompt label can be saved and retrieved.
	 */
	public function test_custom_start_screen_prompt_label() {
		$options = array(
			'start_screen_prompt_label' => 'Start Chat',
		);
		update_option( 'beepi_chatkit_options', $options );
		
		$label = beepi_chatkit_get_option( 'start_screen_prompt_label', 'Get Started' );
		$this->assertEquals( 'Start Chat', $label );
	}

	/**
	 * Test that custom startScreen prompt text can be saved and retrieved.
	 */
	public function test_custom_start_screen_prompt_text() {
		$options = array(
			'start_screen_prompt_text' => 'Hello! What can you do for me?',
		);
		update_option( 'beepi_chatkit_options', $options );
		
		$text = beepi_chatkit_get_option( 'start_screen_prompt_text', 'Hi! How can you assist me today?' );
		$this->assertEquals( 'Hello! What can you do for me?', $text );
	}

	/**
	 * Test that activation hook sets default startScreen options.
	 */
	public function test_activation_sets_default_startscreen_options() {
		delete_option( 'beepi_chatkit_options' );
		beepi_chatkit_activate();
		
		$options = get_option( 'beepi_chatkit_options' );
		$this->assertIsArray( $options );
		$this->assertArrayHasKey( 'start_screen_greeting', $options );
		$this->assertArrayHasKey( 'start_screen_prompt_label', $options );
		$this->assertArrayHasKey( 'start_screen_prompt_text', $options );
		$this->assertEquals( 'How can I help you today?', $options['start_screen_greeting'] );
		$this->assertEquals( 'Get Started', $options['start_screen_prompt_label'] );
		$this->assertEquals( 'Hi! How can you assist me today?', $options['start_screen_prompt_text'] );
	}

	/**
	 * Test that startScreen settings are properly sanitized.
	 */
	public function test_startscreen_settings_sanitization() {
		$input = array(
			'start_screen_greeting' => '<script>alert("XSS")</script>Hello',
			'start_screen_prompt_label' => 'Start<br>Chat',
			'start_screen_prompt_text' => 'Hello<strong>World</strong>',
		);
		
		$sanitized = Beepi_ChatKit_Settings::sanitize_settings( $input );
		
		$this->assertArrayHasKey( 'start_screen_greeting', $sanitized );
		$this->assertArrayHasKey( 'start_screen_prompt_label', $sanitized );
		$this->assertArrayHasKey( 'start_screen_prompt_text', $sanitized );
		
		// Verify that HTML tags are stripped
		$this->assertStringNotContainsString( '<script>', $sanitized['start_screen_greeting'] );
		$this->assertStringNotContainsString( '<br>', $sanitized['start_screen_prompt_label'] );
		$this->assertStringNotContainsString( '<strong>', $sanitized['start_screen_prompt_text'] );
	}

	/**
	 * Clean up after tests.
	 */
	protected function tearDown(): void {
		delete_option( 'beepi_chatkit_options' );
		parent::tearDown();
	}
}
