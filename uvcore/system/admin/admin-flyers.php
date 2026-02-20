<?php

global $uws_core_lib;

$uvsevflyerlocdivhtml = uvs_get_flyerlocdivhtml("eventpage");
$uvscalflyerlocdivhtml = uvs_get_flyerlocdivhtml("calendar");
$uvslisflyerlocdivhtml = uvs_get_flyerlocdivhtml("list");
$uvsslflyerlocdivhtml = uvs_get_flyerlocdivhtml("slider");
$uvsslmobflyerlocdivhtml = uvs_get_flyerlocdivhtml("slidermobile");
$uvssrflyerlocdivhtml = uvs_get_flyerlocdivhtml("share");
$uvsc1flyerlocdivhtml = uvs_get_flyerlocdivhtml("custom1");
$uvsc2flyerlocdivhtml = uvs_get_flyerlocdivhtml("custom2");

$uvsflyerplaceholderurlfield = uvs_get_adminfieldhtml("flyers->placeholderurl");
$uvsflyerevpagehideifnomatch = uvs_get_adminfieldhtml("flyers->eventpage-hideifnomatch");
$uvsflyerevpageuseplaceholder = uvs_get_adminfieldhtml("flyers->eventpage-useplaceholder");
$uvsflyerevpagesizecode = uvs_get_adminfieldhtml("flyers->eventpage-sizecode");
$uvsflyerevpageplaceholderurl = uvs_get_adminfieldhtml("flyers->eventpage-placeholderurl");
$uvsflyercalendarhideifnomatch = uvs_get_adminfieldhtml("flyers->calendar-hideifnomatch");
$uvsflyercalendaruseplaceholder = uvs_get_adminfieldhtml("flyers->calendar-useplaceholder");
$uvsflyercalendarsizecode = uvs_get_adminfieldhtml("flyers->calendar-sizecode");
$uvsflyercalendarplaceholderurl = uvs_get_adminfieldhtml("flyers->calendar-placeholderurl");
$uvsflyerlisthideifnomatch = uvs_get_adminfieldhtml("flyers->list-hideifnomatch");
$uvsflyerlistuseplaceholder = uvs_get_adminfieldhtml("flyers->list-useplaceholder");
$uvsflyerlistsizecode = uvs_get_adminfieldhtml("flyers->list-sizecode");
$uvsflyerlistplaceholderurl = uvs_get_adminfieldhtml("flyers->list-placeholderurl");
$uvsflyersliderhideifnomatch = uvs_get_adminfieldhtml("flyers->slider-hideifnomatch");
$uvsflyerslideruseplaceholder = uvs_get_adminfieldhtml("flyers->slider-useplaceholder");
$uvsflyerslidersizecode = uvs_get_adminfieldhtml("flyers->slider-sizecode");
$uvsflyersliderplaceholderurl = uvs_get_adminfieldhtml("flyers->slider-placeholderurl");
$uvsflyerslidermobilehideifnomatch = uvs_get_adminfieldhtml("flyers->slidermobile-hideifnomatch");
$uvsflyerslidermobileuseplaceholder = uvs_get_adminfieldhtml("flyers->slidermobile-useplaceholder");
$uvsflyerslidermobilesizecode = uvs_get_adminfieldhtml("flyers->slidermobile-sizecode");
$uvsflyerslidermobileplaceholderurl = uvs_get_adminfieldhtml("flyers->slidermobile-placeholderurl");
$uvsflyersharehideifnomatch = uvs_get_adminfieldhtml("flyers->share-hideifnomatch");
$uvsflyershareuseplaceholder = uvs_get_adminfieldhtml("flyers->share-useplaceholder");
$uvsflyersharesizecode = uvs_get_adminfieldhtml("flyers->share-sizecode");
$uvsflyershareplaceholderurl = uvs_get_adminfieldhtml("flyers->share-placeholderurl");
$uvsflyercustom1hideifnomatch = uvs_get_adminfieldhtml("flyers->custom1-hideifnomatch");
$uvsflyercustom1useplaceholder = uvs_get_adminfieldhtml("flyers->custom1-useplaceholder");
$uvsflyercustom1sizecode = uvs_get_adminfieldhtml("flyers->custom1-sizecode");
$uvsflyercustom1placeholderurl = uvs_get_adminfieldhtml("flyers->custom1-placeholderurl");

$uvsflyercustom2hideifnomatch = uvs_get_adminfieldhtml("flyers->custom2-hideifnomatch");
$uvsflyercustom2useplaceholder = uvs_get_adminfieldhtml("flyers->custom2-useplaceholder");
$uvsflyercustom2sizecode = uvs_get_adminfieldhtml("flyers->custom2-sizecode");
$uvsflyercustom2placeholderurl = uvs_get_adminfieldhtml("flyers->custom2-placeholderurl");
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
			<?php echo wp_kses( $uvsflyerevpagehideifnomatch, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyerevpageuseplaceholder; */ ?>
			<?php echo wp_kses( $uvsflyerevpageuseplaceholder, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Size Code <small>Default size code of the image</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyerevpagesizecode; */ ?>
			<?php echo wp_kses( $uvsflyerevpagesizecode, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyerevpageplaceholderurl; */ ?>
			<?php echo wp_kses( $uvsflyerevpageplaceholderurl, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-event">
        <?php // @Axl ?>
        <?php /* Old: echo $uvsevflyerlocdivhtml; */ ?>
        <?php echo wp_kses( $uvsevflyerlocdivhtml, uvs_allowed_admin_html() ); ?>
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
			<?php echo wp_kses( $uvsflyercalendarhideifnomatch, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyercalendaruseplaceholder; */ ?>
			<?php echo wp_kses( $uvsflyercalendaruseplaceholder, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyercalendarsizecode; */ ?>
            <?php echo wp_kses( $uvsflyercalendarsizecode, uvs_allowed_admin_html() ); ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyercalendarplaceholderurl; */ ?>
			<?php echo wp_kses( $uvsflyercalendarplaceholderurl, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>    
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-cal">
        <?php // @Axl ?>
        <?php /* Old: echo $uvscalflyerlocdivhtml; */ ?>
        <?php echo wp_kses( $uvscalflyerlocdivhtml, uvs_allowed_admin_html() ); ?>
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
			<?php echo wp_kses( $uvsflyerlisthideifnomatch, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyerlistuseplaceholder; */ ?>
			<?php echo wp_kses( $uvsflyerlistuseplaceholder, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyerlistsizecode; */ ?>
            <?php echo wp_kses( $uvsflyerlistsizecode, uvs_allowed_admin_html() ); ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyerlistplaceholderurl; */ ?>
            <?php echo wp_kses( $uvsflyerlistplaceholderurl, uvs_allowed_admin_html() ); ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-list">
        <?php // @Axl ?>
        <?php /* Old: echo $uvslisflyerlocdivhtml; */ ?>
        <?php echo wp_kses( $uvslisflyerlocdivhtml, uvs_allowed_admin_html() ); ?>
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
			<?php echo wp_kses( $uvsflyersliderhideifnomatch, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyerslideruseplaceholder; */ ?>
			<?php echo wp_kses( $uvsflyerslideruseplaceholder, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyerslidersizecode; */ ?>
            <?php echo wp_kses( $uvsflyerslidersizecode, uvs_allowed_admin_html() ); ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyersliderplaceholderurl; */ ?>
            <?php echo wp_kses( $uvsflyersliderplaceholderurl, uvs_allowed_admin_html() ); ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-slider">
        <?php // @Axl ?>
        <?php /* Old: echo $uvsslflyerlocdivhtml; */ ?>
        <?php echo wp_kses( $uvsslflyerlocdivhtml, uvs_allowed_admin_html() ); ?>
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
			<?php echo wp_kses( $uvsflyerslidermobilehideifnomatch, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyerslidermobileuseplaceholder; */ ?>
			<?php echo wp_kses( $uvsflyerslidermobileuseplaceholder, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyerslidermobilesizecode; */ ?>
            <?php echo wp_kses( $uvsflyerslidermobilesizecode, uvs_allowed_admin_html() ); ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyerslidermobileplaceholderurl; */ ?>
            <?php echo wp_kses( $uvsflyerslidermobileplaceholderurl, uvs_allowed_admin_html() ); ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-slidermobile">
        <?php // @Axl ?>
        <?php /* Old: echo $uvsslmobflyerlocdivhtml; */ ?>
        <?php echo wp_kses( $uvsslmobflyerlocdivhtml, uvs_allowed_admin_html() ); ?>
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
			<?php echo wp_kses( $uvsflyersharehideifnomatch, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyershareuseplaceholder; */ ?>
			<?php echo wp_kses( $uvsflyershareuseplaceholder, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyersharesizecode; */ ?>
            <?php echo wp_kses( $uvsflyersharesizecode, uvs_allowed_admin_html() ); ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyershareplaceholderurl; */ ?>
            <?php echo wp_kses( $uvsflyershareplaceholderurl, uvs_allowed_admin_html() ); ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-share">
        <?php // @Axl ?>
        <?php /* Old: echo $uvssrflyerlocdivhtml; */ ?>
        <?php echo wp_kses( $uvssrflyerlocdivhtml, uvs_allowed_admin_html() ); ?>
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
			<?php echo wp_kses( $uvsflyercustom1hideifnomatch, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyercustom1useplaceholder; */ ?>
			<?php echo wp_kses( $uvsflyercustom1useplaceholder, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyercustom1sizecode; */ ?>
            <?php echo wp_kses( $uvsflyercustom1sizecode, uvs_allowed_admin_html() ); ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyercustom1placeholderurl; */ ?>
            <?php echo wp_kses( $uvsflyercustom1placeholderurl, uvs_allowed_admin_html() ); ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-custom1">
        <?php // @Axl ?>
        <?php /* Old: echo $uvsc1flyerlocdivhtml; */ ?>
        <?php echo wp_kses( $uvsc1flyerlocdivhtml, uvs_allowed_admin_html() ); ?>
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
			<?php echo wp_kses( $uvsflyercustom2hideifnomatch, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsflyercustom2useplaceholder; */ ?>
			<?php echo wp_kses( $uvsflyercustom2useplaceholder, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyercustom2sizecode; */ ?>
            <?php echo wp_kses( $uvsflyercustom2sizecode, uvs_allowed_admin_html() ); ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvsflyercustom2placeholderurl; */ ?>
            <?php echo wp_kses( $uvsflyercustom2placeholderurl, uvs_allowed_admin_html() ); ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-custom2">
        <?php // @Axl ?>
        <?php /* Old: echo $uvsc2flyerlocdivhtml; */ ?>
        <?php echo wp_kses( $uvsc2flyerlocdivhtml, uvs_allowed_admin_html() ); ?>
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
        <div class="uvsvalue"><?php echo wp_kses( $uvsflyerplaceholderurlfield, uvs_allowed_admin_html() ); ?></div>
        <?php // @Axl End ?>
    </div>
</div>