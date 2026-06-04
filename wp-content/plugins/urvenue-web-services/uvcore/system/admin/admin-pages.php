<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// $urvenue_ws_pageevents = uvs_get_adminfieldhtml("pages->events");
// $urvenue_ws_pageevents = urvenue_ws_adm_get_adminfieldhtml("pages->events"); // Axl UWS-7416
$urvenue_ws_pageevents = urvenue_ws_adm_get_adminfieldhtml("pages->events"); // Axl UWS-7634
// $urvenue_ws_pagesingleevent = uvs_get_adminfieldhtml("pages->singleevent");
// $urvenue_ws_pagesingleevent = urvenue_ws_adm_get_adminfieldhtml("pages->singleevent"); // Axl UWS-7416
$urvenue_ws_pagesingleevent = urvenue_ws_adm_get_adminfieldhtml("pages->singleevent"); // Axl UWS-7634
// $urvenue_ws_pagemap = uvs_get_adminfieldhtml("pages->map");
// $urvenue_ws_pagemap = urvenue_ws_adm_get_adminfieldhtml("pages->map"); // Axl UWS-7416
$urvenue_ws_pagemap = urvenue_ws_adm_get_adminfieldhtml("pages->map"); // Axl UWS-7634
// $urvenue_ws_pageitem = uvs_get_adminfieldhtml("pages->itempage");
// $urvenue_ws_pageitem = urvenue_ws_adm_get_adminfieldhtml("pages->itempage"); // Axl UWS-7416
$urvenue_ws_pageitem = urvenue_ws_adm_get_adminfieldhtml("pages->itempage"); // Axl UWS-7634
// $urvenue_ws_pagepriv = uvs_get_adminfieldhtml("pages->privacy");
// $urvenue_ws_pagepriv = urvenue_ws_adm_get_adminfieldhtml("pages->privacy"); // Axl UWS-7416
$urvenue_ws_pagepriv = urvenue_ws_adm_get_adminfieldhtml("pages->privacy"); // Axl UWS-7634
// $urvenue_ws_pageterms = uvs_get_adminfieldhtml("pages->terms");
// $urvenue_ws_pageterms = urvenue_ws_adm_get_adminfieldhtml("pages->terms"); // Axl UWS-7416
$urvenue_ws_pageterms = urvenue_ws_adm_get_adminfieldhtml("pages->terms"); // Axl UWS-7634

// $urvenue_ws_eventstext = (uvs_is_wordpress()) ? " <small>Shortcode: <strong>[urvenue_ws_events]</strong></small>" : "";
// $urvenue_ws_eventstext = (urvenue_ws_adm_is_wordpress()) ? " <small>Shortcode: <strong>[urvenue_ws_events]</strong></small>" : ""; // Axl UWS-7416
$urvenue_ws_eventstext = (urvenue_ws_adm_is_wordpress()) ? " <small>Shortcode: <strong>[urvenue_ws_events]</strong></small>" : ""; // Axl UWS-7634
// $urvenue_ws_eventtext = (uvs_is_wordpress()) ? " <small>Shortcode: <strong>[urvenue_ws_event]</strong></small>" : "";
// $urvenue_ws_eventtext = (urvenue_ws_adm_is_wordpress()) ? " <small>Shortcode: <strong>[urvenue_ws_event]</strong></small>" : ""; // Axl UWS-7416
$urvenue_ws_eventtext = (urvenue_ws_adm_is_wordpress()) ? " <small>Shortcode: <strong>[urvenue_ws_event]</strong></small>" : ""; // Axl UWS-7634
// $urvenue_ws_maptext = (uvs_is_wordpress()) ? " <small>Shortcode: <strong>[urvenue_ws_map]</strong></small>" : "";
// $urvenue_ws_maptext = (urvenue_ws_adm_is_wordpress()) ? " <small>Shortcode: <strong>[urvenue_ws_map]</strong></small>" : ""; // Axl UWS-7416
$urvenue_ws_maptext = (urvenue_ws_adm_is_wordpress()) ? " <small>Shortcode: <strong>[urvenue_ws_map]</strong></small>" : ""; // Axl UWS-7634
// $urvenue_ws_itempagetext = (uvs_is_wordpress()) ? " <small>Shortcode: <strong>[urvenue_ws_inventory_item_page]</strong></small>" : "";
// $urvenue_ws_itempagetext = (urvenue_ws_adm_is_wordpress()) ? " <small>Shortcode: <strong>[urvenue_ws_inventory_item_page]</strong></small>" : ""; // Axl UWS-7416
$urvenue_ws_itempagetext = (urvenue_ws_adm_is_wordpress()) ? " <small>Shortcode: <strong>[urvenue_ws_inventory_item_page]</strong></small>" : ""; // Axl UWS-7634
?>
<div id="uvs-admin-pages" class="uvs-admin-opt-section <?php /* Old: echo $urvenue_ws_admin_optstabs_state['pages']; */ echo esc_attr( $urvenue_ws_admin_optstabs_state['pages'] ); ?>">
    <div class="uvs-admin-opt-title">Integration Pages</div>
	<div class="uvs-admin-opt-subtitle">Select the pages for your integrations, this will help for site internal links and redirections</div>
    <div class="uvs-admin-opt-space"></div>

	<div class="uvs-infolist-item uvs-clearfix">
		<?php /* Old: echo wp_kses( $urvenue_ws_eventstext, uvs_allowed_admin_html() ); */ ?>
		<div class="uvsname">Events Page<?php /* Old: echo $urvenue_ws_eventstext; */ echo wp_kses( $urvenue_ws_eventstext, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_pageevents; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_pageevents, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_pageevents, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<?php /* Old: echo wp_kses( $urvenue_ws_eventtext, uvs_allowed_admin_html() ); */ ?>
		<div class="uvsname">Event Page<?php /* Old: echo $urvenue_ws_eventtext; */ echo wp_kses( $urvenue_ws_eventtext, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_pagesingleevent; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_pagesingleevent, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_pagesingleevent, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<?php /* Old: echo wp_kses( $urvenue_ws_maptext, uvs_allowed_admin_html() ); */ ?>
		<div class="uvsname">Map Page<?php /* Old: echo $urvenue_ws_maptext; */ echo wp_kses( $urvenue_ws_maptext, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_pagemap; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_pagemap, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_pagemap, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<?php /* Old: echo wp_kses( $urvenue_ws_itempagetext, uvs_allowed_admin_html() ); */ ?>
		<div class="uvsname">Inventory Item Page<?php /* Old: echo $urvenue_ws_itempagetext; */ echo wp_kses( $urvenue_ws_itempagetext, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_pageitem; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_pageitem, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_pageitem, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Privacy Page</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_pagepriv; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_pagepriv, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_pagepriv, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Terms Page</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_pageterms; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_pageterms, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_pageterms, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
</div>