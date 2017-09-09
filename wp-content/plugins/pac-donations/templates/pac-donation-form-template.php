<?php
global $wp_session;
//var_dump($wp_session);
$msg_output ='';
	if (!empty($wp_session['ssl_result_message'])) {
		//PROCESS TXN RESPONSE
		$msg_output = "We're sorry, but it appears we weren't able to approve your donation. " .
					"Please feel free to check the information you submitted and attempt the donation again. " . 
					"If you feel you have received this message in error, please contact your card issuer.";
	}

	if (!empty($wp_session['inputs_msg'])) {
		//PROCESS INPUT VALIDATION RESPONSE
		$msg_bag = explode('|', $wp_session['inputs_msg']);
		
		foreach($msg_bag as $msg){$msg_output .=  '<li>' . $msg . '</li>';}
		unset($wp_session['inputs_msg']);
	}
?>
<!-- DONATION FORM -->
<form id="pac-donation-form" action="<?php echo home_url('/processor/'); ?>" method="post">
	<!-- include nonce field for security -->
	<?php wp_nonce_field( 'pac_submit_form', 'pac_nonce_field' ); ?>
	<div id="pac-form-warn-div" style="<?php if(empty($msg_output)){echo 'display:none !important;';} ?>">
		<ul>
			<?php echo $msg_output; ?> 
		</ul>
	</div>
	<!-- NEW COL -->
	<div class="pac-col-wrap">

		<!-- DONATION -->
		<table class="form-table">
			<h3><?php _e('Donation'); ?></h3>
			<tr>
				<th>
					<label for="amount"><?php _e('Amount'); ?></label>
				</th>
				<td>
					<input type="text" name="amount" value="<?php echo $wp_session['amount']; ?>" required="required" />
				</td>
			</tr>
		</table>
		
		
		<!-- CARD INFORMATON -->
		<table class="form-table">
			<h3><?php echo _e('Card'); ?></h3>
			<tr>
				<!-- CARD LOGOS -->
				<th colspan="2">
					<img class="pac-card-images" alt="visa-logo" title="" src="<?php echo(plugins_url('pac-donations') . '/img/logo-visa.png'); ?>" />
					<img class="pac-card-images" alt="visa-logo" title="" src="<?php echo(plugins_url('pac-donations') . '/img/logo-master.png'); ?>" />
					<img class="pac-card-images" alt="visa-logo" title="" src="<?php echo(plugins_url('pac-donations') . '/img/logo-discover.png'); ?>" />
					<img class="pac-card-images" alt="visa-logo" title="" src="<?php echo(plugins_url('pac-donations') . '/img/logo-american.png'); ?>" />
				</th>		
			</tr>
		    
			<!-- CC CARD RADIOS -->
			<tr>
				<th colspan="2">
					<div id="pac-card-radios-cont" class="pac-form-inputs">
						<div><input type="radio" name="card_type" value="visa" <?php if($wp_session['card_type'] == 'visa') {echo 'checked="checked"';} ?> required="required"></div>
						<div><input type="radio" name="card_type" value="master" <?php if($wp_session['card_type'] == 'master') {echo 'checked="checked"';} ?> ></div>
						<div><input type="radio" name="card_type" value="discover" <?php if($wp_session['card_type'] == 'discover') {echo 'checked="checked"';} ?> ></div>
						<div><input type="radio" name="card_type" value="american" <?php if($wp_session['card_type'] == 'american') {echo 'checked="checked"';} ?> ></div>
					</div>
				</th>
			</tr>
			
			<!-- CARD DETAILS -->
			<tr>
				<th>
					<label for="donor_name">
					<span><?php _e('Name'); ?></span><br />
					</label>
				</th>
				<td>
					<input name="donor_name" type="text" value="<?php echo $wp_session['donor_name']; ?>" maxlength="30" />
				</td>
			</tr>
			<tr>
				<th>
					<label for="card_number"><?php _e('Card Number'); ?></label>
				</th>
				<td>
					<input name="card_number" type="text" value="<?php echo $wp_session['card_number']; ?>" maxlength="16" required="required" />
				</td>
			</tr>
			<tr>
				<th>
					<label for="card_exp">
					<span><?php _e('Expiration Date'); ?></span><br />
					<span><?php _e('(MMYY)'); ?></span>
					</label>
				</th>
				<td>
					<input name="card_exp" type="date" value="<?php echo $wp_session['card_exp']; ?>" maxlength="4" required="required" />
				</td>
			</tr>
			<tr>
				<th>
					<label for="cvv"><?php _e('CCV / CVV'); ?></label>
				</th>
				<td>
					<input name="cvv" type="text" value="<?php echo $wp_session['cvv']; ?>" maxlength="4" required="required" />
				</td>
			</tr>
		
		</table>
		
	</div> <!-- col wrap -->
	
	
	<!-- NEW COL -->
	<div class="pac-col-wrap" id="pac-col-2">
		
		<!-- BILLING ADDRESS -->
		<table class="form-table">
		<h3><?php echo esc_attr('Billing'); ?></h3>
		<tr>
			<th>
				<label for="email"><?php _e('Email Address'); ?></label>
			</th>
			<td>
				<input type="text" name="email" value="<?php echo $wp_session['email']; ?>" maxlength="30" />
			</td>
		</tr>
		<tr>
			<th>
				<label for="address"><?php _e('Street Address'); ?></label>
			</th>
			<td>
				<input type="text" name="address" id="address" value="<?php echo $wp_session['address']; ?>" class="regular-text"  maxlength="25" required="required"/>
			</td>
		</tr>
		<tr>
			<th>
				<label for="address"></label>
			</th>
			<td>
				<input type="text" name="address2" id="address2" value="<?php echo $wp_session['address2']; ?>" class="regular-text" maxlength="25" />
			</td>
		</tr>
		<tr>
			<th>
				<label for="address3"></label>
			</th>
			<td>
				<input type="text" name="address3" id="address3" value="<?php echo $wp_session['address3']; ?>" class="regular-text" maxlength="25" />
			</td>
		</tr>
		<tr>
			<th>
				<label for="city"><?php _e('City'); ?></label>
			</th>
			<td>
				<input type="text" name="city" id="city" value="<?php echo $wp_session['city']; ?>" class="regular-text"  maxlength="25" required="required"/>
			</td>
		</tr>
		<tr>
			<th>
				<label for="state"><?php _e('State'); ?></label>
			</th>
			<td>
	
			<select id="state" name="state" required="required">
				<?php 	$drop_down = new PAC_Dropdown();
						$states = $drop_down->get_dropdown($wp_session['state']);
						echo $states;
				?>
			</select>
			</td>
		</tr>
		
		<tr>
			<th>
				<label for="zip"><?php _e('Zip Code'); ?></label>
			</th>
			<td>
				<input type="text" name="zip" id="zip" value="<?php echo $wp_session['zip']; ?>" class="regular-text" maxlength="5" required="required"/>
			</td>
		</tr>
		
		</table>
		
		
		<script src="<?php echo(plugins_url() . '/pac-donations/js/recaptcha.js'); ?>"></script>
		<div id="pac-don-recatpcha" class="g-recaptcha" data-sitekey="6Lew7AkTAAAAACXHRdYefkYekTtrgH6m6xHyufxW" data-callback="recaptchaCallback"></div>
		
		<!-- BUTTONS -->
		<div id="pac-form-buttons">
			<input type="submit" value="Submit" id="submit" name="submit" disabled />
			<input type="reset" value="Clear" onclick="return resetForm();" />
		</div>
		
	</div> <!-- col wrap -->
</form>
<?php $wp_session->reset(); ?>