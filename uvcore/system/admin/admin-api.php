<?php
$uvsapisourcecode = uvs_get_adminfieldhtml("system->sourcecode");
$uvsapisourceloc = uvs_get_adminfieldhtml("system->sourceloc");
$uvsapiapikey = uvs_get_adminfieldhtml("system->apikey");
$uvsapimicrocode = uvs_get_adminfieldhtml("system->microcode");
$uvsadminusestaging = uvs_get_adminfieldhtml("system->use-staging");
?>
<div id="uvs-admin-api" class="uvs-admin-opt-section <?php echo $uvs_admin_optstabs_state['api']; ?>">
    <div class="uvs-admin-opt-title">API Info</div>
    <div class="uvs-admin-opt-space"></div>

    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">API Key</div>
		<div class="uvsvalue">
			<?php echo $uvsapiapikey; ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Micro Code</div>
		<div class="uvsvalue">
			<?php echo $uvsapimicrocode; ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Source Code</div>
		<div class="uvsvalue">
			<?php echo $uvsapisourcecode; ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Source Loc</div>
		<div class="uvsvalue">
			<?php echo $uvsapisourceloc; ?>
		</div>
    </div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Use Staging <small>Select this options to use staging API endpoints.</small></div>
		<div class="uvsvalue">
			<?php echo $uvsadminusestaging; ?>
		</div>
    </div>
</div>