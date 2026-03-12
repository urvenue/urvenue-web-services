<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_proxies_lib;

// @egt [UWS-7297]
// uws_check_nonce("uwsinventory");
urvenue_ws_check_nonce("uwsinventory"); // Axl UWS-7416

// $uveventcode = uws_cleanup_request("eventcode");
$uveventcode = urvenue_ws_cleanup_request("eventcode"); // Axl UWS-7416
// $uvcartcode = uws_cleanup_request("cartcode");
$uvcartcode = urvenue_ws_cleanup_request("cartcode"); // Axl UWS-7416
// $uvintegration = uws_cleanup_request("integration");
$uvintegration = urvenue_ws_cleanup_request("integration"); // Axl UWS-7416
// $uvreturntempl = uws_cleanup_request("returntempl");
$uvreturntempl = urvenue_ws_cleanup_request("returntempl"); // Axl UWS-7416
// $uvglobaltype = uws_cleanup_request("globaltype");
$uvglobaltype = urvenue_ws_cleanup_request("globaltype"); // Axl UWS-7416
// $uvshoweventsdropdown = uws_cleanup_request("showeventsdropdown", 0);
$uvshoweventsdropdown = urvenue_ws_cleanup_request("showeventsdropdown", 0); // Axl UWS-7416
// $uvbooktypename = uws_cleanup_request("booktypename");
$uvbooktypename = urvenue_ws_cleanup_request("booktypename"); // Axl UWS-7416
// $uvmixecozones = uws_cleanup_request("mixecozones", 0);
$uvmixecozones = urvenue_ws_cleanup_request("mixecozones", 0); // Axl UWS-7416

// Check if ecozones are valid (all must have non-empty names)
// $uvevtdata = uws_get_event($uveventcode);
$uvevtdata = urvenue_ws_get_event($uveventcode); // Axl UWS-7416
if($uvmixecozones && isset($uvevtdata['ecozones']) && is_array($uvevtdata['ecozones'])) {
    $uvevtinvalidecz = false;
    foreach($uvevtdata['ecozones'] as $ecozone) {
        if(empty($ecozone['name'])) {
            $uvevtinvalidecz = true;
            break;
        }
    }
    if($uvevtinvalidecz)
        $uvmixecozones = 0;
}

$uvargs = array(
    "globaltype" => $uvglobaltype, 
    "booktypename" => $uvbooktypename,
);

// Add-On Venues
// $uvaddonvenues = uws_cleanup_request("addonvenues", 0);
$uvaddonvenues = urvenue_ws_cleanup_request("addonvenues", 0); // Axl UWS-7416
// $uvmainvenuecode = uws_cleanup_request("mainvenuecode");
$uvmainvenuecode = urvenue_ws_cleanup_request("mainvenuecode"); // Axl UWS-7416
// $uvvenuecode = uws_cleanup_request("venuecode");
$uvvenuecode = urvenue_ws_cleanup_request("venuecode"); // Axl UWS-7416
// $uvmicrcodode = uws_cleanup_request("microcode");
$uvmicrcodode = urvenue_ws_cleanup_request("microcode"); // Axl UWS-7416
// $uvhomeeventcode = uws_cleanup_request("homeeventcode");
$uvhomeeventcode = urvenue_ws_cleanup_request("homeeventcode"); // Axl UWS-7416

if($uvaddonvenues) {
    $uvargs["mainvenuecode"] = $uvmainvenuecode;
    $uvargs["addonvenuecode"] = $uvvenuecode;
    $uvargs["microcode"] = $uvmicrcodode;
    $uvargs["homeeventcode"] = $uvhomeeventcode;
}

if($uvmixecozones) {
    $uvargs["mixecozones"] = 1;
    $uvargs["returnecozonesmap"] = 1;
}

// $uveventdata = uws_get_event($uveventcode, $uvargs);
$uveventdata = urvenue_ws_get_event($uveventcode, $uvargs); // Axl UWS-7416

if($uvmixecozones && is_array($uveventdata) && isset($uveventdata["ecozonesmap"])) {
    $uvargs["ecozonesmap"] = $uveventdata["ecozonesmap"];
}

// $uvinventorylist = uws_get_eventinventory_list($uveventdata, $uvargs);
$uvinventorylist = urvenue_ws_get_eventinventory_list($uveventdata, $uvargs); // Axl UWS-7416

// $uvcart = uws_get_cart($uvcartcode, $uveventdata);
$uvcart = urvenue_ws_get_cart($uvcartcode, $uveventdata); // Axl UWS-7416
if($uvcart){
    // $uvbkgcheckoutlinks = uws_get_bkgcheckout_links($uvcartcode, $uvcart["accountvars"]);
    $uvbkgcheckoutlinks = urvenue_ws_get_bkgcheckout_links($uvcartcode, $uvcart["accountvars"]); // Axl UWS-7416
    
    $uvcart["checkout-carturl"] = $uvbkgcheckoutlinks["checkout-carturl"];
    $uvcart["checkout-checkurl"] = $uvbkgcheckoutlinks["checkout-checkurl"];
}

if(!is_array($uvinventorylist))
    $uvinventorylist = array();

if(is_array($uws_core_lib) and (isset($uws_core_lib["system"]["checkouttype"])) and $uws_core_lib["system"]["checkouttype"] == "uvcheckout")
    $uvinventorylist["issidecheck"] = 1;

//Events Dropdown
$uveventsdropdown = "";
if($uvshoweventsdropdown){
    // $uveventsdropdown = uws_get_date_events_dropdown($uveventdata);
    $uveventsdropdown = urvenue_ws_get_date_events_dropdown($uveventdata); // Axl UWS-7416
}

$uvinventorylist["proxies"] = $uws_proxies_lib["inventory"];
$uvinventorylist["cart"] = $uvcart;
$uvinventorylist["templates"] = array();
$uvinventorylist["eventdata"] = $uveventdata;
$uvinventorylist["eventsel"] = $uveventsdropdown;

if($uvreturntempl){
    $uvtempls = array(
        // "inventory-itemslist-sel-pop" => uws_get_template("inventory/inventory-itemslist-sel-pop"),
        "inventory-itemslist-sel-pop" => urvenue_ws_get_template("inventory/inventory-itemslist-sel-pop"), // Axl UWS-7416
        // "inventory-itemslist-sel-item" => uws_get_template("inventory/inventory-itemslist-sel-item"),
        "inventory-itemslist-sel-item" => urvenue_ws_get_template("inventory/inventory-itemslist-sel-item"), // Axl UWS-7416
        // "item-added-btn-content" => uws_get_template("inventory/item-added-btn-content"),
        "item-added-btn-content" => urvenue_ws_get_template("inventory/item-added-btn-content"), // Axl UWS-7416
        // "item-add-another" => uws_get_template("inventory/item-add-another"),
        "item-add-another" => urvenue_ws_get_template("inventory/item-add-another"), // Axl UWS-7416
    );

    $uvinventorylist["templates"] = $uvtempls;
}
    
// @Axl
// $uvreturnjson = json_encode($uvinventorylist);
$uvreturnjson = wp_json_encode($uvinventorylist);
// @Axl End
header('Content-Type: application/json');
// echo($uvreturnjson);
echo( $uvreturnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416