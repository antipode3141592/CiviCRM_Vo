<?php
/*
Plugin Name: RACC CiviCRM Integration
Plugin URI: https://racc.org
Description: Customization of CiviCRM for Regional Arts and Culture Council
Author: Sean Kirkpatrick
Author URI: https://racc.org
Contributors: 
Version: 0.01
Date of Last Revision:  07/15/2019
*/
 
/**********************************
* constants and globals
**********************************/
 
if(!defined('STRIPE_BASE_URL')) {
	define('STRIPE_BASE_URL', plugin_dir_url(__FILE__));
}
if(!defined('STRIPE_BASE_DIR')) {
	define('STRIPE_BASE_DIR', dirname(__FILE__));
}
 
$stripe_options = get_option('stripe_settings');
 
/*******************************************
* plugin text domain for translations
*******************************************/
 
load_plugin_textdomain( 'RACC_AOL', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
 
/**********************************
* includes
**********************************/
 
if(is_admin()) {
	// load admin includes
	// include(STRIPE_BASE_DIR . '/includes/settings.php');
} else {
	// load front-end includes
	include(STRIPE_BASE_DIR . '/includes/directories.php');
	include(STRIPE_BASE_DIR . '/includes/profiles.php');
}

