<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//uvcorepath: $uvs_path
$uvs_libexits = false;
$uvs_core_lib = "";

//Check if is wordpress
if(function_exists('get_option') and function_exists('add_menu_page')){//is wordpress
	$uvs_path = $uws_corepath;
	$uvs_libexits = true;
}
else{
	if(file_exists("uvcore.lib.json")){
		$uvslibinfojson = file_get_contents("uvcore.lib.json");
		
		$uvslib = json_decode($uvslibinfojson, true);
		
		if(is_array($uvslib["system"])){
			$uvs_libexits = true;
			$uvs_path = $uvslib["system"]["path"];
		}
	}
}

if(isset($uvs_path) and $uvs_path){
	include_once($uvs_path . "/libs/uv-defaults-lib.php");
	include_once($uvs_path . "/system/uvs-admin-functions.php");

	$uvs_feeds_path = $uvs_path . "/uvfeeds";
	$uvs_core_lib = uvs_get_core_library();

	$uvs_url = ($uws_coreurl) ? $uws_coreurl : $uvs_core_lib["system"]["url"];
	$uvs_lib_path = ($uvs_core_lib and isset($uvs_core_lib["system"]["library"])) ? $uvs_core_lib["system"]["library"] : "";

	include_once($uvs_path . "/libs/uvs-admin-lib.php");
}
else if(isset($uv_isproxy) and $uv_isproxy)
	echo("UVError 01-002: UV Core Init, uvcore path not found<br>");
