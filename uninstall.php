<?php
/**
 * Uninstall script for Beepi ChatKit Embed
 *
 * @package Beepi_ChatKit_Embed
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
exit;
}

// Delete plugin options.
delete_option( 'beepi_chatkit_options' );
