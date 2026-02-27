<div class="uvs-admin-apiconfigcont">

    <div class="uvs-admin-opt-title">UrVenue Configuration</div>
	<div class="uvs-admin-opt-descr">Enter you <strong>API KEY</strong> and <strong>Micro Code</strong> to make your initial configuration.<br><br> If you have a UrVuenue Account and you don't have an API KEY and Micro Code, please contact: <a href='mailto:support@urvenue.com'>support@urvenue.com</a>, if you don't have a UrVenue account yet, you can <a href="https://www.urvenue.com/request-demo/" target="_blank">Request a Demo</a></div>

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
        <button class="uvs-btn uvs-btn-p uvsjs-checkapiconfig" type="button" data-checkapiconfig="<?php /* Old: echo $uvs_admin_lib["loads"]["checkapiconfig"]; */ echo esc_url( $uvs_admin_lib["loads"]["checkapiconfig"] ); ?>">Submit</button>
        <div class="uv-loader-uvicon"></div>
    </div>
</div>