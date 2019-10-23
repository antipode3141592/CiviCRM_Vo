<?php
//load javascript scripts, add localized variables 
function racc_crm_load_scripts() {


	wp_enqueue_script('jquery');
	// wp_enqueue_script('listjs', 'https://cdnjs.cloudflare.com/ajax/libs/list.js/1.5.0/list.min.js');
	// wp_enqueue_script('listjs_functions', RACC_CRM_BASE_DIR, '/listjs_functions.js');

	//load bootstrap
	wp_enqueue_script('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js');
	wp_register_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
	wp_enqueue_style('bootstrap-css');

	//load bootstrap-table
	wp_enqueue_script('bootstrap-table', 'https://unpkg.com/bootstrap-table@1.15.3/dist/bootstrap-table.min.js');
	wp_register_style('bootstrap-table-css', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.15.3/bootstrap-table.css');
	wp_enqueue_style('bootstrap-table-css');

	// wp_enqueue_script('backboneradio', RACC_CRM_BASE_DIR.'/includes/js/backboneradiojs.js');
 }
add_action('wp_enqueue_scripts', 'racc_crm_load_scripts');