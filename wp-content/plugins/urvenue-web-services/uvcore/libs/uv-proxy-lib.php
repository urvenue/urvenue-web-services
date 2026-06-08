<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// $urvenue_ws_proxyurl = uws_get_proxyurl();
$urvenue_ws_proxyurl = urvenue_ws_get_proxyurl(); // Axl UWS-7416
// $urvenue_ws_config_addproxyparams = (isset($urvenue_ws_config_addproxyparams) and $urvenue_ws_config_addproxyparams) ? $urvenue_ws_config_addproxyparams : "";
$urvenue_ws_config_addproxyparams = (isset($urvenue_ws_config_addproxyparams) and $urvenue_ws_config_addproxyparams) ? $urvenue_ws_config_addproxyparams : ""; // Axl UWS-7416

// $urvenue_ws_proxies_lib = array(
$urvenue_ws_proxies_lib = array( // Axl UWS-7416
	"uvcore-init" => array(
		"inventory-init" => $urvenue_ws_proxyurl . "?action=urvenue_ws_proxy&uvaction=uwspx_inventoryinit",
		"cartdrop" => $urvenue_ws_proxyurl . "?action=urvenue_ws_proxy&uvaction=uwspx_cartdrop",
		"inventoryitempop" => $urvenue_ws_proxyurl . "?action=urvenue_ws_proxy&uvaction=uwspx_inventoryitempop",
		"events-load" => $urvenue_ws_proxyurl . "?action=urvenue_ws_proxy&uvaction=uwspx_loadevents",
	),
	"inventory" => array(
		"cart-additem" => $urvenue_ws_proxyurl . "?action=urvenue_ws_proxy&uvaction=uwspx_cartadditem{$urvenue_ws_config_addproxyparams}",
		"item-getottimes" => $urvenue_ws_proxyurl . "?action=urvenue_ws_proxy&uvaction=uwspx_itemgetottimes{$urvenue_ws_config_addproxyparams}",
		"item-gettimes" => $urvenue_ws_proxyurl . "?action=urvenue_ws_proxy&uvaction=uwspx_itemgettimes{$urvenue_ws_config_addproxyparams}",
		"item-getbk4times" => $urvenue_ws_proxyurl . "?action=urvenue_ws_proxy&uvaction=uwspx_itemgetbk4times{$urvenue_ws_config_addproxyparams}",
		"item-inquireform" => $urvenue_ws_proxyurl . "?action=urvenue_ws_proxy&uvaction=uwspx_iteminquireform{$urvenue_ws_config_addproxyparams}",
		"item-inquireform-pro" => $urvenue_ws_proxyurl . "?action=urvenue_ws_proxy&uvaction=uwspx_iteminquireformpro{$urvenue_ws_config_addproxyparams}",
		"get-cart-breakdown" => $urvenue_ws_proxyurl . "?action=urvenue_ws_proxy&uvaction=uwspx_getcartbreakdown{$urvenue_ws_config_addproxyparams}",
		"item-getbottles" => $urvenue_ws_proxyurl . "?action=urvenue_ws_proxy&uvaction=uwspx_itemgetbottles{$urvenue_ws_config_addproxyparams}",
	)
);