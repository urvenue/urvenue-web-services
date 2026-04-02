<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_core_lib;

// $urvenue_ws_evflyerlocdivhtml = uvs_get_flyerlocdivhtml("eventpage");
// $urvenue_ws_evflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("eventpage"); // Axl UWS-7416
$urvenue_ws_evflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("eventpage"); // Axl UWS-7634
// $urvenue_ws_calflyerlocdivhtml = uvs_get_flyerlocdivhtml("calendar");
// $urvenue_ws_calflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("calendar"); // Axl UWS-7416
$urvenue_ws_calflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("calendar"); // Axl UWS-7634
// $urvenue_ws_lisflyerlocdivhtml = uvs_get_flyerlocdivhtml("list");
// $urvenue_ws_lisflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("list"); // Axl UWS-7416
$urvenue_ws_lisflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("list"); // Axl UWS-7634
// $urvenue_ws_slflyerlocdivhtml = uvs_get_flyerlocdivhtml("slider");
// $urvenue_ws_slflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("slider"); // Axl UWS-7416
$urvenue_ws_slflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("slider"); // Axl UWS-7634
// $urvenue_ws_slmobflyerlocdivhtml = uvs_get_flyerlocdivhtml("slidermobile");
// $urvenue_ws_slmobflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("slidermobile"); // Axl UWS-7416
$urvenue_ws_slmobflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("slidermobile"); // Axl UWS-7634
// $urvenue_ws_srflyerlocdivhtml = uvs_get_flyerlocdivhtml("share");
// $urvenue_ws_srflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("share"); // Axl UWS-7416
$urvenue_ws_srflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("share"); // Axl UWS-7634
// $urvenue_ws_c1flyerlocdivhtml = uvs_get_flyerlocdivhtml("custom1");
// $urvenue_ws_c1flyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("custom1"); // Axl UWS-7416
$urvenue_ws_c1flyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("custom1"); // Axl UWS-7634
// $urvenue_ws_c2flyerlocdivhtml = uvs_get_flyerlocdivhtml("custom2");
// $urvenue_ws_c2flyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("custom2"); // Axl UWS-7416
$urvenue_ws_c2flyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("custom2"); // Axl UWS-7634

// $urvenue_ws_flyerplaceholderurlfield = uvs_get_adminfieldhtml("flyers->placeholderurl");
// $urvenue_ws_flyerplaceholderurlfield = urvenue_ws_adm_get_adminfieldhtml("flyers->placeholderurl"); // Axl UWS-7416
$urvenue_ws_flyerplaceholderurlfield = urvenue_ws_adm_get_adminfieldhtml("flyers->placeholderurl"); // Axl UWS-7634
// $urvenue_ws_flyerevpagehideifnomatch = uvs_get_adminfieldhtml("flyers->eventpage-hideifnomatch");
// $urvenue_ws_flyerevpagehideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->eventpage-hideifnomatch"); // Axl UWS-7416
$urvenue_ws_flyerevpagehideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->eventpage-hideifnomatch"); // Axl UWS-7634
// $urvenue_ws_flyerevpageuseplaceholder = uvs_get_adminfieldhtml("flyers->eventpage-useplaceholder");
// $urvenue_ws_flyerevpageuseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->eventpage-useplaceholder"); // Axl UWS-7416
$urvenue_ws_flyerevpageuseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->eventpage-useplaceholder"); // Axl UWS-7634
// $urvenue_ws_flyerevpagesizecode = uvs_get_adminfieldhtml("flyers->eventpage-sizecode");
// $urvenue_ws_flyerevpagesizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->eventpage-sizecode"); // Axl UWS-7416
$urvenue_ws_flyerevpagesizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->eventpage-sizecode"); // Axl UWS-7634
// $urvenue_ws_flyerevpageplaceholderurl = uvs_get_adminfieldhtml("flyers->eventpage-placeholderurl");
// $urvenue_ws_flyerevpageplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->eventpage-placeholderurl"); // Axl UWS-7416
$urvenue_ws_flyerevpageplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->eventpage-placeholderurl"); // Axl UWS-7634
// $urvenue_ws_flyercalendarhideifnomatch = uvs_get_adminfieldhtml("flyers->calendar-hideifnomatch");
// $urvenue_ws_flyercalendarhideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->calendar-hideifnomatch"); // Axl UWS-7416
$urvenue_ws_flyercalendarhideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->calendar-hideifnomatch"); // Axl UWS-7634
// $urvenue_ws_flyercalendaruseplaceholder = uvs_get_adminfieldhtml("flyers->calendar-useplaceholder");
// $urvenue_ws_flyercalendaruseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->calendar-useplaceholder"); // Axl UWS-7416
$urvenue_ws_flyercalendaruseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->calendar-useplaceholder"); // Axl UWS-7634
// $urvenue_ws_flyercalendarsizecode = uvs_get_adminfieldhtml("flyers->calendar-sizecode");
// $urvenue_ws_flyercalendarsizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->calendar-sizecode"); // Axl UWS-7416
$urvenue_ws_flyercalendarsizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->calendar-sizecode"); // Axl UWS-7634
// $urvenue_ws_flyercalendarplaceholderurl = uvs_get_adminfieldhtml("flyers->calendar-placeholderurl");
// $urvenue_ws_flyercalendarplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->calendar-placeholderurl"); // Axl UWS-7416
$urvenue_ws_flyercalendarplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->calendar-placeholderurl"); // Axl UWS-7634
// $urvenue_ws_flyerlisthideifnomatch = uvs_get_adminfieldhtml("flyers->list-hideifnomatch");
// $urvenue_ws_flyerlisthideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->list-hideifnomatch"); // Axl UWS-7416
$urvenue_ws_flyerlisthideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->list-hideifnomatch"); // Axl UWS-7634
// $urvenue_ws_flyerlistuseplaceholder = uvs_get_adminfieldhtml("flyers->list-useplaceholder");
// $urvenue_ws_flyerlistuseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->list-useplaceholder"); // Axl UWS-7416
$urvenue_ws_flyerlistuseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->list-useplaceholder"); // Axl UWS-7634
// $urvenue_ws_flyerlistsizecode = uvs_get_adminfieldhtml("flyers->list-sizecode");
// $urvenue_ws_flyerlistsizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->list-sizecode"); // Axl UWS-7416
$urvenue_ws_flyerlistsizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->list-sizecode"); // Axl UWS-7634
// $urvenue_ws_flyerlistplaceholderurl = uvs_get_adminfieldhtml("flyers->list-placeholderurl");
// $urvenue_ws_flyerlistplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->list-placeholderurl"); // Axl UWS-7416
$urvenue_ws_flyerlistplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->list-placeholderurl"); // Axl UWS-7634
// $urvenue_ws_flyersliderhideifnomatch = uvs_get_adminfieldhtml("flyers->slider-hideifnomatch");
// $urvenue_ws_flyersliderhideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->slider-hideifnomatch"); // Axl UWS-7416
$urvenue_ws_flyersliderhideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->slider-hideifnomatch"); // Axl UWS-7634
// $urvenue_ws_flyerslideruseplaceholder = uvs_get_adminfieldhtml("flyers->slider-useplaceholder");
// $urvenue_ws_flyerslideruseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->slider-useplaceholder"); // Axl UWS-7416
$urvenue_ws_flyerslideruseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->slider-useplaceholder"); // Axl UWS-7634
// $urvenue_ws_flyerslidersizecode = uvs_get_adminfieldhtml("flyers->slider-sizecode");
// $urvenue_ws_flyerslidersizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->slider-sizecode"); // Axl UWS-7416
$urvenue_ws_flyerslidersizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->slider-sizecode"); // Axl UWS-7634
// $urvenue_ws_flyersliderplaceholderurl = uvs_get_adminfieldhtml("flyers->slider-placeholderurl");
// $urvenue_ws_flyersliderplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->slider-placeholderurl"); // Axl UWS-7416
$urvenue_ws_flyersliderplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->slider-placeholderurl"); // Axl UWS-7634
// $urvenue_ws_flyerslidermobilehideifnomatch = uvs_get_adminfieldhtml("flyers->slidermobile-hideifnomatch");
// $urvenue_ws_flyerslidermobilehideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->slidermobile-hideifnomatch"); // Axl UWS-7416
$urvenue_ws_flyerslidermobilehideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->slidermobile-hideifnomatch"); // Axl UWS-7634
// $urvenue_ws_flyerslidermobileuseplaceholder = uvs_get_adminfieldhtml("flyers->slidermobile-useplaceholder");
// $urvenue_ws_flyerslidermobileuseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->slidermobile-useplaceholder"); // Axl UWS-7416
$urvenue_ws_flyerslidermobileuseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->slidermobile-useplaceholder"); // Axl UWS-7634
// $urvenue_ws_flyerslidermobilesizecode = uvs_get_adminfieldhtml("flyers->slidermobile-sizecode");
// $urvenue_ws_flyerslidermobilesizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->slidermobile-sizecode"); // Axl UWS-7416
$urvenue_ws_flyerslidermobilesizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->slidermobile-sizecode"); // Axl UWS-7634
// $urvenue_ws_flyerslidermobileplaceholderurl = uvs_get_adminfieldhtml("flyers->slidermobile-placeholderurl");
// $urvenue_ws_flyerslidermobileplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->slidermobile-placeholderurl"); // Axl UWS-7416
$urvenue_ws_flyerslidermobileplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->slidermobile-placeholderurl"); // Axl UWS-7634
// $urvenue_ws_flyersharehideifnomatch = uvs_get_adminfieldhtml("flyers->share-hideifnomatch");
// $urvenue_ws_flyersharehideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->share-hideifnomatch"); // Axl UWS-7416
$urvenue_ws_flyersharehideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->share-hideifnomatch"); // Axl UWS-7634
// $urvenue_ws_flyershareuseplaceholder = uvs_get_adminfieldhtml("flyers->share-useplaceholder");
// $urvenue_ws_flyershareuseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->share-useplaceholder"); // Axl UWS-7416
$urvenue_ws_flyershareuseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->share-useplaceholder"); // Axl UWS-7634
// $urvenue_ws_flyersharesizecode = uvs_get_adminfieldhtml("flyers->share-sizecode");
// $urvenue_ws_flyersharesizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->share-sizecode"); // Axl UWS-7416
$urvenue_ws_flyersharesizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->share-sizecode"); // Axl UWS-7634
// $urvenue_ws_flyershareplaceholderurl = uvs_get_adminfieldhtml("flyers->share-placeholderurl");
// $urvenue_ws_flyershareplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->share-placeholderurl"); // Axl UWS-7416
$urvenue_ws_flyershareplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->share-placeholderurl"); // Axl UWS-7634
// $urvenue_ws_flyercustom1hideifnomatch = uvs_get_adminfieldhtml("flyers->custom1-hideifnomatch");
// $urvenue_ws_flyercustom1hideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->custom1-hideifnomatch"); // Axl UWS-7416
$urvenue_ws_flyercustom1hideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->custom1-hideifnomatch"); // Axl UWS-7634
// $urvenue_ws_flyercustom1useplaceholder = uvs_get_adminfieldhtml("flyers->custom1-useplaceholder");
// $urvenue_ws_flyercustom1useplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->custom1-useplaceholder"); // Axl UWS-7416
$urvenue_ws_flyercustom1useplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->custom1-useplaceholder"); // Axl UWS-7634
// $urvenue_ws_flyercustom1sizecode = uvs_get_adminfieldhtml("flyers->custom1-sizecode");
// $urvenue_ws_flyercustom1sizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->custom1-sizecode"); // Axl UWS-7416
$urvenue_ws_flyercustom1sizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->custom1-sizecode"); // Axl UWS-7634
// $urvenue_ws_flyercustom1placeholderurl = uvs_get_adminfieldhtml("flyers->custom1-placeholderurl");
// $urvenue_ws_flyercustom1placeholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->custom1-placeholderurl"); // Axl UWS-7416
$urvenue_ws_flyercustom1placeholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->custom1-placeholderurl"); // Axl UWS-7634

// $urvenue_ws_flyercustom2hideifnomatch = uvs_get_adminfieldhtml("flyers->custom2-hideifnomatch");
// $urvenue_ws_flyercustom2hideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->custom2-hideifnomatch"); // Axl UWS-7416
$urvenue_ws_flyercustom2hideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->custom2-hideifnomatch"); // Axl UWS-7634
// $urvenue_ws_flyercustom2useplaceholder = uvs_get_adminfieldhtml("flyers->custom2-useplaceholder");
// $urvenue_ws_flyercustom2useplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->custom2-useplaceholder"); // Axl UWS-7416
$urvenue_ws_flyercustom2useplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->custom2-useplaceholder"); // Axl UWS-7634
// $urvenue_ws_flyercustom2sizecode = uvs_get_adminfieldhtml("flyers->custom2-sizecode");
// $urvenue_ws_flyercustom2sizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->custom2-sizecode"); // Axl UWS-7416
$urvenue_ws_flyercustom2sizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->custom2-sizecode"); // Axl UWS-7634
// $urvenue_ws_flyercustom2placeholderurl = uvs_get_adminfieldhtml("flyers->custom2-placeholderurl");
// $urvenue_ws_flyercustom2placeholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->custom2-placeholderurl"); // Axl UWS-7416
$urvenue_ws_flyercustom2placeholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->custom2-placeholderurl"); // Axl UWS-7634
?>

<div id="uvs-admin-flyers" class="uvs-admin-opt-section <?php /* Old: echo $urvenue_ws_admin_optstabs_state['flyers']; */ echo esc_attr( $urvenue_ws_admin_optstabs_state['flyers'] ); ?>">
    <div class="uvs-admin-opt-title">Flyers</div>
    <div class="uvs-admin-opt-descr">Change the Flyer Type and Flyer Ratio for your events on the different events integrations, the order of the items defines the priority.</div>
    <div class="uvs-admin-opt-space"></div>
    <div class="uvs-admin-opt-subtitle">Event Page</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Hide If No Match <small>Hide Flyer if there is no exact match</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_flyerevpagehideifnomatch; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_flyerevpagehideifnomatch, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_flyerevpagehideifnomatch, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_flyerevpageuseplaceholder; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_flyerevpageuseplaceholder, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_flyerevpageuseplaceholder, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Size Code <small>Default size code of the image</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_flyerevpagesizecode; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_flyerevpagesizecode, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_flyerevpagesizecode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_flyerevpageplaceholderurl; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_flyerevpageplaceholderurl, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_flyerevpageplaceholderurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-event">
        <?php // @Axl ?>
        <?php /* Old: echo $urvenue_ws_evflyerlocdivhtml; */ ?>
        <?php /* Old: echo wp_kses( $urvenue_ws_evflyerlocdivhtml, uvs_allowed_admin_html() ); */ ?>
        <?php echo wp_kses( $urvenue_ws_evflyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
        <?php // @Axl End ?>
    </div>
    <div class="uvs-admin-flyer-actions">
        <span>Add a new Flyer set to the priority list</span>
        <button class="uvs-btn uvs-btn-p uvsjs-addflyerset" type="button" data-target=".uvs-admin-flyer-event">Add New</button>
    </div>
    <div class="uvs-admin-opt-spacedivi"></div>
    <div class="uvs-admin-opt-subtitle">Calendar</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Hide If No Match <small>Hide Flyer if there is no exact match</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_flyercalendarhideifnomatch; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_flyercalendarhideifnomatch, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_flyercalendarhideifnomatch, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_flyercalendaruseplaceholder; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_flyercalendaruseplaceholder, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_flyercalendaruseplaceholder, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $urvenue_ws_flyercalendarsizecode; */ ?>
            <?php /* Old: echo wp_kses( $urvenue_ws_flyercalendarsizecode, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $urvenue_ws_flyercalendarsizecode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_flyercalendarplaceholderurl; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_flyercalendarplaceholderurl, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_flyercalendarplaceholderurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>    
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-cal">
        <?php // @Axl ?>
        <?php /* Old: echo $urvenue_ws_calflyerlocdivhtml; */ ?>
        <?php /* Old: echo wp_kses( $urvenue_ws_calflyerlocdivhtml, uvs_allowed_admin_html() ); */ ?>
        <?php echo wp_kses( $urvenue_ws_calflyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
        <?php // @Axl End ?>
    </div>
    <div class="uvs-admin-flyer-actions">
        <span>Add a new Flyer set to the priority list</span>
        <button class="uvs-btn uvs-btn-p uvsjs-addflyerset" type="button" data-target=".uvs-admin-flyer-cal">Add New</button>
    </div>
    <div class="uvs-admin-opt-spacedivi"></div>
    <div class="uvs-admin-opt-subtitle">List</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Hide If No Match <small>Hide Flyer if there is no exact match</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_flyerlisthideifnomatch; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_flyerlisthideifnomatch, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_flyerlisthideifnomatch, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_flyerlistuseplaceholder; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_flyerlistuseplaceholder, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_flyerlistuseplaceholder, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $urvenue_ws_flyerlistsizecode; */ ?>
            <?php /* Old: echo wp_kses( $urvenue_ws_flyerlistsizecode, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $urvenue_ws_flyerlistsizecode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $urvenue_ws_flyerlistplaceholderurl; */ ?>
            <?php /* Old: echo wp_kses( $urvenue_ws_flyerlistplaceholderurl, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $urvenue_ws_flyerlistplaceholderurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-list">
        <?php // @Axl ?>
        <?php /* Old: echo $urvenue_ws_lisflyerlocdivhtml; */ ?>
        <?php /* Old: echo wp_kses( $urvenue_ws_lisflyerlocdivhtml, uvs_allowed_admin_html() ); */ ?>
        <?php echo wp_kses( $urvenue_ws_lisflyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
        <?php // @Axl End ?>
    </div>
    <div class="uvs-admin-flyer-actions">
        <span>Add a new Flyer set to the priority list</span>
        <button class="uvs-btn uvs-btn-p uvsjs-addflyerset" type="button" data-target=".uvs-admin-flyer-list">Add New</button>
    </div>
    <div class="uvs-admin-opt-spacedivi"></div>
    <div class="uvs-admin-opt-subtitle">Slider</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Hide If No Match <small>Hide Flyer if there is no exact match</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_flyersliderhideifnomatch; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_flyersliderhideifnomatch, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_flyersliderhideifnomatch, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_flyerslideruseplaceholder; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_flyerslideruseplaceholder, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_flyerslideruseplaceholder, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $urvenue_ws_flyerslidersizecode; */ ?>
            <?php /* Old: echo wp_kses( $urvenue_ws_flyerslidersizecode, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $urvenue_ws_flyerslidersizecode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $urvenue_ws_flyersliderplaceholderurl; */ ?>
            <?php /* Old: echo wp_kses( $urvenue_ws_flyersliderplaceholderurl, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $urvenue_ws_flyersliderplaceholderurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-slider">
        <?php // @Axl ?>
        <?php /* Old: echo $urvenue_ws_slflyerlocdivhtml; */ ?>
        <?php /* Old: echo wp_kses( $urvenue_ws_slflyerlocdivhtml, uvs_allowed_admin_html() ); */ ?>
        <?php echo wp_kses( $urvenue_ws_slflyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
        <?php // @Axl End ?>
    </div>
    <div class="uvs-admin-flyer-actions">
        <span>Add a new Flyer set to the priority list</span>
        <button class="uvs-btn uvs-btn-p uvsjs-addflyerset" type="button" data-target=".uvs-admin-flyer-slider">Add New</button>
    </div>
    <div class="uvs-admin-opt-spacedivi"></div>
    <div class="uvs-admin-opt-subtitle">Slider Mobile</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Hide If No Match <small>Hide Flyer if there is no exact match</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_flyerslidermobilehideifnomatch; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_flyerslidermobilehideifnomatch, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_flyerslidermobilehideifnomatch, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_flyerslidermobileuseplaceholder; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_flyerslidermobileuseplaceholder, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_flyerslidermobileuseplaceholder, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $urvenue_ws_flyerslidermobilesizecode; */ ?>
            <?php /* Old: echo wp_kses( $urvenue_ws_flyerslidermobilesizecode, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $urvenue_ws_flyerslidermobilesizecode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $urvenue_ws_flyerslidermobileplaceholderurl; */ ?>
            <?php /* Old: echo wp_kses( $urvenue_ws_flyerslidermobileplaceholderurl, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $urvenue_ws_flyerslidermobileplaceholderurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-slidermobile">
        <?php // @Axl ?>
        <?php /* Old: echo $urvenue_ws_slmobflyerlocdivhtml; */ ?>
        <?php /* Old: echo wp_kses( $urvenue_ws_slmobflyerlocdivhtml, uvs_allowed_admin_html() ); */ ?>
        <?php echo wp_kses( $urvenue_ws_slmobflyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
        <?php // @Axl End ?>
    </div>
    <div class="uvs-admin-flyer-actions">
        <span>Add a new Flyer set to the priority list</span>
        <button class="uvs-btn uvs-btn-p uvsjs-addflyerset" type="button" data-target=".uvs-admin-flyer-slidermobile">Add New</button>
    </div>
    <div class="uvs-admin-opt-spacedivi"></div>
    <div class="uvs-admin-opt-subtitle">Share</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Hide If No Match <small>Hide Flyer if there is no exact match</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_flyersharehideifnomatch; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_flyersharehideifnomatch, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_flyersharehideifnomatch, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_flyershareuseplaceholder; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_flyershareuseplaceholder, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_flyershareuseplaceholder, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $urvenue_ws_flyersharesizecode; */ ?>
            <?php /* Old: echo wp_kses( $urvenue_ws_flyersharesizecode, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $urvenue_ws_flyersharesizecode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $urvenue_ws_flyershareplaceholderurl; */ ?>
            <?php /* Old: echo wp_kses( $urvenue_ws_flyershareplaceholderurl, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $urvenue_ws_flyershareplaceholderurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-share">
        <?php // @Axl ?>
        <?php /* Old: echo $urvenue_ws_srflyerlocdivhtml; */ ?>
        <?php /* Old: echo wp_kses( $urvenue_ws_srflyerlocdivhtml, uvs_allowed_admin_html() ); */ ?>
        <?php echo wp_kses( $urvenue_ws_srflyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
        <?php // @Axl End ?>
    </div>
    <div class="uvs-admin-flyer-actions">
        <span>Add a new Flyer set to the priority list</span>
        <button class="uvs-btn uvs-btn-p uvsjs-addflyerset" type="button" data-target=".uvs-admin-flyer-share">Add New</button>
    </div>
    <div class="uvs-admin-opt-spacedivi"></div>
    <div class="uvs-admin-opt-subtitle">Custom 1</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Hide If No Match <small>Hide Flyer if there is no exact match</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_flyercustom1hideifnomatch; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_flyercustom1hideifnomatch, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_flyercustom1hideifnomatch, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_flyercustom1useplaceholder; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_flyercustom1useplaceholder, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_flyercustom1useplaceholder, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $urvenue_ws_flyercustom1sizecode; */ ?>
            <?php /* Old: echo wp_kses( $urvenue_ws_flyercustom1sizecode, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $urvenue_ws_flyercustom1sizecode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $urvenue_ws_flyercustom1placeholderurl; */ ?>
            <?php /* Old: echo wp_kses( $urvenue_ws_flyercustom1placeholderurl, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $urvenue_ws_flyercustom1placeholderurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-custom1">
        <?php // @Axl ?>
        <?php /* Old: echo $urvenue_ws_c1flyerlocdivhtml; */ ?>
        <?php /* Old: echo wp_kses( $urvenue_ws_c1flyerlocdivhtml, uvs_allowed_admin_html() ); */ ?>
        <?php echo wp_kses( $urvenue_ws_c1flyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
        <?php // @Axl End ?>
    </div>
    <div class="uvs-admin-flyer-actions">
        <span>Add a new Flyer set to the priority list</span>
        <button class="uvs-btn uvs-btn-p uvsjs-addflyerset" type="button" data-target=".uvs-admin-flyer-custom1">Add New</button>
    </div>
    <div class="uvs-admin-opt-spacedivi"></div>
    <div class="uvs-admin-opt-subtitle">Custom 2</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Hide If No Match <small>Hide Flyer if there is no exact match</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_flyercustom2hideifnomatch; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_flyercustom2hideifnomatch, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_flyercustom2hideifnomatch, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_flyercustom2useplaceholder; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_flyercustom2useplaceholder, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_flyercustom2useplaceholder, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $urvenue_ws_flyercustom2sizecode; */ ?>
            <?php /* Old: echo wp_kses( $urvenue_ws_flyercustom2sizecode, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $urvenue_ws_flyercustom2sizecode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $urvenue_ws_flyercustom2placeholderurl; */ ?>
            <?php /* Old: echo wp_kses( $urvenue_ws_flyercustom2placeholderurl, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $urvenue_ws_flyercustom2placeholderurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-custom2">
        <?php // @Axl ?>
        <?php /* Old: echo $urvenue_ws_c2flyerlocdivhtml; */ ?>
        <?php /* Old: echo wp_kses( $urvenue_ws_c2flyerlocdivhtml, uvs_allowed_admin_html() ); */ ?>
        <?php echo wp_kses( $urvenue_ws_c2flyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
        <?php // @Axl End ?>
    </div>
    <div class="uvs-admin-flyer-actions">
        <span>Add a new Flyer set to the priority list</span>
        <button class="uvs-btn uvs-btn-p uvsjs-addflyerset" type="button" data-target=".uvs-admin-flyer-custom2">Add New</button>
    </div>
    <div class="uvs-admin-opt-spacedivi"></div>
    <div class="uvs-infolist-item">
        <div class="uvsname">Global Placeholder Flyer URL <small>When an event doesn't have flyer the placeholder is shown.</small></div>
        <?php // @Axl ?>
        <?php /* old: <div class="uvsvalue">[echo $urvenue_ws_flyerplaceholderurlfield]</div> */ ?>
        <?php /* Old: echo wp_kses( $urvenue_ws_flyerplaceholderurlfield, uvs_allowed_admin_html() ); */ ?>
        <div class="uvsvalue"><?php echo wp_kses( $urvenue_ws_flyerplaceholderurlfield, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?></div>
        <?php // @Axl End ?>
    </div>
</div>