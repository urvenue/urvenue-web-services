<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_proxies_lib;

// @egt [UWS-7297]
uws_check_nonce("uwsinventory");

$uveventcode = uws_cleanup_request("eventcode");
$uvcartcode = uws_cleanup_request("cartcode");
$uvintegration = uws_cleanup_request("integration");
$uvreturntempl = uws_cleanup_request("returntempl");
$uvglobaltype = uws_cleanup_request("globaltype");
$uvshoweventsdropdown = uws_cleanup_request("showeventsdropdown", 0);
$uvbooktypename = uws_cleanup_request("booktypename");
$uvmixecozones = uws_cleanup_request("mixecozones", 0);

// Check if ecozones are valid (all must have non-empty names)
$uvevtdata = uws_get_event($uveventcode);
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
$uvaddonvenues = uws_cleanup_request("addonvenues", 0);
$uvmainvenuecode = uws_cleanup_request("mainvenuecode");
$uvvenuecode = uws_cleanup_request("venuecode");
$uvmicrcodode = uws_cleanup_request("microcode");
$uvhomeeventcode = uws_cleanup_request("homeeventcode");

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

$uveventdata = uws_get_event($uveventcode, $uvargs);

if($uvmixecozones && is_array($uveventdata) && isset($uveventdata["ecozonesmap"])) {
    $uvargs["ecozonesmap"] = $uveventdata["ecozonesmap"];
}

$uvinventorylist = uws_get_eventinventory_list($uveventdata, $uvargs);

$uvcart = uws_get_cart($uvcartcode, $uveventdata);
if($uvcart){
    $uvbkgcheckoutlinks = uws_get_bkgcheckout_links($uvcartcode, $uvcart["accountvars"]);
    
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
    $uveventsdropdown = uws_get_date_events_dropdown($uveventdata);
}

$uvinventorylist["proxies"] = $uws_proxies_lib["inventory"];
$uvinventorylist["cart"] = $uvcart;
$uvinventorylist["templates"] = array();
$uvinventorylist["eventdata"] = $uveventdata;
$uvinventorylist["eventsel"] = $uveventsdropdown;

if($uvreturntempl){
    $uvtempls = array(
        "inventory-itemslist-sel-pop" => uws_get_template("inventory/inventory-itemslist-sel-pop"),
        "inventory-itemslist-sel-item" => uws_get_template("inventory/inventory-itemslist-sel-item"),
        "item-added-btn-content" => uws_get_template("inventory/item-added-btn-content"),
        "item-add-another" => uws_get_template("inventory/item-add-another"),
    );

    $uvinventorylist["templates"] = $uvtempls;
}
    
// @Axl
// $uvreturnjson = json_encode($uvinventorylist);
$uvreturnjson = wp_json_encode($uvinventorylist);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);