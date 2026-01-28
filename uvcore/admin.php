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
	
	<style>
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
	</style>
	<link rel="stylesheet" href="assets/css/flatpickr.min.css" type="text/css" media="all">
	<link rel="stylesheet" href="assets/css/system.css" type="text/css" media="all">
	<link rel="stylesheet" href="assets/css/uwsicons.css" type="text/css" media="all">
	
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/jquery.validate.min.js"></script>
	<script src="assets/js/admin.js"></script>
	<script src="assets/js/flatpickr.min.js"></script>
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