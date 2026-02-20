<?php

global $uws_path, $uws_config_uitheme;

include_once($uws_path . "/includes/map-functions.php");

$uvdate = (isset($_REQUEST["date"])) ? uws_cleanup_var($_REQUEST["date"]) : "";
$uvvenuecode = (isset($_REQUEST["venuecode"])) ? uws_cleanup_var($_REQUEST["venuecode"]) : "";
$uvreturntempl = (isset($_REQUEST["returntempl"])) ? uws_cleanup_var($_REQUEST["returntempl"]) : "";
$uvecozone = (isset($_REQUEST["ecozone"])) ? uws_cleanup_var($_REQUEST["ecozone"]) : "ECZ0";
$uvmanageentid = (isset($_REQUEST["manageentid"])) ? uws_cleanup_var($_REQUEST["manageentid"]) : "";
$uvmanageentid = (!$uvmanageentid and isset($uws_config_manageentid)) ? $uws_config_manageentid : $uvmanageentid;
$uvforcelisttype = (isset($_REQUEST["forcelisttype"])) ? uws_cleanup_var($_REQUEST["forcelisttype"]) : "";
$uvnogroupings = (isset($_REQUEST["nogroupings"])) ? uws_cleanup_var($_REQUEST["nogroupings"]) : "";
$uvreturnlang = (isset($_REQUEST["returnlang"])) ? uws_cleanup_var($_REQUEST["returnlang"]) : "";
$uvhomeecozone = (isset($_REQUEST["homeecozone"])) ? uws_cleanup_var($_REQUEST["homeecozone"]) : "";
$uvhomename = (isset($_REQUEST["homename"])) ? uws_cleanup_var($_REQUEST["homename"]) : "";
$uvcartcode = (isset($_REQUEST["cartcode"])) ? uws_cleanup_var($_REQUEST["cartcode"]) : "";
$uvreqmaptheme = (isset($_REQUEST["theme"])) ? uws_cleanup_var($_REQUEST["theme"]) : "";
$uvmaptheme = ($uvreqmaptheme) ? "uws-" . $uvreqmaptheme : uws_get_theme();
$uvmappopuptheme = (isset($_REQUEST["poptheme"])) ? "uws-" . uws_cleanup_var($_REQUEST["poptheme"]) : uws_get_popup_theme();

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

    $uvreturn = uws_get_map_stage($uvargs);

    if($uvhomeecozone or $uvreturn["isecozonelist"])
        $uvargs["mixecozones"] = 1;

    $uvmonthnoinventorydates = uws_get_month_noinventory_dates($uvargs);

    $uvreturn["availabilityinfo"] = $uvmonthnoinventorydates;

    $uvstandardeco = uws_standardize_ecozone($uvecozone);
    $uveventcode = "EVE" . str_replace("VEN", "", $uvvenuecode) . str_replace("ECZ", "", $uvstandardeco) . date("Ymd", strtotime($uvdate));
    $uvreturn["eventcode"] = $uveventcode;
}

if($uvreturntempl){
    $uvtempls = array(
        "map-item-box" => uws_get_template("map/map-item-box"),
        "map-item-tooltip" => uws_get_template("map/map-item-tooltip"),
        "map-list-item-tooltip" => uws_get_template("map/map-list-item-tooltip"),
        "map-itemslist-sel-pop" => uws_get_template("map/map-itemslist-sel-pop"),
        "map-itemslist-sel-item" => uws_get_template("map/map-itemslist-sel-item"),
        "map-multiitem-tooltip-cont" => uws_get_template("map/map-multiitem-tooltip-cont"),
        "map-multiitem-tooltip-item" => uws_get_template("map/map-multiitem-tooltip-item"),
        "item-added-btn-content" => uws_get_template("inventory/item-added-btn-content"),
        "item-add-another" => uws_get_template("inventory/item-add-another"),
    );

    $uvreturn["templates"] = $uvtempls;
}

$uveventdata = uws_get_event($uveventcode);
$uvcart = uws_get_cart($uvcartcode, $uveventdata);
if($uvcart){
    $uvbkgcheckoutlinks = uws_get_bkgcheckout_links($uvcartcode, $uvcart["accountvars"]);
    
    $uvcart["checkout-carturl"] = $uvbkgcheckoutlinks["checkout-carturl"];
    $uvcart["checkout-checkurl"] = $uvbkgcheckoutlinks["checkout-checkurl"];
    $uvreturn["cart"] = $uvcart;
}

if($uvreturnlang){
    $uvreturn["lang"] = uws_lang("front-lang");
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