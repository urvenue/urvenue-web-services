<?php
function uvs_get_core_library(){
	global $uvs_path, $uv_core_defaults_lib;
	
	$uvscorelibarray = "";
	
	if(uvs_is_wordpress()){//if is wordpress
        $uvscorelibjson = get_option("uvcore_lib");
        $uvscorelibarray = json_decode($uvscorelibjson, true);
    }
	if($uvs_path and file_exists($uvs_path . "/uvcore.lib.json")){
		$uvscorelibjson = file_get_contents($uvs_path . "/uvcore.lib.json");
		$uvscorelibarray = json_decode($uvscorelibjson, true);
	}
	
	if(!is_array($uvscorelibarray) or !is_array($uvscorelibarray["system"]))
		$uvscorelibarray = false;

	$uvscorelibarray = (is_array($uvscorelibarray)) ? uvs_lib_add_defaults($uvscorelibarray) : $uv_core_defaults_lib;

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
function uvs_lib_add_defaults($uvcorelibarray){
    global $uv_core_defaults_lib;

    //$uvnewcorelibarray = array_merge($uv_core_defaults_lib, $uvcorelibarray);
	$uvnewcorelibarray = $uvcorelibarray;

	if(is_array($uv_core_defaults_lib)){
		foreach($uv_core_defaults_lib as $uvcoredeflv1key => $uvcoredeflv1){
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
function uvs_get_proxyurl(){
	global $uvs_url;

	$uvproxyurl = "";
	if(function_exists('get_option') and function_exists('admin_url'))//is wordpress
		$uvproxyurl = admin_url('admin-ajax.php');
	else
		$uvproxyurl = $uvs_url . "/uvcore.proxy.php";

	return $uvproxyurl;
}

//Get Html for events views
function uvs_get_eventsviews($uveventsviewsinfo){
	$uveventsviewshtml = "";

	if(is_array($uveventsviewsinfo)){
		uasort($uveventsviewsinfo, 'uvs_order_keyvalue');

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
function uvs_order_keyvalue($a, $b){
	return $a['order'] - $b['order'];
}

function uvs_get_boxtabs_state($uvsactivetab){
	global $uvs_adminbox_tabs;
	
	$uvsadminboxtabsstatus = array();
	
	if(is_array($uvs_adminbox_tabs))
		foreach($uvs_adminbox_tabs as $uvstabname){
			$uvsadminboxtabsstatus["$uvstabname"] = ($uvstabname == $uvsactivetab) ? "active" :  "";
		}
		
	return $uvsadminboxtabsstatus;
}
function uvs_pullfeed($uvsfileurl){
	// TESTING @Axl
 	// $ch = curl_init();
 	// curl_setopt($ch, CURLOPT_URL, $uvsfileurl);
 	// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 	// curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 5);
 	// curl_setopt($ch, CURLOPT_TIMEOUT, 60);

 	// $output = curl_exec($ch);
	$response = wp_remote_get($uvsfileurl, array(
		'timeout' => 60,
	));
	$output = wp_remote_retrieve_body($response);
	
 	return($output);
}
function uvs_admin_save_lib($uvslib){
	global $uvs_lib_path;

	if(uvs_is_wordpress()){
		update_option("uvcore_lib", $uvslib);
		echo("saved");
	}
	else if(is_writable($uvs_lib_path)){
		$fp = fopen("$uvs_lib_path", "w+");
		fwrite($fp, $uvslib);
		fclose($fp);

		echo("saved");
	}
	else
		echo("popup");
}
function uvs_admin_venues_list_html(){
	global $uvs_core_lib;

	$uvsvenueslisthtml = "";

	if(isset($uvs_core_lib["venues"])){
		foreach($uvs_core_lib["venues"] as $uvsvenueinfo){
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
function uvs_get_flyertypes_html($uvsflyertypevalue = ""){
	global $uvs_flyertypes_lib;

	$uvsflyertypes = "";
	if(is_array($uvs_flyertypes_lib)){
		foreach($uvs_flyertypes_lib as $uvsflyertype){
			$uvsflyertypeselattr = ($uvsflyertype == $uvsflyertypevalue) ? "selected" : "";
			$uvsflyertypes .= "<option value='$uvsflyertype' $uvsflyertypeselattr>$uvsflyertype</option>";
		}
	}

	return $uvsflyertypes;
}
function uvs_get_flyerratios_html($uvsflyerratiovalue = ""){
	global $uvs_flyersratios_lib, $uv_core_defaults_lib;

	$uvsflyerratios = "";
	if(is_array($uvs_flyersratios_lib)){
		foreach($uvs_flyersratios_lib as $uvsflyerratio){
			$uvsflyerratioselattr = ($uvsflyerratio == $uvsflyerratiovalue) ? "selected" : "";
			$uvsflyerratios .= "<option value='$uvsflyerratio' $uvsflyerratioselattr>$uvsflyerratio</option>";
		}
	}

	return $uvsflyerratios;
}
function uvs_get_flyerlocdivhtml($uvflyerloccode = ""){
	global $uvs_core_lib, $uv_core_defaults_lib;

	$uvsflyerlocdivhtml = "";
	$uvsflyerloccount = 0;

	if(is_array($uvs_core_lib["flyers"]) and is_array($uvs_core_lib["flyers"]["$uvflyerloccode"])){
		foreach($uvs_core_lib["flyers"]["$uvflyerloccode"] as $uvsflyerset){
			$uvsflyersettype = $uvsflyerset["type"];
			$uvsflyersetratio = $uvsflyerset["ratio"];
			
			$uvsflyertypeshtml = uvs_get_flyertypes_html($uvsflyersettype);
			$uvsflyerrationshtml = uvs_get_flyerratios_html($uvsflyersetratio);

			$uvsflyerlocdivhtml .= "<div class='uvs-infolist-groupnoti' data-nflyerset='$uvsflyerloccount' data-flyerloc='$uvflyerloccode'><div class='uvs-infolist-item'><div class='uvsname'>Flyer Type:</div><div class='uvsvalue'><select class='uvsjson uvsflyertype' name='flyers[$uvflyerloccode][$uvsflyerloccount][type]'><option value=''> - </option>$uvsflyertypeshtml</select></div></div><div class='uvs-infolist-item'><div class='uvsname'>Flyer Ratio:</div><div class='uvsvalue'><select class='uvsjson uvsflyerratio' name='flyers[$uvflyerloccode][$uvsflyerloccount][ratio]'><option value=''> - </option>$uvsflyerrationshtml</select></div></div><div class='actions'><a class='uvsjs-removeflyer' href='javascript:;'>Remove</a></div></div>";

			$uvsflyerloccount++;
		}
	}
	else{
		$uvsftypedefault = $uv_core_defaults_lib["flyers"][$uvflyerloccode]["type"];
		$uvsfratiodefault = $uv_core_defaults_lib["flyers"][$uvflyerloccode]["ratio"];
		$uvsflyertypeshtml = uvs_get_flyertypes_html($uvsftypedefault);
		$uvsflyerrationshtml = uvs_get_flyerratios_html($uvsfratiodefault);

		$uvsflyerlocdivhtml = "<div class='uvs-infolist-groupnoti' data-nflyerset='0' data-flyerloc='$uvflyerloccode'><div class='uvs-infolist-item'><div class='uvsname'>Flyer Type:</div><div class='uvsvalue'><select class='uvsjson uvsflyertype' name='flyers[$uvflyerloccode][0][type]'><option value=''> - </option>$uvsflyertypeshtml</select></div></div><div class='uvs-infolist-item'><div class='uvsname'>Flyer Ratio:</div><div class='uvsvalue'><select class='uvsjson uvsflyerratio' name='flyers[$uvflyerloccode][0][ratio]'><option value=''> - </option>$uvsflyerrationshtml</select></div></div><div class='actions'><a class='uvsjs-removeflyer' href='javascript:;'>Remove</a></div></div>";
	}

	return $uvsflyerlocdivhtml;
}
// @Axl
function uvs_allowed_admin_html() {
	return array(
		'input'  => array( 'type' => true, 'id' => true, 'class' => true, 'name' => true, 'value' => true, 'data-value-on' => true, 'data-value-off' => true, 'placeholder' => true, 'readonly' => true, 'disabled' => true ),
		'select' => array( 'id' => true, 'class' => true, 'name' => true ),
		'option' => array( 'value' => true, 'selected' => true ),
		'div'    => array( 'class' => true ),
		'button' => array( 'type' => true, 'class' => true ),
		'span'   => array( 'class' => true ),
	);
}
// @Axl End

function uvs_get_adminfieldhtml($uvsfieldname){
	global $uvs_admin_fields;

	$uvsfieldhtml = "";

	if(is_array($uvs_admin_fields[$uvsfieldname])){
		$uvsinputtype = $uvs_admin_fields[$uvsfieldname]["type"];
		$uvsinputname = $uvs_admin_fields[$uvsfieldname]["name"];
		$uvsinputaddclass = (isset($uvs_admin_fields[$uvsfieldname]["addclass"])) ? $uvs_admin_fields[$uvsfieldname]["addclass"] : "";
		$uvsinputattrs = (isset($uvs_admin_fields[$uvsfieldname]["addattrs"])) ? $uvs_admin_fields[$uvsfieldname]["addattrs"] : "";
		$uvsinputidattr = (isset($uvs_admin_fields[$uvsfieldname]["id"])) ? "id='" . $uvs_admin_fields[$uvsfieldname]["id"] . "'" : "";
		$uvsinputvalue = uvs_get_fieldvalue_by_stringroute($uvsfieldname);
		$uvsinputaddclass = ($uvsinputtype == "colorpicker") ? $uvsinputaddclass . " uvs-color-field" : $uvsinputaddclass;
		$uvsinputtype = ($uvsinputtype == "colorpicker" or $uvsinputtype == "page") ? "text" : $uvsinputtype;

		// @Axl
		$uvsinputvalue_esc    = esc_attr( $uvsinputvalue );
		$uvsinputname_esc     = esc_attr( $uvsinputname );
		$uvsinputaddclass_esc = esc_attr( $uvsinputaddclass );
		$uvsinputtype_esc     = esc_attr( $uvsinputtype );
		$uvsinputidattr_esc   = ( isset( $uvs_admin_fields[$uvsfieldname]["id"] ) ) ? "id='" . esc_attr( $uvs_admin_fields[$uvsfieldname]["id"] ) . "'" : "";
		// @Axl End

		if($uvs_admin_fields[$uvsfieldname]["type"] == "page" and uvs_is_wordpress()){
			$uvpagesopts = uvs_get_wppages($uvsinputvalue);

			// @Axl
			// $uvsfieldhtml = "<select $uvsinputidattr class='uvsjson $uvsinputaddclass' name='$uvsinputname'><option value=''>Select Page</option>$uvpagesopts</select>";
			$uvsfieldhtml = "<select $uvsinputidattr_esc class='uvsjson $uvsinputaddclass_esc' name='$uvsinputname_esc'><option value=''>Select Page</option>$uvpagesopts</select>";
			// @Axl End
		}
		else if($uvsinputtype == "select"){
			$uvsselectvalues = $uvs_admin_fields[$uvsfieldname]["values"];

			$uvsselectvalueshtml = uvs_get_fieldvalueshtml($uvsselectvalues, $uvsinputvalue);

			// @Axl
			// $uvsfieldhtml = "<select $uvsinputidattr class='uvsjson $uvsinputaddclass' name='$uvsinputname'>$uvsselectvalueshtml</select>";
			$uvsfieldhtml = "<select $uvsinputidattr_esc class='uvsjson $uvsinputaddclass_esc' name='$uvsinputname_esc'>$uvsselectvalueshtml</select>";
			// @Axl End
		}
		else if($uvsinputtype == "switchui"){
			$uvsswithclaass = ($uvsinputvalue == 1) ? "uvs-on" : "";

			// @Axl
			// $uvsfieldhtml = "<div class='uvs-switch-ui $uvsswithclaass'><button class='uvsjs-trigger-switch' type='button'><span class='uvs-lb-on'>Yes</span><span class='uvs-lb-off'>No</span></button>
			// <input $uvsinputidattr class='uvsjson $uvsinputaddclass' type='hidden' name='$uvsinputname' value='$uvsinputvalue' data-value-on='1' data-value-off='0' $uvsinputattrs></div>";
			$uvsswithclaass_esc = esc_attr( $uvsswithclaass );
			$uvsfieldhtml = "<div class='uvs-switch-ui $uvsswithclaass_esc'><button class='uvsjs-trigger-switch' type='button'><span class='uvs-lb-on'>Yes</span><span class='uvs-lb-off'>No</span></button>
			<input $uvsinputidattr_esc class='uvsjson $uvsinputaddclass_esc' type='hidden' name='$uvsinputname_esc' value='$uvsinputvalue_esc' data-value-on='1' data-value-off='0' $uvsinputattrs></div>";
			// @Axl End
		}
		else
			// @Axl
			// $uvsfieldhtml = "<input $uvsinputidattr class='uvsjson $uvsinputaddclass' type='$uvsinputtype' name='$uvsinputname' value='$uvsinputvalue' $uvsinputattrs>";
			$uvsfieldhtml = "<input $uvsinputidattr_esc class='uvsjson $uvsinputaddclass_esc' type='$uvsinputtype_esc' name='$uvsinputname_esc' value='$uvsinputvalue_esc' $uvsinputattrs>";
			// @Axl End
	}

	return $uvsfieldhtml;
}
function uvs_get_fieldvalue_by_stringroute($uvsstringroute){
	global $uvs_core_lib, $uv_core_defaults_lib;

	$uvsroutecurval = $uvs_core_lib;
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
		$uvsroutecurval = $uv_core_defaults_lib;
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
function uvs_get_fieldvalueshtml($uvsselectvalues, $uvsinputvalue){
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

			// @Axl
		// $uvsselectvalueshtml .= "<option value='$uvsslvalueval' $uvsslvalueattr>$uvsslvaluelabel</option>";
		$uvsselectvalueshtml .= "<option value='" . esc_attr( $uvsslvalueval ) . "' $uvsslvalueattr>" . esc_html( $uvsslvaluelabel ) . "</option>";
		// @Axl End
		}
	}

	return $uvsselectvalueshtml;
}

// Get string returns string for url
function uvs_get_linkstring($string){
    $string = uvs_get_string2u($string, "-");
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
function uvs_get_string2u($string, $uchar){
    global $uvs_cleanchars;
 
    if(!$uchar)
        $uchar="-";
    $string = strtr($string, $uvs_cleanchars);

    $string = ucwords($string);

    $string=preg_replace("|[&][#0-9a-zA-Z]+[;]|", "", $string);
    $string=preg_replace("|[^0-9a-zA-Z]|", $uchar, $string);
    $string=preg_replace("|[$uchar][$uchar]+|", "$uchar", $string);
    
    return $string;
}

/* UV Error */
function uvs_uverror($uvserror){
	// @Axl
	// echo($uvserror);
	echo esc_html( $uvserror );
	// @Axl End
}

//Check if is wordpress
function uvs_is_wordpress(){
	$uviswordpress = 0;

	if(function_exists('get_option') and function_exists('add_menu_page'))
		$uviswordpress = 1;
	
	return $uviswordpress;
}

//Get wordpress pages
function uvs_get_wppages($uvselpageid = ""){
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
		
		// @Axl
		// $uvpagelist .= "<option value='$uvpageid' $uvselected>{$uvparenttag}{$uvpagetitle}</option>";
		$uvpagelist .= "<option value='" . esc_attr( $uvpageid ) . "' $uvselected>" . esc_html( $uvparenttag . $uvpagetitle ) . "</option>";
		// @Axl End
	}

	return $uvpagelist;
}

/**
 * Checks if the current user's email is a UrVenue email.
 *
 * @return int Returns 1 if the user's email is a UrVenue email, 0 otherwise.
 */
function uvs_is_uv_email(){
	$uvuseremail = "";
	$uvemailuv = 0;

	if(uvs_is_wordpress()){
		$uvcurrentuser = wp_get_current_user();
		$uvuseremail = $uvcurrentuser->user_email;

		if(strpos($uvuseremail, "@urvenue.com") !== false)
			$uvemailuv = 1;
	}

	return $uvemailuv;
}

//Checks if is hosted on wpe
function uvs_is_hosted_on_wpengine() {
	return function_exists('is_wpe') && is_wpe();
}