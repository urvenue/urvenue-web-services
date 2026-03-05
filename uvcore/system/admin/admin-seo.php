<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// $uvsseoenabledata = uvs_get_adminfieldhtml("seo->enabledata");
$uvsseoenabledata = urvenue_ws_adm_get_adminfieldhtml("seo->enabledata"); // Axl UWS-7416
// $uvsseoenablemetatags = uvs_get_adminfieldhtml("seo->enabletags");
$uvsseoenablemetatags = urvenue_ws_adm_get_adminfieldhtml("seo->enabletags"); // Axl UWS-7416
// $uvsseotitle = uvs_get_adminfieldhtml("seo->seotitle");
$uvsseotitle = urvenue_ws_adm_get_adminfieldhtml("seo->seotitle"); // Axl UWS-7416
// $uvsseotakeapidescr = uvs_get_adminfieldhtml("seo->seotakeapidescr");
$uvsseotakeapidescr = urvenue_ws_adm_get_adminfieldhtml("seo->seotakeapidescr"); // Axl UWS-7416
// $uvsseodescription = uvs_get_adminfieldhtml("seo->seodescription");
$uvsseodescription = urvenue_ws_adm_get_adminfieldhtml("seo->seodescription"); // Axl UWS-7416
?>
<div id="uvs-admin-seo" class="uvs-admin-opt-section <?php /* Old: echo $uvs_admin_optstabs_state['seo']; */ echo esc_attr( $uvs_admin_optstabs_state['seo'] ); ?>">
    <div class="uvs-admin-opt-title">SEO</div>
    <div class="uvs-admin-opt-space"></div>

    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Enable Data Shema</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsseoenabledata; */ ?>
			<?php /* Old: echo wp_kses( $uvsseoenabledata, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsseoenabledata, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Enable Meta Tags</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsseoenablemetatags; */ ?>
			<?php /* Old: echo wp_kses( $uvsseoenablemetatags, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsseoenablemetatags, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
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
			<?php /* Old: echo $uvsseotitle; */ ?>
			<?php /* Old: echo wp_kses( $uvsseotitle, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsseotitle, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Take Description From UrVenue API <small>If there is no description on the API the below description will be taken.</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsseotakeapidescr; */ ?>
			<?php /* Old: echo wp_kses( $uvsseotakeapidescr, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsseotakeapidescr, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">SEO Description</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsseodescription; */ ?>
			<?php /* Old: echo wp_kses( $uvsseodescription, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsseodescription, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
</div>