<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_feeds_debug, $urvenue_ws_actionlinks_lib, $urvenue_ws_feeds_lib;

// @egt [UWS-7297]
// uws_check_nonce("urvenue_ws_inventory");
urvenue_ws_check_nonce("urvenue_ws_inventory"); // Axl UWS-7416

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
// $urvenue_ws_subtotalagree = uws_cleanup_request("subtotalagree");
$urvenue_ws_subtotalagree = urvenue_ws_cleanup_request("subtotalagree"); // Axl UWS-7416
// $urvenue_ws_subinfo = (isset($_REQUEST["subinfo"])) ? $_REQUEST["subinfo"] : ""; // Axl UWS-7418
// $urvenue_ws_subinfo = (isset($_REQUEST["subinfo"])) ? sanitize_text_field( wp_unslash( $_REQUEST["subinfo"] ) ) : ""; // Axl UWS-7418
$urvenue_ws_subinfo = (isset($_REQUEST["subinfo"])) ? sanitize_text_field( wp_unslash( $_REQUEST["subinfo"] ) ) : ""; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verified via urvenue_ws_check_nonce("urvenue_ws_inventory") above // Axl UWS-7416
$urvenue_ws_apicartcode = $urvenue_ws_cartcount = "";
$urvenue_ws_return = array();

$urvenue_ws_shiftcode = ($urvenue_ws_time) ? "SHT" . $urvenue_ws_time : "SHT0";
$urvenue_ws_durcode = ($urvenue_ws_duration) ? "DUR" . $urvenue_ws_duration : "DUR0";

$urvenue_ws_itemdata = array(
    "mastercode" => $urvenue_ws_mastercode,
    "shiftcode" => $urvenue_ws_shiftcode,
    "durcode" => $urvenue_ws_durcode,
    "qty" => $urvenue_ws_guests,
    "paytype" => $urvenue_ws_paytype,
    "subtotalagree" => $urvenue_ws_subtotalagree,
);

if($urvenue_ws_mastercode){
    if($urvenue_ws_cartcode){
        $urvenue_ws_addcartendpoint = $urvenue_ws_feeds_lib["cartv2-add"]["url"];
        $urvenue_ws_itemdata["cartcode"] = $urvenue_ws_cartcode;
        $urvenue_ws_itemdatabuild = http_build_query($urvenue_ws_itemdata, 'flags_');

        // TESTING @Axl
        // $uvch = curl_init();
        // curl_setopt($uvch, CURLOPT_URL, $urvenue_ws_addcartendpoint);
        // curl_setopt($uvch, CURLOPT_CUSTOMREQUEST, "PUT");
        // curl_setopt($uvch, CURLOPT_RETURNTRANSFER, true); 
        // curl_setopt($uvch, CURLOPT_POSTFIELDS, $urvenue_ws_itemdatabuild);
        // $urvenue_ws_resultraw = curl_exec($uvch);
        $urvenue_ws_response = wp_remote_request($urvenue_ws_addcartendpoint, array(
            'method' => 'PUT',
            'body' => $urvenue_ws_itemdatabuild,
            'timeout' => 60,
        ));
        $urvenue_ws_resultraw = wp_remote_retrieve_body($urvenue_ws_response);

        $urvenue_ws_result = json_decode($urvenue_ws_resultraw, true);

        // TESTING @Axl
        // curl_close($uvch);

        if($urvenue_ws_feeds_debug){
            print_r($urvenue_ws_itemdata);
            // uws_feed_debug_msg("Adding item to cart: $urvenue_ws_cartcode -- endpoint: $urvenue_ws_addcartendpoint");
            urvenue_ws_feed_debug_msg("Adding item to cart: $urvenue_ws_cartcode -- endpoint: $urvenue_ws_addcartendpoint"); // Axl UWS-7416
            print_r($urvenue_ws_result);
        }
    }
    else{
        $urvenue_ws_createcartendpoint = $urvenue_ws_feeds_lib["cartv2-create"]["url"];
        // $urvenue_ws_requestinfo = uws_get_requestinfo();
        $urvenue_ws_requestinfo = urvenue_ws_get_requestinfo(); // Axl UWS-7416

        if($urvenue_ws_subinfo)
            $urvenue_ws_itemdata["meta"]["subscriptions"] = $urvenue_ws_subinfo;
        $urvenue_ws_itemdatabuild = http_build_query($urvenue_ws_itemdata, 'flags_');

        // TESTING @Axl
        // $uvch = curl_init();
        // curl_setopt($uvch, CURLOPT_URL, $urvenue_ws_createcartendpoint);
        // curl_setopt($uvch, CURLOPT_POST, true);
        // curl_setopt($uvch, CURLOPT_CUSTOMREQUEST, "POST");
        // curl_setopt($uvch, CURLOPT_RETURNTRANSFER, true); 
        // curl_setopt($uvch, CURLOPT_POSTFIELDS, $urvenue_ws_itemdatabuild);
        // $urvenue_ws_resultraw = curl_exec($uvch);
        $urvenue_ws_response = wp_remote_post($urvenue_ws_createcartendpoint, array(
            'body' => $urvenue_ws_itemdatabuild,
            'timeout' => 60,
        ));
        $urvenue_ws_resultraw = wp_remote_retrieve_body($urvenue_ws_response);

        $urvenue_ws_result = json_decode($urvenue_ws_resultraw, true);
        
        // TESTING @Axl
        // curl_close($uvch);

        if($urvenue_ws_feeds_debug){
            print_r($urvenue_ws_itemdata);
            // uws_feed_debug_msg("Creating new cart, endpoint: $urvenue_ws_createcartendpoint");
            urvenue_ws_feed_debug_msg("Creating new cart, endpoint: $urvenue_ws_createcartendpoint"); // Axl UWS-7416
            print_r($urvenue_ws_result);
        }
    }

    if(is_array($urvenue_ws_result) and $urvenue_ws_result["success"]){
        $urvenue_ws_apicartcode = $urvenue_ws_result["data"]["cartcode"];
        $urvenue_ws_cartitemcode = $urvenue_ws_result["data"]["cartitemcode"];
        $urvenue_ws_cartcount = $urvenue_ws_result["data"]["cartcount"];
    }
    else if(is_array($urvenue_ws_result) and $urvenue_ws_result["message"]){
        $urvenue_ws_apierrormsg = $urvenue_ws_result["message"];

        if(strpos($urvenue_ws_apierrormsg, "Cart expired") !== false or strpos($urvenue_ws_apierrormsg, "Cart already redeemed") !== false or strpos($urvenue_ws_apierrormsg, "Cart not found") !== false){
            $urvenue_ws_return["recreate"] = 1;
        }
    }

    if($urvenue_ws_apicartcode){
        $urvenue_ws_sidebartarget = ($urvenue_ws_gotocheck) ? "checkout-checkurl" : "checkout-carturl";
        // $urvenue_ws_checkoutlinks = uws_get_bkgcheckout_links($urvenue_ws_apicartcode);
        $urvenue_ws_checkoutlinks = urvenue_ws_get_bkgcheckout_links($urvenue_ws_apicartcode); // Axl UWS-7416
        $urvenue_ws_sidebarcheckurl = $urvenue_ws_checkoutlinks[$urvenue_ws_sidebartarget];

        $urvenue_ws_checkcarturl = $urvenue_ws_checkoutlinks["checkout-carturl"];
        $urvenue_ws_checkcheckurl = $urvenue_ws_checkoutlinks["checkout-checkurl"];

        $urvenue_ws_return["cartcode"] = $urvenue_ws_apicartcode;
        $urvenue_ws_return["opencheck"] = $urvenue_ws_sidebarcheckurl;
        $urvenue_ws_cartreturn["cartcount"] = $urvenue_ws_cartcount;
        $urvenue_ws_cartreturn["checkout-carturl"] = $urvenue_ws_checkcarturl;
        $urvenue_ws_cartreturn["checkout-checkurl"] = $urvenue_ws_checkcheckurl;
        $urvenue_ws_return["cart"] = $urvenue_ws_cartreturn;
        $urvenue_ws_return["issidecheck"] = 1;
    }
    else{
        // $urvenue_ws_pophtml = uws_get_template("inventory/inventory-cart-itemadded-error-pop");
        $urvenue_ws_pophtml = urvenue_ws_get_template("inventory/inventory-cart-itemadded-error-pop"); // Axl UWS-7416
        $urvenue_ws_pophtml = str_replace("{apierrormsg}", $urvenue_ws_apierrormsg, $urvenue_ws_pophtml);
        $urvenue_ws_return["html"] = $urvenue_ws_pophtml;
    }
}

//print_r($urvenue_ws_itemdata);

// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_return);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416