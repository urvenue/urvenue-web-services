<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Calendar
function shortcode_uws_events($atts, $content = null) {
	$uvvenuescodes = (isset($atts['venues'])) ? $atts['venues'] : "";
	$uvvenuesinfilter = (isset($atts['venuesinfilter'])) ? $atts['venuesinfilter'] : "";
	$uvnevents = (isset($atts['nevents'])) ? $atts['nevents'] : "";
	$uvbtnlabel = (isset($atts['button_label'])) ? $atts['button_label'] : "";
	$useview = (isset($atts['view'])) ? $atts['view'] : "";

	
	ob_start();

	//include styles
	wp_enqueue_style('uwscore-styles');
	wp_enqueue_style('uws-icons-styles');
	wp_enqueue_style('uws-events-styles');
	wp_enqueue_style('litepicker');

	//include scripts
	wp_enqueue_script('uwscore-scripts');
	wp_enqueue_script('uws-events-scripts');
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
	$uvfilterdate = (isset($_REQUEST["date"])) ? uws_cleanup_var($_REQUEST["date"]) : "";
	if ($uvfilterdate)
		$uvargs["date"] = $uvfilterdate;

	//add end date filter if is set on the url
	$uvfilterenddate = (isset($_REQUEST["enddate"])) ? uws_cleanup_var($_REQUEST["enddate"]) : "";
	if ($uvfilterenddate)
		$uvargs["enddate"] = $uvfilterenddate;

	//add venue filter if is set on the url
	$uvfiltervenue = (isset($_REQUEST["venue"])) ? uws_cleanup_var($_REQUEST["venue"]) : "";
	if ($uvfiltervenue)
		$uvargs["venue"] = $uvfiltervenue;

	//add performer filter if is set on the url
	$uvfilterperformer = (isset($_REQUEST["performer"])) ? uws_cleanup_var($_REQUEST["performer"]) : "";
	if ($uvfilterperformer)
		$uvargs["performer"] = $uvfilterperformer;

	uws_events($uvargs);

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
add_shortcode("uws_events", "shortcode_uws_events");

function shortcode_uws_events_list($atts, $content = null) {
	global $uws_core_lib;

	$uvvenuescodes = (isset($atts['venues'])) ? $atts['venues'] : "";
	$uvvenuesinfilter = (isset($atts['venuesinfilter'])) ? $atts['venuesinfilter'] : "";
	$uvnevents = (isset($atts['nevents'])) ? $atts['nevents'] : "";
	$uvbtnlabel = (isset($atts['button_label'])) ? $atts['button_label'] : "";

	$useview = (isset($atts['view'])) ? $atts['view'] : "agenda";
	
	ob_start();

	//include styles
	wp_enqueue_style('uwscore-styles');
	wp_enqueue_style('uws-icons-styles');
	wp_enqueue_style('uws-events-styles');
	wp_enqueue_style('litepicker');

	//include scripts
	wp_enqueue_script('uwscore-scripts');
	wp_enqueue_script('uws-events-scripts');
	wp_enqueue_script('litepicker');

	$uvargs = array(
		"venue" => $uvvenuescodes,
		"venuesinfilter" => $uvvenuesinfilter,
		"nevents" => $uvnevents,
	);

	//add date filter if is set on the url
	$uvfilterdate = (isset($_REQUEST["date"])) ? uws_cleanup_var($_REQUEST["date"]) : "";
	if ($uvfilterdate)
		$uvargs["date"] = $uvfilterdate;

	//add end date filter if is set on the url
	$uvfilterenddate = (isset($_REQUEST["enddate"])) ? uws_cleanup_var($_REQUEST["enddate"]) : "";
	if ($uvfilterenddate)
		$uvargs["enddate"] = $uvfilterenddate;

	//add venue filter if is set on the url
	$uvfiltervenue = (isset($_REQUEST["venue"])) ? uws_cleanup_var($_REQUEST["venue"]) : "";
	if ($uvfiltervenue)
		$uvargs["venue"] = $uvfiltervenue;

	//add performer filter if is set on the url
	$uvfilterperformer = (isset($_REQUEST["performer"])) ? uws_cleanup_var($_REQUEST["performer"]) : "";
	if ($uvfilterperformer)
		$uvargs["performer"] = $uvfilterperformer;

	$uvevents = uws_get_events($uvargs);
	$uvlistargs = array(
		"wrap-template" => "events/events-$useview-wrap-default",
		"item-template" => "events/events-$useview-item-default",
		//"maxdate" => $uvmaxdate,
	);

	if($uvbtnlabel)
		$uvlistargs["buttonlabel"] = $uvbtnlabel;

	$uvthisviewhtml = uws_get_events_list($uvevents, $uvlistargs);

	if($uvthisviewhtml) {
		if ($useview == "agenda") {
            $uvviewclass .= " uws-agenda-cols-" . $uws_core_lib["events"]["agenda-columns"];
        }

        $uvthisviewhtml = "<div class='uws-integration uws-events-view uws-events-view-$useview uvsactive $uvviewclass'>$uvthisviewhtml</div>";
	}
	else
		$uvthisviewhtml = "<div class='uws-nocontent'>No Events to Show</div>";

	echo($uvthisviewhtml);

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
add_shortcode("uws_events_list", "shortcode_uws_events_list");

// Slider
function shortcode_uws_events_slider($atts, $content = null)
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
add_shortcode("uws_events_slider", "shortcode_uws_events_slider");

//Event
function shrotcode_uws_event($atts, $content = null)
{
	ob_start();

	//include styles
	wp_enqueue_style('uwscore-styles');
	wp_enqueue_style('uws-icons-styles');
	wp_enqueue_style('uws-event-styles');
	wp_enqueue_style('uws-inventory-styles');
	wp_enqueue_style('nouislider');
	//wp_enqueue_style('uws-events-styles');
	//wp_enqueue_style('litepicker');

	//include scripts
	//wp_enqueue_script('uws-events-scripts');
	//wp_enqueue_script('litepicker');
	wp_enqueue_script('uwscore-scripts');
	wp_enqueue_script('uws-inventory-scripts');
	wp_enqueue_script('pristine');
	wp_enqueue_script('uws-hooks-ga4dl');
	wp_enqueue_script('nouislider');

	uws_event();

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
add_shortcode("uws_event", "shrotcode_uws_event");

//Inventory Item Header
function shrotcode_uws_inventory_item_header($atts, $content = null)
{
	ob_start();

	//include styles
	wp_enqueue_style('uwscore-styles');
	wp_enqueue_style('uws-icons-styles');
	wp_enqueue_style('uws-inventory-styles');
	wp_enqueue_style('uws-invitempage-styles');

	//include scripts
	wp_enqueue_script('uwscore-scripts');
	wp_enqueue_script('uws-inventory-scripts');

	uws_item_header();

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
add_shortcode("uws_inventory_item_header", "shrotcode_uws_inventory_item_header");

//Inventory Item Page
function shrotcode_uws_inventory_item_page($atts, $content = null)
{
	ob_start();

	//include styles
	wp_enqueue_style('uwscore-styles');
	wp_enqueue_style('uws-icons-styles');
	wp_enqueue_style('uws-inventory-styles');
	wp_enqueue_style('uws-invitempage-styles');
	wp_enqueue_style('litepicker');
	wp_enqueue_style('nouislider');

	//include scripts
	wp_enqueue_script('uwscore-scripts');
	wp_enqueue_script('uws-inventory-scripts');
	wp_enqueue_script('uws-invitempage-scripts');
	wp_enqueue_script('litepicker');
	wp_enqueue_script('nouislider');
	wp_enqueue_script('uws-hooks-ga4dl');

	uws_item_page();

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
add_shortcode("uws_inventory_item_page", "shrotcode_uws_inventory_item_page");

// Map
function shortcode_uws_map($atts, $content = null)
{
	global $uws_path, $uws_today;
	ob_start();

	$venuecode = (isset($atts['venuecode'])) ? $atts['venuecode'] : "";
	//include styles
	wp_enqueue_style('uwscore-styles');
	wp_enqueue_style('uws-icons-styles');
	wp_enqueue_style('uws-map-styles');
	wp_enqueue_style('uws-inventory-styles');
	wp_enqueue_style('litepicker');
	wp_enqueue_style('nouislider');
	//wp_enqueue_style('perfect-scrollbar');

	//include scripts
	wp_enqueue_script('uwscore-scripts');
	wp_enqueue_script('uws-map-scripts');
	wp_enqueue_script('uws-inventory-scripts');
	wp_enqueue_script('litepicker');
	wp_enqueue_script('nouislider');
	wp_enqueue_script('hammer');
	//wp_enqueue_script('perfect-scrollbar');
	wp_enqueue_script('uws-mapzoom');
	wp_enqueue_script('uws-mapthumbview');
	wp_enqueue_script('pristine');
	wp_enqueue_script('uws-hooks-ga4dl');

	$uveventdata = "";
	$uvdate = uws_get_events_initial_date("Y-m-d");
	$uveventcode = (isset($atts['eventcode'])) ? $atts['eventcode'] : uws_get_eventcode();

	if ($uveventcode) {
		$uveventdata = uws_get_eventcode_data($uveventcode);
	} else {
		$uvecozone = "ECZ000";
		$uvecozone3 = uws_standardize_ecozone($uvecozone);
		$uvecozone3 = str_replace("ECZ", "", $uvecozone3);

		$uvstartdateformat = str_replace("-", "", $uvdate);

		if ($venuecode != "") {
			$uvprimvenuecode = $venuecode;
		} else {
			$uvprimvenue = uws_get_primary_venue();
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

		$uws_no_inventory_dates = uws_get_month_noinventory_dates($uwsnoinvargs);

		if (is_array($uws_no_inventory_dates) && isset($uws_no_inventory_dates["noinventorydates"]) && in_array($uvdate, $uws_no_inventory_dates["noinventorydates"])) {
			while (in_array($uvdate, $uws_no_inventory_dates["noinventorydates"])) {
				$uvdate = date('Y-m-d', strtotime($uvdate . ' +1 day'));
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

	include_once($uws_path . "/includes/map-functions.php");

	uws_map($uveventdata);

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
add_shortcode("uws_map", "shortcode_uws_map");


include_once($uvwp_path . "/includes/uvwp-shortcodes-experiences.php");
include_once($uvwp_path . "/includes/uvwp-shortcodes-guests.php");

//Inquiry
function shortcode_uws_inquiry($atts, $content = null)
{
	global $uws_path, $uws_core_lib;
	ob_start();

	$uvvenuescodes = (isset($atts['venues'])) ? $atts['venues'] : "";
	$uvredirect_to = (isset($atts['redirect_to'])) ? $atts['redirect_to'] : "";
	$uvnamefields = (isset($atts['namefields'])) ? $atts['namefields'] : "";
	$uvnamefields = (is_array($uws_core_lib) and isset($uws_core_lib["inventory"]["namefields"]) and $uws_core_lib["inventory"]["namefields"]) ? $uws_core_lib["inventory"]["namefields"] : $uvnamefields;
	$uvopendays = (isset($atts['opendays'])) ? $atts['opendays'] : "";

	//include styles
	wp_enqueue_style('uwscore-styles');
	wp_enqueue_style('uws-icons-styles');
	wp_enqueue_style('uws-reservations-styles');
	wp_enqueue_style('litepicker');

	wp_enqueue_script('uwscore-scripts');
	wp_enqueue_script('uws-reservations-scripts');
	wp_enqueue_script('pristine');
	wp_enqueue_script('litepicker');

	$uvargs = array(
		"venue" => $uvvenuescodes,
		"redirect_to" => $uvredirect_to,
		"namefields" => $uvnamefields,
		"opendays" => $uvopendays,
	);

	uws_inquiry_form($uvargs);

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
add_shortcode("uws_inquiry", "shortcode_uws_inquiry");


//[uws_inventorywidget
function shortcode_uws_inventorywidget($atts, $content = null)
{
	global $uws_path;
	ob_start();

	$uvvenuescodes = (isset($atts['venues'])) ? $atts['venues'] : "";

	//include styles
	wp_enqueue_style('uwscore-styles');
	wp_enqueue_style('uws-icons-styles');
	wp_enqueue_style('litepicker');
	wp_enqueue_style('uws-inventory-styles');

	wp_enqueue_script('uwscore-scripts');
	wp_enqueue_script('pristine');
	wp_enqueue_script('litepicker');

	/* Dates */
	$uws_startdate = isset($atts['startdate']) ? $atts['startdate'] : "";
	$uws_maxdays = isset($atts['nextdays']) ? $atts['nextdays'] : "";
	$uws_enddate = isset($atts['endtdate']) ? $atts['endtdate'] : "";

	/* Venues ID*/
	$uws_venuecode = isset($atts['venueid']) ? $atts['venueid'] : "";

	/*ecozone*/
	$uws_ecozone = (isset($atts['ecozone'])) ? $atts['ecozone'] : 0;

	/* global type */
	$uws_globaltype = (isset($atts['globaltype'])) ? $atts['globaltype'] : "seating";

	/* Display Button */
	$uws_display_button = isset($atts['displaybutton']) ? $atts['displaybutton'] : "";
	$uws_display_button_label = isset($atts['displaybuttonlabel']) ? $atts['displaybuttonlabel'] : "Find";

	/* Custom Error Message*/
	$uws_errortitle = isset($atts['errortitle']) ? $atts['errortitle'] : "";
	$uws_errorcontent = isset($atts['errorcontent']) ? $atts['errorcontent'] : "";

	/* Weekdays active*/
	$onlyweekdays = (isset($atts['onlyweekdays'])) ? $atts['onlyweekdays'] : "All";

	/* Mix Ecozones (Display Ecozone Selector) */
	$uvmixecozones = (isset($atts['mixecozones'])) ? $atts['mixecozones'] : 0;

	$uvargs = array(
		"min-date" => $uws_startdate,
		"max-days" => $uws_maxdays,
		"end-date" => $uws_enddate,
		"venuecode" => $uws_venuecode,
		"ecozone" => $uws_ecozone,
		"globaltype" => $uws_globaltype,
		"displaybutton" => $uws_display_button,
		"displaybuttonlabel" => $uws_display_button_label,
		"errortitle" => $uws_errortitle,
		"errorcontent" => $uws_errorcontent,
		"onlyweekdays" => $onlyweekdays,
		"mixecozones" => $uvmixecozones,
	);

	echo uws_inventorywidget($uvargs);
?>

	<?php

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
add_shortcode("uws_inventorywidget", "shortcode_uws_inventorywidget");

//[uws_packages]
function shortcode_uws_packages($atts, $content = null)
{
	global $uws_path, $uws_today;
	ob_start();

	$uvvenuecode = (isset($atts['venuecode'])) ? $atts['venuecode'] : "";
	$uvdate = (isset($atts['fromdate'])) ? $atts['fromdate'] : "";
	$uvtodate = (isset($atts['todate'])) ? $atts['todate'] : "";

	// If $uvdate is set and is in the past, use $uws_today instead
	$uvdate = ($uvdate && strtotime($uvdate) < strtotime($uws_today)) ? $uws_today : $uvdate;

	//include styles
	wp_enqueue_style('uwscore-styles');
	wp_enqueue_style('uws-inventory-styles');
	wp_enqueue_style('nouislider');
	wp_enqueue_style('uws-packages');
	wp_enqueue_style('litepicker');

	//include scripts
	wp_enqueue_script('uwscore-scripts');
	wp_enqueue_script('uws-inventory-scripts');
	wp_enqueue_script('uws-packages');
	wp_enqueue_script('pristine');
	wp_enqueue_script('nouislider');
	wp_enqueue_script('litepicker');

	$uvargs = array(
		"venuecode" => $uvvenuecode,
		"globaltype" => "package",
		"date" => $uvdate,
		"todate" => $uvtodate
	);

	uws_packages($uvargs);

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
add_shortcode("uws_packages", "shortcode_uws_packages");
