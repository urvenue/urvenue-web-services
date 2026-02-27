<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_feeds_debug, $uws_actionlinks_lib, $uws_feeds_lib;

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
$uvsubtotalagree = uws_cleanup_request("subtotalagree");
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

        // TESTING @Axl
        // $uvch = curl_init();
        // curl_setopt($uvch, CURLOPT_URL, $uvaddcartendpoint);
        // curl_setopt($uvch, CURLOPT_CUSTOMREQUEST, "PUT");
        // curl_setopt($uvch, CURLOPT_RETURNTRANSFER, true); 
        // curl_setopt($uvch, CURLOPT_POSTFIELDS, $uvitemdatabuild);
        // $uvresultraw = curl_exec($uvch);
        $uvresponse = wp_remote_request($uvaddcartendpoint, array(
            'method' => 'PUT',
            'body' => $uvitemdatabuild,
            'timeout' => 60,
        ));
        $uvresultraw = wp_remote_retrieve_body($uvresponse);

        $uvresult = json_decode($uvresultraw, true);

        // TESTING @Axl
        // curl_close($uvch);

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

        // TESTING @Axl
        // $uvch = curl_init();
        // curl_setopt($uvch, CURLOPT_URL, $uvcreatecartendpoint);
        // curl_setopt($uvch, CURLOPT_POST, true);
        // curl_setopt($uvch, CURLOPT_CUSTOMREQUEST, "POST");
        // curl_setopt($uvch, CURLOPT_RETURNTRANSFER, true); 
        // curl_setopt($uvch, CURLOPT_POSTFIELDS, $uvitemdatabuild);
        // $uvresultraw = curl_exec($uvch);
        $uvresponse = wp_remote_post($uvcreatecartendpoint, array(
            'body' => $uvitemdatabuild,
            'timeout' => 60,
        ));
        $uvresultraw = wp_remote_retrieve_body($uvresponse);

        $uvresult = json_decode($uvresultraw, true);
        
        // TESTING @Axl
        // curl_close($uvch);

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

// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);