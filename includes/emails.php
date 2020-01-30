<?php
//-ALTERNATIVE ITEM 0-
function get_email_history_user($id){
	$activities = \Civi\Api4\Activity::get()
  ->setSelect([
    'id', 
    'subject', 
    'details',
    'created_date',
    'activity_contacts.contact_id'
  ])
  ->addWhere('activity_type_id', '=', 12)	//inbound email type id
  ->addWhere('activity_contacts.contact_id', '=', $id)
  ->addOrderBy('created_date', 'DESC')
 
  ->execute();
	 ob_start();
	 ?>
	 <div class="table_div">
		<table data-toggle="table">
			<caption></caption>
			<thead>
				<tr>
					<th data-field="id" data-sortable="true">ID</th>
					<th data-field="subject" data-sortable="true">Subject</th>
					<th data-field="details">Body</th>
					<th data-field="date" data-sortable="true">Created Date</th>
					<!-- <th data-field="from" data-sortable="true">From</th> -->
				</tr>
			</thead>
			<tbody>
	<?php	
	foreach ($activities as $activity) {
	  ?><tr>
		<?php _e('<td class="id">'.$activity["id"].'</td>');
		_e('<td class="subject">'.$activity["subject"].'</td>');
		_e('<td class ="details">'.$activity["details"].'</td>');
		_e('<td class ="date">'.$activity["created_date"].'</td>');
		// _e('<td class ="from">'.$activity["contact_data"]["display_name"].'</td>');?>
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

function vo_civi_view_individual_email_history(){
	if (isset($_GET['id']) && ($_GET['id'] > 0)){
		$id = $_GET['id'];
		_e(get_email_history_user($id));
	}
}
add_shortcode('email_history_individual', 'vo_civi_view_individual_email_history');
?>