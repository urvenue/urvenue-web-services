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

// $uvseventsagendacolumns = uvs_get_adminfieldhtml("events->agenda-columns");
$uvseventsagendacolumns = urvenue_ws_adm_get_adminfieldhtml("events->agenda-columns"); // Axl UWS-7416
?>

<div id="uvs-admin-events-agenda" class="uvs-admin-opt-section <?php /* Old: echo $uvs_admin_optstabs_state['events-agenda']; */ echo esc_attr( $uvs_admin_optstabs_state['events-agenda'] ); ?>">
    <div class="uvs-admin-opt-title">Agenda</div>
	<div class="uvs-admin-opt-space"></div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Number of Colunms <small>Default number of colunms on desktop</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvseventsagendacolumns; */ ?>
			<?php /* Old: echo wp_kses( $uvseventsagendacolumns, uvs_allowed_admin_html() ); */ ?>
			<?php echo wp_kses( $uvseventsagendacolumns, urvenue_ws_adm_allowed_admin_html() );  // Axl UWS-7416 ?>
			<?php // @Axl End ?>
		</div>
	</div>
</div>