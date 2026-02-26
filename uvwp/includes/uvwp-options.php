<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'admin_menu', 'uvwp_options_page' );
function uvwp_options_page(){
    global $uvwp_url;

    add_menu_page(
        "UrVenue",
        "UrVenue",
        "administrator",
        "urvenue_opts",
        "uvwp_admin_page",
        $uvwp_url . "/assets/icons/uvlogo-options.png",
        80
    );
}

function uvwp_include_adminstyles(){
    global $uvwp_url, $uvs_url, $uv_assetsversion;

    wp_register_style('uvwp-admin', $uvwp_url . '/assets/css/uvwp-admin.css', false, $uv_assetsversion);
	wp_enqueue_style('uvwp-admin');

	wp_register_style('uvs-systembase', $uvs_url . '/assets/css/system-base.css', false, $uv_assetsversion);
	wp_enqueue_style('uvs-systembase');

    wp_register_style('uvs-system', $uvs_url . '/assets/css/system.css', false, $uv_assetsversion);
	wp_enqueue_style('uvs-system');

    wp_register_style('uv-icons', $uvs_url . '/assets/css/uwsicons.css', false, $uv_assetsversion);
	wp_enqueue_style('uv-icons');

    wp_register_style('flatpickr', $uvs_url . '/assets/css/flatpickr.min.css', false, $uv_assetsversion);
    wp_enqueue_style('flatpickr');

}
add_action('admin_head', 'uvwp_include_adminstyles');

function uvwp_include_adminscripts(){
    global $uvs_url;

    wp_enqueue_style( 'wp-color-picker' );

    wp_register_script('jquery-validate', $uvs_url . '/assets/js/jquery.validate.min.js', array('jquery'), 1, true);
    wp_enqueue_script('jquery-validate');

    wp_register_script('uvs-admin', $uvs_url . '/assets/js/admin.js', array('jquery', 'wp-color-picker'), 1, true);
    wp_enqueue_script('uvs-admin');

    wp_register_script('flatpickr', $uvs_url . '/assets/js/flatpickr.min.js', false, 1, true);
    wp_enqueue_script('flatpickr'); 
}
add_action('admin_enqueue_scripts', 'uvwp_include_adminscripts');

function uvwp_admin_page(){
    global $uvs_path, $uvs_libexits, $uvwp_path, $uvs_core_lib, $uvs_url, $uws_coreurl, $uvs_admin_lib, $uws_core_version, $uvs_feeds_path;
        
    include_once($uvwp_path . "/admin/admin-page.php");
}

/* Front */
//Include front styles
function uvscore_include_styles(){
    global $uws_coreurl, $uv_assetsversion, $uws_core_lib;
	
    //Global Styles, included on all pages
    wp_register_style('uwscore-styles', $uws_coreurl . '/assets/css/uwscore.css', false, $uv_assetsversion);
    wp_enqueue_style('uwscore-styles');

    wp_register_style('uws-icons-styles', $uws_coreurl . '/assets/css/uwsicons.css', false, $uv_assetsversion);
    wp_enqueue_style('uws-icons-styles');

    //Specific pages styles
    wp_register_style('uws-events-styles', $uws_coreurl . '/assets/css/events.css', false, $uv_assetsversion);
    wp_register_style('uws-event-styles', $uws_coreurl . '/assets/css/event.css', false, $uv_assetsversion);
    wp_register_style('litepicker', $uws_coreurl . '/assets/css/litepicker.min.css', false, 1);
    wp_register_style('nouislider', $uws_coreurl . '/assets/css/nouislider.min.css', false, 1);
    wp_register_style('uws-inventory-styles', $uws_coreurl . '/assets/css/uwsinventory.css', false, $uv_assetsversion);
    wp_register_style('uws-experiences-styles', $uws_coreurl . '/assets/css/experiences.css', false, $uv_assetsversion);
    wp_register_style('uws-invitempage-styles', $uws_coreurl . '/assets/css/invitempage.css', false, $uv_assetsversion);
    wp_register_style('uws-itinerary-styles', $uws_coreurl . '/assets/css/itinerary.css', false, $uv_assetsversion);
    wp_register_style('uws-map-styles', $uws_coreurl . '/assets/css/map.css', false, $uv_assetsversion);
    wp_register_style('uws-reservations-styles', $uws_coreurl . '/assets/css/reservations.css', false, $uv_assetsversion);
    wp_register_style('uws-apireq', $uws_coreurl . '/assets/css/apireq.css', false, $uv_assetsversion);
    wp_register_style('perfect-scrollbar', $uws_coreurl . '/assets/css/perfect-scrollbar.css', false, 1);
    wp_register_style('uws-memberships', $uws_coreurl . '/assets/css/memberships.css', false, $uv_assetsversion);
    wp_register_style('uws-packages', $uws_coreurl . '/assets/css/packages.css', false, $uv_assetsversion);

    if(is_array($uws_core_lib) and $uws_core_lib["pages"]["events"] and is_page($uws_core_lib["pages"]["events"])){//pre include events page styles
        //include styles
        wp_enqueue_style('uws-events-styles');
        wp_enqueue_style('litepicker');
    }
    else if(is_array($uws_core_lib) and $uws_core_lib["pages"]["singleevent"]  and is_page($uws_core_lib["pages"]["singleevent"])){// pre include event page styles
        //include styles
        wp_enqueue_style('uws-event-styles');
        wp_enqueue_style('uws-inventory-styles');
        wp_enqueue_style('litepicker');
    }
    else if(is_array($uws_core_lib) and $uws_core_lib["pages"]["map"]  and is_page($uws_core_lib["pages"]["map"])){// pre include map page styles
        //include styles
        wp_enqueue_style('uws-map-styles');
        wp_enqueue_style('uws-inventory-styles');
        wp_enqueue_style('litepicker');
        wp_enqueue_style('nouislider');
    }
}
add_action('wp_enqueue_scripts', 'uvscore_include_styles');

//Add <head> styles for css vars
function uvscore_add_head_styles(){
    $uvcssvars = uws_get_css_vars();

    // @Axl
    // echo "<style>$uvcssvars</style>";
    // CSS output — no HTML escaping function applies to CSS; wp_strip_all_tags() prevents HTML/script injection while preserving CSS declarations
    echo '<style>' . wp_strip_all_tags( $uvcssvars ) . '</style>';
    // @Axl End
}
add_action('wp_head', 'uvscore_add_head_styles', 50);

//Add scripts to footer
function uwscore_add_footer_scripts(){
    //$uvfooterproxy = uws_get_proxies_script("uvcore-init");
    $uvfooterproxy = uws_get_proxy_script();

    // @Axl
    // echo $uvfooterproxy;
    // uws_get_proxy_script() always returns "" — proxy is registered internally via wp_add_inline_script(). Echo is a no-op but kept for traceability.
    echo wp_kses( $uvfooterproxy, array() );
    // @Axl End
}
add_action('wp_footer', 'uwscore_add_footer_scripts');

//Include front scripts
function uvscore_include_scripts(){
    global $uws_coreurl, $uv_assetsversion, $uws_core_lib;

    //Global Styles, included on all pages
	wp_register_script('uwscore-scripts', $uws_coreurl . '/assets/js/uwscore.js', false, $uv_assetsversion);

    //Specific pages scrips
    wp_register_script('uws-events-scripts', $uws_coreurl . '/assets/js/events.js', false, $uv_assetsversion);
    wp_register_script('litepicker', $uws_coreurl . '/assets/js/litepicker.min.js', false, 1);
    wp_register_script('nouislider', $uws_coreurl . '/assets/js/nouislider.min.js', false, 1);
    wp_register_script('hammer', $uws_coreurl . '/assets/js/hammer.min.js', false, 1);
    wp_register_script('uws-inventory-scripts', $uws_coreurl . '/assets/js/uwsinventory.js', false, $uv_assetsversion);
    wp_register_script('uws-experiences-scripts', $uws_coreurl . '/assets/js/experiences.js', false, $uv_assetsversion);
    wp_register_script('uws-invitempage-scripts', $uws_coreurl . '/assets/js/invitempage.js', false, $uv_assetsversion);
	wp_register_script('uws-itinerary-scripts', $uws_coreurl . '/assets/js/itinerary.js', false, $uv_assetsversion);
    wp_register_script('uws-map-scripts', $uws_coreurl . '/assets/js/map.js', false, $uv_assetsversion);
    wp_register_script('uws-reservations-scripts', $uws_coreurl . '/assets/js/reservations.js', false, $uv_assetsversion);
    wp_register_script('uws-hooks-ga4dl', $uws_coreurl . '/assets/js/hooks-ga4dl.js', false, 1);
    wp_register_script('uws-mapzoom', $uws_coreurl . '/assets/js/mapzoom.min.js', false, 1);
    wp_register_script('uws-mapthumbview', $uws_coreurl . '/assets/js/mapthumbview.js', false, $uv_assetsversion);
    wp_register_script('uws-apireq', $uws_coreurl . '/assets/js/apireq.js', false, $uv_assetsversion);
    wp_register_script('perfect-scrollbar', $uws_coreurl . '/assets/js/perfect-scrollbar.min.js', false, 1);
    wp_register_script('pristine', $uws_coreurl . '/assets/js/validate.min.js', false, 1);
    wp_register_script('uws-memberships', $uws_coreurl . '/assets/js/memberships.js', false, $uv_assetsversion);
    wp_register_script('uws-packages', $uws_coreurl . '/assets/js/packages.js', false, $uv_assetsversion);

    wp_enqueue_script('uwscore-scripts');
    wp_enqueue_script('uws-inventory-scripts');

    if(is_array($uws_core_lib) and $uws_core_lib["pages"]["events"] and is_page($uws_core_lib["pages"]["events"])){//pre include events page scripts
        //include scripts
        wp_enqueue_script('uws-events-scripts');
        wp_enqueue_script('litepicker');
    }
    else if(is_array($uws_core_lib) and $uws_core_lib["pages"]["singleevent"]  and is_page($uws_core_lib["pages"]["singleevent"])){// pre include event page scripts
        //include scripts
	    wp_enqueue_script('uwscore-scripts');
	    wp_enqueue_script('uws-inventory-scripts');
        wp_enqueue_script('pristine');
        wp_enqueue_script('litepicker');
        wp_enqueue_script('uws-events-scripts');
    }
    else if(is_array($uws_core_lib) and $uws_core_lib["pages"]["map"]  and is_page($uws_core_lib["pages"]["map"])){// pre include map page scripts
        //include scripts
        wp_enqueue_script('uws-inventory-scripts');
        wp_enqueue_script('litepicker');
        wp_enqueue_script('nouislider');
        wp_enqueue_script('hammer');
        wp_enqueue_script('uws-mapzoom');
        wp_enqueue_script('uws-mapthumbview');
        wp_enqueue_script('pristine');
    }
}
add_action("wp_enqueue_scripts", "uvscore_include_scripts");

//Add proxy files
add_action('wp_ajax_nopriv_uvpx', 'uvwp_proxy');
add_action('wp_ajax_uvpx', 'uvwp_proxy');
function uvwp_proxy(){
	global $uws_corepath, $uvs_path, $uvs_admin_feeds, $uvs_core_lib, $uvs_envicode;
	
	include_once($uws_corepath . "/uvcore.proxy.php");
	
	die();
}

//Event URL and Vars
add_filter('query_vars', 'uwswpplug_add_query_vars');
function uwswpplug_add_query_vars($query_vars){
    $query_vars[] = 'eventcode';
    $query_vars[] = 'mastercode';

    return $query_vars;
}

//Pages URLs rewrite rules
add_action('init', function(){
	global $uws_core_lib;

    if(get_option('uv-flush-pending')){//Flush if pending
        flush_rewrite_rules();
        update_option( 'uv-flush-pending', 0);
    }
    if(isset($_REQUEST["uvaction"]) and $_REQUEST["uvaction"] and $_REQUEST["uvaction"] == "uvsp_adminsave")//add flush to pending
        update_option('uv-flush-pending', 1);
	
    //Event Page Rewrite
    $uvsingleeventpageid = $uws_core_lib["pages"]["singleevent"];
    if($uvsingleeventpageid){
        $uveventpagedir = get_permalink($uvsingleeventpageid);
        $uveventpagedir = str_replace( home_url() . "/", "", $uveventpagedir );
        $uveventpagedir = rtrim($uveventpagedir, "/");

        add_rewrite_rule('^' . $uveventpagedir . '/(eve|EVE[^/]*)-?([^/]*)/?','index.php?page_id=' . $uvsingleeventpageid . '&eventcode=$matches[1]', 'top');
    }

    //Map Page Rewrite
    $uvmappageid = $uws_core_lib["pages"]["map"];
    if($uvmappageid){
	    $uvmappagedir = get_permalink($uvmappageid);
        $uvmappagedir = str_replace( home_url() . "/", "", $uvmappagedir );
        $uvmappagedir = rtrim($uvmappagedir, "/");

        add_rewrite_rule('^' . $uvmappagedir . '/(eve|EVE[^/]*)-?([^/]*)/?','index.php?page_id=' . $uvmappageid . '&eventcode=$matches[1]', 'top');
    }

    //Item Page Rewrite
    $uvitempageid = $uws_core_lib["pages"]["itempage"];
    if($uvitempageid){
	    $uvitempagedir = get_permalink($uvitempageid);
        $uvitempagedir = str_replace( home_url() . "/", "", $uvitempagedir );
        $uvitempagedir = rtrim($uvitempagedir, "/");

        add_rewrite_rule('^' . $uvitempagedir . '/mc|MC([^/]*)-?([^/]*)/?','index.php?page_id=' . $uvitempageid . '&mastercode=$matches[1]', 'top');
    }

    //Check if event pages map exists
    if(isset($uws_core_lib["eventpagesmap"]) and is_array($uws_core_lib["eventpagesmap"])){
        foreach($uws_core_lib["eventpagesmap"] as $uvvenueeventpage){
            foreach($uvvenueeventpage as $uvlinkcode => $uvlinkpageid){
                if(is_array($uvlinkpageid)) {
                    foreach($uvlinkpageid as $uvlinkcode => $uvlink){
                        if($uvlinkcode == "singleevent" or $uvlinkcode == "map"){
                            $uvthispageperml = get_permalink($uvlink);
                            $uvthispageperml = str_replace( home_url() . "/", "", $uvthispageperml );
                            $uvthispageperml = rtrim($uvthispageperml, "/");

                            add_rewrite_rule('^' . $uvthispageperml . '/(eve|EVE[^/]*)-?([^/]*)/?','index.php?page_id=' . $uvlink . '&eventcode=$matches[1]', 'top');
                        }
                    }
                } else if($uvlinkcode == "singleevent" or $uvlinkcode == "map"){
                    $uvthispageperml = get_permalink($uvlinkpageid);
                    $uvthispageperml = str_replace( home_url() . "/", "", $uvthispageperml );
                    $uvthispageperml = rtrim($uvthispageperml, "/");

                    add_rewrite_rule('^' . $uvthispageperml . '/(eve|EVE[^/]*)-?([^/]*)/?','index.php?page_id=' . $uvlinkpageid . '&eventcode=$matches[1]', 'top');
                }
            }
        }
    }
}, 10, 0);

//Check if pageid is in event pages map
function uwswpplug_is_page_singleevent(){
    global $uws_core_lib;

    $uvissingleevent = 0;

    if(isset($uws_core_lib["eventpagesmap"]) and is_array($uws_core_lib["eventpagesmap"])){
        foreach($uws_core_lib["eventpagesmap"] as $uvvenuecode => $uvvenueeventpage){
            foreach($uvvenueeventpage as $uvlinkcode => $uvlinkpageid){
                if($uvlinkcode == "singleevent"){
                    if(is_page($uvlinkpageid)){
                        $uvissingleevent = 1;
                        break;
                    }
                }
            }

            if($uvvenuecode == "langs" and !$uvissingleevent){
                foreach($uvvenueeventpage as $uvthislang){
                    foreach($uvthislang as $uvlinkcode => $uvlinkpageid){
                        if($uvlinkcode == "singleevent"){
                            if(is_page($uvlinkpageid)){
                                $uvissingleevent = 1;
                                break;
                            }
                        }
                    }
                }
            }

            if($uvissingleevent) break;
        }
    }
    
    return $uvissingleevent;
}

//SEO 
//Edit Page Title When Page is Dynamic
// Yoast
add_filter('wpseo_title', 'uwswpplug_set_meta_title', 10, 2 );
add_filter('wpseo_opengraph_title', 'uwswpplug_set_meta_title', 10, 2 );
add_filter('wpseo_twitter_title', 'uwswpplug_set_meta_title', 10, 2 );

// Rank Math
add_filter('rank_math/frontend/title', 'uwswpplug_set_meta_title', 10, 2 );
add_filter('rank_math/opengraph/facebook/title', 'uwswpplug_set_meta_title', 10, 2 );
add_filter('rank_math/opengraph/twitter/title', 'uwswpplug_set_meta_title', 10, 2 );

function uwswpplug_set_meta_title($title){
	global $uvs_core_lib;

    $uviseventsingle = 0;
    $uvsingleeventpageid = $uvs_core_lib["pages"]["singleevent"];

    if($uvsingleeventpageid and is_page($uvsingleeventpageid))
        $uviseventsingle = 1;
    else
        $uviseventsingle = uwswpplug_is_page_singleevent();
		
	if($uviseventsingle and $uvs_core_lib["seo"]["enabletags"]){
        $uveventseo = uws_get_event_seo();

        if(is_array($uveventseo) and $uveventseo["title"])
            $title = $uveventseo["title"];
	}
    
    return $title;
}

add_filter('wpseo_opengraph_type', 'uwswpplug_set_meta_type', 10, 2 );
function uwswpplug_set_meta_type($type){
	global $uvs_core_lib;

    $uviseventsingle = 0;
    $uvsingleeventpageid = $uvs_core_lib["pages"]["singleevent"];

    if($uvsingleeventpageid and is_page($uvsingleeventpageid))
        $uviseventsingle = 1;
    else
        $uviseventsingle = uwswpplug_is_page_singleevent();
		
	if($uviseventsingle and $uvs_core_lib["seo"]["enabletags"]){
        //$uveventseo = uws_get_event_seo();
        //if(is_array($uveventseo) and $uveventseo["title"])
        $type = "event";
	}
    
    return $type;
}

//Edit Page Description When Page is Dynamic
// Yoast
add_filter('wpseo_metadesc', 'uwswpplug_set_meta_description', 10, 2 );
add_filter('wpseo_opengraph_desc', 'uwswpplug_set_meta_description', 10, 2 );

// Rank Math
add_filter('rank_math/frontend/description', 'uwswpplug_set_meta_description', 10, 2 );
add_filter('rank_math/opengraph/facebook/description', 'uwswpplug_set_meta_description', 10, 2 );

function uwswpplug_set_meta_description($description){
	global $uvs_core_lib;

    $uviseventsingle = 0;
    $uvsingleeventpageid = $uvs_core_lib["pages"]["singleevent"];

    if($uvsingleeventpageid and is_page($uvsingleeventpageid))
        $uviseventsingle = 1;
    else
        $uviseventsingle = uwswpplug_is_page_singleevent();
	
	if($uviseventsingle and $uvs_core_lib["seo"]["enabletags"]){
        $uveventseo = uws_get_event_seo();

        if(is_array($uveventseo) and $uveventseo["description"])
            $description = $uveventseo["description"];
    }
    
    return $description;
}

//Edit Page Image When Page is Dynamic
// Yoast
add_filter('wpseo_opengraph_image', 'uwswpplug_set_meta_image', 10, 2 );
add_filter('wpseo_twitter_image', 'uwswpplug_set_meta_image', 10, 2 );

// Rank Math
add_filter('rank_math/opengraph/facebook/image', 'uwswpplug_set_meta_image', 10, 2 );
add_filter('rank_math/opengraph/twitter/image', 'uwswpplug_set_meta_image', 10, 2 );

function uwswpplug_set_meta_image($image){
	global $uvs_core_lib;
	
    $uviseventsingle = 0;
	$uvsingleeventpageid = $uvs_core_lib["pages"]["singleevent"];

    if($uvsingleeventpageid and is_page($uvsingleeventpageid))
        $uviseventsingle = 1;
    else
        $uviseventsingle = uwswpplug_is_page_singleevent();
	
	if($uviseventsingle and $uvs_core_lib["seo"]["enabletags"]){
        $uveventseo = uws_get_event_seo();

        if(is_array($uveventseo) and $uveventseo["image"])
            $image = $uveventseo["image"];
    }
    
    return $image;
}

//Edit Page URL When Page is Dynamic
// Yoast
add_filter('wpseo_opengraph_url', 'uwswpplug_set_page_url', 10, 2 );

// Rank Math
add_filter('rank_math/opengraph/facebook/url', 'uwswpplug_set_page_url', 10, 2 );

function uwswpplug_set_page_url($pageurl){
    global $uvs_core_lib;
	
    $uviseventsingle = 0;
	$uvsingleeventpageid = $uvs_core_lib["pages"]["singleevent"];

    if($uvsingleeventpageid and is_page($uvsingleeventpageid))
        $uviseventsingle = 1;
    else
        $uviseventsingle = uwswpplug_is_page_singleevent();
	
	if($uviseventsingle and $uvs_core_lib["seo"]["enabletags"]){
        $uveventseo = uws_get_event_seo();

        if(is_array($uveventseo) and $uveventseo["url"])
            $pageurl = $uveventseo["url"];
    }
    
    return $pageurl;
}

//Enable debug only if admin
add_action('plugins_loaded', 'uwswpplug_check_enable_debug');
function uwswpplug_check_enable_debug(){
    global $uws_feeds_debug;

    $uws_feeds_debug = (current_user_can('administrator') and isset($_REQUEST["uvdbg"]) and $_REQUEST["uvdbg"]) ? 1 : 0;

    if(isset($_REQUEST["uvclearcache"]) and $_REQUEST["uvclearcache"])
        uws_clean_cached_feeds();
}   

/**
 * - SEO plugins (Yoast SEO and Rank Math).
 *
 * - If Yoast SEO is active (`WPSEO_VERSION` is defined):
 *   - Adds a custom sitemap for events to the Yoast SEO sitemap index using the `wpseo_sitemap_index` filter.
 *   - Hooks into the `init` action to register a custom sitemap handler for events (`wpseo_do_sitemap_events`).
 *   - Modifies the canonical URL using the `wpseo_canonical` filter.
 *
 * - If Rank Math is active (`RankMath` class exists):
 *   - Modifies the canonical URL using the `rank_math/frontend/canonical` filter.
 */
if (defined('WPSEO_VERSION')) {
    add_filter('wpseo_sitemap_index', 'uwswpplug_add_sitemap_events');
    
    add_action('init', function() {
        add_action("wpseo_do_sitemap_events", 'uwswpplug_sitemap_events');
    });
    
    add_filter('wpseo_canonical', 'uvwp_seo_canonical');
}
if (class_exists('RankMath')) {
    add_filter('rank_math/frontend/canonical', 'uvwp_seo_canonical');
}

/**
 * Adds a filter to modify the sitemap index in Yoast SEO plugin.
 *
 * @param string $sitemap_index The original sitemap index.
 * @return string The modified sitemap index.
 */
function uwswpplug_add_sitemap_events($sitemap_index) {
    $uveventssmp = '';
    $uvlastmod = date('c', time());

    $uveventssmp .= '<sitemap>' . "\n";
    $uveventssmp .= '<loc>' . site_url() .'/events-sitemap.xml</loc>' . "\n";
    $uveventssmp .= '<lastmod>' . htmlspecialchars($uvlastmod) . '</lastmod>' . "\n";
    $uveventssmp .= '</sitemap>' . "\n";

    return $sitemap_index . $uveventssmp;
}

/**
 * Generates a sitemap for events.
 *
 * This function retrieves a list of events using the uws_get_events function and generates a sitemap XML string
 * containing the URLs and last modification dates of the events. The generated sitemap is then set as the sitemap
 * for the Yoast SEO plugin.
 */
function uwswpplug_sitemap_events() {
    global $wpseo_sitemaps;
    
    $uveventsmp = '';
    $uvevents = uws_get_events();
    
    if(is_array($uvevents)) {
        $uvlastmod = date('c', time());
        
        foreach($uvevents as $uvevent) {
            $uws_eventurl = $uvevent["event-url"];
            $uveventsmp .= "<url><loc>$uws_eventurl</loc><lastmod>$uvlastmod</lastmod></url>";
        }
    }
    
    $uvevtsitemap = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
    $uvevtsitemap .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" ';
    $uvevtsitemap .= 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    $uvevtsitemap .= $uveventsmp . '</urlset>';
    $wpseo_sitemaps->set_sitemap($uvevtsitemap);
}

/* 
* Update the Canonical URL on every single event, this update requires Yoast or Rank Math SEO plugins to be active.
*/
function uvwp_seo_canonical($uvcanonical) {
    global $uvs_core_lib;
	
    $uviseventsingle = 0;
	$uvsingleeventpageid = $uvs_core_lib["pages"]["singleevent"];

    if($uvsingleeventpageid and is_page($uvsingleeventpageid))
        $uviseventsingle = 1;
    else
        $uviseventsingle = uwswpplug_is_page_singleevent();
	
	if($uviseventsingle and $uvs_core_lib["seo"]["enabletags"]){
        $uveventseo = uws_get_event_seo();

        if(is_array($uveventseo) and $uveventseo["url"])
            $uvcanonical = $uveventseo["url"];
    }
    
    return $uvcanonical;
}