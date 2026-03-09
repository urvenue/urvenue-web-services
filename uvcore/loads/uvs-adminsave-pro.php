<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

unset($_REQUEST["uvaction"]);

if($_REQUEST["system"] and isset($_REQUEST["system"]["path"])){
	$uvslibtmp = $_REQUEST;
	
	/*if(is_array($uvslibtmp["flyers"])){
		foreach($uvslibtmp["flyers"] as $uvflyerlockey => $uvsflyerloc){
			if(is_array($uvsflyerloc)){
				$uvslibtmp["flyers"][$uvflyerlockey] = array_values($uvsflyerloc);
			}
		}
	}*/
	
	if(isset($uvslibtmp["system"]) and is_array($uvslibtmp["system"]) and isset($uvslibtmp["system"]["microcode"])){
		$uvslibtmp["system"]["sourceloc"] = $uvslibtmp["system"]["microcode"];

		if(!isset($uvslibtmp["system"]["sourcecode"]))
			// $uvslibtmp["system"]["sourcecode"] = (uvs_is_wordpress()) ? "wpplugin" : "uwscore";
			$uvslibtmp["system"]["sourcecode"] = (urvenue_ws_adm_is_wordpress()) ? "wpplugin" : "uwscore"; // Axl UWS-7416
	}

	// @Axl
	// $uvslib = json_encode($uvslibtmp);
	$uvslib = wp_json_encode($uvslibtmp);
	// @Axl End
	// uvs_admin_save_lib($uvslib);
	urvenue_ws_adm_admin_save_lib($uvslib); // Axl UWS-7416
}
else
	// uvs_uverror("UVError 01-003: Data info missing.<br>");
	urvenue_ws_adm_uverror("UVError 01-003: Data info missing.<br>"); // Axl UWS-7416
