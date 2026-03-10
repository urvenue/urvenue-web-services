<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!is_array($uws_core_lib) && !is_array($uws_feeds_lib)) exit;

$uvwpecreds = $uws_core_lib["cache"];

// If cacheword exists, update it
// update_option('cacheword', 'uv' . uniqid());
update_option('urvenue_ws_cacheword', 'uv' . uniqid()); // Axl UWS-7416

// Custom WP Engine Clear Cache
// function uvclear_wpengine_cache() {
function urvenue_ws_clear_wpengine_cache() { // Axl UWS-7416
    global $uvvenuecodes, $uvwpecreds, $uws_core_lib, $uws_feeds_lib, $uws_today;

    $uvdefaultmessage = 'UrVenue local cache cleared.';
    // $uws_core_lib["system"]["cache-word"] = get_option('cacheword');
    $uws_core_lib["system"]["cache-word"] = get_option('urvenue_ws_cacheword'); // Axl UWS-7416

    // UV FEED
    // Dates
    // $uvlatestdate = uws_get_events_endinit_date("Y-m-d", $uws_today);
    $uvlatestdate = urvenue_ws_get_events_endinit_date("Y-m-d", $uws_today); // Axl UWS-7416
    $uvfeedtodate = date("Y-m-d", strtotime($uvlatestdate . " +7 days"));
    $uvfeeddates = "fromdate={$uws_today}&todate={$uvfeedtodate}";

    // Venue codes
    $uvvenueslist = $uws_core_lib['venues'];
    $uvvenueslength = (is_array($uvvenueslist)) ? count($uvvenueslist) : 0;
    $uvvenuecodes = ($uvvenueslength > 1) ? implode(',', array_column($uvvenueslist, 'venuecode')) : reset($uws_core_lib['venues'])['venuecode'];

    // UV Feed Params
    $uvfeedparams = $uvfeeddates . "&venuecodes={$uvvenuecodes}&cacheword=" . $uws_core_lib["system"]["cache-word"];

    // Feed URL
    $uvfeedurl = str_replace('{params}', $uvfeedparams, $uws_feeds_lib['urquery']['url']);

    // WP Engine API
    $uvwpengineuser = $uvwpecreds['username'];
	$uvwpenginepwd = $uvwpecreds['password'];
    $install_id = $uvwpecreds['wpeinst'];
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

	// @Axl
	// $fields = json_encode(array( 'type' => $cachetoclear ));
	$fields = wp_json_encode(array( 'type' => $cachetoclear ));
	// @Axl End

    // TESTING @Axl
	// $uvwpe_curl = curl_init();

	// curl_setopt_array($uvwpe_curl, array(
	// 	CURLOPT_URL => $api_url,
	// 	CURLOPT_RETURNTRANSFER => true,
	// 	CURLOPT_ENCODING => '',
	// 	CURLOPT_MAXREDIRS => 10,
	// 	CURLOPT_TIMEOUT => 0,
	// 	CURLOPT_FOLLOWLOCATION => true,
	// 	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	// 	CURLOPT_CUSTOMREQUEST => 'POST',
	// 	CURLOPT_POSTFIELDS => $fields,
	// 	CURLOPT_HTTPHEADER => $headers,
	// ));

    // TESTING @Axl
    $uvwpe_response = wp_remote_post($api_url, array(
		'headers' => array(
			'Content-Type' => 'application/json',
			'Authorization' => 'Basic ' . base64_encode($uvwpenginecreds),
		),
		'body' => $fields,
		'timeout' => 60,
		'redirection' => 10,
	));

    // TESTING @Axl
	// $response = curl_exec($uvwpe_curl);
	// $httpcode = curl_getinfo($uvwpe_curl, CURLINFO_HTTP_CODE);

    // TESTING @Axl
    $response = wp_remote_retrieve_body($uvwpe_response);
	$httpcode = wp_remote_retrieve_response_code($uvwpe_response);
    

	$uvresponse = json_decode($response, true);

	$acceptedHTTPCodes = array(200, 201, 202, 203, 204, 205, 206);
    $status = 0;
    $uvresponsemsg = $message = $reason = '';
    $cachecleared = 'uvfeeds';

    // Check for errors

    // TESTING @Axl
    // if (curl_errno($uvwpe_curl)) {
    //     echo 'Error: ' . curl_error($uvwpe_curl);
    if (is_wp_error($uvwpe_response)) {
        echo 'Error: ' . $uvwpe_response->get_error_message();

    } else if ($uvresponse && !$install_id) {
        $status = 1;
        // $message = (!uvs_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. WP Engine Cache clearing failed, please set the API fields.';
        $message = (!urvenue_ws_adm_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. WP Engine Cache clearing failed, please set the API fields.'; // Axl UWS-7416
        // $reason = (!uvs_is_hosted_on_wpengine()) && 'Missed a WP Engine field, HTTP Status: ' . $httpcode;
        $reason = (!urvenue_ws_adm_is_hosted_on_wpengine()) && 'Missed a WP Engine field, HTTP Status: ' . $httpcode; // Axl UWS-7416
    } else if(!$uvresponse && in_array($httpcode, $acceptedHTTPCodes)) {
            $status = 1;
            $cachecleared = 'uvfeeds, wpcache';
            $message = 'UrVenue local cache and WP Engine cache cleared';
            $reason = 'HTTP Status: ' . $httpcode;
    } else if($uvresponse && $install_id) {
        if($httpcode === 429) { // Too many requests
            $status = 1;
            // $message = (!uvs_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. We can’t clear WP Engine cache, try again in some minutes';
            $message = (!urvenue_ws_adm_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. We can’t clear WP Engine cache, try again in some minutes'; // Axl UWS-7416
            $reason = $uvresponse['message'];
		} else if($httpcode === 400) { // Bad request or invalid parameters
            $message = $uvresponse['errors'][0]['message'];
        } else if($httpcode === 401 || $httpcode === 403) { // Invalid API credentials
            $status = 1;
            // $message = (!uvs_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. We can’t clear WP Engine cache, please check that the WP Engine API credentials are correct';
            $message = (!urvenue_ws_adm_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. We can’t clear WP Engine cache, please check that the WP Engine API credentials are correct'; // Axl UWS-7416
            $reason = $uvresponse['message'];
        } else if($httpcode === 404) { // Installation ID not found
            $status = 1;
            // $message = (!uvs_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. We can’t clear WP Engine cache, please check that the WP Engine Installation ID is correct';
            $message = (!urvenue_ws_adm_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. We can’t clear WP Engine cache, please check that the WP Engine Installation ID is correct'; // Axl UWS-7416
            $reason = 'Site ' . $uvresponse['message'];
        } else {
            $status = 1;
            // $message = (!uvs_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. WP Engine Cache clearing failed';
            $message = (!urvenue_ws_adm_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. WP Engine Cache clearing failed'; // Axl UWS-7416
            $reason = 'Unknown error, HTTP Status: ' . $httpcode;
            // uvwp_send_json_error($status, $message, $reason);
            urvenue_ws_send_json_error($status, $message, $reason); // Axl UWS-7416
        }
    }
    $status = ($status) ? 'success' : 'error';
    $uvresponsemsg = array('uv' => array('success' => array(
                                            'status' => $status,
                                            'message' => $message,
                                            'reason' => $reason,
                                            'cache' => $cachecleared,
                                        )));

    // @Axl
    // $uvdata = json_encode($uvresponsemsg);
    $uvdata = wp_json_encode($uvresponsemsg);
    // @Axl End
    header('Content-Type: application/json; charset=utf-8');
    echo($uvdata);

    // TESTING @Axl
    // curl_close($uvwpe_curl);
}

// function custom_template_redirect() {
function urvenue_ws_template_redirect() { // Axl UWS-7416
    // $uv_url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Axl UWS-7418
    $uv_url_path = parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ); // Axl UWS-7418

    if ($uv_url_path == '/apis/uvclearcache/') {
        // clear_cache_endpoint_callback();
        urvenue_ws_clear_cache_callback(); // Axl UWS-7416
    }
}

// add_action('template_redirect', 'custom_template_redirect');
add_action('template_redirect', 'urvenue_ws_template_redirect'); // Axl UWS-7416

// function clear_cache_endpoint_callback() {
function urvenue_ws_clear_cache_callback() { // Axl UWS-7416
    global $uvwpecreds;

    // if (isset($_GET['apikey']) && $_GET['apikey'] == $uvwpecreds['cacheapikey']) { // Axl UWS-7418
    if (isset($_GET['apikey']) && sanitize_text_field( wp_unslash( $_GET['apikey'] ) ) == $uvwpecreds['cacheapikey']) { // Axl UWS-7418
        // uvclear_wpengine_cache();
        urvenue_ws_clear_wpengine_cache(); // Axl UWS-7416
        
        // if(function_exists('uws_clean_cached_feeds'))
        if(function_exists('urvenue_ws_clean_cached_feeds')) // Axl UWS-7416
            // uws_clean_cached_feeds();
            urvenue_ws_clean_cached_feeds(); // Axl UWS-7416
        exit;
    } else {
        // uvwp_send_json_error(0, 'Invalid API Key', 'unauthorized');
        urvenue_ws_send_json_error(0, 'Invalid API Key', 'unauthorized'); // Axl UWS-7416
        exit;
    }
}

// function uvwp_send_json_error($status, $message, $reason) {
function urvenue_ws_send_json_error($status, $message, $reason) { // Axl UWS-7416
    $status = ($status) ? 'success' : 'error';
    $uvresponsemsg = array('uv' => array(
        'success' => array(
            'status' => $status,
            'message' => $message,
            'reason' => $reason,
        )));
    header('Content-Type: application/json; charset=utf-8');
    // @Axl
    // echo(json_encode($uvresponsemsg));
    echo wp_json_encode($uvresponsemsg);
    // @Axl End
}