<?php

global $uws_proxies_lib;

$uveventcode = (isset($_REQUEST["eventcode"])) ? uws_cleanup_var($_REQUEST["eventcode"]) : "";
$uvcartcode = (isset($_REQUEST["cartcode"])) ? uws_cleanup_var($_REQUEST["cartcode"]) : "";
$uvintegration = (isset($_REQUEST["integration"])) ? uws_cleanup_var($_REQUEST["integration"]) : "";
$uvreturntempl = (isset($_REQUEST["returntempl"])) ? uws_cleanup_var($_REQUEST["returntempl"]) : "";
$uvglobaltype = (isset($_REQUEST["globaltype"])) ? uws_cleanup_var($_REQUEST["globaltype"]) : "";
$uvshoweventsdropdown = (isset($_REQUEST["showeventsdropdown"])) ? uws_cleanup_var($_REQUEST["showeventsdropdown"]) : 0;
$uvbooktypename = (isset($_REQUEST["booktypename"])) ? uws_cleanup_var($_REQUEST["booktypename"]) : "";
$uvmixecozones = (isset($_REQUEST["mixecozones"])) ? uws_cleanup_var($_REQUEST["mixecozones"]) : 0;

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
$uvaddonvenues = (isset($_REQUEST["addonvenues"])) ? uws_cleanup_var($_REQUEST["addonvenues"]) : 0;
$uvmainvenuecode = (isset($_REQUEST["mainvenuecode"])) ? uws_cleanup_var($_REQUEST["mainvenuecode"]) : "";
$uvvenuecode = (isset($_REQUEST["venuecode"])) ? uws_cleanup_var($_REQUEST["venuecode"]) : "";
$uvmicrcodode = (isset($_REQUEST["microcode"])) ? uws_cleanup_var($_REQUEST["microcode"]) : "";
$uvhomeeventcode = (isset($_REQUEST["homeeventcode"])) ? uws_cleanup_var($_REQUEST["homeeventcode"]) : "";

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