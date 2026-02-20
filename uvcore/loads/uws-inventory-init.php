<?php

global $uws_proxies_lib;

$uveventcode = (isset($_REQUEST["eventcode"])) ? uws_cleanup_var($_REQUEST["eventcode"]) : "";
$uvcartcode = (isset($_REQUEST["cartcode"])) ? uws_cleanup_var($_REQUEST["cartcode"]) : "";
$uvintegration = (isset($_REQUEST["integration"])) ? uws_cleanup_var($_REQUEST["integration"]) : "";
$uvreturntempl = (isset($_REQUEST["returntempl"])) ? uws_cleanup_var($_REQUEST["returntempl"]) : "";
$uvhomeeventcode = (isset($_REQUEST["homeeventcode"])) ? uws_cleanup_var($_REQUEST["homeeventcode"]) : "";
$uvhomename = (isset($_REQUEST["homename"])) ? uws_cleanup_var($_REQUEST["homename"]) : "";

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
    
// @Axl
// $uvreturnjson = json_encode($uvinventorylist);
$uvreturnjson = wp_json_encode($uvinventorylist);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);