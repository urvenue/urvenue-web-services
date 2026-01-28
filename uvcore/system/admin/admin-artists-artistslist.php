<?php
/**
	@module: system/admin/box/artists-list
	@author: UrVenue - aa
	@version: 1.0
*/

$uvsartistslistview = uvs_get_adminfieldhtml("artists->artist-listview");
$uvsartistsbuttonlabel = uvs_get_adminfieldhtml("artists->artist-buttonlabel");
?>
<div id="uvs-admin-artists-list" class="uvs-admin-opt-section <?php echo $uvs_admin_optstabs_state['artists-list']; ?>">
    <div class="uvs-admin-opt-title">Artist List</div>
    <div class="uvs-admin-opt-space"></div>

    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">List View</div>
		<div class="uvsvalue">
			<?php echo $uvsartistslistview; ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Button Label</div>
		<div class="uvsvalue">
			<?php echo $uvsartistsbuttonlabel; ?>
		</div>
    </div>
</div>