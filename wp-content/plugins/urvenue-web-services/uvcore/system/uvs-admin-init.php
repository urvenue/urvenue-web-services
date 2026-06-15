<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$urvenue_ws_libexits = false;
$urvenue_ws_core_lib = "";

//Check if is wordpress
if(function_exists('get_option') and function_exists('add_menu_page')){//is wordpress
	$urvenue_ws_uvs_path = $urvenue_ws_corepath;
	$urvenue_ws_libexits = true;
}
else{
	if(file_exists("uvcore.lib.json")){
		global $wp_filesystem;
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		if ( empty( $wp_filesystem ) ) {
			WP_Filesystem();
		}
		$urvenue_ws_adm_libinfojson = $wp_filesystem->get_contents("uvcore.lib.json");

		$urvenue_ws_adm_lib = json_decode($urvenue_ws_adm_libinfojson, true);

		if(is_array($urvenue_ws_adm_lib["system"])){
			$urvenue_ws_libexits = true;
			$urvenue_ws_uvs_path = $urvenue_ws_adm_lib["system"]["path"];
		}
	}
}

if(isset($urvenue_ws_uvs_path) and $urvenue_ws_uvs_path){
	include_once($urvenue_ws_uvs_path . "/libs/uv-defaults-lib.php");
	include_once($urvenue_ws_uvs_path . "/system/uvs-admin-functions.php");

	$urvenue_ws_feeds_path = $urvenue_ws_uvs_path . "/uvfeeds";
	$urvenue_ws_core_lib = urvenue_ws_adm_get_core_library();

	$urvenue_ws_url = ($urvenue_ws_coreurl) ? $urvenue_ws_coreurl : $urvenue_ws_core_lib["system"]["url"];
	$urvenue_ws_lib_path = ($urvenue_ws_core_lib and isset($urvenue_ws_core_lib["system"]["library"])) ? $urvenue_ws_core_lib["system"]["library"] : "";

	include_once($urvenue_ws_uvs_path . "/libs/uvs-admin-lib.php");
}
else if(isset($urvenue_ws_isproxy) and $urvenue_ws_isproxy)
	echo("UVError 01-002: UV Core Init, uvcore path not found<br>");
