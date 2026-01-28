<?php

$uvseventssourcefield = uvs_get_adminfieldhtml("events->global-source");
$uvseventsaddvenuename = uvs_get_adminfieldhtml("events->global-addvenuename");
$uvseventsnmontsfield = uvs_get_adminfieldhtml("events->global-nmonths");
$uvseventshidenoflyer = uvs_get_adminfieldhtml("events->global-hidenoflyer");
$uvseventsinitaldate = uvs_get_adminfieldhtml("events->global-initaldate");
$uvseventsevpagedateselector = uvs_get_adminfieldhtml("events->eventspage-dateselector");
$uvseventsnmontsrange = uvs_get_adminfieldhtml("events->eventspage-monthsrange");
$uvseventsevpageaddvenuefilter = uvs_get_adminfieldhtml("events->eventspage-addvenuefilter");
$uvseventsevpageviewmenu = uvs_get_adminfieldhtml("events->eventspage-viewmenu");
$uvseventsevpageaddperformerfilter = uvs_get_adminfieldhtml("events->global-addperformerfilter");
$uvseventsevpageupdateurl = uvs_get_adminfieldhtml("events->global-updateurl");
$uvseventsdefeventurl = uvs_get_adminfieldhtml("events->global-defaulteventurl");

$uvseventsviews = uvs_get_eventsviews($uvs_core_lib["events"]["eventspage-views"]);
?>

<div id="uvs-admin-events-global" class="uvs-admin-opt-section <?php echo $uvs_admin_optstabs_state['events-global']; ?>">
	<div class="uvs-admin-opt-title">Global</div>
	<div class="uvs-admin-opt-subtitle">Control global options for your events listings</div>
	<div class="uvs-admin-opt-space"></div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Events Source</div>
		<div class="uvsvalue">
			<?php echo $uvseventssourcefield; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Add Venue Name <small>Add Venue Name on All the events listings</small></div>
		<div class="uvsvalue">
			<?php echo $uvseventsaddvenuename; ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Number of Months <small>This is the number of months loaded.</small></div>
		<div class="uvsvalue">
			<?php echo $uvseventsnmontsfield; ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Hide if No Flyer</div>
		<div class="uvsvalue">
			<?php echo $uvseventshidenoflyer; ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Initial Date <small>Initial date for your integrations, if the date is past, the current date is taken</small></div>
		<div class="uvsvalue uvsinitialdatecontainer">
			<!--<input class="uvsjson uvsjs-datepicker" type="text" name="events[global-initaldate]" placeholder="Select a Date" value="">-->
			<?php echo $uvseventsinitaldate; ?>
			<button class="uvs-btn uvs-btn-p uvsjs-clearinitialdatefield" type="button" data-target=".uvs-clear-initial-date-field">Clear Date</button>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Event Default URL <small>Link to event page or map page as default link</small></div>
		<div class="uvsvalue">
			<?php echo $uvseventsdefeventurl; ?>
		</div>
	</div>

    <div class="uvs-admin-opt-space"></div>
    <div class="uvs-admin-opt-title">Events Page</div>
    <div class="uvs-admin-opt-subtitle">Your Events Page Main Integration Options</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Date Selector <small>The way the user will control the date on the events listing</small></div>
		<div class="uvsvalue">
			<?php echo $uvseventsevpagedateselector; ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Months Range <small>User is limited to this range of months.</small></div>
		<div class="uvsvalue">
			<?php echo $uvseventsnmontsrange; ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Add Venue Filter <small>Add venue selector to filters</small></div>
		<div class="uvsvalue">
			<?php echo $uvseventsevpageaddvenuefilter; ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Add Performer Filter <small>Add performer selector to filters</small></div>
		<div class="uvsvalue">
			<?php echo $uvseventsevpageaddperformerfilter; ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Update URL <small>Update URL with the selected filters</small></div>
		<div class="uvsvalue">
			<?php echo $uvseventsevpageupdateurl; ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">View Menu</div>
		<div class="uvsvalue">
			<?php echo $uvseventsevpageviewmenu; ?>
		</div>
    </div>
	<div class="uvs-admin-opt-subtitle">Events Views</div>
	<div class="uvs-admin-opt-descr">Control the order and the visibility of the different events views, if there is no default view, first enabled view is taken.</div>
	<div class="uvs-admin-opt-space"></div>
	<div class="uvs-admin-listorderandview">
		<?php echo $uvseventsviews; ?>
	</div>
</div>