<?php
/*
Plugin Name: GEO-location and directions
Plugin URI: http://www.zeevm.co.il
Description: shows the mobile user his GEO-location and directions to the owner of the site location (business)
Version: 0.1
Author URI: ze'ev ma'ayan 
Author URI: http://www.zeevm.co.il

/*  TM zeevm.co.il */

add_action('wp_footer', 'wpse_43672_wp_footer', 0);
/**
 * Hooks into the `wp_head` action.
*/
function wpse_43672_wp_footer(){ ?>
		       <script>
	   if (navigator.userAgent.match(/Android/i) ||
             navigator.userAgent.match(/webOS/i) ||
             navigator.userAgent.match(/iPhone/i) ||
             navigator.userAgent.match(/iPad/i) ||
             navigator.userAgent.match(/iPod/i) ||
             navigator.userAgent.match(/BlackBerry/) || 
             navigator.userAgent.match(/Windows Phone/i) || 
             navigator.userAgent.match(/ZuneWP7/i)
             ) {
			
                // some code
				document.write('<div style="height:200px; width:100%; background:<?php echo get_option('bg-color'); ?>; border-top-left-radius:35px; border-top-right-radius:35px; position: fixed; bottom:0; left:0; z-index:999;"><div style="margin:90px 0 0 -230px; position:absolute; left:50%;"><a style="font-size:<?php echo get_option('text-size'); ?>px; color:#fff;" href="<?php echo plugins_url(); ?>/get-directions-from-mobile/mobile-page.php" target="_blank"><?php echo get_option('googlemap_button_text'); ?></a></div></div>');
				
               }
		</script>
<?php }?>
<?php
// create custom plugin settings menu  
add_action('admin_menu', 'googlemap_create_menu');  
function googlemap_create_menu() {  
    //create new top-level menu  
    add_menu_page('geo-directions movile redirect', 'Geo Directions', 'administrator', __FILE__, 'googlemap_settings_page');  
      //create new top-level menu  
    //call register settings function  
    add_action( 'admin_init', 'register_mysettings' );  
}  
function register_mysettings() {  
    //register our settings  
	register_setting( 'googlemap-settings-group', 'googlemap_button_text' ); 
    register_setting( 'googlemap-settings-group', 'googlemap_link' );  
	register_setting( 'googlemap-settings-group', 'text-size' );  
	register_setting( 'googlemap-settings-group', 'bg-color' );  
} 
function googlemap_settings_page() { ?>  
<div class="wrap">  
<h2>Your Mobile Buttons Links</h2>  
<form method="post" action="options.php">  
    <?php settings_fields('googlemap-settings-group'); ?>  
    <table class="form-table">  
        <tr valign="top">  
        <th scope="row">button text (at the mobile phone)</th>
        <td><textarea name="googlemap_button_text" cols="90" value="Get Directions To Us"><?php echo get_option('googlemap_button_text'); ?></textarea></td>  
        </tr> 
		<tr valign="top">  
        <th scope="row">Background Color (Example: #647687)</th>
        <td><textarea name="bg-color" cols="90" value="#647687"><?php echo get_option('bg-color'); ?></textarea></td>  
        </tr> 
		<tr valign="top">  
        <th scope="row">Font Size (in px)</th>
        <td><input name="text-size" value="60"><?php echo get_option('text-size'); ?></input></td>  
        </tr> 
        <tr valign="top">  
        <th scope="row">Destination Address</th>
        <td><textarea name="googlemap_link" cols="90" value="tel aviv yaffo 16, israel"><?php echo get_option('googlemap_link'); ?></textarea>
		<br/>find <a href="https://maps.google.com/" target="_blank">your location</a></td>
        </tr>     
    </table>  
    <p class="submit">  
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />  
    </p>  
</form>  
</div>  
<?php } ?>