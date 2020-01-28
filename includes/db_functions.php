<?php
global $racc_db_version;
$racc_db_version = '0.02';	//updated 1/2/2020

function racc_db_install() {
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	global $wpdb;
	global $racc_db_version;
	
	$charset_collate = $wpdb->get_charset_collate();	

	//balances can only exist for members, so wp_id NOT NULL
	$sql = "CREATE TABLE racc_crm_webhooks(
		id bigint UNSIGNED NOT NULL AUTO_INCREMENT,			
		external_id bigint UNSIGNED NULL,
		source nvarchar(50) NULL,
		payload LONGTEXT NULL,
		user_id nvarchar(50) NULL,
		email nvarchar(50) NULL,
		action nvarchar(50) NULL,
		status int NOT NULL DEFAULT -1,
		last_updated datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		first_updated datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  		PRIMARY KEY  (id)
		) $charset_collate;";
	$results = dbDelta($sql);

	update_option( 'racc_db_version', $racc_db_version );
}

function racc_update_db_check(){
	global $racc_db_version;
	if (get_site_option('racc_db_version') < $racc_db_version){
		error_log("Updating racc custom tables.... code DB Version = " . $racc_db_version . "; site DB version = " . get_site_option('racc_db_version'));
		racc_db_install();
	}//else{
		//error_log("skipping db updates, installed RACC DB Version: " . get_site_option('racc_db_version'));
	//}
}
add_action('plugins_loaded', 'racc_update_db_check');


//register database config functions
register_activation_hook( __FILE__, 'racc_db_install' );
?>