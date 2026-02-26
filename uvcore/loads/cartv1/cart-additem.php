<?php

global $uws_core_lib, $uws_feeds_debug;

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
$uvmasterbk4data = uws_cleanup_request("masterbk4data");
$uvbk4data = uws_cleanup_request("bk4data");
$uvforcenew = uws_cleanup_request("forcenew");
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


$uvreturnjson = json_encode($uvreturn);
header('Content-Type: application/json');
echo($uvreturnjson);