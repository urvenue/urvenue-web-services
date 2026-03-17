<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $urvenue_ws_adm_adminbox_tabs;

// $uvs_admin_optstabs_state = uvs_get_boxtabs_state($urvenue_ws_initialtab);
$uvs_admin_optstabs_state = urvenue_ws_adm_get_boxtabs_state($urvenue_ws_initialtab); // Axl UWS-7416
$uvsboxpanelclass = (is_array($urvenue_ws_core_lib) and !$urvenue_ws_core_lib["system"]["apikey"]) ? "uvapiconfig" : "";

?>

<?php // @Axl ?>
<?php /* old: <div class="uvs-boxpanel uvs-boxpanel-admin [echo $uvsboxpanelclass]"> */ ?>
<?php /* old: <form id="uvs-uvcoreadmin-form" action="[echo $urvenue_ws_adm_admin_lib[loads][adminsave]]"> */ ?>
<?php /* old: <div class="uvs-adminbox-corever">[echo $urvenue_ws_adm_core_version]</div> */ ?>
<div class="uvs-boxpanel uvs-boxpanel-admin <?php echo esc_attr( $uvsboxpanelclass ); ?>">
	<form id="uvs-uvcoreadmin-form" action="<?php echo esc_url( $urvenue_ws_adm_admin_lib["loads"]["adminsave"] ); ?>">
		<div class="uvs-adminbox-head uvs-clearfix">
			<div class="uvs-adminbox-head-title">UrVenue Web Services</div>
			<div class="uvs-adminbox-corever"><?php echo esc_html( $urvenue_ws_adm_core_version ); ?></div>
		</div>
<?php // @Axl End ?>

		<?php // @egt [UWS-7297]
		//wp_nonce_field('uvsp_adminsave_action', 'uvsp_adminsave_nonce'); ?>
		<input class="uvsjson" type="hidden" name="uvsp_adminsave_nonce" value="<?php echo esc_attr( wp_create_nonce('uvsp_adminsave_action') ); ?>">
		
		<div class="uvs-adminbox-credentials">
			<?php include_once($urvenue_ws_uvs_path . "/system/admin/admin-apiconfig.php"); ?>
		</div>

		<div class="uvs-adminbox-admcols uvs-clearfix">
			<div class="uvs-adminbox-mainmenu">
				<?php // @Axl ?>
			<ul>
					<li class="uvs-menu-group"><a class="<?php /* Old: echo $uvs_admin_optstabs_state['dashboard']; */ echo esc_attr( $uvs_admin_optstabs_state['dashboard'] ); ?>" href="#dashboard"><span><i class="uwsicon-gauge"></i> Dashboard</span></a></li>

					<li class="uvs-menu-group"><span><i class="uwsicon-calendar-1"></i> Events</span></li>
					<li><a class="<?php /* Old: echo $uvs_admin_optstabs_state['events-global']; */ echo esc_attr( $uvs_admin_optstabs_state['events-global'] ); ?>" href="#events-global">Global</a></li>
					<li class="uvissubitem"><a class="<?php /* Old: echo $uvs_admin_optstabs_state['events-calendar']; */ echo esc_attr( $uvs_admin_optstabs_state['events-calendar'] ); ?>" href="#events-calendar">Calendar</a></li>
					<li class="uvissubitem"><a class="<?php /* Old: echo $uvs_admin_optstabs_state['events-agenda']; */ echo esc_attr( $uvs_admin_optstabs_state['events-agenda'] ); ?>" href="#events-agenda">Agenda</a></li>
					<?php /* old: <li class="uvissubitem"><a class="[echo $uvs_admin_optstabs_state[events-list]]" href="#events-list">List</a></li> */ ?>
					<li class="uvissubitem"><a class="<?php /* Old: echo $uvs_admin_optstabs_state['events-slider']; */ echo esc_attr( $uvs_admin_optstabs_state['events-slider'] ); ?>" href="#events-slider">Slider</a></li>
					<li><a class="<?php /* Old: echo $uvs_admin_optstabs_state['events-event']; */ echo esc_attr( $uvs_admin_optstabs_state['events-event'] ); ?>" href="#events-event">Event Page</a></li>
					<li><a class="<?php /* Old: echo $uvs_admin_optstabs_state['flyers']; */ echo esc_attr( $uvs_admin_optstabs_state['flyers'] ); ?>" href="#flyers">Flyers</a></li>
					<?php /* old: <li class="uvs-menu-group"><span>Artists</span></li>
					<li><a class="[echo $uvs_admin_optstabs_state[artists-artistpage]]" href="#artists-artistpage">Artist Page</a></li>
					<li><a class="[echo $uvs_admin_optstabs_state[artists-list]]" href="#artists-list">Artists List</a></li> */ ?>

					<li class="uvs-menu-group"><span><i class="uwsicon-cog"></i> Configuration</span></li>
					<li><a class="<?php /* Old: echo $uvs_admin_optstabs_state['pages']; */ echo esc_attr( $uvs_admin_optstabs_state['pages'] ); ?>" href="#pages">Pages</a></li>
					<li><a class="<?php /* Old: echo $uvs_admin_optstabs_state['venues']; */ echo esc_attr( $uvs_admin_optstabs_state['venues'] ); ?> uvs-menu-isvenues" href="#venues">Venues</a></li>
					<li><a class="<?php /* Old: echo $uvs_admin_optstabs_state['inventory']; */ echo esc_attr( $uvs_admin_optstabs_state['inventory'] ); ?>" href="#inventory">Inventory</a></li>
					<li><a class="<?php /* Old: echo $uvs_admin_optstabs_state['notifications']; */ echo esc_attr( $uvs_admin_optstabs_state['notifications'] ); ?>" href="#notifications">Notifications</a></li>
					<li><a class="<?php /* Old: echo $uvs_admin_optstabs_state['map']; */ echo esc_attr( $uvs_admin_optstabs_state['map'] ); ?>" href="#map">Map</a></li>
					<li><a class="<?php /* Old: echo $uvs_admin_optstabs_state["ui-color-palette"]; */ echo esc_attr( $uvs_admin_optstabs_state["ui-color-palette"] ); ?> uvs-menu-isvenues" href="#ui-color-palette">UI Color Palette</a></li>
					<li><a class="<?php /* Old: echo $uvs_admin_optstabs_state['seo']; */ echo esc_attr( $uvs_admin_optstabs_state['seo'] ); ?>" href="#seo">SEO</a></li>
					<li><a class="<?php /* Old: echo $uvs_admin_optstabs_state['cache']; */ echo esc_attr( $uvs_admin_optstabs_state['cache'] ); ?>" href="#cache">Cache</a></li>
					<li><a class="<?php /* Old: echo $uvs_admin_optstabs_state['api']; */ echo esc_attr( $uvs_admin_optstabs_state['api'] ); ?>" href="#api">API Info</a></li>
					<li><a class="<?php /* Old: echo $uvs_admin_optstabs_state['status']; */ echo esc_attr( $uvs_admin_optstabs_state['status'] ); ?>" href="#status">System Status</a></li>
				</ul>
			<?php // @Axl End ?>
			</div>
			<div class="uvs-adminbox-optionsarea">
				<div class="uvs-adminbox-optionsarea-inner">
					<?php // @Axl ?>
					<?php /* old: <input ... value="[echo $urvenue_ws_core_lib[system][path]]"> */ ?>
					<?php /* old: <input ... value="[echo $urvenue_ws_url]"> */ ?>
					<?php /* old: <input ... value="[echo $urvenue_ws_core_lib[system][library]]"> */ ?>
					<input class="uvsjson" type="hidden" name="system[path]" value="<?php echo esc_attr( $urvenue_ws_core_lib["system"]["path"] ); ?>">
					<input class="uvsjson" type="hidden" name="system[url]" value="<?php echo esc_attr( $urvenue_ws_url ); ?>">
					<input class="uvsjson" type="hidden" name="system[library]" value="<?php echo esc_attr( $urvenue_ws_core_lib["system"]["library"] ); ?>">
					<?php // @Axl End ?>

					<?php include_once($urvenue_ws_uvs_path . "/system/admin/admin-dashboard.php"); ?>
					<?php include_once($urvenue_ws_uvs_path . "/system/admin/admin-events-global.php"); ?>
					<?php include_once($urvenue_ws_uvs_path . "/system/admin/admin-events-calendar.php"); ?>
					<?php include_once($urvenue_ws_uvs_path . "/system/admin/admin-events-agenda.php"); ?>
					<?php //include_once($urvenue_ws_uvs_path . "/system/admin/admin-events-list.php"); ?>
					<?php include_once($urvenue_ws_uvs_path . "/system/admin/admin-events-slider.php"); ?>
					<?php include_once($urvenue_ws_uvs_path . "/system/admin/admin-events-event.php"); ?>
					<?php //include_once($urvenue_ws_uvs_path . "/system/admin/admin-artists-artistpage.php"); ?>
					<?php //include_once($urvenue_ws_uvs_path . "/system/admin/admin-artists-artistslist.php"); ?>
					<?php include_once($urvenue_ws_uvs_path . "/system/admin/admin-flyers.php"); ?>
					<?php include_once($urvenue_ws_uvs_path . "/system/admin/admin-map.php"); ?>
					<?php include_once($urvenue_ws_uvs_path . "/system/admin/admin-pages.php"); ?>
					<?php include_once($urvenue_ws_uvs_path . "/system/admin/admin-venues.php"); ?>
					<?php include_once($urvenue_ws_uvs_path . "/system/admin/admin-inventory.php"); ?>
					<?php include_once($urvenue_ws_uvs_path . "/system/admin/admin-notifications.php"); ?>
					<?php include_once($urvenue_ws_uvs_path . "/system/admin/admin-ui-color-palette.php"); ?>
					<?php include_once($urvenue_ws_uvs_path . "/system/admin/admin-seo.php"); ?>
					<?php include_once($urvenue_ws_uvs_path . "/system/admin/admin-cache.php"); ?>
					<?php include_once($urvenue_ws_uvs_path . "/system/admin/admin-api.php"); ?>
					<?php include_once($urvenue_ws_uvs_path . "/system/admin/admin-status.php"); ?>
				</div>
			</div>
		</div>
		<div class="uvs-adminbox-actions">
			<div class="uvs-adminbox-actions-status"></div>
			<div class="uvs-adminbox-actions-btnset">
				<div class="uvs-venuecheckloader uv-loader-uvicon"></div>
				<button class="uvs-btn uvs-btn-p uvsjs-saveadmin" type="submit">Save Changes</button>
			</div>
		</div>
	</form>
</div>