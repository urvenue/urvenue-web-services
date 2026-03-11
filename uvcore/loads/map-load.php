<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_path, $uws_config_uitheme;

include_once($uws_path . "/includes/map-functions.php");

// @egt [UWS-7297]
// uws_check_nonce("uwsmap");
urvenue_ws_check_nonce("uwsmap"); // Axl UWS-7416

// $uvdate = uws_cleanup_request("date");
$uvdate = urvenue_ws_cleanup_request("date"); // Axl UWS-7416
// $uvvenuecode = uws_cleanup_request("venuecode");
$uvvenuecode = urvenue_ws_cleanup_request("venuecode"); // Axl UWS-7416
// $uvreturntempl = uws_cleanup_request("returntempl");
$uvreturntempl = urvenue_ws_cleanup_request("returntempl"); // Axl UWS-7416
// $uvecozone = uws_cleanup_request("ecozone", "ECZ0");
$uvecozone = urvenue_ws_cleanup_request("ecozone", "ECZ0"); // Axl UWS-7416
// $uvmanageentid = uws_cleanup_request("manageentid");
$uvmanageentid = urvenue_ws_cleanup_request("manageentid"); // Axl UWS-7416
$uvmanageentid = (!$uvmanageentid and isset($uws_config_manageentid)) ? $uws_config_manageentid : $uvmanageentid;
// $uvforcelisttype = uws_cleanup_request("forcelisttype");
$uvforcelisttype = urvenue_ws_cleanup_request("forcelisttype"); // Axl UWS-7416
// $uvnogroupings = uws_cleanup_request("nogroupings");
$uvnogroupings = urvenue_ws_cleanup_request("nogroupings"); // Axl UWS-7416
// $uvreturnlang = uws_cleanup_request("returnlang");
$uvreturnlang = urvenue_ws_cleanup_request("returnlang"); // Axl UWS-7416
// $uvhomeecozone = uws_cleanup_request("homeecozone");
$uvhomeecozone = urvenue_ws_cleanup_request("homeecozone"); // Axl UWS-7416
// $uvhomename = uws_cleanup_request("homename");
$uvhomename = urvenue_ws_cleanup_request("homename"); // Axl UWS-7416
// $uvcartcode = uws_cleanup_request("cartcode");
$uvcartcode = urvenue_ws_cleanup_request("cartcode"); // Axl UWS-7416
// $uvreqmaptheme = uws_cleanup_request("theme");
$uvreqmaptheme = urvenue_ws_cleanup_request("theme"); // Axl UWS-7416
// $uvmaptheme = ($uvreqmaptheme) ? "uws-" . $uvreqmaptheme : uws_get_theme();
$uvmaptheme = ($uvreqmaptheme) ? "uws-" . $uvreqmaptheme : urvenue_ws_get_theme(); // Axl UWS-7416
// $uvmappopuptheme = (isset($_REQUEST["poptheme"])) ? "uws-" . uws_cleanup_var($_REQUEST["poptheme"]) : uws_get_popup_theme();
// $uvmappopuptheme = (isset($_REQUEST["poptheme"])) ? "uws-" . urvenue_ws_cleanup_var($_REQUEST["poptheme"]) : urvenue_ws_get_popup_theme(); // Axl UWS-7416
// $uvmappopuptheme = (isset($_REQUEST["poptheme"])) ? "uws-" . urvenue_ws_cleanup_var( wp_unslash( $_REQUEST["poptheme"] ) ) : urvenue_ws_get_popup_theme(); // Axl UWS-7418
$uvmappopuptheme = (isset($_REQUEST["poptheme"])) ? "uws-" . sanitize_text_field( urvenue_ws_cleanup_var( wp_unslash( $_REQUEST["poptheme"] ) ) ) : urvenue_ws_get_popup_theme(); // Axl UWS-7418

if($uvreqmaptheme) $uws_config_uitheme = $uvreqmaptheme;

$uvreturn = array();

if($uvdate and $uvvenuecode and $uvecozone){
    $uvargs = array(
        "date" => $uvdate,
        "venuecode" => $uvvenuecode,
        "ecozone" => $uvecozone,
        "manageentid" => $uvmanageentid,
        "forcelisttype" => $uvforcelisttype,
        "nogroupings" => $uvnogroupings,
        "homeecozone" => $uvhomeecozone,
        "homename" => $uvhomename,
    );

    // $uvreturn = uws_get_map_stage($uvargs);
    $uvreturn = urvenue_ws_get_map_stage($uvargs); // Axl UWS-7416

    if($uvhomeecozone or $uvreturn["isecozonelist"])
        $uvargs["mixecozones"] = 1;

    // $uvmonthnoinventorydates = uws_get_month_noinventory_dates($uvargs);
    $uvmonthnoinventorydates = urvenue_ws_get_month_noinventory_dates($uvargs); // Axl UWS-7416

    $uvreturn["availabilityinfo"] = $uvmonthnoinventorydates;

    // $uvstandardeco = uws_standardize_ecozone($uvecozone);
    $uvstandardeco = urvenue_ws_standardize_ecozone($uvecozone); // Axl UWS-7416
    $uveventcode = "EVE" . str_replace("VEN", "", $uvvenuecode) . str_replace("ECZ", "", $uvstandardeco) . date("Ymd", strtotime($uvdate));
    $uvreturn["eventcode"] = $uveventcode;
}

if($uvreturntempl){
    $uvtempls = array(
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

    $uvreturn["templates"] = $uvtempls;
}

// $uveventdata = uws_get_event($uveventcode);
$uveventdata = urvenue_ws_get_event($uveventcode); // Axl UWS-7416
// $uvcart = uws_get_cart($uvcartcode, $uveventdata);
$uvcart = urvenue_ws_get_cart($uvcartcode, $uveventdata); // Axl UWS-7416
if($uvcart){
    // $uvbkgcheckoutlinks = uws_get_bkgcheckout_links($uvcartcode, $uvcart["accountvars"]);
    $uvbkgcheckoutlinks = urvenue_ws_get_bkgcheckout_links($uvcartcode, $uvcart["accountvars"]); // Axl UWS-7416
    
    $uvcart["checkout-carturl"] = $uvbkgcheckoutlinks["checkout-carturl"];
    $uvcart["checkout-checkurl"] = $uvbkgcheckoutlinks["checkout-checkurl"];
    $uvreturn["cart"] = $uvcart;
}

if($uvreturnlang){
    // $uvreturn["lang"] = uws_lang("front-lang");
    $uvreturn["lang"] = urvenue_ws_lang("front-lang"); // Axl UWS-7416
}

if($uvmaptheme){
    $uvreturn["theme"] = $uvmaptheme;
}

if($uvmappopuptheme){
    $uvreturn["poptheme"] = $uvmappopuptheme;
}    

// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);