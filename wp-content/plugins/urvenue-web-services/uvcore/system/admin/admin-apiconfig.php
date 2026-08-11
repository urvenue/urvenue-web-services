<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="uvs-admin-apiconfigcont">

    <div class="uvs-admin-opt-title">UrVenue Configuration</div>
	<div class="uvs-admin-opt-descr">This plugin works exclusively with the <a href="https://www.urvenue.com/" target="_blank" rel="noopener">UrVenue</a> platform and needs an active UrVenue account and service subscription before it can display anything.<br><br>Enter your <strong>API KEY</strong> and <strong>Micro Code</strong> to make your initial configuration. If you have an UrVenue account but not your API KEY and Micro Code, please contact: <a href='mailto:support@urvenue.com'>support@urvenue.com</a>. If you don't have an UrVenue account yet, you can <a href="https://www.urvenue.com/request-demo/" target="_blank" rel="noopener">Request a Demo</a>.</div>

    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">API KEY</div>
		<div class="uvsvalue">
            <input id="apiconfig-apikey" class="uvs-input-big" type="text" name="apiconfig-apikey" value="">
        </div>
    </div>
    <div class="uvs-infolist-item uvs-clearfix">
		<div class="uvsname">Micro Code</div>
		<div class="uvsvalue">
            <input id="apiconfig-microcode" class="uvs-input-big" type="text" name="apiconfig-microcode" value="">
        </div>
    </div>

    <div class="uvs-errorbox">
        <i class="uwsicon-warning-empty"></i>
        <div class="uvs-errorbox-msg uvsdy-apiconfigerror"></div>
    </div>

    <div class="uvs-admin-apiconfig-actions">
        <button class="uvs-btn uvs-btn-p uvsjs-checkapiconfig" type="button" data-checkapiconfig="<?php echo esc_url( add_query_arg( 'uws_nonce', wp_create_nonce( 'uvsp_checkapiconfig' ), $urvenue_ws_adm_admin_lib["loads"]["checkapiconfig"] ) ); ?>">Submit</button>
        <div class="uv-loader-uvicon"></div>
    </div>
</div>