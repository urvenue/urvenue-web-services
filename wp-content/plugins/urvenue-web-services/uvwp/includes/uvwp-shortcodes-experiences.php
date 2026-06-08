<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Experiences Date Filter
// function shrotcode_uws_experiences_date_filter($atts, $content = null){
function urvenue_ws_shortcode_experiences_date_filter($atts, $content = null){ // Axl UWS-7416
    global $urvenue_ws_path;

	extract(shortcode_atts(array(
		"date" => "",
	), $atts));

	ob_start();

	//include styles
    wp_enqueue_style('urvenue-ws-core-styles');
	wp_enqueue_style('urvenue-ws-experiences-styles');
	wp_enqueue_style('litepicker');
	wp_enqueue_style('urvenue-ws-icons-styles');

	//include scripts
	wp_enqueue_script('urvenue-ws-core-scripts');
	wp_enqueue_script('urvenue-ws-experiences-scripts');
	wp_enqueue_script('litepicker');
    
    include_once($urvenue_ws_path . "/includes/experiences-functions.php");
	$uvargs = ($date) ? array("date" => $date) : "";
    // $uvexperiencesfilter = uws_get_experiences_date_filter($uvargs);
    $uvexperiencesfilter = urvenue_ws_get_experiences_date_filter($uvargs); // Axl UWS-7416
	// @Axl
	// echo $uvexperiencesfilter;
	echo wp_kses_post( $uvexperiencesfilter );
	// @Axl End
	
	$content = ob_get_contents();
  	ob_end_clean();

	return $content;
}
// add_shortcode("urvenue_ws_experiences_date_filter", "shrotcode_uws_experiences_date_filter");
add_shortcode("urvenue_ws_experiences_date_filter", "urvenue_ws_shortcode_experiences_date_filter"); // Axl UWS-7416

//Experiences Filters + List
// function shrotcode_uws_experiences($atts, $content = null){
function urvenue_ws_shortcode_experiences($atts, $content = null){ // Axl UWS-7416
    global $urvenue_ws_path;

	extract(shortcode_atts(array(
		"category" => "",
		"view" => "",
		"date" => "",
	), $atts));

	ob_start();

	//include styles
    wp_enqueue_style('urvenue-ws-core-styles');
	wp_enqueue_style('urvenue-ws-experiences-styles');
	wp_enqueue_style('urvenue-ws-icons-styles');
	wp_enqueue_style('urvenue-ws-inventory-styles');
	wp_enqueue_style('litepicker');

	if(isset($_REQUEST["apireq"])) // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only flag controlling asset enqueue, no state change // Axl UWS-7416
		wp_enqueue_style('urvenue-ws-apireq');

	//include scripts
	wp_enqueue_script('urvenue-ws-core-scripts');
	wp_enqueue_script('urvenue-ws-experiences-scripts');
	wp_enqueue_script('litepicker');

	if(isset($_REQUEST["apireq"])) // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only flag controlling asset enqueue, no state change // Axl UWS-7416
        wp_enqueue_script('urvenue-ws-apireq');
    
    include_once($urvenue_ws_path . "/includes/experiences-functions.php");

	$uvargs = "";
	if($view){
		$uvargs = array(
			"customclass" => "uws-expview-" . $view,
		);
	}
	if($date){
		if(is_array($uvargs))
			$uvargs["date"] = $date;
		else
			$uvargs = array(
				"date" => $date,
			);
	}

    // uws_experiences($uvargs);
    urvenue_ws_experiences($uvargs); // Axl UWS-7416
	
	$content = ob_get_contents();
  	ob_end_clean();

	return $content;
}
// add_shortcode("urvenue_ws_experiences", "shrotcode_uws_experiences");
add_shortcode("urvenue_ws_experiences", "urvenue_ws_shortcode_experiences"); // Axl UWS-7416


//Related Experiences
// function shrotcode_uws_related_experiences($atts, $content = null){
function urvenue_ws_shortcode_related_experiences($atts, $content = null){ // Axl UWS-7416
    global $urvenue_ws_path;

	extract(shortcode_atts(array(
		"nexperiences" => "3",
	), $atts));

	ob_start();

	//include styles
    wp_enqueue_style('urvenue-ws-core-styles');
	wp_enqueue_style('urvenue-ws-experiences-styles');
	wp_enqueue_style('urvenue-ws-icons-styles');
	wp_enqueue_style('urvenue-ws-inventory-styles');
    
    include_once($urvenue_ws_path . "/includes/experiences-functions.php");

	$uvargs = array(
		"customclass" => "uws-expview-agenda",
		"nexperiences" => $nexperiences
	);

    // uws_related_experiences($uvargs);
    urvenue_ws_related_experiences($uvargs); // Axl UWS-7416
	
	$content = ob_get_contents();
  	ob_end_clean();

	return $content;
}
// add_shortcode("urvenue_ws_related_experiences", "shrotcode_uws_related_experiences");
add_shortcode("urvenue_ws_related_experiences", "urvenue_ws_shortcode_related_experiences"); // Axl UWS-7416