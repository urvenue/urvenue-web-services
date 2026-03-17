<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// $uvseventssourcefield = uvs_get_adminfieldhtml("events->global-source");
$uvseventssourcefield = urvenue_ws_adm_get_adminfieldhtml("events->global-source"); // Axl UWS-7416
// $uvseventsaddvenuename = uvs_get_adminfieldhtml("events->global-addvenuename");
$uvseventsaddvenuename = urvenue_ws_adm_get_adminfieldhtml("events->global-addvenuename"); // Axl UWS-7416
// $uvseventsnmontsfield = uvs_get_adminfieldhtml("events->global-nmonths");
$uvseventsnmontsfield = urvenue_ws_adm_get_adminfieldhtml("events->global-nmonths"); // Axl UWS-7416
// $uvseventshidenoflyer = uvs_get_adminfieldhtml("events->global-hidenoflyer");
$uvseventshidenoflyer = urvenue_ws_adm_get_adminfieldhtml("events->global-hidenoflyer"); // Axl UWS-7416
// $uvseventsinitaldate = uvs_get_adminfieldhtml("events->global-initaldate");
$uvseventsinitaldate = urvenue_ws_adm_get_adminfieldhtml("events->global-initaldate"); // Axl UWS-7416
// $uvseventsevpagedateselector = uvs_get_adminfieldhtml("events->eventspage-dateselector");
$uvseventsevpagedateselector = urvenue_ws_adm_get_adminfieldhtml("events->eventspage-dateselector"); // Axl UWS-7416
// $uvseventsnmontsrange = uvs_get_adminfieldhtml("events->eventspage-monthsrange");
$uvseventsnmontsrange = urvenue_ws_adm_get_adminfieldhtml("events->eventspage-monthsrange"); // Axl UWS-7416
// $uvseventsevpageaddvenuefilter = uvs_get_adminfieldhtml("events->eventspage-addvenuefilter");
$uvseventsevpageaddvenuefilter = urvenue_ws_adm_get_adminfieldhtml("events->eventspage-addvenuefilter"); // Axl UWS-7416
// $uvseventsevpageviewmenu = uvs_get_adminfieldhtml("events->eventspage-viewmenu");
$uvseventsevpageviewmenu = urvenue_ws_adm_get_adminfieldhtml("events->eventspage-viewmenu"); // Axl UWS-7416
// $uvseventsevpageaddperformerfilter = uvs_get_adminfieldhtml("events->global-addperformerfilter");
$uvseventsevpageaddperformerfilter = urvenue_ws_adm_get_adminfieldhtml("events->global-addperformerfilter"); // Axl UWS-7416
// $uvseventsevpageupdateurl = uvs_get_adminfieldhtml("events->global-updateurl");
$uvseventsevpageupdateurl = urvenue_ws_adm_get_adminfieldhtml("events->global-updateurl"); // Axl UWS-7416
// $uvseventsdefeventurl = uvs_get_adminfieldhtml("events->global-defaulteventurl");
$uvseventsdefeventurl = urvenue_ws_adm_get_adminfieldhtml("events->global-defaulteventurl"); // Axl UWS-7416

// $uvseventsviews = uvs_get_eventsviews($urvenue_ws_core_lib["events"]["eventspage-views"]);
$uvseventsviews = urvenue_ws_adm_get_eventsviews($urvenue_ws_core_lib["events"]["eventspage-views"]); // Axl UWS-7416
?>

<div id="uvs-admin-events-global" class="uvs-admin-opt-section <?php /* Old: echo $uvs_admin_optstabs_state['events-global']; */ echo esc_attr( $uvs_admin_optstabs_state['events-global'] ); ?>">
	<div class="uvs-admin-opt-title">Global</div>
	<div class="uvs-admin-opt-subtitle">Control global options for your events listings</div>
	<div class="uvs-admin-opt-space"></div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Events Source</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvseventssourcefield; */ ?>
			<?php /* Old: echo wp_kses( $uvseventssourcefield, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvseventssourcefield, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Add Venue Name <small>Add Venue Name on All the events listings</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvseventsaddvenuename; */ ?>
			<?php /* Old: echo wp_kses( $uvseventsaddvenuename, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvseventsaddvenuename, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Number of Months <small>This is the number of months loaded.</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvseventsnmontsfield; */ ?>
			<?php /* Old: echo wp_kses( $uvseventsnmontsfield, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvseventsnmontsfield, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Hide if No Flyer</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvseventshidenoflyer; */ ?>
			<?php /* Old: echo wp_kses( $uvseventshidenoflyer, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvseventshidenoflyer, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Initial Date <small>Initial date for your integrations, if the date is past, the current date is taken</small></div>
		<div class="uvsvalue uvsinitialdatecontainer">
			<!--<input class="uvsjson uvsjs-datepicker" type="text" name="events[global-initaldate]" placeholder="Select a Date" value="">-->
			<?php // @Axl ?>
			<?php /* Old: echo $uvseventsinitaldate; */ ?>
			<?php /* Old: echo wp_kses( $uvseventsinitaldate, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvseventsinitaldate, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
			<button class="uvs-btn uvs-btn-p uvsjs-clearinitialdatefield" type="button" data-target=".uvs-clear-initial-date-field">Clear Date</button>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Event Default URL <small>Link to event page or map page as default link</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvseventsdefeventurl; */ ?>
			<?php /* Old: echo wp_kses( $uvseventsdefeventurl, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvseventsdefeventurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>

    <div class="uvs-admin-opt-space"></div>
    <div class="uvs-admin-opt-title">Events Page</div>
    <div class="uvs-admin-opt-subtitle">Your Events Page Main Integration Options</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Date Selector <small>The way the user will control the date on the events listing</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvseventsevpagedateselector; */ ?>
			<?php /* Old: echo wp_kses( $uvseventsevpagedateselector, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvseventsevpagedateselector, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Months Range <small>User is limited to this range of months.</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvseventsnmontsrange; */ ?>
			<?php /* Old: echo wp_kses( $uvseventsnmontsrange, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvseventsnmontsrange, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Add Venue Filter <small>Add venue selector to filters</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvseventsevpageaddvenuefilter; */ ?>
			<?php /* Old: echo wp_kses( $uvseventsevpageaddvenuefilter, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvseventsevpageaddvenuefilter, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Add Performer Filter <small>Add performer selector to filters</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvseventsevpageaddperformerfilter; */ ?>
			<?php /* Old: echo wp_kses( $uvseventsevpageaddperformerfilter, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvseventsevpageaddperformerfilter, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Update URL <small>Update URL with the selected filters</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvseventsevpageupdateurl; */ ?>
			<?php /* Old: echo wp_kses( $uvseventsevpageupdateurl, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvseventsevpageupdateurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">View Menu</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvseventsevpageviewmenu; */ ?>
			<?php /* Old: echo wp_kses( $uvseventsevpageviewmenu, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvseventsevpageviewmenu, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
	<div class="uvs-admin-opt-subtitle">Events Views</div>
	<div class="uvs-admin-opt-descr">Control the order and the visibility of the different events views, if there is no default view, first enabled view is taken.</div>
	<div class="uvs-admin-opt-space"></div>
	<div class="uvs-admin-listorderandview">
		<?php // @Axl ?>
		<?php /* Old: echo $uvseventsviews; */ ?>
		<?php /* Old: echo wp_kses( $uvseventsviews, uvs_allowed_admin_html() ); */ ?>
		<?php echo wp_kses( $uvseventsviews, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
		<?php // @Axl End ?>
	</div>
</div>