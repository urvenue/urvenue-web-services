<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_core_lib;

//$uws_config_envicode = "apiuat"; //overwrite environment configuration, be careful

$uws_envicode = (isset($uws_config_envicode)) ? $uws_config_envicode : "api";
$uws_envicode = (isset($uws_core_lib["system"]["use-staging"]) and $uws_core_lib["system"]["use-staging"]) ? "apistaging" : $uws_envicode;

$uws_envicode = (isset($_REQUEST["uvstaging"]) and $_REQUEST["uvstaging"]) ? "apistaging" : $uws_envicode;
$uws_envicode = (isset($_REQUEST["uvenvicode"]) and $_REQUEST["uvenvicode"]) ? $_REQUEST["uvenvicode"] : $uws_envicode;

//API Vars
$uws_apikey = (isset($uws_apikey) and $uws_apikey) ? $uws_apikey : $uws_core_lib["system"]["apikey"];
$uws_sourcecode = (isset($uws_sourcecode) and $uws_sourcecode) ? $uws_sourcecode : $uws_core_lib["system"]["sourcecode"];
$uws_sourceloc = (isset($uws_sourceloc) and $uws_sourceloc) ? $uws_sourceloc : $uws_core_lib["system"]["sourceloc"];

$uws_bkgaddsubd = ($uws_envicode == "apistaging") ? "staging." : "";
$uws_bkgaddsubd = ($uws_envicode == "apiuat") ? "uat." : $uws_bkgaddsubd;
$uws_bkgaddsubd = (isset($uws_addbookingsubdomain) and $uws_addbookingsubdomain) ? $uws_addbookingsubdomain : $uws_bkgaddsubd;

// Remove double booketing
$uws_bkgaddsubd = ($uws_bkgaddsubd === "booketing.") ? "" : $uws_bkgaddsubd;

$uws_config_addurlstaging = (isset($uws_config_addurlstaging) and $uws_config_addurlstaging) ? $uws_config_addurlstaging : "";
$uws_config_addcheckouturl = (isset($uws_config_addcheckouturl) and $uws_config_addcheckouturl) ? $uws_config_addcheckouturl : "";

$uws_menuapikey = ($uws_envicode == "apistaging") ? "VEFOHYHKCTCP" : "SREOCZRCGKNC";
$uws_menuapikey = (isset($uws_config_menuapikey) and $uws_config_menuapikey) 
	? $uws_config_menuapikey 
	: $uws_menuapikey;
$uws_menuapikey = (isset($uws_core_lib["events"]["addon-bottles"]["menuapikey"]) and $uws_core_lib["events"]["addon-bottles"]["menuapikey"]) 
	? $uws_core_lib["events"]["addon-bottles"]["menuapikey"] 
	: $uws_menuapikey;

$uws_feeds_lib = array(
    "urquery" => array(//Deprecated
		"url" => "https://$uws_envicode.urvenue.me/v1/urassistant/urquery/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&{params}",
		"expiration" => 3600,
	),
	"inventory" => array(//Should Use Globally From 13/06/2024 - Short Cache Time, show inventory purposes
		"url" => "https://$uws_envicode.urvenue.me/v1/gxn/inventory/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&{params}",
		"expiration" => 60
	),
	"inventory-eventsonly" => array(//Should Use Globally From 13/06/2024 - Filter Only Events
		"url" => "https://$uws_envicode.urvenue.me/v1/gxn/inventory/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&filters=data:schedule&{params}",
		"expiration" => 3600
	),
	"inventorylist" => array(//Deprecated
		"url" => "https://$uws_envicode.urvenue.me/v1/gxn/inventorylist/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&{params}",
		"expiration" => 60
	),
	"inventoryitem" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/gxn/inventoryitem/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&{params}",
		"expiration" => 60
	),
	"inventorymap" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/gxn/inventorymap/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&filter=all&{params}",
		"expiration" => 60
	),
	"mapinventory" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/gxn/mapinventory/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&filter=all&{params}",
		"expiration" => 60
	),
	"mapinventorykeep" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/gxn/mapinventory/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&filter=all&{params}",
		"expiration" => 3600
	),
	"packagesinventory" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/gxn/inventory/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&{params}",
		"expiration" => 86400
	),
	"packagesiteminfo" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/gxn/inventoryitem/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&{params}",
		"expiration" => 86400
	),
	"inventorylist-events" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/gxn/inventorylist/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&filters=data:schedule&{params}",
		"expiration" => 3600,
	),
	"inventorylist-venues" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/gxn/inventorylist/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&{params}",
		"expiration" => 86400,
	),
	"inventorylist-events-stocks" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/gxn/inventorylist/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&filters=data:events&{params}",
		"expiration" => 3600,
	),
	"inventorylist-events-marketplace" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/gxn/inventorylist/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&filters=marketplace|data:schedule&{params}",
		"expiration" => 3600,
	),
	"gxnavailability" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/gxn/availability/json/?apikey=$uws_apikey&channel=public_prepay&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&{params}",
		"expiration" => 3600,
	),
	"venueday" => array( //Will be deprecated
		"url" => "https://$uws_envicode.urvenue.me/v1/gxn/venueday/json/?providerid={providerid}&resellerid={resellerid}&channel=public_prepay&apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&{params}",
		"expiration" => 60,
	),
	"cart-get" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/urcheckout/cartitems/json/get?apikey=$uws_apikey&resellerid={resellerid}&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&channel=public_prepay&network=hosted&manageentid={manageentid}&{params}",
		"expiration" => 10,
	),
	"cart-create" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/urcheckout/cartitems/json/post?apikey=$uws_apikey&resellerid={resellerid}&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&channel=public_prepay&network=hosted&manageentid={manageentid}&{params}",
		"expiration" => -1,
	),
	"cart-update" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/urcheckout/cartitems/json/put?apikey=$uws_apikey&resellerid={resellerid}&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&channel=public_prepay&network=hosted&manageentid={manageentid}&{params}",
		"expiration" => -1,
	),
	"cart-delete" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/urcheckout/cartitems/json/delete?apikey=$uws_apikey&resellerid={resellerid}&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&channel=public_prepay&network=hosted&manageentid={manageentid}&{params}",
		"expiration" => -1,
	),
	"inventory-inquiry" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/booking/inquiry/json?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc",
		"expiration" => -1,
	),
	"inquiry-leadtypes" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/urinventory/leadstypes/json/?apikey=$uws_apikey&manageentid={manageentid}&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&{params}&systemid=15&accountid=82&appaccountid=82",
		"expiration" => 3600,
	),
	"inquiry-send" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/booking/inquiry/json?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc",
		"expiration" => -1,
	),
	"ot-itemtimes" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/urcheckout/otavailability/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&{params}",
		"expiration" => -1,
		"varpass" => "url"
	),
	"bk4-itemtimes" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/urcheckout/b4tavailability/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&{params}",
		"expiration" => -1,
		"varpass" => "url"
	),
	"cartv2-create" => array(
		"url" => "https://$uws_envicode.urvenue.me/v2/transact/cartitems/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc",
		"expiration" => -1,
	),
	"cartv2-add" => array(
		"url" => "https://$uws_envicode.urvenue.me/v2/transact/cartitems/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc",
		"expiration" => -1,
	),
	"cartv2-get" => array(
		"url" => "https://$uws_envicode.urvenue.me/v2/transact/cartitems/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&{params}",
		"expiration" => -1,
	),
	"cartv2-delete" => array(
		"url" => "https://$uws_envicode.urvenue.me/v2/transact/cartitems/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc",
		"expiration" => -1,
	),
	"marketevents" => array(
		"url" => "https://apiuat.urvenue.me/v1/vea/marketevents/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&{params}",
		"expiration" => 3600,
	),
	"eventvenues" => array(
		"url" => "https://apiuat.urvenue.me/v1/gxn/eventvenues/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&{params}",
		"expiration" => 3600,
	),
	"marketeventvenues" => array(
		"url" => "https://apiuat.urvenue.me/v1/gxn/marketeventvenues/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&{params}",
		"expiration" => 3600,
	),
	"digital-menu" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/urinventory/menu/json/?apikey=$uws_menuapikey&systemid=18&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&{params}",
		"expiration" => 3600,
	),
	"ecozonedetails" => array(
		"url" => "https://$uws_envicode.urvenue.me/v1/global/ecozonedetails/json/?apikey=$uws_apikey&sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&{params}",
		"expiration" => 3600,
	),
);

$uws_feedscleartime = 86400;//172800 - 2 Days

// $uws_endcurlangparam = (uws_get_cur_lang() != "en") ? "&lang=" . uws_get_cur_lang() : "";
$uws_endcurlangparam = (urvenue_ws_get_cur_lang() != "en") ? "&lang=" . urvenue_ws_get_cur_lang() : ""; // Axl UWS-7416
$uvaddcheckoutparams = $uvendcheckoutparams = $uv_addurlstaging ="";
// if(uws_get_cur_lang() != "en"){
if(urvenue_ws_get_cur_lang() != "en"){ // Axl UWS-7416
	// $uvaddcheckoutparams = "?lang=" . uws_get_cur_lang();
	$uvaddcheckoutparams = "?lang=" . urvenue_ws_get_cur_lang(); // Axl UWS-7416
	// $uvendcheckoutparams = "&lang=" . uws_get_cur_lang();
	$uvendcheckoutparams = "&lang=" . urvenue_ws_get_cur_lang(); // Axl UWS-7416
}
else
	$uv_addurlstaging = str_replace("&", "?", $uv_addurlstaging);

//$uws_bkgaddsubd = "staging."; //overwrite booking checkout subdomain, be careful
$uws_bkgaddhost = $uws_config_chkhost ?? "booketing";
$uws_actionlinks_lib = array(
	"microsite" => array(
		// "checkout-carturl" => "https://{$uws_bkgaddsubd}$uws_bkgaddhost.com/checkout/cart/{cartcode}/?sourcecode=$uws_sourcecode&sourceloc={sourceloc}&manageents={manageentid}&resellerid={resellerid}&providerid={providerid}&noredirect=1{$uws_config_addurlstaging}{$uws_config_addcheckouturl}&lang=" . uws_get_cur_lang(),
		"checkout-carturl" => "https://{$uws_bkgaddsubd}$uws_bkgaddhost.com/checkout/cart/{cartcode}/?sourcecode=$uws_sourcecode&sourceloc={sourceloc}&manageents={manageentid}&resellerid={resellerid}&providerid={providerid}&noredirect=1{$uws_config_addurlstaging}{$uws_config_addcheckouturl}&lang=" . urvenue_ws_get_cur_lang(), // Axl UWS-7416
		// "checkout-checkurl" => "https://{$uws_bkgaddsubd}$uws_bkgaddhost.com/checkout/details/{cartcode}/?sourcecode=$uws_sourcecode&sourceloc={sourceloc}&manageents={manageentid}&resellerid={resellerid}&providerid={providerid}{$uws_config_addurlstaging}{$uws_config_addcheckouturl}&lang=" . uws_get_cur_lang(),
		"checkout-checkurl" => "https://{$uws_bkgaddsubd}$uws_bkgaddhost.com/checkout/details/{cartcode}/?sourcecode=$uws_sourcecode&sourceloc={sourceloc}&manageents={manageentid}&resellerid={resellerid}&providerid={providerid}{$uws_config_addurlstaging}{$uws_config_addcheckouturl}&lang=" . urvenue_ws_get_cur_lang(), // Axl UWS-7416
	),
	"bk" => array(
		"checkout-carturl" => "#uws-view-cart",
		"checkout-checkurl" => "https://{$uws_bkgaddsubd}booketing.com/bk/{sourceloc}/checkout/{manageentid}/{cartcode}/info{$uvaddcheckoutparams}{$uv_addurlstaging}",
		"checkout-payment" => "https://{$uws_bkgaddsubd}booketing.com/bk/{sourceloc}/checkout/{manageentid}/{cartcode}/billing{$uvaddcheckoutparams}{$uv_addurlstaging}",
		"checkout-success" => "https://{$uws_bkgaddsubd}booketing.com/bk/{sourceloc}/checkout/{manageentid}/{cartcode}/success{$uvaddcheckoutparams}{$uv_addurlstaging}",
	),
	"uvcheckout" => array(
		"checkout-carturl" => "https://{$uws_bkgaddsubd}booketing.com/uvcheckout/cart/?cartcode={cartcode}&sourcecode={sourcecode}&sourceloc={sourceloc}{$uvendcheckoutparams}{$uv_addurlstaging}{$uws_config_addcheckouturl}",
		"checkout-checkurl" => "https://{$uws_bkgaddsubd}booketing.com/uvcheckout/checkout/?cartcode={cartcode}&sourcecode={sourcecode}&sourceloc={sourceloc}{$uvendcheckoutparams}{$uv_addurlstaging}{$uws_config_addcheckouturl}",
		"checkout-payment" => "https://{$uws_bkgaddsubd}booketing.com/uvcheckout/payment/?cartcode={cartcode}&sourcecode={sourcecode}&sourceloc={sourceloc}{$uvendcheckoutparams}{$uv_addurlstaging}{$uws_config_addcheckouturl}",
		"checkout-success" => "https://{$uws_bkgaddsubd}booketing.com/uvcheckout/success/?cartcode={cartcode}&sourcecode={sourcecode}&sourceloc={sourceloc}{$uvendcheckoutparams}{$uv_addurlstaging}{$uws_config_addcheckouturl}",
	),
);

//Send checkout to our staging=live
/*$uws_actionlinks_lib = array(
	"checkout-carturl" => "https://staging.{$uws_bkgaddsubd}booketing.com/checkout/cart/{cartcode}/?sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&manageents={manageentid}&resellerid={resellerid}&providerid={providerid}&noredirect=1{$uws_config_addurlstaging}{$uws_config_addcheckouturl}&staging=live",
	"checkout-checkurl" => "https://staging.{$uws_bkgaddsubd}booketing.com/checkout/details/{cartcode}/?sourcecode=$uws_sourcecode&sourceloc=$uws_sourceloc&manageents={manageentid}&resellerid={resellerid}&providerid={providerid}{$uws_config_addurlstaging}{$uws_config_addcheckouturl}&staging=live",
);*/

/*Expirations
    3600: 1 hour
*/