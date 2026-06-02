<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! current_user_can( 'manage_options' ) ) { // Axl UWS-8152
	wp_send_json_error( array( 'message' => 'Insufficient permissions' ), 403 ); // Axl UWS-8152
} // Axl UWS-8152
if ( ! isset( $_POST['uvsp_adminsave_nonce'] ) || // Axl UWS-8152
     ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['uvsp_adminsave_nonce'] ) ), 'uvsp_adminsave_action' ) ) { // Axl UWS-8152
	wp_send_json_error( array( 'message' => 'Invalid nonce' ), 403 ); // Axl UWS-8152
} // Axl UWS-8152

unset($_REQUEST["uvaction"]);

// if($_REQUEST["system"] and isset($_REQUEST["system"]["path"])){ // Axl UWS-7418
// if(isset($_REQUEST["system"]) && $_REQUEST["system"] and isset($_REQUEST["system"]["path"])){ // Axl UWS-7418
// if(isset($_REQUEST["system"]) && isset($_REQUEST["system"]["path"])){ // Axl UWS-7418
// if(isset($_REQUEST["system"]) && isset($_REQUEST["system"]["path"])){ // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin save handler; admin capability check handles authorization // Axl UWS-7416
if(isset($_POST["system"]) && isset($_POST["system"]["path"])){ // Axl UWS-8150
	// $urvenue_ws_adm_libtmp = $_REQUEST;
	// $urvenue_ws_adm_libtmp = $_REQUEST; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin save handler; admin capability check handles authorization // Axl UWS-7416
	// $urvenue_ws_adm_libtmp = $_POST; // Axl UWS-8152
	$urvenue_ws_adm_libtmp = wp_unslash( $_POST ); // Axl UWS-8150
	array_walk_recursive( $urvenue_ws_adm_libtmp, function( &$value ) { // Axl UWS-8150
		if ( is_string( $value ) ) { // Axl UWS-8150
			$value = sanitize_text_field( $value ); // Axl UWS-8150
		} // Axl UWS-8150
	} ); // Axl UWS-8150
	
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
			// $urvenue_ws_adm_libtmp["system"]["sourcecode"] = (uvs_is_wordpress()) ? "wpplugin" : "uwscore";
			$urvenue_ws_adm_libtmp["system"]["sourcecode"] = (urvenue_ws_adm_is_wordpress()) ? "wpplugin" : "uwscore"; // Axl UWS-7416
	}

	// @Axl
	// $urvenue_ws_adm_lib = json_encode($urvenue_ws_adm_libtmp);
	$urvenue_ws_adm_lib = wp_json_encode($urvenue_ws_adm_libtmp);
	// @Axl End
	// uvs_admin_save_lib($urvenue_ws_adm_lib);
	urvenue_ws_adm_admin_save_lib($urvenue_ws_adm_lib); // Axl UWS-7416
}
else
	// uvs_uverror("UVError 01-003: Data info missing.<br>");
	urvenue_ws_adm_uverror("UVError 01-003: Data info missing.<br>"); // Axl UWS-7416
