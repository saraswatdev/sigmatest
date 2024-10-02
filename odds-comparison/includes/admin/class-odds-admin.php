<?php

class Odds_Admin {
    private static $instance = null;

    private function __construct() {
        // Register settings and fields
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        // Add admin menu page
        add_action( 'admin_menu', array( $this, 'add_admin_page' ) );
    }

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Register settings
    public function register_settings() {
        // Register a setting
        register_setting( 'odds_comparison_settings', 'odds_comparison_options' );

        // Add a settings section
        add_settings_section(
            'odds_comparison_section',
            'Settings',
            array( $this, 'settings_section_callback' ),
            'odds_comparison'
        );

        // Add settings fields
        add_settings_field(
            'bookmakers', // Field ID
            'Bookmakers', // Field title
            array( $this, 'bookmakers_render' ), // Callback
            'odds_comparison', // Page to display the field
            'odds_comparison_section' // Section to display the field
        );
    }

    // Section callback
    public function settings_section_callback() {
        echo 'Configure the settings for Odds Comparison.';
    }

    // Render Bookmakers field
    public function bookmakers_render() {
        $options = get_option( 'odds_comparison_options' );
        ?>
        <input type='text' name='odds_comparison_options[bookmakers]' value='<?php echo esc_attr( $options['bookmakers'] ?? '' ); ?>'>
        <label for='bookmakers'>Comma-separated list of bookmakers</label>
        <?php
    }

    // Add admin page
    public function add_admin_page() {
        add_menu_page(
            'Odds Comparison',
            'Odds Comparison',
            'manage_options',
            'odds-comparison',
            array( $this, 'admin_page_content' ),
            'dashicons-chart-line'
        );
    }

    // Admin page content
    public function admin_page_content() {
        ?>
        <div class="wrap">
            <h1>Odds Comparison Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'odds_comparison_settings' ); // Ensure this matches the register_setting
                do_settings_sections( 'odds_comparison' ); // Ensure this matches the page slug
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}

// Initialize the Odds_Admin class
Odds_Admin::get_instance();
