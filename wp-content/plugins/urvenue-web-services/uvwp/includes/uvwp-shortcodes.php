<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Calendar
// function shortcode_uws_events($atts, $content = null) {
function urvenue_ws_shortcode_events($atts, $content = null) { // Axl UWS-7416
	$uvvenuescodes = (isset($atts['venues'])) ? $atts['venues'] : "";
	$uvvenuesinfilter = (isset($atts['venuesinfilter'])) ? $atts['venuesinfilter'] : "";
	$uvnevents = (isset($atts['nevents'])) ? $atts['nevents'] : "";
	$uvbtnlabel = (isset($atts['button_label'])) ? $atts['button_label'] : "";
	$useview = (isset($atts['view'])) ? $atts['view'] : "";

	
	ob_start();

	//include styles
	wp_enqueue_style('urvenue-ws-core-styles');
	wp_enqueue_style('urvenue-ws-icons-styles');
	wp_enqueue_style('urvenue-ws-events-styles');
	wp_enqueue_style('litepicker');

	//include scripts
	wp_enqueue_script('urvenue-ws-core-scripts');
	wp_enqueue_script('urvenue-ws-events-scripts');
	wp_enqueue_script('litepicker');

	$uvargs = array(
		"venue" => $uvvenuescodes,
		"venuesinfilter" => $uvvenuesinfilter,
		"nevents" => $uvnevents,
		"view" => $useview,
	);

	if($uvbtnlabel)
		$uvargs["buttonlabel"] = $uvbtnlabel;

	//add date filter if is set on the url
	// $uvfilterdate = (isset($_REQUEST["date"])) ? uws_cleanup_var($_REQUEST["date"]) : "";
	// $uvfilterdate = (isset($_REQUEST["date"])) ? urvenue_ws_cleanup_var($_REQUEST["date"]) : ""; // Axl UWS-7416
	// $uvfilterdate = (isset($_REQUEST["date"])) ? urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["date"] ) ) ) : ""; // Axl UWS-7418
	$uvfilterdate = (isset($_REQUEST["date"])) ? urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["date"] ) ) ) : ""; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only URL param for shortcode display filtering, no state change // Axl UWS-7416
	if ($uvfilterdate)
		$uvargs["date"] = $uvfilterdate;

	//add end date filter if is set on the url
	// $uvfilterenddate = (isset($_REQUEST["enddate"])) ? uws_cleanup_var($_REQUEST["enddate"]) : "";
	// $uvfilterenddate = (isset($_REQUEST["enddate"])) ? urvenue_ws_cleanup_var($_REQUEST["enddate"]) : ""; // Axl UWS-7416
	// $uvfilterenddate = (isset($_REQUEST["enddate"])) ? urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["enddate"] ) ) ) : ""; // Axl UWS-7418
	$uvfilterenddate = (isset($_REQUEST["enddate"])) ? urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["enddate"] ) ) ) : ""; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only URL param for shortcode display filtering, no state change // Axl UWS-7416
	if ($uvfilterenddate)
		$uvargs["enddate"] = $uvfilterenddate;

	//add venue filter if is set on the url
	// $uvfiltervenue = (isset($_REQUEST["venue"])) ? uws_cleanup_var($_REQUEST["venue"]) : "";
	// $uvfiltervenue = (isset($_REQUEST["venue"])) ? urvenue_ws_cleanup_var($_REQUEST["venue"]) : ""; // Axl UWS-7416
	// $uvfiltervenue = (isset($_REQUEST["venue"])) ? urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["venue"] ) ) ) : ""; // Axl UWS-7418
	$uvfiltervenue = (isset($_REQUEST["venue"])) ? urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["venue"] ) ) ) : ""; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only URL param for shortcode display filtering, no state change // Axl UWS-7416
	if ($uvfiltervenue)
		$uvargs["venue"] = $uvfiltervenue;

	//add performer filter if is set on the url
	// $uvfilterperformer = (isset($_REQUEST["performer"])) ? uws_cleanup_var($_REQUEST["performer"]) : "";
	// $uvfilterperformer = (isset($_REQUEST["performer"])) ? urvenue_ws_cleanup_var($_REQUEST["performer"]) : ""; // Axl UWS-7416
	// $uvfilterperformer = (isset($_REQUEST["performer"])) ? urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["performer"] ) ) ) : ""; // Axl UWS-7418
	$uvfilterperformer = (isset($_REQUEST["performer"])) ? urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["performer"] ) ) ) : ""; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only URL param for shortcode display filtering, no state change // Axl UWS-7416
	if ($uvfilterperformer)
		$uvargs["performer"] = $uvfilterperformer;

	// uws_events($uvargs);
	urvenue_ws_events($uvargs); // Axl UWS-7416

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
// add_shortcode("urvenue_ws_events", "shortcode_uws_events");
add_shortcode("urvenue_ws_events", "urvenue_ws_shortcode_events"); // Axl UWS-7416

// function shortcode_uws_events_list($atts, $content = null) {
function urvenue_ws_shortcode_events_list($atts, $content = null) { // Axl UWS-7416
	global $urvenue_ws_core_lib;

	$uvvenuescodes = (isset($atts['venues'])) ? $atts['venues'] : "";
	$uvvenuesinfilter = (isset($atts['venuesinfilter'])) ? $atts['venuesinfilter'] : "";
	$uvnevents = (isset($atts['nevents'])) ? $atts['nevents'] : "";
	$uvbtnlabel = (isset($atts['button_label'])) ? $atts['button_label'] : "";

	$useview = (isset($atts['view'])) ? $atts['view'] : "agenda";
	
	ob_start();

	//include styles
	wp_enqueue_style('urvenue-ws-core-styles');
	wp_enqueue_style('urvenue-ws-icons-styles');
	wp_enqueue_style('urvenue-ws-events-styles');
	wp_enqueue_style('litepicker');

	//include scripts
	wp_enqueue_script('urvenue-ws-core-scripts');
	wp_enqueue_script('urvenue-ws-events-scripts');
	wp_enqueue_script('litepicker');

	$uvargs = array(
		"venue" => $uvvenuescodes,
		"venuesinfilter" => $uvvenuesinfilter,
		"nevents" => $uvnevents,
	);

	//add date filter if is set on the url
	// $uvfilterdate = (isset($_REQUEST["date"])) ? uws_cleanup_var($_REQUEST["date"]) : "";
	// $uvfilterdate = (isset($_REQUEST["date"])) ? urvenue_ws_cleanup_var($_REQUEST["date"]) : ""; // Axl UWS-7416
	// $uvfilterdate = (isset($_REQUEST["date"])) ? urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["date"] ) ) ) : ""; // Axl UWS-7418
	$uvfilterdate = (isset($_REQUEST["date"])) ? urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["date"] ) ) ) : ""; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only URL param for shortcode display filtering, no state change // Axl UWS-7416
	if ($uvfilterdate)
		$uvargs["date"] = $uvfilterdate;

	//add end date filter if is set on the url
	// $uvfilterenddate = (isset($_REQUEST["enddate"])) ? uws_cleanup_var($_REQUEST["enddate"]) : "";
	// $uvfilterenddate = (isset($_REQUEST["enddate"])) ? urvenue_ws_cleanup_var($_REQUEST["enddate"]) : ""; // Axl UWS-7416
	// $uvfilterenddate = (isset($_REQUEST["enddate"])) ? urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["enddate"] ) ) ) : ""; // Axl UWS-7418
	$uvfilterenddate = (isset($_REQUEST["enddate"])) ? urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["enddate"] ) ) ) : ""; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only URL param for shortcode display filtering, no state change // Axl UWS-7416
	if ($uvfilterenddate)
		$uvargs["enddate"] = $uvfilterenddate;

	//add venue filter if is set on the url
	// $uvfiltervenue = (isset($_REQUEST["venue"])) ? uws_cleanup_var($_REQUEST["venue"]) : "";
	// $uvfiltervenue = (isset($_REQUEST["venue"])) ? urvenue_ws_cleanup_var($_REQUEST["venue"]) : ""; // Axl UWS-7416
	// $uvfiltervenue = (isset($_REQUEST["venue"])) ? urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["venue"] ) ) ) : ""; // Axl UWS-7418
	$uvfiltervenue = (isset($_REQUEST["venue"])) ? urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["venue"] ) ) ) : ""; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only URL param for shortcode display filtering, no state change // Axl UWS-7416
	if ($uvfiltervenue)
		$uvargs["venue"] = $uvfiltervenue;

	//add performer filter if is set on the url
	// $uvfilterperformer = (isset($_REQUEST["performer"])) ? uws_cleanup_var($_REQUEST["performer"]) : "";
	// $uvfilterperformer = (isset($_REQUEST["performer"])) ? urvenue_ws_cleanup_var($_REQUEST["performer"]) : ""; // Axl UWS-7416
	// $uvfilterperformer = (isset($_REQUEST["performer"])) ? urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["performer"] ) ) ) : ""; // Axl UWS-7418
	$uvfilterperformer = (isset($_REQUEST["performer"])) ? urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["performer"] ) ) ) : ""; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only URL param for shortcode display filtering, no state change // Axl UWS-7416
	if ($uvfilterperformer)
		$uvargs["performer"] = $uvfilterperformer;

	// $uvevents = uws_get_events($uvargs);
	$uvevents = urvenue_ws_get_events($uvargs); // Axl UWS-7416
	$uvlistargs = array(
		"wrap-template" => "events/events-$useview-wrap-default",
		"item-template" => "events/events-$useview-item-default",
		//"maxdate" => $uvmaxdate,
	);

	if($uvbtnlabel)
		$uvlistargs["buttonlabel"] = $uvbtnlabel;

	// $uvthisviewhtml = uws_get_events_list($uvevents, $uvlistargs);
	$uvthisviewhtml = urvenue_ws_get_events_list($uvevents, $uvlistargs); // Axl UWS-7416

	if($uvthisviewhtml) {
		if ($useview == "agenda") {
            $uvviewclass .= " uws-agenda-cols-" . $urvenue_ws_core_lib["events"]["agenda-columns"];
        }

        $uvthisviewhtml = "<div class='uws-integration uws-events-view uws-events-view-$useview uvsactive $uvviewclass'>$uvthisviewhtml</div>";
	}
	else
		$uvthisviewhtml = "<div class='uws-nocontent'>No Events to Show</div>";

	//before just echo
	echo wp_kses_post($uvthisviewhtml);
	//echo($uvthisviewhtml);

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
// add_shortcode("urvenue_ws_events_list", "shortcode_uws_events_list");
add_shortcode("urvenue_ws_events_list", "urvenue_ws_shortcode_events_list"); // Axl UWS-7416

// Slider
// function shortcode_uws_events_slider($atts, $content = null)
function urvenue_ws_shortcode_events_slider($atts, $content = null) // Axl UWS-7416
{
	/*extract(shortcode_atts(array(
		   "date" => date("Y-m-d"),
		   "venue" => "global",
		   "nmonths" => 5
	   ), $atts));*/
	ob_start();

	echo ("slider");

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
// add_shortcode("urvenue_ws_events_slider", "shortcode_uws_events_slider");
add_shortcode("urvenue_ws_events_slider", "urvenue_ws_shortcode_events_slider"); // Axl UWS-7416

//Event
// function shrotcode_uws_event($atts, $content = null)
function urvenue_ws_shortcode_event($atts, $content = null) // Axl UWS-7416
{
	ob_start();

	//include styles
	wp_enqueue_style('urvenue-ws-core-styles');
	wp_enqueue_style('urvenue-ws-icons-styles');
	wp_enqueue_style('urvenue-ws-event-styles');
	wp_enqueue_style('urvenue-ws-inventory-styles');
	wp_enqueue_style('nouislider');
	//wp_enqueue_style('urvenue-ws-events-styles');
	//wp_enqueue_style('litepicker');

	//include scripts
	//wp_enqueue_script('urvenue-ws-events-scripts');
	//wp_enqueue_script('litepicker');
	wp_enqueue_script('urvenue-ws-core-scripts');
	wp_enqueue_script('urvenue-ws-inventory-scripts');
	wp_enqueue_script('pristine');
	wp_enqueue_script('urvenue-ws-hooks-ga4dl');
	wp_enqueue_script('nouislider');

	// uws_event();
	urvenue_ws_event(); // Axl UWS-7416

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
// add_shortcode("urvenue_ws_event", "shrotcode_uws_event");
add_shortcode("urvenue_ws_event", "urvenue_ws_shortcode_event"); // Axl UWS-7416

//Inventory Item Header
// function shrotcode_uws_inventory_item_header($atts, $content = null)
function urvenue_ws_shortcode_inventory_item_header($atts, $content = null) // Axl UWS-7416
{
	ob_start();

	//include styles
	wp_enqueue_style('urvenue-ws-core-styles');
	wp_enqueue_style('urvenue-ws-icons-styles');
	wp_enqueue_style('urvenue-ws-inventory-styles');
	wp_enqueue_style('urvenue-ws-invitempage-styles');

	//include scripts
	wp_enqueue_script('urvenue-ws-core-scripts');
	wp_enqueue_script('urvenue-ws-inventory-scripts');

	// uws_item_header();
	urvenue_ws_item_header(); // Axl UWS-7416

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
// add_shortcode("urvenue_ws_inventory_item_header", "shrotcode_uws_inventory_item_header");
add_shortcode("urvenue_ws_inventory_item_header", "urvenue_ws_shortcode_inventory_item_header"); // Axl UWS-7416

//Inventory Item Page
// function shrotcode_uws_inventory_item_page($atts, $content = null)
function urvenue_ws_shortcode_inventory_item_page($atts, $content = null) // Axl UWS-7416
{
	ob_start();

	//include styles
	wp_enqueue_style('urvenue-ws-core-styles');
	wp_enqueue_style('urvenue-ws-icons-styles');
	wp_enqueue_style('urvenue-ws-inventory-styles');
	wp_enqueue_style('urvenue-ws-invitempage-styles');
	wp_enqueue_style('litepicker');
	wp_enqueue_style('nouislider');

	//include scripts
	wp_enqueue_script('urvenue-ws-core-scripts');
	wp_enqueue_script('urvenue-ws-inventory-scripts');
	wp_enqueue_script('urvenue-ws-invitempage-scripts');
	wp_enqueue_script('litepicker');
	wp_enqueue_script('nouislider');
	wp_enqueue_script('urvenue-ws-hooks-ga4dl');

	// uws_item_page();
	urvenue_ws_item_page(); // Axl UWS-7416

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
// add_shortcode("urvenue_ws_inventory_item_page", "shrotcode_uws_inventory_item_page");
add_shortcode("urvenue_ws_inventory_item_page", "urvenue_ws_shortcode_inventory_item_page"); // Axl UWS-7416

// Map
// function shortcode_uws_map($atts, $content = null)
function urvenue_ws_shortcode_map($atts, $content = null) // Axl UWS-7416
{
	global $urvenue_ws_path, $urvenue_ws_today;
	ob_start();

	$venuecode = (isset($atts['venuecode'])) ? $atts['venuecode'] : "";
	//include styles
	wp_enqueue_style('urvenue-ws-core-styles');
	wp_enqueue_style('urvenue-ws-icons-styles');
	wp_enqueue_style('urvenue-ws-map-styles');
	wp_enqueue_style('urvenue-ws-inventory-styles');
	wp_enqueue_style('litepicker');
	wp_enqueue_style('nouislider');
	//wp_enqueue_style('perfect-scrollbar');

	//include scripts
	wp_enqueue_script('urvenue-ws-core-scripts');
	wp_enqueue_script('urvenue-ws-map-scripts');
	wp_enqueue_script('urvenue-ws-inventory-scripts');
	wp_enqueue_script('litepicker');
	wp_enqueue_script('nouislider');
	wp_enqueue_script('hammer');
	//wp_enqueue_script('perfect-scrollbar');
	wp_enqueue_script('urvenue-ws-mapzoom');
	wp_enqueue_script('urvenue-ws-mapthumbview');
	wp_enqueue_script('pristine');
	wp_enqueue_script('urvenue-ws-hooks-ga4dl');

	$uveventdata = "";
	// $uvdate = uws_get_events_initial_date("Y-m-d");
	$uvdate = urvenue_ws_get_events_initial_date("Y-m-d"); // Axl UWS-7416
	// $uveventcode = (isset($atts['eventcode'])) ? $atts['eventcode'] : uws_get_eventcode();
	$uveventcode = (isset($atts['eventcode'])) ? $atts['eventcode'] : urvenue_ws_get_eventcode(); // Axl UWS-7416

	if ($uveventcode) {
		// $uveventdata = uws_get_eventcode_data($uveventcode);
		$uveventdata = urvenue_ws_get_eventcode_data($uveventcode); // Axl UWS-7416
	} else {
		$uvecozone = "ECZ000";
		// $uvecozone3 = uws_standardize_ecozone($uvecozone);
		$uvecozone3 = urvenue_ws_standardize_ecozone($uvecozone); // Axl UWS-7416
		$uvecozone3 = str_replace("ECZ", "", $uvecozone3);

		$uvstartdateformat = str_replace("-", "", $uvdate);

		if ($venuecode != "") {
			$uvprimvenuecode = $venuecode;
		} else {
			// $uvprimvenue = uws_get_primary_venue();
			$uvprimvenue = urvenue_ws_get_primary_venue(); // Axl UWS-7416
			$uvprimvenuecode = (is_array($uvprimvenue)) ? $uvprimvenue["venuecode"] : "";
		}


		/* Event Code Settings*/
		$uvstarteventcode = str_replace("VEN", "EVE", $uvprimvenuecode);
		$uveventcode = $uvstarteventcode . $uvecozone3 . $uvstartdateformat;

		// Check if the start date is a no inventory date
		$uwsnoinvargs = array(
			"venuecode" => $uvprimvenuecode,
			"date" => $uvdate,
			"ecozone" => $uvecozone3,
		);

		// $urvenue_ws_no_inventory_dates = uws_get_month_noinventory_dates($uwsnoinvargs);
		$urvenue_ws_no_inventory_dates = urvenue_ws_get_month_noinventory_dates($uwsnoinvargs); // Axl UWS-7416

		if (is_array($urvenue_ws_no_inventory_dates) && isset($urvenue_ws_no_inventory_dates["noinventorydates"]) && in_array($uvdate, $urvenue_ws_no_inventory_dates["noinventorydates"])) {
			while (in_array($uvdate, $urvenue_ws_no_inventory_dates["noinventorydates"])) {
				// $uvdate = date('Y-m-d', strtotime($uvdate . ' +1 day'));
				$uvdate = gmdate('Y-m-d', strtotime($uvdate . ' +1 day')); // Axl UWS-7416
			}

			$uvstartdate = $uvdate;
			$uvstartdateformat = str_replace("-", "", $uvstartdate);
			$uveventcode = $uvstarteventcode . $uvecozone3 . $uvstartdateformat;
		}

		$uveventdata = array(
			"date" => $uvdate,
			"venuecode" => $uvprimvenuecode,
			"ecozone" => $uvecozone3,
			"eventcode" => $uveventcode,
		);
	}

	if (isset($atts['hide_venue_selection']) and $atts['hide_venue_selection']) {
		if (is_array($uveventdata))
			$uveventdata["hidevenuesel"] = 1;
		else
			$uveventdata = array("hidevenuesel" => 1);
	}

	include_once($urvenue_ws_path . "/includes/map-functions.php");

	// uws_map($uveventdata);
	urvenue_ws_map($uveventdata); // Axl UWS-7416

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
// add_shortcode("urvenue_ws_map", "shortcode_uws_map");
add_shortcode("urvenue_ws_map", "urvenue_ws_shortcode_map"); // Axl UWS-7416


include_once($urvenue_ws_uvwp_path . "/includes/uvwp-shortcodes-experiences.php");
include_once($urvenue_ws_uvwp_path . "/includes/uvwp-shortcodes-guests.php");

//Inquiry
// function shortcode_uws_inquiry($atts, $content = null)
function urvenue_ws_shortcode_inquiry($atts, $content = null) // Axl UWS-7416
{
	global $urvenue_ws_path, $urvenue_ws_core_lib;
	ob_start();

	$uvvenuescodes = (isset($atts['venues'])) ? $atts['venues'] : "";
	$uvredirect_to = (isset($atts['redirect_to'])) ? $atts['redirect_to'] : "";
	$uvnamefields = (isset($atts['namefields'])) ? $atts['namefields'] : "";
	$uvnamefields = (is_array($urvenue_ws_core_lib) and isset($urvenue_ws_core_lib["inventory"]["namefields"]) and $urvenue_ws_core_lib["inventory"]["namefields"]) ? $urvenue_ws_core_lib["inventory"]["namefields"] : $uvnamefields;
	$uvopendays = (isset($atts['opendays'])) ? $atts['opendays'] : "";

	//include styles
	wp_enqueue_style('urvenue-ws-core-styles');
	wp_enqueue_style('urvenue-ws-icons-styles');
	wp_enqueue_style('urvenue-ws-reservations-styles');
	wp_enqueue_style('litepicker');

	wp_enqueue_script('urvenue-ws-core-scripts');
	wp_enqueue_script('urvenue-ws-reservations-scripts');
	wp_enqueue_script('pristine');
	wp_enqueue_script('litepicker');

	$uvargs = array(
		"venue" => $uvvenuescodes,
		"redirect_to" => $uvredirect_to,
		"namefields" => $uvnamefields,
		"opendays" => $uvopendays,
	);

	// uws_inquiry_form($uvargs);
	urvenue_ws_inquiry_form($uvargs); // Axl UWS-7416

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
// add_shortcode("urvenue_ws_inquiry", "shortcode_uws_inquiry");
add_shortcode("urvenue_ws_inquiry", "urvenue_ws_shortcode_inquiry"); // Axl UWS-7416


//[urvenue_ws_inventorywidget
// function shortcode_uws_inventorywidget($atts, $content = null)
function urvenue_ws_shortcode_inventorywidget($atts, $content = null) // Axl UWS-7416
{
	global $urvenue_ws_path;
	ob_start();

	$uvvenuescodes = (isset($atts['venues'])) ? $atts['venues'] : "";

	//include styles
	wp_enqueue_style('urvenue-ws-core-styles');
	wp_enqueue_style('urvenue-ws-icons-styles');
	wp_enqueue_style('litepicker');
	wp_enqueue_style('urvenue-ws-inventory-styles');

	wp_enqueue_script('urvenue-ws-core-scripts');
	wp_enqueue_script('pristine');
	wp_enqueue_script('litepicker');

	/* Dates */
	$urvenue_ws_startdate = isset($atts['startdate']) ? $atts['startdate'] : "";
	$urvenue_ws_maxdays = isset($atts['nextdays']) ? $atts['nextdays'] : "";
	$urvenue_ws_enddate = isset($atts['endtdate']) ? $atts['endtdate'] : "";

	/* Venues ID*/
	$urvenue_ws_venuecode = isset($atts['venueid']) ? $atts['venueid'] : "";

	/*ecozone*/
	$urvenue_ws_ecozone = (isset($atts['ecozone'])) ? $atts['ecozone'] : 0;

	/* global type */
	$urvenue_ws_globaltype = (isset($atts['globaltype'])) ? $atts['globaltype'] : "seating";

	/* Display Button */
	$urvenue_ws_display_button = isset($atts['displaybutton']) ? $atts['displaybutton'] : "";
	$urvenue_ws_display_button_label = isset($atts['displaybuttonlabel']) ? $atts['displaybuttonlabel'] : "Find";

	/* Custom Error Message*/
	$urvenue_ws_errortitle = isset($atts['errortitle']) ? $atts['errortitle'] : "";
	$urvenue_ws_errorcontent = isset($atts['errorcontent']) ? $atts['errorcontent'] : "";

	/* Weekdays active*/
	$onlyweekdays = (isset($atts['onlyweekdays'])) ? $atts['onlyweekdays'] : "All";

	/* Mix Ecozones (Display Ecozone Selector) */
	$uvmixecozones = (isset($atts['mixecozones'])) ? $atts['mixecozones'] : 0;

	$uvargs = array(
		"min-date" => $urvenue_ws_startdate,
		"max-days" => $urvenue_ws_maxdays,
		"end-date" => $urvenue_ws_enddate,
		"venuecode" => $urvenue_ws_venuecode,
		"ecozone" => $urvenue_ws_ecozone,
		"globaltype" => $urvenue_ws_globaltype,
		"displaybutton" => $urvenue_ws_display_button,
		"displaybuttonlabel" => $urvenue_ws_display_button_label,
		"errortitle" => $urvenue_ws_errortitle,
		"errorcontent" => $urvenue_ws_errorcontent,
		"onlyweekdays" => $onlyweekdays,
		"mixecozones" => $uvmixecozones,
	);

	// echo uws_inventorywidget($uvargs);
	// echo urvenue_ws_inventorywidget($uvargs); // Axl UWS-7416
	echo wp_kses_post(urvenue_ws_inventorywidget($uvargs)); // Axl UWS-7416
?>

	<?php

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
// add_shortcode("urvenue_ws_inventorywidget", "shortcode_uws_inventorywidget");
add_shortcode("urvenue_ws_inventorywidget", "urvenue_ws_shortcode_inventorywidget"); // Axl UWS-7416

//[urvenue_ws_packages]
// function shortcode_uws_packages($atts, $content = null)
function urvenue_ws_shortcode_packages($atts, $content = null) // Axl UWS-7416
{
	global $urvenue_ws_path, $urvenue_ws_today;
	ob_start();

	$uvvenuecode = (isset($atts['venuecode'])) ? $atts['venuecode'] : "";
	$uvdate = (isset($atts['fromdate'])) ? $atts['fromdate'] : "";
	$uvtodate = (isset($atts['todate'])) ? $atts['todate'] : "";

	// If $uvdate is set and is in the past, use $urvenue_ws_today instead
	$uvdate = ($uvdate && strtotime($uvdate) < strtotime($urvenue_ws_today)) ? $urvenue_ws_today : $uvdate;

	//include styles
	wp_enqueue_style('urvenue-ws-core-styles');
	wp_enqueue_style('urvenue-ws-inventory-styles');
	wp_enqueue_style('nouislider');
	wp_enqueue_style('urvenue-ws-packages');
	wp_enqueue_style('litepicker');

	//include scripts
	wp_enqueue_script('urvenue-ws-core-scripts');
	wp_enqueue_script('urvenue-ws-inventory-scripts');
	wp_enqueue_script('urvenue-ws-packages');
	wp_enqueue_script('pristine');
	wp_enqueue_script('nouislider');
	wp_enqueue_script('litepicker');

	$uvargs = array(
		"venuecode" => $uvvenuecode,
		"globaltype" => "package",
		"date" => $uvdate,
		"todate" => $uvtodate
	);

	// uws_packages($uvargs);
	urvenue_ws_packages($uvargs); // Axl UWS-7416

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
// add_shortcode("urvenue_ws_packages", "shortcode_uws_packages");
add_shortcode("urvenue_ws_packages", "urvenue_ws_shortcode_packages"); // Axl UWS-7416
