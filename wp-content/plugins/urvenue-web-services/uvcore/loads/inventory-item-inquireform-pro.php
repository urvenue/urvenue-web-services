<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_feeds_lib;

// @egt [UWS-7297]
// uws_check_nonce("urvenue_ws_inventory");
urvenue_ws_check_nonce("urvenue_ws_inventory"); // Axl UWS-7416

$urvenue_ws_apiurl = $urvenue_ws_feeds_lib["inventory-inquiry"]["url"];

// $urvenue_ws_data = $_POST;
// $urvenue_ws_data = $_POST; // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified via urvenue_ws_check_nonce("urvenue_ws_inventory") above // Axl UWS-7416
$urvenue_ws_data = array( // Axl UWS-8150
    'manageentid'  => isset( $_POST['manageentid'] )  ? sanitize_text_field( wp_unslash( $_POST['manageentid'] ) )  : '',
    'venueid'      => isset( $_POST['venueid'] )      ? sanitize_text_field( wp_unslash( $_POST['venueid'] ) )      : '',
    'caldate'      => isset( $_POST['caldate'] )      ? sanitize_text_field( wp_unslash( $_POST['caldate'] ) )      : '',
    'itemcode'     => isset( $_POST['itemcode'] )     ? sanitize_text_field( wp_unslash( $_POST['itemcode'] ) )     : '',
    'booktypeid'   => isset( $_POST['booktypeid'] )   ? sanitize_text_field( wp_unslash( $_POST['booktypeid'] ) )   : '',
    'globaltype'   => isset( $_POST['globaltype'] )   ? sanitize_text_field( wp_unslash( $_POST['globaltype'] ) )   : '',
    'mastercode'   => isset( $_POST['mastercode'] )   ? sanitize_text_field( wp_unslash( $_POST['mastercode'] ) )   : '',
    'itemname'     => isset( $_POST['itemname'] )     ? sanitize_text_field( wp_unslash( $_POST['itemname'] ) )     : '',
    'partyname'    => isset( $_POST['partyname'] )    ? sanitize_text_field( wp_unslash( $_POST['partyname'] ) )    : '',
    'fname'        => isset( $_POST['fname'] )        ? sanitize_text_field( wp_unslash( $_POST['fname'] ) )        : '',
    'lname'        => isset( $_POST['lname'] )        ? sanitize_text_field( wp_unslash( $_POST['lname'] ) )        : '',
    'email'        => isset( $_POST['email'] )        ? sanitize_email( wp_unslash( $_POST['email'] ) )             : '',
    'phonecode'    => isset( $_POST['phonecode'] )    ? sanitize_text_field( wp_unslash( $_POST['phonecode'] ) )    : '',
    'phonenumber'  => isset( $_POST['phonenumber'] )  ? sanitize_text_field( wp_unslash( $_POST['phonenumber'] ) )  : '',
    'partysize'    => isset( $_POST['partysize'] )    ? sanitize_text_field( wp_unslash( $_POST['partysize'] ) )    : '',
    'loyality'     => isset( $_POST['loyality'] )     ? sanitize_text_field( wp_unslash( $_POST['loyality'] ) )     : '',
    'budget'       => isset( $_POST['budget'] )       ? sanitize_text_field( wp_unslash( $_POST['budget'] ) )       : '',
    'comments'     => isset( $_POST['comments'] )     ? sanitize_textarea_field( wp_unslash( $_POST['comments'] ) ) : '',
    'optin'        => isset( $_POST['optin'] )        ? sanitize_text_field( wp_unslash( $_POST['optin'] ) )        : '',
    'optinpriv'    => isset( $_POST['optinpriv'] )    ? sanitize_text_field( wp_unslash( $_POST['optinpriv'] ) )    : '',
); // Axl UWS-8150
$urvenue_ws_data["phone"] = ($urvenue_ws_data["phonecode"] and $urvenue_ws_data["phonenumber"]) ? $urvenue_ws_data["phonecode"] . "." . $urvenue_ws_data["phonenumber"] : "";
// $urvenue_ws_data["optinemail"] = (isset($_REQUEST["optin"])) ? $_REQUEST["optin"] : ""; // Axl UWS-7418
// $urvenue_ws_data["optinemail"] = (isset($_REQUEST["optin"])) ? sanitize_text_field( wp_unslash( $_REQUEST["optin"] ) ) : ""; // Axl UWS-7418
$urvenue_ws_data["optinemail"] = (isset($_REQUEST["optin"])) ? sanitize_text_field( wp_unslash( $_REQUEST["optin"] ) ) : ""; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verified via urvenue_ws_check_nonce("urvenue_ws_inventory") above // Axl UWS-7416

unset($urvenue_ws_data["action"]);
unset($urvenue_ws_data["uvaction"]);
unset($urvenue_ws_data["phonecode"]);
unset($urvenue_ws_data["phonenumber"]);
//$urvenue_ws_data = array();

// TESTING @Axl
// $curl = curl_init();
// curl_setopt_array($curl, [
//     CURLOPT_URL => $urvenue_ws_apiurl,
//     CURLOPT_POST => true,
//     CURLOPT_POSTFIELDS => $urvenue_ws_data,
//     CURLOPT_RETURNTRANSFER => true,
// ]);
// $urvenue_ws_response = curl_exec($curl);
// curl_close($curl);
// $uvwpresponse = wp_remote_post($urvenue_ws_apiurl, array(
$urvenue_ws_wpresponse = wp_remote_post($urvenue_ws_apiurl, array( // Axl UWS-7634
    'body' => $urvenue_ws_data,
    'timeout' => 60,
));
// $urvenue_ws_response = wp_remote_retrieve_body($uvwpresponse);
$urvenue_ws_response = wp_remote_retrieve_body($urvenue_ws_wpresponse); // Axl UWS-7634

$urvenue_ws_response = json_decode($urvenue_ws_response, true);

if(is_array($urvenue_ws_response) and  $urvenue_ws_response["uv"]["success"]["status"] == "success"){
    // $urvenue_ws_popcontent = uws_get_template("inventory/inventory-inq-success");
    $urvenue_ws_popcontent = urvenue_ws_get_template("inventory/inventory-inq-success"); // Axl UWS-7416
}
else{
    $urvenue_ws_errormsg = (isset($urvenue_ws_response["uv"]["success"]["message"])) ? $urvenue_ws_response["uv"]["success"]["message"] : "";
    // $urvenue_ws_popcontent = uws_get_template("inventory/inventory-inq-failed");
    $urvenue_ws_popcontent = urvenue_ws_get_template("inventory/inventory-inq-failed"); // Axl UWS-7416
    $urvenue_ws_popcontent = str_replace("{apierrormsg}", $urvenue_ws_errormsg, $urvenue_ws_popcontent);
}

$urvenue_ws_returnarray = array(
    "html" => $urvenue_ws_popcontent
);

// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_returnarray);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_returnarray);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416