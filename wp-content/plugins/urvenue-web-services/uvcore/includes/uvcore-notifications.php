<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Function to clear throttling for testing
function urvenue_ws_clear_notice_throttling($uvalerttype = 'noevents') {
    $uvthrottlekey = 'urvenue_ws_notice_' . preg_replace('/[^a-z0-9_]/i', '_', $uvalerttype);
    
    // Clear WordPress transient
    if (function_exists('delete_transient'))
        delete_transient($uvthrottlekey);
}

if(isset($_REQUEST['uwsclearthrottle'])) urvenue_ws_clear_notice_throttling(); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Debug utility URL parameter, no user-facing state change

// function uws_website_notices_send($uvnoticemsg = "", $uvnoticedetails = ""){
function urvenue_ws_website_notices_send($uvnoticemsg = "", $uvnoticedetails = ""){
    global $urvenue_ws_website_notices_types, $urvenue_ws_core_lib;

    $uvenablenotice = (isset($urvenue_ws_core_lib["notifications"]["enable"]) and $urvenue_ws_core_lib["notifications"]["enable"]) ? $urvenue_ws_core_lib["notifications"]["enable"] : 0;
    $uvwebhook = (isset($urvenue_ws_core_lib["notifications"]["webhook"]) and $urvenue_ws_core_lib["notifications"]["webhook"]) ? $urvenue_ws_core_lib["notifications"]["webhook"] : "";

    if($uvwebhook and $uvenablenotice){
        // Check if first parameter is a known alert type
        if (isset($urvenue_ws_website_notices_types[$uvnoticemsg])) {
            $uvnoticetype = $uvnoticemsg;
            
            // If second parameter is a processed message (string), use it
            // Otherwise use the template and replace {website_url}
            if(is_string($uvnoticedetails) && !empty($uvnoticedetails)){
                $uvnoticemsg = $uvnoticedetails;
            } else {
                $uvnoticemsg = isset($urvenue_ws_website_notices_types[$uvnoticemsg]['message_template']) 
                    ? $urvenue_ws_website_notices_types[$uvnoticemsg]['message_template'] 
                    : $urvenue_ws_website_notices_types['default']['message_template'];
                $uvsiteurl = function_exists('get_site_url') ? get_site_url() : sanitize_text_field( wp_unslash( isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : '' ) );
                $uvnoticemsg = str_replace('{website_url}', $uvsiteurl, $uvnoticemsg);
            }

            // Prevent empty messages from being sent
            if (empty(trim($uvnoticemsg))) {
                return false;
            }
        } else {
            // Custom message - generate a type key from the message
            $uvnoticetype = 'custom_' . substr(md5($uvnoticemsg), 0, 12);
            $uvnoticemsg = trim((string)$uvnoticemsg);

            $uvsiteurl = function_exists('get_site_url') ? get_site_url() : sanitize_text_field( wp_unslash( isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : '' ) );
            $uvnoticemsg = str_replace('{website_url}', $uvsiteurl, $uvnoticemsg);
        }

        // Throttle using WP transients (stored in wp_options / object cache)
        $uvthrottlekey = 'urvenue_ws_notice_' . preg_replace('/[^a-z0-9_]/i', '_', $uvnoticetype);
        $uvisthrottled = false;

        if (function_exists('get_transient') && function_exists('set_transient')) {
            $uvisthrottled = (get_transient($uvthrottlekey) !== false);
        }

        if ($uvisthrottled) {
            return false;
        }

        // Per-request dedupe
        static $uvsentinreq = [];
        if (isset($uvsentinreq[$uvnoticetype])) {
            return false;
        }

        // Format API response or details as JSON
        // Only add details if it's not already part of the processed message
        if(!is_string($uvnoticedetails) || (is_string($uvnoticedetails) && empty($uvnoticedetails))){
            if (is_array($uvnoticedetails) || is_object($uvnoticedetails)) {
                $uvdetails = wp_json_encode($uvnoticedetails, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            } else {
                $uvdetails = trim((string)$uvnoticedetails);
            }

            if ($uvdetails !== '') {
                $uvnoticemsg .= "\n```" . mb_substr($uvdetails, 0, 28000) . "```";
            }
        }

        $uvpayload = array(
            'text' => $uvnoticemsg,
        );

        $uvwpresponse = wp_remote_post($uvwebhook, array(
            'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
            'body' => wp_json_encode($uvpayload),
            'timeout' => 8,
        ));

        $uvisok = !is_wp_error($uvwpresponse) && ((int)wp_remote_retrieve_response_code($uvwpresponse) >= 200) && ((int)wp_remote_retrieve_response_code($uvwpresponse) < 300);

        if ($uvisok) {
            // mark sent in this request
            $uvsentinreq[$uvnoticetype] = true;

            // start 30-min throttle window in WordPress transients
            if (function_exists('set_transient')) {
                set_transient($uvthrottlekey, 1, 30 * MINUTE_IN_SECONDS);
            }

            return true;
        }
    }

    return false;
}