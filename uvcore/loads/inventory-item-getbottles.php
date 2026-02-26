<?php

// @egt [UWS-7297]
uws_check_nonce("uwsinventory");

$uvvenueid = uws_cleanup_request("venueid");
$uvminspend = uws_cleanup_request("subtotalagree", 0);
$uvcurrencysymbol = uws_cleanup_request("currencysymbol", "$");

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