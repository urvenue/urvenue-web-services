<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$urvenue_ws_pageevents = urvenue_ws_adm_get_adminfieldhtml("pages->events");
$urvenue_ws_pagesingleevent = urvenue_ws_adm_get_adminfieldhtml("pages->singleevent");
$urvenue_ws_pagemap = urvenue_ws_adm_get_adminfieldhtml("pages->map");
$urvenue_ws_pageitem = urvenue_ws_adm_get_adminfieldhtml("pages->itempage");
$urvenue_ws_pagepriv = urvenue_ws_adm_get_adminfieldhtml("pages->privacy");
$urvenue_ws_pageterms = urvenue_ws_adm_get_adminfieldhtml("pages->terms");

$urvenue_ws_eventstext = (urvenue_ws_adm_is_wordpress()) ? " <small>Shortcode: <strong>[urvenue_ws_events]</strong></small>" : "";
$urvenue_ws_eventtext = (urvenue_ws_adm_is_wordpress()) ? " <small>Shortcode: <strong>[urvenue_ws_event]</strong></small>" : "";
$urvenue_ws_maptext = (urvenue_ws_adm_is_wordpress()) ? " <small>Shortcode: <strong>[urvenue_ws_map]</strong></small>" : "";
$urvenue_ws_itempagetext = (urvenue_ws_adm_is_wordpress()) ? " <small>Shortcode: <strong>[urvenue_ws_inventory_item_page]</strong></small>" : "";
?>
<div id="uvs-admin-pages" class="uvs-admin-opt-section <?php echo esc_attr( $urvenue_ws_admin_optstabs_state['pages'] ); ?>">
    <div class="uvs-admin-opt-title">Integration Pages</div>
	<div class="uvs-admin-opt-subtitle">Select the pages for your integrations, this will help for site internal links and redirections</div>
    <div class="uvs-admin-opt-space"></div>

	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Events Page<?php echo wp_kses( $urvenue_ws_eventstext, urvenue_ws_adm_allowed_admin_html() ); ?></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_pageevents, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Event Page<?php echo wp_kses( $urvenue_ws_eventtext, urvenue_ws_adm_allowed_admin_html() ); ?></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_pagesingleevent, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Map Page<?php echo wp_kses( $urvenue_ws_maptext, urvenue_ws_adm_allowed_admin_html() ); ?></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_pagemap, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Inventory Item Page<?php echo wp_kses( $urvenue_ws_itempagetext, urvenue_ws_adm_allowed_admin_html() ); ?></div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_pageitem, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Privacy Page</div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_pagepriv, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Terms Page</div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_pageterms, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
	</div>
</div>