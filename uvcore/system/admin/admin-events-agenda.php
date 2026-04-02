<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*$uvseventscalnmonths = uvs_get_adminfieldhtml("events->calendar-nmonths");
// $uvseventsaddlist = uvs_get_adminfieldhtml("events->calendar-addlist");
$uvseventsaddlist = urvenue_ws_adm_get_adminfieldhtml("events->calendar-addlist"); // Axl UWS-7416
// $uvseventsinitialview = uvs_get_adminfieldhtml("events->calendar-initialview");
$uvseventsinitialview = urvenue_ws_adm_get_adminfieldhtml("events->calendar-initialview"); // Axl UWS-7416
// $uvseventsviewmenu = uvs_get_adminfieldhtml("events->calendar-viewmenu");
$uvseventsviewmenu = urvenue_ws_adm_get_adminfieldhtml("events->calendar-viewmenu"); // Axl UWS-7416
// $uvseventsonlyoneevent = uvs_get_adminfieldhtml("events->calendar-onlyoneevent");
$uvseventsonlyoneevent = urvenue_ws_adm_get_adminfieldhtml("events->calendar-onlyoneevent"); // Axl UWS-7416
// $uvseventsmonthseltype = uvs_get_adminfieldhtml("events->calendar-monthseltype");
$uvseventsmonthseltype = urvenue_ws_adm_get_adminfieldhtml("events->calendar-monthseltype"); // Axl UWS-7416
$uvseventsinitialviewclass = (!is_array($urvenue_ws_core_lib["events"]) or !$urvenue_ws_core_lib["events"]["calendar-addlist"]) ? "uvs-fieldhide" : "";*/

// $urvenue_ws_eventsagendacolumns = uvs_get_adminfieldhtml("events->agenda-columns");
// $urvenue_ws_eventsagendacolumns = urvenue_ws_adm_get_adminfieldhtml("events->agenda-columns"); // Axl UWS-7416
$urvenue_ws_eventsagendacolumns = urvenue_ws_adm_get_adminfieldhtml("events->agenda-columns"); // Axl UWS-7634
?>

<div id="uvs-admin-events-agenda" class="uvs-admin-opt-section <?php /* Old: echo $urvenue_ws_admin_optstabs_state['events-agenda']; */ echo esc_attr( $urvenue_ws_admin_optstabs_state['events-agenda'] ); ?>">
    <div class="uvs-admin-opt-title">Agenda</div>
	<div class="uvs-admin-opt-space"></div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Number of Colunms <small>Default number of colunms on desktop</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $urvenue_ws_eventsagendacolumns; */ ?>
			<?php /* Old: echo wp_kses( $urvenue_ws_eventsagendacolumns, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $urvenue_ws_eventsagendacolumns, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
</div>