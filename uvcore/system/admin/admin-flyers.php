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

<div id="uvs-admin-flyers" class="uvs-admin-opt-section <?php echo $uvs_admin_optstabs_state['flyers']; ?>">
    <div class="uvs-admin-opt-title">Flyers</div>
    <div class="uvs-admin-opt-descr">Change the Flyer Type and Flyer Ratio for your events on the different events integrations, the order of the items defines the priority.</div>
    <div class="uvs-admin-opt-space"></div>
    <div class="uvs-admin-opt-subtitle">Event Page</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Hide If No Match <small>Hide Flyer if there is no exact match</small></div>
		<div class="uvsvalue">
			<?php echo $uvsflyerevpagehideifnomatch; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php echo $uvsflyerevpageuseplaceholder; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Size Code <small>Default size code of the image</small></div>
		<div class="uvsvalue">
			<?php echo $uvsflyerevpagesizecode; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
		<div class="uvsvalue">
			<?php echo $uvsflyerevpageplaceholderurl; ?>
		</div>
	</div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-event">
        <?php echo $uvsevflyerlocdivhtml; ?>
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
			<?php echo $uvsflyercalendarhideifnomatch; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php echo $uvsflyercalendaruseplaceholder; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php echo $uvsflyercalendarsizecode; ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
		<div class="uvsvalue">
			<?php echo $uvsflyercalendarplaceholderurl; ?>
		</div>
	</div>    
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-cal">
        <?php echo $uvscalflyerlocdivhtml; ?>
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
			<?php echo $uvsflyerlisthideifnomatch; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php echo $uvsflyerlistuseplaceholder; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php echo $uvsflyerlistsizecode; ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php echo $uvsflyerlistplaceholderurl; ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-list">
        <?php echo $uvslisflyerlocdivhtml; ?>
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
			<?php echo $uvsflyersliderhideifnomatch; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php echo $uvsflyerslideruseplaceholder; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php echo $uvsflyerslidersizecode; ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php echo $uvsflyersliderplaceholderurl; ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-slider">
        <?php echo $uvsslflyerlocdivhtml; ?>
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
			<?php echo $uvsflyerslidermobilehideifnomatch; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php echo $uvsflyerslidermobileuseplaceholder; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php echo $uvsflyerslidermobilesizecode; ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php echo $uvsflyerslidermobileplaceholderurl; ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-slidermobile">
        <?php echo $uvsslmobflyerlocdivhtml; ?>
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
			<?php echo $uvsflyersharehideifnomatch; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php echo $uvsflyershareuseplaceholder; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php echo $uvsflyersharesizecode; ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php echo $uvsflyershareplaceholderurl; ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-share">
        <?php echo $uvssrflyerlocdivhtml; ?>
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
			<?php echo $uvsflyercustom1hideifnomatch; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php echo $uvsflyercustom1useplaceholder; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php echo $uvsflyercustom1sizecode; ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php echo $uvsflyercustom1placeholderurl; ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-custom1">
        <?php echo $uvsc1flyerlocdivhtml; ?>
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
			<?php echo $uvsflyercustom2hideifnomatch; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Placeholder <small>Use Placeholder if there is no flyer</small></div>
		<div class="uvsvalue">
			<?php echo $uvsflyercustom2useplaceholder; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Size Code <small>Default size code of the image</small></div>
        <div class="uvsvalue">
            <?php echo $uvsflyercustom2sizecode; ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Placeholder Flyer URL<small>When the flyer is not found the placeholder is shown.</small></div>
        <div class="uvsvalue">
            <?php echo $uvsflyercustom2placeholderurl; ?>
        </div>
    </div>
    <div class="uvs-admin-flyer-loccont uvs-admin-flyer-custom2">
        <?php echo $uvsc2flyerlocdivhtml; ?>
    </div>
    <div class="uvs-admin-flyer-actions">
        <span>Add a new Flyer set to the priority list</span>
        <button class="uvs-btn uvs-btn-p uvsjs-addflyerset" type="button" data-target=".uvs-admin-flyer-custom2">Add New</button>
    </div>
    <div class="uvs-admin-opt-spacedivi"></div>
    <div class="uvs-infolist-item">
        <div class="uvsname">Global Placeholder Flyer URL <small>When an event doesn't have flyer the placeholder is shown.</small></div>
        <div class="uvsvalue"><?php echo $uvsflyerplaceholderurlfield; ?></div>
    </div>
</div>