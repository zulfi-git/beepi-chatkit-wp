<?php
/**
 * Admin Settings Page for Beepi ChatKit Embed
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

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
exit();
}

/**
 * Class to handle admin settings page and options.
 */
class Beepi_ChatKit_Settings {

/**
 * Initialize the settings.
 */
public static function init() {
add_action( 'admin_menu', array( __CLASS__, 'add_settings_page' ) );
add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
add_filter( 'plugin_action_links_' . plugin_basename( dirname( __DIR__ ) . '/beepi-chatkit-embed.php' ), array( __CLASS__, 'add_settings_link' ) );
add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_assets' ) );
}

/**
 * Add settings page to admin menu.
 */
public static function add_settings_page() {
add_options_page(
'Beepi ChatKit Settings',
'Beepi ChatKit',
'manage_options',
'beepi-chatkit-settings',
array( __CLASS__, 'render_settings_page' )
);
}

/**
 * Add settings link on plugins page.
 *
 * @param array $links Existing plugin action links.
 * @return array Modified plugin action links.
 */
public static function add_settings_link( $links ) {
$settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=beepi-chatkit-settings' ) ) . '">Settings</a>';
array_unshift( $links, $settings_link );
return $links;
}

/**
 * Enqueue admin assets for the settings page.
 *
 * @param string $hook_suffix The current admin page hook suffix.
 */
public static function enqueue_admin_assets( $hook_suffix ) {
// Only load on our settings page.
if ( 'settings_page_beepi-chatkit-settings' !== $hook_suffix ) {
return;
}

// Enqueue admin JavaScript.
wp_enqueue_script(
'beepi-chatkit-admin',
plugins_url( 'assets/js/admin-health.js', dirname( __FILE__ ) ),
array( 'jquery' ),
'1.3.0',
true
);

// Pass AJAX URL and nonce to JavaScript.
wp_localize_script(
'beepi-chatkit-admin',
'beepichatKitAdmin',
array(
'ajaxUrl' => admin_url( 'admin-ajax.php' ),
'nonce'   => wp_create_nonce( 'beepi_chatkit_health_nonce' ),
)
);

// Enqueue admin CSS.
wp_enqueue_style(
'beepi-chatkit-admin',
plugins_url( 'assets/css/admin-health.css', dirname( __FILE__ ) ),
array(),
'1.3.0'
);
}

/**
 * Register plugin settings.
 */
public static function register_settings() {
register_setting(
'beepi_chatkit_settings',
'beepi_chatkit_options',
array( __CLASS__, 'sanitize_settings' )
);

add_settings_section(
'beepi_chatkit_main',
'ChatKit Configuration',
array( __CLASS__, 'section_callback' ),
'beepi-chatkit-settings'
);

add_settings_field(
'workflow_id',
'Workflow ID',
array( __CLASS__, 'workflow_id_callback' ),
'beepi-chatkit-settings',
'beepi_chatkit_main'
);

add_settings_field(
'start_url',
'Start URL',
array( __CLASS__, 'start_url_callback' ),
'beepi-chatkit-settings',
'beepi_chatkit_main'
);

add_settings_field(
'refresh_url',
'Refresh URL',
array( __CLASS__, 'refresh_url_callback' ),
'beepi-chatkit-settings',
'beepi_chatkit_main'
);
}

/**
 * Settings section callback.
 */
public static function section_callback() {
echo '<p>Configure your OpenAI ChatKit integration settings.</p>';
}

/**
 * Workflow ID field callback.
 */
public static function workflow_id_callback() {
$options = get_option( 'beepi_chatkit_options' );
$value   = isset( $options['workflow_id'] ) ? $options['workflow_id'] : '';
echo '<input type="text" name="beepi_chatkit_options[workflow_id]" value="' . esc_attr( $value ) . '" class="regular-text" />';
echo '<p class="description">Your ChatKit workflow ID from OpenAI</p>';
}

/**
 * Start URL field callback.
 */
public static function start_url_callback() {
$options = get_option( 'beepi_chatkit_options' );
$value   = isset( $options['start_url'] ) ? $options['start_url'] : Beepi_ChatKit_Embed::DEFAULT_START_URL;
echo '<input type="url" name="beepi_chatkit_options[start_url]" value="' . esc_attr( $value ) . '" class="regular-text" />';
echo '<p class="description">Cloudflare Worker endpoint for token generation</p>';
}

/**
 * Refresh URL field callback.
 */
public static function refresh_url_callback() {
$options = get_option( 'beepi_chatkit_options' );
$value   = isset( $options['refresh_url'] ) ? $options['refresh_url'] : Beepi_ChatKit_Embed::DEFAULT_REFRESH_URL;
echo '<input type="url" name="beepi_chatkit_options[refresh_url]" value="' . esc_attr( $value ) . '" class="regular-text" />';
echo '<p class="description">Cloudflare Worker endpoint for token refresh</p>';
}

/**
 * Sanitize settings input.
 *
 * @param array $input Raw input from the form.
 * @return array Sanitized settings.
 */
public static function sanitize_settings( $input ) {
$output = array();

if ( isset( $input['workflow_id'] ) ) {
$output['workflow_id'] = sanitize_text_field( $input['workflow_id'] );
}

if ( isset( $input['start_url'] ) ) {
$output['start_url'] = esc_url_raw( $input['start_url'] );
}

if ( isset( $input['refresh_url'] ) ) {
$output['refresh_url'] = esc_url_raw( $input['refresh_url'] );
}

return $output;
}

/**
 * Render the settings page.
 */
public static function render_settings_page() {
if ( ! current_user_can( 'manage_options' ) ) {
wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'beepi-chatkit-embed' ) );
}
?>
<div class="wrap">
<h1>Beepi ChatKit Settings</h1>

<!-- Health Status Section -->
<div class="beepi-chatkit-health-section">
<h2>Worker Health Status</h2>
<div id="beepi-chatkit-health-status" class="beepi-health-container">
<p class="beepi-health-loading">Checking health status...</p>
</div>
<button type="button" id="beepi-chatkit-refresh-health" class="button">Refresh Status</button>
</div>

<form method="post" action="options.php">
<?php
settings_fields( 'beepi_chatkit_settings' );
do_settings_sections( 'beepi-chatkit-settings' );
submit_button();
?>
</form>
</div>
<?php
}
}
