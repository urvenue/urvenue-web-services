<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// $uvspageevents = uvs_get_adminfieldhtml("pages->events");
$uvspageevents = urvenue_ws_adm_get_adminfieldhtml("pages->events"); // Axl UWS-7416
// $uvspagesingleevent = uvs_get_adminfieldhtml("pages->singleevent");
$uvspagesingleevent = urvenue_ws_adm_get_adminfieldhtml("pages->singleevent"); // Axl UWS-7416
// $uvspagemap = uvs_get_adminfieldhtml("pages->map");
$uvspagemap = urvenue_ws_adm_get_adminfieldhtml("pages->map"); // Axl UWS-7416
// $uvspageitem = uvs_get_adminfieldhtml("pages->itempage");
$uvspageitem = urvenue_ws_adm_get_adminfieldhtml("pages->itempage"); // Axl UWS-7416
// $uvspagepriv = uvs_get_adminfieldhtml("pages->privacy");
$uvspagepriv = urvenue_ws_adm_get_adminfieldhtml("pages->privacy"); // Axl UWS-7416
// $uvspageterms = uvs_get_adminfieldhtml("pages->terms");
$uvspageterms = urvenue_ws_adm_get_adminfieldhtml("pages->terms"); // Axl UWS-7416

// $uvseventstext = (uvs_is_wordpress()) ? " <small>Shortcode: <strong>[uws_events]</strong></small>" : "";
$uvseventstext = (urvenue_ws_adm_is_wordpress()) ? " <small>Shortcode: <strong>[uws_events]</strong></small>" : ""; // Axl UWS-7416
// $uvseventtext = (uvs_is_wordpress()) ? " <small>Shortcode: <strong>[uws_event]</strong></small>" : "";
$uvseventtext = (urvenue_ws_adm_is_wordpress()) ? " <small>Shortcode: <strong>[uws_event]</strong></small>" : ""; // Axl UWS-7416
// $uvsmaptext = (uvs_is_wordpress()) ? " <small>Shortcode: <strong>[uws_map]</strong></small>" : "";
$uvsmaptext = (urvenue_ws_adm_is_wordpress()) ? " <small>Shortcode: <strong>[uws_map]</strong></small>" : ""; // Axl UWS-7416
// $uvsitempagetext = (uvs_is_wordpress()) ? " <small>Shortcode: <strong>[uws_inventory_item_page]</strong></small>" : "";
$uvsitempagetext = (urvenue_ws_adm_is_wordpress()) ? " <small>Shortcode: <strong>[uws_inventory_item_page]</strong></small>" : ""; // Axl UWS-7416
?>
<div id="uvs-admin-pages" class="uvs-admin-opt-section <?php /* Old: echo $uvs_admin_optstabs_state['pages']; */ echo esc_attr( $uvs_admin_optstabs_state['pages'] ); ?>">
    <div class="uvs-admin-opt-title">Integration Pages</div>
	<div class="uvs-admin-opt-subtitle">Select the pages for your integrations, this will help for site internal links and redirections</div>
    <div class="uvs-admin-opt-space"></div>

	<div class="uvs-infolist-item uvs-clearfix">
		<?php /* Old: echo wp_kses( $uvseventstext, uvs_allowed_admin_html() ); */ ?>
		<div class="uvsname">Events Page<?php /* Old: echo $uvseventstext; */ echo wp_kses( $uvseventstext, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvspageevents; */ ?>
			<?php /* Old: echo wp_kses( $uvspageevents, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvspageevents, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<?php /* Old: echo wp_kses( $uvseventtext, uvs_allowed_admin_html() ); */ ?>
		<div class="uvsname">Event Page<?php /* Old: echo $uvseventtext; */ echo wp_kses( $uvseventtext, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvspagesingleevent; */ ?>
			<?php /* Old: echo wp_kses( $uvspagesingleevent, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvspagesingleevent, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<?php /* Old: echo wp_kses( $uvsmaptext, uvs_allowed_admin_html() ); */ ?>
		<div class="uvsname">Map Page<?php /* Old: echo $uvsmaptext; */ echo wp_kses( $uvsmaptext, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvspagemap; */ ?>
			<?php /* Old: echo wp_kses( $uvspagemap, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvspagemap, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<?php /* Old: echo wp_kses( $uvsitempagetext, uvs_allowed_admin_html() ); */ ?>
		<div class="uvsname">Inventory Item Page<?php /* Old: echo $uvsitempagetext; */ echo wp_kses( $uvsitempagetext, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvspageitem; */ ?>
			<?php /* Old: echo wp_kses( $uvspageitem, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvspageitem, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Privacy Page</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvspagepriv; */ ?>
			<?php /* Old: echo wp_kses( $uvspagepriv, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvspagepriv, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Terms Page</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvspageterms; */ ?>
			<?php /* Old: echo wp_kses( $uvspageterms, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvspageterms, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
</div>