<?php
/*
*	This script catches responses for approved transactions from converge, and routes
*	to the receipt, or back to the donation form.
*/

//----- INCLUDE WP API
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-blog-header.php' );

global $wp_session;

//SET UP LOGGING
$debug = true;		//script debug variable, "true" will produce template-log.txt in plugin dir
if ($debug) {	$log='';	$log .= current_time('mysql') . PHP_EOL;
	//$log_file = plugins_url('/logs/response-.txt', __DIR__);
	$log_file = plugin_dir_path(__FILE__) .'response-log.txt';
}

//ASSIGN RESPONSE TO ARRAY
$response = array();
$keys = array();
$vals = array();

foreach ($_GET as $key => $val) {
	array_push($keys, $key);
	array_push($vals, $val);	
}

$response = array_combine($keys, $vals);
	
	
	if($debug) {
		$log.= '----- PRINTING RESPONSE' . PHP_EOL;
		foreach($response as $key => $val) {
			$log.= $key . '= ' . $val . PHP_EOL;
		}
		$log.= PHP_EOL;
	}
	
	//SET RESPONSE RESULT
	$ssl_result = $response['ssl_result'];
	$log.='RESPONSE RESULT: ' . $ssl_result . PHP_EOL;
	
	//WRITE LOGS
	if ($debug) 
		{
			file_put_contents($log_file, $log, FILE_APPEND);
		}

	//PROCESS RESPONSE AND REDIRECT
	if ($ssl_result == 0)		//txn approved
	{ 	
		foreach($response as $key => $val) {
			$wp_session[$key] = $val;
		}
		wp_safe_redirect(home_url('/receipt/'));
		exit();
	} else  {		//txn disapproved
		$wp_session['ssl_result_message'] = $response['ssl_result_message'];
		wp_safe_redirect(home_url('/donate/'));
		exit();
	}
?>