# Odds Comparison Plugin Documentation

## Table of Contents
1. [Overview](#overview)
2. [Installation and Setup](#installation-and-setup)
3. [Plugin Structure](#plugin-structure)
4. [Core Functionality](#core-functionality)
   - [Fetching Live Odds](#fetching-live-odds)
   - [Admin Dashboard](#admin-dashboard)
   - [Gutenberg Block](#gutenberg-block)
   - [Odds Conversion](#odds-conversion)
5. [Code Structure](#code-structure)
   - [Main Plugin File](#main-plugin-file)
   - [Odds Fetcher](#odds-fetcher)
   - [Admin Dashboard Integration](#admin-dashboard-integration)
   - [Gutenberg Block Integration](#gutenberg-block-integration)
6. [Extending the Plugin](#extending-the-plugin)
7. [Performance Optimization](#performance-optimization)
8. [Conclusion](#conclusion)

---

## Overview

The Odds Comparison plugin provides an advanced solution for comparing live odds from different bookmakers. It fetches live odds via API or web scraping, displays them in a comparison table on the front end, and provides administrators with full control over which bookmakers and markets are shown. Additionally, it integrates seamlessly with Gutenberg, allowing users to embed odds comparison tables dynamically within WordPress posts and pages.

## Installation and Setup

1. **Download the Plugin**: 
   Download the plugin files from the Git repository or zip file.

2. **Upload the Plugin**: 
   Go to your WordPress dashboard, navigate to `Plugins > Add New > Upload Plugin`, and upload the `odds-comparison.zip` file.

3. **Activate the Plugin**: 
   Once uploaded, click `Activate`.

4. **Configure Bookmakers**:
   In the WordPress admin, navigate to `Odds Comparison > Settings` to set your list of bookmakers (comma-separated).

5. **Add Gutenberg Block**: 
   To add the odds comparison block, create or edit a post or page, then select the `Odds Comparison` block from the Gutenberg editor.

---

## Plugin Structure

The plugin is structured as follows:

```
odds-comparison/
│
├── assets/ 
│   ├── css/ 
│   │   └── odds-comparison.css       # Front-end styling
│   └── js/ 
│       └── gutenberg-block.js        # Gutenberg block logic
│
├── includes/
│   ├── admin/ 
│   │   └── class-odds-admin.php      # Admin settings page and configuration
│   ├── class-odds-comparison.php     # Core plugin functionality
│   └── class-odds-fetcher.php        # Logic for fetching odds data
│
├── odds-comparison.php               # Main plugin file, bootstraps the plugin
└── README.md                         # Plugin documentation
```

---

## Core Functionality

### Fetching Live Odds

The plugin uses the `Odds_Fetcher` class to retrieve live odds from a third-party API or scrape data from an external odds comparison website. The logic is contained in `class-odds-fetcher.php`.

- **API Example**: We use `wp_remote_get()` to fetch data from an API.
- **Scraping Example**: You can extend this functionality to scrape from websites like Oddschecker or other services.
  
The fetched odds are then processed and displayed in a formatted table.

### Admin Dashboard

Administrators can control which bookmakers to display using the settings page:

1. **Bookmakers Field**: 
   A comma-separated list of bookmakers is entered in the settings page.
   
2. **Settings Page**: 
   Located in the WordPress admin under `Odds Comparison`, the settings page allows for full configuration of which bookmakers are shown on the front end.

### Gutenberg Block

The plugin provides a custom Gutenberg block allowing users to add the odds comparison table to posts or pages.

- **Block Attributes**: 
  The block allows the admin to select which bookmakers to show. This is done via a REST API that retrieves the available bookmakers.

- **Front-End Rendering**: 
  The block’s front-end display is handled via PHP and the `render_callback` function in the plugin. This allows the block to remain dynamic and scalable.

### Odds Conversion

The plugin can be extended to include odds conversion between fractional, decimal, and American formats. The current version fetches odds in decimal format by default. Future versions could include conversion functions to display odds in multiple formats.

---

## Code Structure

### Main Plugin File

`odds-comparison.php`:
- **Purpose**: This file initializes the plugin and ensures all components (classes, hooks, REST API endpoints) are loaded when WordPress is ready.
- **Key Points**: 
  - Defines constants for plugin paths.
  - Registers hooks to initialize the main plugin logic, REST API routes, and Gutenberg block.

```php
function odds_comparison_init() {
    Odds_Comparison::get_instance();
}
add_action( 'plugins_loaded', 'odds_comparison_init' );
```

### Odds Fetcher

`class-odds-fetcher.php`:
- **Purpose**: Fetches the live odds data from an external source (e.g., API or scraped website).
- **Example API Call**:
  ```php
  $response = wp_remote_get( 'https://api.the-odds-api.com/v3/odds/?sport=soccer' );
  $odds = json_decode( wp_remote_retrieve_body( $response ), true );
  ```

### Admin Dashboard Integration

`class-odds-admin.php`:
- **Purpose**: Manages the plugin settings page.
- **Key Points**:
  - Adds a settings page under `Odds Comparison`.
  - Allows the admin to set which bookmakers to display via a text field.

### Gutenberg Block Integration

`gutenberg-block.js`:
- **Purpose**: Adds a block in the Gutenberg editor.
- **Key Points**:
  - Dynamically fetches bookmakers from a REST API.
  - Allows the user to select bookmakers for display.

---

## Extending the Plugin

### Adding More Bookmakers

To add more bookmakers to the comparison, you can simply update the list in the plugin settings or extend the `Odds_Fetcher` class to pull data from additional sources.

- **Example**: Add new APIs or scraping logic inside `fetch_odds()` in `class-odds-fetcher.php`.

### Adding Odds Conversion

To add support for converting odds between formats (fractional, decimal, American), you can modify the `Odds_Fetcher` class to implement conversion logic. 

- **Example**:
  ```php
  function convert_to_fractional( $decimal_odds ) {
      return ( $decimal_odds - 1 ) . "/1";
  }
  ```

### Enhancing the Admin Interface

You can add more fields to the admin settings page, such as odds format or default sports market, by modifying the `class-odds-admin.php` file.

---

## Performance Optimization

The plugin is designed with performance in mind. Some key optimization techniques include:

- **Caching**: Use the WordPress Transients API to cache odds data for a set period of time to avoid frequent API calls.
  ```php
  $odds = get_transient( 'odds_data' );
  if ( false === $odds ) {
      $odds = Odds_Fetcher::fetch_odds( $bookmakers );
      set_transient( 'odds_data', $odds, HOUR_IN_SECONDS );
  }
  ```

- **Lazy Loading**: For larger sets of odds data, implement lazy loading techniques to load only the data needed on demand.

---

## Conclusion

The Odds Comparison plugin offers a robust solution for comparing bookmaker odds on a WordPress site. With its modular design, it can be easily extended to support more bookmakers, odds formats, and sports markets. Administrators have full control over how and where the odds are displayed, and the plugin integrates seamlessly with the Gutenberg editor for easy placement within posts and pages.

For future improvements, consider adding support for:
- Additional odds formats.
- Advanced filtering by sport or region.
- User personalization of bookmaker preferences.

