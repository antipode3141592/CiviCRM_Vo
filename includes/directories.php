<?php

function racc_civi_individual_directory(){
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
    'RE_Integration.RE_Constituent_ID'
  ])
  ->addWhere('contact_type', '=', 'Individual')
  // ->addWhere('emails.is_primary', '=', 1)
  // ->addWhere('addresses.is_primary', '=', 1)
  ->addOrderBy('display_name', 'ASC')
  ->execute();
	ob_start();
	?>
	<div class="table_div">
	<table data-toggle="table">		<!-- data-toggle="table" for Bootstrap-Table -->
		<caption></caption>
		<thead>
			<tr>
				<th data-field="name_first" data-sortable="true">First Name</th>
				<th data-field="name_last" data-sortable="true">Last Name</th>
				<th data-field="name">Profile Link</th>
				<th data-field="id" data-sortable="true">ID</th>
				<th data-field="job_title" data-sortable="true">Job Title</th>
				<th data-field="zipcode" data-sortable="true">Postal Code</th>
				<th data-field="email" data-sortable="true">E-Mail</th>
				<th data-field="RE_ID" data-sortable="true">RE ID</th>
			</tr>
		</thead>
		<tbody>
	<?php
	$url_civicrm =get_site_url(null,'/individual-profile/');
	$bcc_email = "sktest11235813@gmail.com";
	foreach ($contacts as $contact) {
	  ?><tr>
	  	<?php 
	  	$link = esc_url_raw(add_query_arg(array(
			'id' => $contact["id"],
		), $url_civicrm));
			_e('<td class="name_first">'.$contact["first_name"].'</td>');
			_e('<td class="name_last">'.$contact["last_name"].'</td>');
	  		_e('<td><a href="'.$link.'">'.$contact["display_name"].'</a></td>');
	  		_e('<td class="id">'.$contact["id"].'</td>');
	  		_e('<td>'.$contact["job_title"].'</td>');
	  		_e('<td>'.$contact["addresses"][0]["postal_code"].'</td>');
	  		_e('<td><a href="mailto:'.$contact["emails"][0]["email"].'?bcc='.$bcc_email.'" target="_top">'.$contact["emails"][0]["email"].'</a></td>');
	  		_e('<td>'.$contact["RE_Integration"]["RE_Constituent_ID"].'</td>');
	  		?>
	  </tr>
	  <?php
	}
	?>
		</tbody>
	</table>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode('directory_individual', 'racc_civi_individual_directory');
?>