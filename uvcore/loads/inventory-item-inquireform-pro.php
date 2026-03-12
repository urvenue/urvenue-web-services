<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_feeds_lib;

// @egt [UWS-7297]
// uws_check_nonce("uwsinventory");
urvenue_ws_check_nonce("uwsinventory"); // Axl UWS-7416

$uvapiurl = $uws_feeds_lib["inventory-inquiry"]["url"];

$uvdata = $_POST;
$uvdata["phone"] = ($uvdata["phonecode"] and $uvdata["phonenumber"]) ? $uvdata["phonecode"] . "." . $uvdata["phonenumber"] : "";
// $uvdata["optinemail"] = (isset($_REQUEST["optin"])) ? $_REQUEST["optin"] : ""; // Axl UWS-7418
$uvdata["optinemail"] = (isset($_REQUEST["optin"])) ? sanitize_text_field( wp_unslash( $_REQUEST["optin"] ) ) : ""; // Axl UWS-7418

unset($uvdata["action"]);
unset($uvdata["uvaction"]);
unset($uvdata["phonecode"]);
unset($uvdata["phonenumber"]);
//$uvdata = array();

// TESTING @Axl
// $curl = curl_init();
// curl_setopt_array($curl, [
//     CURLOPT_URL => $uvapiurl,
//     CURLOPT_POST => true,
//     CURLOPT_POSTFIELDS => $uvdata,
//     CURLOPT_RETURNTRANSFER => true,
// ]);
// $response = curl_exec($curl);
// curl_close($curl);
$uvwpresponse = wp_remote_post($uvapiurl, array(
    'body' => $uvdata,
    'timeout' => 60,
));
$response = wp_remote_retrieve_body($uvwpresponse);

$uvresponse = json_decode($response, true);

if(is_array($uvresponse) and  $uvresponse["uv"]["success"]["status"] == "success"){
    // $uvpopcontent = uws_get_template("inventory/inventory-inq-success");
    $uvpopcontent = urvenue_ws_get_template("inventory/inventory-inq-success"); // Axl UWS-7416
}
else{
    $uverrormsg = (isset($uvresponse["uv"]["success"]["message"])) ? $uvresponse["uv"]["success"]["message"] : "";
    // $uvpopcontent = uws_get_template("inventory/inventory-inq-failed");
    $uvpopcontent = urvenue_ws_get_template("inventory/inventory-inq-failed"); // Axl UWS-7416
    $uvpopcontent = str_replace("{apierrormsg}", $uverrormsg, $uvpopcontent);
}

$uvreturnarray = array(
    "html" => $uvpopcontent
);

// @Axl
// $uvreturnjson = json_encode($uvreturnarray);
$uvreturnjson = wp_json_encode($uvreturnarray);
// @Axl End
header('Content-Type: application/json');
// echo($uvreturnjson);
echo( $uvreturnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416