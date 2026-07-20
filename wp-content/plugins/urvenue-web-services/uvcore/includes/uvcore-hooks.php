<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$urvenue_ws_hooks = array();
$urvenue_ws_filters = array();

function urvenue_ws_add_action($uvhook, $uvcallback){
    global $urvenue_ws_hooks;
    $urvenue_ws_hooks[$uvhook][] = $uvcallback;
}

function urvenue_ws_do_action($uvhook, ...$uvargs){
    global $urvenue_ws_hooks;

    if (isset($urvenue_ws_hooks[$uvhook])) {
        foreach ($urvenue_ws_hooks[$uvhook] as $uvcallback) {
            call_user_func_array($uvcallback, $uvargs);
        }
    }
}


// Function to register a callback to a filter
function urvenue_ws_add_filter($uvfilter, $uvcallback) {
    global $urvenue_ws_filters;
    if (!isset($urvenue_ws_filters[$uvfilter])) {
        $urvenue_ws_filters[$uvfilter] = array();
    }
    $urvenue_ws_filters[$uvfilter][] = $uvcallback;
}

// Function to apply filters to a value
function urvenue_ws_apply_filters($uvfilter, $uvvalue, ...$uvargs) {
    global $urvenue_ws_filters;
    if (isset($urvenue_ws_filters[$uvfilter])) {
        foreach ($urvenue_ws_filters[$uvfilter] as $uvcallback) {
            $uvvalue = call_user_func_array($uvcallback, array_merge([$uvvalue], $uvargs));
        }
    }
    return $uvvalue;
}
