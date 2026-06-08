<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// $urvenue_ws_hooks = array();
$urvenue_ws_hooks = array(); // Axl UWS-7634
// $urvenue_ws_filters = array();
$urvenue_ws_filters = array(); // Axl UWS-7634

// function uws_add_action($uvhook, $uvcallback){
function urvenue_ws_add_action($uvhook, $uvcallback){ // Axl UWS-7416
    // global $urvenue_ws_hooks;
    global $urvenue_ws_hooks; // Axl UWS-7634
    // $urvenue_ws_hooks[$uvhook][] = $uvcallback;
    $urvenue_ws_hooks[$uvhook][] = $uvcallback; // Axl UWS-7634
}

// function uws_do_action($uvhook, ...$uvargs){
function urvenue_ws_do_action($uvhook, ...$uvargs){ // Axl UWS-7416
    // global $urvenue_ws_hooks;
    global $urvenue_ws_hooks; // Axl UWS-7634

    // if (isset($urvenue_ws_hooks[$uvhook])) {
    if (isset($urvenue_ws_hooks[$uvhook])) { // Axl UWS-7634
        // foreach ($urvenue_ws_hooks[$uvhook] as $uvcallback) {
        foreach ($urvenue_ws_hooks[$uvhook] as $uvcallback) { // Axl UWS-7634
            call_user_func_array($uvcallback, $uvargs);
        }
    }
}


// Function to register a callback to a filter
// function uws_add_filter($uvfilter, $uvcallback) {
function urvenue_ws_add_filter($uvfilter, $uvcallback) { // Axl UWS-7416
    // global $urvenue_ws_filters;
    global $urvenue_ws_filters; // Axl UWS-7634
    // if (!isset($urvenue_ws_filters[$uvfilter])) {
    if (!isset($urvenue_ws_filters[$uvfilter])) { // Axl UWS-7634
        // $urvenue_ws_filters[$uvfilter] = array();
        $urvenue_ws_filters[$uvfilter] = array(); // Axl UWS-7634
    }
    // $urvenue_ws_filters[$uvfilter][] = $uvcallback;
    $urvenue_ws_filters[$uvfilter][] = $uvcallback; // Axl UWS-7634
}

// Function to apply filters to a value
// function uws_apply_filters($uvfilter, $uvvalue, ...$uvargs) {
function urvenue_ws_apply_filters($uvfilter, $uvvalue, ...$uvargs) { // Axl UWS-7416
    // global $urvenue_ws_filters;
    global $urvenue_ws_filters; // Axl UWS-7634
    // if (isset($urvenue_ws_filters[$uvfilter])) {
    if (isset($urvenue_ws_filters[$uvfilter])) { // Axl UWS-7634
        // foreach ($urvenue_ws_filters[$uvfilter] as $uvcallback) {
        foreach ($urvenue_ws_filters[$uvfilter] as $uvcallback) { // Axl UWS-7634
            $uvvalue = call_user_func_array($uvcallback, array_merge([$uvvalue], $uvargs));
        }
    }
    return $uvvalue;
}