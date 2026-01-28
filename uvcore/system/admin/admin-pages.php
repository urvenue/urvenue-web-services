<?php
$uvspageevents = uvs_get_adminfieldhtml("pages->events");
$uvspagesingleevent = uvs_get_adminfieldhtml("pages->singleevent");
$uvspagemap = uvs_get_adminfieldhtml("pages->map");
$uvspageitem = uvs_get_adminfieldhtml("pages->itempage");
$uvspagepriv = uvs_get_adminfieldhtml("pages->privacy");
$uvspageterms = uvs_get_adminfieldhtml("pages->terms");

$uvseventstext = (uvs_is_wordpress()) ? " <small>Shortcode: <strong>[uws_events]</strong></small>" : "";
$uvseventtext = (uvs_is_wordpress()) ? " <small>Shortcode: <strong>[uws_event]</strong></small>" : "";
$uvsmaptext = (uvs_is_wordpress()) ? " <small>Shortcode: <strong>[uws_map]</strong></small>" : "";
$uvsitempagetext = (uvs_is_wordpress()) ? " <small>Shortcode: <strong>[uws_inventory_item_page]</strong></small>" : "";
?>
<div id="uvs-admin-pages" class="uvs-admin-opt-section <?php echo $uvs_admin_optstabs_state['pages']; ?>">
    <div class="uvs-admin-opt-title">Integration Pages</div>
	<div class="uvs-admin-opt-subtitle">Select the pages for your integrations, this will help for site internal links and redirections</div>
    <div class="uvs-admin-opt-space"></div>

	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Events Page<?php echo $uvseventstext; ?></div>
		<div class="uvsvalue">
			<?php echo $uvspageevents; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Event Page<?php echo $uvseventtext; ?></div>
		<div class="uvsvalue">
			<?php echo $uvspagesingleevent; ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Map Page<?php echo $uvsmaptext; ?></div>
		<div class="uvsvalue">
			<?php echo $uvspagemap; ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Inventory Item Page<?php echo $uvsitempagetext; ?></div>
		<div class="uvsvalue">
			<?php echo $uvspageitem; ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Privacy Page</div>
		<div class="uvsvalue">
			<?php echo $uvspagepriv; ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Terms Page</div>
		<div class="uvsvalue">
			<?php echo $uvspageterms; ?>
		</div>
	</div>
</div>