<?php

global $uws_core_lib;

// @egt [UWS-7297]
uws_check_nonce("uwsinventory");

$uvcartcode = (isset($_REQUEST["cartcode"])) ? uws_cleanup_var($_REQUEST["cartcode"]) : "";
$uvmastercode = (isset($_REQUEST["mastercode"])) ? uws_cleanup_var($_REQUEST["mastercode"]) : "";
$uvitemcode = (isset($_REQUEST["itemcode"])) ? uws_cleanup_var($_REQUEST["itemcode"]) : "";
$uvcaldate = (isset($_REQUEST["caldate"])) ? uws_cleanup_var($_REQUEST["caldate"]) : "";
$uvvenuecode = (isset($_REQUEST["venuecode"])) ? uws_cleanup_var($_REQUEST["venuecode"]) : "";
$uvecozone = (isset($_REQUEST["ecozone"])) ? uws_cleanup_var($_REQUEST["ecozone"]) : "";
$uvitemname = (isset($_REQUEST["itemname"])) ? uws_cleanup_var($_REQUEST["itemname"]) : "";
$uvpaytype = (isset($_REQUEST["paytype"])) ? uws_cleanup_var($_REQUEST["paytype"]) : "";
$uvguests = (isset($_REQUEST["guests"])) ? uws_cleanup_var($_REQUEST["guests"]) : "";
$uveventcode = (isset($_REQUEST["eventcode"])) ? uws_cleanup_var($_REQUEST["eventcode"]) : "";
$uvtime = (isset($_REQUEST["time"])) ? uws_cleanup_var($_REQUEST["time"]) : "";
$uvduration = (isset($_REQUEST["duration"])) ? uws_cleanup_var($_REQUEST["duration"]) : "";
$uvvendor = (isset($_REQUEST["vendor"])) ? uws_cleanup_var($_REQUEST["vendor"]) : "";
$uvgotocheck = (isset($_REQUEST["gotocheck"])) ? uws_cleanup_var($_REQUEST["gotocheck"]) : "";
$uvsectionid = (isset($_REQUEST["sectionid"])) ? uws_cleanup_var($_REQUEST["sectionid"]) : "";
$uvlocationid = (isset($_REQUEST["locationid"])) ? uws_cleanup_var($_REQUEST["locationid"]) : "";
$uvcartmanagementid = (isset($_REQUEST["cartmanagementid"])) ? uws_cleanup_var($_REQUEST["cartmanagementid"]) : "";
$uvmanageentid = (isset($_REQUEST["manageentid"])) ? uws_cleanup_var($_REQUEST["manageentid"]) : "";
$uvtimetype = (isset($_REQUEST["timetype"])) ? uws_cleanup_var($_REQUEST["timetype"]) : "";
$uvtimecategory = (isset($_REQUEST["timecategory"])) ? uws_cleanup_var($_REQUEST["timecategory"]) : "";
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


$uvreturnjson = json_encode($uvreturn);
header('Content-Type: application/json');
echo($uvreturnjson);