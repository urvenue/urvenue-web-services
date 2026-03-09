<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_core_lib;

// $uvsevflyerlocdivhtml = uvs_get_flyerlocdivhtml("eventpage");
$uvsevflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("eventpage"); // Axl UWS-7416
// $uvscalflyerlocdivhtml = uvs_get_flyerlocdivhtml("calendar");
$uvscalflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("calendar"); // Axl UWS-7416
// $uvslisflyerlocdivhtml = uvs_get_flyerlocdivhtml("list");
$uvslisflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("list"); // Axl UWS-7416
// $uvsslflyerlocdivhtml = uvs_get_flyerlocdivhtml("slider");
$uvsslflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("slider"); // Axl UWS-7416
// $uvsslmobflyerlocdivhtml = uvs_get_flyerlocdivhtml("slidermobile");
$uvsslmobflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("slidermobile"); // Axl UWS-7416
// $uvssrflyerlocdivhtml = uvs_get_flyerlocdivhtml("share");
$uvssrflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("share"); // Axl UWS-7416
// $uvsc1flyerlocdivhtml = uvs_get_flyerlocdivhtml("custom1");
$uvsc1flyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("custom1"); // Axl UWS-7416
// $uvsc2flyerlocdivhtml = uvs_get_flyerlocdivhtml("custom2");
$uvsc2flyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("custom2"); // Axl UWS-7416

// $uvsflyerplaceholderurlfield = uvs_get_adminfieldhtml("flyers->placeholderurl");
$uvsflyerplaceholderurlfield = urvenue_ws_adm_get_adminfieldhtml("flyers->placeholderurl"); // Axl UWS-7416
// $uvsflyerevpagehideifnomatch = uvs_get_adminfieldhtml("flyers->eventpage-hideifnomatch");
$uvsflyerevpagehideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->eventpage-hideifnomatch"); // Axl UWS-7416
// $uvsflyerevpageuseplaceholder = uvs_get_adminfieldhtml("flyers->eventpage-useplaceholder");
$uvsflyerevpageuseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->eventpage-useplaceholder"); // Axl UWS-7416
// $uvsflyerevpagesizecode = uvs_get_adminfieldhtml("flyers->eventpage-sizecode");
$uvsflyerevpagesizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->eventpage-sizecode"); // Axl UWS-7416
// $uvsflyerevpageplaceholderurl = uvs_get_adminfieldhtml("flyers->eventpage-placeholderurl");
$uvsflyerevpageplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->eventpage-placeholderurl"); // Axl UWS-7416
// $uvsflyercalendarhideifnomatch = uvs_get_adminfieldhtml("flyers->calendar-hideifnomatch");
$uvsflyercalendarhideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->calendar-hideifnomatch"); // Axl UWS-7416
// $uvsflyercalendaruseplaceholder = uvs_get_adminfieldhtml("flyers->calendar-useplaceholder");
$uvsflyercalendaruseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->calendar-useplaceholder"); // Axl UWS-7416
// $uvsflyercalendarsizecode = uvs_get_adminfieldhtml("flyers->calendar-sizecode");
$uvsflyercalendarsizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->calendar-sizecode"); // Axl UWS-7416
// $uvsflyercalendarplaceholderurl = uvs_get_adminfieldhtml("flyers->calendar-placeholderurl");
$uvsflyercalendarplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->calendar-placeholderurl"); // Axl UWS-7416
// $uvsflyerlisthideifnomatch = uvs_get_adminfieldhtml("flyers->list-hideifnomatch");
$uvsflyerlisthideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->list-hideifnomatch"); // Axl UWS-7416
// $uvsflyerlistuseplaceholder = uvs_get_adminfieldhtml("flyers->list-useplaceholder");
$uvsflyerlistuseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->list-useplaceholder"); // Axl UWS-7416
// $uvsflyerlistsizecode = uvs_get_adminfieldhtml("flyers->list-sizecode");
$uvsflyerlistsizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->list-sizecode"); // Axl UWS-7416
// $uvsflyerlistplaceholderurl = uvs_get_adminfieldhtml("flyers->list-placeholderurl");
$uvsflyerlistplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->list-placeholderurl"); // Axl UWS-7416
// $uvsflyersliderhideifnomatch = uvs_get_adminfieldhtml("flyers->slider-hideifnomatch");
$uvsflyersliderhideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->slider-hideifnomatch"); // Axl UWS-7416
// $uvsflyerslideruseplaceholder = uvs_get_adminfieldhtml("flyers->slider-useplaceholder");
$uvsflyerslideruseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->slider-useplaceholder"); // Axl UWS-7416
// $uvsflyerslidersizecode = uvs_get_adminfieldhtml("flyers->slider-sizecode");
$uvsflyerslidersizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->slider-sizecode"); // Axl UWS-7416
// $uvsflyersliderplaceholderurl = uvs_get_adminfieldhtml("flyers->slider-placeholderurl");
$uvsflyersliderplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->slider-placeholderurl"); // Axl UWS-7416
// $uvsflyerslidermobilehideifnomatch = uvs_get_adminfieldhtml("flyers->slidermobile-hideifnomatch");
$uvsflyerslidermobilehideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->slidermobile-hideifnomatch"); // Axl UWS-7416
// $uvsflyerslidermobileuseplaceholder = uvs_get_adminfieldhtml("flyers->slidermobile-useplaceholder");
$uvsflyerslidermobileuseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->slidermobile-useplaceholder"); // Axl UWS-7416
// $uvsflyerslidermobilesizecode = uvs_get_adminfieldhtml("flyers->slidermobile-sizecode");
$uvsflyerslidermobilesizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->slidermobile-sizecode"); // Axl UWS-7416
// $uvsflyerslidermobileplaceholderurl = uvs_get_adminfieldhtml("flyers->slidermobile-placeholderurl");
$uvsflyerslidermobileplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->slidermobile-placeholderurl"); // Axl UWS-7416
// $uvsflyersharehideifnomatch = uvs_get_adminfieldhtml("flyers->share-hideifnomatch");
$uvsflyersharehideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->share-hideifnomatch"); // Axl UWS-7416
// $uvsflyershareuseplaceholder = uvs_get_adminfieldhtml("flyers->share-useplaceholder");
$uvsflyershareuseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->share-useplaceholder"); // Axl UWS-7416
// $uvsflyersharesizecode = uvs_get_adminfieldhtml("flyers->share-sizecode");
$uvsflyersharesizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->share-sizecode"); // Axl UWS-7416
// $uvsflyershareplaceholderurl = uvs_get_adminfieldhtml("flyers->share-placeholderurl");
$uvsflyershareplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->share-placeholderurl"); // Axl UWS-7416
// $uvsflyercustom1hideifnomatch = uvs_get_adminfieldhtml("flyers->custom1-hideifnomatch");
$uvsflyercustom1hideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->custom1-hideifnomatch"); // Axl UWS-7416
// $uvsflyercustom1useplaceholder = uvs_get_adminfieldhtml("flyers->custom1-useplaceholder");
$uvsflyercustom1useplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->custom1-useplaceholder"); // Axl UWS-7416
// $uvsflyercustom1sizecode = uvs_get_adminfieldhtml("flyers->custom1-sizecode");
$uvsflyercustom1sizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->custom1-sizecode"); // Axl UWS-7416
// $uvsflyercustom1placeholderurl = uvs_get_adminfieldhtml("flyers->custom1-placeholderurl");
$uvsflyercustom1placeholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->custom1-placeholderurl"); // Axl UWS-7416

// $uvsflyercustom2hideifnomatch = uvs_get_adminfieldhtml("flyers->custom2-hideifnomatch");
$uvsflyercustom2hideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->custom2-hideifnomatch"); // Axl UWS-7416
// $uvsflyercustom2useplaceholder = uvs_get_adminfieldhtml("flyers->custom2-useplaceholder");
$uvsflyercustom2useplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->custom2-useplaceholder"); // Axl UWS-7416
// $uvsflyercustom2sizecode = uvs_get_adminfieldhtml("flyers->custom2-sizecode");
$uvsflyercustom2sizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->custom2-sizecode"); // Axl UWS-7416
// $uvsflyercustom2placeholderurl = uvs_get_adminfieldhtml("flyers->custom2-placeholderurl");
$uvsflyercustom2placeholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->custom2-placeholderurl"); // Axl UWS-7416
?>

<div id="uvs-admin-flyers" class="uvs-admin-opt-section <?php /* Old: echo $uvs_admin_optstabs_state['flyers']; */ echo esc_attr( $uvs_admin_optstabs_state['flyers'] ); ?>">
    <div class="uvs-admin-opt-title">Flyers</div>
    <div class="uvs-admin-opt-descr">Change the Flyer Type and Flyer Ratio for your events on the different events integrations, the order of the items defines the priority.</div>
    <div class="uvs-admin-opt-space"></div>
    <div class="uvs-admin-opt-subtitle">Event Page</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Hide If No Match <small>Hide Flyer if there is no exact match</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyerevpagehideifnomatch; */ ?>
			<?php /* Old: echo wp_kses( $uvsflyerevpagehideifnomatch, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsflyerevpagehideifnomatch, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyerevpageuseplaceholder; */ ?>
			<?php /* Old: echo wp_kses( $uvsflyerevpageuseplaceholder, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsflyerevpageuseplaceholder, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Size Code <small>Default size code of the image</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyerevpagesizecode; */ ?>
			<?php /* Old: echo wp_kses( $uvsflyerevpagesizecode, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsflyerevpagesizecode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyerevpageplaceholderurl; */ ?>
			<?php /* Old: echo wp_kses( $uvsflyerevpageplaceholderurl, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsflyerevpageplaceholderurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-event">
        <?php // @Axl ?>
        <?php /* Old: echo $uvsevflyerlocdivhtml; */ ?>
        <?php /* Old: echo wp_kses( $uvsevflyerlocdivhtml, uvs_allowed_admin_html() ); */ ?>
        <?php echo wp_kses( $uvsevflyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
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
			<?php /* Old: echo $uvsflyercalendarhideifnomatch; */ ?>
			<?php /* Old: echo wp_kses( $uvsflyercalendarhideifnomatch, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsflyercalendarhideifnomatch, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyercalendaruseplaceholder; */ ?>
			<?php /* Old: echo wp_kses( $uvsflyercalendaruseplaceholder, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsflyercalendaruseplaceholder, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyercalendarsizecode; */ ?>
            <?php /* Old: echo wp_kses( $uvsflyercalendarsizecode, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $uvsflyercalendarsizecode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyercalendarplaceholderurl; */ ?>
			<?php /* Old: echo wp_kses( $uvsflyercalendarplaceholderurl, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsflyercalendarplaceholderurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>    
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-cal">
        <?php // @Axl ?>
        <?php /* Old: echo $uvscalflyerlocdivhtml; */ ?>
        <?php /* Old: echo wp_kses( $uvscalflyerlocdivhtml, uvs_allowed_admin_html() ); */ ?>
        <?php echo wp_kses( $uvscalflyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
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
			<?php /* Old: echo $uvsflyerlisthideifnomatch; */ ?>
			<?php /* Old: echo wp_kses( $uvsflyerlisthideifnomatch, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsflyerlisthideifnomatch, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyerlistuseplaceholder; */ ?>
			<?php /* Old: echo wp_kses( $uvsflyerlistuseplaceholder, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsflyerlistuseplaceholder, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyerlistsizecode; */ ?>
            <?php /* Old: echo wp_kses( $uvsflyerlistsizecode, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $uvsflyerlistsizecode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyerlistplaceholderurl; */ ?>
            <?php /* Old: echo wp_kses( $uvsflyerlistplaceholderurl, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $uvsflyerlistplaceholderurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-list">
        <?php // @Axl ?>
        <?php /* Old: echo $uvslisflyerlocdivhtml; */ ?>
        <?php /* Old: echo wp_kses( $uvslisflyerlocdivhtml, uvs_allowed_admin_html() ); */ ?>
        <?php echo wp_kses( $uvslisflyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
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
			<?php /* Old: echo $uvsflyersliderhideifnomatch; */ ?>
			<?php /* Old: echo wp_kses( $uvsflyersliderhideifnomatch, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsflyersliderhideifnomatch, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyerslideruseplaceholder; */ ?>
			<?php /* Old: echo wp_kses( $uvsflyerslideruseplaceholder, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsflyerslideruseplaceholder, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyerslidersizecode; */ ?>
            <?php /* Old: echo wp_kses( $uvsflyerslidersizecode, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $uvsflyerslidersizecode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyersliderplaceholderurl; */ ?>
            <?php /* Old: echo wp_kses( $uvsflyersliderplaceholderurl, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $uvsflyersliderplaceholderurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-slider">
        <?php // @Axl ?>
        <?php /* Old: echo $uvsslflyerlocdivhtml; */ ?>
        <?php /* Old: echo wp_kses( $uvsslflyerlocdivhtml, uvs_allowed_admin_html() ); */ ?>
        <?php echo wp_kses( $uvsslflyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
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
			<?php /* Old: echo $uvsflyerslidermobilehideifnomatch; */ ?>
			<?php /* Old: echo wp_kses( $uvsflyerslidermobilehideifnomatch, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsflyerslidermobilehideifnomatch, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyerslidermobileuseplaceholder; */ ?>
			<?php /* Old: echo wp_kses( $uvsflyerslidermobileuseplaceholder, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsflyerslidermobileuseplaceholder, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyerslidermobilesizecode; */ ?>
            <?php /* Old: echo wp_kses( $uvsflyerslidermobilesizecode, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $uvsflyerslidermobilesizecode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyerslidermobileplaceholderurl; */ ?>
            <?php /* Old: echo wp_kses( $uvsflyerslidermobileplaceholderurl, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $uvsflyerslidermobileplaceholderurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-slidermobile">
        <?php // @Axl ?>
        <?php /* Old: echo $uvsslmobflyerlocdivhtml; */ ?>
        <?php /* Old: echo wp_kses( $uvsslmobflyerlocdivhtml, uvs_allowed_admin_html() ); */ ?>
        <?php echo wp_kses( $uvsslmobflyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
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
			<?php /* Old: echo $uvsflyersharehideifnomatch; */ ?>
			<?php /* Old: echo wp_kses( $uvsflyersharehideifnomatch, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsflyersharehideifnomatch, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyershareuseplaceholder; */ ?>
			<?php /* Old: echo wp_kses( $uvsflyershareuseplaceholder, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsflyershareuseplaceholder, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyersharesizecode; */ ?>
            <?php /* Old: echo wp_kses( $uvsflyersharesizecode, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $uvsflyersharesizecode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyershareplaceholderurl; */ ?>
            <?php /* Old: echo wp_kses( $uvsflyershareplaceholderurl, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $uvsflyershareplaceholderurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-share">
        <?php // @Axl ?>
        <?php /* Old: echo $uvssrflyerlocdivhtml; */ ?>
        <?php /* Old: echo wp_kses( $uvssrflyerlocdivhtml, uvs_allowed_admin_html() ); */ ?>
        <?php echo wp_kses( $uvssrflyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
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
			<?php /* Old: echo $uvsflyercustom1hideifnomatch; */ ?>
			<?php /* Old: echo wp_kses( $uvsflyercustom1hideifnomatch, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsflyercustom1hideifnomatch, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyercustom1useplaceholder; */ ?>
			<?php /* Old: echo wp_kses( $uvsflyercustom1useplaceholder, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsflyercustom1useplaceholder, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyercustom1sizecode; */ ?>
            <?php /* Old: echo wp_kses( $uvsflyercustom1sizecode, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $uvsflyercustom1sizecode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyercustom1placeholderurl; */ ?>
            <?php /* Old: echo wp_kses( $uvsflyercustom1placeholderurl, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $uvsflyercustom1placeholderurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-custom1">
        <?php // @Axl ?>
        <?php /* Old: echo $uvsc1flyerlocdivhtml; */ ?>
        <?php /* Old: echo wp_kses( $uvsc1flyerlocdivhtml, uvs_allowed_admin_html() ); */ ?>
        <?php echo wp_kses( $uvsc1flyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
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
			<?php /* Old: echo $uvsflyercustom2hideifnomatch; */ ?>
			<?php /* Old: echo wp_kses( $uvsflyercustom2hideifnomatch, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsflyercustom2hideifnomatch, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyercustom2useplaceholder; */ ?>
			<?php /* Old: echo wp_kses( $uvsflyercustom2useplaceholder, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvsflyercustom2useplaceholder, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyercustom2sizecode; */ ?>
            <?php /* Old: echo wp_kses( $uvsflyercustom2sizecode, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $uvsflyercustom2sizecode, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyercustom2placeholderurl; */ ?>
            <?php /* Old: echo wp_kses( $uvsflyercustom2placeholderurl, uvs_allowed_admin_html() ); */ ?>
            <?php echo wp_kses( $uvsflyercustom2placeholderurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-custom2">
        <?php // @Axl ?>
        <?php /* Old: echo $uvsc2flyerlocdivhtml; */ ?>
        <?php /* Old: echo wp_kses( $uvsc2flyerlocdivhtml, uvs_allowed_admin_html() ); */ ?>
        <?php echo wp_kses( $uvsc2flyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
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
        <!-- <div class="uvsvalue"><?php echo $uvsflyerplaceholderurlfield; ?></div> -->
        <?php /* Old: echo wp_kses( $uvsflyerplaceholderurlfield, uvs_allowed_admin_html() ); */ ?>
        <div class="uvsvalue"><?php echo wp_kses( $uvsflyerplaceholderurlfield, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?></div>
        <?php // @Axl End ?>
    </div>
</div>