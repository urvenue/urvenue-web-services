<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// preg_match("#^\d+(\.\d+)*#", PHP_VERSION, $uvsphpvermatch);
preg_match("#^\d+(\.\d+)*#", PHP_VERSION, $urvenue_ws_phpvermatch);
$urvenue_ws_phpversion = $urvenue_ws_phpvermatch[0];

$urvenue_ws_libfile = $urvenue_ws_core_lib["system"]["library"];
global $wp_filesystem;
if ( ! function_exists( 'WP_Filesystem' ) ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
}
if ( empty( $wp_filesystem ) ) {
	WP_Filesystem();
}
if( $wp_filesystem->is_writable( $urvenue_ws_libfile ) )
	$urvenue_ws_libiswrhtml = "<span class='uvsok'>Yes</span>";
else
	$urvenue_ws_libiswrhtml = "<span class='uvsbad'>No</span>";

if($urvenue_ws_feeds_path and $wp_filesystem->is_writable( $urvenue_ws_feeds_path ))
	$urvenue_ws_feedsiswrhtml = "<span class='uvsok'>Working Properly</span>";

	$urvenue_ws_feedsiswrhtml = "<span class='uvsbad'>Not Working</span>";

$urvenue_ws_corelayer = (urvenue_ws_adm_is_wordpress()) ? "Wordpress" : "Base";

$urvenue_ws_debugmode = urvenue_ws_adm_get_fieldvalue_by_stringroute("system->debug");
$urvenue_ws_debugmode_on = ( $urvenue_ws_debugmode && $urvenue_ws_debugmode !== "0" ) ? true : false;
$urvenue_ws_debugmode_forced = ( defined('URVENUE_WS_DEBUG') && URVENUE_WS_DEBUG ) ? true : false;

?>

<div id="uvs-admin-status" class="uvs-admin-opt-section <?php echo esc_attr( $urvenue_ws_admin_optstabs_state['status'] ); ?>">
	<div class="uvs-admin-opt-title">Status</div>
	<div class="uvs-admin-opt-descr">On the status you can find information about the uvcore status and the compatibility with the server</div>
	<div class="uvs-admin-opt-space"></div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">UWS Version:</div>
		<div class="uvsvalue"><strong><?php echo esc_html( $urvenue_ws_adm_core_version ); ?></strong></div>
	</div>
	<?php if(!urvenue_ws_adm_is_wordpress()){ ?>
		<div class="uvs-infolist-item uvs-clearfix">
			<div class="uvsname">UvCore URL</div>
			<div class="uvsvalue"><?php echo esc_html( $urvenue_ws_url ); ?></div>
		</div>
	<?php } ?>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">UWS Cache Status</div>
		<div class="uvsvalue"><?php echo wp_kses( $urvenue_ws_feedsiswrhtml, urvenue_ws_adm_allowed_admin_html() ); ?></div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">UWS Layer</div>
		<div class="uvsvalue"><?php echo esc_html( $urvenue_ws_corelayer ); ?></div>
	</div>
	<?php if(!urvenue_ws_adm_is_wordpress()){ ?>
		<div class="uvs-infolist-item uvs-clearfix">
			<div class="uvsname">Library is Writable</div>
			<div class="uvsvalue"><?php echo wp_kses( $urvenue_ws_libiswrhtml, urvenue_ws_adm_allowed_admin_html() ); ?></div>
		</div>
	<?php } ?>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Feeds Method</div>
		<div class="uvsvalue">CURL</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Debug Mode
			<small>Logs UWS feed activity to the WordPress debug log. Keep it off in production.</small>
		</div>
		<div class="uvsvalue">
			<?php if ( $urvenue_ws_debugmode_forced ): ?>
				<span class="uvsok">Forced on via URVENUE_WS_DEBUG constant</span>
			<?php else: ?>
				<div class="uvs-switch-ui <?php echo $urvenue_ws_debugmode_on ? 'uvs-on' : ''; ?>">
					<button class="uvsjs-trigger-switch" type="button"><span class="uvs-lb-on">Yes</span><span class="uvs-lb-off">No</span></button>
					<input class="uvsjson" type="hidden" name="system[debug]" value="<?php echo esc_attr( $urvenue_ws_debugmode_on ? '1' : '0' ); ?>" data-value-on="1" data-value-off="0">
				</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">PHP Version</div>
		<div class="uvsvalue"><span class="uvsok"><?php echo esc_html( $urvenue_ws_phpversion ); ?></span></div>
	</div>
</div>