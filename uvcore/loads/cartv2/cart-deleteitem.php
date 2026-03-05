<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_feeds_lib;

// $uvcartcode = uws_cleanup_request("cartcode");
$uvcartcode = urvenue_ws_cleanup_request("cartcode"); // Axl UWS-7416
// $uvitemcartcode = uws_cleanup_request("itemcartcode");
$uvitemcartcode = urvenue_ws_cleanup_request("itemcartcode"); // Axl UWS-7416
// $uvmanagementid = uws_cleanup_request("managementid");
$uvmanagementid = urvenue_ws_cleanup_request("managementid"); // Axl UWS-7416
$uvmanagementid = (!$uvmanagementid and isset($uws_defaultmanageentid)) ? $uws_defaultmanageentid : $uvmanagementid;
$uvmanagementid = (!$uvmanagementid and isset($uws_config_manageentid)) ? $uws_config_manageentid : $uvmanagementid;
// $uvmastercode = uws_cleanup_request("mastercode");
$uvmastercode = urvenue_ws_cleanup_request("mastercode"); // Axl UWS-7416
// $uvitem = ($uvmastercode) ? uws_get_invitem($uvmastercode) : "";
$uvitem = ($uvmastercode) ? urvenue_ws_get_invitem($uvmastercode) : ""; // Axl UWS-7416
$uvcartcount = 1;

if($uvcartcode and $uvitemcartcode){

    $uvdeletecartendpoint = $uws_feeds_lib["cartv2-delete"]["url"];
    $uvitemdata = array(
        "cartcode" => $uvcartcode,
        "itemcartcode" => $uvitemcartcode
    );

    $uvdeletecartendpoint = $uvdeletecartendpoint . "&" . http_build_query($uvitemdata);

    // TESTING @Axl
    // $uvch = curl_init();
    // curl_setopt($uvch, CURLOPT_URL, $uvdeletecartendpoint);
    // curl_setopt($uvch, CURLOPT_CUSTOMREQUEST, "DELETE");
    // curl_setopt($uvch, CURLOPT_RETURNTRANSFER, true);
    // $uvresultraw = curl_exec($uvch);
    $uvresponse = wp_remote_request($uvdeletecartendpoint, array(
        'method' => 'DELETE',
        'timeout' => 60,
    ));
    $uvresultraw = wp_remote_retrieve_body($uvresponse);

    $uvresult = json_decode($uvresultraw, true);

    // TESTING @Axl
    // curl_close($uvch);

    if(is_array($uvresult) and $uvresult["success"]){
        $uvcartcount = $uvresult["data"]["cartcount"];
    }
}

$uvreturn = array();

if($uvcartcode and $uvitemcartcode){
    // $uvcart = uws_get_cart($uvcartcode);
    $uvcart = urvenue_ws_get_cart($uvcartcode); // Axl UWS-7416
    $uvreturn = array(
        "cart" => $uvcart,
        "cartcode" => $uvcartcode,
        "item" => $uvitem
    );
}

$uvreturn["cartcount"] = $uvcartcount;

// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);