<?php

function racc_civi_view_individual_profile(){
	if (isset($_GET['contact_id']){
		$contact_id = $_GET['contact_id'];
	} else {
		
	}
}
add_shortcode('profile_individual', 'racc_civi_view_individual_profile');
?>