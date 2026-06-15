<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// $uvsinitialtab = "dashboard";
$urvenue_ws_initialtab = "dashboard"; // Axl UWS-7416

// @egt [UWS-7264]
// function uvwp_adminpage_styles() {
function urvenue_ws_adminpage_styles() { // Axl UWS-7416
    global $urvenue_ws_assetsversion;

    $uvwp_admin_css = "
		.uvs-setupbox, .uvs-logo, .uvs-content{display: none;}
		body{background-color: #fafafa;}
		.uvs-nostyleserror{
			display: block;
			font-size: 30px;
			line-height: 1.2;
			position: absolute;
			width: 80%;
			top: 45vh;
			left: 10%;
			text-align: center;
		}
	";

    // wp_register_style('uvwp_admin_styles', false);
    wp_register_style('urvenue_ws_admin_styles', false, array(), $urvenue_ws_assetsversion); // Axl UWS-7416
    // wp_enqueue_style('uvwp_admin_styles');
    wp_enqueue_style('urvenue_ws_admin_styles'); // Axl UWS-7416
    // wp_add_inline_style('uvwp_admin_styles', $uvwp_admin_css);
    wp_add_inline_style('urvenue_ws_admin_styles', $uvwp_admin_css); // Axl UWS-7416
}
// add_action('wp_enqueue_scripts', 'uvwp_adminpage_styles');
add_action('admin_enqueue_scripts', 'urvenue_ws_adminpage_styles'); // Axl UWS-7416

?>

<div class="uvs-systempage uvs-page">
	<div class="uvs-nostyleserror">
		<div>Loading...</div>
	</div>

	<div class="uvs-logo">
		<a href="https://urvenue.com/"><img src="<?php /* Old: echo $urvenue_ws_url; */ echo esc_url( $urvenue_ws_url ); ?>/assets/images/urvenuelogo-light.svg" alt="UrVenue"></a>
	</div>
	
	<div class="uvs-content">
		<?php if($urvenue_ws_libexits){
			include_once($urvenue_ws_uvs_path . "/system/admin/admin-box.php");
		} else{ ?>
			<div class="uvs-boxpanel uvs-blockcenter uvs-maxw800">
				<p class="uvs-text-center">Sorry the library is empty, start the initial setup or contact support@urvenue.com</p>
				<div class="uvs-text-center uvs-mt20">
					<a class="uvs-btn uvs-btn-p" href="setup.php">Setup</a>
				</div>
			</div>
		<?php } ?>
	
	</div>
</div>

<?php

wp_localize_script('urvenue-ws-admin', 'uvs_core_lib', $urvenue_ws_core_lib);

?>