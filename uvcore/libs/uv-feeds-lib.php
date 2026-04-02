<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_core_lib;

//$uws_config_envicode = "apiuat"; //overwrite environment configuration, be careful

// $uws_envicode = (isset($uws_config_envicode)) ? $uws_config_envicode : "api";
$urvenue_ws_envicode = (isset($uws_config_envicode)) ? $uws_config_envicode : "api"; // Axl UWS-7416
// $uws_envicode = (isset($uws_core_lib["system"]["use-staging"]) and $uws_core_lib["system"]["use-staging"]) ? "apistaging" : $uws_envicode;
$urvenue_ws_envicode = (isset($urvenue_ws_core_lib["system"]["use-staging"]) and $urvenue_ws_core_lib["system"]["use-staging"]) ? "apistaging" : $urvenue_ws_envicode; // Axl UWS-7416

// $uws_envicode = (isset($_REQUEST["uvstaging"]) and $_REQUEST["uvstaging"]) ? "apistaging" : $uws_envicode;
// $urvenue_ws_envicode = (isset($_REQUEST["uvstaging"]) and $_REQUEST["uvstaging"]) ? "apistaging" : $urvenue_ws_envicode; // Axl UWS-7416
$urvenue_ws_envicode = (isset($_REQUEST["uvstaging"]) and sanitize_text_field( wp_unslash( $_REQUEST["uvstaging"] ) )) ? "apistaging" : $urvenue_ws_envicode; // Axl UWS-7418
// $uws_envicode = (isset($_REQUEST["uvenvicode"]) and $_REQUEST["uvenvicode"]) ? $_REQUEST["uvenvicode"] : $uws_envicode;
// $urvenue_ws_envicode = (isset($_REQUEST["uvenvicode"]) and $_REQUEST["uvenvicode"]) ? $_REQUEST["uvenvicode"] : $urvenue_ws_envicode; // Axl UWS-7416
$urvenue_ws_envicode = (isset($_REQUEST["uvenvicode"]) and sanitize_key( wp_unslash( $_REQUEST["uvenvicode"] ) )) ? sanitize_key( wp_unslash( $_REQUEST["uvenvicode"] ) ) : $urvenue_ws_envicode; // Axl UWS-7418

//API Vars
// $uws_apikey = (isset($uws_apikey) and $uws_apikey) ? $uws_apikey : $uws_core_lib["system"]["apikey"];
$urvenue_ws_apikey = (isset($urvenue_ws_apikey) and $urvenue_ws_apikey) ? $urvenue_ws_apikey : $urvenue_ws_core_lib["system"]["apikey"]; // Axl UWS-7416
// $uws_sourcecode = (isset($uws_sourcecode) and $uws_sourcecode) ? $uws_sourcecode : $uws_core_lib["system"]["sourcecode"];
$urvenue_ws_sourcecode = (isset($urvenue_ws_sourcecode) and $urvenue_ws_sourcecode) ? $urvenue_ws_sourcecode : $urvenue_ws_core_lib["system"]["sourcecode"]; // Axl UWS-7416
// $uws_sourceloc = (isset($uws_sourceloc) and $uws_sourceloc) ? $uws_sourceloc : $uws_core_lib["system"]["sourceloc"];
$urvenue_ws_sourceloc = (isset($urvenue_ws_sourceloc) and $urvenue_ws_sourceloc) ? $urvenue_ws_sourceloc : $urvenue_ws_core_lib["system"]["sourceloc"]; // Axl UWS-7416

// $uws_bkgaddsubd = ($uws_envicode == "apistaging") ? "staging." : "";
$urvenue_ws_bkgaddsubd = ($urvenue_ws_envicode == "apistaging") ? "staging." : ""; // Axl UWS-7416
// $uws_bkgaddsubd = ($uws_envicode == "apiuat") ? "uat." : $uws_bkgaddsubd;
$urvenue_ws_bkgaddsubd = ($urvenue_ws_envicode == "apiuat") ? "uat." : $urvenue_ws_bkgaddsubd; // Axl UWS-7416
// $uws_bkgaddsubd = (isset($uws_addbookingsubdomain) and $uws_addbookingsubdomain) ? $uws_addbookingsubdomain : $uws_bkgaddsubd;
$urvenue_ws_bkgaddsubd = (isset($uws_addbookingsubdomain) and $uws_addbookingsubdomain) ? $uws_addbookingsubdomain : $urvenue_ws_bkgaddsubd; // Axl UWS-7416

// Remove double booketing
// $uws_bkgaddsubd = ($uws_bkgaddsubd === "booketing.") ? "" : $uws_bkgaddsubd;
$urvenue_ws_bkgaddsubd = ($urvenue_ws_bkgaddsubd === "booketing.") ? "" : $urvenue_ws_bkgaddsubd; // Axl UWS-7416

// $uws_config_addurlstaging = (isset($uws_config_addurlstaging) and $uws_config_addurlstaging) ? $uws_config_addurlstaging : "";
$urvenue_ws_config_addurlstaging = (isset($urvenue_ws_config_addurlstaging) and $urvenue_ws_config_addurlstaging) ? $urvenue_ws_config_addurlstaging : ""; // Axl UWS-7416
// $uws_config_addcheckouturl = (isset($uws_config_addcheckouturl) and $uws_config_addcheckouturl) ? $uws_config_addcheckouturl : "";
$urvenue_ws_config_addcheckouturl = (isset($urvenue_ws_config_addcheckouturl) and $urvenue_ws_config_addcheckouturl) ? $urvenue_ws_config_addcheckouturl : ""; // Axl UWS-7416

// $uws_menuapikey = ($uws_envicode == "apistaging") ? "VEFOHYHKCTCP" : "SREOCZRCGKNC";
$urvenue_ws_menuapikey = ($urvenue_ws_envicode == "apistaging") ? "VEFOHYHKCTCP" : "SREOCZRCGKNC"; // Axl UWS-7416
// $uws_menuapikey = (isset($uws_config_menuapikey) and $uws_config_menuapikey)
// 	? $uws_config_menuapikey
// 	: $uws_menuapikey;
$urvenue_ws_menuapikey = (isset($uws_config_menuapikey) and $uws_config_menuapikey) // Axl UWS-7416
	? $uws_config_menuapikey
	: $urvenue_ws_menuapikey;
// $uws_menuapikey = (isset($uws_core_lib["events"]["addon-bottles"]["menuapikey"]) and $uws_core_lib["events"]["addon-bottles"]["menuapikey"])
// 	? $uws_core_lib["events"]["addon-bottles"]["menuapikey"]
// 	: $uws_menuapikey;
$urvenue_ws_menuapikey = (isset($urvenue_ws_core_lib["events"]["addon-bottles"]["menuapikey"]) and $urvenue_ws_core_lib["events"]["addon-bottles"]["menuapikey"]) // Axl UWS-7416
	? $urvenue_ws_core_lib["events"]["addon-bottles"]["menuapikey"]
	: $urvenue_ws_menuapikey;

// $uws_feeds_lib = array(
$urvenue_ws_feeds_lib = array( // Axl UWS-7416
    "urquery" => array(//Deprecated
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/urassistant/urquery/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&{params}",
		"expiration" => 3600,
	),
	"inventory" => array(//Should Use Globally From 13/06/2024 - Short Cache Time, show inventory purposes
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/gxn/inventory/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&{params}",
		"expiration" => 60
	),
	"inventory-eventsonly" => array(//Should Use Globally From 13/06/2024 - Filter Only Events
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/gxn/inventory/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&filters=data:schedule&{params}",
		"expiration" => 3600
	),
	"inventorylist" => array(//Deprecated
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/gxn/inventorylist/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&{params}",
		"expiration" => 60
	),
	"inventoryitem" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/gxn/inventoryitem/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&{params}",
		"expiration" => 60
	),
	"inventorymap" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/gxn/inventorymap/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&filter=all&{params}",
		"expiration" => 60
	),
	"mapinventory" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/gxn/mapinventory/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&filter=all&{params}",
		"expiration" => 60
	),
	"mapinventorykeep" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/gxn/mapinventory/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&filter=all&{params}",
		"expiration" => 3600
	),
	"packagesinventory" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/gxn/inventory/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&{params}",
		"expiration" => 86400
	),
	"packagesiteminfo" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/gxn/inventoryitem/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&{params}",
		"expiration" => 86400
	),
	"inventorylist-events" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/gxn/inventorylist/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&filters=data:schedule&{params}",
		"expiration" => 3600,
	),
	"inventorylist-venues" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/gxn/inventorylist/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&{params}",
		"expiration" => 86400,
	),
	"inventorylist-events-stocks" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/gxn/inventorylist/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&filters=data:events&{params}",
		"expiration" => 3600,
	),
	"inventorylist-events-marketplace" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/gxn/inventorylist/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&filters=marketplace|data:schedule&{params}",
		"expiration" => 3600,
	),
	"gxnavailability" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/gxn/availability/json/?apikey=$urvenue_ws_apikey&channel=public_prepay&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&{params}",
		"expiration" => 3600,
	),
	"venueday" => array( //Will be deprecated
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/gxn/venueday/json/?providerid={providerid}&resellerid={resellerid}&channel=public_prepay&apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&{params}",
		"expiration" => 60,
	),
	"cart-get" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/urcheckout/cartitems/json/get?apikey=$urvenue_ws_apikey&resellerid={resellerid}&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&channel=public_prepay&network=hosted&manageentid={manageentid}&{params}",
		"expiration" => 10,
	),
	"cart-create" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/urcheckout/cartitems/json/post?apikey=$urvenue_ws_apikey&resellerid={resellerid}&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&channel=public_prepay&network=hosted&manageentid={manageentid}&{params}",
		"expiration" => -1,
	),
	"cart-update" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/urcheckout/cartitems/json/put?apikey=$urvenue_ws_apikey&resellerid={resellerid}&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&channel=public_prepay&network=hosted&manageentid={manageentid}&{params}",
		"expiration" => -1,
	),
	"cart-delete" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/urcheckout/cartitems/json/delete?apikey=$urvenue_ws_apikey&resellerid={resellerid}&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&channel=public_prepay&network=hosted&manageentid={manageentid}&{params}",
		"expiration" => -1,
	),
	"inventory-inquiry" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/booking/inquiry/json?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc",
		"expiration" => -1,
	),
	"inquiry-leadtypes" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/urinventory/leadstypes/json/?apikey=$urvenue_ws_apikey&manageentid={manageentid}&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&{params}&systemid=15&accountid=82&appaccountid=82",
		"expiration" => 3600,
	),
	"inquiry-send" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/booking/inquiry/json?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc",
		"expiration" => -1,
	),
	"ot-itemtimes" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/urcheckout/otavailability/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&{params}",
		"expiration" => -1,
		"varpass" => "url"
	),
	"bk4-itemtimes" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/urcheckout/b4tavailability/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&{params}",
		"expiration" => -1,
		"varpass" => "url"
	),
	"cartv2-create" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v2/transact/cartitems/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc",
		"expiration" => -1,
	),
	"cartv2-add" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v2/transact/cartitems/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc",
		"expiration" => -1,
	),
	"cartv2-get" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v2/transact/cartitems/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&{params}",
		"expiration" => -1,
	),
	"cartv2-delete" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v2/transact/cartitems/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc",
		"expiration" => -1,
	),
	"marketevents" => array(
		"url" => "https://apiuat.urvenue.me/v1/vea/marketevents/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&{params}",
		"expiration" => 3600,
	),
	"eventvenues" => array(
		"url" => "https://apiuat.urvenue.me/v1/gxn/eventvenues/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&{params}",
		"expiration" => 3600,
	),
	"marketeventvenues" => array(
		"url" => "https://apiuat.urvenue.me/v1/gxn/marketeventvenues/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&{params}",
		"expiration" => 3600,
	),
	"digital-menu" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/urinventory/menu/json/?apikey=$urvenue_ws_menuapikey&systemid=18&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&{params}",
		"expiration" => 3600,
	),
	"ecozonedetails" => array(
		"url" => "https://$urvenue_ws_envicode.urvenue.me/v1/global/ecozonedetails/json/?apikey=$urvenue_ws_apikey&sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&{params}",
		"expiration" => 3600,
	),
);

// $uws_feedscleartime = 86400;//172800 - 2 Days
$urvenue_ws_feedscleartime = 86400;//172800 - 2 Days // Axl UWS-7416

// $uws_endcurlangparam = (uws_get_cur_lang() != "en") ? "&lang=" . uws_get_cur_lang() : "";
$urvenue_ws_endcurlangparam = (urvenue_ws_get_cur_lang() != "en") ? "&lang=" . urvenue_ws_get_cur_lang() : ""; // Axl UWS-7416
// $uws_addcheckoutparams = $uws_endcheckoutparams = $uws_addurlstaging ="";
$urvenue_ws_addcheckoutparams = $urvenue_ws_endcheckoutparams = $urvenue_ws_addurlstaging = ""; // Axl UWS-7416
// if(urvenue_ws_get_cur_lang() != "en"){ // Axl UWS-7416
if(urvenue_ws_get_cur_lang() != "en"){
	// $uws_addcheckoutparams = "?lang=" . uws_get_cur_lang();
	$urvenue_ws_addcheckoutparams = "?lang=" . urvenue_ws_get_cur_lang(); // Axl UWS-7416
	// $uws_endcheckoutparams = "&lang=" . uws_get_cur_lang();
	$urvenue_ws_endcheckoutparams = "&lang=" . urvenue_ws_get_cur_lang(); // Axl UWS-7416
}
else
	$urvenue_ws_addurlstaging = str_replace("&", "?", $urvenue_ws_addurlstaging); // Axl UWS-7416

//$urvenue_ws_bkgaddsubd = "staging."; //overwrite booking checkout subdomain, be careful
// $uws_bkgaddhost = $uws_config_chkhost ?? "booketing";
$urvenue_ws_bkgaddhost = $urvenue_ws_config_chkhost ?? "booketing"; // Axl UWS-7416
// $uws_actionlinks_lib = array(
$urvenue_ws_actionlinks_lib = array( // Axl UWS-7416
	"microsite" => array(
		// "checkout-carturl" => "https://{$urvenue_ws_bkgaddsubd}$urvenue_ws_bkgaddhost.com/checkout/cart/{cartcode}/?sourcecode=$urvenue_ws_sourcecode&sourceloc={sourceloc}&manageents={manageentid}&resellerid={resellerid}&providerid={providerid}&noredirect=1{$uws_config_addurlstaging}{$uws_config_addcheckouturl}&lang=" . uws_get_cur_lang(),
		"checkout-carturl" => "https://{$urvenue_ws_bkgaddsubd}$urvenue_ws_bkgaddhost.com/checkout/cart/{cartcode}/?sourcecode=$urvenue_ws_sourcecode&sourceloc={sourceloc}&manageents={manageentid}&resellerid={resellerid}&providerid={providerid}&noredirect=1{$urvenue_ws_config_addurlstaging}{$urvenue_ws_config_addcheckouturl}&lang=" . urvenue_ws_get_cur_lang(), // Axl UWS-7416
		// "checkout-checkurl" => "https://{$urvenue_ws_bkgaddsubd}$urvenue_ws_bkgaddhost.com/checkout/details/{cartcode}/?sourcecode=$urvenue_ws_sourcecode&sourceloc={sourceloc}&manageents={manageentid}&resellerid={resellerid}&providerid={providerid}{$uws_config_addurlstaging}{$uws_config_addcheckouturl}&lang=" . uws_get_cur_lang(),
		"checkout-checkurl" => "https://{$urvenue_ws_bkgaddsubd}$urvenue_ws_bkgaddhost.com/checkout/details/{cartcode}/?sourcecode=$urvenue_ws_sourcecode&sourceloc={sourceloc}&manageents={manageentid}&resellerid={resellerid}&providerid={providerid}{$urvenue_ws_config_addurlstaging}{$urvenue_ws_config_addcheckouturl}&lang=" . urvenue_ws_get_cur_lang(), // Axl UWS-7416
	),
	"bk" => array(
		"checkout-carturl" => "#uws-view-cart",
		"checkout-checkurl" => "https://{$urvenue_ws_bkgaddsubd}booketing.com/bk/{sourceloc}/checkout/{manageentid}/{cartcode}/info{$urvenue_ws_addcheckoutparams}{$urvenue_ws_addurlstaging}",
		"checkout-payment" => "https://{$urvenue_ws_bkgaddsubd}booketing.com/bk/{sourceloc}/checkout/{manageentid}/{cartcode}/billing{$urvenue_ws_addcheckoutparams}{$urvenue_ws_addurlstaging}",
		"checkout-success" => "https://{$urvenue_ws_bkgaddsubd}booketing.com/bk/{sourceloc}/checkout/{manageentid}/{cartcode}/success{$urvenue_ws_addcheckoutparams}{$urvenue_ws_addurlstaging}",
	),
	"uvcheckout" => array(
		"checkout-carturl" => "https://{$urvenue_ws_bkgaddsubd}booketing.com/uvcheckout/cart/?cartcode={cartcode}&sourcecode={sourcecode}&sourceloc={sourceloc}{$urvenue_ws_endcheckoutparams}{$urvenue_ws_addurlstaging}{$urvenue_ws_config_addcheckouturl}",
		"checkout-checkurl" => "https://{$urvenue_ws_bkgaddsubd}booketing.com/uvcheckout/checkout/?cartcode={cartcode}&sourcecode={sourcecode}&sourceloc={sourceloc}{$urvenue_ws_endcheckoutparams}{$urvenue_ws_addurlstaging}{$urvenue_ws_config_addcheckouturl}",
		"checkout-payment" => "https://{$urvenue_ws_bkgaddsubd}booketing.com/uvcheckout/payment/?cartcode={cartcode}&sourcecode={sourcecode}&sourceloc={sourceloc}{$urvenue_ws_endcheckoutparams}{$urvenue_ws_addurlstaging}{$urvenue_ws_config_addcheckouturl}",
		"checkout-success" => "https://{$urvenue_ws_bkgaddsubd}booketing.com/uvcheckout/success/?cartcode={cartcode}&sourcecode={sourcecode}&sourceloc={sourceloc}{$urvenue_ws_endcheckoutparams}{$urvenue_ws_addurlstaging}{$urvenue_ws_config_addcheckouturl}",
	),
);

//Send checkout to our staging=live
/*$urvenue_ws_actionlinks_lib = array(
	"checkout-carturl" => "https://staging.{$urvenue_ws_bkgaddsubd}booketing.com/checkout/cart/{cartcode}/?sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&manageents={manageentid}&resellerid={resellerid}&providerid={providerid}&noredirect=1{$urvenue_ws_config_addurlstaging}{$urvenue_ws_config_addcheckouturl}&staging=live",
	"checkout-checkurl" => "https://staging.{$urvenue_ws_bkgaddsubd}booketing.com/checkout/details/{cartcode}/?sourcecode=$urvenue_ws_sourcecode&sourceloc=$urvenue_ws_sourceloc&manageents={manageentid}&resellerid={resellerid}&providerid={providerid}{$urvenue_ws_config_addurlstaging}{$urvenue_ws_config_addcheckouturl}&staging=live",
);*/

/*Expirations
    3600: 1 hour
*/