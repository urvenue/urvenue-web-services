<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_core_lib, $urvenue_ws_feeds_debug;

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
// $uvmasterbk4data = uws_cleanup_request("masterbk4data");
$uvmasterbk4data = urvenue_ws_cleanup_request("masterbk4data"); // Axl UWS-7416
// $uvbk4data = uws_cleanup_request("bk4data");
$uvbk4data = urvenue_ws_cleanup_request("bk4data"); // Axl UWS-7416
// $uvforcenew = uws_cleanup_request("forcenew");
$uvforcenew = urvenue_ws_cleanup_request("forcenew"); // Axl UWS-7416
$uvismixedmanagentid = 0;
$uvwasadded = 0;

if($urvenue_ws_core_lib["inventory"]["manageentlock"] && $uvcartmanagementid){
    // $uvitemthevenueinfo = uws_get_venuelibinfo_byvenuecode($uvvenuecode);
    $uvitemthevenueinfo = urvenue_ws_get_venuelibinfo_byvenuecode($uvvenuecode); // Axl UWS-7416

    if($uvitemthevenueinfo["manageentid"] != $uvcartmanagementid)
        $uvismixedmanagentid = 1;
}

if($uvismixedmanagentid){//When new item is not the same managentid of the current cart items, multiple managent ids not supported yet
    $uvreturn = array();
    // $uvpopcontent = uws_get_template("cart/cart-mixedmanagent-pop");
    $uvpopcontent = urvenue_ws_get_template("cart/cart-mixedmanagent-pop"); // Axl UWS-7416
    $uvreturn["html"] = $uvpopcontent;
}
else{
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
        "locationid" => $uvlocationid,
        "forcenew" => $uvforcenew,
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

    if($urvenue_ws_feeds_debug){
        print_r($uvitemdata);
        // uws_feed_debug_msg("Adding item to cart: $uvcartcode -- endpoint: $uvcartendurl");
        urvenue_ws_feed_debug_msg("Adding item to cart: $uvcartcode -- endpoint: $uvcartendurl"); // Axl UWS-7416
        print_r($uvcartfeedresponse);
    }

    /*$uvcartparams = "mastercode=$uvmastercode&itemcode=$uvitemcode&caldate=$uvcaldate&venuecode=$uvvenuecode&ecozone=$uvecozoneid&paytype=$uvpaytype&qty=1&guests=$uvguests&eventcode=$uveventcode&name=$uvitemname&time=$uvtime&duration=$uvduration&vendor=$uvvendor&sectionid=$uvsectionid&locationid=$uvlocationid";*/
    
    /*if($uvcartcode){
        $uvcartparams = "cartcode=" . $uvcartcode . "&" . $uvcartparams;
        // $uvcartfeedresponse = uws_get_apiwvar("cart-update", $uvcartparams, $uvvendata);
        $uvcartfeedresponse = urvenue_ws_get_apiwvar("cart-update", $uvcartparams, $uvvendata); // Axl UWS-7416
    }
    else{
        // $uvcartfeedresponse = uws_get_apiwvar("cart-create", $uvcartparams, $uvvendata);
        $uvcartfeedresponse = urvenue_ws_get_apiwvar("cart-create", $uvcartparams, $uvvendata); // Axl UWS-7416
    }*/

    //Build Response
    $uvreturn = array();
    $uvrecreate = 0;

    if(is_array($uvcartfeedresponse) and isset($uvcartfeedresponse["uv"]["data"]["recreate"]) and $uvcartfeedresponse["uv"]["data"]["recreate"])
        $uvrecreate = 1;
    
    $uvcart = $uvapierrormsg = "";
    if(!$uvrecreate and is_array($uvcartfeedresponse) and $uvcartfeedresponse["uv"]["success"]["status"] == "success"){
        $uvcartcode = $uvcartfeedresponse["uv"]["data"]["cartcode"];
        // $uvcart = uws_get_cart($uvcartcode, $uvvendata, $uvcartfeedresponse);
        $uvcart = urvenue_ws_get_cart($uvcartcode, $uvvendata, $uvcartfeedresponse); // Axl UWS-7416
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
        // $uvcartadded = uws_get_template("inventory/inventory-cart-itemadded-error-pop");
        $uvcartadded = urvenue_ws_get_template("inventory/inventory-cart-itemadded-error-pop"); // Axl UWS-7416
        $uvcartadded = str_replace("{apierrormsg}", $uvapierrormsg, $uvcartadded);
    }
    else{

        // $uvaccountvars = uws_get_account_vars($uvvendata);
        $uvaccountvars = urvenue_ws_get_account_vars($uvvendata); // Axl UWS-7416
        // $uvcartadded = uws_get_template("inventory/inventory-cart-itemadded-pop");
        $uvcartadded = urvenue_ws_get_template("inventory/inventory-cart-itemadded-pop"); // Axl UWS-7416
        // $uvbkgcheckoutlinks = uws_get_bkgcheckout_links($uvcartcode, $uvaccountvars);
        $uvbkgcheckoutlinks = urvenue_ws_get_bkgcheckout_links($uvcartcode, $uvaccountvars); // Axl UWS-7416
        
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
    else if($urvenue_ws_core_lib["inventory"]["closepopupafteraddtocart"] and $uvwasadded)
        $uvreturn["closepopup"] = 1;
}


// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
// echo($uvreturnjson);
echo( $uvreturnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416