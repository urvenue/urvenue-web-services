<?php

global $uws_feeds_debug, $uws_actionlinks_lib, $uws_feeds_lib;

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
$uvsubtotalagree = (isset($_REQUEST["subtotalagree"])) ? uws_cleanup_var($_REQUEST["subtotalagree"]) : "";
$uvsubinfo = (isset($_REQUEST["subinfo"])) ? $_REQUEST["subinfo"] : "";
$uvapicartcode = $uvcartcount = "";
$uvreturn = array();

$uvshiftcode = ($uvtime) ? "SHT" . $uvtime : "SHT0";
$uvdurcode = ($uvduration) ? "DUR" . $uvduration : "DUR0";

$uvitemdata = array(
    "mastercode" => $uvmastercode,
    "shiftcode" => $uvshiftcode,
    "durcode" => $uvdurcode,
    "qty" => $uvguests,
    "paytype" => $uvpaytype,
    "subtotalagree" => $uvsubtotalagree,
);

if($uvmastercode){
    if($uvcartcode){
        $uvaddcartendpoint = $uws_feeds_lib["cartv2-add"]["url"];
        $uvitemdata["cartcode"] = $uvcartcode;
        $uvitemdatabuild = http_build_query($uvitemdata, 'flags_');

        $uvch = curl_init();
        curl_setopt($uvch, CURLOPT_URL, $uvaddcartendpoint);
        curl_setopt($uvch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($uvch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($uvch, CURLOPT_POSTFIELDS, $uvitemdatabuild);
        $uvresultraw = curl_exec($uvch);
        $uvresult = json_decode($uvresultraw, true);
        curl_close($uvch);

        if($uws_feeds_debug){
            print_r($uvitemdata);
            uws_feed_debug_msg("Adding item to cart: $uvcartcode -- endpoint: $uvaddcartendpoint");
            print_r($uvresult);
        }
    }
    else{
        $uvcreatecartendpoint = $uws_feeds_lib["cartv2-create"]["url"];
        $uvrequestinfo = uws_get_requestinfo();

        if($uvsubinfo)
            $uvitemdata["meta"]["subscriptions"] = $uvsubinfo;
        $uvitemdatabuild = http_build_query($uvitemdata, 'flags_');

        $uvch = curl_init();
        curl_setopt($uvch, CURLOPT_URL, $uvcreatecartendpoint);
        curl_setopt($uvch, CURLOPT_POST, true);
        curl_setopt($uvch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($uvch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($uvch, CURLOPT_POSTFIELDS, $uvitemdatabuild);
        $uvresultraw = curl_exec($uvch);
        $uvresult = json_decode($uvresultraw, true);
        curl_close($uvch);

        if($uws_feeds_debug){
            print_r($uvitemdata);
            uws_feed_debug_msg("Creating new cart, endpoint: $uvcreatecartendpoint");
            print_r($uvresult);
        }
    }

    if(is_array($uvresult) and $uvresult["success"]){
        $uvapicartcode = $uvresult["data"]["cartcode"];
        $uvcartitemcode = $uvresult["data"]["cartitemcode"];
        $uvcartcount = $uvresult["data"]["cartcount"];
    }
    else if(is_array($uvresult) and $uvresult["message"]){
        $uvapierrormsg = $uvresult["message"];

        if(strpos($uvapierrormsg, "Cart expired") !== false or strpos($uvapierrormsg, "Cart already redeemed") !== false or strpos($uvapierrormsg, "Cart not found") !== false){
            $uvreturn["recreate"] = 1;
        }
    }

    if($uvapicartcode){
        $uvsidebartarget = ($uvgotocheck) ? "checkout-checkurl" : "checkout-carturl";
        $uvcheckoutlinks = uws_get_bkgcheckout_links($uvapicartcode);
        $uvsidebarcheckurl = $uvcheckoutlinks[$uvsidebartarget];

        $uvcheckcarturl = $uvcheckoutlinks["checkout-carturl"];
        $uvcheckcheckurl = $uvcheckoutlinks["checkout-checkurl"];

        $uvreturn["cartcode"] = $uvapicartcode;
        $uvreturn["opencheck"] = $uvsidebarcheckurl;
        $uvcartreturn["cartcount"] = $uvcartcount;
        $uvcartreturn["checkout-carturl"] = $uvcheckcarturl;
        $uvcartreturn["checkout-checkurl"] = $uvcheckcheckurl;
        $uvreturn["cart"] = $uvcartreturn;
        $uvreturn["issidecheck"] = 1;
    }
    else{
        $uvpophtml = uws_get_template("inventory/inventory-cart-itemadded-error-pop");
        $uvpophtml = str_replace("{apierrormsg}", $uvapierrormsg, $uvpophtml);
        $uvreturn["html"] = $uvpophtml;
    }
}

//print_r($uvitemdata);

$uvreturnjson = json_encode($uvreturn);
header('Content-Type: application/json');
echo($uvreturnjson);