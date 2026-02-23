<?php

// @egt [UWS-7297]
uws_check_nonce("uwsinventory");

//Add the thing to the cart
$uvcartcode = uws_cleanup_request("cartcode");
$uvmanagementid = uws_cleanup_request("managementid");
$uvmanagementid = (!$uvmanagementid and isset($uws_defaultmanageentid)) ? $uws_defaultmanageentid : $uvmanagementid;
$uvmanagementid = (!$uvmanagementid and isset($uws_config_manageentid)) ? $uws_config_manageentid : $uvmanagementid;

$uveventdata = ($uvmanagementid) ? array("managementid" => $uvmanagementid) : "";

$uvcart = uws_get_cart($uvcartcode, $uveventdata);

$uvreturn = array(
    "cart" => $uvcart
);

$uvreturnjson = json_encode($uvreturn);
header('Content-Type: application/json');
echo($uvreturnjson);