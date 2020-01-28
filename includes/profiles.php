<?php
function get_profile_individual($id){
	$contacts = \Civi\Api4\Contact::get()
  ->setSelect([
    'id', 
    'display_name', 
    'job_title', 
    'organization_name', 
    'created_date', 
    'modified_date',
    'emails.email',
    'Integrations.RE_Constituent_ID'
  ])
  ->addWhere('id', '=', $id)
  ->execute();
	 ob_start();
	 ?>
	 <div class="table_div">
		<table data-toggle="table">
			<caption></caption>
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Email</th>
					<th>Job Title</th>
					<th>Organization</th>
					<th>Created Date</th>
					<th>Modified Date</th>
					<th>RE ID</th>
				</tr>
			</thead>
			<tbody>
	<?php
	$bcc_email = "sktest11235813@gmail.com";
	foreach ($contacts as $contact) {
	  ?><tr>
		<?php _e('<td>'.$contact["id"].'</td>');
		_e('<td>'.$contact["display_name"].'</td>');
		_e('<td><a href="mailto:'.$contact["emails"][0]["email"].'?bcc='.$bcc_email.'" target="_top">'.$contact["emails"][0]["email"].'</a></td>');
		_e('<td>'.$contact["job_title"].'</td>');
		_e('<td>'.$contact["organization_name"].'</td>');
		_e('<td>'.$contact["created_date"].'</td>');
		_e('<td>'.$contact["modified_date"].'</td>');
		_e('<td>'.$contact["Integrations"]["RE_Constituent_ID"].'</td>');?>
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

function racc_civi_view_individual_profile(){
	if (isset($_GET['id']) && ($_GET['id'] > 0)){
		$id = $_GET['id'];
		_e(get_profile_individual($id));
	}
}
add_shortcode('profile_individual', 'racc_civi_view_individual_profile');
?>