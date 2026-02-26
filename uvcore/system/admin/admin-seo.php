<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$uvsseoenabledata = uvs_get_adminfieldhtml("seo->enabledata");
$uvsseoenablemetatags = uvs_get_adminfieldhtml("seo->enabletags");
$uvsseotitle = uvs_get_adminfieldhtml("seo->seotitle");
$uvsseotakeapidescr = uvs_get_adminfieldhtml("seo->seotakeapidescr");
$uvsseodescription = uvs_get_adminfieldhtml("seo->seodescription");
?>
<div id="uvs-admin-seo" class="uvs-admin-opt-section <?php /* Old: echo $uvs_admin_optstabs_state['seo']; */ echo esc_attr( $uvs_admin_optstabs_state['seo'] ); ?>">
    <div class="uvs-admin-opt-title">SEO</div>
    <div class="uvs-admin-opt-space"></div>

    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Enable Data Shema</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsseoenabledata; */ ?>
			<?php echo wp_kses( $uvsseoenabledata, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Enable Meta Tags</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsseoenablemetatags; */ ?>
			<?php echo wp_kses( $uvsseoenablemetatags, uvs_allowed_admin_html() ); ?>
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
			<?php echo wp_kses( $uvsseotitle, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
    </div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Take Description From UrVenue API <small>If there is no description on the API the below description will be taken.</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsseotakeapidescr; */ ?>
			<?php echo wp_kses( $uvsseotakeapidescr, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
    </div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">SEO Description</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsseodescription; */ ?>
			<?php echo wp_kses( $uvsseodescription, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
    </div>
</div>