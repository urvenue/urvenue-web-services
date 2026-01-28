<?php
$uvsinitialtab = "dashboard";
?>
<style>
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
</style>
<div class="uvs-systempage uvs-page">
	<div class="uvs-nostyleserror">
		<div>Loading...</div>
	</div>

	<div class="uvs-logo">
		<a href="https://urvenue.com/"><img src="<?php echo $uvs_url; ?>/assets/images/urvenuelogo-light.svg" alt="UrVenue"></a>
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

<script>
	var uvs_core_lib = <?php echo json_encode($uvs_core_lib); ?>;
</script>