<?php

include_once("system/uvs-admin-init.php");
	
$uvsinitialtab = "dashboard";

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
		// @egt [UWS-7264]
		add_action('admin_enqueue_scripts', function(){
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

			wp_register_style('uvwp_admin_styles', '');
			wp_enqueue_style('uvwp_admin_styles');
			wp_add_inline_style('uvwp_admin_styles', $uvwp_admin_css);

			wp_enqueue_style('flatpickr-css', $uvbaseurl . 'assets/css/flatpickr.min.css', array(), null, 'all');
			wp_enqueue_style('system-css', $uvbaseurl . 'assets/css/system.css', array(), null, 'all');
			wp_enqueue_style('uwsicons-css', '$uvbaseurl . assets/css/uwsicons.css', array(), null, 'all');

			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-validate', $uvbaseurl . 'assets/js/jquery.validate.min.js', array('jquery'), null, true);
			wp_enqueue_script('admin', $uvbaseurl . 'assets/js/admin.js', array('jquery', 'jquery-validate', 'flatpickr'), null, true);
			wp_enqueue_script('flatpickr', $uvbaseurl . 'assets/js/flatpickr.min.js', array(), null, true);
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
</body>
</html>