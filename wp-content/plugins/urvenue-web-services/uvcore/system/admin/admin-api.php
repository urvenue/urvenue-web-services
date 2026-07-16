<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$urvenue_ws_apisourcecode = urvenue_ws_adm_get_adminfieldhtml("system->sourcecode");
$urvenue_ws_apisourceloc = urvenue_ws_adm_get_adminfieldhtml("system->sourceloc");
$urvenue_ws_apiapikey = urvenue_ws_adm_get_adminfieldhtml("system->apikey");
$urvenue_ws_apimicrocode = urvenue_ws_adm_get_adminfieldhtml("system->microcode");
$urvenue_ws_adminusestaging = urvenue_ws_adm_get_adminfieldhtml("system->use-staging");
$urvenue_ws_adminshowcredits = urvenue_ws_adm_get_adminfieldhtml("system->show-credits");
?>
<div id="uvs-admin-api" class="uvs-admin-opt-section <?php echo esc_attr( $urvenue_ws_admin_optstabs_state['api'] ); ?>">
    <div class="uvs-admin-opt-title">API Info</div>
    <div class="uvs-admin-opt-space"></div>

    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">API Key</div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_apiapikey, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Micro Code</div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_apimicrocode, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Source Code</div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_apisourcecode, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Source Loc</div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_apisourceloc, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
    </div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Staging <small>Select this options to use staging API endpoints.</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_adminusestaging, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
    </div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Show Attribution Logos <small>Enable to display "Powered By" credit logos on the frontend (UrVenue, OpenTable, Book4Time). Disabled by default.</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_adminshowcredits, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
    </div>
</div>