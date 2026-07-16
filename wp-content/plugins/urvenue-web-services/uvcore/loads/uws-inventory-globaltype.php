<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_proxies_lib;

urvenue_ws_check_nonce("urvenue_ws_inventory");

$urvenue_ws_eventcode = urvenue_ws_cleanup_request("eventcode");
$urvenue_ws_cartcode = urvenue_ws_cleanup_request("cartcode");
$urvenue_ws_integration = urvenue_ws_cleanup_request("integration");
$urvenue_ws_returntempl = urvenue_ws_cleanup_request("returntempl");
$urvenue_ws_globaltype = urvenue_ws_cleanup_request("globaltype");
$urvenue_ws_showeventsdropdown = urvenue_ws_cleanup_request("showeventsdropdown", 0);
$urvenue_ws_booktypename = urvenue_ws_cleanup_request("booktypename");
$urvenue_ws_mixecozones = urvenue_ws_cleanup_request("mixecozones", 0);

// Check if ecozones are valid (all must have non-empty names)
$urvenue_ws_evtdata = urvenue_ws_get_event($urvenue_ws_eventcode);
if($urvenue_ws_mixecozones && isset($urvenue_ws_evtdata['ecozones']) && is_array($urvenue_ws_evtdata['ecozones'])) {
    $urvenue_ws_evtinvalidecz = false;
    foreach($urvenue_ws_evtdata['ecozones'] as $urvenue_ws_ecozone) {
        if(empty($urvenue_ws_ecozone['name'])) {
            $urvenue_ws_evtinvalidecz = true;
            break;
        }
    }
    if($urvenue_ws_evtinvalidecz)
        $urvenue_ws_mixecozones = 0;
}

$urvenue_ws_args = array(
    "globaltype" => $urvenue_ws_globaltype,
    "booktypename" => $urvenue_ws_booktypename,
);

// Add-On Venues
$urvenue_ws_addonvenues = urvenue_ws_cleanup_request("addonvenues", 0);
$urvenue_ws_mainvenuecode = urvenue_ws_cleanup_request("mainvenuecode");
$urvenue_ws_venuecode = urvenue_ws_cleanup_request("venuecode");
$urvenue_ws_micrcodode = urvenue_ws_cleanup_request("microcode");
$urvenue_ws_homeeventcode = urvenue_ws_cleanup_request("homeeventcode");

if($urvenue_ws_addonvenues) {
    $urvenue_ws_args["mainvenuecode"] = $urvenue_ws_mainvenuecode;
    $urvenue_ws_args["addonvenuecode"] = $urvenue_ws_venuecode;
    $urvenue_ws_args["microcode"] = $urvenue_ws_micrcodode;
    $urvenue_ws_args["homeeventcode"] = $urvenue_ws_homeeventcode;
}

if($urvenue_ws_mixecozones) {
    $urvenue_ws_args["mixecozones"] = 1;
    $urvenue_ws_args["returnecozonesmap"] = 1;
}

$urvenue_ws_eventdata = urvenue_ws_get_event($urvenue_ws_eventcode, $urvenue_ws_args);

if($urvenue_ws_mixecozones && is_array($urvenue_ws_eventdata) && isset($urvenue_ws_eventdata["ecozonesmap"])) {
    $urvenue_ws_args["ecozonesmap"] = $urvenue_ws_eventdata["ecozonesmap"];
}

$urvenue_ws_inventorylist = urvenue_ws_get_eventinventory_list($urvenue_ws_eventdata, $urvenue_ws_args);

$urvenue_ws_cart = urvenue_ws_get_cart($urvenue_ws_cartcode, $urvenue_ws_eventdata);
if($urvenue_ws_cart){
    $urvenue_ws_bkgcheckoutlinks = urvenue_ws_get_bkgcheckout_links($urvenue_ws_cartcode, $urvenue_ws_cart["accountvars"]);

    $urvenue_ws_cart["checkout-carturl"] = $urvenue_ws_bkgcheckoutlinks["checkout-carturl"];
    $urvenue_ws_cart["checkout-checkurl"] = $urvenue_ws_bkgcheckoutlinks["checkout-checkurl"];
}

if(!is_array($urvenue_ws_inventorylist))
    $urvenue_ws_inventorylist = array();

if(is_array($urvenue_ws_core_lib) and (isset($urvenue_ws_core_lib["system"]["checkouttype"])) and $urvenue_ws_core_lib["system"]["checkouttype"] == "uvcheckout")
    $urvenue_ws_inventorylist["issidecheck"] = 1;

//Events Dropdown
$urvenue_ws_eventsdropdown = "";
if($urvenue_ws_showeventsdropdown){
    $urvenue_ws_eventsdropdown = urvenue_ws_get_date_events_dropdown($urvenue_ws_eventdata);
}

$urvenue_ws_inventorylist["proxies"] = $urvenue_ws_proxies_lib["inventory"];
$urvenue_ws_inventorylist["cart"] = $urvenue_ws_cart;
$urvenue_ws_inventorylist["templates"] = array();
$urvenue_ws_inventorylist["eventdata"] = $urvenue_ws_eventdata;
$urvenue_ws_inventorylist["eventsel"] = $urvenue_ws_eventsdropdown;

if($urvenue_ws_returntempl){
    $urvenue_ws_templs = array(
        "inventory-itemslist-sel-pop" => urvenue_ws_get_template("inventory/inventory-itemslist-sel-pop"),
        "inventory-itemslist-sel-item" => urvenue_ws_get_template("inventory/inventory-itemslist-sel-item"),
        "item-added-btn-content" => urvenue_ws_get_template("inventory/item-added-btn-content"),
        "item-add-another" => urvenue_ws_get_template("inventory/item-add-another"),
    );

    $urvenue_ws_inventorylist["templates"] = $urvenue_ws_templs;
}

$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_inventorylist);
header('Content-Type: application/json');
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode()
