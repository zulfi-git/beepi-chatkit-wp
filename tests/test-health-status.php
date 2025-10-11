<?php
/**
 * Test health status functionality
 *
 * @package Beepi_ChatKit_Embed
 */

/**
 * Test health status feature.
 */
class Test_Health_Status extends PHPUnit\Framework\TestCase {

	/**
	 * Test that health status function exists.
	 */
	public function test_health_function_exists() {
		$this->assertTrue( function_exists( 'beepi_chatkit_get_health_status' ) );
	}

	/**
	 * Test that AJAX handler function exists.
	 */
	public function test_ajax_handler_exists() {
		$this->assertTrue( function_exists( 'beepi_chatkit_ajax_health_check' ) );
	}

	/**
	 * Test that health URL constant exists.
	 */
	public function test_health_url_constant_exists() {
		$this->assertTrue( defined( 'Beepi_ChatKit_Embed::DEFAULT_HEALTH_URL' ) );
	}

	/**
	 * Test health status function returns array.
	 */
	public function test_health_function_returns_array() {
		$result = beepi_chatkit_get_health_status();
		$this->assertIsArray( $result );
	}

	/**
	 * Test health status function returns success key.
	 */
	public function test_health_function_has_success_key() {
		$result = beepi_chatkit_get_health_status();
		$this->assertArrayHasKey( 'success', $result );
	}

	/**
	 * Test health status function returns expected data structure on success.
	 */
	public function test_health_function_success_structure() {
		$result = beepi_chatkit_get_health_status();
		
		if ( $result['success'] ) {
			$this->assertArrayHasKey( 'status', $result );
			$this->assertArrayHasKey( 'version', $result );
			$this->assertArrayNotHasKey( 'uptime', $result );
		} else {
			$this->assertArrayHasKey( 'error', $result );
		}
	}

	/**
	 * Test that admin assets enqueue method exists.
	 */
	public function test_enqueue_admin_assets_method_exists() {
		$this->assertTrue( method_exists( 'Beepi_ChatKit_Settings', 'enqueue_admin_assets' ) );
	}
}
