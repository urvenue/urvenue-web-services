<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_proxies_lib;

// @egt [UWS-7297]
// uws_check_nonce("urvenue_ws_inventory");
urvenue_ws_check_nonce("urvenue_ws_inventory"); // Axl UWS-7416

// $urvenue_ws_eventcode = uws_cleanup_request("eventcode");
$urvenue_ws_eventcode = urvenue_ws_cleanup_request("eventcode"); // Axl UWS-7416
// $urvenue_ws_cartcode = uws_cleanup_request("cartcode");
$urvenue_ws_cartcode = urvenue_ws_cleanup_request("cartcode"); // Axl UWS-7416
// $urvenue_ws_integration = uws_cleanup_request("integration");
$urvenue_ws_integration = urvenue_ws_cleanup_request("integration"); // Axl UWS-7416
// $urvenue_ws_returntempl = uws_cleanup_request("returntempl");
$urvenue_ws_returntempl = urvenue_ws_cleanup_request("returntempl"); // Axl UWS-7416
// $urvenue_ws_homeeventcode = uws_cleanup_request("homeeventcode");
$urvenue_ws_homeeventcode = urvenue_ws_cleanup_request("homeeventcode"); // Axl UWS-7416
// $urvenue_ws_homename = uws_cleanup_request("homename");
$urvenue_ws_homename = urvenue_ws_cleanup_request("homename"); // Axl UWS-7416

// $urvenue_ws_eventdataandeczmap = uws_get_event($urvenue_ws_eventcode, array("returnecozonesmap" => 1));
$urvenue_ws_eventdataandeczmap = urvenue_ws_get_event($urvenue_ws_eventcode, array("returnecozonesmap" => 1)); // Axl UWS-7416

$urvenue_ws_eventdata = $urvenue_ws_eventdataandeczmap["event"];
$urvenue_ws_eventdataandeczmap = $urvenue_ws_eventdataandeczmap["ecozonesmap"];

if(is_array($urvenue_ws_eventdata) and $urvenue_ws_homeeventcode) $urvenue_ws_eventdata["homeeventcode"] = $urvenue_ws_homeeventcode;
if(is_array($urvenue_ws_eventdata) and $urvenue_ws_homename) $urvenue_ws_eventdata["homename"] = $urvenue_ws_homename;
// $urvenue_ws_inventorylist = uws_get_eventinventory_list($urvenue_ws_eventdata, array("ecozonesmap" => $urvenue_ws_eventdataandeczmap));
$urvenue_ws_inventorylist = urvenue_ws_get_eventinventory_list($urvenue_ws_eventdata, array("ecozonesmap" => $urvenue_ws_eventdataandeczmap)); // Axl UWS-7416

// $urvenue_ws_cart = uws_get_cart($urvenue_ws_cartcode, $urvenue_ws_eventdata);
$urvenue_ws_cart = urvenue_ws_get_cart($urvenue_ws_cartcode, $urvenue_ws_eventdata); // Axl UWS-7416
if($urvenue_ws_cart){
    // $urvenue_ws_bkgcheckoutlinks = uws_get_bkgcheckout_links($urvenue_ws_cartcode, $urvenue_ws_cart["accountvars"]);
    $urvenue_ws_bkgcheckoutlinks = urvenue_ws_get_bkgcheckout_links($urvenue_ws_cartcode, $urvenue_ws_cart["accountvars"]); // Axl UWS-7416
    
    $urvenue_ws_cart["checkout-carturl"] = $urvenue_ws_bkgcheckoutlinks["checkout-carturl"];
    $urvenue_ws_cart["checkout-checkurl"] = $urvenue_ws_bkgcheckoutlinks["checkout-checkurl"];
}

if(!is_array($urvenue_ws_inventorylist))
    $urvenue_ws_inventorylist = array();

if(is_array($urvenue_ws_core_lib) and (isset($urvenue_ws_core_lib["system"]["checkouttype"])) and $urvenue_ws_core_lib["system"]["checkouttype"] == "uvcheckout")
    $urvenue_ws_inventorylist["issidecheck"] = 1;

$urvenue_ws_inventorylist["proxies"] = $urvenue_ws_proxies_lib["inventory"];
$urvenue_ws_inventorylist["cart"] = $urvenue_ws_cart;
$urvenue_ws_inventorylist["templates"] = array();
$urvenue_ws_inventorylist["eventdata"] = $urvenue_ws_eventdata;

if($urvenue_ws_returntempl){
    $urvenue_ws_templs = array(
        // "inventory-itemslist-sel-pop" => uws_get_template("inventory/inventory-itemslist-sel-pop"),
        "inventory-itemslist-sel-pop" => urvenue_ws_get_template("inventory/inventory-itemslist-sel-pop"), // Axl UWS-7416
        // "inventory-itemslist-sel-item" => uws_get_template("inventory/inventory-itemslist-sel-item"),
        "inventory-itemslist-sel-item" => urvenue_ws_get_template("inventory/inventory-itemslist-sel-item"), // Axl UWS-7416
        // "item-added-btn-content" => uws_get_template("inventory/item-added-btn-content"),
        "item-added-btn-content" => urvenue_ws_get_template("inventory/item-added-btn-content"), // Axl UWS-7416
        // "item-add-another" => uws_get_template("inventory/item-add-another"),
        "item-add-another" => urvenue_ws_get_template("inventory/item-add-another"), // Axl UWS-7416
        // "item-list-remove" => uws_get_template("inventory/item-list-remove"),
        "item-list-remove" => urvenue_ws_get_template("inventory/item-list-remove"), // Axl UWS-7416
    );

    $urvenue_ws_inventorylist["templates"] = $urvenue_ws_templs;
}
    
// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_inventorylist);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_inventorylist);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416