<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_core_lib, $urvenue_ws_feeds_debug;

urvenue_ws_check_nonce("urvenue_ws_inventory");

$urvenue_ws_cartcode = urvenue_ws_cleanup_request("cartcode");
$urvenue_ws_mastercode = urvenue_ws_cleanup_request("mastercode");
$urvenue_ws_itemcode = urvenue_ws_cleanup_request("itemcode");
$urvenue_ws_caldate = urvenue_ws_cleanup_request("caldate");
$urvenue_ws_venuecode = urvenue_ws_cleanup_request("venuecode");
$urvenue_ws_ecozone = urvenue_ws_cleanup_request("ecozone");
$urvenue_ws_itemname = urvenue_ws_cleanup_request("itemname");
$urvenue_ws_paytype = urvenue_ws_cleanup_request("paytype");
$urvenue_ws_guests = urvenue_ws_cleanup_request("guests");
$urvenue_ws_eventcode = urvenue_ws_cleanup_request("eventcode");
$urvenue_ws_time = urvenue_ws_cleanup_request("time");
$urvenue_ws_duration = urvenue_ws_cleanup_request("duration");
$urvenue_ws_vendor = urvenue_ws_cleanup_request("vendor");
$urvenue_ws_gotocheck = urvenue_ws_cleanup_request("gotocheck");
$urvenue_ws_sectionid = urvenue_ws_cleanup_request("sectionid");
$urvenue_ws_locationid = urvenue_ws_cleanup_request("locationid");
$urvenue_ws_cartmanagementid = urvenue_ws_cleanup_request("cartmanagementid");
$urvenue_ws_manageentid = urvenue_ws_cleanup_request("manageentid");
$urvenue_ws_timetype = urvenue_ws_cleanup_request("timetype");
$urvenue_ws_timecategory = urvenue_ws_cleanup_request("timecategory");
$urvenue_ws_masterbk4data = urvenue_ws_cleanup_request("masterbk4data");
$urvenue_ws_bk4data = urvenue_ws_cleanup_request("bk4data");
$urvenue_ws_forcenew = urvenue_ws_cleanup_request("forcenew");
$urvenue_ws_ismixedmanagentid = 0;
$urvenue_ws_wasadded = 0;

if($urvenue_ws_core_lib["inventory"]["manageentlock"] && $urvenue_ws_cartmanagementid){
    $urvenue_ws_itemthevenueinfo = urvenue_ws_get_venuelibinfo_byvenuecode($urvenue_ws_venuecode);

    if($urvenue_ws_itemthevenueinfo["manageentid"] != $urvenue_ws_cartmanagementid)
        $urvenue_ws_ismixedmanagentid = 1;
}

if($urvenue_ws_ismixedmanagentid){//When new item is not the same managentid of the current cart items, multiple managent ids not supported yet
    $urvenue_ws_return = array();
    $urvenue_ws_popcontent = urvenue_ws_get_template("cart/cart-mixedmanagent-pop");
    $urvenue_ws_return["html"] = $urvenue_ws_popcontent;
}
else{
    $urvenue_ws_itemname = urlencode($urvenue_ws_itemname);
    $urvenue_ws_ecozone = urvenue_ws_standardize_ecozone($urvenue_ws_ecozone);
    $urvenue_ws_ecozoneid = urvenue_ws_ecozone_to_ecoid($urvenue_ws_ecozone);
    if(!$urvenue_ws_eventcode)
        $urvenue_ws_eventcode = "EVE" . str_replace("VEN", "", $urvenue_ws_venuecode) . str_replace("ECZ", "", $urvenue_ws_ecozone) . gmdate("Ymd", strtotime($urvenue_ws_caldate));

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
        "locationid" => $urvenue_ws_locationid,
        "forcenew" => $urvenue_ws_forcenew,
    );

    if($urvenue_ws_cartcode){
        $urvenue_ws_itemdata["cartcode"] = $urvenue_ws_cartcode;
        $urvenue_ws_cartendurl = urvenue_ws_get_apiwvarurl("cart-update", $urvenue_ws_vendata);
    }
    else{
        $urvenue_ws_cartendurl = urvenue_ws_get_apiwvarurl("cart-create", $urvenue_ws_vendata);
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

    if($urvenue_ws_masterbk4data and $urvenue_ws_bk4data){//When is Book4time
        $urvenue_ws_masterbk4data = stripslashes(html_entity_decode($urvenue_ws_masterbk4data));
        $urvenue_ws_bkdata1 = json_decode($urvenue_ws_masterbk4data, true);

        $urvenue_ws_bk4data = stripslashes(html_entity_decode($urvenue_ws_bk4data));
        $urvenue_ws_bkdata2 = json_decode($urvenue_ws_bk4data, true);

        if(is_array($urvenue_ws_bkdata1) and is_array($urvenue_ws_bkdata2)){
            $urvenue_ws_cartjsondata = array_merge($urvenue_ws_bkdata1, $urvenue_ws_bkdata2);
            $urvenue_ws_itemdata["ext_cartjson"] = $urvenue_ws_cartjsondata;
        }
    }

    $urvenue_ws_itemdatabuild = http_build_query($urvenue_ws_itemdata);

    $urvenue_ws_response = wp_remote_post($urvenue_ws_cartendurl, array(
        'body' => $urvenue_ws_itemdatabuild,
        'timeout' => 60,
    ));
    $urvenue_ws_resultraw = wp_remote_retrieve_body($urvenue_ws_response);

    $urvenue_ws_cartfeedresponse = json_decode($urvenue_ws_resultraw, true);

    if($urvenue_ws_feeds_debug){
        print_r($urvenue_ws_itemdata);
        urvenue_ws_feed_debug_msg("Adding item to cart: $urvenue_ws_cartcode -- endpoint: $urvenue_ws_cartendurl");
        print_r($urvenue_ws_cartfeedresponse);
    }

    /*$urvenue_ws_cartparams = "mastercode=$urvenue_ws_mastercode&itemcode=$urvenue_ws_itemcode&caldate=$urvenue_ws_caldate&venuecode=$urvenue_ws_venuecode&ecozone=$urvenue_ws_ecozoneid&paytype=$urvenue_ws_paytype&qty=1&guests=$urvenue_ws_guests&eventcode=$urvenue_ws_eventcode&name=$urvenue_ws_itemname&time=$urvenue_ws_time&duration=$urvenue_ws_duration&vendor=$urvenue_ws_vendor&sectionid=$urvenue_ws_sectionid&locationid=$urvenue_ws_locationid";*/

    /*if($urvenue_ws_cartcode){
        $urvenue_ws_cartparams = "cartcode=" . $urvenue_ws_cartcode . "&" . $urvenue_ws_cartparams;
        // $urvenue_ws_cartfeedresponse = uws_get_apiwvar("cart-update", $urvenue_ws_cartparams, $urvenue_ws_vendata);
        $urvenue_ws_cartfeedresponse = urvenue_ws_get_apiwvar("cart-update", $urvenue_ws_cartparams, $urvenue_ws_vendata); // Axl UWS-7416
    }
    else{
        // $urvenue_ws_cartfeedresponse = uws_get_apiwvar("cart-create", $urvenue_ws_cartparams, $urvenue_ws_vendata);
        $urvenue_ws_cartfeedresponse = urvenue_ws_get_apiwvar("cart-create", $urvenue_ws_cartparams, $urvenue_ws_vendata); // Axl UWS-7416
    }*/

    //Build Response
    $urvenue_ws_return = array();
    $urvenue_ws_recreate = 0;

    if(is_array($urvenue_ws_cartfeedresponse) and isset($urvenue_ws_cartfeedresponse["uv"]["data"]["recreate"]) and $urvenue_ws_cartfeedresponse["uv"]["data"]["recreate"])
        $urvenue_ws_recreate = 1;

    $urvenue_ws_cart = $urvenue_ws_apierrormsg = "";
    if(!$urvenue_ws_recreate and is_array($urvenue_ws_cartfeedresponse) and $urvenue_ws_cartfeedresponse["uv"]["success"]["status"] == "success"){
        $urvenue_ws_cartcode = $urvenue_ws_cartfeedresponse["uv"]["data"]["cartcode"];
        $urvenue_ws_cart = urvenue_ws_get_cart($urvenue_ws_cartcode, $urvenue_ws_vendata, $urvenue_ws_cartfeedresponse);
    }
    else if(is_array($urvenue_ws_cartfeedresponse) and $urvenue_ws_cartfeedresponse["uv"]["success"]["status"] == "error"){
        $urvenue_ws_apierrormsg = $urvenue_ws_cartfeedresponse["uv"]["success"]["message"];

        if($urvenue_ws_apierrormsg == "Invalid Cart" or $urvenue_ws_apierrormsg == "Cart not found"){
            //$urvenue_ws_cartcode = "";
            $urvenue_ws_return["recreate"] = 1;
        }
    }
    else if($urvenue_ws_recreate)
        $urvenue_ws_return["recreate"] = 1;

    if(!$urvenue_ws_cart){
        $urvenue_ws_cartadded = urvenue_ws_get_template("inventory/inventory-cart-itemadded-error-pop");
        $urvenue_ws_cartadded = str_replace("{apierrormsg}", $urvenue_ws_apierrormsg, $urvenue_ws_cartadded);
    }
    else{

        $urvenue_ws_accountvars = urvenue_ws_get_account_vars($urvenue_ws_vendata);
        $urvenue_ws_cartadded = urvenue_ws_get_template("inventory/inventory-cart-itemadded-pop");
        $urvenue_ws_bkgcheckoutlinks = urvenue_ws_get_bkgcheckout_links($urvenue_ws_cartcode, $urvenue_ws_accountvars);

        $urvenue_ws_cartadded = str_replace(
            array(
                "{carturl}",
                "{checkouturl}",
            ),
            array(
                $urvenue_ws_bkgcheckoutlinks["checkout-carturl"],
                $urvenue_ws_bkgcheckoutlinks["checkout-checkurl"],
            ),
            $urvenue_ws_cartadded
        );

        $urvenue_ws_return["carturl"] = $urvenue_ws_bkgcheckoutlinks["checkout-carturl"];
        $urvenue_ws_return["checkurl"] = $urvenue_ws_bkgcheckoutlinks["checkout-checkurl"];

        $urvenue_ws_wasadded = 1;
    }

    $urvenue_ws_return["html"] = $urvenue_ws_cartadded;
    $urvenue_ws_return["cartcode"] = $urvenue_ws_cartcode;
    $urvenue_ws_return["closepopup"] = 0;

    if($urvenue_ws_cart)
        $urvenue_ws_return["cart"] = $urvenue_ws_cart;

    if($urvenue_ws_gotocheck and $urvenue_ws_cartcode and is_array($urvenue_ws_bkgcheckoutlinks) and $urvenue_ws_bkgcheckoutlinks["checkout-checkurl"])
        $urvenue_ws_return["redirect"] = $urvenue_ws_bkgcheckoutlinks["checkout-checkurl"];
    else if($urvenue_ws_core_lib["inventory"]["closepopupafteraddtocart"] and $urvenue_ws_wasadded)
        $urvenue_ws_return["closepopup"] = 1;
}


$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
header('Content-Type: application/json');
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode()
