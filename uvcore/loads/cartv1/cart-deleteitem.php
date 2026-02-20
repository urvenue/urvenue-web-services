<?php

$uvcartcode = (isset($_REQUEST["cartcode"])) ? uws_cleanup_var($_REQUEST["cartcode"]) : "";
$uvitemcartcode = (isset($_REQUEST["itemcartcode"])) ? uws_cleanup_var($_REQUEST["itemcartcode"]) : "";
$uvmanagementid = (isset($_REQUEST["managementid"])) ? uws_cleanup_var($_REQUEST["managementid"]) : "";
$uvmanagementid = (!$uvmanagementid and isset($uws_defaultmanageentid)) ? $uws_defaultmanageentid : $uvmanagementid;
$uvmanagementid = (!$uvmanagementid and isset($uws_config_manageentid)) ? $uws_config_manageentid : $uvmanagementid;
$uvmastercode = (isset($_REQUEST["mastercode"])) ? uws_cleanup_var($_REQUEST["mastercode"]) : "";
$uvitem = ($uvmastercode) ? uws_get_invitem($uvmastercode) : "";

if($uvcartcode and $uvitemcartcode){
    $uvcartparams = "cartcode=$uvcartcode&itemcartcode=$uvitemcartcode";
    $uveventdata = ($uvmanagementid) ? array("managementid" => $uvmanagementid) : "";
    $uvcartfeedresponse = uws_get_apiwvar("cart-delete", $uvcartparams, $uveventdata);
}

if(is_array($uvcartfeedresponse) and $uvcartfeedresponse["uv"]["success"]["status"] == "success"){
    $uvcartcode = $uvcartfeedresponse["uv"]["data"]["cartcode"];
    $uvcartcode = ($uvcartfeedresponse["uv"]["data"]["cart"]) ? $uvcartcode : "";
    $uvcart = uws_get_cart($uvcartcode, $uvvendata, $uvcartfeedresponse);
}

$uvreturn = array(
    "cart" => $uvcart,
    "cartcode" => $uvcartcode,
    "item" => $uvitem
);

// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);