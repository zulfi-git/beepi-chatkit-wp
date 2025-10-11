<?php
/**
 * Basic test cases for Beepi ChatKit Embed
 *
 * @package Beepi_ChatKit_Embed
 */

/**
 * Test basic plugin functionality.
 */
class Test_Basic extends PHPUnit\Framework\TestCase {

/**
 * Test that the plugin class exists.
 */
public function test_plugin_class_exists() {
$this->assertTrue( class_exists( 'Beepi_ChatKit_Embed' ) );
}

/**
 * Test that settings class exists.
 */
public function test_settings_class_exists() {
$this->assertTrue( class_exists( 'Beepi_ChatKit_Settings' ) );
}

/**
 * Test that helper function exists.
 */
public function test_helper_function_exists() {
$this->assertTrue( function_exists( 'beepi_chatkit_get_option' ) );
}

/**
 * Test that activation function exists.
 */
public function test_activation_function_exists() {
$this->assertTrue( function_exists( 'beepi_chatkit_activate' ) );
}
}
