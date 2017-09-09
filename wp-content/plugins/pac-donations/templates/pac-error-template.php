<?php
$debug = true;
global $wp_session;
if ($debug) {
	$log = '';
	//$log .= date_default_timezone_get() . PHP_EOL;
	$log_file = plugin_dir_path(__FILE__) .'error-template-log.txt';
	}
	
	//catch error code from converge
	if(isset($_GET['errorCode'])) {
		$log .= "====================";
		foreach($_GET as $key => $val) {
			$log .= $key . '= ' . $val . PHP_EOL;
		}
	}
?>
<div id="pac-receipt">
	<div>
		<h3 id="txn-result"><?php _e('ERROR - unable to complete transaction'); ?></h3>
	</div>
	<p>Unfortunately, there seems to be a problem with our donation processing system. Rest assured we are working to resolve the problem In the mean-time, please feel free to <a href="<?php echo home_url('/contact/'); ?>">Contact Us</a> and inquire about resolution.</p>
	<?php if ($debug) {file_put_contents($log_file, $log, FILE_APPEND);} ?>
	<?php $wp_session->reset();	?>