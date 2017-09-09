<?php
/**
 * Plugin Name: Floating Social Buttons
 * Plugin URI: http://www.click2check.net/wordpress-plugins/floating-social-buttons
 * Description: Floating Social Buttons Provide an easy way to add floating social sharing button.
 * Version: 1.5
 * Author: Bhagirath
 * Author URI: http://click2check.net
 * License: GPL2
 */

add_action('admin_menu', 'floating_social_buttons_menu');
add_action('admin_enqueue_scripts', 'floating_social_buttons_admin_enqueue_scripts');

function floating_social_buttons_admin_enqueue_scripts($current_page = '')
{
		if (strpos($current_page, 'floating-social-buttons') === false) {
			return ;
		}
		
		

		wp_register_style('floating_social_buttons', plugins_url("/css/main.css", __FILE__), false,
				filemtime( plugin_dir_path( __FILE__ ) . "/css/main.css" ) );
				
		wp_enqueue_style('floating_social_buttons');

		wp_register_style('floating_social_buttons_bootstrap', plugins_url("/css/bootstrap.min.css", __FILE__), false,
				filemtime( plugin_dir_path( __FILE__ ) . "/css/bootstrap.min.css" ) );
				
		wp_enqueue_style('floating_social_buttons_bootstrap');
		
		wp_enqueue_script( 'jquery' );
		wp_register_script( 'floating_social_buttons', plugins_url("/js/main.js", __FILE__), array('jquery', ),
				filemtime( plugin_dir_path( __FILE__ ) . "/js/main.js" ), true);
		wp_enqueue_script( 'floating_social_buttons' );
		
		wp_register_script( 'floating_social_buttons_bootstrap', plugins_url("/js/bootstrap.min.js", __FILE__), array('jquery', ),
				filemtime( plugin_dir_path( __FILE__ ) . "/js/bootstrap.min.js" ), true);
		wp_enqueue_script( 'floating_social_buttons_bootstrap' );
}



function floating_social_buttons_menu() {
	// Add the new admin menu and page and save the returned hook suffix
	$hook_suffix = add_options_page('Floating Social Buttons Options', 'Floating Social Buttons', 'manage_options', 'floating-social-buttons', 'floating_social_buttons_option');
	add_action( 'admin_init', 'register_social_button_setting' );
	// Use the hook suffix to compose the hook and register an action executed when plugin's options page is loaded
	//add_action( 'load-' . $hook_suffix , 'my_load_function' );
}

function floating_social_setting_link($links) { 
  
  $settings_link = '<a href="options-general.php?page=floating-social-buttons.php">Settings</a>'; 
  array_unshift($links, $settings_link); 
  $donate_link = '<a href="http://www.click2check.net">Donate</a>'; 
  array_unshift($links, $donate_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'floating_social_setting_link' );


add_action('wp_footer', 'add_social_button');

function register_social_button_setting()
{
	if ( get_option( 'selected_button' ) === false ) {
		add_option( 'selected_button', 'facebook,tweet,gplus,digg,linkedin' );
	}
	if (get_option( 'floating_social_button_float' ) === false ) {
		add_option( 'floating_social_button_float', 'left' );
	}
	if (get_option( 'floating_social_button_position_top' ) === false ) {
		add_option( 'floating_social_button_position_top', '20' );
	}
	if (get_option( 'floating_social_button_position_left' ) === false ) {
		add_option( 'floating_social_button_position_left', '20' );
	}
	if (get_option( 'floating_social_button_disable_mobile' ) === false ) {
		add_option( 'floating_social_button_disable_mobile', '1' );
	}
	
}


function floating_social_buttons_get_plugin_data() {
    // pull only these vars
    $default_headers = array(
        'Name' => 'Plugin Name',
        'PluginURI' => 'Plugin URI',
        'Description' => 'Description',
    );

    $plugin_data = get_file_data(__FILE__, $default_headers, 'plugin');

    $url = $plugin_data['PluginURI'];
    $name = $plugin_data['Name'];

    $data['name'] = $name;
    $data['url'] = $url;

    $data = array_merge($data, $plugin_data);

    return $data;
}



function add_social_button() {
$buttons = explode (',',get_option( 'selected_button' ));
$float = get_option('floating_social_button_float');
$position_top =  get_option('floating_social_button_position_top');
$position_left =  get_option('floating_social_button_position_left');
$disable = get_option('floating_social_button_disable_mobile');
if($disable == 1 && wp_is_mobile())
{
return;
}
else if($disable == 1)
{
echo '<style>
@media (max-width : 640px) {
    #pageshare
	{
	display:none !important;
	}
}
</style>
';
}
//print_r($buttons);

    echo "<!--SideBar Floating Share Buttons Code Start-->

<style>

#pageshare {position:fixed; top:".$position_top."%; ".$float.":".$position_left."px; float:left; border: 1px solid black; border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;background-color:#eff3fa;padding:0 0 2px

0;z-index:10;}

#pageshare .sbutton {float:left;clear:both;margin:5px 5px 0 5px;}

.fb_share_count_top {width:48px !important;}

.fb_share_count_top, .fb_share_count_inner {-moz-border-radius:3px;-webkit-border-radius:3px;}

.FBConnectButton_Small, .FBConnectButton_RTL_Small {width:49px !important; -moz-border-radius:3px;-webkit-border-radius:3px;}

.FBConnectButton_Small .FBConnectButton_Text {padding:2px 2px 3px !important;-moz-border-radius:3px;-webkit-border-radius:3px;font-size:8px;}

</style>";

echo "<div id='pageshare' title='Share This With Your Friends'>";


if(in_array('facebook',$buttons))
{
echo "<div class='sbutton' id='gb'><script src='http://connect.facebook.net/en_US/all.js#xfbml=1'></script><fb:like layout='box_count' show_faces='false' font=''></fb:like></div>";
}
if(in_array('facebook_share',$buttons))
{
echo "<div class='sbutton' id='fbcount'><div id='fb-root'></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = '//connect.facebook.net/en_US/all.js#xfbml=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script><div class='fb-share-button' data-type='box_count'></div></div>";
}

if(in_array('tweet',$buttons))
{
echo "<div class='sbutton' id='rt'><a href='http://twitter.com/share' class='twitter-share-button' data-count='vertical' >Tweet</a><script src='http://platform.twitter.com/widgets.js' type='text/javascript'></script></div>";
}

if(in_array('gplus',$buttons))
{
echo "<div class='sbutton' style='margin-left:5px;' id='gplusone'><script type='text/javascript' src='https://apis.google.com/js/plusone.js'></script><g:plusone size='tall'></g:plusone></div>";
}

if(in_array('linkedin',$buttons))
{
echo "<div class='sbutton' id='linkedin' style='margin-left:3px;width:48px'><script src='//platform.linkedin.com/in.js' type='text/javascript'>
 lang: en_US
</script>
<script type='IN/Share' data-counter='top'></script></div>";
}

if(in_array('digg',$buttons))
{
echo "<div class='sbutton' id='digg' style='margin-left:7px;width:48px'><script src='http://widgets.digg.com/buttons.js' type='text/javascript'></script><a class='DiggThisButton DiggMedium'></a></div>";
}

if(in_array('stumbleupon',$buttons))
{
echo "<div class='sbutton' id='digg' style='margin-left:7px;width:48px'><!-- Place this tag where you want the su badge to render -->
<su:badge layout='5'></su:badge>

<!-- Place this snippet wherever appropriate -->
<script type='text/javascript'>
  (function() {
    var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;
    li.src = ('https:' == document.location.protocol ? 'https:' : 'http:') + '//platform.stumbleupon.com/1/widgets.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);
  })();
</script></div>
";
}

if(in_array('pinterest',$buttons))
{
echo "<div class='sbutton' id='pinterest' style='margin-left:10px;width:48px;margin-top:40px;'>
 <a target='_blank' href='//www.pinterest.com/pin/create/button/?url=".get_permalink()."&media=".wp_get_attachment_url(get_the_post_thumbnail())."&description=".get_bloginfo()."' data-pin-do='buttonPin' data-pin-config='above'><img src='//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png' /></a>
<!-- Please call pinit.js only once per page --></div>";

}

echo "<br/><div style='clear: both;font-size: 9px;text-align:center;'></div></div>

<!--SideBar Floating Share Buttons Code End-->
";
}



function floating_social_buttons_option() {
	
$message ="";
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

if(isset($_REQUEST['social_buttons_hidden']))
{
	
	if(isset($_REQUEST['socialbuttons']))
	{
		if($_REQUEST['socialbuttons'] != '')
		{
			$buttons  = implode(',',$_REQUEST['socialbuttons']);
			update_option('selected_button',$buttons);
		}
		update_option('floating_social_button_float',$_REQUEST['floating']);
		update_option('floating_social_button_position_top',$_REQUEST['position-top']);
		update_option('floating_social_button_position_left',$_REQUEST['position-left']);
		
		if(isset($_REQUEST['disableonmobile']))
		{
			update_option('floating_social_button_disable_mobile','1');
		}
		else
		{
			update_option('floating_social_button_disable_mobile','0');
		}
		$message = '<div id="message" class="updated"><p>Floating Social Buttons Setting Saved Successfully..</p></div>';
	}
}
	
	?>
	
<div class="wrap">
<div id="icon-options-general" class="icon32"></div>
<h2>Floating Social Buttons</h2>

<?php echo $message; 
$buttons = explode (',',get_option( 'selected_button' ));

?>
<div id="poststuff">

            <div id="post-body" class="metabox-holder columns-2">

                <!-- main content -->
                <div id="post-body-content">

                    <div class="meta-box-sortables ui-sortable">


                        <div class="postbox">

                            <h3><span>Floating Social Buttons Settings</span></h3>
                            <div class="inside">
 <form name="social_buttons" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
        <input type="hidden" name="social_buttons_hidden" value="Y">
		
		
		<ul class="list-group">
		<li class="list-group-item list-group-item-info"><strong>Select buttons to display on website:</strong></li>
		<li class="list-group-item">
		<input type="checkbox" <?php if(in_array('facebook',$buttons)) { echo 'checked="checked"'; }?> name="socialbuttons[]" value="facebook"/> FaceBook Like
		</li><li class="list-group-item">
		<input type="checkbox" <?php if(in_array('facebook_share',$buttons)) { echo 'checked="checked"'; }?> name="socialbuttons[]" value="facebook_share"/> FaceBook Share</li><li class="list-group-item">
		<input type="checkbox" <?php if(in_array('tweet',$buttons)) { echo 'checked="checked"'; }?> name="socialbuttons[]" value="tweet"/> Tweet
		</li><li class="list-group-item">
		<input type="checkbox" <?php if(in_array('gplus',$buttons)) { echo 'checked="checked"'; }?> name="socialbuttons[]" value="gplus"/> Google Plus
		</li><li class="list-group-item">
		<input type="checkbox" <?php if(in_array('linkedin',$buttons)) { echo 'checked="checked"'; }?> name="socialbuttons[]" value="linkedin"/> Linkedin
		</li><li class="list-group-item">
		<input type="checkbox" <?php if(in_array('digg',$buttons)) { echo 'checked="checked"'; }?> name="socialbuttons[]" value="digg"/> Digg
		</li><li class="list-group-item">
		<input type="checkbox" <?php if(in_array('stumbleupon',$buttons)) { echo 'checked="checked"'; }?> name="socialbuttons[]" value="stumbleupon"/>
		StumbleUpon
		</li><li class="list-group-item">
		<input type="checkbox" <?php if(in_array('pinterest',$buttons)) { echo 'checked="checked"'; }?> name="socialbuttons[]" value="pinterest"/> Pinterest
		</li>		
		</ul>
		
		<ul class="list-group">
		<li class="list-group-item list-group-item-info"><strong>Display Setting :</strong></li>
		<li class="list-group-item">
		
		Float Position : <input type="radio" name="floating" <?php if(get_option('floating_social_button_float') == "left") { echo 'checked="checked"'; }?> value="left" > Left <input type="radio" name="floating" value="right" <?php if(get_option('floating_social_button_float') == "right") { echo 'checked="checked"'; }?>> Right
		</li>
		<li class="list-group-item">
		Position From Top : <input type="text" name="position-top" value="<?php echo get_option('floating_social_button_position_top');?>" style="width:40px;"/>%</li>
		<li class="list-group-item">
		Position From Left or Right : <input type="text" name="position-left" value="<?php echo get_option('floating_social_button_position_left');?>" style="width:40px;"/>px </li>
		<li class="list-group-item">
		<input type="checkbox" id="chkmobile" name="disableonmobile"  <?php if(get_option('floating_social_button_disable_mobile') == "1") { echo 'checked="checked"'; }?> /> Disable On Mobile
		</li>
		</ul><div class="alert alert-danger">
		Note : if you select Float left, it will consider position from left and if you select Float right then it will consider from right
		</div>
		
		<input type="submit" name="savesetting" class="btn btn-primary" value="Save Setting"/>
</form>
</div> <!-- .inside -->

                        </div> <!-- .postbox -->

						<div class="postbox">
                            <?php
                                $plugin_data = floating_social_buttons_get_plugin_data();

                                $app_link = urlencode($plugin_data['PluginURI']);
                                $app_title = urlencode($plugin_data['Name']);
                                $app_descr = urlencode($plugin_data['Description']);
                                ?>
                                <h3>Share</h3>
                                <p>
                                    <!-- AddThis Button BEGIN -->
                                <div class="addthis_toolbox addthis_default_style addthis_32x32_style" style="padding: 0px 20px 10px;">
                                    <a class="addthis_button_facebook" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_twitter" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_google_plusone" g:plusone:count="false" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_linkedin" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_email" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_myspace" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_google" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_digg" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_delicious" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_stumbleupon" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_tumblr" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_favorites" addthis:url="<?php echo $app_link ?>" addthis:title="<?php echo $app_title ?>" addthis:description="<?php echo $app_descr ?>"></a>
                                    <a class="addthis_button_compact"></a>
                                </div>
                                <!-- The JS code is in the footer -->

                                <script type="text/javascript">
                                    var addthis_config = {"data_track_clickback": true};
                                    var addthis_share = {
                                        templates: {twitter: 'Check out {{title}} #WordPress #plugin at {{lurl}} (via @orbisius)'}
                                    }
                                </script>
                                <!-- AddThis Button START part2 -->
                                <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=lordspace"></script>
                                <!-- AddThis Button END part2 -->
                        </div> <!-- .postbox -->
                        <div class="postbox">
						<h3>Our Other Plugins</h3>
						<div class="list-group">
							  <?php include plugin_dir_path( __FILE__ ) . "/class/our_plugins.php" ?>
							</div>
						</div>
                    </div> <!-- .meta-box-sortables .ui-sortable -->

                </div> <!-- post-body-content -->
  <!-- sidebar -->
                <div id="postbox-container-1" class="postbox-container">

                    <div class="meta-box-sortables">

                        <div class="postbox">
                            <h3><span>Hire Us</span></h3>
                            <div class="inside">
								We are expert Wordpress Developer.
                                Hire us to create a plugin/web/mobile application for your business.
                                <br/><a href="https://www.odesk.com/users/~0148a552598c563ebb"
                                   title="If you want a custom web/mobile app/plugin developed contact us. This opens in a new window/tab"
                                    class="btn btn-primary" target="_blank">Hire me on O'Desk</a>
                            </div> <!-- .inside -->
                        </div> <!-- .postbox -->

                        <div class="postbox">
                            <h3><span>Follow Us On FaceBook</span></h3>
                            <div class="inside">
                                  <iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fclick2check.net&amp;width=250&amp;height=290&amp;colorscheme=light&amp;show_faces=true&amp;header=true&amp;stream=false&amp;show_border=true&amp;appId=161074614043987" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:250px; height:290px;" allowTransparency="true"></iframe>
                            </div> <!-- .inside -->
                        </div> <!-- .postbox -->

                        <div class="postbox">
						<h3><span>Follow Us On Google Plus</span></h3>
                            <div class="inside">
                              <iframe frameborder="0" hspace="0" marginheight="0" marginwidth="0" scrolling="no" style="position: static; top: 0px; width: 280px; margin: 0px; border-style: none; left: 0px; visibility: visible; height: 232px;" tabindex="0" vspace="0" width="100%" id="I0_1393700337193" name="I0_1393700337193" src="https://apis.google.com/_/im/_/widget/render/plus/followers?usegapi=1&amp;bsv=o&amp;action=followers&amp;height=250&amp;source=blogger%3Ablog%3Afollowers&amp;width=280&amp;origin=http%3A%2F%2Fwww.click2check.net&amp;url=https%3A%2F%2Fplus.google.com%2F115313279456959924671&amp;gsrc=3p&amp;ic=1&amp;jsh=m%3B%2F_%2Fscs%2Fapps-static%2F_%2Fjs%2Fk%3Doz.gapi.en_GB.wyNTvg-ZoSU.O%2Fm%3D__features__%2Fam%3DIQ%2Frt%3Dj%2Fd%3D1%2Ft%3Dzcms%2Frs%3DAItRSTP1urva78IJIHxeksZE6kSRRrxiwQ#_methods=onPlusOne%2C_ready%2C_close%2C_open%2C_resizeMe%2C_renderstart%2Concircled%2Cdrefresh%2Cerefresh%2Conload&amp;id=I0_1393700337193&amp;parent=http%3A%2F%2Fwww.click2check.net&amp;pfname=&amp;rpctoken=99993975" data-gapiattached="true" title="+1"></iframe>
                            </div>
                        </div> <!-- .postbox -->


                        <div class="postbox"> <!-- quick-contact -->
                            
                            <h3><span>Quick Help or Suggestion</span></h3>
                            <div class="inside">
                                <div>
                                    Your questions and suggestions are most welcome! if you have any question than feel free to contact us.
                                        <a href="<?php echo $plugin_data['PluginURI'];?>"
                                   title="<?php echo $plugin_data['Name'];?>"
                                    class="btn btn-primary" target="_blank">Contact Us Now</a>
                                </div>
                            </div> <!-- .inside -->
                         </div> <!-- .postbox --> <!-- /quick-contact -->

                    </div> <!-- .meta-box-sortables -->

                </div> <!-- #postbox-container-1 .postbox-container -->

            </div> <!-- #post-body .metabox-holder .columns-2 -->

            <br class="clear">
        </div> <!-- #poststuff -->



</div>
<?php 
}?>