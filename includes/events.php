<?php

function events_show_all(){
	//grab GET variables
	$results_per_page = isset($_GET['rpp']) ? sanitize_text_field($_GET['rpp']) : 25;	//default to show 25 results per page
	$current_page = isset($_GET['pp']) ? sanitize_text_field($_GET['pp']) : 1;
	//calculate offset to send API based on $results_per_page and $current_page
	$results_offset = (($current_page - 1) * $results_per_page);

	$event_total_results = \Civi\Api4\Event::get()
	->setSelect([
	    'id', 
	    'summary', 
	    'title', 
	    'start_date', 
	    'end_date', 
	    'registration_link_text', 
	    'is_active',
	  ])
	  ->selectRowCount()
	  ->execute()
	  ->count();

	$events = \Civi\Api4\Event::get()
	  ->setSelect([
	    'id', 
	    'summary', 
	    'title', 
	    'start_date', 
	    'end_date', 
	    'registration_link_text', 
	    'is_active',
	  ])
		->setLimit($results_per_page)
		->setOffset($results_offset)
  		->execute();
	
	 ob_start();
	 ?>
	 <div class="table_div">
	 	<?php _e(create_navigation_pagination_controls($event_total_results)); ?>
		<table data-toggle="table">
			<caption></caption>
			<thead>
				<tr>
					<th data-field="id" data-sortable="true">ID</th>
					<th data-field="summary" data-sortable="true">Summary</th>
					<th data-field="title" data-sortable="true">Title</th>
					<th data-field="start_date" data-sortable="true">Start Date</th>
					<th data-field="end_date" data-sortable="true">End Date</th>
					<th data-field="registration_link" data-sortable="false">Registration Link</th>
					<th data-field="is_active" data-sortable="true">Is Active?</th>
				</tr>
			</thead>
			<tbody>
	<?php

	// $url_civicrm =get_site_url(null,'/individual-profile/');
	foreach ($events as $event) {
		// $link = esc_url_raw(add_query_arg(array(
		// 	'id' => $event["contact_id"],
		// ), $url_civicrm));
	  ?><tr>
		<?php _e('<td class="id">'.$event["id"].'</td>');
			_e('<td class="summary">'.$event["summary"].'</td>');
			_e('<td class="title">'.$event["title"].'</td>');
			_e('<td class="start_date">'.$event["start_date"].'</td>');
			_e('<td class="end_date">'.$event["end_date"].'</td>');
	  		_e('<td class="registration_link">'.$event["registration_link_text"].'</td>');
	  		_e('<td class="is_active">'.$event["is_active"].'</td>');?>
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
add_shortcode('events_show_all', 'events_show_all');

function events_user($userid){
	$contributions = \Civi\Api4\Contribution::get()
	  ->setSelect([
	    'id', 
	    'contact_id', 
	    'receive_date', 
	    'total_amount', 
	    'Giving.Appeal', 
	    'Giving.Fund', 
	  ])
	  ->addWhere('contact_id', '=', $userid)
	  ->execute();
	  ob_start();
	  ?>
	  <div class="table_div">
		<table data-toggle="table">		<!-- data-toggle="table" for Bootstrap-Table -->
		<caption></caption>
		<thead>
			<tr>
				<th data-field="id" data-sortable="true">Gift ID</th>
				<th data-field="date" data-sortable="true">Gift Date</th>
				<th data-field="amount" data-sortable="true">Gift Amount</th>
				<th data-field="appeal" data-sortable="true">Appeal</th>
				<th data-field="fund" data-sortable="true">Fund</th>
			</tr>
		</thead>
		<tbody>
  		<?php
	foreach ($contributions as $contribution) {
		// var_dump($contribution);
		//dot fields in query are arrays of arrays
	  ?><tr>
	  	<?php
	  	_e('<td class="id">'.$contribution["id"].'</td>');
	  	_e('<td class="date">'.$contribution["receive_date"].'</td>');
	  	_e('<td class="amount">'.$contribution["total_amount"].'</td>');
	  	_e('<td class="appeal">'.$contribution["Giving"]["Appeal"].'</td>');
	  	_e('<td class="fund">'.$contribution["Giving"]["Fund"].'</td>');
		?>
		</tr><?php
	}?>
		</tbody>
	</table>
	</div>
	<?php
	return ob_get_clean();
}

function events_user_shortcode() {
	//grab shortcode attribute for user id
	if (isset($_GET['id']) && ($_GET['id'] > 0)){
		$id = $_GET['id'];
		_e(events_user($id));
	}
}
add_shortcode('events_show_user', 'events_user_shortcode');
?>