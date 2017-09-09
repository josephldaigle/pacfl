<?php
/**
*	This class saves the donation txn to the db
*	Used by /templates/receipt-templates.php
*/

class PAC_Donation_DAO {

	/**
	*	@param		array containing values to write
	*	@return		false, or 1 if success
	*/
	
	public function save_donation($response) {
	global $wpdb;
	
		$date = current_time('mysql', false);
		$table = 'wp_pac_donations';
		$name = '';
		
		if (! empty($response['name'] ) ) {
			$name = $response['name'];
		} else {
			$name = 'Anonymous';
		}
		
		$data = array(
				'donation_date' => $date,
				'donor_name' => $response['name'],
				'donation_amount' => $response['ssl_amount'],
				'donation_campaign_id' => 0,	//IMPLEMENT LATER
				'vendor_txn_id' => $response['ssl_txn_id'],
				'card_last_4' => $response['ssl_card_number']
				);
				
		$wpdb->insert( $table, $data);

	}//end save_donation();
}//end class