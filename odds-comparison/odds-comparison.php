<?php
/**
 * Plugin Name: Odds Comparison
 * Description: An advanced odds comparison plugin for displaying live odds from bookmakers.
 * Version: 1.0
 * Author: Ravi
 */

defined( 'ABSPATH' ) || exit;

define( 'ODDS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'ODDS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once ODDS_PLUGIN_PATH . 'includes/class-odds-comparison.php';
require_once ODDS_PLUGIN_PATH . 'includes/class-odds-fetcher.php';
require_once ODDS_PLUGIN_PATH . 'includes/admin/class-odds-admin.php';

// Initialize the plugin
function odds_comparison_init() {
    Odds_Comparison::get_instance();
}
add_action( 'plugins_loaded', 'odds_comparison_init' );


add_action('rest_api_init', function () {
    register_rest_route('odds/v1', '/bookmakers', array(
        'methods' => 'GET',
        'callback' => 'get_bookmakers',
        'permission_callback' => '__return_true', // Adjust permissions as needed
    ));
});

function get_bookmakers() {
    $options = get_option('odds_comparison_options');
    $bookmakers = !empty($options['bookmakers']) ? explode(',', $options['bookmakers']) : [];

    // Prepare an array for the response
    $bookmaker_list = [];
    foreach ($bookmakers as $bookmaker) {
        $bookmaker_list[] = [
            'label' => ucwords(str_replace('_', ' ', $bookmaker)), // Format label
            'value' => $bookmaker
        ];
    }

    return rest_ensure_response($bookmaker_list);
}
