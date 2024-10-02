<?php

class Odds_Comparison {

    private static $instance = null;

    private function __construct() {
        // Hooks for Gutenberg block
        add_action( 'init', array( $this, 'register_gutenberg_block' ) );

        // Enqueue styles and scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

        // Initialize admin functionality
        Odds_Admin::get_instance();
    }

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function register_gutenberg_block() {
        wp_register_script(
            'odds-block',
            ODDS_PLUGIN_URL . 'assets/js/gutenberg-block.js',
            array( 'wp-blocks', 'wp-editor', 'wp-components', 'wp-element' ),
            filemtime( ODDS_PLUGIN_PATH . 'assets/js/gutenberg-block.js' )
        );

        register_block_type( 'odds/comparison', array(
            'editor_script' => 'odds-block',
            'render_callback' => array( $this, 'render_odds_comparison_block' ),
        ) );
    }

    public function enqueue_assets() {
        wp_enqueue_style( 'odds-comparison', ODDS_PLUGIN_URL . 'assets/css/odds-comparison.css', array(), '1.0' );
    }

    public function render_odds_comparison_block( $attributes ) {
        $bookmakers = $attributes['bookmakers'] ?? [];
        $odds_data = Odds_Fetcher::fetch_odds( $bookmakers );

        ob_start();
        ?>
        <div class="odds-comparison">
            <h2>Odds Comparison</h2>
            <ul>
                <?php foreach ( $odds_data as $bookmaker => $odds ) : ?>
                    <li><?php echo esc_html( $bookmaker ); ?>: <?php echo esc_html( $odds ); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
        return ob_get_clean();
    }
}

function render_odds_comparison_block( $attributes ) {
    $bookmakers = get_option( 'odds_comparison_bookmakers', [] );
    $odds = Odds_Fetcher::fetch_odds( $bookmakers );

    if ( empty( $odds ) ) {
        return '<p>No odds available.</p>';
    }

    $output = '<div class="odds-comparison-table">';
    foreach ( $odds as $odd ) {
        // Format the output here based on the odds data structure
        $output .= '<div class="odds-row">';
        $output .= '<span class="bookmaker">' . esc_html( $odd['bookmaker'] ) . '</span>';
        $output .= '<span class="odds">' . esc_html( $odd['odds'] ) . '</span>';
        $output .= '</div>';
    }
    $output .= '</div>';

    return $output;
}
