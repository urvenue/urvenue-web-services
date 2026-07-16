<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_feeds_debug, $urvenue_ws_actionlinks_lib, $urvenue_ws_feeds_lib;

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
$urvenue_ws_subtotalagree = urvenue_ws_cleanup_request("subtotalagree");
$urvenue_ws_subinfo = (isset($_REQUEST["subinfo"])) ? sanitize_text_field( wp_unslash( $_REQUEST["subinfo"] ) ) : ""; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verified via urvenue_ws_check_nonce("urvenue_ws_inventory") above
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

        $urvenue_ws_response = wp_remote_request($urvenue_ws_addcartendpoint, array(
            'method' => 'PUT',
            'body' => $urvenue_ws_itemdatabuild,
            'timeout' => 60,
        ));
        $urvenue_ws_resultraw = wp_remote_retrieve_body($urvenue_ws_response);

        $urvenue_ws_result = json_decode($urvenue_ws_resultraw, true);

        if($urvenue_ws_feeds_debug){
            print_r($urvenue_ws_itemdata);
            urvenue_ws_feed_debug_msg("Adding item to cart: $urvenue_ws_cartcode -- endpoint: $urvenue_ws_addcartendpoint");
            print_r($urvenue_ws_result);
        }
    }
    else{
        $urvenue_ws_createcartendpoint = $urvenue_ws_feeds_lib["cartv2-create"]["url"];
        $urvenue_ws_requestinfo = urvenue_ws_get_requestinfo();

        if($urvenue_ws_subinfo)
            $urvenue_ws_itemdata["meta"]["subscriptions"] = $urvenue_ws_subinfo;
        $urvenue_ws_itemdatabuild = http_build_query($urvenue_ws_itemdata, 'flags_');

        $urvenue_ws_response = wp_remote_post($urvenue_ws_createcartendpoint, array(
            'body' => $urvenue_ws_itemdatabuild,
            'timeout' => 60,
        ));
        $urvenue_ws_resultraw = wp_remote_retrieve_body($urvenue_ws_response);

        $urvenue_ws_result = json_decode($urvenue_ws_resultraw, true);

        if($urvenue_ws_feeds_debug){
            print_r($urvenue_ws_itemdata);
            urvenue_ws_feed_debug_msg("Creating new cart, endpoint: $urvenue_ws_createcartendpoint");
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
        $urvenue_ws_checkoutlinks = urvenue_ws_get_bkgcheckout_links($urvenue_ws_apicartcode);
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
        $urvenue_ws_pophtml = urvenue_ws_get_template("inventory/inventory-cart-itemadded-error-pop");
        $urvenue_ws_pophtml = str_replace("{apierrormsg}", $urvenue_ws_apierrormsg, $urvenue_ws_pophtml);
        $urvenue_ws_return["html"] = $urvenue_ws_pophtml;
    }
}

//print_r($urvenue_ws_itemdata);

$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
header('Content-Type: application/json');
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode()
