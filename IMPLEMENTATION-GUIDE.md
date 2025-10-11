# Implementation Guide for Priority Phases

Quick reference for implementing the recommended improvements from the codebase assessment.

---

## Phase 1: Quick Wins ✅ COMPLETED

**Status:** ✅ Implemented  
**Time:** 1-2 hours  
**Date Completed:** October 2025

### Completed Items:
- ✅ Added `.editorconfig` for consistent formatting
- ✅ Added `.gitattributes` for proper Git handling
- ✅ Added `.gitignore` for excluding build artifacts
- ✅ Created `CONTRIBUTING.md` with contribution guidelines
- ✅ Enhanced JavaScript with JSDoc comments
- ✅ Added troubleshooting section to README
- ✅ Added documentation references
- ✅ Updated changelog

---

## Phase 2: Configuration Management (RECOMMENDED NEXT)

**Priority:** HIGH  
**Time:** 2-4 hours  
**Value:** Huge usability improvement

### Implementation Steps:

#### Step 1: Create Settings Page (1 hour)

Create new file: `includes/admin-settings.php`

```php
<?php
/**
 * Admin Settings Page
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Beepi_ChatKit_Settings {
    
    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'add_settings_page' ) );
        add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
    }
    
    public static function add_settings_page() {
        add_options_page(
            'ChatKit Settings',
            'ChatKit',
            'manage_options',
            'beepi-chatkit-settings',
            array( __CLASS__, 'render_settings_page' )
        );
    }
    
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
    
    public static function section_callback() {
        echo '<p>Configure your OpenAI ChatKit integration settings.</p>';
    }
    
    public static function workflow_id_callback() {
        $options = get_option( 'beepi_chatkit_options' );
        $value = isset( $options['workflow_id'] ) ? $options['workflow_id'] : '';
        echo '<input type="text" name="beepi_chatkit_options[workflow_id]" value="' . esc_attr( $value ) . '" class="regular-text" />';
        echo '<p class="description">Your ChatKit workflow ID from OpenAI</p>';
    }
    
    public static function start_url_callback() {
        $options = get_option( 'beepi_chatkit_options' );
        $value = isset( $options['start_url'] ) ? $options['start_url'] : 'https://chatkit.beepi.no/api/chatkit/start';
        echo '<input type="url" name="beepi_chatkit_options[start_url]" value="' . esc_attr( $value ) . '" class="regular-text" />';
        echo '<p class="description">Cloudflare Worker endpoint for token generation</p>';
    }
    
    public static function refresh_url_callback() {
        $options = get_option( 'beepi_chatkit_options' );
        $value = isset( $options['refresh_url'] ) ? $options['refresh_url'] : 'https://chatkit.beepi.no/api/chatkit/refresh';
        echo '<input type="url" name="beepi_chatkit_options[refresh_url]" value="' . esc_attr( $value ) . '" class="regular-text" />';
        echo '<p class="description">Cloudflare Worker endpoint for token refresh</p>';
    }
    
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
    
    public static function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>Beepi ChatKit Settings</h1>
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
```

#### Step 2: Update Main Plugin File (30 minutes)

Modify `beepi-chatkit-embed.php`:

```php
// Replace constants with function to get options
function beepi_chatkit_get_option( $key, $default = '' ) {
    $options = get_option( 'beepi_chatkit_options', array() );
    return isset( $options[ $key ] ) ? $options[ $key ] : $default;
}

// Include admin settings if in admin
if ( is_admin() ) {
    require_once plugin_dir_path( __FILE__ ) . 'includes/admin-settings.php';
    Beepi_ChatKit_Settings::init();
}

// Update enqueue_scripts to use options
wp_localize_script(
    'beepi-chatkit-init',
    'beepichatKitConfig',
    array(
        'startUrl'    => beepi_chatkit_get_option( 'start_url', 'https://chatkit.beepi.no/api/chatkit/start' ),
        'refreshUrl'  => beepi_chatkit_get_option( 'refresh_url', 'https://chatkit.beepi.no/api/chatkit/refresh' ),
        'workflowId'  => beepi_chatkit_get_option( 'workflow_id', '' ),
    )
);
```

#### Step 3: Add Activation Hook (15 minutes)

```php
// Add to beepi-chatkit-embed.php
register_activation_hook( __FILE__, 'beepi_chatkit_activate' );

function beepi_chatkit_activate() {
    // Set default options
    $default_options = array(
        'start_url' => 'https://chatkit.beepi.no/api/chatkit/start',
        'refresh_url' => 'https://chatkit.beepi.no/api/chatkit/refresh',
        'workflow_id' => '',
    );
    
    add_option( 'beepi_chatkit_options', $default_options );
}
```

#### Step 4: Add Uninstall Hook (15 minutes)

Create `uninstall.php`:

```php
<?php
/**
 * Plugin Uninstall Handler
 */

// If uninstall not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Delete plugin options
delete_option( 'beepi_chatkit_options' );
```

### Testing Checklist:
- [ ] Settings page appears under Settings menu
- [ ] Fields save correctly
- [ ] Values are properly sanitized
- [ ] Settings link appears on plugins page
- [ ] Shortcode still works with new settings
- [ ] Default values work on fresh install

---

## Phase 4: Security Hardening (RECOMMENDED NEXT)

**Priority:** HIGH  
**Time:** 2-3 hours  
**Value:** Critical for production

### Implementation Steps:

#### Step 1: Add Nonce Verification (30 minutes)

Update settings form in `includes/admin-settings.php`:

```php
public static function render_settings_page() {
    // Check user permissions
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'You do not have sufficient permissions to access this page.' );
    }
    
    ?>
    <div class="wrap">
        <h1>Beepi ChatKit Settings</h1>
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
```

#### Step 2: Enhance Input Validation (1 hour)

Update `sanitize_settings()`:

```php
public static function sanitize_settings( $input ) {
    $output = array();
    $errors = array();
    
    // Validate Workflow ID
    if ( isset( $input['workflow_id'] ) ) {
        $workflow_id = sanitize_text_field( $input['workflow_id'] );
        if ( ! empty( $workflow_id ) && ! preg_match( '/^[a-zA-Z0-9_-]+$/', $workflow_id ) ) {
            $errors[] = 'Invalid workflow ID format';
        } else {
            $output['workflow_id'] = $workflow_id;
        }
    }
    
    // Validate URLs
    if ( isset( $input['start_url'] ) ) {
        $url = esc_url_raw( $input['start_url'] );
        if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
            $errors[] = 'Invalid start URL';
        } else {
            $output['start_url'] = $url;
        }
    }
    
    if ( isset( $input['refresh_url'] ) ) {
        $url = esc_url_raw( $input['refresh_url'] );
        if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
            $errors[] = 'Invalid refresh URL';
        } else {
            $output['refresh_url'] = $url;
        }
    }
    
    // Display errors
    if ( ! empty( $errors ) ) {
        add_settings_error(
            'beepi_chatkit_options',
            'beepi_chatkit_errors',
            implode( '<br>', $errors ),
            'error'
        );
    }
    
    return $output;
}
```

#### Step 3: Add Rate Limiting Check (30 minutes)

Add to JavaScript initialization:

```javascript
// Track failed attempts
let failedAttempts = 0;
const MAX_ATTEMPTS = 3;
const RETRY_DELAY = 5000; // 5 seconds

getClientSecret: async function(currentClientSecret) {
    if (failedAttempts >= MAX_ATTEMPTS) {
        console.error('Beepi ChatKit: Too many failed attempts. Please refresh the page.');
        throw new Error('Rate limit exceeded');
    }
    
    try {
        // ... existing code ...
        failedAttempts = 0; // Reset on success
    } catch (error) {
        failedAttempts++;
        console.error('Beepi ChatKit: Error with client secret:', error);
        throw error;
    }
}
```

### Testing Checklist:
- [ ] Non-admin users cannot access settings
- [ ] Invalid URLs are rejected
- [ ] Invalid workflow IDs are rejected
- [ ] Error messages display properly
- [ ] Rate limiting works correctly
- [ ] No XSS vulnerabilities

---

## Phase 3: Testing (Optional but Recommended)

**Priority:** MEDIUM  
**Time:** 4-6 hours

### Quick Setup:

1. Install PHPUnit:
```bash
composer require --dev phpunit/phpunit
composer require --dev yoast/phpunit-polyfills
```

2. Create `phpunit.xml.dist`:
```xml
<?xml version="1.0"?>
<phpunit bootstrap="tests/bootstrap.php">
    <testsuites>
        <testsuite name="plugin">
            <directory>tests</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

3. Create test files in `tests/` directory

---

## Implementation Timeline

### Week 1: Configuration Management
- Day 1-2: Implement admin settings page
- Day 3: Update plugin to use options
- Day 4: Testing and bug fixes
- Day 5: Documentation update

### Week 2: Security Hardening
- Day 1-2: Add nonce verification and validation
- Day 3: Add rate limiting
- Day 4-5: Security testing and audit

### Week 3 (Optional): Testing
- Day 1-2: Setup test infrastructure
- Day 3-4: Write tests
- Day 5: CI/CD setup

---

## Success Criteria

### Phase 2:
- ✅ No more manual file editing required
- ✅ Settings page is user-friendly
- ✅ Backward compatible with existing setups
- ✅ Documentation updated

### Phase 4:
- ✅ All inputs are validated and sanitized
- ✅ No security vulnerabilities
- ✅ User permissions enforced
- ✅ Error handling is robust

---

## Notes

- Always test in a development environment first
- Maintain backward compatibility
- Keep changes minimal and focused
- Update documentation with each phase
- Version bump after each phase completion

---

For detailed assessment, see [ASSESSMENT.md](./ASSESSMENT.md)
