<?php

function contributions_show_all(){	
	$contacts = \Civi\Api4\Contribution::get()
	->setSelect([
	    'id', 
	    'contact_id', 
	    'receive_date', 
	    'total_amount', 
	    'Giving.Appeal', 
	    'Giving.Fund', 
	  ])
  ->setChain([
    'contact_data' => ['Contact', 'get', ['where' => [['id', '=', '$contact_id']]], 0]
  ])
  ->setLi
  ->execute();
	 ob_start();
	 ?>
	 <div class="table_div">
		<table data-toggle="table">
			<caption></caption>
			<thead>
				<tr>
					<th data-field="id" data-sortable="true">ID</th>
					<th data-field="name" data-sortable="true">Name</th>
					<th data-field="data" data-sortable="true">Recieved Date</th>
					<th data-field="amount" data-sortable="true">Amount</th>
					<th data-field="appeal" data-sortable="true">Appeal</th>
					<th data-field="fund" data-sortable="true">Fund</th>
				</tr>
			</thead>
			<tbody>
	<?php
	$url_civicrm =get_site_url(null,'/individual-profile/');
	foreach ($contacts as $contact) {
		$link = esc_url_raw(add_query_arg(array(
			'id' => $contact["contact_id"],
		), $url_civicrm));
	  ?><tr>
		<?php _e('<td class="id">'.$contact["contact_id"].'</td>');
			_e('<td class="name"><a href="'.$link.'">'.$contact["contact_data"]["display_name"].'</a></td>');
			_e('<td class="date">'.$contact["receive_date"].'</td>');
			_e('<td class="amount">'.$contact["total_amount"].'</td>');
			_e('<td class="appeal">'.$contact["Giving"]["Appeal"].'</td>');
	  		_e('<td class="fund">'.$contact["Giving"]["Fund"].'</td>');?>
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
add_shortcode('contributions_show_all', 'contributions_show_all');

function contributions_user($userid){
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

function contributions_user_shortcode() {
	//grab shortcode attribute for user id
	if (isset($_GET['id']) && ($_GET['id'] > 0)){
		$id = $_GET['id'];
		_e(contributions_user($id));
	}
}
add_shortcode('contributions_show_user', 'contributions_user_shortcode');
?>