<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// $uws_proxyurl = uws_get_proxyurl();
$uws_proxyurl = urvenue_ws_get_proxyurl(); // Axl UWS-7416
$uws_config_addproxyparams = (isset($uws_config_addproxyparams) and $uws_config_addproxyparams) ? $uws_config_addproxyparams : "";

$uws_proxies_lib = array(
	"uvcore-init" => array(
		"inventory-init" => $uws_proxyurl . "?action=uvpx&uvaction=uwspx_inventoryinit",
		"cartdrop" => $uws_proxyurl . "?action=uvpx&uvaction=uwspx_cartdrop",
		"inventoryitempop" => $uws_proxyurl . "?action=uvpx&uvaction=uwspx_inventoryitempop",
		"events-load" => $uws_proxyurl . "?action=uvpx&uvaction=uwspx_loadevents",
	),
	"inventory" => array(
		"cart-additem" => $uws_proxyurl . "?action=uvpx&uvaction=uwspx_cartadditem{$uws_config_addproxyparams}",
		"item-getottimes" => $uws_proxyurl . "?action=uvpx&uvaction=uwspx_itemgetottimes{$uws_config_addproxyparams}",
		"item-gettimes" => $uws_proxyurl . "?action=uvpx&uvaction=uwspx_itemgettimes{$uws_config_addproxyparams}",
		"item-getbk4times" => $uws_proxyurl . "?action=uvpx&uvaction=uwspx_itemgetbk4times{$uws_config_addproxyparams}",
		"item-inquireform" => $uws_proxyurl . "?action=uvpx&uvaction=uwspx_iteminquireform{$uws_config_addproxyparams}",
		"item-inquireform-pro" => $uws_proxyurl . "?action=uvpx&uvaction=uwspx_iteminquireformpro{$uws_config_addproxyparams}",
		"get-cart-breakdown" => $uws_proxyurl . "?action=uvpx&uvaction=uwspx_getcartbreakdown{$uws_config_addproxyparams}",
		"item-getbottles" => $uws_proxyurl . "?action=uvpx&uvaction=uwspx_itemgetbottles{$uws_config_addproxyparams}",
	)
);