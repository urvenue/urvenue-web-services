<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_proxies_lib;

// @egt [UWS-7297]
// uws_check_nonce("uwsinventory");
urvenue_ws_check_nonce("uwsinventory"); // Axl UWS-7416

// $uvmastercode = uws_cleanup_request("mastercode");
$uvmastercode = urvenue_ws_cleanup_request("mastercode"); // Axl UWS-7416
// $uvsectionid = uws_cleanup_request("sectionid");
$uvsectionid = urvenue_ws_cleanup_request("sectionid"); // Axl UWS-7416
// $uvlocationid = uws_cleanup_request("locationid");
$uvlocationid = urvenue_ws_cleanup_request("locationid"); // Axl UWS-7416
// $uvforcenew = uws_cleanup_request("forcenew");
$uvforcenew = urvenue_ws_cleanup_request("forcenew"); // Axl UWS-7416
// $uvpricingbreakdown = uws_cleanup_request("pricingbreakdown");
$uvpricingbreakdown = urvenue_ws_cleanup_request("pricingbreakdown"); // Axl UWS-7416
// $uvitem = uws_get_invitem($uvmastercode);
$uvitem = urvenue_ws_get_invitem($uvmastercode); // Axl UWS-7416
$uviteminfo = $uvitem["info"];
$uvitempop = "";
$uvpopitemmodule = "default";

if(is_array($uvitem)){
    $uvargs = "";
    if(isset($uvitem["info"]) and $uvitem["info"]["globaltype"] == "membership"){
        $uvpopitemmodule = "membership";
        $uvargs = array(
            "template" => "memberships/item-pop"
        );
    }

    if($uvpricingbreakdown)
        $uvargs = array(
            "template" => "inventory/inventory-itembreakdown-pop"
        );
        

    // $uvitempop = uws_get_itempop($uvitem, $uvargs);
    $uvitempop = urvenue_ws_get_itempop($uvitem, $uvargs); // Axl UWS-7416

    if($uvsectionid)
        $uvitem["selectedsectionid"] = $uvsectionid;
    if($uvlocationid)
        $uvitem["selectedlocationid"] = $uvlocationid;
    if($uvforcenew)
        $uvitem["selectedforcenew"] = $uvforcenew;
}

$uvreturn = array(
    "html" => $uvitempop,
    "popitem" => $uvitem,
    "popitem-module" => $uvpopitemmodule,
);

if($uvpopitemmodule == "membership"){
    $uvtempls = array(
        // "membership-primmembership-sel-item" => uws_get_template("memberships/primmembership-sel-item"),
        "membership-primmembership-sel-item" => urvenue_ws_get_template("memberships/primmembership-sel-item"), // Axl UWS-7416
    );

    $uvreturn["templates"] = $uvtempls;
}

// if($_REQUEST["returnprox"]) // Axl UWS-7418
if(isset($_REQUEST["returnprox"]) && sanitize_text_field( wp_unslash( $_REQUEST["returnprox"] ) )) // Axl UWS-7418
    // $uvreturn["proxies"] = uws_get_proxies("inventory");
    $uvreturn["proxies"] = urvenue_ws_get_proxies("inventory"); // Axl UWS-7416
    
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
// echo($uvreturnjson);
echo( $uvreturnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416