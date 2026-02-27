<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_core_lib;

// @egt [UWS-7297]
uws_check_nonce("uwsinventory");

$uvcartcode = uws_cleanup_request("cartcode");
$uvmastercode = uws_cleanup_request("mastercode");
$uvitemcode = uws_cleanup_request("itemcode");
$uvcaldate = uws_cleanup_request("caldate");
$uvvenuecode = uws_cleanup_request("venuecode");
$uvecozone = uws_cleanup_request("ecozone");
$uvitemname = uws_cleanup_request("itemname");
$uvpaytype = uws_cleanup_request("paytype");
$uvguests = uws_cleanup_request("guests");
$uveventcode = uws_cleanup_request("eventcode");
$uvtime = uws_cleanup_request("time");
$uvduration = uws_cleanup_request("duration");
$uvvendor = uws_cleanup_request("vendor");
$uvgotocheck = uws_cleanup_request("gotocheck");
$uvsectionid = uws_cleanup_request("sectionid");
$uvlocationid = uws_cleanup_request("locationid");
$uvcartmanagementid = uws_cleanup_request("cartmanagementid");
$uvmanageentid = uws_cleanup_request("manageentid");
$uvtimetype = uws_cleanup_request("timetype");
$uvtimecategory = uws_cleanup_request("timecategory");
$uvismixedmanagentid = 0;


$uvitemname = urlencode($uvitemname);
$uvecozone = uws_standardize_ecozone($uvecozone);
$uvecozoneid = uws_ecozone_to_ecoid($uvecozone);
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
    $uvcartendurl = uws_get_apiwvarurl("cart-update", $uvvendata);
}
else{
    $uvcartendurl = uws_get_apiwvarurl("cart-create", $uvvendata);
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
echo($uvreturnjson);