<?php

	global $wp_session;
	//var_dump($wp_session);
	$debug = false;
	$txn_data = array();
	$name = '';
	
	//SETUP LOG
	/* $log_file = plugin_dir_path(__DIR__) . '/logs/receipt-log.txt';
	$log = 'START LOGGING' . PHP_EOL; */
	
	//LOAD FORM OUTPUT VALUES
	$pac_rec_head = array(
					'title' 	=> get_bloginfo('name', 'display'),
					'address1' 	=> 'P.O. Box 363',
					'address2'	=> 'High Springs, FL 32643');
	
	
	//GET STATE ABBREVIATION
	$drop_down = new PAC_Dropdown();
	$state = $drop_down->get_state_abbr($wp_session['state']);
		
	$csz =  $wp_session['city'] . ', ' . $state . ' ' . $wp_session['zip'];

	//GET DONOR NAME
	if (! empty($wp_session['donor_name'] ) ) {
		$name = $wp_session['donor_name'];
	} else {
		$name = 'Anonymous';
	}
	
	//WRITE TO DB
	/* $dao_path = plugin_dir_path(__DIR__) . '/includes/class-donation-dao.php';
	require_once($dao_path); */
	
	$donationDAO = new PAC_Donation_DAO();
	$txn_data['name'] = $name;
	$txn_data['ssl_amount'] = $wp_session['ssl_amount'];
	$txn_data['ssl_txn_id'] = $wp_session['ssl_txn_id'];
	$txn_data['ssl_card_number'] = $wp_session['ssl_card_number'];
	
	try {
		$donationDAO->save_donation($txn_data);
	} catch (Exception $e) {
		$log .= $e->message();
	}
	
	//DEBUG OUTPUT
	if ($debug) 
	{
		$log.= 'end of file' . PHP_EOL . PHP_EOL;
		file_put_contents($log_file, $log, FILE_APPEND);
	}
?>	
<div id="pac-receipt">
<div>
	<h3 id="txn-result"><?php _e('Transaction Approved!'); ?></h3>
</div>
<p>Please print this receipt for your records.</p>
		<table class="form-table">
			<tr>
				<td>
					<span><h5><a href="<?php echo home_url(); ?>"><?php echo $pac_rec_head['title']; ?></a></h5></span>
					<span><?php echo $pac_rec_head['address1']; ?></span>
					<br />
					<span><?php echo $pac_rec_head['address2']; ?></span>
				</td>
			</tr>
		</table>
		
		<table>
			<tr>
				<td>
					<?php echo date('F j, Y'); ?>
				</td>
			</tr>
		</table>
			
		<table class="form-table">
		<h5><?php _e('Summary'); ?></h5>
			<tr>
				<th><?php _e('Description'); ?></th>
				<th><?php _e('Type'); ?></th>
				<th><?php _e('Amount'); ?></th>
				<th><?php _e('Card Number'); ?></th>
			</tr>
			<tr>
				<td><?php _e('Charitable donation'); ?></td>
				<td><?php _e('Credit Card'); ?></td>
				<td><?php echo '$' . $wp_session['ssl_amount']; ?></td>
				<td><?php echo $wp_session['ssl_card_number']; ?></td>
			</tr>
		</table>
			
		<table class="form-table">
		<h5><?php _e('Donor Information'); ?></h5>
			<tr>
				<th><?php _e('Name'); ?></th>
				<th><?php _e('Address'); ?></th>
			</tr>
			<tr>
				<td><?php echo $name; ?></td>
				<td>
					<span>
						<?php echo $wp_session['address']; ?>
					</span>
					<br />
					<span>
						<?php echo $csz; ?>
					</span>
				</td>
			</tr>
		</table>
	</div>
<?php $wp_session->reset();	?>