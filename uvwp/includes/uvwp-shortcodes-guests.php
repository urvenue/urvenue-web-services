<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_shortcode("uws_itinerary", function($atts, $content = null){
    global $uws_path;

    ob_start();

    //include styles
	wp_enqueue_style('uwscore-styles');
    wp_enqueue_style('uws-icons-styles');
    wp_enqueue_style('uws-itinerary-styles');
    wp_enqueue_style('litepicker');

	//include scripts
	wp_enqueue_script('uwscore-scripts');
    wp_enqueue_script('uws-itinerary-scripts');
    wp_enqueue_script('litepicker');

    include_once($uws_path . "/includes/itinerary-functions.php");
    // uws_itinerary();
    urvenue_ws_itinerary(); // Axl UWS-7416

	$content = ob_get_contents();
	ob_end_clean();

    return $content;
});