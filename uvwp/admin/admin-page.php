<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$uvsinitialtab = "dashboard";

// @egt [UWS-7264]
function uvwp_adminpage_styles() {
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

    wp_register_style('uvwp_admin_styles', false);
    wp_enqueue_style('uvwp_admin_styles');
    wp_add_inline_style('uvwp_admin_styles', $uvwp_admin_css);
}
add_action('wp_enqueue_scripts', 'uvwp_adminpage_styles');

?>

<div class="uvs-systempage uvs-page">
	<div class="uvs-nostyleserror">
		<div>Loading...</div>
	</div>

	<div class="uvs-logo">
		<a href="https://urvenue.com/"><img src="<?php /* Old: echo $uvs_url; */ echo esc_url( $uvs_url ); ?>/assets/images/urvenuelogo-light.svg" alt="UrVenue"></a>
	</div>
	
	<div class="uvs-content">
		<?php if($uvs_libexits){
			include_once($uvs_path . "/system/admin/admin-box.php");
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

// @egt [UWS-7264]
add_action('wp_footer', function () use ($uvs_core_lib) {
	// @Axl
	// echo "<script>var uvs_core_lib = " . json_encode($uvs_core_lib) . ";</script>";
	echo "<script>var uvs_core_lib = " . wp_json_encode($uvs_core_lib) . ";</script>";
	// @Axl End
});

?>