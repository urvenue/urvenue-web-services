<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// preg_match("#^\d+(\.\d+)*#", PHP_VERSION, $uvsphpvermatch);
preg_match("#^\d+(\.\d+)*#", PHP_VERSION, $urvenue_ws_phpvermatch); // Axl UWS-7634
// $urvenue_ws_phpversion = $uvsphpvermatch[0];
$urvenue_ws_phpversion = $urvenue_ws_phpvermatch[0]; // Axl UWS-7634

// $uvslibfile = $urvenue_ws_core_lib["system"]["library"];
$urvenue_ws_libfile = $urvenue_ws_core_lib["system"]["library"]; // Axl UWS-7634
// if(is_writable($uvslibfile))
// if(is_writable($urvenue_ws_libfile)) // Axl UWS-7416
global $wp_filesystem;
if ( ! function_exists( 'WP_Filesystem' ) ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
}
if ( empty( $wp_filesystem ) ) {
	WP_Filesystem();
}
if( $wp_filesystem->is_writable( $urvenue_ws_libfile ) ) // Axl UWS-7416
	// $urvenue_ws_libiswrhtml = "<span class='uvsok'>Yes</span>";
	$urvenue_ws_libiswrhtml = "<span class='uvsok'>Yes</span>"; // Axl UWS-7634
else
	// $urvenue_ws_libiswrhtml = "<span class='uvsbad'>No</span>";
	$urvenue_ws_libiswrhtml = "<span class='uvsbad'>No</span>"; // Axl UWS-7634

// if($urvenue_ws_feeds_path and is_writable($urvenue_ws_feeds_path)) // Axl UWS-7416
if($urvenue_ws_feeds_path and $wp_filesystem->is_writable( $urvenue_ws_feeds_path )) // Axl UWS-7416
	// $urvenue_ws_feedsiswrhtml = "<span class='uvsok'>Working Properly</span>";
	$urvenue_ws_feedsiswrhtml = "<span class='uvsok'>Working Properly</span>"; // Axl UWS-7634
else
	// $urvenue_ws_feedsiswrhtml = "<span class='uvsbad'>Not Working</span>";
	$urvenue_ws_feedsiswrhtml = "<span class='uvsbad'>Not Working</span>"; // Axl UWS-7634

// $urvenue_ws_corelayer = (uvs_is_wordpress()) ? "Wordpress" : "Base";
// $urvenue_ws_corelayer = (urvenue_ws_adm_is_wordpress()) ? "Wordpress" : "Base"; // Axl UWS-7416
$urvenue_ws_corelayer = (urvenue_ws_adm_is_wordpress()) ? "Wordpress" : "Base"; // Axl UWS-7634

?>

<div id="uvs-admin-status" class="uvs-admin-opt-section <?php /* Old: echo $urvenue_ws_admin_optstabs_state['status']; */ echo esc_attr( $urvenue_ws_admin_optstabs_state['status'] ); ?>">
	<div class="uvs-admin-opt-title">Status</div>
	<div class="uvs-admin-opt-descr">On the status you can find information about the uvcore status and the compatibility with the server</div>
	<div class="uvs-admin-opt-space"></div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">UWS Version:</div>
		<div class="uvsvalue"><strong><?php /* Old: echo $urvenue_ws_adm_core_version; */ echo esc_html( $urvenue_ws_adm_core_version ); ?></strong></div>
	</div>
	<?php /* Old: if(!uvs_is_wordpress()){ */ ?>
	<?php if(!urvenue_ws_adm_is_wordpress()){  // Axl UWS-7416 ?>
		<div class="uvs-infolist-item uvs-clearfix">
			<div class="uvsname">UvCore URL</div>
			<div class="uvsvalue"><?php /* Old: echo $urvenue_ws_url; */ echo esc_html( $urvenue_ws_url ); ?></div>
		</div>
	<?php } ?>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">UWS Cache Status</div>
		<div class="uvsvalue"><?php /* Old: echo $urvenue_ws_feedsiswrhtml; */ echo wp_kses( $urvenue_ws_feedsiswrhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?></div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">UWS Layer</div>
		<div class="uvsvalue"><?php /* Old: echo $urvenue_ws_corelayer; */ echo esc_html( $urvenue_ws_corelayer ); ?></div>
	</div>
	<?php /* Old: if(!uvs_is_wordpress()){ */ ?>
	<?php if(!urvenue_ws_adm_is_wordpress()){  // Axl UWS-7416 ?>
		<div class="uvs-infolist-item uvs-clearfix">
			<div class="uvsname">Library is Writable</div>
			<div class="uvsvalue"><?php /* Old: echo $urvenue_ws_libiswrhtml; */ echo wp_kses( $urvenue_ws_libiswrhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?></div>
		</div>
	<?php } ?>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Feeds Method</div>
		<div class="uvsvalue">CURL</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Debug Mode</div>
		<div class="uvsvalue">No</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">PHP Version</div>
		<div class="uvsvalue"><span class="uvsok"><?php /* Old: echo $urvenue_ws_phpversion; */ echo esc_html( $urvenue_ws_phpversion ); ?></span></div>
	</div>
</div>