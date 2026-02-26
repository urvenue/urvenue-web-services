<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
    
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);