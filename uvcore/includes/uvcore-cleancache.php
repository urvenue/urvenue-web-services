<?php

if(!is_array($uws_core_lib) && !is_array($uws_feeds_lib)) exit;

$uvwpecreds = $uws_core_lib["cache"];

// If cacheword exists, update it
update_option('cacheword', 'uv' . uniqid());

// Custom WP Engine Clear Cache
function uvclear_wpengine_cache() {
    global $uvvenuecodes, $uvwpecreds, $uws_core_lib, $uws_feeds_lib, $uws_today;

    $uvdefaultmessage = 'UrVenue local cache cleared.';
    $uws_core_lib["system"]["cache-word"] = get_option('cacheword');

    // UV FEED
    // Dates
    $uvlatestdate = uws_get_events_endinit_date("Y-m-d", $uws_today);
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

	$fields = json_encode(array( 'type' => $cachetoclear ));

	$uvwpe_curl = curl_init();

	curl_setopt_array($uvwpe_curl, array(
		CURLOPT_URL => $api_url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $fields,
		CURLOPT_HTTPHEADER => $headers,
	));
	$response = curl_exec($uvwpe_curl);
	$httpcode = curl_getinfo($uvwpe_curl, CURLINFO_HTTP_CODE);

	$uvresponse = json_decode($response, true);

	$acceptedHTTPCodes = array(200, 201, 202, 203, 204, 205, 206);
    $status = 0;
    $uvresponsemsg = $message = $reason = '';
    $cachecleared = 'uvfeeds';

    // Check for errors
    if (curl_errno($uvwpe_curl)) {
        echo 'Error: ' . curl_error($uvwpe_curl);
    } else if ($uvresponse && !$install_id) {
        $status = 1;
        $message = (!uvs_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. WP Engine Cache clearing failed, please set the API fields.';
        $reason = (!uvs_is_hosted_on_wpengine()) && 'Missed a WP Engine field, HTTP Status: ' . $httpcode;
    } else if(!$uvresponse && in_array($httpcode, $acceptedHTTPCodes)) {
            $status = 1;
            $cachecleared = 'uvfeeds, wpcache';
            $message = 'UrVenue local cache and WP Engine cache cleared';
            $reason = 'HTTP Status: ' . $httpcode;
    } else if($uvresponse && $install_id) {
        if($httpcode === 429) { // Too many requests
            $status = 1;
            $message = (!uvs_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. We can’t clear WP Engine cache, try again in some minutes';
            $reason = $uvresponse['message'];
		} else if($httpcode === 400) { // Bad request or invalid parameters
            $message = $uvresponse['errors'][0]['message'];
        } else if($httpcode === 401 || $httpcode === 403) { // Invalid API credentials
            $status = 1;
            $message = (!uvs_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. We can’t clear WP Engine cache, please check that the WP Engine API credentials are correct';
            $reason = $uvresponse['message'];
        } else if($httpcode === 404) { // Installation ID not found
            $status = 1;
            $message = (!uvs_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. We can’t clear WP Engine cache, please check that the WP Engine Installation ID is correct';
            $reason = 'Site ' . $uvresponse['message'];
        } else {
            $status = 1;
            $message = (!uvs_is_hosted_on_wpengine()) ? $uvdefaultmessage : 'UrVenue local cache cleared. WP Engine Cache clearing failed';
            $reason = 'Unknown error, HTTP Status: ' . $httpcode;
            uvwp_send_json_error($status, $message, $reason);
        }
    }
    $status = ($status) ? 'success' : 'error';
    $uvresponsemsg = array('uv' => array('success' => array(
                                            'status' => $status,
                                            'message' => $message,
                                            'reason' => $reason,
                                            'cache' => $cachecleared,
                                        )));

    $uvdata = json_encode($uvresponsemsg);
    header('Content-Type: application/json; charset=utf-8');
    echo($uvdata);
    curl_close($uvwpe_curl);
}

function custom_template_redirect() {
    $uv_url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if ($uv_url_path == '/apis/uvclearcache/') {
        clear_cache_endpoint_callback();
    }
}

add_action('template_redirect', 'custom_template_redirect');

function clear_cache_endpoint_callback() {
    global $uvwpecreds;

    if (isset($_GET['apikey']) && $_GET['apikey'] == $uvwpecreds['cacheapikey']) {
        uvclear_wpengine_cache();
        
        if(function_exists('uws_clean_cached_feeds'))
            uws_clean_cached_feeds();
        exit;
    } else {
        uvwp_send_json_error(0, 'Invalid API Key', 'unauthorized');
        exit;
    }
}

function uvwp_send_json_error($status, $message, $reason) {
    $status = ($status) ? 'success' : 'error';
    $uvresponsemsg = array('uv' => array(
        'success' => array(
            'status' => $status,
            'message' => $message,
            'reason' => $reason,
        )));
    header('Content-Type: application/json; charset=utf-8');
    echo(json_encode($uvresponsemsg));
}