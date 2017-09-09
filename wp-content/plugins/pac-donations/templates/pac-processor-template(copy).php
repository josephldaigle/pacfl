<?php 
    /* Template Name: PAC Processor */

	//FETCH SESSION
	global $wp_session;
	//var_dump($wp_session);
	$mode = false; 		//set to true for prod
	if (true == true) {

		$vldtr = new PAC_Form_Validator();
		$user_input = array();
		
		//FETCH USER INPUTS
		$keys = array();
		$vals = array();
		foreach($_POST as $name => $val) {
			//$log .= $name . '= ' . $val . PHP_EOL;
			if (!preg_match('/pac|\_wp\_http|submit/', $name)) {
				array_push($keys, $name);
				array_push($vals, $val);
			}
		}
		$user_input = array_combine($keys, $vals);
		
		//SET SESSION VARIABLES
		foreach($user_input as $key => $val) {
			$wp_session[$key] = $val;
		}
		
		//VALIDATE INPUTS
		$res = $vldtr->check_inputs($user_input);
		
		if (empty($res)) {
			//INPUT VALIDATION PASSED
			
			//CONVERT INPUTS
			$user_input['card_exp'] = (int)$user_input['card_exp'];
			$user_input['cvv'] = (int)$user_input['cvv'];
			
			//ATTEMPT TRANSACTION
			try {
				$txn_processor = new PAC_Transaction_Processor();
				//$txn_processor->debug_on();
				$txn_processor->set_mode($mode);
				$txn_processor->set_user_inputs($user_input);
				$response = $txn_processor->send_request();
			} catch (Exception $e) {
				//REDIRECT TO ERROR PAGE ON FAIL
				wp_safe_redirect(home_url('/error/'));
				exit();
			}
		} else {
			//INPUT VALIDATION FAILED
			$wp_session['inputs_msg'] = $res;
			wp_safe_redirect(home_url('/donate/'));
			exit();
		}
		
	} else {
		//NONCE INVALID
		wp_safe_redirect(home_url());
		exit();
	}
?>