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
<div id="uvs-admin-pages" class="uvs-admin-opt-section <?php /* Old: echo $uvs_admin_optstabs_state['pages']; */ echo esc_attr( $uvs_admin_optstabs_state['pages'] ); ?>">
    <div class="uvs-admin-opt-title">Integration Pages</div>
	<div class="uvs-admin-opt-subtitle">Select the pages for your integrations, this will help for site internal links and redirections</div>
    <div class="uvs-admin-opt-space"></div>

	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Events Page<?php /* Old: echo $uvseventstext; */ echo wp_kses( $uvseventstext, uvs_allowed_admin_html() ); ?></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvspageevents; */ ?>
			<?php echo wp_kses( $uvspageevents, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Event Page<?php /* Old: echo $uvseventtext; */ echo wp_kses( $uvseventtext, uvs_allowed_admin_html() ); ?></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvspagesingleevent; */ ?>
			<?php echo wp_kses( $uvspagesingleevent, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Map Page<?php /* Old: echo $uvsmaptext; */ echo wp_kses( $uvsmaptext, uvs_allowed_admin_html() ); ?></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvspagemap; */ ?>
			<?php echo wp_kses( $uvspagemap, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Inventory Item Page<?php /* Old: echo $uvsitempagetext; */ echo wp_kses( $uvsitempagetext, uvs_allowed_admin_html() ); ?></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvspageitem; */ ?>
			<?php echo wp_kses( $uvspageitem, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Privacy Page</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvspagepriv; */ ?>
			<?php echo wp_kses( $uvspagepriv, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Terms Page</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvspageterms; */ ?>
			<?php echo wp_kses( $uvspageterms, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
</div>