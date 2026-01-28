<?php

$uvsinventorymanageentlock = uvs_get_adminfieldhtml("inventory->manageentlock");
$uvsinventoryshowiteminfoinline = uvs_get_adminfieldhtml("inventory->showiteminfoinline");

?>

<div id="uvs-admin-inventory" class="uvs-admin-opt-section <?php echo $uvs_admin_optstabs_state['inventory']; ?>">
    <div class="uvs-admin-opt-title">Inventory</div>
	<div class="uvs-admin-opt-space"></div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Don't Allow Multiple Manageent ID <small>Allows the user to choose between continue with the current cart or create a new one</small></div>
		<div class="uvsvalue">
			<?php echo $uvsinventorymanageentlock; ?>
		</div>
    </div>
	<div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Show Info Inline On Item Popup <small>Image, highlight and description will be show on the item inventory popup</small></div>
		<div class="uvsvalue">
			<?php echo $uvsinventoryshowiteminfoinline; ?>
		</div>
    </div>
</div>