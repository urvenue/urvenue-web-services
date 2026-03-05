<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_core_lib;

// @egt [UWS-7297]
// uws_check_nonce("uwsinventory");
urvenue_ws_check_nonce("uwsinventory"); // Axl UWS-7416

// $uvcheckoutinfo = uws_cleanup_request("checkoutinfo");
$uvcheckoutinfo = urvenue_ws_cleanup_request("checkoutinfo"); // Axl UWS-7416
$uvforcecheckotv1 = ($uvcheckoutinfo == "forcecheckoutv1") ? 1 : 0;

if($uvforcecheckotv1){
    $uws_core_lib["system"]["use-cartv2"] = 0;
    $uws_core_lib["system"]["checkouttype"] = "microsite";
}

//Check if v2 should be used
if(is_array($uws_core_lib) and isset($uws_core_lib["system"]["use-cartv2"]) and $uws_core_lib["system"]["use-cartv2"] and !$uvforcecheckotv1)
    include_once($uws_corepath . "/loads/cartv2/cart-additem.php");
else
    include_once($uws_corepath . "/loads/cartv1/cart-additem.php");