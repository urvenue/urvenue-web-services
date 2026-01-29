<?php

/**
 * @wordpress-plugin
 * Plugin Name: UrVenue Web Services
<<<<<<< Updated upstream
 * Version:     1.0.52
 * Plugin URI:  https://wordpress.org/plugins/urvenue-web-services/
 * Description: UrVenue Integrations: Events, Inventory.
 * Author:      UrVenue/uws
 * Author URI:  https://www.urvenue.com/
=======
 * Plugin URI:  https://wordpress.org/plugins/wp-urvenue-webservices/
 * Description: UrVenue Integrations: Events, Inventory.
 * Version:     1.2.1
 * Author:      UrVenue / UWS
 * Author URI:  https://www.urvenue.com/
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
>>>>>>> Stashed changes
 * Text Domain: wp-urvenue-webservices
 */


/* UvCore Global Vars */
$uws_corepath = plugin_dir_path(__FILE__) . "uvcore";
$uws_coreurl = plugin_dir_url(__FILE__) . "uvcore";

$uvwp_path = plugin_dir_path(__FILE__) . "uvwp";
$uvwp_url = plugin_dir_url(__FILE__) . "uvwp";

$uws_today = date("Y-m-d", strtotime("-5 hours", strtotime(current_time("Y-m-d H:i:s")))); //Avoid hiding events at 12pm

include_once($uws_corepath . "/system/uvs-admin-init.php");
include_once($uws_corepath . "/init-uvcore.php");
include_once($uvwp_path . "/includes/init-uvwp.php");

// Add Settings link to plugin
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'uwswpplug_add_settings_link');
function uwswpplug_add_settings_link($links) {
    $settings_link = '<a href="admin.php?page=urvenue_opts">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

// Add Cache Link to Admin Bar
add_action('admin_bar_menu', 'uwswpplug_add_cache_link', 999);
function uwswpplug_add_cache_link($wp_admin_bar) {
    global $uws_core_lib;

    if(!is_admin() || !isset($uws_core_lib['system']['apikey']) || (isset($uws_core_lib['system']['apikey']) && $uws_core_lib['system']['apikey'] === '')) return;
    
    $args = array(
        'id' => 'uws_cache_link',
        'title' => 'Clear Cache',
        'href' => uvs_get_fieldvalue_by_stringroute("cache->endpoint"),
        'meta' => array(
            'class' => 'uws_cache_link uvsjs-clearcache',
            'target' => '_self',
        )
    );
    $wp_admin_bar->add_node($args);
}