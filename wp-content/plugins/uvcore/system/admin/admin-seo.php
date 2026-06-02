<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// $urvenue_ws_seoenabledata = uvs_get_adminfieldhtml("seo->enabledata");
// $urvenue_ws_seoenabledata = urvenue_ws_adm_get_adminfieldhtml("seo->enabledata"); // Axl UWS-7416
$urvenue_ws_seoenabledata = urvenue_ws_adm_get_adminfieldhtml("seo->enabledata"); // Axl UWS-7634
// $urvenue_ws_seoenablemetatags = uvs_get_adminfieldhtml("seo->enabletags");
// $urvenue_ws_seoenablemetatags = urvenue_ws_adm_get_adminfieldhtml("seo->enabletags"); // Axl UWS-7416
$urvenue_ws_seoenablemetatags = urvenue_ws_adm_get_adminfieldhtml("seo->enabletags"); // Axl UWS-7634
// $urvenue_ws_seotitle = uvs_get_adminfieldhtml("seo->seotitle");
// $urvenue_ws_seotitle = urvenue_ws_adm_get_adminfieldhtml("seo->seotitle"); // Axl UWS-7416
$urvenue_ws_seotitle = urvenue_ws_adm_get_adminfieldhtml("seo->seotitle"); // Axl UWS-7634
// $urvenue_ws_seotakeapidescr = uvs_get_adminfieldhtml("seo->seotakeapidescr");
// $urvenue_ws_seotakeapidescr = urvenue_ws_adm_get_adminfieldhtml("seo->seotakeapidescr"); // Axl UWS-7416
$urvenue_ws_seotakeapidescr = urvenue_ws_adm_get_adminfieldhtml("seo->seotakeapidescr"); // Axl UWS-7634
// $urvenue_ws_seodescription = uvs_get_adminfieldhtml("seo->seodescription");
// $urvenue_ws_seodescription = urvenue_ws_adm_get_adminfieldhtml("seo->seodescription"); // Axl UWS-7416
$urvenue_ws_seodescription = urvenue_ws_adm_get_adminfieldhtml("seo->seodescription"); // Axl UWS-7634
?>
<div id="uvs-admin-seo" class="uvs-admin-opt-section <?php /* Old: echo $urvenue_ws_admin_optstabs_state['seo']; */ echo esc_attr( $urvenue_ws_admin_optstabs_state['seo'] ); ?>">
    <div class="uvs-admin-opt-title">SEO</div>
    <div class="uvs-admin-opt-space"></div>

    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Enable Data Shema</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_seoenabledata; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_seoenabledata, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_seoenabledata, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Enable Meta Tags</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_seoenablemetatags; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_seoenablemetatags, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_seoenablemetatags, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>

	<div class="uvs-admin-opt-space"></div>
    <div class="uvs-admin-opt-title">SEO Metas</div>
    <div class="uvs-admin-opt-subtitle">Add title and description with varibles:<br><span>{eventname}, {eventddate}, {venuename}, {sitetitle}</span></div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">SEO Title</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_seotitle; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_seotitle, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_seotitle, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Take Description From UrVenue API <small>If there is no description on the API the below description will be taken.</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_seotakeapidescr; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_seotakeapidescr, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_seotakeapidescr, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">SEO Description</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_seodescription; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_seodescription, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_seodescription, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
</div>