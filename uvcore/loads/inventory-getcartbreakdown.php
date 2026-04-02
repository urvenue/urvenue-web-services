<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_core_lib;

// @egt [UWS-7297]
// uws_check_nonce("uwsinventory");
urvenue_ws_check_nonce("uwsinventory"); // Axl UWS-7416

// $urvenue_ws_cartcode = uws_cleanup_request("cartcode");
$urvenue_ws_cartcode = urvenue_ws_cleanup_request("cartcode"); // Axl UWS-7416
// $urvenue_ws_mastercode = uws_cleanup_request("mastercode");
$urvenue_ws_mastercode = urvenue_ws_cleanup_request("mastercode"); // Axl UWS-7416
// $urvenue_ws_itemcode = uws_cleanup_request("itemcode");
$urvenue_ws_itemcode = urvenue_ws_cleanup_request("itemcode"); // Axl UWS-7416
// $urvenue_ws_caldate = uws_cleanup_request("caldate");
$urvenue_ws_caldate = urvenue_ws_cleanup_request("caldate"); // Axl UWS-7416
// $urvenue_ws_venuecode = uws_cleanup_request("venuecode");
$urvenue_ws_venuecode = urvenue_ws_cleanup_request("venuecode"); // Axl UWS-7416
// $urvenue_ws_ecozone = uws_cleanup_request("ecozone");
$urvenue_ws_ecozone = urvenue_ws_cleanup_request("ecozone"); // Axl UWS-7416
// $urvenue_ws_itemname = uws_cleanup_request("itemname");
$urvenue_ws_itemname = urvenue_ws_cleanup_request("itemname"); // Axl UWS-7416
// $urvenue_ws_paytype = uws_cleanup_request("paytype");
$urvenue_ws_paytype = urvenue_ws_cleanup_request("paytype"); // Axl UWS-7416
// $urvenue_ws_guests = uws_cleanup_request("guests");
$urvenue_ws_guests = urvenue_ws_cleanup_request("guests"); // Axl UWS-7416
// $urvenue_ws_eventcode = uws_cleanup_request("eventcode");
$urvenue_ws_eventcode = urvenue_ws_cleanup_request("eventcode"); // Axl UWS-7416
// $urvenue_ws_time = uws_cleanup_request("time");
$urvenue_ws_time = urvenue_ws_cleanup_request("time"); // Axl UWS-7416
// $urvenue_ws_duration = uws_cleanup_request("duration");
$urvenue_ws_duration = urvenue_ws_cleanup_request("duration"); // Axl UWS-7416
// $urvenue_ws_vendor = uws_cleanup_request("vendor");
$urvenue_ws_vendor = urvenue_ws_cleanup_request("vendor"); // Axl UWS-7416
// $urvenue_ws_gotocheck = uws_cleanup_request("gotocheck");
$urvenue_ws_gotocheck = urvenue_ws_cleanup_request("gotocheck"); // Axl UWS-7416
// $urvenue_ws_sectionid = uws_cleanup_request("sectionid");
$urvenue_ws_sectionid = urvenue_ws_cleanup_request("sectionid"); // Axl UWS-7416
// $urvenue_ws_locationid = uws_cleanup_request("locationid");
$urvenue_ws_locationid = urvenue_ws_cleanup_request("locationid"); // Axl UWS-7416
// $urvenue_ws_cartmanagementid = uws_cleanup_request("cartmanagementid");
$urvenue_ws_cartmanagementid = urvenue_ws_cleanup_request("cartmanagementid"); // Axl UWS-7416
// $urvenue_ws_manageentid = uws_cleanup_request("manageentid");
$urvenue_ws_manageentid = urvenue_ws_cleanup_request("manageentid"); // Axl UWS-7416
// $urvenue_ws_timetype = uws_cleanup_request("timetype");
$urvenue_ws_timetype = urvenue_ws_cleanup_request("timetype"); // Axl UWS-7416
// $urvenue_ws_timecategory = uws_cleanup_request("timecategory");
$urvenue_ws_timecategory = urvenue_ws_cleanup_request("timecategory"); // Axl UWS-7416
// $uvismixedmanagentid = 0;
$urvenue_ws_ismixedmanagentid = 0; // Axl UWS-7634


$urvenue_ws_itemname = urlencode($urvenue_ws_itemname);
// $urvenue_ws_ecozone = uws_standardize_ecozone($urvenue_ws_ecozone);
$urvenue_ws_ecozone = urvenue_ws_standardize_ecozone($urvenue_ws_ecozone); // Axl UWS-7416
// $urvenue_ws_ecozoneid = uws_ecozone_to_ecoid($urvenue_ws_ecozone);
$urvenue_ws_ecozoneid = urvenue_ws_ecozone_to_ecoid($urvenue_ws_ecozone); // Axl UWS-7416
if(!$urvenue_ws_eventcode)
    $urvenue_ws_eventcode = "EVE" . str_replace("VEN", "", $urvenue_ws_venuecode) . str_replace("ECZ", "", $urvenue_ws_ecozone) . date("Ymd", strtotime($urvenue_ws_caldate));
    
$urvenue_ws_vendata = array(
    "venuecode" => $urvenue_ws_venuecode,
    "eventcode" => $urvenue_ws_eventcode,
);

if($urvenue_ws_manageentid)
    $urvenue_ws_vendata["manageentid"] = $urvenue_ws_manageentid;

$urvenue_ws_itemdata = array(
    "mastercode" => $urvenue_ws_mastercode,
    "itemcode" => $urvenue_ws_itemcode,
    "caldate" => $urvenue_ws_caldate,
    "venuecode" => $urvenue_ws_venuecode,
    "ecozone" => $urvenue_ws_ecozoneid,
    "paytype" => $urvenue_ws_paytype,
    "qty" => "1",
    "guests" => $urvenue_ws_guests,
    "eventcode" => $urvenue_ws_eventcode,
    "name" => $urvenue_ws_itemname,
    "time" => $urvenue_ws_time,
    "duration" => $urvenue_ws_duration,
    "vendor" => $urvenue_ws_vendor,
    "sectionid" => $urvenue_ws_sectionid,
    "locationid" => $urvenue_ws_locationid
);

if($urvenue_ws_cartcode){
    $urvenue_ws_itemdata["cartcode"] = $urvenue_ws_cartcode;
    // $uvcartendurl = uws_get_apiwvarurl("cart-update", $urvenue_ws_vendata);
    // $uvcartendurl = urvenue_ws_get_apiwvarurl("cart-update", $urvenue_ws_vendata); // Axl UWS-7416
    $urvenue_ws_cartendurl = urvenue_ws_get_apiwvarurl("cart-update", $urvenue_ws_vendata); // Axl UWS-7634
}
else{
    // $uvcartendurl = uws_get_apiwvarurl("cart-create", $urvenue_ws_vendata);
    // $uvcartendurl = urvenue_ws_get_apiwvarurl("cart-create", $urvenue_ws_vendata); // Axl UWS-7416
    $urvenue_ws_cartendurl = urvenue_ws_get_apiwvarurl("cart-create", $urvenue_ws_vendata); // Axl UWS-7634
}

if($urvenue_ws_timecategory){//When is OT times detailed
    $urvenue_ws_timedets = array(
        "type" => $urvenue_ws_timetype,
        "timetype" => "times_detailed",
        "vendor" => "opentable",
        "category" => $urvenue_ws_timecategory,
    );
    $urvenue_ws_itemdata["ext_cartjson"] = $urvenue_ws_timedets;
}

$urvenue_ws_itemdata = http_build_query($urvenue_ws_itemdata);

// TESTING @Axl
// $uvch = curl_init();
// curl_setopt($uvch, CURLOPT_URL, $uvcartendurl);
// curl_setopt($uvch, CURLOPT_POST, true);
// curl_setopt($uvch, CURLOPT_CUSTOMREQUEST, "POST");
// curl_setopt($uvch, CURLOPT_RETURNTRANSFER, true); 
// curl_setopt($uvch, CURLOPT_POSTFIELDS, $urvenue_ws_itemdata);
// $urvenue_ws_resultraw = curl_exec($uvch);
// $urvenue_ws_response = wp_remote_post($uvcartendurl, array(
$urvenue_ws_response = wp_remote_post($urvenue_ws_cartendurl, array( // Axl UWS-7634
    'body' => $urvenue_ws_itemdata,
    'timeout' => 60,
));
$urvenue_ws_resultraw = wp_remote_retrieve_body($urvenue_ws_response);

$urvenue_ws_cartfeedresponse = json_decode($urvenue_ws_resultraw, true);

// TESTING @Axl
// curl_close($uvch);


//Build Response
$urvenue_ws_return = array();
$urvenue_ws_itemsbasecomponents = array();
$urvenue_ws_carttotals = "";

if(!$uvrecreate and is_array($urvenue_ws_cartfeedresponse) and $urvenue_ws_cartfeedresponse["uv"]["success"]["status"] == "success"){
    $urvenue_ws_cartitems = $urvenue_ws_cartfeedresponse["uv"]["data"]["cart"];
    $urvenue_ws_carttotals = $urvenue_ws_cartfeedresponse["uv"]["data"]["totals"];

    if(is_array($urvenue_ws_cartitems)){
        // foreach($urvenue_ws_cartitems as $uvitemcartcode => $uvcartitem){
        foreach($urvenue_ws_cartitems as $urvenue_ws_itemcartcode => $urvenue_ws_cartitem){ // Axl UWS-7634
            // if($uvcartitem["pricing"] and $uvcartitem["pricing"]["componentbreakdowns"]){
            if($urvenue_ws_cartitem["pricing"] and $urvenue_ws_cartitem["pricing"]["componentbreakdowns"]){ // Axl UWS-7634
                // $urvenue_ws_cartitemcomps = $uvcartitem["pricing"]["componentbreakdowns"];
                $urvenue_ws_cartitemcomps = $urvenue_ws_cartitem["pricing"]["componentbreakdowns"]; // Axl UWS-7634

                if(is_array($urvenue_ws_cartitemcomps) and $urvenue_ws_cartitemcomps[0]){
                    // $urvenue_ws_itemsbasecomponents[$uvitemcartcode] = array(
                    $urvenue_ws_itemsbasecomponents[$urvenue_ws_itemcartcode] = array( // Axl UWS-7634
                        "pricingdisplay" => $urvenue_ws_cartitemcomps[0]["pricingdisplay"],
                        "totalbase" => $urvenue_ws_cartitemcomps[0]["totalbase"],
                    );
                }
            }
        }
    }
}


$urvenue_ws_return["itemsbasecomponents"] = $urvenue_ws_itemsbasecomponents;
$urvenue_ws_return["totals"] = $urvenue_ws_carttotals;


// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_return);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416