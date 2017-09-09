<?php
/*
Plugin Name: cs-backtop
Plugin URI: http://codesalsa.net/projects/codesalsa-back-top/
Description: BackTop is a lightweight wordpress plugin to integrate a customizable Back to Top feature to any website with ease.
Version: 1.0
Author: Code Salsa
Author URI: http://codesalsa.net
License: MTI
*/


// Add the stylesheet to the header

function cs_backTop_style(){
	wp_enqueue_style( 'backTopStyles', plugins_url('/css/backTop.css', __FILE__), false);
}

add_action('wp_enqueue_scripts', 'cs_backTop_style');

function cs_insert_backTop() {
	
?>
	<a id='backTop'>Back To Top</a>
	<script>
	jQuery(document).ready( function() {
		jQuery('#backTop').backTop({
			'position' : <?php if(get_option('cs_backtop_position')): echo get_option('cs_backtop_position'); else: echo "400"; endif; ?>,
			'speed' : <?php if(get_option('cs_backtop_speed')): echo get_option('cs_backtop_speed'); else: echo "1000"; endif; ?>,
			'color' : '<?php if(get_option('cs_backtop_style')): echo get_option('cs_backtop_style'); else: echo "black"; endif; ?>',
			'size' : '<?php if(get_option('cs_backtop_size')): echo get_option('cs_backtop_size'); else: echo "small"; endif; ?>',
		});
	});
	</script>
<?php
}

add_action('wp_footer', 'cs_insert_backTop');

function cs_backTop_jquery() {
    wp_enqueue_script( 'jquery' );
}    

add_action('wp_enqueue_scripts', 'cs_backTop_jquery');

// Add the scripts to the footer
function cs_backTop_mainjs(){
	wp_register_script( 'backTopScripts', plugins_url('/js/jquery.backTop.min.js', __FILE__), array('jquery'), '2.1.4' );
	wp_enqueue_script( 'backTopScripts' );
}
// Action our scripts
add_action( 'wp_enqueue_scripts', 'cs_backTop_mainjs' );

// Add a setting link to plugin list page
add_filter('plugin_action_links', 'cs_bacTop_add_settings_link', 10, 2);
function cs_bacTop_add_settings_link($links, $file) {
	static $this_plugin;
	if (!$this_plugin) {
	$this_plugin = plugin_basename(__FILE__);
	}
	if ($file == $this_plugin) {
		$settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=cs_bacTop_settings">Settings</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}

// Add admin menu

add_action( 'admin_menu', 'register_cs_bacTop_custom_menu_page' );
function register_cs_bacTop_custom_menu_page(){
	add_menu_page( 'Code Salsa - Back Top Settings', 'CS-BackTop', 'manage_options', 'cs_bacTop_settings', 'cs_bacTop_admin_panel_view', plugins_url( 'cs-backtop/img/logomenu.png' ));
	add_action( 'admin_init', 'update_extra_post_info' );
}


function cs_bacTop_admin_panel_view(){
?>

<div class="wrap">
	<div class="tpq_section1">
		<h2>Code Salsa - Backtop Settings</h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'cs_bacTop_options' ); ?>
			<?php do_settings_sections( 'cs_bacTop_options' ); ?>
			<table class="cs_backtop_admin_frm_table">
				<tr valign="top">
					<th style="width:170px; text-align:left;" scope="row">Scroll Position from Top</th>
					<td><input type="text" name="cs_backtop_position" value="<?php if(get_option('cs_backtop_position')){ echo get_option('cs_backtop_position'); } else { echo "400"; } ?>" /></td>
				</tr>
				<tr valign="top">
					<th style="width:170px; text-align:left;" scope="row">Icon Appearance Speed</th>
					<td><input type="text" name="cs_backtop_speed" value="<?php echo get_option('cs_backtop_speed'); ?>" /></td>
				</tr>
				<tr valign="top">
					<th style="width:170px; text-align:left;" scope="row">Icon Color Style</th>
					<th><select name="cs_backtop_style" id="cs_backtop_style" style="width:100%;">
							<option value="black" <?php echo (get_option('cs_backtop_style') == 'black')? 'selected="selected"' : '';?>>Black</option>
							<option value="white" <?php echo (get_option('cs_backtop_style') == 'white')? 'selected="selected"' : '';?>>White</option>
							<option value="red" <?php echo (get_option('cs_backtop_style') == 'red')? 'selected="selected"' : '';?>>Red</option>
							<option value="green" <?php echo (get_option('cs_backtop_style') == 'green')? 'selected="selected"' : '';?>>Green</option>
						</select>
					</th>
				</tr>
				<tr valign="top">
					<th style="width:170px; text-align:left;" scope="row">Icon Size</th>
					<th><select name="cs_backtop_size" id="cs_backtop_size" style="width:100%;">
							<option value="small" <?php echo (get_option('cs_backtop_size') == 'small')? 'selected="selected"' : '';?>>Small</option>
							<option value="medium" <?php echo (get_option('cs_backtop_size') == 'medium')? 'selected="selected"' : '';?>>Medium</option>
							<option value="large" <?php echo (get_option('cs_backtop_size') == 'large')? 'selected="selected"' : '';?>>Large</option>
						</select>
					</th>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
</div>

<?php }

if( !function_exists("update_extra_post_info") ) {
	function update_extra_post_info() {
		register_setting( 'cs_bacTop_options', 'cs_backtop_position' );
		register_setting( 'cs_bacTop_options', 'cs_backtop_speed' );
		register_setting( 'cs_bacTop_options', 'cs_backtop_style' );
		register_setting( 'cs_bacTop_options', 'cs_backtop_size' );
	}
}

?>