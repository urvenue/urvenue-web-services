<?php
/*$uvseventscalnmonths = uvs_get_adminfieldhtml("events->calendar-nmonths");
$uvseventsaddlist = uvs_get_adminfieldhtml("events->calendar-addlist");
$uvseventsinitialview = uvs_get_adminfieldhtml("events->calendar-initialview");
$uvseventsviewmenu = uvs_get_adminfieldhtml("events->calendar-viewmenu");
$uvseventsonlyoneevent = uvs_get_adminfieldhtml("events->calendar-onlyoneevent");
$uvseventsmonthseltype = uvs_get_adminfieldhtml("events->calendar-monthseltype");
$uvseventsinitialviewclass = (!is_array($uvs_core_lib["events"]) or !$uvs_core_lib["events"]["calendar-addlist"]) ? "uvs-fieldhide" : "";*/

$uvseventsagendacolumns = uvs_get_adminfieldhtml("events->agenda-columns");
?>

<div id="uvs-admin-events-agenda" class="uvs-admin-opt-section <?php /* Old: echo $uvs_admin_optstabs_state['events-agenda']; */ echo esc_attr( $uvs_admin_optstabs_state['events-agenda'] ); ?>">
    <div class="uvs-admin-opt-title">Agenda</div>
	<div class="uvs-admin-opt-space"></div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Number of Colunms <small>Default number of colunms on desktop</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvseventsagendacolumns; */ ?>
			<?php echo wp_kses( $uvseventsagendacolumns, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
	</div>
</div>