<?php
	$user_id = get_current_user_id();
	
	/*----- LOAD STATES -----*/
	$state_file = plugins_url('/xml/states.xml', __DIR__);
	$xml_states = simplexml_load_file($state_file);
	$user_state = get_user_meta($user_id, 'pac_state', TRUE);
	
	$drop_down = '';
	$remaining = '';
	
	if (empty($user_state)) {
		//insert blank placeholder
		$drop_down .= '<option value="" selected="selected"></option>';
		
		foreach($xml_states->state as $state) {
			$curr_state = $state->attributes()->name;
			$drop_down .= '<option value="' . $curr_state . '">' . $curr_state . '</option>';
		}
	} else {
		//user had state stored
		foreach($xml_states->state as $state) {
			$curr_state = $state->attributes()->name;
			if (preg_match('/' . $user_state .'/', $curr_state)) {
				$drop_down .= '<option value="' . $curr_state . '" selected="selected">' . $curr_state . '</option>';
			} else {
				$remaining .= '<option value="' . $curr_state . '">' . $curr_state . '</option>';
			}
		}
		$drop_down .= $remaining;
	}
?>

<table class="form-table">
	<!-- BILLING ADDRESS -->
	<table class="form-table">
	<h3><?php echo esc_attr('Billing Address'); ?></h3>
	<tr>
		<th>
			<label for="notice"><?php _e('Notice'); ?></label>
		</th>
		<td>
			<?php _e('This information is retained for your convenience, and is NOT required. Check the box below to opt-out. If you opt-out, we will be required to collect this information again if you wish to make a donation.'); ?>
		</td>
	</tr>
	<tr>
		<th>
			<label for="pac-opt-out"><?php _e('Opt-Out'); ?></label>
		</th>
		<td>
			<input type="checkbox" name="pac-opt-out" id="pac-opt-out" value="" class="regular-text" <?php if(get_user_meta($user_id, 'pac_opt_out', TRUE)) {echo esc_attr('checked=checked');} ?> />
			<span class="description"><?php _e('&nbsp;Do not save my address'); ?></span>
		</td>
	
	</tr>
	<tr>
		<th>
			<label for="pac-street-1"><?php _e('Street Address'); ?></label>
		</th>
		<td>
			<input type="text" name="pac-street-1" id="pac-street-1" value="<?php if(get_user_meta($user_id, 'pac_street_1', TRUE)) { echo esc_attr(get_user_meta($user_id, 'pac_street_1', TRUE)); } ?>" class="regular-text" maxlength="25" />
		</td>
	</tr>
	<tr>
		<th>
			<label for="pac-street-2"></label>
		</th>
		<td>
			<input type="text" name="pac-street-2" id="pac-street-2" value="<?php if(get_user_meta($user_id, 'pac_street_2', TRUE)) { echo esc_attr(get_user_meta($user_id, 'pac_street_2', TRUE)); } ?>" class="regular-text" maxlength="25" />
		</td>
	</tr>
	<tr>
		<th>
			<label for="pac-street-3"></label>
		</th>
		<td>
			<input type="text" name="pac-street-3" id="pac-street-3" value="<?php if(get_user_meta($user_id, 'pac_street_3', TRUE)) { echo esc_attr(get_user_meta($user_id, 'pac_street_3', TRUE)); } ?>" class="regular-text" maxlength="25" />
		</td>
	</tr>
	<tr>
		<th>
			<label for="pac-city"><?php _e('City'); ?></label>
		</th>
		<td>
			<input type="text" name="pac-city" id="pac-city" value="<?php if(get_user_meta($user_id, 'pac_city', TRUE)) { echo esc_attr(get_user_meta($user_id, 'pac_city', TRUE)); } ?>" class="regular-text" maxlength="25" />
		</td>
	</tr>
	<tr>
		<th>
			<label for="pac-state"><?php _e('State'); ?></label>
		</th>
		<td>

		<select id="pac-states" name="pac-state">
		
		<?php echo $drop_down; ?>
		
		</select>
		</td>
	</tr>
	<tr>
		<th>
			<label for="pac-zip"><?php _e('Zip Code'); ?></label>
		</th>
		<td>
			<input type="text" name="pac-zip" id="pac-zip" value="<?php if(get_user_meta($user_id, 'pac_zip', TRUE)) { echo esc_attr(get_user_meta($user_id, 'pac_zip', TRUE)); } ?>" class="regular-text" maxlength="5" />
		</td>
	</tr>
	
</table>