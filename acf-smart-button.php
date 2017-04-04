<?php
/*
	Plugin Name: Advanced Custom Fields: Smart Button
	Plugin URI: https://github.com/gillesgoetsch//acf-smart-button
	Description: A button field that lets you choose between internal and external and gives you either a post_object or a url field
	Version: 1.0.0
	Author: Gilles Goetsch
	Author URI: http://kollektiv.ag
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


// Reference: https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
add_action( 'init', 'acf_smart_button_load_textdomain' );
/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function acf_smart_button_load_textdomain() {
 load_plugin_textdomain( 'acf-smart-button', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}

// 2. Include field type for ACF5
// $version = 5 and can be ignored until ACF6 exists
function include_field_types_smart_button( $version ) {

	include_once('acf-smart-button-v5.php');

}

add_action('acf/include_field_types', 'include_field_types_smart_button');

// 3. Include field type for ACF4
/*
 **NO SUPPORT FOR ACF4 YET
function register_fields_smart_button() {

	include_once('acf-smart-button-v4.php');

}

add_action('acf/register_fields', 'register_fields_smart_button');
*/



?>
