<?php
/**	Plugin Name: PAC Donations
*	Plugin URI: http://www.pacfl.org
*	Description: This plugin creates a donation form, and integrates the PAC website with Converge.
*	Version: 1.0 
*	Author: Joe Daigle 
*	License: GPL
*
*		Notes:
*		1) See bottom of this file for license details
*		2) This plugin is not internationalized
*/

/**
*	This function executes when the 'activate' button is selected in the admin "plugins" area.
*/
register_activation_hook( __FILE__, 'paci_install' ); 
function paci_install() {
	//check wp version
	global $wp_version; 
	if ( version_compare( $wp_version, '3.5', '<' ) ) { 
		wp_die( 'This plugin requires WordPress version 3.5 or higher.' );
	}//end if
	
	//install db tables
	paci_init_db();
	
}//end paci_install


/**
*	This function executes when the 'de-activate' button is selected in the admin "plugins" area.
*/
register_deactivation_hook( __FILE__, 'paci_deactivate()' );

function paci_deactivate() {
	return true;
}


//---------- FUNCTIONS ---------------//

/**
*	This function includes css stylesheets for the donation form.
*/
function register_pac_scripts() {
	wp_register_style('pac-donation-main', plugins_url( 'pac-donations/css/pac-donation-style.css' ));
	wp_enqueue_style( 'pac-donation-main' );
	/* wp_register_style('pac-receipt', plugins_url( 'pac-donation/css/receipt-print.css' ));
	wp_enqueue_style( 'pac-receipt' ); */
}
add_action('wp_enqueue_scripts', 'register_pac_scripts');

/**
*	This function creates the donations table.
*	called from when plugin activated
*/
function paci_init_db(){
   global $wpdb;
	$table_prefix = 'wp_';
	$donations_table = $table_prefix . "pac_donations";
	$charset_collate = $wpdb->get_charset_collate();
	$sql = '';
	
	//create donations table
	if ($wpdb->get_var("SHOW TABLES LIKE '$donations_table'") != $donations_table) {
			$sql .= "CREATE TABLE $donations_table (
			  donation_id bigint NOT NULL AUTO_INCREMENT,
			  donation_date datetime NOT NULL,
			  donor_name varchar(30) NOT NULL,
			  donation_amount decimal(30,2) NOT NULL,
			  donation_campaign_id bigint(20) NOT NULL,
			  vendor_txn_id varchar(50) NOT NULL,
			  card_last_4 varchar(20) NOT NULL,
			  UNIQUE KEY (donation_id)
			);$carset_collate;";
	}//endif
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	
}//end paci_init_db


/*----- USER PROFILE -----*/

function pac_custom_user_profile_fields($user) {
	load_template(__DIR__ . '/templates/pac-user-profile.php');
}
add_action('show_user_profile', 'pac_custom_user_profile_fields');
add_action('edit_user_profile', 'pac_custom_user_profile_fields');

/**
*	This function saves the new profile fields.
*/
function pac_save_profile_fields( $user_id ) {

	if (!(current_user_can( 'edit_user', $user_id ) ))
		return false;
	
	//UPDATE USER META
	if ( isset($_POST['pac-opt-out']))
	{
		update_usermeta( $user_id, 'pac_opt_out', 'checked');

		delete_usermeta( $user_id, 'pac_street_1');
		delete_usermeta( $user_id, 'pac_street_2');
		delete_usermeta( $user_id, 'pac_street_3');
		delete_usermeta( $user_id, 'pac_city');
		delete_usermeta( $user_id, 'pac_state');
		delete_usermeta( $user_id, 'pac_zip');
		
	} else {
		
		update_usermeta( $user_id, 'pac_street_1', $_POST['pac-street-1'] );
		update_usermeta( $user_id, 'pac_street_2', $_POST['pac-street-2'] );
		update_usermeta( $user_id, 'pac_street_3', $_POST['pac-street-3'] );
		update_usermeta( $user_id, 'pac_city', $_POST['pac-city'] );
		update_usermeta( $user_id, 'pac_state', $_POST['pac-state'] );
		update_usermeta( $user_id, 'pac_zip', $_POST['pac-zip'] );
	}
	
}
add_action( 'personal_options_update', 'pac_save_profile_fields' );
add_action( 'edit_user_profile_update', 'pac_save_profile_fields' );

/**
*	include classes
*/
function pac_classes() {
	if ( ! class_exists( 'PAC_Transaction_Processor' ) ) {
	require_once( plugin_dir_path(__FILE__) . '/lib/class-txn-processor.php' );
	}
	
	if ( ! class_exists( 'PAC_Dropdown' ) ) {
	require_once(  plugin_dir_path(__FILE__) .'/lib/class-pac-dropdown.php' );
	}
	if ( ! class_exists( 'PAC_Donation_DAO' ) ) {
	require_once( plugin_dir_path(__FILE__) . '/lib/class-donation-dao.php' );
	}
	
	if ( ! class_exists( 'PAC_Form_Validator' ) ) {
	require_once( plugin_dir_path(__FILE__) . '/lib/class-validator.php' );
	}
	
	if ( ! class_exists( 'PAC_CC_Validator' ) ) {
	require_once( plugin_dir_path(__FILE__) . '/lib/class-cc-validator.php' );
	}
	
	if ( !defined( 'PAC_PLUGIN_DIR' ) ) {
	define( 'PAC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	}
}
add_action('init', 'pac_classes');

/**
*	Start session on site load
*/
function pac_start_session() {
	global $wp_session;
	$wp_session = WP_Session::get_instance();
}
add_action('init', 'pac_start_session');

/**
*	Set session expiration timer
*/
add_filter( 'wp_session_expiration', 'pac_session_exp');
function pac_session_exp() { 
		return 60;
}

/**
*	Resets current session after user txn complete
*/
add_action('template_redirect', 'pac_receipt_session_flush');
function pac_receipt_session_flush() {
	global $wp_session;
	
	//FLUSH SESSION
	if (preg_match('/receipt/', wp_get_referer())) {
		$wp_session->reset();
	}

}

//SHORTCODES
/*
*	Allows insertion of receipt template into a page.
*/
function pac_get_receipt_template() {
	load_template(__DIR__ . '/templates/pac-receipt-template.php');
}
add_shortcode('pac-receipt', 'pac_get_receipt_template');

/*
*	Allows insertion of error template into a page.
*/
function pac_get_error_template() {
	load_template(__DIR__ . '/templates/pac-error-template.php');
}
add_shortcode('pac-error', 'pac_get_error_template');

/**
*	Add donation template to donations page
*/
function pac_get_donation_template() {
	load_template(__DIR__ . '/templates/pac-donation-form-template.php');
}
add_shortcode('pac-donation', 'pac_get_donation_template');


/* Copyright 2015 Joseph Daigle (email: josephldaigle@yahoo.com) This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version. This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA */
?>