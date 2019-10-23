<?php

function racc_civi_individual_directory(){
	global $wp;

	$results_per_page = isset($_GET['rpp']) ? sanitize_text_field($_GET['rpp']) : 25;	//default to show 25 results per page
	$results_offset = isset($_GET['ro']) ? sanitize_text_field($_GET['ro']) : 0;

	try{
		$contact_total = \Civi\Api4\Contact::get()
			->selectRowCount()
			->addWhere('contact_type', '=', 'Individual')
			->execute()
			->count();
  		// var_dump($contactCount);
  		// $contact_total = $contactCount[0]["row_count"];

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
			    'emails.email'
			  ])
		  ->addWhere('contact_type', '=', 'Individual')
		  ->setLimit($results_per_page)
		  ->setOffset($results_offset)
		  ->execute();
	}
	catch (\API_Exception $e) {
		$error = $e->getMessage();
		error_log($error);
		return false;
	}
	ob_start();
	?>
	<div class="table_div">
	<div class="table_filters_div">
		<form id="directory_form_filter" name="directory_form_filter" method="GET">
			<div class="form-row">
				<label for="rpp">Results to display: </label>
				<select id="rpp" name="rpp">
					<option value="25" <?php _e($results_per_page == 25 ? "selected" : "" ); ?> >25</option>
					<option value="50" <?php _e($results_per_page == 50 ? "selected" : "" ); ?> >50</option>
					<option value="100" <?php _e($results_per_page == 100 ? "selected" : "" ); ?> >100</option>
				</select>
				<button type="submit">OK</button>
			</div>
		</form>
	</div>
	<div>
		<p>Displaying <?php _e(number_format($results_offset,0)."-".number_format(number_format($results_offset,0) + number_format($results_per_page,0))." of ".number_format($contact_total,0)." total contacts."); ?> contact records.
		</p>
	</div>
	<nav>
		<ul class="pagination justify-content-center">
			<?php 
				$url_this_page = home_url(add_query_arg(array(),$wp->request));	//grab this page's url

				$prev_page_link = esc_url_raw(add_query_arg(array(	//add query arguments
					'rpp' => $results_per_page,
					'ro' => number_format($results_offset - $results_per_page,0)
					), $url_this_page));
				//when on the first page, the previous button should be disabled and page 1 highlighted
				//when on last page, the next button should be disabled and last page highlighted
				//for all other pages, display a maximum of 
				//TODO: add "go to start" and "go to end buttons"
				//change pagination to use |<<, <, >, and >>| instead of first, prev, next, and last (probably)
			?>
			<li class="page-item <?php $results_offset == 0 ? _e("disabled") : _e(""); ?>">
				<a class="page-link" href="<?php _e($prev_page_link) ?>">Previous</a></li>
			<?php
				$page_max = 5;
				$page_current = $results_offset;
			for($x = 0; $x < number_format($contact_total/$results_per_page,0); $x++)
			{
				$loop_link = esc_url_raw(add_query_arg(array(
					'rpp' => $results_per_page,
					'ro' => ), $url_this_page));
				?>
				<li class="page-item">
					<a class="page-link" href="<?php _e($loop_link) ?>"><?php _e($x + 1)?></a></li>
				<?php
			}
			?>
			<li class="page-item"><a class="page-link" href="#">Next</a></li>
		</ul>
	</nav>
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
				<!-- <th data-field="RE_ID" data-sortable="true">RE ID</th>
				<th data-field="Emma_ID" data-sortable="true">Emma ID</th> -->
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
	  		// _e('<td>'.$contact["RE_Integration"]["RE_Constituent_ID"].'</td>');
	  		// _e('<td>'.$contact["RE_Integration"]["Emma_Member_ID"].'</td>');
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