<?php

global $uws_feeds_lib;

$uvcartcode = (isset($_REQUEST["cartcode"])) ? uws_cleanup_var($_REQUEST["cartcode"]) : "";
$uvitemcartcode = (isset($_REQUEST["itemcartcode"])) ? uws_cleanup_var($_REQUEST["itemcartcode"]) : "";
$uvmanagementid = (isset($_REQUEST["managementid"])) ? uws_cleanup_var($_REQUEST["managementid"]) : "";
$uvmanagementid = (!$uvmanagementid and isset($uws_defaultmanageentid)) ? $uws_defaultmanageentid : $uvmanagementid;
$uvmanagementid = (!$uvmanagementid and isset($uws_config_manageentid)) ? $uws_config_manageentid : $uvmanagementid;
$uvmastercode = (isset($_REQUEST["mastercode"])) ? uws_cleanup_var($_REQUEST["mastercode"]) : "";
$uvitem = ($uvmastercode) ? uws_get_invitem($uvmastercode) : "";
$uvcartcount = 1;

if($uvcartcode and $uvitemcartcode){

    $uvdeletecartendpoint = $uws_feeds_lib["cartv2-delete"]["url"];
    $uvitemdata = array(
        "cartcode" => $uvcartcode,
        "itemcartcode" => $uvitemcartcode
    );

    $uvdeletecartendpoint = $uvdeletecartendpoint . "&" . http_build_query($uvitemdata);

    $uvch = curl_init();
    curl_setopt($uvch, CURLOPT_URL, $uvdeletecartendpoint);
    curl_setopt($uvch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($uvch, CURLOPT_RETURNTRANSFER, true);
    $uvresultraw = curl_exec($uvch);
    $uvresult = json_decode($uvresultraw, true);

    curl_close($uvch);

    if(is_array($uvresult) and $uvresult["success"]){
        $uvcartcount = $uvresult["data"]["cartcount"];
    }
}

$uvreturn = array();

if($uvcartcode and $uvitemcartcode){
    $uvcart = uws_get_cart($uvcartcode);
    $uvreturn = array(
        "cart" => $uvcart,
        "cartcode" => $uvcartcode,
        "item" => $uvitem
    );
}

$uvreturn["cartcount"] = $uvcartcount;

$uvreturnjson = json_encode($uvreturn);
header('Content-Type: application/json');
echo($uvreturnjson);