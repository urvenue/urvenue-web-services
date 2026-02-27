<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Testing function - remove after testing
function uws_test_notices() {
    echo "Testing notice system...\n";
    
    // Test 1: First call should send
    echo "Test 1 (should send): ";
    $result1 = uws_website_notices_send("noevents", "Test notification #1");
    echo $result1 ? "✅ SENT\n" : "❌ NOT SENT\n";
    
    // Test 2: Immediate second call should NOT send (throttled)
    echo "Test 2 (should NOT send - throttled): ";
    $result2 = uws_website_notices_send("noevents", "Test notification #2");
    echo $result2 ? "✅ SENT\n" : "❌ NOT SENT\n";
    
    // Test 3: Different type should send
    echo "Test 3 (different type, should send): ";
    $result3 = uws_website_notices_send("Custom test message", "Different type test");
    echo $result3 ? "✅ SENT\n" : "❌ NOT SENT\n";
    
    echo "Wait 2+ minutes and run again to test throttle expiration...\n";
}

// Function to clear throttling for testing
function uws_clear_notice_throttling($uvalerttype = 'noevents') {
    $uvthrottlekey = 'uws_notice_' . preg_replace('/[^a-z0-9_]/i', '_', $uvalerttype);
    
    // Clear WordPress transient
    if (function_exists('delete_transient'))
        delete_transient($uvthrottlekey);
    
    // Clear file fallback
    $uvthrottlefile = sys_get_temp_dir() . '/uws_throttle_' . $uvthrottlekey . '.txt';
    if (file_exists($uvthrottlefile))
        unlink($uvthrottlefile);
}

// Uncomment to run tests:
// uws_test_notices();
// uws_website_notices_send("noevents", "");
if(isset($_REQUEST['uwsclearthrottle'])) uws_clear_notice_throttling();

function uws_website_notices_send($uvnoticemsg = "", $uvnoticedetails = ""){
    global $uws_website_notices_types, $uws_core_lib;

    $uvenablenotice = (isset($uws_core_lib["notifications"]["enable"]) and $uws_core_lib["notifications"]["enable"]) ? $uws_core_lib["notifications"]["enable"] : 0;
    $uvwebhook = (isset($uws_core_lib["notifications"]["webhook"]) and $uws_core_lib["notifications"]["webhook"]) ? $uws_core_lib["notifications"]["webhook"] : "";

    if($uvwebhook and $uvenablenotice){
        // Check if first parameter is a known alert type
        if (isset($uws_website_notices_types[$uvnoticemsg])) {
            $uvnoticetype = $uvnoticemsg;
            
            // If second parameter is a processed message (string), use it
            // Otherwise use the template and replace {website_url}
            if(is_string($uvnoticedetails) && !empty($uvnoticedetails)){
                $uvnoticemsg = $uvnoticedetails;
            } else {
                $uvnoticemsg = isset($uws_website_notices_types[$uvnoticemsg]['message_template']) 
                    ? $uws_website_notices_types[$uvnoticemsg]['message_template'] 
                    : $uws_website_notices_types['default']['message_template'];
                $uvsiteurl = function_exists('get_site_url') ? get_site_url() : $_SERVER['HTTP_HOST'];
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
            
            $uvsiteurl = function_exists('get_site_url') ? get_site_url() : $_SERVER['HTTP_HOST'];
            $uvnoticemsg = str_replace('{website_url}', $uvsiteurl, $uvnoticemsg);
        }

        // 30-minute throttle using WP transients with file fallback
        $uvthrottlekey = 'uws_notice_' . preg_replace('/[^a-z0-9_]/i', '_', $uvnoticetype);
        $uvisthrottled = false;

        // First check WordPress transients
        if (function_exists('get_transient') && function_exists('set_transient')) {
            $uvisthrottled = (get_transient($uvthrottlekey) !== false);
        }

        // If not throttled by transients, check file fallback
        if (!$uvisthrottled) {
            $uvthrottlefile = sys_get_temp_dir() . '/uws_throttle_' . $uvthrottlekey . '.txt';
            if (file_exists($uvthrottlefile)) {
                $file_time = (int)file_get_contents($uvthrottlefile);
                $current_time = time();
                // Check if file is less than 30 minutes old
                if (($current_time - $file_time) < (30 * 60)) {
                    $uvisthrottled = true;
                }
            }
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
                // @Axl
                // $uvdetails = json_encode($uvnoticedetails, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                $uvdetails = wp_json_encode($uvnoticedetails, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                // @Axl End
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

        // TESTING @Axl
        // $ch = curl_init($uvwebhook);
        // curl_setopt_array($ch, array(
        //     CURLOPT_POST           => true,
        //     CURLOPT_HTTPHEADER     => array('Content-Type: application/json; charset=utf-8'),
        //     CURLOPT_POSTFIELDS     => json_encode($uvpayload),
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_CONNECTTIMEOUT => 5,
        //     CURLOPT_TIMEOUT        => 8,
        // ));

        // @Axl
        // 'body' => json_encode($uvpayload),
        $uvwpresponse = wp_remote_post($uvwebhook, array(
            'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
            'body' => wp_json_encode($uvpayload),
            'timeout' => 8,
        ));
        // @Axl End

        // TESTING @Axl
        // curl_exec($ch);
        // $uvisok = !curl_errno($ch) && ((int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE) >= 200) && ((int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE) < 300);
        // curl_close($ch);
        $uvisok = !is_wp_error($uvwpresponse) && ((int)wp_remote_retrieve_response_code($uvwpresponse) >= 200) && ((int)wp_remote_retrieve_response_code($uvwpresponse) < 300);

        if ($uvisok) {
            // mark sent in this request
            $uvsentinreq[$uvnoticetype] = true;

            // start 4-min throttle window in WordPress transients (testing)
            if (function_exists('set_transient')) {
                set_transient($uvthrottlekey, 1, 4 * MINUTE_IN_SECONDS);
            }

            // Also create file fallback for cache-resistant throttling
            $uvthrottlefile = sys_get_temp_dir() . '/uws_throttle_' . $uvthrottlekey . '.txt';
            file_put_contents($uvthrottlefile, time());

            return true;
        }
    }

    return false;
}