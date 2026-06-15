<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'admin_menu', 'urvenue_ws_options_page' );
function urvenue_ws_options_page(){ 
    global $urvenue_ws_uvwp_url;

    add_menu_page(
        "UrVenue",
        "UrVenue",
        "administrator",
        "urvenue_opts",
        // "uvwp_admin_page",
        "urvenue_ws_admin_page", 
        $urvenue_ws_uvwp_url . "/assets/icons/uvlogo-options.png",
        80
    );
}

function urvenue_ws_include_adminstyles(){
    global $urvenue_ws_uvwp_url, $urvenue_ws_coreurl, $urvenue_ws_assetsversion;

    wp_register_style('uvwp-admin', $urvenue_ws_uvwp_url . '/assets/css/uvwp-admin.css', false, $urvenue_ws_assetsversion);
	wp_enqueue_style('uvwp-admin');

	wp_register_style('urvenue-ws-systembase', $urvenue_ws_coreurl . '/assets/css/system-base.css', false, $urvenue_ws_assetsversion);
	wp_enqueue_style('urvenue-ws-systembase');

    wp_register_style('urvenue-ws-system', $urvenue_ws_coreurl . '/assets/css/system.css', false, $urvenue_ws_assetsversion);
	wp_enqueue_style('urvenue-ws-system');

    wp_register_style('urvenue-ws-icons', $urvenue_ws_coreurl . '/assets/css/uwsicons.css', false, $urvenue_ws_assetsversion);
	wp_enqueue_style('urvenue-ws-icons');

    wp_register_style('flatpickr', $urvenue_ws_coreurl . '/assets/css/flatpickr.min.css', false, $urvenue_ws_assetsversion);
    wp_enqueue_style('flatpickr');

}
add_action('admin_enqueue_scripts', 'urvenue_ws_include_adminstyles'); 

function urvenue_ws_include_adminscripts(){
    global $urvenue_ws_coreurl;

    wp_enqueue_style( 'wp-color-picker' );

    wp_register_script('jquery-validate', $urvenue_ws_coreurl . '/assets/js/jquery.validate.min.js', array('jquery'), 1, true);
    wp_enqueue_script('jquery-validate');

    wp_register_script('urvenue-ws-admin', $urvenue_ws_coreurl . '/assets/js/admin.js', array('jquery', 'wp-color-picker'), 1, true);
    wp_enqueue_script('urvenue-ws-admin');

    wp_register_script('flatpickr', $urvenue_ws_coreurl . '/assets/js/flatpickr.min.js', false, 1, true);
    wp_enqueue_script('flatpickr');
}
add_action('admin_enqueue_scripts', 'urvenue_ws_include_adminscripts'); 

function urvenue_ws_admin_page(){ 
    global $urvenue_ws_uvs_path, $urvenue_ws_libexits, $urvenue_ws_uvwp_path, $urvenue_ws_core_lib, $urvenue_ws_url, $urvenue_ws_coreurl, $urvenue_ws_adm_admin_lib, $urvenue_ws_adm_core_version, $urvenue_ws_feeds_path;
        
    include_once($urvenue_ws_uvwp_path . "/admin/admin-page.php");
}

/* Front */
//Include front styles
function urvenue_ws_include_styles(){ 
    global $urvenue_ws_coreurl, $urvenue_ws_assetsversion, $urvenue_ws_core_lib;
	
    //Global Styles, included on all pages
    wp_register_style('urvenue-ws-core-styles', $urvenue_ws_coreurl . '/assets/css/uwscore.css', false, $urvenue_ws_assetsversion);
    wp_enqueue_style('urvenue-ws-core-styles');
    wp_add_inline_style('urvenue-ws-core-styles', urvenue_ws_get_css_vars());

    wp_register_style('urvenue-ws-icons-styles', $urvenue_ws_coreurl . '/assets/css/uwsicons.css', false, $urvenue_ws_assetsversion);
    wp_enqueue_style('urvenue-ws-icons-styles');

    //Specific pages styles
    wp_register_style('urvenue-ws-events-styles', $urvenue_ws_coreurl . '/assets/css/events.css', false, $urvenue_ws_assetsversion);
    wp_register_style('urvenue-ws-event-styles', $urvenue_ws_coreurl . '/assets/css/event.css', false, $urvenue_ws_assetsversion);
    wp_register_style('litepicker', $urvenue_ws_coreurl . '/assets/css/litepicker.min.css', false, 1);
    wp_register_style('nouislider', $urvenue_ws_coreurl . '/assets/css/nouislider.min.css', false, 1);
    wp_register_style('urvenue-ws-inventory-styles', $urvenue_ws_coreurl . '/assets/css/uwsinventory.css', false, $urvenue_ws_assetsversion);
    wp_register_style('urvenue-ws-experiences-styles', $urvenue_ws_coreurl . '/assets/css/experiences.css', false, $urvenue_ws_assetsversion);
    wp_register_style('urvenue-ws-invitempage-styles', $urvenue_ws_coreurl . '/assets/css/invitempage.css', false, $urvenue_ws_assetsversion);
    wp_register_style('urvenue-ws-itinerary-styles', $urvenue_ws_coreurl . '/assets/css/itinerary.css', false, $urvenue_ws_assetsversion);
    wp_register_style('urvenue-ws-map-styles', $urvenue_ws_coreurl . '/assets/css/map.css', false, $urvenue_ws_assetsversion);
    wp_register_style('urvenue-ws-reservations-styles', $urvenue_ws_coreurl . '/assets/css/reservations.css', false, $urvenue_ws_assetsversion);
    wp_register_style('urvenue-ws-apireq', $urvenue_ws_coreurl . '/assets/css/apireq.css', false, $urvenue_ws_assetsversion);
    wp_register_style('perfect-scrollbar', $urvenue_ws_coreurl . '/assets/css/perfect-scrollbar.css', false, 1);
    wp_register_style('urvenue-ws-memberships', $urvenue_ws_coreurl . '/assets/css/memberships.css', false, $urvenue_ws_assetsversion);
    wp_register_style('urvenue-ws-packages', $urvenue_ws_coreurl . '/assets/css/packages.css', false, $urvenue_ws_assetsversion);

    if(is_array($urvenue_ws_core_lib) and $urvenue_ws_core_lib["pages"]["events"] and is_page($urvenue_ws_core_lib["pages"]["events"])){//pre include events page styles
        //include styles
        wp_enqueue_style('urvenue-ws-events-styles');
        wp_enqueue_style('litepicker');
    }
    else if(is_array($urvenue_ws_core_lib) and $urvenue_ws_core_lib["pages"]["singleevent"]  and is_page($urvenue_ws_core_lib["pages"]["singleevent"])){// pre include event page styles
        //include styles
        wp_enqueue_style('urvenue-ws-event-styles');
        wp_enqueue_style('urvenue-ws-inventory-styles');
        wp_enqueue_style('litepicker');
    }
    else if(is_array($urvenue_ws_core_lib) and $urvenue_ws_core_lib["pages"]["map"]  and is_page($urvenue_ws_core_lib["pages"]["map"])){// pre include map page styles
        //include styles
        wp_enqueue_style('urvenue-ws-map-styles');
        wp_enqueue_style('urvenue-ws-inventory-styles');
        wp_enqueue_style('litepicker');
        wp_enqueue_style('nouislider');
    }
}
add_action('wp_enqueue_scripts', 'urvenue_ws_include_styles'); 

//Add scripts to footer
function urvenue_ws_add_footer_scripts(){
    $uvfooterproxy = urvenue_ws_get_proxy_script(); 

    echo wp_kses( $uvfooterproxy, array() );
}
// add_action('wp_footer', 'uwscore_add_footer_scripts');
add_action('wp_footer', 'urvenue_ws_add_footer_scripts'); 

//Include front scripts
function urvenue_ws_include_scripts(){ 
    global $urvenue_ws_coreurl, $urvenue_ws_assetsversion, $urvenue_ws_core_lib;

    //Global Styles, included on all pages
	wp_register_script('urvenue-ws-core-scripts', $urvenue_ws_coreurl . '/assets/js/uwscore.js', false, $urvenue_ws_assetsversion, true);

    //Specific pages scrips
    wp_register_script('urvenue-ws-events-scripts', $urvenue_ws_coreurl . '/assets/js/events.js', false, $urvenue_ws_assetsversion, true);
    wp_localize_script('urvenue-ws-events-scripts', 'urvenue_ws_events_vars', array(
        'targetNonce' => wp_create_nonce('urvenue_ws_events'),
    ));

    wp_register_script('litepicker', $urvenue_ws_coreurl . '/assets/js/litepicker.min.js', false, 1, true);
    wp_register_script('nouislider', $urvenue_ws_coreurl . '/assets/js/nouislider.min.js', false, 1, true);
    wp_register_script('hammer', $urvenue_ws_coreurl . '/assets/js/hammer.min.js', false, 1, true);

    wp_register_script('urvenue-ws-inventory-scripts', $urvenue_ws_coreurl . '/assets/js/uwsinventory.js', false, $urvenue_ws_assetsversion, true);
    wp_localize_script('urvenue-ws-inventory-scripts', 'urvenue_ws_inventory_vars', array(
        'targetNonce' => wp_create_nonce('urvenue_ws_inventory'),
    ));

    wp_register_script('urvenue-ws-experiences-scripts', $urvenue_ws_coreurl . '/assets/js/experiences.js', false, $urvenue_ws_assetsversion, true);
    wp_localize_script('urvenue-ws-experiences-scripts', 'urvenue_ws_experiences_vars', array(
        'targetNonce' => wp_create_nonce('urvenue_ws_experiences'),
    ));

    wp_register_script('urvenue-ws-invitempage-scripts', $urvenue_ws_coreurl . '/assets/js/invitempage.js', false, $urvenue_ws_assetsversion, true);

	wp_register_script('urvenue-ws-itinerary-scripts', $urvenue_ws_coreurl . '/assets/js/itinerary.js', false, $urvenue_ws_assetsversion, true);
    wp_localize_script('urvenue-ws-itinerary-scripts', 'urvenue_ws_itinerary_vars', array(
        'targetNonce' => wp_create_nonce('urvenue_ws_itinerary'),
    ));

    wp_register_script('urvenue-ws-map-scripts', $urvenue_ws_coreurl . '/assets/js/map.js', false, $urvenue_ws_assetsversion, true);
    wp_localize_script('urvenue-ws-map-scripts', 'urvenue_ws_map_vars', array(
        'targetNonce' => wp_create_nonce('urvenue_ws_map'),
    ));

    wp_register_script('urvenue-ws-reservations-scripts', $urvenue_ws_coreurl . '/assets/js/reservations.js', false, $urvenue_ws_assetsversion, true);
    wp_localize_script('urvenue-ws-reservations-scripts', 'urvenue_ws_reservations_vars', array(
        'targetNonce' => wp_create_nonce('urvenue_ws_reservations'),
    ));

    wp_register_script('urvenue-ws-hooks-ga4dl', $urvenue_ws_coreurl . '/assets/js/hooks-ga4dl.js', false, 1, true);
    wp_register_script('urvenue-ws-mapzoom', $urvenue_ws_coreurl . '/assets/js/mapzoom.min.js', false, 1, true);
    wp_register_script('urvenue-ws-mapthumbview', $urvenue_ws_coreurl . '/assets/js/mapthumbview.js', false, $urvenue_ws_assetsversion, true);
    wp_register_script('urvenue-ws-apireq', $urvenue_ws_coreurl . '/assets/js/apireq.js', false, $urvenue_ws_assetsversion, true);
    wp_register_script('perfect-scrollbar', $urvenue_ws_coreurl . '/assets/js/perfect-scrollbar.min.js', false, 1, true);
    wp_register_script('pristine', $urvenue_ws_coreurl . '/assets/js/validate.min.js', false, 1, true);
    wp_register_script('urvenue-ws-memberships', $urvenue_ws_coreurl . '/assets/js/memberships.js', false, $urvenue_ws_assetsversion, true);

    wp_register_script('urvenue-ws-packages', $urvenue_ws_coreurl . '/assets/js/packages.js', false, $urvenue_ws_assetsversion, true);
    wp_localize_script('urvenue-ws-packages', 'urvenue_ws_packages_vars', array(
        'targetNonce' => wp_create_nonce('urvenue_ws_packages'),
    ));

    wp_enqueue_script('urvenue-ws-core-scripts');
    wp_enqueue_script('urvenue-ws-inventory-scripts');

    if(is_array($urvenue_ws_core_lib) and $urvenue_ws_core_lib["pages"]["events"] and is_page($urvenue_ws_core_lib["pages"]["events"])){//pre include events page scripts
        //include scripts
        wp_enqueue_script('urvenue-ws-events-scripts');
        wp_enqueue_script('litepicker');
    }
    else if(is_array($urvenue_ws_core_lib) and $urvenue_ws_core_lib["pages"]["singleevent"]  and is_page($urvenue_ws_core_lib["pages"]["singleevent"])){// pre include event page scripts
        //include scripts
	    wp_enqueue_script('urvenue-ws-core-scripts');
	    wp_enqueue_script('urvenue-ws-inventory-scripts');
        wp_enqueue_script('pristine');
        wp_enqueue_script('litepicker');
        wp_enqueue_script('urvenue-ws-events-scripts');
    }
    else if(is_array($urvenue_ws_core_lib) and $urvenue_ws_core_lib["pages"]["map"]  and is_page($urvenue_ws_core_lib["pages"]["map"])){// pre include map page scripts
        //include scripts
        wp_enqueue_script('urvenue-ws-inventory-scripts');
        wp_enqueue_script('litepicker');
        wp_enqueue_script('nouislider');
        wp_enqueue_script('hammer');
        wp_enqueue_script('urvenue-ws-mapzoom');
        wp_enqueue_script('urvenue-ws-mapthumbview');
        wp_enqueue_script('pristine');
    }
}
add_action("wp_enqueue_scripts", "urvenue_ws_include_scripts"); 

//Add proxy files
add_action('wp_ajax_nopriv_urvenue_ws_proxy', 'urvenue_ws_proxy');
add_action('wp_ajax_urvenue_ws_proxy', 'urvenue_ws_proxy');
// function uvwp_proxy(){
function urvenue_ws_proxy(){ 
	global $urvenue_ws_corepath, $urvenue_ws_uvs_path, $urvenue_ws_adm_admin_feeds, $urvenue_ws_core_lib, $urvenue_ws_adm_envicode;
	
	include_once($urvenue_ws_corepath . "/uvcore.proxy.php");
	
	die();
}

//Event URL and Vars
add_filter('query_vars', 'urvenue_ws_add_query_vars');
function urvenue_ws_add_query_vars($query_vars){ 
    $query_vars[] = 'eventcode';
    $query_vars[] = 'mastercode';

    return $query_vars;
}

 
// moved flush and nonce check to wp_ajax_urvenue_ws_proxy since lifecycle for init is too early and wont let nonce check work properly
add_action('wp_ajax_urvenue_ws_proxy', 'urvenue_ws_ajax_handler'); 
function urvenue_ws_ajax_handler() { 
    if(!isset($_POST['uvsp_adminsave_nonce']) ||
    !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['uvsp_adminsave_nonce'] ) ), 'uvsp_adminsave_action')) {
        wp_send_json_error(['message' => 'Invalid nonce'], 403);
    }

    if(isset($_POST['uvaction']) && sanitize_text_field( wp_unslash( $_POST['uvaction'] ) ) === 'uvsp_adminsave') {
        update_option('urvenue_ws_flush_pending', 1); 
    }

    wp_send_json_success();
}

//Pages URLs rewrite rules
add_action('init', function(){
	global $urvenue_ws_core_lib;

    if(get_option('urvenue_ws_flush_pending')){
        flush_rewrite_rules();
        update_option( 'urvenue_ws_flush_pending', 0); 
    }
	
    //Event Page Rewrite
    $uvsingleeventpageid = $urvenue_ws_core_lib["pages"]["singleevent"];
    if($uvsingleeventpageid){
        $uveventpagedir = get_permalink($uvsingleeventpageid);
        $uveventpagedir = str_replace( home_url() . "/", "", $uveventpagedir );
        $uveventpagedir = rtrim($uveventpagedir, "/");

        add_rewrite_rule('^' . $uveventpagedir . '/(eve|EVE[^/]*)-?([^/]*)/?','index.php?page_id=' . $uvsingleeventpageid . '&eventcode=$matches[1]', 'top');
    }

    //Map Page Rewrite
    $uvmappageid = $urvenue_ws_core_lib["pages"]["map"];
    if($uvmappageid){
	    $uvmappagedir = get_permalink($uvmappageid);
        $uvmappagedir = str_replace( home_url() . "/", "", $uvmappagedir );
        $uvmappagedir = rtrim($uvmappagedir, "/");

        add_rewrite_rule('^' . $uvmappagedir . '/(eve|EVE[^/]*)-?([^/]*)/?','index.php?page_id=' . $uvmappageid . '&eventcode=$matches[1]', 'top');
    }

    //Item Page Rewrite
    $uvitempageid = $urvenue_ws_core_lib["pages"]["itempage"];
    if($uvitempageid){
	    $uvitempagedir = get_permalink($uvitempageid);
        $uvitempagedir = str_replace( home_url() . "/", "", $uvitempagedir );
        $uvitempagedir = rtrim($uvitempagedir, "/");

        add_rewrite_rule('^' . $uvitempagedir . '/mc|MC([^/]*)-?([^/]*)/?','index.php?page_id=' . $uvitempageid . '&mastercode=$matches[1]', 'top');
    }

    //Check if event pages map exists
    if(isset($urvenue_ws_core_lib["eventpagesmap"]) and is_array($urvenue_ws_core_lib["eventpagesmap"])){
        foreach($urvenue_ws_core_lib["eventpagesmap"] as $uvvenueeventpage){
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
function urvenue_ws_is_page_singleevent(){ 
    global $urvenue_ws_core_lib;

    $uvissingleevent = 0;

    if(isset($urvenue_ws_core_lib["eventpagesmap"]) and is_array($urvenue_ws_core_lib["eventpagesmap"])){
        foreach($urvenue_ws_core_lib["eventpagesmap"] as $uvvenuecode => $uvvenueeventpage){
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
add_filter('wpseo_title', 'urvenue_ws_set_meta_title', 10, 2 );
add_filter('wpseo_opengraph_title', 'urvenue_ws_set_meta_title', 10, 2 );
add_filter('wpseo_twitter_title', 'urvenue_ws_set_meta_title', 10, 2 );

// Rank Math
add_filter('rank_math/frontend/title', 'urvenue_ws_set_meta_title', 10, 2 );
add_filter('rank_math/opengraph/facebook/title', 'urvenue_ws_set_meta_title', 10, 2 );
add_filter('rank_math/opengraph/twitter/title', 'urvenue_ws_set_meta_title', 10, 2 ); 

function urvenue_ws_set_meta_title($title){ 
	global $urvenue_ws_core_lib;

    $uviseventsingle = 0;
    $uvsingleeventpageid = $urvenue_ws_core_lib["pages"]["singleevent"];

    if($uvsingleeventpageid and is_page($uvsingleeventpageid))
        $uviseventsingle = 1;
    else
        $uviseventsingle = urvenue_ws_is_page_singleevent(); 
		
	if($uviseventsingle and $urvenue_ws_core_lib["seo"]["enabletags"]){
        $uveventseo = urvenue_ws_get_event_seo(); 

        if(is_array($uveventseo) and $uveventseo["title"])
            $title = $uveventseo["title"];
	}
    
    return $title;
}

add_filter('wpseo_opengraph_type', 'urvenue_ws_set_meta_type', 10, 2 );
function urvenue_ws_set_meta_type($type){ 
	global $urvenue_ws_core_lib;

    $uviseventsingle = 0;
    $uvsingleeventpageid = $urvenue_ws_core_lib["pages"]["singleevent"];

    if($uvsingleeventpageid and is_page($uvsingleeventpageid))
        $uviseventsingle = 1;
    else
        $uviseventsingle = urvenue_ws_is_page_singleevent(); 
		
	if($uviseventsingle and $urvenue_ws_core_lib["seo"]["enabletags"]){
        $type = "event";
	}
    
    return $type;
}

//Edit Page Description When Page is Dynamic
// Yoast
add_filter('wpseo_metadesc', 'urvenue_ws_set_meta_description', 10, 2 );
add_filter('wpseo_opengraph_desc', 'urvenue_ws_set_meta_description', 10, 2 ); 

// Rank Math
add_filter('rank_math/frontend/description', 'urvenue_ws_set_meta_description', 10, 2 );
add_filter('rank_math/opengraph/facebook/description', 'urvenue_ws_set_meta_description', 10, 2 ); 

function urvenue_ws_set_meta_description($description){ 
	global $urvenue_ws_core_lib;

    $uviseventsingle = 0;
    $uvsingleeventpageid = $urvenue_ws_core_lib["pages"]["singleevent"];

    if($uvsingleeventpageid and is_page($uvsingleeventpageid))
        $uviseventsingle = 1;
    else
        $uviseventsingle = urvenue_ws_is_page_singleevent();
	
	if($uviseventsingle and $urvenue_ws_core_lib["seo"]["enabletags"]){
        $uveventseo = urvenue_ws_get_event_seo(); 

        if(is_array($uveventseo) and $uveventseo["description"])
            $description = $uveventseo["description"];
    }
    
    return $description;
}

//Edit Page Image When Page is Dynamic
// Yoast
add_filter('wpseo_opengraph_image', 'urvenue_ws_set_meta_image', 10, 2 );
add_filter('wpseo_twitter_image', 'urvenue_ws_set_meta_image', 10, 2 ); 

// Rank Math
add_filter('rank_math/opengraph/facebook/image', 'urvenue_ws_set_meta_image', 10, 2 );
add_filter('rank_math/opengraph/twitter/image', 'urvenue_ws_set_meta_image', 10, 2 ); 

function urvenue_ws_set_meta_image($image){ 
	global $urvenue_ws_core_lib;
	
    $uviseventsingle = 0;
	$uvsingleeventpageid = $urvenue_ws_core_lib["pages"]["singleevent"];

    if($uvsingleeventpageid and is_page($uvsingleeventpageid))
        $uviseventsingle = 1;
    else
        $uviseventsingle = urvenue_ws_is_page_singleevent(); 
	
	if($uviseventsingle and $urvenue_ws_core_lib["seo"]["enabletags"]){
        $uveventseo = urvenue_ws_get_event_seo(); 

        if(is_array($uveventseo) and $uveventseo["image"])
            $image = $uveventseo["image"];
    }
    
    return $image;
}

//Edit Page URL When Page is Dynamic
// Yoast
add_filter('wpseo_opengraph_url', 'urvenue_ws_set_page_url', 10, 2 ); 

// Rank Math
add_filter('rank_math/opengraph/facebook/url', 'urvenue_ws_set_page_url', 10, 2 ); 

// function uwswpplug_set_page_url($pageurl){
function urvenue_ws_set_page_url($pageurl){ 
    global $urvenue_ws_core_lib;
	
    $uviseventsingle = 0;
	$uvsingleeventpageid = $urvenue_ws_core_lib["pages"]["singleevent"];

    if($uvsingleeventpageid and is_page($uvsingleeventpageid))
        $uviseventsingle = 1;
    else
        $uviseventsingle = urvenue_ws_is_page_singleevent(); 
	
	if($uviseventsingle and $urvenue_ws_core_lib["seo"]["enabletags"]){
        $uveventseo = urvenue_ws_get_event_seo(); 

        if(is_array($uveventseo) and $uveventseo["url"])
            $pageurl = $uveventseo["url"];
    }
    
    return $pageurl;
}

// Enable verbose feed debugging from the plugin's System Status panel (Debug Mode toggle).
add_action('init', 'urvenue_ws_check_enable_debug');
function urvenue_ws_check_enable_debug(){
    global $urvenue_ws_feeds_debug;

    $urvenue_ws_feeds_debug = 0;

    if ( defined('URVENUE_WS_DEBUG') && URVENUE_WS_DEBUG ) {
        $urvenue_ws_feeds_debug = 1;
        return;
    }

    if ( ! is_user_logged_in() ) {
        return;
    }

    $urvenue_ws_lib = json_decode( get_option('urvenue_ws_uvcore_lib'), true );
    if ( is_array($urvenue_ws_lib) && ! empty($urvenue_ws_lib['system']['debug']) && $urvenue_ws_lib['system']['debug'] !== '0' ) {
        $urvenue_ws_feeds_debug = 1;
    }
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
    add_filter('wpseo_sitemap_index', 'urvenue_ws_add_sitemap_events'); 
    
    add_action('init', function() {
        add_action("wpseo_do_sitemap_events", 'urvenue_ws_sitemap_events'); 
    });
    
    add_filter('wpseo_canonical', 'urvenue_ws_seo_canonical'); 
}
if (class_exists('RankMath')) {
    add_filter('rank_math/frontend/canonical', 'urvenue_ws_seo_canonical'); 
}

/**
 * Adds a filter to modify the sitemap index in Yoast SEO plugin.
 *
 * @param string $sitemap_index The original sitemap index.
 * @return string The modified sitemap index.
 */
function urvenue_ws_add_sitemap_events($sitemap_index) { 
    $uveventssmp = '';
    $uvlastmod = gmdate('c', time()); 

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
function urvenue_ws_sitemap_events() { 
    global $wpseo_sitemaps; // External global owned by the Yoast SEO plugin, not this plugin; do not prefix.
    
    $uveventsmp = '';
    $uvevents = urvenue_ws_get_events(); 
    
    if(is_array($uvevents)) {
        $uvlastmod = gmdate('c', time()); 
        
        foreach($uvevents as $uvevent) {
            $urvenue_ws_eventurl = $uvevent["event-url"];
            $uveventsmp .= "<url><loc>$urvenue_ws_eventurl</loc><lastmod>$uvlastmod</lastmod></url>";
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
function urvenue_ws_seo_canonical($uvcanonical) { 
    global $urvenue_ws_core_lib;
	
    $uviseventsingle = 0;
	$uvsingleeventpageid = $urvenue_ws_core_lib["pages"]["singleevent"];

    if($uvsingleeventpageid and is_page($uvsingleeventpageid))
        $uviseventsingle = 1;
    else
        $uviseventsingle = urvenue_ws_is_page_singleevent(); 
	
	if($uviseventsingle and $urvenue_ws_core_lib["seo"]["enabletags"]){
        $uveventseo = urvenue_ws_get_event_seo(); 

        if(is_array($uveventseo) and $uveventseo["url"])
            $uvcanonical = $uveventseo["url"];
    }
    
    return $uvcanonical;
}