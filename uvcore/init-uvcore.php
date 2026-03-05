<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$uws_coredir = realpath(__DIR__);
$uv_assetsversion = "1.0.52"; 

$uvurlpath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if(isset($_REQUEST["uvwpdeleteuvcorelib"]) and $_REQUEST["uvwpdeleteuvcorelib"])
	// delete_option("uvcore_lib");
	delete_option("urvenue_ws_uvcore_lib"); // Axl UWS-7416

//Check if is wordpress
if(function_exists('get_option')){//is wordpress
	$uws_path = $uws_corepath;
	$uws_libexits = true;
}
else if(file_exists("$uws_coredir/uvcore.lib.json")){
	$uvlibinfojson = file_get_contents("$uws_coredir/uvcore.lib.json");
    $uvlib = json_decode($uvlibinfojson, true);
    
	if(is_array($uvlib["system"])){
		$uws_libexits = true;
		$uws_path = $uvlib["system"]["path"];
	}
}
if(!isset($uws_path) or !$uws_path)
	$uws_path = $uws_corepath;
if(!isset($uvs_path) or !$uvs_path)
	$uvs_path = $uws_corepath;

if($uws_path){
    include_once($uws_path . "/libs/uv-defaults-lib.php");
	include_once($uws_path . "/libs/ui-lib.php");
	include_once($uws_path . "/includes/uvcore-hooks.php");
	include_once($uws_path . "/includes/uvcore-functions.php");
	include_once($uws_path . "/includes/lang-functions.php");
	include_once($uws_path . "/includes/events-functions.php");
	include_once($uws_path . "/includes/map-functions.php");
	include_once($uws_path . "/includes/venues-functions.php");
	include_once($uws_path . "/includes/inventory-functions.php");
	include_once($uws_path . "/includes/reservations-functions.php");
	include_once($uws_path . "/includes/packages-functions.php");

	$uws_feeds_path = $uvs_path . "/uvfeeds";

	// if(!uws_is_wordpress())
	if(!urvenue_ws_is_wordpress()) // Axl UWS-7416
		$uws_feeds_debug = (isset($_REQUEST["uvdbg"]) and $_REQUEST["uvdbg"] and ($_REQUEST["uvdbg"] == date("j"))) ? 1 : 0;
	else
		$uws_feeds_debug = 0;

	// $uws_core_lib = uws_get_core_library();
	$uws_core_lib = urvenue_ws_get_core_library(); // Axl UWS-7416
	$uws_url = $uws_core_lib["system"]["url"];
	$uws_lib_path = $uws_core_lib["system"]["library"];
	// $uws_today = uws_get_today();
	$uws_today = urvenue_ws_get_today(); // Axl UWS-7416

	include_once($uws_path . "/libs/uv-feeds-lib.php");
	include_once($uws_path . "/libs/uv-proxy-lib.php");
	include_once($uws_path . "/includes/uvcore-notifications.php");
	include_once($uws_path . "/includes/uvcore-feeds.php");

	if(function_exists('get_option') &&  $uvurlpath == '/apis/uvclearcache/') {
		// if (FALSE === get_option('cacheword') && FALSE === update_option('cacheword',FALSE)) add_option( 'cacheword', '');
		if (FALSE === get_option('urvenue_ws_cacheword') && FALSE === update_option('urvenue_ws_cacheword',FALSE)) add_option( 'urvenue_ws_cacheword', ''); // Axl UWS-7416

		include_once($uws_path . "/includes/uvcore-cleancache.php");
	}
}
else if($uv_isproxy)
    echo("UVError 02-001: UV Core Init, uvcore path not found<br> ");