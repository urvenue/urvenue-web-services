<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// $urvenue_ws_apisourcecode = uvs_get_adminfieldhtml("system->sourcecode");
// $urvenue_ws_apisourcecode = urvenue_ws_adm_get_adminfieldhtml("system->sourcecode"); // Axl UWS-7416
$urvenue_ws_apisourcecode = urvenue_ws_adm_get_adminfieldhtml("system->sourcecode"); // Axl UWS-7634
// $urvenue_ws_apisourceloc = uvs_get_adminfieldhtml("system->sourceloc");
// $urvenue_ws_apisourceloc = urvenue_ws_adm_get_adminfieldhtml("system->sourceloc"); // Axl UWS-7416
$urvenue_ws_apisourceloc = urvenue_ws_adm_get_adminfieldhtml("system->sourceloc"); // Axl UWS-7634
// $urvenue_ws_apiapikey = uvs_get_adminfieldhtml("system->apikey");
// $urvenue_ws_apiapikey = urvenue_ws_adm_get_adminfieldhtml("system->apikey"); // Axl UWS-7416
$urvenue_ws_apiapikey = urvenue_ws_adm_get_adminfieldhtml("system->apikey"); // Axl UWS-7634
// $urvenue_ws_apimicrocode = uvs_get_adminfieldhtml("system->microcode");
// $urvenue_ws_apimicrocode = urvenue_ws_adm_get_adminfieldhtml("system->microcode"); // Axl UWS-7416
$urvenue_ws_apimicrocode = urvenue_ws_adm_get_adminfieldhtml("system->microcode"); // Axl UWS-7634
// $urvenue_ws_adminusestaging = uvs_get_adminfieldhtml("system->use-staging");
// $urvenue_ws_adminusestaging = urvenue_ws_adm_get_adminfieldhtml("system->use-staging"); // Axl UWS-7416
$urvenue_ws_adminusestaging = urvenue_ws_adm_get_adminfieldhtml("system->use-staging"); // Axl UWS-7634
?>
<div id="uvs-admin-api" class="uvs-admin-opt-section <?php /* Old: echo $urvenue_ws_admin_optstabs_state['api']; */ echo esc_attr( $urvenue_ws_admin_optstabs_state['api'] ); ?>">
    <div class="uvs-admin-opt-title">API Info</div>
    <div class="uvs-admin-opt-space"></div>

    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">API Key</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_apiapikey; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_apiapikey, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_apiapikey, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Micro Code</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_apimicrocode; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_apimicrocode, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_apimicrocode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Source Code</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_apisourcecode; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_apisourcecode, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_apisourcecode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Source Loc</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_apisourceloc; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_apisourceloc, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_apisourceloc, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Staging <small>Select this options to use staging API endpoints.</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_adminusestaging; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_adminusestaging, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_adminusestaging, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
</div>