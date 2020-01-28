<?php

function racc_civi_individual_directory(){
	global $wp;
	//grab POST variables
	$filter_first_name = isset($_POST['first_name']) ? wp_strip_all_tags($_POST['first_name']) : null;
	$filter_last_name = isset($_POST['last_name']) ? wp_strip_all_tags($_POST['last_name']) : null;
	$filter_job_title = isset($_POST['job_title']) ? wp_strip_all_tags($_POST['job_title']) : null;
	$filter_postal_code = isset($_POST['postal_code']) ? wp_strip_all_tags($_POST['postal_code']) : null;
	$filter_email = isset($_POST['email']) ? wp_strip_all_tags($_POST['email']) : null;

	//grab GET variables
	$results_per_page = isset($_GET['rpp']) ? sanitize_text_field($_GET['rpp']) : 25;	//default to show 25 results per page
	$current_page = isset($_GET['pp']) ? sanitize_text_field($_GET['pp']) : 1;
	//calculate offset to send API based on $results_per_page and $current_page
	$results_offset = (($current_page - 1) * $results_per_page);

	$where_array = array(
		array('contact_type', '=', 'Individual'));

	if ($filter_first_name) { array_push($where_array, array('first_name', 'LIKE', $filter_first_name)); }
	if ($filter_last_name) { array_push($where_array, array('last_name', 'LIKE', $filter_first_name)); }
	if ($filter_job_title) { array_push($where_array, array('job_title', 'LIKE', $filter_job_title)); }
	if ($filter_postal_code) { array_push($where_array, array('addresses.postal_code', 'LIKE', $filter_postal_code)); }
	if ($filter_email) { array_push($where_array, array('emails.email', 'LIKE', $filter_email)); }
	// if ($filter_interests) { array_push($where_array, array('Interests.Contact_Interests', 'IN', $filter_interests)); }

	try{
		$contact_total_results = \Civi\Api4\Contact::get()
			->selectRowCount()
			->setWhere($where_array)
			->execute()
			->count();

		$contacts = \Civi\Api4\Contact::get()
			->setSelect([
			  	'id',
			  	'first_name',
			  	'last_name',
			    'display_name', 
			    'job_title', 
			    'addresses.street_address', 
			    'addresses.is_primary',
			    'emails.is_primary',
			    'addresses.postal_code', 
			    'emails.email',
			    'Interests.Contact_Interests'
			  ])
		  ->setWhere($where_array)
		  ->setLimit($results_per_page)
		  ->setOffset($results_offset)
		  ->setChain([
		    'tags' => ['EntityTag', 'get', ['where' => [['entity_id', '=', '$id']]]], 
		    'groups' => ['GroupContact', 'get', ['where' => [['contact_id', '=', '$id']]]],
  		  ])
		  ->execute();
	}
	catch (\API_Exception $e) {
		$error = $e->getMessage();
		error_log($error);
		return false;
	}
	ob_start();
	?>
	<div class="table_div table-responsive">
		<form id="filter_form" name="filter_form" method="POST">
			<div class='form-row'>
				<label for='first_name'>First Name</label>
				<input type='text' name='first_name' value="<?php _e($filter_first_name) ?>"/>
				<label for='last_name'>Last Name</label>
				<input type='text' name='last_name' value="<?php _e($filter_last_name) ?>"/>
			</div>
			<div class='form-row'>
				<label for='job_title'>Job Title</label>
				<input type='text' name='job_title' value="<?php _e($filter_job_title) ?>"/>
			</div>
			<div class='form-row'>
				<label for='email'>E-mail</label>
				<input type='text' name='email' value="<?php _e($filter_email) ?>"/>
			</div>
			<div class='form-row'>
				<label for='postal_code'>Postal Code</label>
				<input type='text' name='postal_code' value="<?php _e($filter_postal_code) ?>"/>
			</div>
			<?php
			// <div class='form-row'>
			// 	<label for='interests'>Communication Interests</label>
			// 	<input type='checkbox' name='interests[]' value='Newsletter'>Newsletter</input>
			// 	<input type='checkbox' name='interests[]' value='Volunteering'>Volunteering</input>
			// 	<input type='checkbox' name='interests[]' value='Opportunities'>Opportunities</input>
			// 	<input type='checkbox' name='interests[]' value='Events'>Events</input>
			// </div>
			?>
			<div class='form-row'>
				<button type='submit'>Filter!</button>
			</div>
		</form>
		<?php _e(create_navigation_pagination_controls($contact_total_results)); ?>
	<table data-toggle="table" data-show-columns="true">
		<caption></caption>
		<thead>
			<tr>
				<th data-field="id" data-sortable="true" data-switchable="false">ID</th>
				<th data-field="name_first" data-sortable="true">First Name</th>
				<th data-field="name_last" data-sortable="true">Last Name</th>
				<th data-field="job_title" data-sortable="true" >Job Title</th>
				<th data-field="zipcode" data-sortable="true">Postal Code</th>
				<th data-field="email" data-sortable="true" data-switchable="false">E-Mail</th>
				<th data-field="interests" data-sortable="false">Contact Interests</th>
				<th data-field="tags" data-sortable="false">Tags</th>
				<th data-field="groups" data-sortable="false">Groups</th>
			</tr>
		</thead>
		<tbody>
	<?php
	$url_civicrm =get_site_url(null,'/individual-profile/');
	$bcc_email = "sktest11235813@gmail.com";

	foreach ($contacts as $contact) {
	  ?><tr>

	  	<?php 
	  	// error_log(var_dump($contact));
	  	$link = esc_url_raw(add_query_arg(array(
			'id' => $contact["id"],
		), $url_civicrm));
			_e('<td class="id"><a href="'.$link.'">'.$contact["id"].'</a></td>');
			_e('<td class="name_first">'.$contact["first_name"].'</td>');
			_e('<td class="name_last">'.$contact["last_name"].'</td>');
	  		_e('<td class="job_title">'.$contact["job_title"].'</td>');
	  		_e('<td class="zipcode">');
	  		if(isset($contact["addresses"][0]["postal_code"])) {
	  			_e($contact["addresses"][0]["postal_code"]);
	  		}
  			_e('</td>');
	  		_e('<td class="email">');
	  		if(isset($contact["emails"][0]["email"])) {
	  			_e('<a href="mailto:'.$contact["emails"][0]["email"].'?bcc='.$bcc_email.'" target="_top">'.$contact["emails"][0]["email"].'</a>');
	  		}
	  		_e('</td>');
	  		_e('<td class="interests">');
	  		if(isset($contact["Interests"]["Contact_Interests"])) {
	  			_e(implode(',', $contact["Interests"]["Contact_Interests"]));
	  		}
	  		_e('</td>');
	  		_e('<td class="tags">');
	  		if(isset($contact["tags"])) {
	  			// foreach ($contact["tags"] as $_tag) {
	  			for ($x = 0; $x < count($contact["tags"]); $x++){
	  				$tags = \Civi\Api4\Tag::get()
					  ->setSelect([
					    'name',
					  ])
					  ->addWhere('id', '=', $contact["tags"][$x]["tag_id"])
					  ->execute();
					if ($x + 1 == count($contact["tags"])){
						_e($tags[0]["name"]);		//for the last element, do not print a comma
					} else {
						_e($tags[0]["name"].", ");
					}
					// if (count($tags) > 0){
					// 	_e($tags[0]["name"].", ");
					// }
	  			}
	  		}
	  		_e('</td>');
	  		_e('<td class="groups">');
	  		if(isset($contact["groups"])) {
	  			for ($x = 0; $x < count($contact["groups"]); $x++){
	  			// foreach ($contact["groups"] as $_group) {
	  				$groups = \Civi\Api4\Group::get()
					  ->setSelect([
					    'title',
					  ])
					  ->addWhere('id', '=', $contact["groups"][$x]["group_id"])
					  ->setLimit(25)
					  ->execute();
					// if (count($groups) > 0){
					if ($x + 1 == count($contact["groups"])){
						_e($groups[0]["title"]);		//for the last element, do not print a comma
					} else {
						_e($groups[0]["title"].", ");
					}
	  			}
	  			// var_dump($contact["groups"]);
	  			// _e(implode(',', $contact["groups"]["group_id"]));
	  		}
	  		_e('</td>');
	  		?>
	  </tr>
	  <?php
	}
	?>
		</tbody>
	</table>
	<?php _e(create_navigation_pagination_controls($contact_total_results)); ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode('directory_individual', 'racc_civi_individual_directory');
?>