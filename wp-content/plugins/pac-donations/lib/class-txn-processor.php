<?php
/**
*	Handles transaction processing for PAC Donations
*
*/
class PAC_Transaction_Processor {
	
	private $mode; 			//true for production server, false for test
	
	private $post_target;	//URL to Converge server

	private $query_const;	//txn req constant config settings
	
	private $query_fields;	//user txn inputs
	
	private $set;		//whether or not user input is provided
	
	private $log;
	
	public $debug;
	
	
	
	/**
	*	CONSTRUCTOR - sets up class members
	*/
	public function PAC_Transaction_Processor() {
		//SET UP ARRAYS
		$this->query_const = array('show_form' => 'false',
						'transaction_type' => 'ccsale',
						'card_present' => 'N',
						'cvv_indicator' => 1,
						'result_format' => 'ASCII',
						'error_url' => '',
						'receipt_link_method' => 'REDG',
						'receipt_link_url' => ''
						);
		$this->query_fields = array('amount' => '',
						'card_number' => '',
						'card_exp' => '',
						'cvv' => '',
						'address' => '',
						'zip'=> ''
						);
		
		//SET QUERY CONST URLS
		$this->query_const['receipt_link_url'] = plugins_url('/includes/txn-response.php', __DIR__);
		$this->query_const['error_url'] = home_url('/error/');
		
		$this->mode = false;
		
		//SET TARGET URL FOR REQUEST
		$this->set_request_target();
		
		$this->log = '';
		
	}
	
	/**
	*	sets the mode, true for production, false for test
	*/	
	public function set_mode($mode) {
		if(!is_null($mode) && !empty($mode) && is_bool($mode)) {
			$this->mode = $mode;
			$this->set_request_target();
		}
	}
	
	/**
	*	returns $mode
	*/
	public function get_mode() {
		if(isset($this->mode)) {
			return $this->mode;
		} else {
			return false;
		}
	}
	
	/**
	*	sets up user inputs - does not validate
	*/
	public function set_user_inputs($user_inputs) {
		foreach($user_inputs as $key => $val) {
			if (array_key_exists($key, $this->query_fields)) {
					$this->query_fields[$key] = $val;
				}
			}
		$set = true;
		return $user_inputs;
	}
	
	/**
	*	creates and sends the request to converge, and returns the response
	*/
	public function send_request() {
		//-----SET UP cURL REQUEST
		$this->log .= '------ BUILD POST REQUEST' . PHP_EOL;
		$query_string = $this->prepare_txn_query();
		
		$ch = curl_init();
		$this->log .= 'POST TARGET: ' . $this->post_target . PHP_EOL;
		curl_setopt($ch, CURLOPT_URL, $this->post_target);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		
		$request = curl_exec($ch);

		curl_close($ch);
		
		$this->log.= '-----SENT REQUEST' . PHP_EOL . $query_string . PHP_EOL;
		$this->log.= '----- REQUEST RESULT' . PHP_EOL . $request . PHP_EOL;
		
		return $request;
		}
	
	/**
	*	returns $query_fields
	*/
	public function get_query_fields() {
		return $this->query_fields;
	}
	
	/**
	*	returns $query_fields
	*/
	public function get_query_const() {
		return $this->query_const;
	}
	
	/**
	*	debug switch
	*/
	public function debug_on() {
		$this->debug = true;
	}
	
	/**
	*	print logs
	*/
	public function print_log() {
		/* $path = plugin_dir_path(__DIR__) . '/logs/class-txn-processor.txt';
		file_put_contents($path, $this->log, FILE_APPEND); */
	}
	
	/**
	*	returns the txn request as string
	*/
	protected function prepare_txn_query() {
		$creds = $this->fetch_credentials();
		
		$query_string = //"&ssl_test_mode=true".
			"&ssl_merchant_id=$creds->account_number".
			"&ssl_user_id=$creds->user_id".
			"&ssl_pin=$creds->pin".
			"&ssl_show_form={$this->query_const['show_form']}".
			
			"&ssl_transaction_type={$this->query_const['transaction_type']}".
			"&ssl_card_present={$this->query_const['card_present']}".
			"&ssl_cvv2ccv2_indicator={$this->query_const['cvv_indicator']}".

			"&ssl_result_format={$this->query_const['result_format']}".

			"&ssl_error_url={$this->query_const['error_url']}".
			"&ssl_receipt_link_method={$this->query_const['receipt_link_method']}".
			"&ssl_receipt_link_url={$this->query_const['receipt_link_url']}".
			
			"&ssl_amount={$this->query_fields['amount']}".
			"&ssl_card_number={$this->query_fields['card_number']}".
			"&ssl_exp_date={$this->query_fields['card_exp']}".
			"&ssl_cvv2cvc2={$this->query_fields['cvv']}".
			
			"&ssl_avs_address={$this->query_fields['address']}".
			"&ssl_avs_zip={$this->query_fields['zip']}";
			
			return $query_string;
	}//end prepare_txn()
	
	/**
	*	returns xml object containing credentials relative to $mode
	*/
	protected function fetch_credentials() {
		//SET POST TARGETS
		if ($this->mode) {
			$post_cred_file = plugins_url('xml/prod.xml', __DIR__);
			//$this->log.= '------ LOADING PROD CREDENTIALS' . PHP_EOL;
		} else {	
			$post_cred_file = plugins_url('xml/udf.xml', __DIR__);
			//$this->log.= '------ LOADING TEST CREDENTIALS' .PHP_EOL;
		}
		
		// GET ADMIN CREDENTIALS
		try {
			$creds = simplexml_load_file($post_cred_file);
			//$this->log .= '----- ADMIN CREDENTIALS OBTAINED' . PHP_EOL;
			return $creds;
		} catch (Exception $e) {
			//$this->log.= 'Caught exception: TRANSACTION PROCESSOR : ' . $e->getMessage() . PHP_EOL;
		}
	}
	
	/**
	*	sets $post_target to prod or test url relative to $mode
	*/
	protected function set_request_target() {
		if ($this->get_mode()) {
			$this->post_target = 'https://www.myvirtualmerchant.com/VirtualMerchant/process.do';
			//$this->log.= '------ EXECUTING LIVE SCRIPT' . PHP_EOL;
		} else {
			$this->post_target = 'https://demo.myvirtualmerchant.com/VirtualMerchantDemo/process.do';
			//$this->log.= '------ EXECUTING TEST SCRIPT' .PHP_EOL;
		}
	}

		
}//end class
