<?php

function racc_civi_individual_directory(){
	$contacts = \Civi\Api4\Contact::get()
  ->setSelect([
  	'id',
    'display_name', 
    'job_title', 
    'addresses.street_address', 
    'addresses.postal_code', 
    'emails.email'
  ])
  ->addWhere('contact_type', '=', 'Individual')
  ->addWhere('emails.is_primary', '=', 1)
  ->addWhere('addresses.is_primary', '=', 1)
  ->addOrderBy('display_name', 'ASC')
  ->execute();
	ob_start();
	?>
	<div class="table_div">
	<table>
		<caption></caption>
		<thead>
			<tr>
				<th>Display Name</th>
				<th>ID</th>
				<th>Job Title</th>
				<th>Street Address</th>
				<th>Postal Code</th>
				<th>E-Mail</th>
			</tr>
		</thead>
		<tbody>
	<?php
	$url_civicrm =get_site_url(null,'/civicrm/');
	foreach ($contacts as $contact) {
	  ?><tr>
	  	<?php _e('<td>'.$contact["display_name"].'</td>');?>
	  	<?php _e('<td>'.$contact["id"].'</td>');?>
	  	<?php _e('<td>'.$contact["job_title"].'</td>');?>
	  	<?php _e('<td>'.$contact["addresses"][0]["street_address"].'</td>');?>
	  	<?php _e('<td>'.$contact["addresses"][0]["postal_code"].'</td>');?>
	  	<?php _e('<td>'.$contact["emails"][0]["email"].'</td>');?>
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