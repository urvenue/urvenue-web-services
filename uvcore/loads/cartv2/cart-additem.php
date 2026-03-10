<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_feeds_debug, $uws_actionlinks_lib, $uws_feeds_lib;

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
// $uvsubtotalagree = uws_cleanup_request("subtotalagree");
$uvsubtotalagree = urvenue_ws_cleanup_request("subtotalagree"); // Axl UWS-7416
// $uvsubinfo = (isset($_REQUEST["subinfo"])) ? $_REQUEST["subinfo"] : ""; // Axl UWS-7418
$uvsubinfo = (isset($_REQUEST["subinfo"])) ? sanitize_text_field( wp_unslash( $_REQUEST["subinfo"] ) ) : ""; // Axl UWS-7418
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
            // uws_feed_debug_msg("Adding item to cart: $uvcartcode -- endpoint: $uvaddcartendpoint");
            urvenue_ws_feed_debug_msg("Adding item to cart: $uvcartcode -- endpoint: $uvaddcartendpoint"); // Axl UWS-7416
            print_r($uvresult);
        }
    }
    else{
        $uvcreatecartendpoint = $uws_feeds_lib["cartv2-create"]["url"];
        // $uvrequestinfo = uws_get_requestinfo();
        $uvrequestinfo = urvenue_ws_get_requestinfo(); // Axl UWS-7416

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
            // uws_feed_debug_msg("Creating new cart, endpoint: $uvcreatecartendpoint");
            urvenue_ws_feed_debug_msg("Creating new cart, endpoint: $uvcreatecartendpoint"); // Axl UWS-7416
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
        // $uvcheckoutlinks = uws_get_bkgcheckout_links($uvapicartcode);
        $uvcheckoutlinks = urvenue_ws_get_bkgcheckout_links($uvapicartcode); // Axl UWS-7416
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
        // $uvpophtml = uws_get_template("inventory/inventory-cart-itemadded-error-pop");
        $uvpophtml = urvenue_ws_get_template("inventory/inventory-cart-itemadded-error-pop"); // Axl UWS-7416
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