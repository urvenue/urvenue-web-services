<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$uvsartistspageurl = uvs_get_adminfieldhtml("artists->artist-url");
$uvsartistsimagetype = uvs_get_adminfieldhtml("artists->artist-imagetype");
$uvsartistsimageratio = uvs_get_adminfieldhtml("artists->artist-imageratio");

?>
<?php // @Axl ?>
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
<!-- <div id="uvs-admin-artists-artistpage" class="uvs-admin-opt-section <?php echo $uvs_admin_optstabs_state['artists-artistpage']; ?>"> -->
<div id="uvs-admin-artists-artistpage" class="uvs-admin-opt-section <?php echo esc_attr( $uvs_admin_optstabs_state['artists-artistpage'] ); ?>">
    <div class="uvs-admin-opt-title">Artist Page</div>
    <div class="uvs-admin-opt-space"></div>

    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Artist URL</div>
		<div class="uvsvalue">
			<?php /* Old: echo $uvsartistspageurl; */ echo wp_kses( $uvsartistspageurl, uvs_allowed_admin_html() ); ?>
		</div>
    </div>
    <div class="uvs-admin-opt-subtitle">Artist Image</div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Image Type</div>
		<div class="uvsvalue">
			<?php /* Old: echo $uvsartistsimagetype; */ echo wp_kses( $uvsartistsimagetype, uvs_allowed_admin_html() ); ?>
		</div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Image Ratio</div>
		<div class="uvsvalue">
			<?php /* Old: echo $uvsartistsimageratio; */ echo wp_kses( $uvsartistsimageratio, uvs_allowed_admin_html() ); ?>
		</div>
    </div>
</div>
<?php // @Axl End ?>if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
