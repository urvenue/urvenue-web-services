<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// $uws_coredir = realpath(__DIR__);
$urvenue_ws_coredir = realpath(__DIR__); // Axl UWS-7416
// $uv_assetsversion = "1.0.52";
$urvenue_ws_assetsversion = "1.0.52"; // Axl UWS-7416

// $uvurlpath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Axl UWS-7416
// $uvurlpath = parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ); // Axl UWS-7418
// $uvurlpath = parse_url( sanitize_text_field( wp_unslash( isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '' ) ), PHP_URL_PATH ); // Axl UWS-7418
$urvenue_ws_uvurlpath = parse_url( sanitize_text_field( wp_unslash( isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '' ) ), PHP_URL_PATH ); // Axl UWS-7416

// if(isset($_REQUEST["uvwpdeleteuvcorelib"]) and $_REQUEST["uvwpdeleteuvcorelib"]) // Axl UWS-7418
if(isset($_REQUEST["uvwpdeleteuvcorelib"]) and sanitize_text_field( wp_unslash( $_REQUEST["uvwpdeleteuvcorelib"] ) )) // Axl UWS-7418
	// delete_option("uvcore_lib");
	delete_option("urvenue_ws_uvcore_lib"); // Axl UWS-7416

//Check if is wordpress
if(function_exists('get_option')){//is wordpress
	// $uws_path = $uws_corepath;
	$urvenue_ws_path = $urvenue_ws_corepath; // Axl UWS-7416
	// $uws_libexits = true;
	$urvenue_ws_libexits = true; // Axl UWS-7416
}
else if(file_exists("$urvenue_ws_coredir/uvcore.lib.json")){
	// $uvlibinfojson = file_get_contents("$uws_coredir/uvcore.lib.json");
	$urvenue_ws_uvlibinfojson = file_get_contents("$urvenue_ws_coredir/uvcore.lib.json"); // Axl UWS-7416
	// $uvlib = json_decode($uvlibinfojson, true);
	$urvenue_ws_uvlib = json_decode($urvenue_ws_uvlibinfojson, true); // Axl UWS-7416

	if(is_array($urvenue_ws_uvlib["system"])){
		// $uws_libexits = true;
		$urvenue_ws_libexits = true; // Axl UWS-7416
		// $uws_path = $uvlib["system"]["path"];
		$urvenue_ws_path = $urvenue_ws_uvlib["system"]["path"]; // Axl UWS-7416
	}
}
if(!isset($urvenue_ws_path) or !$urvenue_ws_path)
	// $uws_path = $uws_corepath;
	$urvenue_ws_path = $urvenue_ws_corepath; // Axl UWS-7416
if(!isset($urvenue_ws_uvs_path) or !$urvenue_ws_uvs_path)
	// $uvs_path = $uws_corepath;
	$urvenue_ws_uvs_path = $urvenue_ws_corepath; // Axl UWS-7416

if($urvenue_ws_path){
    include_once($urvenue_ws_path . "/libs/uv-defaults-lib.php");
	include_once($urvenue_ws_path . "/libs/ui-lib.php");
	include_once($urvenue_ws_path . "/includes/uvcore-hooks.php");
	include_once($urvenue_ws_path . "/includes/uvcore-functions.php");
	include_once($urvenue_ws_path . "/includes/lang-functions.php");
	include_once($urvenue_ws_path . "/includes/events-functions.php");
	include_once($urvenue_ws_path . "/includes/map-functions.php");
	include_once($urvenue_ws_path . "/includes/venues-functions.php");
	include_once($urvenue_ws_path . "/includes/inventory-functions.php");
	include_once($urvenue_ws_path . "/includes/reservations-functions.php");
	include_once($urvenue_ws_path . "/includes/packages-functions.php");

	// $uws_feeds_path = $uvs_path . "/uvfeeds";
	$urvenue_ws_feeds_path = $urvenue_ws_uvs_path . "/uvfeeds"; // Axl UWS-7416

	if(!urvenue_ws_is_wordpress())
		// $uws_feeds_debug = (isset($_REQUEST["uvdbg"]) and sanitize_text_field( wp_unslash( $_REQUEST["uvdbg"] ) ) and (sanitize_text_field( wp_unslash( $_REQUEST["uvdbg"] ) ) == date("j"))) ? 1 : 0; // Axl UWS-7418
		$urvenue_ws_feeds_debug = (isset($_REQUEST["uvdbg"]) and sanitize_text_field( wp_unslash( $_REQUEST["uvdbg"] ) ) and (sanitize_text_field( wp_unslash( $_REQUEST["uvdbg"] ) ) == date("j"))) ? 1 : 0; // Axl UWS-7416
	else
		// $uws_feeds_debug = 0;
		$urvenue_ws_feeds_debug = 0; // Axl UWS-7416

	// $uws_core_lib = urvenue_ws_get_core_library();
	$urvenue_ws_core_lib = urvenue_ws_get_core_library(); // Axl UWS-7416
	// $uws_url = $uws_core_lib["system"]["url"];
	$urvenue_ws_url = $urvenue_ws_core_lib["system"]["url"]; // Axl UWS-7416
	// $uws_lib_path = $uws_core_lib["system"]["library"];
	$urvenue_ws_lib_path = $urvenue_ws_core_lib["system"]["library"]; // Axl UWS-7416
	// $uws_today = urvenue_ws_get_today();
	$urvenue_ws_today = urvenue_ws_get_today(); // Axl UWS-7416

	include_once($urvenue_ws_path . "/libs/uv-feeds-lib.php");
	include_once($urvenue_ws_path . "/libs/uv-proxy-lib.php");
	include_once($urvenue_ws_path . "/includes/uvcore-notifications.php");
	include_once($urvenue_ws_path . "/includes/uvcore-feeds.php");

	if(function_exists('get_option') &&  $urvenue_ws_uvurlpath == '/apis/uvclearcache/') {
		if (FALSE === get_option('urvenue_ws_cacheword') && FALSE === update_option('urvenue_ws_cacheword',FALSE)) add_option( 'urvenue_ws_cacheword', ''); // Axl UWS-7416

		include_once($urvenue_ws_path . "/includes/uvcore-cleancache.php");
	}
}
else if($uv_isproxy)
    echo("UVError 02-001: UV Core Init, uvcore path not found<br> ");