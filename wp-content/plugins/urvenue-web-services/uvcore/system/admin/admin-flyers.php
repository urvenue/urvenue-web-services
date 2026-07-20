<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_core_lib;

$urvenue_ws_evflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("eventpage");
$urvenue_ws_calflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("calendar");
$urvenue_ws_lisflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("list");
$urvenue_ws_slflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("slider");
$urvenue_ws_slmobflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("slidermobile");
$urvenue_ws_srflyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("share");
$urvenue_ws_c1flyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("custom1");
$urvenue_ws_c2flyerlocdivhtml = urvenue_ws_adm_get_flyerlocdivhtml("custom2");

$urvenue_ws_flyerplaceholderurlfield = urvenue_ws_adm_get_adminfieldhtml("flyers->placeholderurl");
$urvenue_ws_flyerevpagehideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->eventpage-hideifnomatch");
$urvenue_ws_flyerevpageuseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->eventpage-useplaceholder");
$urvenue_ws_flyerevpagesizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->eventpage-sizecode");
$urvenue_ws_flyerevpageplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->eventpage-placeholderurl");
$urvenue_ws_flyercalendarhideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->calendar-hideifnomatch");
$urvenue_ws_flyercalendaruseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->calendar-useplaceholder");
$urvenue_ws_flyercalendarsizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->calendar-sizecode");
$urvenue_ws_flyercalendarplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->calendar-placeholderurl");
$urvenue_ws_flyerlisthideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->list-hideifnomatch");
$urvenue_ws_flyerlistuseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->list-useplaceholder");
$urvenue_ws_flyerlistsizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->list-sizecode");
$urvenue_ws_flyerlistplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->list-placeholderurl");
$urvenue_ws_flyersliderhideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->slider-hideifnomatch");
$urvenue_ws_flyerslideruseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->slider-useplaceholder");
$urvenue_ws_flyerslidersizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->slider-sizecode");
$urvenue_ws_flyersliderplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->slider-placeholderurl");
$urvenue_ws_flyerslidermobilehideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->slidermobile-hideifnomatch");
$urvenue_ws_flyerslidermobileuseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->slidermobile-useplaceholder");
$urvenue_ws_flyerslidermobilesizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->slidermobile-sizecode");
$urvenue_ws_flyerslidermobileplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->slidermobile-placeholderurl");
$urvenue_ws_flyersharehideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->share-hideifnomatch");
$urvenue_ws_flyershareuseplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->share-useplaceholder");
$urvenue_ws_flyersharesizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->share-sizecode");
$urvenue_ws_flyershareplaceholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->share-placeholderurl");
$urvenue_ws_flyercustom1hideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->custom1-hideifnomatch");
$urvenue_ws_flyercustom1useplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->custom1-useplaceholder");
$urvenue_ws_flyercustom1sizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->custom1-sizecode");
$urvenue_ws_flyercustom1placeholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->custom1-placeholderurl");

$urvenue_ws_flyercustom2hideifnomatch = urvenue_ws_adm_get_adminfieldhtml("flyers->custom2-hideifnomatch");
$urvenue_ws_flyercustom2useplaceholder = urvenue_ws_adm_get_adminfieldhtml("flyers->custom2-useplaceholder");
$urvenue_ws_flyercustom2sizecode = urvenue_ws_adm_get_adminfieldhtml("flyers->custom2-sizecode");
$urvenue_ws_flyercustom2placeholderurl = urvenue_ws_adm_get_adminfieldhtml("flyers->custom2-placeholderurl");
?>

<div id="uvs-admin-flyers" class="uvs-admin-opt-section <?php echo esc_attr( $urvenue_ws_admin_optstabs_state['flyers'] ); ?>">
    <div class="uvs-admin-opt-title">Flyers</div>
    <div class="uvs-admin-opt-descr">Change the Flyer Type and Flyer Ratio for your events on the different events integrations, the order of the items defines the priority.</div>
    <div class="uvs-admin-opt-space"></div>
    <div class="uvs-admin-opt-subtitle">Event Page</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Hide If No Match <small>Hide Flyer if there is no exact match</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_flyerevpagehideifnomatch, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_flyerevpageuseplaceholder, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Size Code <small>Default size code of the image</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_flyerevpagesizecode, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_flyerevpageplaceholderurl, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-event">
        <?php echo wp_kses( $urvenue_ws_evflyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() ); ?>
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
			<?php echo wp_kses( $urvenue_ws_flyercalendarhideifnomatch, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_flyercalendaruseplaceholder, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php echo wp_kses( $urvenue_ws_flyercalendarsizecode, urvenue_ws_adm_allowed_admin_html() ); ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_flyercalendarplaceholderurl, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>    
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-cal">
        <?php echo wp_kses( $urvenue_ws_calflyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() ); ?>
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
			<?php echo wp_kses( $urvenue_ws_flyerlisthideifnomatch, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_flyerlistuseplaceholder, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php echo wp_kses( $urvenue_ws_flyerlistsizecode, urvenue_ws_adm_allowed_admin_html() ); ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php echo wp_kses( $urvenue_ws_flyerlistplaceholderurl, urvenue_ws_adm_allowed_admin_html() ); ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-list">
        <?php echo wp_kses( $urvenue_ws_lisflyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() ); ?>
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
			<?php echo wp_kses( $urvenue_ws_flyersliderhideifnomatch, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_flyerslideruseplaceholder, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php echo wp_kses( $urvenue_ws_flyerslidersizecode, urvenue_ws_adm_allowed_admin_html() ); ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php echo wp_kses( $urvenue_ws_flyersliderplaceholderurl, urvenue_ws_adm_allowed_admin_html() ); ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-slider">
        <?php echo wp_kses( $urvenue_ws_slflyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() ); ?>
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
			<?php echo wp_kses( $urvenue_ws_flyerslidermobilehideifnomatch, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_flyerslidermobileuseplaceholder, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php echo wp_kses( $urvenue_ws_flyerslidermobilesizecode, urvenue_ws_adm_allowed_admin_html() ); ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php echo wp_kses( $urvenue_ws_flyerslidermobileplaceholderurl, urvenue_ws_adm_allowed_admin_html() ); ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-slidermobile">
        <?php echo wp_kses( $urvenue_ws_slmobflyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() ); ?>
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
			<?php echo wp_kses( $urvenue_ws_flyersharehideifnomatch, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_flyershareuseplaceholder, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php echo wp_kses( $urvenue_ws_flyersharesizecode, urvenue_ws_adm_allowed_admin_html() ); ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php echo wp_kses( $urvenue_ws_flyershareplaceholderurl, urvenue_ws_adm_allowed_admin_html() ); ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-share">
        <?php echo wp_kses( $urvenue_ws_srflyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() ); ?>
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
			<?php echo wp_kses( $urvenue_ws_flyercustom1hideifnomatch, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_flyercustom1useplaceholder, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php echo wp_kses( $urvenue_ws_flyercustom1sizecode, urvenue_ws_adm_allowed_admin_html() ); ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php echo wp_kses( $urvenue_ws_flyercustom1placeholderurl, urvenue_ws_adm_allowed_admin_html() ); ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-custom1">
        <?php echo wp_kses( $urvenue_ws_c1flyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() ); ?>
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
			<?php echo wp_kses( $urvenue_ws_flyercustom2hideifnomatch, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_flyercustom2useplaceholder, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php echo wp_kses( $urvenue_ws_flyercustom2sizecode, urvenue_ws_adm_allowed_admin_html() ); ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php echo wp_kses( $urvenue_ws_flyercustom2placeholderurl, urvenue_ws_adm_allowed_admin_html() ); ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-custom2">
        <?php echo wp_kses( $urvenue_ws_c2flyerlocdivhtml, urvenue_ws_adm_allowed_admin_html() ); ?>
    </div>
    <div class="uvs-admin-flyer-actions">
        <span>Add a new Flyer set to the priority list</span>
        <button class="uvs-btn uvs-btn-p uvsjs-addflyerset" type="button" data-target=".uvs-admin-flyer-custom2">Add New</button>
    </div>
    <div class="uvs-admin-opt-spacedivi"></div>
    <div class="uvs-infolist-item">
        <div class="uvsname">Global Placeholder Flyer URL <small>When an event doesn't have flyer the placeholder is shown.</small></div>
        <div class="uvsvalue"><?php echo wp_kses( $urvenue_ws_flyerplaceholderurlfield, urvenue_ws_adm_allowed_admin_html() ); ?></div>
    </div>
</div>