<?php
/*
*	This class provides validation methods for form inputs to
*	the donation processing script.
*
*	This script only ensures form input is valid for the expected value.
*	Validation against the payment account is conducted on external server.
*/

class PAC_Form_Validator {

/*
*	@function 	- validates donation amount
*	@params 	$donation - user entered donation
*	@return		boolean
*/
function check_donation($donation){
		
	if (is_null($donation) || empty($donation)) {
		return false;
	} else {
		$donation = trim($donation);
		$donation = str_replace('$', '', $donation);
		$donation = str_replace(',', '', $donation);
		$len = strlen($donation);
		
		if (is_numeric($donation)) {
			if (floatval($donation)) {
				if ($donation > 0) {
					if (!(false === ($pos = strpos($donation, '.')))) {
						if (($len - $pos - 1) <= 2) {
							return true;
						}
					} else {
						return true;
					}
				}
			}
		}
		return false;
	}
}


/*
*	@function 	- validates credit card number
*	@params 	$cardnumber - user entered credit card number
*	@return 	boolean
*/	
function check_card_number($card_number, $card_type) {
    $cc_checker = new PAC_CC_Validator();
    return $cc_checker->checkCreditCard($card_number, $card_type);
}
 


/*
*	@function 	- validates card expiration date entered by user
*	@params 	@date - a 4 digit date (MMYY)
*	@return 	boolean
*/
function check_exp_date($date) {
	
	if ($date === null || empty($date) )
	{
		return false;
	}
	
	if (strlen($date) == 4) {
		
		$month = substr($date, 0, 2);
		$year = substr($date, 2, 2);
		
		if ((int)$year >= date("y")) {
			if ((int)$month <= 12) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		} 
	} else {
		return false;
	}
}


/*
*	@function 	- validates ccv length against card type
*	@params 	$card_type - user entered  credit card type (visa, master)
*				$card_ccv - user entered ccv code (security on back of card)
*	@return		boolean
*/
function check_card_cvv($card_type, $card_ccv) {	
	//check ccv is int
	if (is_null($card_ccv) || empty($card_ccv)) {
		return false;
	} else if (!ctype_digit($card_ccv)) {
		return false;
	}
	//convert type to int
	$i = 0;
	switch ($card_type) {
		case 'american':
			$i = 2;
			break;
		default:
			$i = 1;
			break;
	}

	//test number of digits
	if ($i == 2) {
		if (strlen($card_ccv) !== 4) {
			
			// The credit card is an American Express card but does not have a four digit CVV code
			return false;
		}
	} else if(strlen($card_ccv) !== 3) {
			// The credit card is a Visa, MasterCard, or Discover Card card but does not have a three digit CVV code
			return false;
	} 
	return true;
}
    

/*	
*	@function 	- validates email address
*	@params 	$email - user entered email address
*	@return		boolean
*/
function check_email($email){
	if ( empty($email) )
	{
		return true;
	}
		
	if (false == (filter_var($email, FILTER_VALIDATE_EMAIL))) {
		return false;
	} else {
		return true;
	} 
}

/*
*	@function 	validates address entered by user
*	@params		$address - the address the user entered
*	@return 	boolean
*/
function check_address($address) {
	if (!is_null($address) && !empty($address)) {
		return true;
	}
	return false;
}


/*
*	@function 	validates city entered by user
*	@params		$city - the city the user entered
*	@return 	boolean
*/
function check_city($city) {
		
	if (!is_null($city) && !empty($city)) {
		return  preg_match('/^[a-zA-Z-\s]+$/', $city);
	}
	return false;

}


/*
*	@function 	validates zip code entered by user
*	@params 	@zip - a 5 digit zip code
*	@return 	boolean
*/
function check_zip_code($zip) {
	
	if (is_null($zip) || empty($zip)) {
		return false;
	} else if (!ctype_digit($zip)) {
		return false;
	}

	if (!preg_match('/^[0-9]{5}$/', $zip)) {
		return false;
	}
	return true;
}


//one function to rule them all
function check_inputs($inputs) {
	$log = '';
	$input_results = array();
	$log_file = plugin_dir_path(__DIR__) . '/logs/pac-validator-log.txt';
	
	//$log .= 'WP SESSION IN VALIDATOR:' . $inputs['pac-donation-amount'] . PHP_EOL;
	
	//donation amount
	if (!$this->check_donation($inputs['amount'])) {
		$log .= 'donation amount not valid: ' . $inputs['amount'] . PHP_EOL;
		array_push($input_results, 'Donation amount is invalid. (ie, x,xxx.xx)');
	}
	
	//card number and type
	if (!$this->check_card_number($inputs['card_number'], $inputs['card_type'])) {
		$log .= 'card number is not valid: ' . $inputs['card_number'] . PHP_EOL;
		array_push($input_results, 'Card Number is invalid. Must match the card type provided.');
	}
	
	//card exp date
	$inputs['card_exp'] = str_replace('/', '', $inputs['card_exp']);
	if (!$this->check_exp_date($inputs['card_exp'])){
		$log .= 'pac-card-expiration is not valid: ' . $inputs['card-exp'] . PHP_EOL;		
		array_push($input_results, 'Expiration date not valid. (MMYY)');
	}

	//ccv_cvv
	if (!$this->check_card_cvv($inputs['card_type'], $inputs['cvv'])) {
		$log .= 'card ccv invalid: ' . $inputs['cvv'] . PHP_EOL;	
		array_push($input_results, 'CCV/CVV not valid. Must be 3 or 4 digits, and match the card type.');
	}
	
	//email
	 if (!$this->check_Email($inputs['email'])) {
		$log .= 'email address is not valid: ' . $inputs['email'] . PHP_EOL;
		array_push($input_results, 'Email address is not a valid format. Please use a valid email, or leave the field blank.');
	}
	
	//street address
	if (!$this->check_address($inputs['address'])) {
		$log .= 'street 1 is invalid: ' . $inputs['address'] . PHP_EOL;
		array_push($input_results, 'Street address not valid. Please ensure your street address is accurately entered.');
		
	} elseif (!empty($inputs['address2'])) {
		if (!$this->check_address($inputs['address2'])) {
			$log .= 'Street address 2 is invalid: ' . $inputs['address2'] . PHP_EOL;
			array_push($input_results, 'Street address not valid. Please ensure your street address is accurately entered.');
			}
			
	} elseif (!empty($inputs['address3'])) {
		if ($this->check_address($inputs['address3'])) {
			$log .= 'street 3 is invalid: ' . $inputs['address3'] . PHP_EOL;
			array_push($input_results, 'Street address not valid. Please ensure your street address is accurately entered.');
		}
	}
	
	//city
	if (!$this->check_city($inputs['city'])) {
		$log .= 'city is invalid: ' . $inputs['city'] . PHP_EOL;		
		array_push($input_results, 'The city provided is not valid.');
	}
	
	//zip
	if (!$this->check_zip_code($inputs['zip'])) {
		$log .= 'zip is invalid: ' . $inputs['zip'] . PHP_EOL;		
		array_push($input_results, 'The zip code provided is not valid. Please enter 5 numbers.');
	}
	
	$msg = implode('|', $input_results);
	
	//$log.= 'RETURN VALUE: ' . $msg . PHP_EOL;
	//file_put_contents($log_file, $log, FILE_APPEND);
	
	return $msg;
}
	
}//end class