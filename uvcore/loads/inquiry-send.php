<?php

global $uws_feeds_lib;

$uvapiurl = $uws_feeds_lib["inquiry-send"]["url"];

$uvdata = $_POST;
$uvdata["phone"] = ($uvdata["phonecode"] and $uvdata["phonenumber"]) ? $uvdata["phonecode"] . "." . $uvdata["phonenumber"] : "";
$uvdata["optinemail"] = (isset($_REQUEST["optin"])) ? $_REQUEST["optin"] : "";

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

$uvstatus = "";
if(is_array($uvresponse) and  $uvresponse["uv"]["success"]["status"] == "success"){
    $uvpopcontent = uws_get_template("reservations/inquiry-success");
    $uvstatus = "success";
}
else{
    $uverrormsg = (isset($uvresponse["uv"]["success"]["message"])) ? $uvresponse["uv"]["success"]["message"] : "";
    $uvpopcontent = uws_get_template("reservations/inquiry-failed");
    $uvpopcontent = str_replace("{apierrormsg}", $uverrormsg, $uvpopcontent);
    $uvstatus = "error";
}

$uvreturnarray = array(
    "status" => $uvresponse["uv"]["success"]["status"],
    "msg" => $uvpopcontent
);

$uvreturnjson = json_encode($uvreturnarray);
header('Content-Type: application/json');
echo($uvreturnjson);