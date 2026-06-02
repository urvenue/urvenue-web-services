<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_core_lib;

// @egt [UWS-7297]
// uws_check_nonce("uwsinventory");
urvenue_ws_check_nonce("uwsinventory"); // Axl UWS-7416

//Check if v2 should be used
if(is_array($urvenue_ws_core_lib) and isset($urvenue_ws_core_lib["system"]["use-cartv2"]) and $urvenue_ws_core_lib["system"]["use-cartv2"])
    include_once($urvenue_ws_corepath . "/loads/cartv2/cart-deleteitem.php");
else
    include_once($urvenue_ws_corepath . "/loads/cartv1/cart-deleteitem.php");