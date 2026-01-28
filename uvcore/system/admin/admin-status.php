<?php

preg_match("#^\d+(\.\d+)*#", PHP_VERSION, $uvsphpvermatch);
$uvsphpversion = $uvsphpvermatch[0];

$uvslibfile = $uvs_core_lib["system"]["library"];
if(is_writable($uvslibfile))
	$uvslibiswrhtml = "<span class='uvsok'>Yes</span>";
else
	$uvslibiswrhtml = "<span class='uvsbad'>No</span>";

if($uvs_feeds_path and is_writable($uvs_feeds_path))
	$uvsfeedsiswrhtml = "<span class='uvsok'>Working Properly</span>";
else
	$uvsfeedsiswrhtml = "<span class='uvsbad'>Not Working</span>";

$uvcorelayer = (uvs_is_wordpress()) ? "Wordpress" : "Base";

?>

<div id="uvs-admin-status" class="uvs-admin-opt-section <?php echo $uvs_admin_optstabs_state['status']; ?>">
	<div class="uvs-admin-opt-title">Status</div>
	<div class="uvs-admin-opt-descr">On the status you can find information about the uvcore status and the compatibility with the server</div>
	<div class="uvs-admin-opt-space"></div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">UWS Version:</div>
		<div class="uvsvalue"><strong><?php echo $uws_core_version; ?></strong></div>
	</div>
	<?php if(!uvs_is_wordpress()){ ?>
		<div class="uvs-infolist-item uvs-clearfix">
			<div class="uvsname">UvCore URL</div>
			<div class="uvsvalue"><?php echo $uvs_url; ?></div>
		</div>
	<?php } ?>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">UWS Cache Status</div>
		<div class="uvsvalue"><?php echo $uvsfeedsiswrhtml; ?></div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">UWS Layer</div>
		<div class="uvsvalue"><?php echo $uvcorelayer; ?></div>
	</div>
	<?php if(!uvs_is_wordpress()){ ?>
		<div class="uvs-infolist-item uvs-clearfix">
			<div class="uvsname">Library is Writable</div>
			<div class="uvsvalue"><?php echo $uvslibiswrhtml; ?></div>
		</div>
	<?php } ?>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Feeds Method</div>
		<div class="uvsvalue">CURL</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Debug Mode</div>
		<div class="uvsvalue">No</div>
	</div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">PHP Version</div>
		<div class="uvsvalue"><span class="uvsok"><?php echo $uvsphpversion; ?></span></div>
	</div>
</div>