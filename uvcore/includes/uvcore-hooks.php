<?php

$uws_hooks = array();
$uws_filters = array();

function uws_add_action($uvhook, $uvcallback){
    global $uws_hooks;
    $uws_hooks[$uvhook][] = $uvcallback;
}

function uws_do_action($uvhook, ...$uvargs){
    global $uws_hooks;

    if (isset($uws_hooks[$uvhook])) {
        foreach ($uws_hooks[$uvhook] as $uvcallback) {
            call_user_func_array($uvcallback, $uvargs);
        }
    }
}


// Function to register a callback to a filter
function uws_add_filter($uvfilter, $uvcallback) {
    global $uws_filters;
    if (!isset($uws_filters[$uvfilter])) {
        $uws_filters[$uvfilter] = array();
    }
    $uws_filters[$uvfilter][] = $uvcallback;
}

// Function to apply filters to a value
function uws_apply_filters($uvfilter, $uvvalue, ...$uvargs) {
    global $uws_filters;
    if (isset($uws_filters[$uvfilter])) {
        foreach ($uws_filters[$uvfilter] as $uvcallback) {
            $uvvalue = call_user_func_array($uvcallback, array_merge([$uvvalue], $uvargs));
        }
    }
    return $uvvalue;
}