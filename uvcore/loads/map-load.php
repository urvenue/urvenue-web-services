<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_path, $urvenue_ws_config_uitheme;

include_once($urvenue_ws_path . "/includes/map-functions.php");

// @egt [UWS-7297]
// uws_check_nonce("uwsmap");
urvenue_ws_check_nonce("uwsmap"); // Axl UWS-7416

// $urvenue_ws_date = uws_cleanup_request("date");
$urvenue_ws_date = urvenue_ws_cleanup_request("date"); // Axl UWS-7416
// $urvenue_ws_venuecode = uws_cleanup_request("venuecode");
$urvenue_ws_venuecode = urvenue_ws_cleanup_request("venuecode"); // Axl UWS-7416
// $urvenue_ws_returntempl = uws_cleanup_request("returntempl");
$urvenue_ws_returntempl = urvenue_ws_cleanup_request("returntempl"); // Axl UWS-7416
// $urvenue_ws_ecozone = uws_cleanup_request("ecozone", "ECZ0");
$urvenue_ws_ecozone = urvenue_ws_cleanup_request("ecozone", "ECZ0"); // Axl UWS-7416
// $urvenue_ws_manageentid = uws_cleanup_request("manageentid");
$urvenue_ws_manageentid = urvenue_ws_cleanup_request("manageentid"); // Axl UWS-7416
$urvenue_ws_manageentid = (!$urvenue_ws_manageentid and isset($urvenue_ws_config_manageentid)) ? $urvenue_ws_config_manageentid : $urvenue_ws_manageentid;
// $urvenue_ws_forcelisttype = uws_cleanup_request("forcelisttype");
$urvenue_ws_forcelisttype = urvenue_ws_cleanup_request("forcelisttype"); // Axl UWS-7416
// $urvenue_ws_nogroupings = uws_cleanup_request("nogroupings");
$urvenue_ws_nogroupings = urvenue_ws_cleanup_request("nogroupings"); // Axl UWS-7416
// $urvenue_ws_returnlang = uws_cleanup_request("returnlang");
$urvenue_ws_returnlang = urvenue_ws_cleanup_request("returnlang"); // Axl UWS-7416
// $urvenue_ws_homeecozone = uws_cleanup_request("homeecozone");
$urvenue_ws_homeecozone = urvenue_ws_cleanup_request("homeecozone"); // Axl UWS-7416
// $urvenue_ws_homename = uws_cleanup_request("homename");
$urvenue_ws_homename = urvenue_ws_cleanup_request("homename"); // Axl UWS-7416
// $urvenue_ws_cartcode = uws_cleanup_request("cartcode");
$urvenue_ws_cartcode = urvenue_ws_cleanup_request("cartcode"); // Axl UWS-7416
// $urvenue_ws_reqmaptheme = uws_cleanup_request("theme");
$urvenue_ws_reqmaptheme = urvenue_ws_cleanup_request("theme"); // Axl UWS-7416
// $urvenue_ws_maptheme = ($urvenue_ws_reqmaptheme) ? "uws-" . $urvenue_ws_reqmaptheme : uws_get_theme();
$urvenue_ws_maptheme = ($urvenue_ws_reqmaptheme) ? "uws-" . $urvenue_ws_reqmaptheme : urvenue_ws_get_theme(); // Axl UWS-7416
// $urvenue_ws_mappopuptheme = (isset($_REQUEST["poptheme"])) ? "uws-" . uws_cleanup_var($_REQUEST["poptheme"]) : uws_get_popup_theme();
// $urvenue_ws_mappopuptheme = (isset($_REQUEST["poptheme"])) ? "uws-" . urvenue_ws_cleanup_var($_REQUEST["poptheme"]) : urvenue_ws_get_popup_theme(); // Axl UWS-7416
// $urvenue_ws_mappopuptheme = (isset($_REQUEST["poptheme"])) ? "uws-" . urvenue_ws_cleanup_var( wp_unslash( $_REQUEST["poptheme"] ) ) : urvenue_ws_get_popup_theme(); // Axl UWS-7418
// $urvenue_ws_mappopuptheme = (isset($_REQUEST["poptheme"])) ? "uws-" . sanitize_text_field( urvenue_ws_cleanup_var( wp_unslash( $_REQUEST["poptheme"] ) ) ) : urvenue_ws_get_popup_theme(); // Axl UWS-7418
$urvenue_ws_mappopuptheme = (isset($_REQUEST["poptheme"])) ? "uws-" . urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["poptheme"] ) ) ) : urvenue_ws_get_popup_theme(); // Axl UWS-7416

if($urvenue_ws_reqmaptheme) $urvenue_ws_config_uitheme = $urvenue_ws_reqmaptheme;

$urvenue_ws_return = array();

if($urvenue_ws_date and $urvenue_ws_venuecode and $urvenue_ws_ecozone){
    $urvenue_ws_args = array(
        "date" => $urvenue_ws_date,
        "venuecode" => $urvenue_ws_venuecode,
        "ecozone" => $urvenue_ws_ecozone,
        "manageentid" => $urvenue_ws_manageentid,
        "forcelisttype" => $urvenue_ws_forcelisttype,
        "nogroupings" => $urvenue_ws_nogroupings,
        "homeecozone" => $urvenue_ws_homeecozone,
        "homename" => $urvenue_ws_homename,
    );

    // $urvenue_ws_return = uws_get_map_stage($urvenue_ws_args);
    $urvenue_ws_return = urvenue_ws_get_map_stage($urvenue_ws_args); // Axl UWS-7416

    if($urvenue_ws_homeecozone or $urvenue_ws_return["isecozonelist"])
        $urvenue_ws_args["mixecozones"] = 1;

    // $urvenue_ws_monthnoinventorydates = uws_get_month_noinventory_dates($urvenue_ws_args);
    $urvenue_ws_monthnoinventorydates = urvenue_ws_get_month_noinventory_dates($urvenue_ws_args); // Axl UWS-7416

    $urvenue_ws_return["availabilityinfo"] = $urvenue_ws_monthnoinventorydates;

    // $urvenue_ws_standardeco = uws_standardize_ecozone($urvenue_ws_ecozone);
    $urvenue_ws_standardeco = urvenue_ws_standardize_ecozone($urvenue_ws_ecozone); // Axl UWS-7416
    // $urvenue_ws_eventcode = "EVE" . str_replace("VEN", "", $urvenue_ws_venuecode) . str_replace("ECZ", "", $urvenue_ws_standardeco) . date("Ymd", strtotime($urvenue_ws_date));
    $urvenue_ws_eventcode = "EVE" . str_replace("VEN", "", $urvenue_ws_venuecode) . str_replace("ECZ", "", $urvenue_ws_standardeco) . gmdate("Ymd", strtotime($urvenue_ws_date)); // Axl UWS-7416
    $urvenue_ws_return["eventcode"] = $urvenue_ws_eventcode;
}

if($urvenue_ws_returntempl){
    $urvenue_ws_templs = array(
        // "map-item-box" => uws_get_template("map/map-item-box"),
        "map-item-box" => urvenue_ws_get_template("map/map-item-box"), // Axl UWS-7416
        // "map-item-tooltip" => uws_get_template("map/map-item-tooltip"),
        "map-item-tooltip" => urvenue_ws_get_template("map/map-item-tooltip"), // Axl UWS-7416
        // "map-list-item-tooltip" => uws_get_template("map/map-list-item-tooltip"),
        "map-list-item-tooltip" => urvenue_ws_get_template("map/map-list-item-tooltip"), // Axl UWS-7416
        // "map-itemslist-sel-pop" => uws_get_template("map/map-itemslist-sel-pop"),
        "map-itemslist-sel-pop" => urvenue_ws_get_template("map/map-itemslist-sel-pop"), // Axl UWS-7416
        // "map-itemslist-sel-item" => uws_get_template("map/map-itemslist-sel-item"),
        "map-itemslist-sel-item" => urvenue_ws_get_template("map/map-itemslist-sel-item"), // Axl UWS-7416
        // "map-multiitem-tooltip-cont" => uws_get_template("map/map-multiitem-tooltip-cont"),
        "map-multiitem-tooltip-cont" => urvenue_ws_get_template("map/map-multiitem-tooltip-cont"), // Axl UWS-7416
        // "map-multiitem-tooltip-item" => uws_get_template("map/map-multiitem-tooltip-item"),
        "map-multiitem-tooltip-item" => urvenue_ws_get_template("map/map-multiitem-tooltip-item"), // Axl UWS-7416
        // "item-added-btn-content" => uws_get_template("inventory/item-added-btn-content"),
        "item-added-btn-content" => urvenue_ws_get_template("inventory/item-added-btn-content"), // Axl UWS-7416
        // "item-add-another" => uws_get_template("inventory/item-add-another"),
        "item-add-another" => urvenue_ws_get_template("inventory/item-add-another"), // Axl UWS-7416
    );

    $urvenue_ws_return["templates"] = $urvenue_ws_templs;
}

// $urvenue_ws_eventdata = uws_get_event($urvenue_ws_eventcode);
$urvenue_ws_eventdata = urvenue_ws_get_event($urvenue_ws_eventcode); // Axl UWS-7416
// $urvenue_ws_cart = uws_get_cart($urvenue_ws_cartcode, $urvenue_ws_eventdata);
$urvenue_ws_cart = urvenue_ws_get_cart($urvenue_ws_cartcode, $urvenue_ws_eventdata); // Axl UWS-7416
if($urvenue_ws_cart){
    // $urvenue_ws_bkgcheckoutlinks = uws_get_bkgcheckout_links($urvenue_ws_cartcode, $urvenue_ws_cart["accountvars"]);
    $urvenue_ws_bkgcheckoutlinks = urvenue_ws_get_bkgcheckout_links($urvenue_ws_cartcode, $urvenue_ws_cart["accountvars"]); // Axl UWS-7416
    
    $urvenue_ws_cart["checkout-carturl"] = $urvenue_ws_bkgcheckoutlinks["checkout-carturl"];
    $urvenue_ws_cart["checkout-checkurl"] = $urvenue_ws_bkgcheckoutlinks["checkout-checkurl"];
    $urvenue_ws_return["cart"] = $urvenue_ws_cart;
}

if($urvenue_ws_returnlang){
    // $urvenue_ws_return["lang"] = uws_lang("front-lang");
    $urvenue_ws_return["lang"] = urvenue_ws_lang("front-lang"); // Axl UWS-7416
}

if($urvenue_ws_maptheme){
    $urvenue_ws_return["theme"] = $urvenue_ws_maptheme;
}

if($urvenue_ws_mappopuptheme){
    $urvenue_ws_return["poptheme"] = $urvenue_ws_mappopuptheme;
}    

// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_return);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416