<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

preg_match("#^\d+(\.\d+)*#", PHP_VERSION, $uvsphpvermatch);
$uvsphpversion = $uvsphpvermatch[0];

$uvslibfile = $urvenue_ws_core_lib["system"]["library"];
if(is_writable($uvslibfile))
	$uvslibiswrhtml = "<span class='uvsok'>Yes</span>";
else
	$uvslibiswrhtml = "<span class='uvsbad'>No</span>";

if($urvenue_ws_feeds_path and is_writable($urvenue_ws_feeds_path))
	$uvsfeedsiswrhtml = "<span class='uvsok'>Working Properly</span>";
else
	$uvsfeedsiswrhtml = "<span class='uvsbad'>Not Working</span>";

// $uvcorelayer = (uvs_is_wordpress()) ? "Wordpress" : "Base";
$uvcorelayer = (urvenue_ws_adm_is_wordpress()) ? "Wordpress" : "Base"; // Axl UWS-7416

?>

<div id="uvs-admin-status" class="uvs-admin-opt-section <?php /* Old: echo $uvs_admin_optstabs_state['status']; */ echo esc_attr( $uvs_admin_optstabs_state['status'] ); ?>">
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
		<div class="uvsvalue"><?php /* Old: echo $uvsfeedsiswrhtml; */ echo wp_kses( $uvsfeedsiswrhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?></div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">UWS Layer</div>
		<div class="uvsvalue"><?php /* Old: echo $uvcorelayer; */ echo esc_html( $uvcorelayer ); ?></div>
	</div>
	<?php /* Old: if(!uvs_is_wordpress()){ */ ?>
	<?php if(!urvenue_ws_adm_is_wordpress()){  // Axl UWS-7416 ?>
		<div class="uvs-infolist-item uvs-clearfix">
			<div class="uvsname">Library is Writable</div>
			<div class="uvsvalue"><?php /* Old: echo $uvslibiswrhtml; */ echo wp_kses( $uvslibiswrhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?></div>
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
		<div class="uvsvalue"><span class="uvsok"><?php /* Old: echo $uvsphpversion; */ echo esc_html( $uvsphpversion ); ?></span></div>
	</div>
</div>