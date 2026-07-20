<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_core_lib;

urvenue_ws_check_nonce("urvenue_ws_inventory");

$urvenue_ws_checkoutinfo = urvenue_ws_cleanup_request("checkoutinfo");
$urvenue_ws_forcecheckotv1 = ($urvenue_ws_checkoutinfo == "forcecheckoutv1") ? 1 : 0;

if($urvenue_ws_forcecheckotv1){
    $urvenue_ws_core_lib["system"]["use-cartv2"] = 0;
    $urvenue_ws_core_lib["system"]["checkouttype"] = "microsite";
}

//Check if v2 should be used
if(is_array($urvenue_ws_core_lib) and isset($urvenue_ws_core_lib["system"]["use-cartv2"]) and $urvenue_ws_core_lib["system"]["use-cartv2"] and !$urvenue_ws_forcecheckotv1)
    include_once($urvenue_ws_corepath . "/loads/cartv2/cart-additem.php");
else
    include_once($urvenue_ws_corepath . "/loads/cartv1/cart-additem.php");
