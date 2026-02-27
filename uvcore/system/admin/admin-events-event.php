<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//$uvseventseventurl = uvs_get_adminfieldhtml("events->event-url");
//$uvseventsshowartist = uvs_get_adminfieldhtml("events->event-showartist");
$uvseventlayout = uvs_get_adminfieldhtml("events->event-layout");
$uvseventcolumns = uvs_get_adminfieldhtml("events->event-columns");
$uvsactivedropdows = uvs_get_adminfieldhtml("events->event-activedropdowns");

$uvseventshowdigitalmenu = uvs_get_adminfieldhtml("events->addon-bottles->showdigitalmenu");
$uvseventshowsummary     = uvs_get_adminfieldhtml("events->addon-bottles->showsummary");
$uvseventmenuapikey      = uvs_get_adminfieldhtml("events->addon-bottles->menuapikey");
?>
<div id="uvs-admin-events-event" class="uvs-admin-opt-section <?php /* Old: echo $uvs_admin_optstabs_state['events-event']; */ echo esc_attr( $uvs_admin_optstabs_state['events-event'] ); ?>">
    <div class="uvs-admin-opt-title">Event Page</div>
    <div class="uvs-admin-opt-space"></div>
    <!--<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Event URL</div>
		<div class="uvsvalue">
			<?php //echo $uvseventseventurl; ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Show Artist <small>If event is linked to an artist</small></div>
		<div class="uvsvalue">
			<?php //echo $uvseventsshowartist; ?>
		</div>
    </div>-->
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Event Layout 
			<small>
				<strong>Container:</strong> Requires to place the event shortcode on a inner container
				<br><br>
				<strong>Full:</strong> Requires to plece the event shortcode on a 100% page width container with not paddings
			</small>
		</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvseventlayout; */ ?>
			<?php echo wp_kses( $uvseventlayout, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
    </div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Event Columns <small>Select the event page columns placement</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvseventcolumns; */ ?>
			<?php echo wp_kses( $uvseventcolumns, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
    </div>
	<div class="uvs-infolist-item uvs-clearfix uvs-no-bb">
		<div class="uvsname">Auto Open Tabs/Dropdowns <small>Will show the event tabs/items info by default.</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsactivedropdows; */ ?>
			<?php echo wp_kses( $uvsactivedropdows, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>

	<div class="uvs-admin-opt-spacedivi"></div>
	<div class="uvs-admin-opt-title">Add-Ons</div>
	<div class="uvs-admin-opt-subtitle uvs-add-ons-subtitle">Digital Menu</div>

	<div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Show Digital Menu</div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvseventshowdigitalmenu; */ ?>
            <?php echo wp_kses( $uvseventshowdigitalmenu, uvs_allowed_admin_html() ); ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Show Summary</div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvseventshowsummary; */ ?>
            <?php echo wp_kses( $uvseventshowsummary, uvs_allowed_admin_html() ); ?>
            <?php // @Axl End ?>
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
        <div class="uvsname">Menu API Key 
            <small>If empty, it will use the default key from the libs</small>
        </div>
        <div class="uvsvalue">
            <?php // @Axl ?>
            <?php /* Old: echo $uvseventmenuapikey; */ ?>
            <?php echo wp_kses( $uvseventmenuapikey, uvs_allowed_admin_html() ); ?>
            <?php // @Axl End ?>
        </div>
    </div>
</div>