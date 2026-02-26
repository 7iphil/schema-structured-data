<?php
/**
 * Plugin Name: Schema Structured Data
 * Plugin URI: https://iphil.top/portfolio/schema-structured-data/
 * Description: Generate Schema.org structured data via shortcode. Supports HowTo, FAQPage, ItemList, CreativeWork.
 * Version: 1.0.0
 * Author: Phil
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: schema-structured-data
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'SSD_PATH', plugin_dir_path( __FILE__ ) );

spl_autoload_register( 'ssd_autoloader' );

function ssd_autoloader( $class_name ) {
    $file = SSD_PATH . 'includes/class-' . strtolower( str_replace( '_', '-', $class_name ) ) . '.php';
    if ( file_exists( $file ) ) {
        require_once $file;
    }
}

add_action( 'init', 'ssd_init' );

function ssd_init() {
    $shortcode = new SSD_Shortcode();
    $shortcode->register();
}

add_action( 'wp_footer', 'ssd_output', 1 );

function ssd_output() {
    
    $queue = SSD_Schema::get_queue();
    
    if ( ! empty( $queue ) ) {
        foreach ( $queue as $schema ) {
            echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
        }
    }
}