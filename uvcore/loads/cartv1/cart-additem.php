<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_core_lib, $uws_feeds_debug;

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
$uvmasterbk4data = (isset($_REQUEST["masterbk4data"])) ? uws_cleanup_var($_REQUEST["masterbk4data"]) : "";
$uvbk4data = (isset($_REQUEST["bk4data"])) ? uws_cleanup_var($_REQUEST["bk4data"]) : "";
$uvforcenew = (isset($_REQUEST["forcenew"])) ? uws_cleanup_var($_REQUEST["forcenew"]) : "";
$uvismixedmanagentid = 0;
$uvwasadded = 0;

if($uws_core_lib["inventory"]["manageentlock"] && $uvcartmanagementid){
    $uvitemthevenueinfo = uws_get_venuelibinfo_byvenuecode($uvvenuecode);

    if($uvitemthevenueinfo["manageentid"] != $uvcartmanagementid)
        $uvismixedmanagentid = 1;
}

if($uvismixedmanagentid){//When new item is not the same managentid of the current cart items, multiple managent ids not supported yet
    $uvreturn = array();
    $uvpopcontent = uws_get_template("cart/cart-mixedmanagent-pop");
    $uvreturn["html"] = $uvpopcontent;
}
else{
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
        "locationid" => $uvlocationid,
        "forcenew" => $uvforcenew,
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
    
    if($uvmasterbk4data and $uvbk4data){//When is Book4time
        $uvmasterbk4data = stripslashes(html_entity_decode($uvmasterbk4data));
        $uvbkdata1 = json_decode($uvmasterbk4data, true);

        $uvbk4data = stripslashes(html_entity_decode($uvbk4data));
        $uvbkdata2 = json_decode($uvbk4data, true);

        if(is_array($uvbkdata1) and is_array($uvbkdata2)){
            $uvcartjsondata = array_merge($uvbkdata1, $uvbkdata2);
            $uvitemdata["ext_cartjson"] = $uvcartjsondata;
        }
    }

    $uvitemdatabuild = http_build_query($uvitemdata);

    // TESTING @Axl
    // $uvch = curl_init();
    // curl_setopt($uvch, CURLOPT_URL, $uvcartendurl);
    // curl_setopt($uvch, CURLOPT_POST, true);
    // curl_setopt($uvch, CURLOPT_CUSTOMREQUEST, "POST");
    // curl_setopt($uvch, CURLOPT_RETURNTRANSFER, true); 
    // curl_setopt($uvch, CURLOPT_POSTFIELDS, $uvitemdatabuild);
    // $uvresultraw = curl_exec($uvch);

    $uvresponse = wp_remote_post($uvcartendurl, array(
        'body' => $uvitemdatabuild,
        'timeout' => 60,
    ));
    $uvresultraw = wp_remote_retrieve_body($uvresponse);
    
    $uvcartfeedresponse = json_decode($uvresultraw, true);
    
    // TESTING @Axl
    // curl_close($uvch);

    if($uws_feeds_debug){
        print_r($uvitemdata);
        uws_feed_debug_msg("Adding item to cart: $uvcartcode -- endpoint: $uvcartendurl");
        print_r($uvcartfeedresponse);
    }

    /*$uvcartparams = "mastercode=$uvmastercode&itemcode=$uvitemcode&caldate=$uvcaldate&venuecode=$uvvenuecode&ecozone=$uvecozoneid&paytype=$uvpaytype&qty=1&guests=$uvguests&eventcode=$uveventcode&name=$uvitemname&time=$uvtime&duration=$uvduration&vendor=$uvvendor&sectionid=$uvsectionid&locationid=$uvlocationid";*/
    
    /*if($uvcartcode){
        $uvcartparams = "cartcode=" . $uvcartcode . "&" . $uvcartparams;
        $uvcartfeedresponse = uws_get_apiwvar("cart-update", $uvcartparams, $uvvendata);
    }
    else{
        $uvcartfeedresponse = uws_get_apiwvar("cart-create", $uvcartparams, $uvvendata);
    }*/

    //Build Response
    $uvreturn = array();
    $uvrecreate = 0;

    if(is_array($uvcartfeedresponse) and isset($uvcartfeedresponse["uv"]["data"]["recreate"]) and $uvcartfeedresponse["uv"]["data"]["recreate"])
        $uvrecreate = 1;
    
    $uvcart = $uvapierrormsg = "";
    if(!$uvrecreate and is_array($uvcartfeedresponse) and $uvcartfeedresponse["uv"]["success"]["status"] == "success"){
        $uvcartcode = $uvcartfeedresponse["uv"]["data"]["cartcode"];
        $uvcart = uws_get_cart($uvcartcode, $uvvendata, $uvcartfeedresponse);
    }
    else if(is_array($uvcartfeedresponse) and $uvcartfeedresponse["uv"]["success"]["status"] == "error"){
        $uvapierrormsg = $uvcartfeedresponse["uv"]["success"]["message"];
    
        if($uvapierrormsg == "Invalid Cart" or $uvapierrormsg == "Cart not found"){
            //$uvcartcode = "";
            $uvreturn["recreate"] = 1;
        }
    }
    else if($uvrecreate)
        $uvreturn["recreate"] = 1;
    
    if(!$uvcart){
        $uvcartadded = uws_get_template("inventory/inventory-cart-itemadded-error-pop");
        $uvcartadded = str_replace("{apierrormsg}", $uvapierrormsg, $uvcartadded);
    }
    else{

        $uvaccountvars = uws_get_account_vars($uvvendata);
        $uvcartadded = uws_get_template("inventory/inventory-cart-itemadded-pop");
        $uvbkgcheckoutlinks = uws_get_bkgcheckout_links($uvcartcode, $uvaccountvars);
        
        $uvcartadded = str_replace(
            array(
                "{carturl}",
                "{checkouturl}",
            ),
            array(
                $uvbkgcheckoutlinks["checkout-carturl"],
                $uvbkgcheckoutlinks["checkout-checkurl"],
            ),
            $uvcartadded
        );
    
        $uvreturn["carturl"] = $uvbkgcheckoutlinks["checkout-carturl"];
        $uvreturn["checkurl"] = $uvbkgcheckoutlinks["checkout-checkurl"];

        $uvwasadded = 1;
    }
    
    $uvreturn["html"] = $uvcartadded;
    $uvreturn["cartcode"] = $uvcartcode;
    $uvreturn["closepopup"] = 0;
    
    if($uvcart)
        $uvreturn["cart"] = $uvcart;
    
    if($uvgotocheck and $uvcartcode and is_array($uvbkgcheckoutlinks) and $uvbkgcheckoutlinks["checkout-checkurl"])
        $uvreturn["redirect"] = $uvbkgcheckoutlinks["checkout-checkurl"];
    else if($uws_core_lib["inventory"]["closepopupafteraddtocart"] and $uvwasadded)
        $uvreturn["closepopup"] = 1;
}


// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);