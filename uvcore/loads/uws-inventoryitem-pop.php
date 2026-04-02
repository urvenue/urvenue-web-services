<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_proxies_lib;

// @egt [UWS-7297]
// uws_check_nonce("uwsinventory");
urvenue_ws_check_nonce("uwsinventory"); // Axl UWS-7416

// $urvenue_ws_mastercode = uws_cleanup_request("mastercode");
$urvenue_ws_mastercode = urvenue_ws_cleanup_request("mastercode"); // Axl UWS-7416
// $urvenue_ws_sectionid = uws_cleanup_request("sectionid");
$urvenue_ws_sectionid = urvenue_ws_cleanup_request("sectionid"); // Axl UWS-7416
// $urvenue_ws_locationid = uws_cleanup_request("locationid");
$urvenue_ws_locationid = urvenue_ws_cleanup_request("locationid"); // Axl UWS-7416
// $urvenue_ws_forcenew = uws_cleanup_request("forcenew");
$urvenue_ws_forcenew = urvenue_ws_cleanup_request("forcenew"); // Axl UWS-7416
// $urvenue_ws_pricingbreakdown = uws_cleanup_request("pricingbreakdown");
$urvenue_ws_pricingbreakdown = urvenue_ws_cleanup_request("pricingbreakdown"); // Axl UWS-7416
// $urvenue_ws_item = uws_get_invitem($urvenue_ws_mastercode);
$urvenue_ws_item = urvenue_ws_get_invitem($urvenue_ws_mastercode); // Axl UWS-7416
$urvenue_ws_iteminfo = $urvenue_ws_item["info"];
$urvenue_ws_itempop = "";
$urvenue_ws_popitemmodule = "default";

if(is_array($urvenue_ws_item)){
    $urvenue_ws_args = "";
    if(isset($urvenue_ws_item["info"]) and $urvenue_ws_item["info"]["globaltype"] == "membership"){
        $urvenue_ws_popitemmodule = "membership";
        $urvenue_ws_args = array(
            "template" => "memberships/item-pop"
        );
    }

    if($urvenue_ws_pricingbreakdown)
        $urvenue_ws_args = array(
            "template" => "inventory/inventory-itembreakdown-pop"
        );
        

    // $urvenue_ws_itempop = uws_get_itempop($urvenue_ws_item, $urvenue_ws_args);
    $urvenue_ws_itempop = urvenue_ws_get_itempop($urvenue_ws_item, $urvenue_ws_args); // Axl UWS-7416

    if($urvenue_ws_sectionid)
        $urvenue_ws_item["selectedsectionid"] = $urvenue_ws_sectionid;
    if($urvenue_ws_locationid)
        $urvenue_ws_item["selectedlocationid"] = $urvenue_ws_locationid;
    if($urvenue_ws_forcenew)
        $urvenue_ws_item["selectedforcenew"] = $urvenue_ws_forcenew;
}

$urvenue_ws_return = array(
    "html" => $urvenue_ws_itempop,
    "popitem" => $urvenue_ws_item,
    "popitem-module" => $urvenue_ws_popitemmodule,
);

if($urvenue_ws_popitemmodule == "membership"){
    $urvenue_ws_templs = array(
        // "membership-primmembership-sel-item" => uws_get_template("memberships/primmembership-sel-item"),
        "membership-primmembership-sel-item" => urvenue_ws_get_template("memberships/primmembership-sel-item"), // Axl UWS-7416
    );

    $urvenue_ws_return["templates"] = $urvenue_ws_templs;
}

// if($_REQUEST["returnprox"]) // Axl UWS-7418
if(isset($_REQUEST["returnprox"]) && sanitize_text_field( wp_unslash( $_REQUEST["returnprox"] ) )) // Axl UWS-7418
    // $urvenue_ws_return["proxies"] = uws_get_proxies("inventory");
    $urvenue_ws_return["proxies"] = urvenue_ws_get_proxies("inventory"); // Axl UWS-7416
    
// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_return);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416