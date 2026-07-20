<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include_once("system/uvs-admin-init.php");
	
$urvenue_ws_initialtab = "dashboard";

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="width=device-width" />
	<link rel="icon" type="image/png" href="assets/images/urvenueicon.png">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex,nofollow" />
	<title>UvCore | Admin</title>
	
	<?php
		add_action('admin_enqueue_scripts', function(){
			global $urvenue_ws_assetsversion;

			$uvbaseurl = plugin_dir_url( __FILE__ );

			$uvwp_admin_css = "
				.uvs-setupbox, .uvs-logo, .uvs-content{display: none;}
				body{background-color: #fafafa;}
				.uvs-nostyleserror{
					display: block;
					font-size: 30px;
					position: absolute;
					width: 80%;
					top: 45vh;
					left: 10%;
					text-align: center;
				}
			";

			wp_register_style('urvenue_ws_admin_styles', '', array(), $urvenue_ws_assetsversion);
			wp_enqueue_style('urvenue_ws_admin_styles');
			wp_add_inline_style('urvenue_ws_admin_styles', $uvwp_admin_css);

			wp_enqueue_style('flatpickr-css', $uvbaseurl . 'assets/css/flatpickr.min.css', array(), $urvenue_ws_assetsversion, 'all');
			wp_enqueue_style('urvenue-ws-system-css', $uvbaseurl . 'assets/css/system.css', array(), $urvenue_ws_assetsversion, 'all');
			wp_enqueue_style('urvenue-ws-icons-css', $uvbaseurl . 'assets/css/uwsicons.css', array(), $urvenue_ws_assetsversion, 'all');

			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-validate', $uvbaseurl . 'assets/js/jquery.validate.min.js', array('jquery'), $urvenue_ws_assetsversion, true);
			wp_enqueue_script('urvenue-ws-admin-scripts', $uvbaseurl . 'assets/js/admin.js', array('jquery', 'jquery-validate', 'flatpickr'), $urvenue_ws_assetsversion, true);
			wp_enqueue_script('flatpickr', $uvbaseurl . 'assets/js/flatpickr.min.js', array(), $urvenue_ws_assetsversion, true);
		});
	?>
</head>
<body class="uvs-systempage">
	<div class="uvs-nostyleserror">
		<div>We could not include our files :(<br>Please, contact support@urvenue.com</div>
	</div>

	<div class="uvs-logo">
		<a href="https://urvenue.com/"><img src="assets/images/urvenuelogo-light.svg" alt="UrVenue"></a>
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
</body>
</html>