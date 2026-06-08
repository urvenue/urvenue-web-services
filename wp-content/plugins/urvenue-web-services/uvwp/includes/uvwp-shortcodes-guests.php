<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_shortcode("urvenue_ws_itinerary", function($atts, $content = null){
    global $urvenue_ws_path;

    ob_start();

    //include styles
	wp_enqueue_style('urvenue-ws-core-styles');
    wp_enqueue_style('urvenue-ws-icons-styles');
    wp_enqueue_style('urvenue-ws-itinerary-styles');
    wp_enqueue_style('litepicker');

	//include scripts
	wp_enqueue_script('urvenue-ws-core-scripts');
    wp_enqueue_script('urvenue-ws-itinerary-scripts');
    wp_enqueue_script('litepicker');

    include_once($urvenue_ws_path . "/includes/itinerary-functions.php");
    // uws_itinerary();
    urvenue_ws_itinerary(); // Axl UWS-7416

	$content = ob_get_contents();
	ob_end_clean();

    return $content;
});