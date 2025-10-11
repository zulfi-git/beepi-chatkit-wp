<?php
/**
 * Test cases for default URL constants in Beepi ChatKit Embed
 *
 * @package Beepi_ChatKit_Embed
 */

/**
 * Test that default URL constants are defined and used correctly.
 */
class Test_Constants extends PHPUnit\Framework\TestCase {

	/**
	 * Test that DEFAULT_START_URL constant is defined.
	 */
	public function test_default_start_url_constant_exists() {
		$this->assertTrue( defined( 'Beepi_ChatKit_Embed::DEFAULT_START_URL' ) );
	}

	/**
	 * Test that DEFAULT_REFRESH_URL constant is defined.
	 */
	public function test_default_refresh_url_constant_exists() {
		$this->assertTrue( defined( 'Beepi_ChatKit_Embed::DEFAULT_REFRESH_URL' ) );
	}

	/**
	 * Test that DEFAULT_START_URL has the correct value.
	 */
	public function test_default_start_url_value() {
		$this->assertEquals( 
			'https://chatkit.beepi.no/api/chatkit/start', 
			Beepi_ChatKit_Embed::DEFAULT_START_URL 
		);
	}

	/**
	 * Test that DEFAULT_REFRESH_URL has the correct value.
	 */
	public function test_default_refresh_url_value() {
		$this->assertEquals( 
			'https://chatkit.beepi.no/api/chatkit/refresh', 
			Beepi_ChatKit_Embed::DEFAULT_REFRESH_URL 
		);
	}
}
