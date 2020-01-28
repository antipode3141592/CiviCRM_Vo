<?php
//a function and shortcode for a webhook catcher
//  logs incoming webhooks in an internal db webhooks table
function racc_crm_webhook_catcher(){
	//switch flow based on source of webhook.  currently supporting webhooks from:
	//  1)  Gravity Forms - many types of transactions
	//		- contact updates (email, address)
	//		- add pledge record
	//  2)  Stripe - incoming donations/payments
	//		- contact updates (email, address)
	//		- add payment record
	//  3)  Survey Monkey Apply - Grants and Public Art transactions
	//		- contact updates (email, address)
	
	if(isset($_GET['crm-listener'])){
		switch ($_GET['crm-listener']){
			case 'gravity_forms': 
				racc_webhook_gravity_forms();
				break;
			case 'stripe':
				racc_webhook_stripe();
				break;
			case 'sm_apply':
				racc_webhook_sm_apply();
				break;
			default:
				break;
		}
	}
	return false;
}
add_shortcode('crm_webhook', 'racc_crm_webhook_catcher');

function racc_webhook_gravity_forms(){
	global $wpdb;
	http_response_code(200); //standard response
	error_log("function stub:  racc_webhook_gravity_forms()");

	//store form data
	try{
		$body = file_get_contents('php://input');
		// grab the event information
		$event_json = json_decode($body);
		$sig_header = $_SERVER["HTTP_STRIPE_SIGNATURE"];	//grab signing signature


		$external_id = isset($event_json->external_id) ? $event_json->external_id : null;
		$source = isset($event_json->source) ? $event_json->source : null;
		$payload = isset($event_json->payload) ? $event_json->payload : null;
		$user_id = isset($event_json->user_id) ? $event_json->user_id : null;
		$email = isset($event_json->email) ? $event_json->email : null;
		$action = isset($event_json->action) ? $event_json->action : null;
		$status = isset($event_json->status) ? $event_json->status : 0;

		//table definition for racc_crm_webhooks:
		// 		id bigint UNSIGNED NOT NULL AUTO_INCREMENT,			
		// 		external_id bigint UNSIGNED NULL,
		// 		source nvarchar(50) NULL,
		// 		payload LONGTEXT NULL,
		// 		user_id nvarchar(50) NULL,
		// 		email nvarchar(50) NULL,
		// 		action nvarchar(50) NULL,
		// 		status int NOT NULL DEFAULT -1,
		// 		last_updated datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
		// 		first_updated datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,

		$results = $wpdb->get_results($wpdb->prepare("INSERT INTO racc_crm_webhooks(external_id, source, payload, user_id, email, action, status) VALUES(%s, %s, %s, %s, %s, %s, %s)", $external_id, $source, $payload, $user_id, $email, $action, $status));
		}catch(Exception $e){
			error_log("gravity_forms webhook catcher error: " + $e->message);
		}
}

function racc_webhook_stripe(){
	// global $wpdb;
	http_response_code(200);	//standard response header
	error_log("function stub:  racc_webhook_stripe()");
	return false;
}

function racc_webhook_sm_apply(){
	// global $wpdb;
	http_response_code(200);	//standard response header
	// retrieve the request's body and parse it as JSON
	// try{
		// $body = file_get_contents('php://input');
		// grab the event information
		// $event_json = json_decode($body);
	// }catch(Exception $e){
	// 	error_log("sm_apply webhook catcher error: " + $e->message);
	// }
	error_log("function stub:  racc_webhook_sm_apply()");
	// re-retrieving helps prevent attacks with fabricated event ids
	return false;
}

//CRON-scheduled periodic process to read 
function racc_crm_webhook_processor(){
	return false;
}

?>