<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$urvenue_ws_venueslisthtml = urvenue_ws_adm_admin_venues_list_html();
?>

<div id="uvs-admin-venues" class="uvs-admin-opt-section <?php echo esc_attr( $urvenue_ws_admin_optstabs_state['venues'] ); ?>">
	<div class="uvs-admin-opt-title">Venues</div>
	<!--<div class="uvs-admin-opt-descr">Use your <strong>VEA Venue ID</strong> to make your initial configuration. If you don't know your ID contact: <a href='mailto:support@urvenue.com'>support@urvenue.com</a></div>
	<div class="uvs-admin-opt-inputbtn uvs-maxw450 uvs-clearfix">
		<div class="uvs-admin-opt-inputbtn-input uvs-admin-opt-input-bigicon">
			<i class="uv-icon-idcard"></i>
			<input id="veaid" class="uvs-input-big uvs-maxw150" type="text" name="veaid" value="" placeholder="VEA Venue ID">
		</div>
		<div class="uvs-admin-opt-inputbtn-btn uvs-admin-opt-inputbtn-btnbig">
			<button class="uvs-btn uvs-btn-p uvsjs-checkvenueid" data-loadertarget=".uvs-venuecheckloader" data-checkurl="<?php /* Old: echo $urvenue_ws_adm_admin_lib["loads"]["checkveaid"]; */ /* echo esc_url( $urvenue_ws_adm_admin_lib["loads"]["checkveaid"] ); // Axl UWS-8152 */ echo esc_url( add_query_arg( 'uws_nonce', wp_create_nonce( 'uvsp_veaidinfo' ), $urvenue_ws_adm_admin_lib["loads"]["checkveaid"] ) ); // Axl UWS-8152 ?>" type="button">Check</button>
			<div class="uvs-venuecheckloader uv-loader-uvicon"></div>
		</div>
	</div>-->
	<div class="uvs-admin-venuesmsg"></div>
	<div class="uvs-admin-opt-linesep"></div>
	<div id="uvs-admin-venuesinfo" class="uvs-admin-opt-infolist">
		<?php echo wp_kses( $urvenue_ws_venueslisthtml, urvenue_ws_adm_allowed_admin_html() ); ?>
	</div>
	<div class="uvs-admin-venues-actions">
        <span>Add a new Venue manually.</span>
        <button class="uvs-btn uvs-btn-p uvsjs-addnewvenue" type="button" data-target="#uvs-admin-venuesinfo">Add Venue</button>
    </div>
</div>