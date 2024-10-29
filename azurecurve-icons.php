<?php
/*
Plugin Name: azurecurve Icons
Plugin URI: http://development.azurecurve.co.uk/plugins/icons

Description: Allows a 16x16 icon to be displayed in a post of page using a shortcode.
Version: 1.0.3

Author: azurecurve
Author URI: http://development.azurecurve.co.uk

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.

The full copy of the GNU General Public License is available here: http://www.gnu.org/licenses/gpl.txt

*/

//include menu
require_once( dirname(  __FILE__ ) . '/includes/menu.php');

function azc_i_icon($atts, $content = null) {
	if (empty($atts)){
		$icon = 'none';
	}else{
		$attribs = implode('',$atts);
		$icon = trim ( trim ( trim ( trim ( trim ( $attribs , '=' ) , '"' ) , "'" ) , '&#8217;' ) , "&#8221;" );
	}
	return "<img class='azc_icons' src='".plugin_dir_url(__FILE__)."images/$icon.png' />";
}
add_shortcode( 'icon', 'azc_i_icon' );
add_shortcode( 'icons', 'azc_i_icon' );
add_shortcode( 'ICON', 'azc_i_icon' );
add_shortcode( 'ICONS', 'azc_i_icon' );

function azc_i_load_css(){
	wp_enqueue_style( 'azurecurve-icons', plugins_url( 'style.css', __FILE__ ) );
}
add_action('wp_enqueue_scripts', 'azc_i_load_css');

function azc_create_i_plugin_menu() {
	global $admin_page_hooks;
    
	add_submenu_page( "azc-plugin-menus"
						,"icons"
						,"Icons"
						,'manage_options'
						,"azc-i"
						,"azc_i_settings" );
}
add_action("admin_menu", "azc_create_i_plugin_menu");

function azc_i_settings() {
	if (!current_user_can('manage_options')) {
		$error = new WP_Error('not_found', __('You do not have sufficient permissions to access this page.' , 'azc-i'), array('response' => '200'));
		if(is_wp_error($error)){
			wp_die($error, '', $error->get_error_data());
		}
    }
	?>
	<div id="azc-t-general" class="wrap">
			<h2>azurecurve icons</h2>

			<label for="explanation">
				<p>azurecurve Icons <?php _e('allows a 16x16 icon to be displayed in a post or page using the [icon] shortcode.', 'azc-i'); ?></p>
				<p><?php _e('Format of shortcode is [icon=accept] to display the accept icon.', 'azc-i'); ?></p>
				<p>Included icons are from the famfamfam Silk icon set 1.3 by Mark James (<a href="http://www.famfamfam.com/lab/icons/silk/">http://www.famfamfam.com/lab/icons/silk/</a>). Extra icons can be added by simply placing them in PNG format into the /images folder; the filename, without the extension, is the shortcode parameter.</p>
			</label>
			<p>
			Available icons are:
				
				<?php
				$dir = plugin_dir_path(__FILE__) . '/images';
				if (is_dir( $dir )) {
					if ($directory = opendir($dir)) {
						while (($file = readdir($directory)) !== false) {
							if ($file != '.' and $file != '..' and $file != 'Thumbs.db'){
								$filewithoutext = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
								echo "<div style='width: 180px; display: inline-block;'><img src='";
								echo plugin_dir_url(__FILE__) . "images/$filewithoutext.png;' title='$filewithoutext' alt='$filewithoutext' />&nbsp;<em>$filewithoutext</em></div>";
							}
						}
						closedir($directory);
					}
				}
				?>
				
			</p>
			
			<label for="additional-plugins">
				azurecurve <?php _e('has the following plugins which allow shortcodes to be used in comments and widgets:', 'azc-i'); ?>
			</label>
			<ul class='azc_plugin_index'>
				<li>
					<?php
					if ( is_plugin_active( 'azurecurve-shortcodes-in-comments/azurecurve-shortcodes-in-comments.php' ) ) {
						echo "<a href='admin.php?page=azc-sic' class='azc_plugin_index'>Shortcodes in Comments</a>";
					}else{
						echo "<a href='https://wordpress.org/plugins/azurecurve-shortcodes-in-comments/' class='azc_plugin_index'>Shortcodes in Comments</a>";
					}
					?>
				</li>
				<li>
					<?php
					if ( is_plugin_active( 'azurecurve-shortcodes-in-widgets/azurecurve-shortcodes-in-widgets.php' ) ) {
						echo "<a href='admin.php?page=azc-siw' class='azc_plugin_index'>Shortcodes in Widgets</a>";
					}else{
						echo "<a href='https://wordpress.org/plugins/azurecurve-shortcodes-in-widgets/' class='azc_plugin_index'>Shortcodes in Widgets</a>";
					}
					?>
				</li>
			</ul>
	</div>
<?php }

// Add Action Link
function azc_i_plugin_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=azc-i">'.__('Settings' ,'azc-i').'</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}
add_filter('plugin_action_links', 'azc_i_plugin_action_links', 10, 2);

?>