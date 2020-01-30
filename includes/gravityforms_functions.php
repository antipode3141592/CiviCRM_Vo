<?php
// https://docs.civicrm.org/sysadmin/en/latest/troubleshooting/#trigger-rebuild

//process gravity form submission from Newsletter Signup (form id = 2)
//  see https://docs.gravityforms.com/gform_after_submission/ for details on the submission hook
function vo_after_newsletter_signup_submission($entry, $form){
	//grab entry data
	$field_id = 6;
	$field = RGFormsModel::get_field($form, $field_id);
	$value = is_object($field) ? $field->get_value_export($entry) : '';	//create comma separated list from checked values
	$body = array(
		'first_name' => rgar($entry, 1),
		'last_name' => rgar($entry, 2),
		'email' => rgar($entry, 5),
		'interests' => $value,
	);

	$results = \Civi\Api4\Contact::create()
		->addValue('contact_type', 'Individual')
		->addValue('first_name', $body['first_name'])
		->addValue('last_name', $body['last_name'])
		->addValue('Interests.Contact_Interests', explode(',', $value))
		->setChain(['primary_email' => ['Email', 'create', ['values' => 
			['contact_id' => '$id',
			 'email' => $body['email'],
			'location_type_id' => 'Main']]],])
		->execute();
}
add_action('gform_after_submission_2', 'vo_after_newsletter_signup_submission', 10, 2);

//process gravity form submission from Newsletter Signup (form id = 4)
function vo_after_quick_add_individual_submission($entry, $form){
		//grab entry data
	$field_id = 6;
	$field = RGFormsModel::get_field($form, $field_id);
	$value = is_object($field) ? $field->get_value_export($entry) : '';	//create comma separated list from checked values

	//rgar grabs the data from $entry.  to get data from multi-part input fields, use decimal seperators
	$body = array(
		'first_name' => rgar($entry, '10.3'),	//for field id=10, grab the 3rd 
		'middle_name' => rgar($entry, '10.4'),
		'last_name' => rgar($entry, '10.6'),
		'email' => rgar($entry, 5),
		'phone' => rgar($entry, 11),
		'notes' => rgar($entry, 9),
		'interests' => $value,
	);

	//deduplication check
	//TODO:  routing for deduplication.
		// if duplicate, cancel quick_add action and suggest reroute to update path.  if update, transfer $entry contents

	if (contact_record_exists($body['email'])){
		//api call to create new record from $entry details
		$results = \Civi\Api4\Contact::create()
			->addValue('contact_type', 'Individual')
			->addValue('first_name', $body['first_name'])
			->addValue('middle_name', $body['middle_name'])
			->addValue('last_name', $body['last_name'])
			->addValue('Interests.Contact_Interests', explode(',', $value))
			->setChain(['primary_email' => ['Email', 'create', ['values' => 
				['contact_id' => '$id',
				 'email' => $body['email'],
				'location_type_id' => 'Main']]],])
			->setChain(['primary_email' => ['Email', 'create', ['values' => 
				['contact_id' => '$id',
				 'email' => $body['email'],
				'location_type_id' => 'Main']]],])
			->execute();
	} else {
		//duplicate entry!
	}


}
add_action('gform_after_submission_4', 'vo_after_quick_add_individual_submission', 10, 2);

//use e-mail address to check for existence of the contact.  return true if exists
function contact_record_exists($email){
	$where_array = array(
		array('emails.email', '=', $emails));

	$contact_total_results = \Civi\Api4\Contact::get()
			->selectRowCount()
			->setWhere($where_array)
			->execute()
			->count();
	if ($contact_total_results > 0){
		return true;
	} else {
		return false;
	}
}
?>