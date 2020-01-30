<?php
/*
Plugin Name: Basic CiviCRM Integration
Plugin URI: https://VO.org
Description: Customization of CiviCRM for Arts Nonprofits
Author: Sean Kirkpatrick
Author URI: https://VO.org
Contributors: 
Version: 0.12
Date of Last Revision:  1/30/2020
*/
 
/**********************************
* constants and globals
**********************************/
 
if(!defined('VO_CRM_BASE_URL')) {
	define('VO_CRM_BASE_URL', plugin_dir_url(__FILE__));
}
if(!defined('VO_CRM_BASE_DIR')) {
	define('VO_CRM_BASE_DIR', dirname(__FILE__));
}
 
// $stripe_options = get_option('stripe_settings');
 
/*******************************************
* plugin text domain for translations
*******************************************/
 
load_plugin_textdomain( 'VO_CRM', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
 
/**********************************
* includes
**********************************/
 
if(is_admin()) {
	// load admin includes
} else {
	// load front-end includes
	include(VO_CRM_BASE_DIR . '/includes/directories.php');
	include(VO_CRM_BASE_DIR . '/includes/profiles.php');
	include(VO_CRM_BASE_DIR . '/includes/contributions.php');
	include(VO_CRM_BASE_DIR . '/includes/emails.php');
	include(VO_CRM_BASE_DIR . '/includes/navigation.php');
	include(VO_CRM_BASE_DIR . '/includes/events.php');
	include(VO_CRM_BASE_DIR . '/includes/gravityforms_functions.php');
	include(VO_CRM_BASE_DIR . '/includes/db_functions.php');
	include(VO_CRM_BASE_DIR . '/includes/webhook_processing.php');
	include(VO_CRM_BASE_DIR . '/includes/enqueue_scripts.php');
}

