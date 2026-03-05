<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// $uvsapisourcecode = uvs_get_adminfieldhtml("system->sourcecode");
$uvsapisourcecode = urvenue_ws_adm_get_adminfieldhtml("system->sourcecode"); // Axl UWS-7416
// $uvsapisourceloc = uvs_get_adminfieldhtml("system->sourceloc");
$uvsapisourceloc = urvenue_ws_adm_get_adminfieldhtml("system->sourceloc"); // Axl UWS-7416
// $uvsapiapikey = uvs_get_adminfieldhtml("system->apikey");
$uvsapiapikey = urvenue_ws_adm_get_adminfieldhtml("system->apikey"); // Axl UWS-7416
// $uvsapimicrocode = uvs_get_adminfieldhtml("system->microcode");
$uvsapimicrocode = urvenue_ws_adm_get_adminfieldhtml("system->microcode"); // Axl UWS-7416
// $uvsadminusestaging = uvs_get_adminfieldhtml("system->use-staging");
$uvsadminusestaging = urvenue_ws_adm_get_adminfieldhtml("system->use-staging"); // Axl UWS-7416
?>
<div id="uvs-admin-api" class="uvs-admin-opt-section <?php /* Old: echo $uvs_admin_optstabs_state['api']; */ echo esc_attr( $uvs_admin_optstabs_state['api'] ); ?>">
    <div class="uvs-admin-opt-title">API Info</div>
    <div class="uvs-admin-opt-space"></div>

    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">API Key</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsapiapikey; */ ?>
			<?php /* Old: echo wp_kses( $uvsapiapikey, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsapiapikey, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Micro Code</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsapimicrocode; */ ?>
			<?php /* Old: echo wp_kses( $uvsapimicrocode, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsapimicrocode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Source Code</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsapisourcecode; */ ?>
			<?php /* Old: echo wp_kses( $uvsapisourcecode, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsapisourcecode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Source Loc</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsapisourceloc; */ ?>
			<?php /* Old: echo wp_kses( $uvsapisourceloc, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsapisourceloc, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Staging <small>Select this options to use staging API endpoints.</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsadminusestaging; */ ?>
			<?php /* Old: echo wp_kses( $uvsadminusestaging, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsadminusestaging, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
</div>