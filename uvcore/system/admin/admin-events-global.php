<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// $urvenue_ws_eventssourcefield = uvs_get_adminfieldhtml("events->global-source");
// $urvenue_ws_eventssourcefield = urvenue_ws_adm_get_adminfieldhtml("events->global-source"); // Axl UWS-7416
$urvenue_ws_eventssourcefield = urvenue_ws_adm_get_adminfieldhtml("events->global-source"); // Axl UWS-7634
// $urvenue_ws_eventsaddvenuename = uvs_get_adminfieldhtml("events->global-addvenuename");
// $urvenue_ws_eventsaddvenuename = urvenue_ws_adm_get_adminfieldhtml("events->global-addvenuename"); // Axl UWS-7416
$urvenue_ws_eventsaddvenuename = urvenue_ws_adm_get_adminfieldhtml("events->global-addvenuename"); // Axl UWS-7634
// $urvenue_ws_eventsnmontsfield = uvs_get_adminfieldhtml("events->global-nmonths");
// $urvenue_ws_eventsnmontsfield = urvenue_ws_adm_get_adminfieldhtml("events->global-nmonths"); // Axl UWS-7416
$urvenue_ws_eventsnmontsfield = urvenue_ws_adm_get_adminfieldhtml("events->global-nmonths"); // Axl UWS-7634
// $urvenue_ws_eventshidenoflyer = uvs_get_adminfieldhtml("events->global-hidenoflyer");
// $urvenue_ws_eventshidenoflyer = urvenue_ws_adm_get_adminfieldhtml("events->global-hidenoflyer"); // Axl UWS-7416
$urvenue_ws_eventshidenoflyer = urvenue_ws_adm_get_adminfieldhtml("events->global-hidenoflyer"); // Axl UWS-7634
// $urvenue_ws_eventsinitaldate = uvs_get_adminfieldhtml("events->global-initaldate");
// $urvenue_ws_eventsinitaldate = urvenue_ws_adm_get_adminfieldhtml("events->global-initaldate"); // Axl UWS-7416
$urvenue_ws_eventsinitaldate = urvenue_ws_adm_get_adminfieldhtml("events->global-initaldate"); // Axl UWS-7634
// $urvenue_ws_eventsevpagedateselector = uvs_get_adminfieldhtml("events->eventspage-dateselector");
// $urvenue_ws_eventsevpagedateselector = urvenue_ws_adm_get_adminfieldhtml("events->eventspage-dateselector"); // Axl UWS-7416
$urvenue_ws_eventsevpagedateselector = urvenue_ws_adm_get_adminfieldhtml("events->eventspage-dateselector"); // Axl UWS-7634
// $urvenue_ws_eventsnmontsrange = uvs_get_adminfieldhtml("events->eventspage-monthsrange");
// $urvenue_ws_eventsnmontsrange = urvenue_ws_adm_get_adminfieldhtml("events->eventspage-monthsrange"); // Axl UWS-7416
$urvenue_ws_eventsnmontsrange = urvenue_ws_adm_get_adminfieldhtml("events->eventspage-monthsrange"); // Axl UWS-7634
// $urvenue_ws_eventsevpageaddvenuefilter = uvs_get_adminfieldhtml("events->eventspage-addvenuefilter");
// $urvenue_ws_eventsevpageaddvenuefilter = urvenue_ws_adm_get_adminfieldhtml("events->eventspage-addvenuefilter"); // Axl UWS-7416
$urvenue_ws_eventsevpageaddvenuefilter = urvenue_ws_adm_get_adminfieldhtml("events->eventspage-addvenuefilter"); // Axl UWS-7634
// $urvenue_ws_eventsevpageviewmenu = uvs_get_adminfieldhtml("events->eventspage-viewmenu");
// $urvenue_ws_eventsevpageviewmenu = urvenue_ws_adm_get_adminfieldhtml("events->eventspage-viewmenu"); // Axl UWS-7416
$urvenue_ws_eventsevpageviewmenu = urvenue_ws_adm_get_adminfieldhtml("events->eventspage-viewmenu"); // Axl UWS-7634
// $urvenue_ws_eventsevpageaddperformerfilter = uvs_get_adminfieldhtml("events->global-addperformerfilter");
// $urvenue_ws_eventsevpageaddperformerfilter = urvenue_ws_adm_get_adminfieldhtml("events->global-addperformerfilter"); // Axl UWS-7416
$urvenue_ws_eventsevpageaddperformerfilter = urvenue_ws_adm_get_adminfieldhtml("events->global-addperformerfilter"); // Axl UWS-7634
// $urvenue_ws_eventsevpageupdateurl = uvs_get_adminfieldhtml("events->global-updateurl");
// $urvenue_ws_eventsevpageupdateurl = urvenue_ws_adm_get_adminfieldhtml("events->global-updateurl"); // Axl UWS-7416
$urvenue_ws_eventsevpageupdateurl = urvenue_ws_adm_get_adminfieldhtml("events->global-updateurl"); // Axl UWS-7634
// $urvenue_ws_eventsdefeventurl = uvs_get_adminfieldhtml("events->global-defaulteventurl");
// $urvenue_ws_eventsdefeventurl = urvenue_ws_adm_get_adminfieldhtml("events->global-defaulteventurl"); // Axl UWS-7416
$urvenue_ws_eventsdefeventurl = urvenue_ws_adm_get_adminfieldhtml("events->global-defaulteventurl"); // Axl UWS-7634

// $urvenue_ws_eventsviews = uvs_get_eventsviews($urvenue_ws_core_lib["events"]["eventspage-views"]);
// $urvenue_ws_eventsviews = urvenue_ws_adm_get_eventsviews($urvenue_ws_core_lib["events"]["eventspage-views"]); // Axl UWS-7416
$urvenue_ws_eventsviews = urvenue_ws_adm_get_eventsviews($urvenue_ws_core_lib["events"]["eventspage-views"]); // Axl UWS-7634
?>

<div id="uvs-admin-events-global" class="uvs-admin-opt-section <?php /* Old: echo $urvenue_ws_admin_optstabs_state['events-global']; */ echo esc_attr( $urvenue_ws_admin_optstabs_state['events-global'] ); ?>">
	<div class="uvs-admin-opt-title">Global</div>
	<div class="uvs-admin-opt-subtitle">Control global options for your events listings</div>
	<div class="uvs-admin-opt-space"></div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Events Source</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_eventssourcefield; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_eventssourcefield, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_eventssourcefield, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Add Venue Name <small>Add Venue Name on All the events listings</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_eventsaddvenuename; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_eventsaddvenuename, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_eventsaddvenuename, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Number of Months <small>This is the number of months loaded.</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_eventsnmontsfield; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_eventsnmontsfield, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_eventsnmontsfield, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Hide if No Flyer</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_eventshidenoflyer; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_eventshidenoflyer, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_eventshidenoflyer, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Initial Date <small>Initial date for your integrations, if the date is past, the current date is taken</small></div>
		<div class="uvsvalue uvsinitialdatecontainer">
			<!--<input class="uvsjson uvsjs-datepicker" type="text" name="events[global-initaldate]" placeholder="Select a Date" value="">-->
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_eventsinitaldate; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_eventsinitaldate, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_eventsinitaldate, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
			<button class="uvs-btn uvs-btn-p uvsjs-clearinitialdatefield" type="button" data-target=".uvs-clear-initial-date-field">Clear Date</button>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Event Default URL <small>Link to event page or map page as default link</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_eventsdefeventurl; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_eventsdefeventurl, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_eventsdefeventurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
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
			<?php /* Old: echo $urvenue_ws_eventsevpagedateselector; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_eventsevpagedateselector, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_eventsevpagedateselector, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Months Range <small>User is limited to this range of months.</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_eventsnmontsrange; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_eventsnmontsrange, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_eventsnmontsrange, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Add Venue Filter <small>Add venue selector to filters</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_eventsevpageaddvenuefilter; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_eventsevpageaddvenuefilter, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_eventsevpageaddvenuefilter, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Add Performer Filter <small>Add performer selector to filters</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_eventsevpageaddperformerfilter; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_eventsevpageaddperformerfilter, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_eventsevpageaddperformerfilter, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Update URL <small>Update URL with the selected filters</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_eventsevpageupdateurl; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_eventsevpageupdateurl, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_eventsevpageupdateurl, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">View Menu</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_eventsevpageviewmenu; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_eventsevpageviewmenu, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_eventsevpageviewmenu, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
    </div>
	<div class="uvs-admin-opt-subtitle">Events Views</div>
	<div class="uvs-admin-opt-descr">Control the order and the visibility of the different events views, if there is no default view, first enabled view is taken.</div>
	<div class="uvs-admin-opt-space"></div>
	<div class="uvs-admin-listorderandview">
		<?php // @Axl ?>
		<?php /* Old: echo $urvenue_ws_eventsviews; */ ?>
		<?php /* Old: echo wp_kses( $urvenue_ws_eventsviews, uvs_allowed_admin_html() ); */ ?>
		<?php echo wp_kses( $urvenue_ws_eventsviews, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
		<?php // @Axl End ?>
	</div>
</div>