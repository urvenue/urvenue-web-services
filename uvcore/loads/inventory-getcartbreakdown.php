<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_core_lib;

// @egt [UWS-7297]
// uws_check_nonce("uwsinventory");
urvenue_ws_check_nonce("uwsinventory"); // Axl UWS-7416

// $uvcartcode = uws_cleanup_request("cartcode");
$uvcartcode = urvenue_ws_cleanup_request("cartcode"); // Axl UWS-7416
// $uvmastercode = uws_cleanup_request("mastercode");
$uvmastercode = urvenue_ws_cleanup_request("mastercode"); // Axl UWS-7416
// $uvitemcode = uws_cleanup_request("itemcode");
$uvitemcode = urvenue_ws_cleanup_request("itemcode"); // Axl UWS-7416
// $uvcaldate = uws_cleanup_request("caldate");
$uvcaldate = urvenue_ws_cleanup_request("caldate"); // Axl UWS-7416
// $uvvenuecode = uws_cleanup_request("venuecode");
$uvvenuecode = urvenue_ws_cleanup_request("venuecode"); // Axl UWS-7416
// $uvecozone = uws_cleanup_request("ecozone");
$uvecozone = urvenue_ws_cleanup_request("ecozone"); // Axl UWS-7416
// $uvitemname = uws_cleanup_request("itemname");
$uvitemname = urvenue_ws_cleanup_request("itemname"); // Axl UWS-7416
// $uvpaytype = uws_cleanup_request("paytype");
$uvpaytype = urvenue_ws_cleanup_request("paytype"); // Axl UWS-7416
// $uvguests = uws_cleanup_request("guests");
$uvguests = urvenue_ws_cleanup_request("guests"); // Axl UWS-7416
// $uveventcode = uws_cleanup_request("eventcode");
$uveventcode = urvenue_ws_cleanup_request("eventcode"); // Axl UWS-7416
// $uvtime = uws_cleanup_request("time");
$uvtime = urvenue_ws_cleanup_request("time"); // Axl UWS-7416
// $uvduration = uws_cleanup_request("duration");
$uvduration = urvenue_ws_cleanup_request("duration"); // Axl UWS-7416
// $uvvendor = uws_cleanup_request("vendor");
$uvvendor = urvenue_ws_cleanup_request("vendor"); // Axl UWS-7416
// $uvgotocheck = uws_cleanup_request("gotocheck");
$uvgotocheck = urvenue_ws_cleanup_request("gotocheck"); // Axl UWS-7416
// $uvsectionid = uws_cleanup_request("sectionid");
$uvsectionid = urvenue_ws_cleanup_request("sectionid"); // Axl UWS-7416
// $uvlocationid = uws_cleanup_request("locationid");
$uvlocationid = urvenue_ws_cleanup_request("locationid"); // Axl UWS-7416
// $uvcartmanagementid = uws_cleanup_request("cartmanagementid");
$uvcartmanagementid = urvenue_ws_cleanup_request("cartmanagementid"); // Axl UWS-7416
// $uvmanageentid = uws_cleanup_request("manageentid");
$uvmanageentid = urvenue_ws_cleanup_request("manageentid"); // Axl UWS-7416
// $uvtimetype = uws_cleanup_request("timetype");
$uvtimetype = urvenue_ws_cleanup_request("timetype"); // Axl UWS-7416
// $uvtimecategory = uws_cleanup_request("timecategory");
$uvtimecategory = urvenue_ws_cleanup_request("timecategory"); // Axl UWS-7416
$uvismixedmanagentid = 0;


$uvitemname = urlencode($uvitemname);
// $uvecozone = uws_standardize_ecozone($uvecozone);
$uvecozone = urvenue_ws_standardize_ecozone($uvecozone); // Axl UWS-7416
// $uvecozoneid = uws_ecozone_to_ecoid($uvecozone);
$uvecozoneid = urvenue_ws_ecozone_to_ecoid($uvecozone); // Axl UWS-7416
if(!$uveventcode)
    $uveventcode = "EVE" . str_replace("VEN", "", $uvvenuecode) . str_replace("ECZ", "", $uvecozone) . date("Ymd", strtotime($uvcaldate));
    
$uvvendata = array(
    "venuecode" => $uvvenuecode,
    "eventcode" => $uveventcode,
);

if($uvmanageentid)
    $uvvendata["manageentid"] = $uvmanageentid;

$uvitemdata = array(
    "mastercode" => $uvmastercode,
    "itemcode" => $uvitemcode,
    "caldate" => $uvcaldate,
    "venuecode" => $uvvenuecode,
    "ecozone" => $uvecozoneid,
    "paytype" => $uvpaytype,
    "qty" => "1",
    "guests" => $uvguests,
    "eventcode" => $uveventcode,
    "name" => $uvitemname,
    "time" => $uvtime,
    "duration" => $uvduration,
    "vendor" => $uvvendor,
    "sectionid" => $uvsectionid,
    "locationid" => $uvlocationid
);

if($uvcartcode){
    $uvitemdata["cartcode"] = $uvcartcode;
    // $uvcartendurl = uws_get_apiwvarurl("cart-update", $uvvendata);
    $uvcartendurl = urvenue_ws_get_apiwvarurl("cart-update", $uvvendata); // Axl UWS-7416
}
else{
    // $uvcartendurl = uws_get_apiwvarurl("cart-create", $uvvendata);
    $uvcartendurl = urvenue_ws_get_apiwvarurl("cart-create", $uvvendata); // Axl UWS-7416
}

if($uvtimecategory){//When is OT times detailed
    $uvtimedets = array(
        "type" => $uvtimetype,
        "timetype" => "times_detailed",
        "vendor" => "opentable",
        "category" => $uvtimecategory,
    );
    $uvitemdata["ext_cartjson"] = $uvtimedets;
}

$uvitemdata = http_build_query($uvitemdata);

// TESTING @Axl
// $uvch = curl_init();
// curl_setopt($uvch, CURLOPT_URL, $uvcartendurl);
// curl_setopt($uvch, CURLOPT_POST, true);
// curl_setopt($uvch, CURLOPT_CUSTOMREQUEST, "POST");
// curl_setopt($uvch, CURLOPT_RETURNTRANSFER, true); 
// curl_setopt($uvch, CURLOPT_POSTFIELDS, $uvitemdata);
// $uvresultraw = curl_exec($uvch);
$uvresponse = wp_remote_post($uvcartendurl, array(
    'body' => $uvitemdata,
    'timeout' => 60,
));
$uvresultraw = wp_remote_retrieve_body($uvresponse);

$uvcartfeedresponse = json_decode($uvresultraw, true);

// TESTING @Axl
// curl_close($uvch);


//Build Response
$uvreturn = array();
$uvitemsbasecomponents = array();
$uvcarttotals = "";

if(!$uvrecreate and is_array($uvcartfeedresponse) and $uvcartfeedresponse["uv"]["success"]["status"] == "success"){
    $uvcartitems = $uvcartfeedresponse["uv"]["data"]["cart"];
    $uvcarttotals = $uvcartfeedresponse["uv"]["data"]["totals"];

    if(is_array($uvcartitems)){
        foreach($uvcartitems as $uvitemcartcode => $uvcartitem){
            if($uvcartitem["pricing"] and $uvcartitem["pricing"]["componentbreakdowns"]){
                $uvcartitemcomps = $uvcartitem["pricing"]["componentbreakdowns"];

                if(is_array($uvcartitemcomps) and $uvcartitemcomps[0]){
                    $uvitemsbasecomponents[$uvitemcartcode] = array(
                        "pricingdisplay" => $uvcartitemcomps[0]["pricingdisplay"],
                        "totalbase" => $uvcartitemcomps[0]["totalbase"],
                    );
                }
            }
        }
    }
}


$uvreturn["itemsbasecomponents"] = $uvitemsbasecomponents;
$uvreturn["totals"] = $uvcarttotals;


// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
// echo($uvreturnjson);
echo( $uvreturnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416