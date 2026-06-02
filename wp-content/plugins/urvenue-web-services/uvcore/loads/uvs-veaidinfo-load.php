<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! current_user_can( 'manage_options' ) ) { // Axl UWS-8152
	echo "<div class='uvs-admin-errormsg'>Insufficient permissions.</div>"; // Axl UWS-8152
	exit; // Axl UWS-8152
} // Axl UWS-8152
if ( ! isset( $_REQUEST['uws_nonce'] ) || // Axl UWS-8152
     ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['uws_nonce'] ) ), 'uvsp_veaidinfo' ) ) { // Axl UWS-8152
	echo "<div class='uvs-admin-errormsg'>Invalid security token.</div>"; // Axl UWS-8152
	exit; // Axl UWS-8152
} // Axl UWS-8152

global $urvenue_ws_assetsversion;

if($urvenue_ws_libexits)
	include_once($urvenue_ws_uvs_path . "/system/uvs-admin-init.php");

// $urvenue_ws_adm_ve = isset($urvenue_ws_adm_ve) ? $urvenue_ws_adm_ve : $_REQUEST["uvsve"]; // Axl UWS-7418
// $urvenue_ws_adm_ve = isset($urvenue_ws_adm_ve) ? $urvenue_ws_adm_ve : ( isset( $_REQUEST["uvsve"] ) ? sanitize_text_field( wp_unslash( $_REQUEST["uvsve"] ) ) : '' ); // Axl UWS-7418
$urvenue_ws_adm_ve = isset($urvenue_ws_adm_ve) ? $urvenue_ws_adm_ve : ( isset( $_REQUEST["uvsve"] ) ? sanitize_text_field( wp_unslash( $_REQUEST["uvsve"] ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin load file; admin capability check handles authorization // Axl UWS-7416
// $urvenue_ws_adm_nv = isset($urvenue_ws_adm_nv) ? $urvenue_ws_adm_nv : $_REQUEST["uvsnv"]; // Axl UWS-7418
// $urvenue_ws_adm_nv = isset($urvenue_ws_adm_nv) ? $urvenue_ws_adm_nv : ( isset( $_REQUEST["uvsnv"] ) ? sanitize_text_field( wp_unslash( $_REQUEST["uvsnv"] ) ) : '' ); // Axl UWS-7418
$urvenue_ws_adm_nv = isset($urvenue_ws_adm_nv) ? $urvenue_ws_adm_nv : ( isset( $_REQUEST["uvsnv"] ) ? sanitize_text_field( wp_unslash( $_REQUEST["uvsnv"] ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin load file; admin capability check handles authorization // Axl UWS-7416
$urvenue_ws_adm_venueinfofeedurl = $urvenue_ws_adm_admin_feeds["venueinfo"];
$urvenue_ws_adm_venueinfofeedurl = str_replace("{params}", "ve" . $urvenue_ws_adm_ve, $urvenue_ws_adm_venueinfofeedurl);

// $urvenue_ws_adm_venueinfofeed = uvs_pullfeed($urvenue_ws_adm_venueinfofeedurl);
$urvenue_ws_adm_venueinfofeed = urvenue_ws_adm_pullfeed($urvenue_ws_adm_venueinfofeedurl); // Axl UWS-7416
$urvenue_ws_adm_venueinfofeed = json_decode($urvenue_ws_adm_venueinfofeed, true);

if(is_array($urvenue_ws_adm_venueinfofeed) and is_array($urvenue_ws_adm_venueinfofeed["venues"]) and is_array($urvenue_ws_adm_venueinfofeed["venues"][0]) and ($urvenue_ws_adm_venueinfofeed["venues"][0]["id"] == $urvenue_ws_adm_ve)){
	$urvenue_ws_adm_venueinfoarray = $urvenue_ws_adm_venueinfofeed["venues"][0];
	$urvenue_ws_adm_venuename = $urvenue_ws_adm_venueinfoarray["name"];
	$urvenue_ws_adm_venuealias = $urvenue_ws_adm_venueinfo["venuealias"];
	$urvenue_ws_adm_venueforcealias = $urvenue_ws_adm_venueinfo["venueforcealias"];
	$urvenue_ws_adm_venuehideinevents = $urvenue_ws_adm_venueinfo["venuehideinevents"];
	$urvenue_ws_adm_venueveaid = $urvenue_ws_adm_venueinfoarray["id"];
	$urvenue_ws_adm_venueuvid = $urvenue_ws_adm_venueinfoarray["urvenueid"];
	$urvenue_ws_adm_venueserver = $urvenue_ws_adm_venueinfoarray["urvenueurl"];
	$urvenue_ws_adm_venueclientid = $urvenue_ws_adm_venueinfoarray["urclientid"];
	$urvenue_ws_adm_venuewbcode = $urvenue_ws_adm_venueinfoarray["wbcode"];
	$urvenue_ws_adm_venuelogo = $urvenue_ws_adm_venueinfoarray["logos"]["transpwhitebg"]["raw_url"];
	$urvenue_ws_adm_venueisprimary = ($urvenue_ws_adm_nv == 0) ? 1 : 0;

	if(!$urvenue_ws_adm_venuewbcode)
		$urvenue_ws_adm_venuewbcode = str_replace(array(" ", "-"), array("", ""), $urvenue_ws_adm_venuename);

	$urvenue_ws_adm_venuelogoclass = (!$urvenue_ws_adm_venuelogo) ? "noimg" : "";
	$urvenue_ws_adm_venueforcealias_checked = ($urvenue_ws_adm_venueforcealias) ? " checked":"";
	$urvenue_ws_adm_venuehideinevents_checked = ($urvenue_ws_adm_venuehideinevents) ? " checked":"";

	// $urvenue_ws_adm_venueforminfo = "<input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][wbcode]' value='$urvenue_ws_adm_venuewbcode'>"; // Axl UWS-7416
	// $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][venue-name]' value='$urvenue_ws_adm_venuename'>"; // Axl UWS-7416
	// $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][veaid]' value='$urvenue_ws_adm_venueveaid'>"; // Axl UWS-7416
	// $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][urvenueid]' value='$urvenue_ws_adm_venueuvid'>"; // Axl UWS-7416
	// $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][clientid]' value='$urvenue_ws_adm_venueclientid'>"; // Axl UWS-7416
	// $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][logourl]' value='$urvenue_ws_adm_venuelogo'>"; // Axl UWS-7416
	// $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][uvserver]' value='$urvenue_ws_adm_venueserver'>"; // Axl UWS-7416
	// $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson venueprimary' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][isprimary]' value='$urvenue_ws_adm_venueisprimary'>"; // Axl UWS-7416
	$urvenue_ws_adm_wbcode_esc = esc_attr( $urvenue_ws_adm_venuewbcode ); // Axl UWS-8151
	$urvenue_ws_adm_venueforminfo = "<input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][wbcode]' value='{$urvenue_ws_adm_wbcode_esc}'>"; // Axl UWS-8151
	$urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][venue-name]' value='" . esc_attr( $urvenue_ws_adm_venuename ) . "'>"; // Axl UWS-8151
	$urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][veaid]' value='" . esc_attr( $urvenue_ws_adm_venueveaid ) . "'>"; // Axl UWS-8151
	$urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][urvenueid]' value='" . esc_attr( $urvenue_ws_adm_venueuvid ) . "'>"; // Axl UWS-8151
	$urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][clientid]' value='" . esc_attr( $urvenue_ws_adm_venueclientid ) . "'>"; // Axl UWS-8151
	$urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][logourl]' value='" . esc_url( $urvenue_ws_adm_venuelogo ) . "'>"; // Axl UWS-8151
	$urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][uvserver]' value='" . esc_url( $urvenue_ws_adm_venueserver ) . "'>"; // Axl UWS-8151
	$urvenue_ws_adm_venueforminfo .= "<input class='uvsjson venueprimary' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][isprimary]' value='" . esc_attr( $urvenue_ws_adm_venueisprimary ) . "'>"; // Axl UWS-8151

	$urvenue_ws_adm_venueisprimarylabel = ($urvenue_ws_adm_venueisprimary) ? "Is Primary" : "Make Primary";
	$urvenue_ws_adm_venueisprimaryclass = ($urvenue_ws_adm_venueisprimary) ? "active" : "";

	$urvenue_ws_venuealiasswitchclass = ($urvenue_ws_adm_venueforcealias) ? "uvs-on" : "";
	// $urvenue_ws_venuealiasinput = "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Use Alias as Venue Name:</div><div class='uvsvalue'><div class='uvs-switch-ui $urvenue_ws_venuealiasswitchclass'><button class='uvsjs-trigger-switch' type='button'><span class='uvs-lb-on'>Yes</span><span class='uvs-lb-off'>No</span></button><input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][venueforcealias]' value='$urvenue_ws_adm_venueforcealias' data-value-on='1' data-value-off=''></div></div></div>"; // Axl UWS-7416
	$urvenue_ws_venuealiasinput = "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Use Alias as Venue Name:</div><div class='uvsvalue'><div class='uvs-switch-ui " . esc_attr( $urvenue_ws_venuealiasswitchclass ) . "'><button class='uvsjs-trigger-switch' type='button'><span class='uvs-lb-on'>Yes</span><span class='uvs-lb-off'>No</span></button><input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][venueforcealias]' value='" . esc_attr( $urvenue_ws_adm_venueforcealias ) . "' data-value-on='1' data-value-off=''></div></div></div>"; // Axl UWS-8151

	$urvenue_ws_hideeventsswitchclass = ($urvenue_ws_adm_venuehideinevents) ? "uvs-on" : "";
	// $urvenue_ws_hideeventsinput = "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Use Alias as Venue Name:</div><div class='uvsvalue'><div class='uvs-switch-ui $urvenue_ws_hideeventsswitchclass'><button class='uvsjs-trigger-switch' type='button'><span class='uvs-lb-on'>Yes</span><span class='uvs-lb-off'>No</span></button><input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][venuehideinevents]' value='$urvenue_ws_adm_venuehideinevents' data-value-on='1' data-value-off=''></div></div></div>"; // Axl UWS-7416
	$urvenue_ws_hideeventsinput = "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Use Alias as Venue Name:</div><div class='uvsvalue'><div class='uvs-switch-ui " . esc_attr( $urvenue_ws_hideeventsswitchclass ) . "'><button class='uvsjs-trigger-switch' type='button'><span class='uvs-lb-on'>Yes</span><span class='uvs-lb-off'>No</span></button><input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][venuehideinevents]' value='" . esc_attr( $urvenue_ws_adm_venuehideinevents ) . "' data-value-on='1' data-value-off=''></div></div></div>"; // Axl UWS-8151
	
	// @egt [UWS-7264]
	// $urvenue_ws_adm_pendchanges_script = 'uvs_pendchanges = true;';
	$urvenue_ws_adm_pendchanges_script = 'urvenue_ws_adm_pendchanges = true;'; // Axl UWS-7416

	// wp_register_script('uvs_pendchanges', false, array(), null, true);
	wp_register_script('urvenue_ws_adm_pendchanges', false, array(), $urvenue_ws_assetsversion, true); // Axl UWS-7416
	// wp_enqueue_script('uvs_pendchanges');
	wp_enqueue_script('urvenue_ws_adm_pendchanges'); // Axl UWS-7416
	// wp_add_inline_script('uvs_pendchanges', "(function () { {$urvenue_ws_adm_pendchanges_script} })();");
	wp_add_inline_script('urvenue_ws_adm_pendchanges', "(function () { {$urvenue_ws_adm_pendchanges_script} })();"); // Axl UWS-7416

	// $urvenue_ws_adm_venueinfoinfohtml = "<div class='uvs-admin-venueinf uvs-admin-venueinf-veaid-$urvenue_ws_adm_venueveaid'>$urvenue_ws_adm_venueforminfo<div class='uvs-infolist-item-img $urvenue_ws_adm_venuelogoclass' style='background-image: url($urvenue_ws_adm_venuelogo);'></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Code:</div><div class='uvsvalue'><strong>$urvenue_ws_adm_venuewbcode</strong></div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Name:</div><div class='uvsvalue'>$urvenue_ws_adm_venuename</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Name Alias:</div><div class='uvsvalue'><input type='text' name='venues[$urvenue_ws_adm_venuekey][venuealias]' value='$urvenue_ws_adm_venuealias' class='uvsjson'></div></div>{$urvenue_ws_venuealiasinput}{$urvenue_ws_hideeventsinput}<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>VEA Venue ID:</div><div class='uvsvalue'>$urvenue_ws_adm_venueveaid</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>UrVenue Venue ID:</div><div class='uvsvalue'>$urvenue_ws_adm_venueuvid</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Client ID:</div><div class='uvsvalue'>$urvenue_ws_adm_venueclientid</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>UrVenue Server:</div><div class='uvsvalue'>$urvenue_ws_adm_venueserver</div></div><div class='actions'><a class='uvsjs-triggervenueprimary $urvenue_ws_adm_venueisprimaryclass' href='javascript:;' data-isprimary='$urvenue_ws_adm_venueisprimary'>$urvenue_ws_adm_venueisprimarylabel</a><a class='uvsjs-removevenue' href='javascript:;'>Remove</a></div></div>"; // Axl UWS-7416
	$urvenue_ws_adm_venueinfoinfohtml = "<div class='uvs-admin-venueinf uvs-admin-venueinf-veaid-" . esc_attr( $urvenue_ws_adm_venueveaid ) . "'>{$urvenue_ws_adm_venueforminfo}<div class='uvs-infolist-item-img " . esc_attr( $urvenue_ws_adm_venuelogoclass ) . "' style='background-image: url(" . esc_url( $urvenue_ws_adm_venuelogo ) . ");'></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Code:</div><div class='uvsvalue'><strong>" . esc_html( $urvenue_ws_adm_venuewbcode ) . "</strong></div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Name:</div><div class='uvsvalue'>" . esc_html( $urvenue_ws_adm_venuename ) . "</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Name Alias:</div><div class='uvsvalue'><input type='text' name='venues[" . esc_attr( $urvenue_ws_adm_venuekey ) . "][venuealias]' value='" . esc_attr( $urvenue_ws_adm_venuealias ) . "' class='uvsjson'></div></div>{$urvenue_ws_venuealiasinput}{$urvenue_ws_hideeventsinput}<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>VEA Venue ID:</div><div class='uvsvalue'>" . esc_html( $urvenue_ws_adm_venueveaid ) . "</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>UrVenue Venue ID:</div><div class='uvsvalue'>" . esc_html( $urvenue_ws_adm_venueuvid ) . "</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Client ID:</div><div class='uvsvalue'>" . esc_html( $urvenue_ws_adm_venueclientid ) . "</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>UrVenue Server:</div><div class='uvsvalue'>" . esc_html( $urvenue_ws_adm_venueserver ) . "</div></div><div class='actions'><a class='uvsjs-triggervenueprimary " . esc_attr( $urvenue_ws_adm_venueisprimaryclass ) . "' href='javascript:;' data-isprimary='" . esc_attr( $urvenue_ws_adm_venueisprimary ) . "'>" . esc_html( $urvenue_ws_adm_venueisprimarylabel ) . "</a><a class='uvsjs-removevenue' href='javascript:;'>Remove</a></div></div>"; // Axl UWS-8151
	
	// @Axl
	// echo $urvenue_ws_adm_venueinfoinfohtml;
	// echo wp_kses( $urvenue_ws_adm_venueinfoinfohtml, uvs_allowed_admin_html() );
	echo wp_kses( $urvenue_ws_adm_venueinfoinfohtml, urvenue_ws_adm_allowed_admin_html() ); // Axl UWS-7416
	// @Axl End
}
else {
	// @Axl
	// echo "<div class='uvs-admin-errormsg'>We did not find a venue with this VEA Venue ID: <strong>$urvenue_ws_adm_ve</strong>, check your ID or contact support.</div>";
	echo "<div class='uvs-admin-errormsg'>We did not find a venue with this VEA Venue ID: <strong>" . esc_html( $urvenue_ws_adm_ve ) . "</strong>, check your ID or contact support.</div>";
	// @Axl End
}