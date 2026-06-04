<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//uvcorepath: $urvenue_ws_uvs_path
// $urvenue_ws_libexits = false;
$urvenue_ws_libexits = false; // Axl UWS-7416
// $urvenue_ws_core_lib = "";
$urvenue_ws_core_lib = ""; // Axl UWS-7416

//Check if is wordpress
if(function_exists('get_option') and function_exists('add_menu_page')){//is wordpress
	// $uvs_path = $urvenue_ws_corepath;
	$urvenue_ws_uvs_path = $urvenue_ws_corepath; // Axl UWS-7416
	// $urvenue_ws_libexits = true;
	$urvenue_ws_libexits = true; // Axl UWS-7416
}
else{
	if(file_exists("uvcore.lib.json")){
		// $uvslibinfojson = file_get_contents("uvcore.lib.json");
		$urvenue_ws_adm_libinfojson = file_get_contents("uvcore.lib.json"); // Axl UWS-7634

		// $uvslib = json_decode($uvslibinfojson, true);
		$urvenue_ws_adm_lib = json_decode($urvenue_ws_adm_libinfojson, true); // Axl UWS-7634

		// if(is_array($uvslib["system"])){
		if(is_array($urvenue_ws_adm_lib["system"])){ // Axl UWS-7634
			// $urvenue_ws_libexits = true;
			$urvenue_ws_libexits = true; // Axl UWS-7416
			// $uvs_path = $uvslib["system"]["path"];
			// $urvenue_ws_uvs_path = $uvslib["system"]["path"]; // Axl UWS-7416
			$urvenue_ws_uvs_path = $urvenue_ws_adm_lib["system"]["path"]; // Axl UWS-7634
		}
	}
}

if(isset($urvenue_ws_uvs_path) and $urvenue_ws_uvs_path){
	include_once($urvenue_ws_uvs_path . "/libs/uv-defaults-lib.php");
	include_once($urvenue_ws_uvs_path . "/system/uvs-admin-functions.php");

	// $urvenue_ws_feeds_path = $uvs_path . "/uvfeeds";
	$urvenue_ws_feeds_path = $urvenue_ws_uvs_path . "/uvfeeds"; // Axl UWS-7416
	// $urvenue_ws_core_lib = urvenue_ws_adm_get_core_library();
	$urvenue_ws_core_lib = urvenue_ws_adm_get_core_library(); // Axl UWS-7416

	// $urvenue_ws_url = ($urvenue_ws_coreurl) ? $urvenue_ws_coreurl : $urvenue_ws_core_lib["system"]["url"];
	$urvenue_ws_url = ($urvenue_ws_coreurl) ? $urvenue_ws_coreurl : $urvenue_ws_core_lib["system"]["url"]; // Axl UWS-7416
	// $urvenue_ws_lib_path = ($urvenue_ws_core_lib and isset($urvenue_ws_core_lib["system"]["library"])) ? $urvenue_ws_core_lib["system"]["library"] : "";
	$urvenue_ws_lib_path = ($urvenue_ws_core_lib and isset($urvenue_ws_core_lib["system"]["library"])) ? $urvenue_ws_core_lib["system"]["library"] : ""; // Axl UWS-7416

	include_once($urvenue_ws_uvs_path . "/libs/uvs-admin-lib.php");
}
// else if(isset($uv_isproxy) and $uv_isproxy)
else if(isset($urvenue_ws_isproxy) and $urvenue_ws_isproxy) // Axl UWS-7634
	echo("UVError 01-002: UV Core Init, uvcore path not found<br>");
