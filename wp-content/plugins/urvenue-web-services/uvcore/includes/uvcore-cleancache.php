<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!is_array($urvenue_ws_core_lib) && !is_array($urvenue_ws_feeds_lib)) exit;

$urvenue_ws_wpecreds = $urvenue_ws_core_lib["cache"];

// If cacheword exists, update it
update_option('urvenue_ws_cacheword', 'uv' . uniqid());

// Custom WP Engine Clear Cache
function urvenue_ws_clear_wpengine_cache() {
    global $urvenue_ws_venuecodes, $urvenue_ws_wpecreds, $urvenue_ws_core_lib, $urvenue_ws_feeds_lib, $urvenue_ws_today;

    $uvdefaultmessage = 'UrVenue local cache cleared.';
    $urvenue_ws_core_lib["system"]["cache-word"] = get_option('urvenue_ws_cacheword');

    // UV FEED
    // Dates
    $uvlatestdate = urvenue_ws_get_events_endinit_date("Y-m-d", $urvenue_ws_today);
    $uvfeedtodate = gmdate("Y-m-d", strtotime($uvlatestdate . " +7 days"));
    $uvfeeddates = "fromdate={$urvenue_ws_today}&todate={$uvfeedtodate}";

    // Venue codes
    $uvvenueslist = $urvenue_ws_core_lib['venues'];
    $uvvenueslength = (is_array($uvvenueslist)) ? count($uvvenueslist) : 0;
    $urvenue_ws_venuecodes = ($uvvenueslength > 1) ? implode(',', array_column($uvvenueslist, 'venuecode')) : reset($urvenue_ws_core_lib['venues'])['venuecode'];

    // UV Feed Params
    $uvfeedparams = $uvfeeddates . "&venuecodes={$urvenue_ws_venuecodes}&cacheword=" . $urvenue_ws_core_lib["system"]["cache-word"];

    // Feed URL
    $uvfeedurl = str_replace('{params}', $uvfeedparams, $urvenue_ws_feeds_lib['urquery']['url']);

    // WP Engine API
    $uvwpengineuser = $urvenue_ws_wpecreds['username'];
	$uvwpenginepwd = $urvenue_ws_wpecreds['password'];
    $install_id = $urvenue_ws_wpecreds['wpeinst'];
	$uvwpenginecreds = $uvwpengineuser . ":" . $uvwpenginepwd;

    // Set the API endpoint URL
    $api_url = "https://api.wpengineapi.com/v1/installs/{$install_id}/purge_cache";

    // Prepare the request headers
    $headers = array(
		'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode($uvwpenginecreds),
    );

    // page, object, cdn
	$cachetoclear = 'page';

	$fields = wp_json_encode(array( 'type' => $cachetoclear ));

    $uvwpe_response = wp_remote_post($api_url, array(
		'headers' => array(
			'Content-Type' => 'application/json',
			'Authorization' => 'Basic ' . base64_encode($uvwpenginecreds),
		),
		'body' => $fields,
		'timeout' => 60,
		'redirection' => 10,
	));

    $response = wp_remote_retrieve_body($uvwpe_response);
	$httpcode = wp_remote_retrieve_response_code($uvwpe_response);
    

	$uvresponse = json_decode($response, true);

	$acceptedHTTPCodes = array(200, 201, 202, 203, 204, 205, 206);
    $status = 0;
    $uvresponsemsg = $message = $reason = '';
    $cachecleared = 'uvfeeds';

    // Check for errors

    if (is_wp_error($uvwpe_response)) {
        echo 'Error: ' . esc_html( $uvwpe_response->get_error_message() );

    } else if ($uvresponse && !$install_id) {
        $status = 1;
        $message = (!urvenue_ws_adm_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. WP Engine Cache clearing failed, please set the API fields.';
        $reason = (!urvenue_ws_adm_is_hosted_on_wpengine()) && 'Missed a WP Engine field, HTTP Status: ' . $httpcode;
    } else if(!$uvresponse && in_array($httpcode, $acceptedHTTPCodes)) {
            $status = 1;
            $cachecleared = 'uvfeeds, wpcache';
            $message = 'UrVenue local cache and WP Engine cache cleared';
            $reason = 'HTTP Status: ' . $httpcode;
    } else if($uvresponse && $install_id) {
        if($httpcode === 429) { // Too many requests
            $status = 1;
            $message = (!urvenue_ws_adm_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. We can’t clear WP Engine cache, try again in some minutes';
            $reason = $uvresponse['message'];
		} else if($httpcode === 400) { // Bad request or invalid parameters
            $message = $uvresponse['errors'][0]['message'];
        } else if($httpcode === 401 || $httpcode === 403) { // Invalid API credentials
            $status = 1;
            $message = (!urvenue_ws_adm_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. We can’t clear WP Engine cache, please check that the WP Engine API credentials are correct';
            $reason = $uvresponse['message'];
        } else if($httpcode === 404) { // Installation ID not found
            $status = 1;
            $message = (!urvenue_ws_adm_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. We can’t clear WP Engine cache, please check that the WP Engine Installation ID is correct';
            $reason = 'Site ' . $uvresponse['message'];
        } else {
            $status = 1;
            $message = (!urvenue_ws_adm_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. WP Engine Cache clearing failed';
            $reason = 'Unknown error, HTTP Status: ' . $httpcode;
            urvenue_ws_send_json_error($status, $message, $reason);
        }
    }
    $status = ($status) ? 'success' : 'error';
    $uvresponsemsg = array('uv' => array('success' => array(
                                            'status' => $status,
                                            'message' => $message,
                                            'reason' => $reason,
                                            'cache' => $cachecleared,
                                        )));

    $uvdata = wp_json_encode($uvresponsemsg);
    header('Content-Type: application/json; charset=utf-8');
    echo( $uvdata ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode()

}

function urvenue_ws_template_redirect() {
    $uv_url_path = wp_parse_url( sanitize_text_field( wp_unslash( isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '' ) ), PHP_URL_PATH );

    if ($uv_url_path == '/apis/uvclearcache/') {
        urvenue_ws_clear_cache_callback();
    }
}

add_action('template_redirect', 'urvenue_ws_template_redirect');

function urvenue_ws_clear_cache_callback() {
    global $urvenue_ws_wpecreds;

    if (isset($_GET['apikey']) && sanitize_text_field( wp_unslash( $_GET['apikey'] ) ) == $urvenue_ws_wpecreds['cacheapikey']) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- API key auth used instead of nonce for cache-clear endpoint
        urvenue_ws_clear_wpengine_cache();
        
        if(function_exists('urvenue_ws_clean_cached_feeds'))
            urvenue_ws_clean_cached_feeds();
        exit;
    } else {
        urvenue_ws_send_json_error(0, 'Invalid API Key', 'unauthorized');
        exit;
    }
}

function urvenue_ws_send_json_error($status, $message, $reason) {
    $status = ($status) ? 'success' : 'error';
    $uvresponsemsg = array('uv' => array(
        'success' => array(
            'status' => $status,
            'message' => $message,
            'reason' => $reason,
        )));
    header('Content-Type: application/json; charset=utf-8');
    echo wp_json_encode($uvresponsemsg);
}