<?php
/* 
Ninja forms + Mailcimp API connection
Author: MZ Creative Studio
Author URI: https://www.mzcreativestudio.com
*/
add_action( 'ninja_forms_after_submission', 'mz_ninja_forms_after_submission' );
function mz_ninja_forms_after_submission( $form_data ){

	foreach ( $form_data['fields'] as $field ) {
		if( 'fname' == $field['key']){
			$fname = $field['value']; 
		}
		if( 'lname' == $field['key']){
			$lname = $field['value']; 
		}
		if( 'email' == $field['key']){
			$email = $field['value']; 
		}
		if( 'list_id' == $field['key']){
			$list_id = $field['value']; 
		}
	}
 	
	$apikey = "your-API-KEY";	// replace with your API key
	$auth = base64_encode("user:$apikey");
	$server = "us3";
	$url = "https://$server.api.mailchimp.com/3.0/lists/$list_id/members";
	$status = "subscribed";
	$data = array(
			"apikey"	=>	$apikey,
			"email_address"	=>	$email,
			"status"	=>	$status,
			"merge_fields"	=>	array(
					"FNAME"	=>	$fname,
					"LNAME"	=>	$lname,
			)
	);

	$json_data = json_encode($data);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(

		"content-type: application/json",
		"authorization: Basic $auth",
	)

	);
	curl_exec($ch);
	curl_close($ch);
	
	return $form_data;

}