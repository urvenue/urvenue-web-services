<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Plugin Name: UrVenue Web Services
 * Plugin URI:  https://wordpress.org/plugins/urvenue-web-services/
 * Description: UrVenue Integrations: Events, Inventory.
 * Version:     1.2.7
 * Author:      UrVenue / UWS, uvwebservices
 * Author URI:  https://www.urvenue.com/
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: urvenue-web-services
 */

/* UvCore Global Vars */
$urvenue_ws_corepath = plugin_dir_path(__FILE__) . "uvcore";
$urvenue_ws_coreurl = plugin_dir_url(__FILE__) . "uvcore";

$urvenue_ws_uvwp_path = plugin_dir_path(__FILE__) . "uvwp";
$urvenue_ws_uvwp_url = plugin_dir_url(__FILE__) . "uvwp";

$urvenue_ws_today = gmdate("Y-m-d", strtotime("-5 hours", strtotime(current_time("Y-m-d H:i:s")))); //Avoid hiding events at 12pm

include_once($urvenue_ws_corepath . "/system/uvs-admin-init.php");
include_once($urvenue_ws_corepath . "/init-uvcore.php");
include_once($urvenue_ws_uvwp_path . "/includes/init-uvwp.php");

// Add Settings link to plugin
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'urvenue_ws_add_settings_link');
function urvenue_ws_add_settings_link($links)
{
    $settings_link = '<a href="admin.php?page=urvenue_opts">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

// Add Cache Link to Admin Bar
add_action('admin_bar_menu', 'urvenue_ws_add_cache_link', 999);
function urvenue_ws_add_cache_link($wp_admin_bar)
{
    global $urvenue_ws_core_lib;

    if (!is_admin() || !isset($urvenue_ws_core_lib['system']['apikey']) || (isset($urvenue_ws_core_lib['system']['apikey']) && $urvenue_ws_core_lib['system']['apikey'] === ''))
        return;

    $args = array(
        'id' => 'urvenue_ws_cache_link',
        'title' => 'Clear Cache',
        'href' => urvenue_ws_adm_get_fieldvalue_by_stringroute("cache->endpoint"),
        'meta' => array(
            'class' => 'urvenue_ws_cache_link uvsjs-clearcache',
            'target' => '_self',
        )
    );
    $wp_admin_bar->add_node($args);
}