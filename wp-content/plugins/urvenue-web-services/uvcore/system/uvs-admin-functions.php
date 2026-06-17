<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function urvenue_ws_adm_get_core_library(){
	global $urvenue_ws_uvs_path, $urvenue_ws_core_defaults_lib;
	
	$uvscorelibarray = "";
	
	if(urvenue_ws_adm_is_wordpress()){//if is wordpress
        $uvscorelibjson = get_option("urvenue_ws_uvcore_lib");
        $uvscorelibarray = json_decode($uvscorelibjson, true);
    }
	if($urvenue_ws_uvs_path and file_exists($urvenue_ws_uvs_path . "/uvcore.lib.json")){
		global $wp_filesystem;
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		if ( empty( $wp_filesystem ) ) {
			WP_Filesystem();
		}
		$uvscorelibjson = $wp_filesystem->get_contents($urvenue_ws_uvs_path . "/uvcore.lib.json");
		$uvscorelibarray = json_decode($uvscorelibjson, true);
	}
	
	if(!is_array($uvscorelibarray) or !is_array($uvscorelibarray["system"]))
		$uvscorelibarray = false;

	$uvscorelibarray = (is_array($uvscorelibarray)) ? urvenue_ws_adm_lib_add_defaults($uvscorelibarray) : $urvenue_ws_core_defaults_lib;

	//Set Basic Variables if they are not pressent
	if(is_array($uvscorelibarray) and !isset($uvscorelibarray["system"]["path"]))
		$uvscorelibarray["system"]["path"] = "";
	if(is_array($uvscorelibarray) and !isset($uvscorelibarray["system"]["url"]))
		$uvscorelibarray["system"]["url"] = "";
	if(is_array($uvscorelibarray) and !isset($uvscorelibarray["system"]["library"]))
		$uvscorelibarray["system"]["library"] = "";
		
	return $uvscorelibarray;
}
// Add default values to library
function urvenue_ws_adm_lib_add_defaults($uvcorelibarray){
    global $urvenue_ws_core_defaults_lib;

	$uvnewcorelibarray = $uvcorelibarray;

	if(is_array($urvenue_ws_core_defaults_lib)){
		foreach($urvenue_ws_core_defaults_lib as $uvcoredeflv1key => $uvcoredeflv1){
			if(is_array($uvcoredeflv1)){
				foreach($uvcoredeflv1 as $uvcoredeflv2key => $uvcoredeflv2){
					if(!isset($uvnewcorelibarray[$uvcoredeflv1key][$uvcoredeflv2key])/* or !$uvnewcorelibarray[$uvcoredeflv1key][$uvcoredeflv2key]*/)
						$uvnewcorelibarray[$uvcoredeflv1key][$uvcoredeflv2key] = $uvcoredeflv2;
				}
			}
		}
	}

    return $uvnewcorelibarray;
}

//Get proxy url
function urvenue_ws_adm_get_proxyurl(){
	global $urvenue_ws_url;

	$uvproxyurl = "";
	if(function_exists('get_option') and function_exists('admin_url'))//is wordpress
		$uvproxyurl = admin_url('admin-ajax.php');
	else
		$uvproxyurl = $urvenue_ws_url . "/uvcore.proxy.php";

	return $uvproxyurl;
}

//Get Html for events views
function urvenue_ws_adm_get_eventsviews($uveventsviewsinfo){
	$uveventsviewshtml = "";

	if(is_array($uveventsviewsinfo)){
		uasort($uveventsviewsinfo, 'urvenue_ws_adm_order_keyvalue');

		foreach($uveventsviewsinfo as $uveventviewkey => $uveventsview){
			$uvviewshow = $uveventsview["show"];
			$uvvieworder = $uveventsview["order"];
			$uvviewlabel = $uveventsview["label"];
			$uvviewdefaultview = $uveventsview["defaultview"];
			$uvviewicon = $uveventsview["icon"];

			$uvsviewitemcontclass = ($uvviewshow) ? "" : "uvdisabled";

			$uveventsviewinputs = "<input class='uvsjson uvsinputorder' type='hidden' name='events[eventspage-views][$uveventviewkey][order]' value='$uvvieworder'>";
			$uveventsviewinputs .= "<input class='uvsjson' type='hidden' name='events[eventspage-views][$uveventviewkey][label]' value='$uvviewlabel'>";
			$uveventsviewinputs .= "<input class='uvsjson' type='hidden' name='events[eventspage-views][$uveventviewkey][icon]' value='$uvviewicon'>";

			$uvsviewswitchclass = ($uvviewshow) ? "uvs-on" : "";
			$uveventsviewuiswitch = "<div class='uvs-switch-ui $uvsviewswitchclass'><button class='uvsjs-trigger-switch uvsjs-listorderviewitemenable' type='button'><span class='uvs-lb-on'>Yes</span><span class='uvs-lb-off'>No</span></button><input class='uvsjson' type='hidden' name='events[eventspage-views][$uveventviewkey][show]' value='$uvviewshow' data-value-on='1' data-value-off='0'></div>";

			$uvsviewdefaultviewclass = ($uvviewdefaultview) ? "uvs-on" : "";
			$uveventsviewdefaultviewswitch = "<div class='uvs-switch-ui uvs-listorderviewdefswich $uvsviewdefaultviewclass'><button class='uvsjs-trigger-switch uvsjs-listorderviewitemdef' type='button'><span class='uvs-lb-on'>Yes</span><span class='uvs-lb-off'>No</span></button><input class='uvsjson' type='hidden' name='events[eventspage-views][$uveventviewkey][defaultview]' value='$uvviewdefaultview' data-value-on='1' data-value-off='0'></div>";

			$uveventsviewshtml .= "<div class='uvs-admin-listorderandview-item $uvsviewitemcontclass'><div class='uvname'>$uvviewlabel</div><div class='uvorvwswitch'><div class='uvslabel'>Enable</div><div class='uvstheswitch'>$uveventsviewuiswitch</div></div><div class='uvorvwswitch'><div class='uvslabel'>Default View</div><div class='uvstheswitch'>$uveventsviewdefaultviewswitch</div></div><div class='uvsactions'><a href='javascript:;' aria-label='Move Element Up' class='uvsjs-moveorderup uvs-listorderup'><i class='uwsicon-down-open'></i></a><a href='javascript:;' aria-label='Move Element Down' class='uvsjs-moveorderdown uvs-listorderdown'><i class='uwsicon-down-open'></i></a></div>$uveventsviewinputs</div>";
		}
	}

	return $uveventsviewshtml;
}

//Sort by helper, order by order(key) value
function urvenue_ws_adm_order_keyvalue($a, $b){
	return $a['order'] - $b['order'];
}

function urvenue_ws_adm_get_boxtabs_state($uvsactivetab){
	global $urvenue_ws_adm_adminbox_tabs;
	
	$uvsadminboxtabsstatus = array();
	
	if(is_array($urvenue_ws_adm_adminbox_tabs))
		foreach($urvenue_ws_adm_adminbox_tabs as $uvstabname){
			$uvsadminboxtabsstatus["$uvstabname"] = ($uvstabname == $uvsactivetab) ? "active" :  "";
		}
		
	return $uvsadminboxtabsstatus;
}

function urvenue_ws_adm_pullfeed($uvsfileurl){

	$response = wp_remote_get($uvsfileurl, array(
		'timeout' => 60,
	));
	$output = wp_remote_retrieve_body($response);
	
 	return($output);
}

function urvenue_ws_adm_admin_save_lib($uvslib){
	global $urvenue_ws_lib_path;

	global $wp_filesystem;
	if ( ! function_exists( 'WP_Filesystem' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}
	if ( empty( $wp_filesystem ) ) {
		WP_Filesystem();
	}

	if(urvenue_ws_adm_is_wordpress()){
		update_option("urvenue_ws_uvcore_lib", $uvslib);
		echo("saved");
	}
	else if( $wp_filesystem->is_writable( $urvenue_ws_lib_path ) ){
		$wp_filesystem->put_contents( $urvenue_ws_lib_path, $uvslib );

		echo("saved");
	}
	else
		echo("popup");
}

function urvenue_ws_adm_admin_venues_list_html(){
	global $urvenue_ws_core_lib;

	$uvsvenueslisthtml = "";

	if(isset($urvenue_ws_core_lib["venues"])){
		foreach($urvenue_ws_core_lib["venues"] as $uvsvenueinfo){
			$uvsvenuekey = $uvsvenueinfo["venuekey"];
			$uvsmanageentid = $uvsvenueinfo["manageentid"];
			$uvsresellerid = $uvsvenueinfo["resellerid"];
			$uvsproviderid = $uvsvenueinfo["providerid"];
			$uvsvenuename = $uvsvenueinfo["venuename"];
			$uvsvenuealias = (isset($uvsvenueinfo["venuealias"])) ? $uvsvenueinfo["venuealias"] : "";
			$uvsvenueforcealias = (isset($uvsvenueinfo["venueforcealias"])) ? $uvsvenueinfo["venueforcealias"] : "";
			$uvsvenuehideinevents = (isset($uvsvenueinfo["venuehideinevents"])) ? $uvsvenueinfo["venuehideinevents"] : "";
			$uvsvenuecode = $uvsvenueinfo["venuecode"];
			$uvsvenueuvid = $uvsvenueinfo["urvenueid"];
			$uvsvenueclientid = $uvsvenueinfo["clientid"];
			$uvsvenuelogo = $uvsvenueinfo["logourl"];
			$uvsvenueserver = $uvsvenueinfo["uvserver"];
			$uvsvenueisprimary = $uvsvenueinfo["isprimary"];

			$uvsvenuelogoclass = (!$uvsvenuelogo) ? "noimg" : "";
			$uvsvenueisprimary = ($uvsvenueisprimary) ? $uvsvenueisprimary : 0;
			$uvsvenueforcealias_checked = ($uvsvenueforcealias) ? " checked":"";
			$uvsvenuehideinevents_checked = ($uvsvenuehideinevents) ? " checked":"";

			$uvsvenueforminfo = "<input class='uvsjson' type='hidden' name='venues[$uvsvenuekey][venuekey]' value='$uvsvenuekey'>";
			$uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuekey][manageentid]' value='$uvsmanageentid'>";
			$uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuekey][providerid]' value='$uvsproviderid'>";
			$uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuekey][resellerid]' value='$uvsresellerid'>";
			$uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuekey][venuename]' value='$uvsvenuename'>";
			$uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuekey][venuecode]' value='$uvsvenuecode'>";
			$uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuekey][urvenueid]' value='$uvsvenueuvid'>";
			$uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuekey][clientid]' value='$uvsvenueclientid'>";
			$uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuekey][logourl]' value='$uvsvenuelogo'>";
			$uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuekey][uvserver]' value='$uvsvenueserver'>";
			$uvsvenueforminfo .= "<input class='uvsjson venueprimary' type='hidden' name='venues[$uvsvenuekey][isprimary]' value='$uvsvenueisprimary'>";

			$uvsvenueisprimarylabel = ($uvsvenueisprimary) ? "Is Primary" : "Make Primary";
			$uvsvenueisprimaryclass = ($uvsvenueisprimary) ? "active" : "";

			$uvsvenueidhtml = ($uvsvenueuvid) ? "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue ID:</div><div class='uvsvalue'>$uvsvenueuvid</div></div>" : "";
            $uvsclientidhtml = ($uvsvenueclientid) ? "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Client ID:</div><div class='uvsvalue'>$uvsvenueclientid</div></div>" : "";
            $uvsserverhtml = ($uvsvenueserver) ? "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>UrVenue Server:</div><div class='uvsvalue'>$uvsvenueserver</div></div>" : "";

			//$uvvenuealiasinput = "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Use Alias as Venue Name:</div><div class='uvsvalue'><input type='checkbox' name='venues[$uvsvenuekey][venueforcealias]' value='1' class='uvsjson'$uvsvenueforcealias_checked></div></div>";

			$uvvenuealiasswitchclass = ($uvsvenueforcealias) ? "uvs-on" : "";
			$uvvenuealiasinput = "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Use Alias as Venue Name:</div><div class='uvsvalue'><div class='uvs-switch-ui $uvvenuealiasswitchclass'><button class='uvsjs-trigger-switch' type='button'><span class='uvs-lb-on'>Yes</span><span class='uvs-lb-off'>No</span></button><input class='uvsjson' type='hidden' name='venues[$uvsvenuekey][venueforcealias]' value='$uvsvenueforcealias' data-value-on='1' data-value-off=''></div></div></div>";

			//$uvhideeventsinput = "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Hide in Events:</div><div class='uvsvalue'><input type='checkbox' name='venues[$uvsvenuekey][venuehideinevents]' value='1' class='uvsjson'$uvsvenuehideinevents_checked></div></div>";

			$uvhideeventsswitchclass = ($uvsvenuehideinevents) ? "uvs-on" : "";
			$uvhideeventsinput = "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Hide Events:</div><div class='uvsvalue'><div class='uvs-switch-ui $uvhideeventsswitchclass'><button class='uvsjs-trigger-switch' type='button'><span class='uvs-lb-on'>Yes</span><span class='uvs-lb-off'>No</span></button><input class='uvsjson' type='hidden' name='venues[$uvsvenuekey][venuehideinevents]' value='$uvsvenuehideinevents' data-value-on='1' data-value-off=''></div></div></div>";

			$uvsvenueslisthtml .= "<div class='uvs-admin-venueinf uvs-admin-venueinf-vc-$uvsvenuecode'>$uvsvenueforminfo<div class='uvs-infolist-item-img $uvsvenuelogoclass' style='background-image: url($uvsvenuelogo);'></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue KEY:</div><div class='uvsvalue'><strong>$uvsvenuekey</strong></div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Name:</div><div class='uvsvalue'>$uvsvenuename</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Name Alias:</div><div class='uvsvalue'><input type='text' name='venues[$uvsvenuekey][venuealias]' value='$uvsvenuealias' class='uvsjson'></div></div>{$uvvenuealiasinput}{$uvhideeventsinput}<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Code:</div><div class='uvsvalue'>$uvsvenuecode</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Manageentid:</div><div class='uvsvalue'>$uvsmanageentid</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Providerid:</div><div class='uvsvalue'>$uvsproviderid</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Resellerid:</div><div class='uvsvalue'>$uvsresellerid</div></div>" . $uvsvenueidhtml . $uvsclientidhtml . $uvsserverhtml . "<div class='actions'><a class='uvsjs-triggervenueprimary $uvsvenueisprimaryclass' href='javascript:;' data-isprimary='$uvsvenueisprimary'>$uvsvenueisprimarylabel</a><a class='uvsjs-removevenue' href='javascript:;'>Remove</a></div></div>";
		}
	}

	return $uvsvenueslisthtml;
}

function urvenue_ws_adm_get_flyertypes_html($uvsflyertypevalue = ""){
	global $urvenue_ws_adm_flyertypes_lib;

	$uvsflyertypes = "";
	if(is_array($urvenue_ws_adm_flyertypes_lib)){
		foreach($urvenue_ws_adm_flyertypes_lib as $uvsflyertype){
			$uvsflyertypeselattr = ($uvsflyertype == $uvsflyertypevalue) ? "selected" : "";
			$uvsflyertypes .= "<option value='$uvsflyertype' $uvsflyertypeselattr>$uvsflyertype</option>";
		}
	}

	return $uvsflyertypes;
}

function urvenue_ws_adm_get_flyerratios_html($uvsflyerratiovalue = ""){
	global $urvenue_ws_adm_flyersratios_lib, $urvenue_ws_core_defaults_lib;

	$uvsflyerratios = "";
	if(is_array($urvenue_ws_adm_flyersratios_lib)){
		foreach($urvenue_ws_adm_flyersratios_lib as $uvsflyerratio){
			$uvsflyerratioselattr = ($uvsflyerratio == $uvsflyerratiovalue) ? "selected" : "";
			$uvsflyerratios .= "<option value='$uvsflyerratio' $uvsflyerratioselattr>$uvsflyerratio</option>";
		}
	}

	return $uvsflyerratios;
}

function urvenue_ws_adm_get_flyerlocdivhtml($uvflyerloccode = ""){
	global $urvenue_ws_core_lib, $urvenue_ws_core_defaults_lib;

	$uvsflyerlocdivhtml = "";
	$uvsflyerloccount = 0;

	if(is_array($urvenue_ws_core_lib["flyers"]) and is_array($urvenue_ws_core_lib["flyers"]["$uvflyerloccode"])){
		foreach($urvenue_ws_core_lib["flyers"]["$uvflyerloccode"] as $uvsflyerset){
			$uvsflyersettype = $uvsflyerset["type"];
			$uvsflyersetratio = $uvsflyerset["ratio"];
			
			$uvsflyertypeshtml = urvenue_ws_adm_get_flyertypes_html($uvsflyersettype);
			$uvsflyerrationshtml = urvenue_ws_adm_get_flyerratios_html($uvsflyersetratio);

			$uvsflyerlocdivhtml .= "<div class='uvs-infolist-groupnoti' data-nflyerset='$uvsflyerloccount' data-flyerloc='$uvflyerloccode'><div class='uvs-infolist-item'><div class='uvsname'>Flyer Type:</div><div class='uvsvalue'><select class='uvsjson uvsflyertype' name='flyers[$uvflyerloccode][$uvsflyerloccount][type]'><option value=''> - </option>$uvsflyertypeshtml</select></div></div><div class='uvs-infolist-item'><div class='uvsname'>Flyer Ratio:</div><div class='uvsvalue'><select class='uvsjson uvsflyerratio' name='flyers[$uvflyerloccode][$uvsflyerloccount][ratio]'><option value=''> - </option>$uvsflyerrationshtml</select></div></div><div class='actions'><a class='uvsjs-removeflyer' href='javascript:;'>Remove</a></div></div>";

			$uvsflyerloccount++;
		}
	}
	else{
		$uvsftypedefault = $urvenue_ws_core_defaults_lib["flyers"][$uvflyerloccode]["type"];
		$uvsfratiodefault = $urvenue_ws_core_defaults_lib["flyers"][$uvflyerloccode]["ratio"];
		$uvsflyertypeshtml = urvenue_ws_adm_get_flyertypes_html($uvsftypedefault);
		$uvsflyerrationshtml = urvenue_ws_adm_get_flyerratios_html($uvsfratiodefault);

		$uvsflyerlocdivhtml = "<div class='uvs-infolist-groupnoti' data-nflyerset='0' data-flyerloc='$uvflyerloccode'><div class='uvs-infolist-item'><div class='uvsname'>Flyer Type:</div><div class='uvsvalue'><select class='uvsjson uvsflyertype' name='flyers[$uvflyerloccode][0][type]'><option value=''> - </option>$uvsflyertypeshtml</select></div></div><div class='uvs-infolist-item'><div class='uvsname'>Flyer Ratio:</div><div class='uvsvalue'><select class='uvsjson uvsflyerratio' name='flyers[$uvflyerloccode][0][ratio]'><option value=''> - </option>$uvsflyerrationshtml</select></div></div><div class='actions'><a class='uvsjs-removeflyer' href='javascript:;'>Remove</a></div></div>";
	}

	return $uvsflyerlocdivhtml;
}

function urvenue_ws_adm_allowed_admin_html() {
	return array(
		'input'  => array( 'type' => true, 'id' => true, 'class' => true, 'name' => true, 'value' => true, 'data-value-on' => true, 'data-value-off' => true, 'placeholder' => true, 'readonly' => true, 'disabled' => true ),
		'select' => array( 'id' => true, 'class' => true, 'name' => true ),
		'option' => array( 'value' => true, 'selected' => true ),
		'div'    => array( 'class' => true ),
		'button' => array( 'type' => true, 'class' => true ),
		'span'   => array( 'class' => true ),
	);
}

function urvenue_ws_adm_get_adminfieldhtml($uvsfieldname){
	global $urvenue_ws_adm_admin_fields;

	$uvsfieldhtml = "";

	if(is_array($urvenue_ws_adm_admin_fields[$uvsfieldname])){
		$uvsinputtype = $urvenue_ws_adm_admin_fields[$uvsfieldname]["type"];
		$uvsinputname = $urvenue_ws_adm_admin_fields[$uvsfieldname]["name"];
		$uvsinputaddclass = (isset($urvenue_ws_adm_admin_fields[$uvsfieldname]["addclass"])) ? $urvenue_ws_adm_admin_fields[$uvsfieldname]["addclass"] : "";
		$uvsinputattrs = (isset($urvenue_ws_adm_admin_fields[$uvsfieldname]["addattrs"])) ? $urvenue_ws_adm_admin_fields[$uvsfieldname]["addattrs"] : "";
		$uvsinputidattr = (isset($urvenue_ws_adm_admin_fields[$uvsfieldname]["id"])) ? "id='" . $urvenue_ws_adm_admin_fields[$uvsfieldname]["id"] . "'" : "";
		$uvsinputvalue = urvenue_ws_adm_get_fieldvalue_by_stringroute($uvsfieldname);
		$uvsinputaddclass = ($uvsinputtype == "colorpicker") ? $uvsinputaddclass . " uvs-color-field" : $uvsinputaddclass;
		$uvsinputtype = ($uvsinputtype == "colorpicker" or $uvsinputtype == "page") ? "text" : $uvsinputtype;

		$uvsinputvalue_esc    = esc_attr( $uvsinputvalue );
		$uvsinputname_esc     = esc_attr( $uvsinputname );
		$uvsinputaddclass_esc = esc_attr( $uvsinputaddclass );
		$uvsinputtype_esc     = esc_attr( $uvsinputtype );
		$uvsinputidattr_esc   = ( isset( $urvenue_ws_adm_admin_fields[$uvsfieldname]["id"] ) ) ? "id='" . esc_attr( $urvenue_ws_adm_admin_fields[$uvsfieldname]["id"] ) . "'" : "";

		if($urvenue_ws_adm_admin_fields[$uvsfieldname]["type"] == "page" and urvenue_ws_adm_is_wordpress()){
			$uvpagesopts = urvenue_ws_adm_get_wppages($uvsinputvalue);

			$uvsfieldhtml = "<select $uvsinputidattr_esc class='uvsjson $uvsinputaddclass_esc' name='$uvsinputname_esc'><option value=''>Select Page</option>$uvpagesopts</select>";
		}
		else if($uvsinputtype == "select"){
			$uvsselectvalues = $urvenue_ws_adm_admin_fields[$uvsfieldname]["values"];

			$uvsselectvalueshtml = urvenue_ws_adm_get_fieldvalueshtml($uvsselectvalues, $uvsinputvalue);

			$uvsfieldhtml = "<select $uvsinputidattr_esc class='uvsjson $uvsinputaddclass_esc' name='$uvsinputname_esc'>$uvsselectvalueshtml</select>";
		}
		else if($uvsinputtype == "switchui"){
			$uvsswithclaass = ($uvsinputvalue == 1) ? "uvs-on" : "";

			$uvsswithclaass_esc = esc_attr( $uvsswithclaass );
			$uvsfieldhtml = "<div class='uvs-switch-ui $uvsswithclaass_esc'><button class='uvsjs-trigger-switch' type='button'><span class='uvs-lb-on'>Yes</span><span class='uvs-lb-off'>No</span></button>
			<input $uvsinputidattr_esc class='uvsjson $uvsinputaddclass_esc' type='hidden' name='$uvsinputname_esc' value='$uvsinputvalue_esc' data-value-on='1' data-value-off='0' $uvsinputattrs></div>";
		}
		else
			$uvsfieldhtml = "<input $uvsinputidattr_esc class='uvsjson $uvsinputaddclass_esc' type='$uvsinputtype_esc' name='$uvsinputname_esc' value='$uvsinputvalue_esc' $uvsinputattrs>";
	}

	return $uvsfieldhtml;
}

function urvenue_ws_adm_get_fieldvalue_by_stringroute($uvsstringroute){
	global $urvenue_ws_core_lib, $urvenue_ws_core_defaults_lib;

	$uvsroutecurval = $urvenue_ws_core_lib;
	$uvsroutesplit = explode("->", $uvsstringroute);
	if(is_array($uvsroutesplit)){
		foreach($uvsroutesplit as $uvsrouteval){
			if(isset($uvsroutecurval[$uvsrouteval])){
				$uvsroutecurval = $uvsroutecurval[$uvsrouteval];
			}
		}
	}

	$uvsroutecurval = (is_array($uvsroutecurval)) ? "" : $uvsroutecurval;

	if(!$uvsroutecurval and $uvsroutecurval != "0"){
		$uvsroutecurval = $urvenue_ws_core_defaults_lib;
		if(is_array($uvsroutesplit)){
			foreach($uvsroutesplit as $uvsrouteval){
				if(isset($uvsroutecurval[$uvsrouteval])){
					$uvsroutecurval = $uvsroutecurval[$uvsrouteval];
				}
			}
		}
	}

	$uvsroutecurval = (is_array($uvsroutecurval)) ? "" : $uvsroutecurval;

	return $uvsroutecurval;
}

function urvenue_ws_adm_get_fieldvalueshtml($uvsselectvalues, $uvsinputvalue){
	$uvsselectvalueshtml = "";

	if(is_array($uvsselectvalues)){
		foreach($uvsselectvalues as $uvsslvalue){
			if(is_array($uvsslvalue)){
				$uvsslvaluelabel = $uvsslvalue["label"];
				$uvsslvalueval = $uvsslvalue["value"];
			}
			else{
				$uvsslvaluelabel = $uvsslvalue;
				$uvsslvalueval = $uvsslvalue;
			}

			$uvsslvalueattr = ($uvsinputvalue == $uvsslvalueval) ? "selected" : "";

		$uvsselectvalueshtml .= "<option value='" . esc_attr( $uvsslvalueval ) . "' $uvsslvalueattr>" . esc_html( $uvsslvaluelabel ) . "</option>";
		}
	}

	return $uvsselectvalueshtml;
}

// Get string returns string for url
function urvenue_ws_adm_get_linkstring($string){
    $string = urvenue_ws_adm_get_string2u($string, "-");
    $string = preg_replace("|[^a-zA-Z0-9_]|", "-", $string);
    $string = preg_replace("|-+|", "-", $string);
    
    if(substr($string, -1) == "-") 
        $string = substr($string, 0, -1);
        
    if(substr($string, 0, 1) == "-") 
        $string=substr($string, 1);
        
    $string = strtolower($string);
    
    return($string);
}

// Clean special chars
function urvenue_ws_adm_get_string2u($string, $uchar){
    global $urvenue_ws_adm_cleanchars;
 
    if(!$uchar)
        $uchar="-";
    $string = strtr($string, $urvenue_ws_adm_cleanchars);

    $string = ucwords($string);

    $string=preg_replace("|[&][#0-9a-zA-Z]+[;]|", "", $string);
    $string=preg_replace("|[^0-9a-zA-Z]|", $uchar, $string);
    $string=preg_replace("|[$uchar][$uchar]+|", "$uchar", $string);
    
    return $string;
}

/* UV Error */
function urvenue_ws_adm_uverror($uvserror){
	echo esc_html( $uvserror );
}

//Check if is wordpress
function urvenue_ws_adm_is_wordpress(){
	$uviswordpress = 0;

	if(function_exists('get_option') and function_exists('add_menu_page'))
		$uviswordpress = 1;
	
	return $uviswordpress;
}

//Get wordpress pages
function urvenue_ws_adm_get_wppages($uvselpageid = ""){
	$uvpagelist = "";

	$uvsargs = array(
		'post_type'    => 'page',
		'post_status'  => array( 'publish', 'draft', 'private' ),
		'orderby'      => 'post_title',
		'posts_per_page' => -1,
	);

	$uvspages = new WP_Query($uvsargs);

	while($uvspages -> have_posts()){
		$uvspages -> the_post();
		$uvpagetitle = get_the_title();
		$uvpageid = get_the_ID();
	
		$uvpageparent = wp_get_post_parent_id($uvpageid);
		$uvpageparenttitle = get_the_title($uvpageparent);
		
		$uvselected = $uvparenttag = "";
		if($uvselpageid == $uvpageid)
			$uvselected = "selected";
		if($uvpageparent > 0)
			$uvparenttag = "$uvpageparenttitle > ";
		
		$uvpagelist .= "<option value='" . esc_attr( $uvpageid ) . "' $uvselected>" . esc_html( $uvparenttag . $uvpagetitle ) . "</option>";
	}

	return $uvpagelist;
}

/**
 * Checks if the current user's email is a UrVenue email.
 *
 * @return int Returns 1 if the user's email is a UrVenue email, 0 otherwise.
 */
function urvenue_ws_adm_is_uv_email(){
	$uvuseremail = "";
	$uvemailuv = 0;

	if(urvenue_ws_adm_is_wordpress()){
		$uvcurrentuser = wp_get_current_user();
		$uvuseremail = $uvcurrentuser->user_email;

		if(strpos($uvuseremail, "@urvenue.com") !== false)
			$uvemailuv = 1;
	}

	return $uvemailuv;
}

//Checks if is hosted on wpe
function urvenue_ws_adm_is_hosted_on_wpengine() {
	return function_exists('is_wpe') && is_wpe();
}