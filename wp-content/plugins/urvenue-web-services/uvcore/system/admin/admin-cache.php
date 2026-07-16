<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$urvenue_ws_wpeinstid = urvenue_ws_adm_get_adminfieldhtml("cache->wpeinst");
$urvenue_ws_inpwpeusername = urvenue_ws_adm_get_adminfieldhtml("cache->username");
$urvenue_ws_inpwpepassword = urvenue_ws_adm_get_adminfieldhtml("cache->password");
$urvenue_ws_inpendpoint = urvenue_ws_adm_get_adminfieldhtml("cache->endpoint");
$urvenue_ws_cacheendpoint = urvenue_ws_adm_get_fieldvalue_by_stringroute("cache->endpoint");

$urvenue_ws_inpapikey = urvenue_ws_adm_get_adminfieldhtml("cache->apikey");
$urvenue_ws_isuvuser = urvenue_ws_adm_is_uv_email();
$urvenue_ws_ishostedwpe = urvenue_ws_adm_is_hosted_on_wpengine();
?>
<div id="uvs-admin-cache" class="uvs-admin-opt-section <?php echo esc_attr( $urvenue_ws_admin_optstabs_state['cache'] ); ?>">
	<!-- <div class="uv-loader-uvicon"></div> -->
    <div class="uvs-admin-opt-title">UWS Cache</div>
	<div class="uvs-admin-opt-subtitle">Please fill out every field to activate the <strong>Clear Cache</strong> functionality.</div>
    <div class="uvs-admin-opt-space"></div>

	<div class="uvs-admin-opt-title">WP Engine</div>
	<div class="uvs-admin-opt-subtitle">
		Resources to access the WP Engine API.
		<ul class="uvs-admin-list">
			<li>API Username & Password in the <a href="https://my.wpengine.com/profile/api_access" target="_blank">WP Engine User Portal</a>.</li>
			<li><a href="https://wpengineapi.com/reference" target="_blank">Documentation Reference</a>.</li>
		</ul>
	</div> 
	

	<div class="uvs-infolist-item uvs-clearfix" <?php if(!$urvenue_ws_isuvuser): echo "style= border:none;"; endif;?> >
		<div class="uvsname">Clear Cache Endpoint
			<small>Provide this URL to the Backend Team.</small>
			<?php if(urvenue_ws_adm_get_fieldvalue_by_stringroute("cache->endpoint") != ""): ?>
				<div class="uvs-btn-group">
					<button class="uvsjs-clearcache uvs-btn uvs-btn-p" data-endpoint="<?php echo esc_attr( $urvenue_ws_cacheendpoint ); ?>">
						Clear Cache
					</button>
					<button class="uvsjs-copyendpoint uvs-btn uvs-btn-p" data-endpoint="<?php echo esc_attr( $urvenue_ws_cacheendpoint ); ?>">
						Copy Endpoint
					</button>
				</div>
			<?php endif; ?>
		</div>
		<div class="uvsvalue">
			<?php echo wp_kses( $urvenue_ws_inpendpoint, urvenue_ws_adm_allowed_admin_html() ); ?>
		</div>
    </div>
	
	<?php if($urvenue_ws_isuvuser): ?>
		<?php if($urvenue_ws_ishostedwpe): ?>
			<div class="uvs-infolist-item uvs-clearfix">
				<div class="uvsname">WP Engine Installation ID
					<small>GET Request to: <a href="https://api.wpengineapi.com/v1/sites/">https://api.wpengineapi.com/v1/sites/</a>
						*Use the site_id field.
					</small>
				</div>
				<div class="uvsvalue">
					<?php echo wp_kses( $urvenue_ws_wpeinstid, urvenue_ws_adm_allowed_admin_html() ); ?>
				</div>
			</div>

			<div class="uvs-infolist-item uvs-clearfix">
				<div class="uvsname">WP Engine Username</div>
				<div class="uvsvalue">
					<?php echo wp_kses( $urvenue_ws_inpwpeusername, urvenue_ws_adm_allowed_admin_html() ); ?>
				</div>
			</div>

			<div class="uvs-infolist-item uvs-clearfix" style="border:none;">
				<div class="uvsname">WP Engine Password</div>
				<div class="uvsvalue">
					<?php echo wp_kses( $urvenue_ws_inpwpepassword, urvenue_ws_adm_allowed_admin_html() ); ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="uvs-admin-opt-spacedivi"></div>

		<div class="uvs-admin-opt-title">UWS Feed</div>
		<div class="uvs-admin-opt-subtitle">The below API KEY will be used to access the Clear Cache Endpoint.</div>

		<div class="uvs-infolist-item uvs-clearfix">
			<div class="uvsname">Cache API KEY
				<small>(Auto-generated)</small>
			</div>
			<div class="uvsvalue">
				<?php echo wp_kses( $urvenue_ws_inpapikey, urvenue_ws_adm_allowed_admin_html() ); ?>
			</div>
		</div>
    <?php endif; ?>
</div>