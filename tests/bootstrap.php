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

// Load the plugin main file for testing.
require_once dirname( __DIR__ ) . '/beepi-chatkit-embed.php';
