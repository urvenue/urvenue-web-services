<?php

global $uws_proxies_lib;

// @egt [UWS-7297]
uws_check_nonce("uwsinventory");

$uveventcode = uws_cleanup_request("eventcode");
$uvcartcode = uws_cleanup_request("cartcode");
$uvintegration = uws_cleanup_request("integration");
$uvreturntempl = uws_cleanup_request("returntempl");
$uvhomeeventcode = uws_cleanup_request("homeeventcode");
$uvhomename = uws_cleanup_request("homename");

$uveventdataandeczmap = uws_get_event($uveventcode, array("returnecozonesmap" => 1));

$uveventdata = $uveventdataandeczmap["event"];
$uveventdataandeczmap = $uveventdataandeczmap["ecozonesmap"];

if(is_array($uveventdata) and $uvhomeeventcode) $uveventdata["homeeventcode"] = $uvhomeeventcode;
if(is_array($uveventdata) and $uvhomename) $uveventdata["homename"] = $uvhomename;
$uvinventorylist = uws_get_eventinventory_list($uveventdata, array("ecozonesmap" => $uveventdataandeczmap));

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

$uvinventorylist["proxies"] = $uws_proxies_lib["inventory"];
$uvinventorylist["cart"] = $uvcart;
$uvinventorylist["templates"] = array();
$uvinventorylist["eventdata"] = $uveventdata;

if($uvreturntempl){
    $uvtempls = array(
        "inventory-itemslist-sel-pop" => uws_get_template("inventory/inventory-itemslist-sel-pop"),
        "inventory-itemslist-sel-item" => uws_get_template("inventory/inventory-itemslist-sel-item"),
        "item-added-btn-content" => uws_get_template("inventory/item-added-btn-content"),
        "item-add-another" => uws_get_template("inventory/item-add-another"),
        "item-list-remove" => uws_get_template("inventory/item-list-remove"),
    );

    $uvinventorylist["templates"] = $uvtempls;
}
    
$uvreturnjson = json_encode($uvinventorylist);
header('Content-Type: application/json');
echo($uvreturnjson);