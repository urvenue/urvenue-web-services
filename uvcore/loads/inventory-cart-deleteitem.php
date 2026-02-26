<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_core_lib;

//Check if v2 should be used
if(is_array($uws_core_lib) and isset($uws_core_lib["system"]["use-cartv2"]) and $uws_core_lib["system"]["use-cartv2"])
    include_once($uws_corepath . "/loads/cartv2/cart-deleteitem.php");
else
    include_once($uws_corepath . "/loads/cartv1/cart-deleteitem.php");