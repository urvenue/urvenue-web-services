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
// $uvhomeeventcode = uws_cleanup_request("homeeventcode");
$uvhomeeventcode = urvenue_ws_cleanup_request("homeeventcode"); // Axl UWS-7416
// $uvhomename = uws_cleanup_request("homename");
$uvhomename = urvenue_ws_cleanup_request("homename"); // Axl UWS-7416

// $uveventdataandeczmap = uws_get_event($uveventcode, array("returnecozonesmap" => 1));
$uveventdataandeczmap = urvenue_ws_get_event($uveventcode, array("returnecozonesmap" => 1)); // Axl UWS-7416

$uveventdata = $uveventdataandeczmap["event"];
$uveventdataandeczmap = $uveventdataandeczmap["ecozonesmap"];

if(is_array($uveventdata) and $uvhomeeventcode) $uveventdata["homeeventcode"] = $uvhomeeventcode;
if(is_array($uveventdata) and $uvhomename) $uveventdata["homename"] = $uvhomename;
// $uvinventorylist = uws_get_eventinventory_list($uveventdata, array("ecozonesmap" => $uveventdataandeczmap));
$uvinventorylist = urvenue_ws_get_eventinventory_list($uveventdata, array("ecozonesmap" => $uveventdataandeczmap)); // Axl UWS-7416

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

$uvinventorylist["proxies"] = $uws_proxies_lib["inventory"];
$uvinventorylist["cart"] = $uvcart;
$uvinventorylist["templates"] = array();
$uvinventorylist["eventdata"] = $uveventdata;

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
        // "item-list-remove" => uws_get_template("inventory/item-list-remove"),
        "item-list-remove" => urvenue_ws_get_template("inventory/item-list-remove"), // Axl UWS-7416
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