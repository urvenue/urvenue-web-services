<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! current_user_can( 'manage_options' ) ) {
	wp_send_json_error( array( 'message' => 'Insufficient permissions' ), 403 );
}
if ( ! isset( $_POST['uvsp_adminsave_nonce'] ) ||
     ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['uvsp_adminsave_nonce'] ) ), 'uvsp_adminsave_action' ) ) {
	wp_send_json_error( array( 'message' => 'Invalid nonce' ), 403 );
}

unset($_REQUEST["uvaction"]);

if(isset($_POST["system"]) && isset($_POST["system"]["path"])){
	$urvenue_ws_adm_libtmp = wp_unslash( $_POST );
	array_walk_recursive( $urvenue_ws_adm_libtmp, function( &$value ) {
		if ( is_string( $value ) ) {
			$value = sanitize_text_field( $value );
		}
	} );

	/*if(is_array($urvenue_ws_adm_libtmp["flyers"])){
		foreach($urvenue_ws_adm_libtmp["flyers"] as $uvflyerlockey => $uvsflyerloc){
			if(is_array($uvsflyerloc)){
				$urvenue_ws_adm_libtmp["flyers"][$uvflyerlockey] = array_values($uvsflyerloc);
			}
		}
	}*/

	if(isset($urvenue_ws_adm_libtmp["system"]) and is_array($urvenue_ws_adm_libtmp["system"]) and isset($urvenue_ws_adm_libtmp["system"]["microcode"])){
		$urvenue_ws_adm_libtmp["system"]["sourceloc"] = $urvenue_ws_adm_libtmp["system"]["microcode"];

		if(!isset($urvenue_ws_adm_libtmp["system"]["sourcecode"]))
			$urvenue_ws_adm_libtmp["system"]["sourcecode"] = (urvenue_ws_adm_is_wordpress()) ? "wpplugin" : "uwscore";
	}

	$urvenue_ws_adm_lib = wp_json_encode($urvenue_ws_adm_libtmp);
	urvenue_ws_adm_admin_save_lib($urvenue_ws_adm_lib);
}
else
	urvenue_ws_adm_uverror("UVError 01-003: Data info missing.<br>");
