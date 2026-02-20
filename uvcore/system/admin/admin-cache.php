<?php
$uvswpeinstid = uvs_get_adminfieldhtml("cache->wpeinst");
$uvsinpwpeusername = uvs_get_adminfieldhtml("cache->username");
$uvsinpwpepassword = uvs_get_adminfieldhtml("cache->password");
$uvsinpendpoint = uvs_get_adminfieldhtml("cache->endpoint");
$uvcacheendpoint = uvs_get_fieldvalue_by_stringroute("cache->endpoint");

$uvsinpapikey = uvs_get_adminfieldhtml("cache->apikey");
$uvisuvuser = uvs_is_uv_email();
$uvsishostedwpe = uvs_is_hosted_on_wpengine();
?>
<div id="uvs-admin-cache" class="uvs-admin-opt-section <?php /* Old: echo $uvs_admin_optstabs_state['cache']; */ echo esc_attr( $uvs_admin_optstabs_state['cache'] ); ?>">
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
	

	<div class="uvs-infolist-item uvs-clearfix" <?php if(!$uvisuvuser): echo "style= border:none;"; endif;?> >
		<div class="uvsname">Clear Cache Endpoint
			<small>Provide this URL to the Backend Team.</small>
			<?php if(uvs_get_fieldvalue_by_stringroute("cache->endpoint") != ""): ?>
				<div class="uvs-btn-group">
					<button class="uvsjs-clearcache uvs-btn uvs-btn-p" data-endpoint="<?php /* Old: echo $uvcacheendpoint; */ echo esc_attr( $uvcacheendpoint ); ?>">
						Clear Cache
					</button>
					<button class="uvsjs-copyendpoint uvs-btn uvs-btn-p" data-endpoint="<?php /* Old: echo $uvcacheendpoint; */ echo esc_attr( $uvcacheendpoint ); ?>">
						Copy Endpoint
					</button>
				</div>
			<?php endif; ?>
		</div>
		<div class="uvsvalue">
			<?php // @Axl ?>
			<?php /* Old: echo $uvsinpendpoint; */ ?>
			<?php echo wp_kses( $uvsinpendpoint, uvs_allowed_admin_html() ); ?>
			<?php // @Axl End ?>
		</div>
    </div>
	
	<?php if($uvisuvuser): ?>
		<?php if($uvsishostedwpe): ?>
			<div class="uvs-infolist-item uvs-clearfix">
				<div class="uvsname">WP Engine Installation ID
					<small>GET Request to: <a href="https://api.wpengineapi.com/v1/sites/">https://api.wpengineapi.com/v1/sites/</a>
						*Use the site_id field.
					</small>
				</div>
				<div class="uvsvalue">
					<?php // @Axl ?>
					<?php /* Old: echo $uvswpeinstid; */ ?>
					<?php echo wp_kses( $uvswpeinstid, uvs_allowed_admin_html() ); ?>
					<?php // @Axl End ?>
				</div>
			</div>

			<div class="uvs-infolist-item uvs-clearfix">
				<div class="uvsname">WP Engine Username</div>
				<div class="uvsvalue">
					<?php // @Axl ?>
					<?php /* Old: echo $uvsinpwpeusername; */ ?>
					<?php echo wp_kses( $uvsinpwpeusername, uvs_allowed_admin_html() ); ?>
					<?php // @Axl End ?>
				</div>
			</div>

			<div class="uvs-infolist-item uvs-clearfix" style="border:none;">
				<div class="uvsname">WP Engine Password</div>
				<div class="uvsvalue">
					<?php // @Axl ?>
					<?php /* Old: echo $uvsinpwpepassword; */ ?>
					<?php echo wp_kses( $uvsinpwpepassword, uvs_allowed_admin_html() ); ?>
					<?php // @Axl End ?>
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
				<?php // @Axl ?>
				<?php /* Old: echo $uvsinpapikey; */ ?>
				<?php echo wp_kses( $uvsinpapikey, uvs_allowed_admin_html() ); ?>
				<?php // @Axl End ?>
			</div>
		</div>
    <?php endif; ?>
</div>