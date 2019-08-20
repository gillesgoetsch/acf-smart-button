<?php
/*
	Plugin Name: Advanced Custom Fields: Smart Button
	Plugin URI: https://github.com/gillesgoetsch/acf-smart-button
	Description: A button field that lets you choose between internal and external and gives you either a post_object or a url field
	Version: 1.0.4
	Author: Gilles Goetsch
	Author URI: https://gillesgoetsch.ch
	License: GPLv2 or later
	License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


/**
 * Load plugin textdomain.
 *
 * @see https://codex.wordpress.org/Function_Reference/load_plugin_textdomain
 *
 *  @type	action
 *  @since	1.0.0
 */
function acf_smart_button_load_textdomain() {
  load_plugin_textdomain( 'acf-smart-button', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}
add_action( 'init', 'acf_smart_button_load_textdomain' );


/**
 * Include field.
 *
 * @type	action
 * @since 1.0.0
 */
function include_field_types_smart_button( $version ) {
	include_once('acf-smart-button-v5.php');
}
add_action('acf/include_field_types', 'include_field_types_smart_button');


?>
