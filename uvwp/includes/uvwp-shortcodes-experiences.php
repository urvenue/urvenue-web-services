<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Experiences Date Filter
function shrotcode_uws_experiences_date_filter($atts, $content = null){
    global $uws_path;

	extract(shortcode_atts(array(
		"date" => "",
	), $atts));

	ob_start();

	//include styles
    wp_enqueue_style('uwscore-styles');
	wp_enqueue_style('uws-experiences-styles');
	wp_enqueue_style('litepicker');
	wp_enqueue_style('uws-icons-styles');

	//include scripts
	wp_enqueue_script('uwscore-scripts');
	wp_enqueue_script('uws-experiences-scripts');
	wp_enqueue_script('litepicker');
    
    include_once($uws_path . "/includes/experiences-functions.php");
	$uvargs = ($date) ? array("date" => $date) : "";
    $uvexperiencesfilter = uws_get_experiences_date_filter($uvargs);
	// @Axl
	// echo $uvexperiencesfilter;
	echo wp_kses_post( $uvexperiencesfilter );
	// @Axl End
	
	$content = ob_get_contents();
  	ob_end_clean();

	return $content;
}
add_shortcode("uws_experiences_date_filter", "shrotcode_uws_experiences_date_filter");

//Experiences Filters + List
function shrotcode_uws_experiences($atts, $content = null){
    global $uws_path;

	extract(shortcode_atts(array(
		"category" => "",
		"view" => "",
		"date" => "",
	), $atts));

	ob_start();

	//include styles
    wp_enqueue_style('uwscore-styles');
	wp_enqueue_style('uws-experiences-styles');
	wp_enqueue_style('uws-icons-styles');
	wp_enqueue_style('uws-inventory-styles');
	wp_enqueue_style('litepicker');

	if(isset($_REQUEST["apireq"]))
		wp_enqueue_style('uws-apireq');

	//include scripts
	wp_enqueue_script('uwscore-scripts');
	wp_enqueue_script('uws-experiences-scripts');
	wp_enqueue_script('litepicker');

	if(isset($_REQUEST["apireq"]))
        wp_enqueue_script('uws-apireq');
    
    include_once($uws_path . "/includes/experiences-functions.php");

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

    uws_experiences($uvargs);
	
	$content = ob_get_contents();
  	ob_end_clean();

	return $content;
}
add_shortcode("uws_experiences", "shrotcode_uws_experiences");


//Related Experiences
function shrotcode_uws_related_experiences($atts, $content = null){
    global $uws_path;

	extract(shortcode_atts(array(
		"nexperiences" => "3",
	), $atts));

	ob_start();

	//include styles
    wp_enqueue_style('uwscore-styles');
	wp_enqueue_style('uws-experiences-styles');
	wp_enqueue_style('uws-icons-styles');
	wp_enqueue_style('uws-inventory-styles');
    
    include_once($uws_path . "/includes/experiences-functions.php");

	$uvargs = array(
		"customclass" => "uws-expview-agenda",
		"nexperiences" => $nexperiences
	);

    uws_related_experiences($uvargs);
	
	$content = ob_get_contents();
  	ob_end_clean();

	return $content;
}
add_shortcode("uws_related_experiences", "shrotcode_uws_related_experiences");