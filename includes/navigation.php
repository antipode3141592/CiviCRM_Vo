<?php

function create_navigation_pagination_controls($contact_total_results){
	global $wp;

	//grab GET variables
	$results_per_page = isset($_GET['rpp']) ? sanitize_text_field($_GET['rpp']) : 25;	//default to show 25 results per page
	$current_page = isset($_GET['pp']) ? sanitize_text_field($_GET['pp']) : 1;
	//calculate offset to send API based on $results_per_page and $current_page
	$results_offset = (($current_page - 1) * $results_per_page);
	
	$page_max_display = 5;  //maximum number of pages to display at one time (not including first/prev/next/last and "...")
	$page_loop_end = $current_page + $page_max_display;
	$page_last = ceil($contact_total_results / $results_per_page );	//round up total_results/results_per_page to get total page count
	$page_next_to_last = $page_last - 1;

	ob_start();
	?>
	<div class="table_filters_div">
		<form id="directory_form_filter" name="directory_form_filter" method="GET">
			<div class="form-row">
				<label for="rpp">Results to display: </label>
				<select id="rpp" name="rpp">
					<option value="25" <?php _e($results_per_page == 25 ? "selected" : "" ); ?> >25</option>
					<option value="50" <?php _e($results_per_page == 50 ? "selected" : "" ); ?> >50</option>
					<option value="100" <?php _e($results_per_page == 100 ? "selected" : "" ); ?> >100</option>
					<option value="500" <?php _e($results_per_page == 500 ? "selected" : "" ); ?> >500</option>
				</select>
				<input type="hidden" id="pp" name="pp" value="<?php _e($current_page)?>"/>
				<button type="submit">OK</button>
			</div>
		</form>
		<form id="directory_form_page_selection_form" name="directory_form_page_selection_form" method="GET">
			<div class="form-row">
				<label for="pp">Skip to Page</label>
				<input type="number" min="1" max="<?php _e($page_last); ?>" value="<?php _e($current_page); ?>" id="pp" name="pp"/>
				of <?php _e($page_last) ?> Pages
				<input type="hidden" id="rpp" name="rpp" value="<?php _e($results_per_page)?>"/>
				<button type="submit">Go!</button>
			</div>
			
		</form>
	</div>
	<div>
		<p>Displaying <?php _e(number_format($results_offset + 1,0)." to ".number_format(($results_offset + $results_per_page + 1) <= $contact_total_results ? ($results_offset + $results_per_page + 1) : $contact_total_results, 0)." of ".number_format($contact_total_results,0)." total records."); ?>
		</p>
	</div>
	<nav>
		<ul class="pagination justify-content-center">
			<?php
				//when on the first page, the previous button should be disabled and page 1 highlighted
				//when on last page, the next button should be disabled and last page highlighted
				// $url_this_page = home_url(add_query_arg(array(),$wp->request));	//grab this page's url
				$url_this_page = home_url($wp->request);
				$first_page_link = esc_url_raw(add_query_arg(array(	//add query arguments
					'rpp' => $results_per_page,
					'pp' => 1
					), $url_this_page));
				$prev_page_link = esc_url_raw(add_query_arg(array(	//add query arguments
					'rpp' => $results_per_page,
					'pp' => number_format($current_page - 1,0)
					), $url_this_page));
				$next_page_link = esc_url_raw(add_query_arg(array(	//add query arguments
					'rpp' => $results_per_page,
					'pp' => number_format($current_page + 1,0)
					), $url_this_page));
				$last_page_link = esc_url_raw(add_query_arg(array(	//add query arguments
					'rpp' => $results_per_page,
					'pp' => $page_last
					), $url_this_page));
				?>
			<li class="page-item <?php $current_page == 1 ? _e("disabled") : _e(""); ?>">
				<a class="page-link" href="<?php _e($first_page_link) ?>">|&lt;&lt;</a></li>
			<li class="page-item <?php $current_page == 1 ? _e("disabled") : _e(""); ?>">
				<a class="page-link" href="<?php _e($prev_page_link) ?>">&lt;</a></li>
			<?php
			for($x = $current_page; $x < $page_loop_end; $x++)
			{
				if ($x > $page_last){ continue; }
				$loop_link = esc_url_raw(add_query_arg(
					array(
						'rpp' => $results_per_page,
						'pp' => $x
					), $url_this_page));
				?>
				<li class="page-item <?php $current_page == $x ? _e("active") : _e(""); ?>">
					<a class="page-link" href="<?php _e($loop_link) ?>"><?php _e($x)?></a></li>
				<?php
			}
			?>
			<li class="page-item <?php $current_page == $page_last ? _e("disabled") : _e(""); ?>">
				<a class="page-link" href="<?php _e($next_page_link) ?>">&gt;</a></li>
			<li class="page-item <?php $current_page == $page_last ? _e("disabled") : _e(""); ?>">
				<a class="page-link" href="<?php _e($last_page_link) ?>">&gt;&gt;|</a></li>
		</ul>
	</nav>
	<?php
	return ob_get_clean();
}

function create_filter_controls($args = null)
{
	ob_start();
	if (!empty($args)){
		foreach($args as $arg){

		}
	}
	?>

	<?php
	return ob_get_clean();
}
?>