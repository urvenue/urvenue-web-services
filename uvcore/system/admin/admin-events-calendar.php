<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//$uvseventscalnmonths = uvs_get_adminfieldhtml("events->calendar-nmonths");
//$uvseventsaddlist = uvs_get_adminfieldhtml("events->calendar-addlist");
//$uvseventsinitialview = uvs_get_adminfieldhtml("events->calendar-initialview");
//$uvseventsviewmenu = uvs_get_adminfieldhtml("events->calendar-viewmenu");
//$uvseventsonlyoneevent = uvs_get_adminfieldhtml("events->calendar-onlyoneevent");
$uvseventscalonlylist = uvs_get_adminfieldhtml("events->calendar-alwayslist");
//$uvseventsmonthseltype = uvs_get_adminfieldhtml("events->calendar-monthseltype");

//$uvseventsinitialviewclass = (!is_array($uvs_core_lib["events"]) or !$uvs_core_lib["events"]["calendar-addlist"]) ? "uvs-fieldhide" : "";

?>

<div id="uvs-admin-events-calendar" class="uvs-admin-opt-section <?php /* Old: echo $uvs_admin_optstabs_state['events-calendar']; */ echo esc_attr( $uvs_admin_optstabs_state['events-calendar'] ); ?>">
    <div class="uvs-admin-opt-title">Events Calendar</div>
	<div class="uvs-admin-opt-space"></div>
	<!--<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Number of Months</div>
		<div class="uvsvalue">
			<?php //echo $uvseventscalnmonths; ?>
		</div>
	</div>-->
    <!--<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Add List to Integration <small>Add List as second view</small></div>
		<div class="uvsvalue">
			<?php //echo $uvseventsaddlist; ?>
		</div>
	</div>-->
    <!--<div class="uvs-fieldcallistrel uvs-infolist-item <?php /* Old: echo $uvseventsinitialviewclass; */ echo esc_attr( $uvseventsinitialviewclass ); ?> uvs-clearfix">
		<div class="uvsname">Initial View</div>
		<div class="uvsvalue">
			<?php //echo $uvseventsinitialview; ?>
		</div>
    </div>-->
    <!--<div class="uvs-fieldcallistrel uvs-infolist-item <?php /* Old: echo $uvseventsinitialviewclass; */ echo esc_attr( $uvseventsinitialviewclass ); ?> uvs-clearfix">
		<div class="uvsname">View Menu</div>
		<div class="uvsvalue">
			<?php //echo $uvseventsviewmenu; ?>
		</div>
    </div>-->
    <!--<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">1 Event Per Date <small>If there are more than 1 event in the date only the event with highest priority will be show</small></div>
		<div class="uvsvalue">
			<?php //echo $uvseventsonlyoneevent; ?>
		</div>
    </div>-->
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Always List <small>Don't show event flyer, show always as list</small></div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvseventscalonlylist; */ ?>
			<?php echo wp_kses( $uvseventscalonlylist, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
    </div>
    <!--<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Month Selection Type</div>
		<div class="uvsvalue">
			<?php //echo $uvseventsmonthseltype; ?>
		</div>
	</div>-->
</div>