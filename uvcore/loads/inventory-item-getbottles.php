<?php

// @egt [UWS-7297]
uws_check_nonce("uwsinventory");

$uvvenueid = (isset($_REQUEST["venueid"])) ? uws_cleanup_var($_REQUEST["venueid"]) : "";
$uvminspend = (isset($_REQUEST["subtotalagree"])) ? uws_cleanup_var($_REQUEST["subtotalagree"]) : 0;
$uvcurrencysymbol = (isset($_REQUEST["currencysymbol"])) ? uws_cleanup_var($_REQUEST["currencysymbol"]) : "$";

$uvbottleargs = array(
    "venueid" => $uvvenueid,
    "minspend" => $uvminspend,
    "currencysymbol" => $uvcurrencysymbol,
);
$uvitembottlesel = uws_get_itembottlesel($uvbottleargs);

$uvreturn = array(
    "html" => $uvitembottlesel["html"],
    "menubottles" => $uvitembottlesel["menubottles"],
);
    
$uvreturnjson = json_encode($uvreturn);
header('Content-Type: application/json');
echo($uvreturnjson);