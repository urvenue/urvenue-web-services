<?php
$uvsartistspageurl = uvs_get_adminfieldhtml("artists->artist-url");
$uvsartistsimagetype = uvs_get_adminfieldhtml("artists->artist-imagetype");
$uvsartistsimageratio = uvs_get_adminfieldhtml("artists->artist-imageratio");

?>
<div id="uvs-admin-artists-artistpage" class="uvs-admin-opt-section <?php echo $uvs_admin_optstabs_state['artists-artistpage']; ?>">
    <div class="uvs-admin-opt-title">Artist Page</div>
    <div class="uvs-admin-opt-space"></div>

    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Artist URL</div>
		<div class="uvsvalue">
			<?php echo $uvsartistspageurl; ?>
		</div>
    </div>
    <div class="uvs-admin-opt-subtitle">Artist Image</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Image Type</div>
		<div class="uvsvalue">
			<?php echo $uvsartistsimagetype; ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Image Ratio</div>
		<div class="uvsvalue">
			<?php echo $uvsartistsimageratio; ?>
		</div>
    </div>
</div>