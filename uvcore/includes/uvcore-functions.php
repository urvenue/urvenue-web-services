<?php

// Get uvcore library, set global variables
function uws_get_core_library(){
	global $uws_path, $uv_core_defaults_lib;
	
	$uvscorelibarray = "";
	
	if(uws_is_wordpress()){//if is wordpress
        $uvscorelibjson = get_option("uvcore_lib");
        $uvscorelibarray = json_decode($uvscorelibjson, true);
    }
	if($uws_path and file_exists($uws_path . "/uvcore.lib.json")){
		$uvscorelibjson = file_get_contents($uws_path . "/uvcore.lib.json");
		$uvscorelibarray = json_decode($uvscorelibjson, true);
	}
	
	if(!is_array($uvscorelibarray) or !is_array($uvscorelibarray["system"]))
		$uvscorelibarray = false;

	$uvscorelibarray = (is_array($uvscorelibarray)) ? uws_lib_add_defaults($uvscorelibarray) : $uv_core_defaults_lib;

	//Set Basic Variables if they are not pressent
	if(is_array($uvscorelibarray) and !isset($uvscorelibarray["system"]["path"]))
		$uvscorelibarray["system"]["path"] = "";
	if(is_array($uvscorelibarray) and !isset($uvscorelibarray["system"]["url"]))
		$uvscorelibarray["system"]["url"] = "";
	if(is_array($uvscorelibarray) and !isset($uvscorelibarray["system"]["library"]))
		$uvscorelibarray["system"]["library"] = "";
		
	return $uvscorelibarray;
}
function uws_get_today(){
    global $uws_today;

    $uvtoday = ($uws_today) ? $uws_today : date("Y-m-d");

    return $uvtoday;
}

// Add default values to library
function uws_lib_add_defaults($uvcorelibarray){
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

function uws_get_share_links($uvshareurl){
	$uvsharelinks = "";

	if($uvshareurl){
		$uvsharelinks = "
			<ul class='uws-social-shares'>
				<li><a class='uwsjs-fbshare' href='#uws-share-fb' data-shareurl='$uvshareurl' aria-label='" . uws_lang("share-fb") . "'><i class='uwsicon-facebook'></i> <span>" . uws_lang("share") . "</span></a></li>
				<li><a class='uwsjs-twshare' href='#uws-share-tw' data-shareurl='$uvshareurl' aria-label='" . uws_lang("share-tw") . "'><i class='uwsicon-x-logo'></i> <span>" . uws_lang("post") . "</span></a></li>
				<li><a class='uwsjs-copytext uws-addcopyedtag' href='#uws-share-url' data-copytext='$uvshareurl' aria-label='" . uws_lang("share-url") . "'><i class='uwsicon-link'></i> <span>" . uws_lang("copy-link") . "</span></a></li>
			</ul>
		";
	}

	return $uvsharelinks;
}

// Include styles on head
//updated with enqueue for @egt [UWS-7264]
function uws_include_styles(){
    global $uws_url;
	
	wp_enqueue_style('uvcore-css', $uws_url . '/assets/css/uvcore.css', array(), null, 'all');
    wp_enqueue_style('uwsicons-css', $uws_url . '/assets/css/uwsicons.css', array(), null, 'all');
}
// add_action('wp_enqueue_scripts', 'uws_include_styles');

// Include styles on head 
//updated with enqueue for @egt [UWS-7264]
function uws_include_scripts(){
    global $uws_url;

	wp_register_script('uvcore-js',  $uws_url . '/assets/js/uvcore.js', array(), null, array('strategy' => 'async'));
	wp_enqueue_script('uvcore-js');
}
// add_action('wp_enqueue_scripts', 'uws_include_scripts');

/*Get css vars script
    Returns: string with css vars form global styles
*/ 
function uws_get_css_vars(){
	global $uws_theme_vars, $uws_poptheme_vars, $uws_core_lib, $uws_config_uitheme, $uws_config_uipoptheme;

	$uvcssstyles = "";
	$uvcssvars = "";
	//print_r($uws_theme_vars);
	// print_r($uws_core_lib);
	$uvuitheme = $uws_core_lib["ui"]["uitheme"];
	$uvuitheme = ($uws_theme_vars[$uvuitheme]) ? $uvuitheme : "light";
	$uvuitheme = ($uws_config_uitheme) ? $uws_config_uitheme : $uvuitheme;

	$uvuipoptheme = $uws_core_lib["ui"]["uipoptheme"];
	$uvuipoptheme = ($uws_poptheme_vars[$uvuipoptheme]) ? $uvuipoptheme : "light";
	$uvuipoptheme = ($uws_config_uipoptheme) ? $uws_config_uipoptheme : $uvuipoptheme;
	
	if(is_array($uws_theme_vars[$uvuitheme])){
		foreach($uws_theme_vars[$uvuitheme] as $uvuivarkey => $uvuivar){
			$uvcssvars .= "--uws-$uvuivarkey: $uvuivar; ";
		}

		// add accentcolor
		if($uws_theme_vars[$uvuitheme]["accentcolor"]){
			$uwsaccentcolor = ($uws_core_lib["ui"]["accentcolor"]) ? $uws_core_lib["ui"]["accentcolor"] : $uws_theme_vars[$uvuitheme]["accentcolor"];
			$uws_accentcolor_opacity = ($uvuitheme == "light") ? $uwsaccentcolor . '1F' : $uwsaccentcolor . '66';	
			$uws_accentcolor_opacitylight = ($uvuitheme == "light") ? $uwsaccentcolor . '14' : $uwsaccentcolor . '42';
			$uws_primarycolor = $uws_theme_vars[$uvuitheme]["primary-color"]; //($uws_core_lib["ui"]["primarycolor"]) ? $uws_core_lib["ui"]["primarycolor"] : $uws_theme_vars[$uvuitheme]["primary-color"];		
			$uws_secondarycolor = $uws_theme_vars[$uvuitheme]["secondary-color"]; //($uws_core_lib["ui"]["secondarycolor"]) ? $uws_core_lib["ui"]["secondarycolor"] : $uws_theme_vars[$uvuitheme]["secondary-color"];
			
			$uvcssvars .= "--uws-main-color: $uws_primarycolor; ";
			$uvcssvars .= "--uws-primary-color: $uws_primarycolor; ";
			$uvcssvars .= "--uws-secondary-color: $uws_secondarycolor; ";
			$uvcssvars .= "--uws-subtle-color: $uws_secondarycolor; ";
			$uvcssvars .= "--uws-accentcolorcust: $uwsaccentcolor; ";
			$uvcssvars .= "--uws-accentcoloropac: $uws_accentcolor_opacity; ";
			$uvcssvars .= "--uws-accentcoloropaclight: $uws_accentcolor_opacitylight; ";
			$uvcssvars .= "--uws-input-bg: $uws_accentcolor_opacity; ";
			$uvcssvars .= "--uws-dropdown-elemhovder: $uws_accentcolor_opacity; ";
		}
	}

	// Add poptheme vars
	if(is_array($uws_poptheme_vars[$uvuipoptheme])){
		foreach($uws_poptheme_vars[$uvuipoptheme] as $uvuivarkey => $uvuivar){
			$uvcssvars .= "--uws-$uvuivarkey: $uvuivar; ";
		}

		// uipoptheme and popaccentcolor
		if($uws_poptheme_vars[$uvuitheme]["popaccentcolor"]){
			$uwspopaccentcolor = ($uws_core_lib["ui"]["popaccentcolor"]) ? $uws_core_lib["ui"]["popaccentcolor"] : $uws_poptheme_vars[$uvuipoptheme]["popaccentcolor"];
			$uws_popaccentcolor_lopacity = $uwspopaccentcolor . '1F'; // Adding 1F for 12% opacity in hex
			$uws_popaccentcolor_opacity = $uwspopaccentcolor . '66'; // Adding 66 for 40% opacity in hex
			
			$uvcssvars .= "--uws-popaccentcolorcust: $uwspopaccentcolor; ";
			$uvcssvars .= "--uws-popaccentcolorlopac: $uws_popaccentcolor_lopacity; ";
			$uvcssvars .= "--uws-popaccentcoloropac: $uws_popaccentcolor_opacity; ";
			$uvcssvars .= "--uws-popinput-bg: $uws_popaccentcolor_opacity; ";
			$uvcssvars .= "--uws-popdropdown-elemhovder: $uws_popaccentcolor_opacity; ";
		}
	}

	$uvcssstyles = ":root{" . $uvcssvars . "}";

	return $uvcssstyles;
}

/*Check if is wordpress*/
function uws_is_wordpress(){
	$uviswordpress = 0;

	if(function_exists('get_option') and function_exists('add_menu_page'))
		$uviswordpress = 1;
	
	return $uviswordpress;
}

/*Get string returns string for url*/
function uws_get_linkstring($string){
    $string = uws_get_string2u($string, "-");
    $string = preg_replace("|[^a-zA-Z0-9_]|", "-", $string);
    $string = preg_replace("|-+|", "-", $string);
    
    if(substr($string, -1) == "-") 
        $string = substr($string, 0, -1);
        
    if(substr($string, 0, 1) == "-") 
        $string=substr($string, 1);
        
    $string = strtolower($string);
    
    return($string);
}

/*Clean special chars*/
function uws_get_string2u($string, $uchar){
    global $uws_cleanchars_lib;
 
    if(!$uchar)
        $uchar="-";
    $string = strtr($string, $uws_cleanchars_lib);

    $string = ucwords($string);

    $string = preg_replace("|[&][#0-9a-zA-Z]+[;]|", "", $string);
    $string = preg_replace("|[^0-9a-zA-Z]|", $uchar, $string);
    $string = preg_replace("|[$uchar][$uchar]+|", "$uchar", $string);
    
    return $string;
}

/*Get argument by code
	Requires: args(var with args), argcode(code for the argument), argdef(default value for the argument)
*/
function uws_get_arg($uvargs, $uvargcode, $uvargdef = ""){
	$uvthearg = "";

	if(is_array($uvargs) and isset($uvargs[$uvargcode]) and $uvargs[$uvargcode])
		$uvthearg = $uvargs[$uvargcode];
	else
		$uvthearg = $uvargdef;

	return $uvthearg;
}

/*Get template content
	Requires: template(template code or html template)
*/
function uws_get_template($uvtemplate){
	global $uws_path, $uws_alttemplatepath, $uws_core_lib;

	$uvtemplatecontent = "";

	if(strpos($uvtemplate, '{') !== false){//Template is already html
		$uvtemplatecontent = $uvtemplate;
	}
	else{
		$uvcustomtemplatename = (isset($uws_core_lib["system"]["templates-custom-folder"])) ? $uws_core_lib["system"]["templates-custom-folder"] : "uwstemplates";
		$uvteamplatepath = $uws_path . "/includes/templates/" . $uvtemplate . ".html";
		
		if(uws_get_cur_lang() != "en" and file_exists($uws_path . "/includes/templates/langs/" . uws_get_cur_lang() . "/" . $uvtemplate . ".html"))
			$uvteamplatepath = $uws_path . "/includes/templates/langs/" . uws_get_cur_lang() . "/" . $uvtemplate . ".html";

		if(uws_is_wordpress() and file_exists(get_stylesheet_directory() . "/" . $uvcustomtemplatename .  "/" . $uvtemplate . ".html"))
			$uvteamplatepath = get_stylesheet_directory() . "/" . $uvcustomtemplatename . "/" . $uvtemplate . ".html";

		if(uws_is_wordpress() and uws_get_cur_lang() != "en" and file_exists(get_stylesheet_directory() . "/" . $uvcustomtemplatename . "/langs/" . uws_get_cur_lang() . "/" . $uvtemplate . ".html"))
			$uvteamplatepath = get_stylesheet_directory() . "/" . $uvcustomtemplatename . "/langs/" . uws_get_cur_lang() . "/" . $uvtemplate . ".html";

		if($uws_alttemplatepath and file_exists($uws_alttemplatepath . "/" . $uvtemplate . ".html"))
			$uvteamplatepath = $uws_alttemplatepath . "/" . $uvtemplate . ".html";

		if($uws_alttemplatepath and uws_get_cur_lang() != "en" and file_exists($uws_alttemplatepath . "/langs/" . uws_get_cur_lang() . "/" . $uvtemplate . ".html"))
			$uvteamplatepath = $uws_alttemplatepath . "/langs/" . uws_get_cur_lang() . "/" . $uvtemplate . ".html";

		if(file_exists($uvteamplatepath)){
			$uvtemplatecontent  = uws_api_call($uvteamplatepath, 1);
		}
	}

	return $uvtemplatecontent;
}

/*Get dummy api (json object with dummy content for different apis)
	Requires: Name of the dummy
	Returns: Array with the dummy content
*/
function uws_get_dummyapi($uvdummycode = "", $uvdummytype = "json"){
	global $uws_path;

	$uvdummycontent = "";

	if($uvdummycode){
		$uvdummypath = $uws_path . "/libs/dummies/" . $uvdummycode . "." . $uvdummytype;

		if(file_exists($uvdummypath)){
			$uvdummycontent  = uws_api_call($uvdummypath, 1);
			if($uvdummycontent and $uvdummytype == "json")
				$uvdummycontent = json_decode($uvdummycontent, 1);
		}
	}

	return $uvdummycontent;
}

/*Get svg icon 
	Requires: Icon key
*/
function uws_get_icon($uviconkey = ""){
	global $uws_path;

	$uvicon = "";

	if($uviconkey){
		$uviconpath = $uws_path . "/includes/icons/" . $uviconkey . ".svg";

		if(file_exists($uviconpath)){
			$uvicon  = uws_api_call($uviconpath, 1);
		}
	}

	return $uvicon;
}

/*Get html list of options only <li>
	Requires: Array with the list to show
	Returns: html with the list (only <li>)
*/
function uws_get_optionslist($uvlist, $uvselected = "", $uvtype = "", $uvnolink = ""){
	$uvlisthtml = "";

	if(is_array($uvlist)){
		if($uvtype == "checkboxes"){
			foreach($uvlist as $uvitemkey => $uvitemlabel){
				$uvitemvalue = $uvitemlabel;
				$uvitemlabel = ($uvitemlabel == "Complimentary") ? $uvitemlabel . " <i class='uwscompicon'></i>" : $uvitemlabel;

				$uvlisthtml .= "<li><div class='uwscheckbox'><input id='uwscheckbox-$uvitemkey' type='checkbox' name='uwscheckbox-$uvitemkey' value='$uvitemvalue'><label for='uwscheckbox-$uvitemkey'>$uvitemlabel</label></div></li>";
			}
		}
		else{
			foreach($uvlist as $uvitemkey => $uvitemlabel){
				$uvselectedclass = ($uvselected and $uvitemkey == $uvselected) ? "uwscurrent" : "";
				$uvlinkstart = (!$uvnolink) ? "<a href='#' data-value='$uvitemkey'>" : "";
				$uvlinkend = (!$uvnolink) ? "</a>" : "";

				$uvlisthtml .= "<li class='$uvselectedclass'>{$uvlinkstart}{$uvitemlabel}{$uvlinkend}</li>";
			}
		}
	}

	return $uvlisthtml;
}

/*Get phone code options
    Optional: selected phone code, format: [US]+1
    Returns: html with phone code options
*/
function uws_get_phonecode_options($uvphonecode = ""){
    $uvphonecodeopts = "";
    $uvphonecode = ($uvphonecode == "") ? "[US]+1" : $uvphonecode;

    $uvphonecodes = uws_get_dummyapi("phonecodes");

    if(is_array($uvphonecodes)){
        preg_match('/\[(.*?)\]/', $uvphonecode, $uvmatches);
        $uvthecode = $uvmatches[1];

        foreach($uvphonecodes as $uvphonecodeitem){
            $uvselected = "";
            if($uvphonecodeitem["code"] == $uvthecode){
                $uvselected = "selected";
            }

            $uvphonecodeopts .= "<option value='[" . $uvphonecodeitem["code"] . "]+" . $uvphonecodeitem["number"] . "' $uvselected>" . $uvphonecodeitem["code"] . "  - " .  $uvphonecodeitem["label"] . "</option>";
        }
    }

    return $uvphonecodeopts;
}

/*Get integration credits
	Optional: creditstype(kind of credits to show)
	Returns: html with powered by urvenue
*/
function uws_get_uwscredits($uvcreditstype = ""){
	global $uws_core_lib, $uws_config_uitheme, $uws_theme_vars, $uws_url;

	$uvuitheme = $uws_core_lib["ui"]["uitheme"];
	$uvuitheme = ($uws_theme_vars[$uvuitheme]) ? $uvuitheme : "light";
	$uvuitheme = ($uws_config_uitheme) ? $uws_config_uitheme : $uvuitheme;

	$uvextracreditlogo = ($uvcreditstype == "uv+ot" or $uvcreditstype == "ot") ? "<a href='https://www.opentable.com/' target='_blank'><img src='/wp-content/plugins/wp-urvenue-webservices/uvcore/assets/images/external/opentablelogo.svg' class='uwspowbyot' alt='Powered By OpenTable'></a>" : "";
	$uvextracreditlogo = ($uvcreditstype == "bk4") ? "<a href='https://book4time.com/' target='_blank'><img src='$uws_url/assets/images/b4t-logo.svg' class='uwspwby-bk4' alt='Powered By book4time'></a>" : $uvextracreditlogo;
	$uvurvenuecreditlogo = ($uvcreditstype != "ot" and $uvcreditstype != "bk4") ? "<a href='https://www.urvenue.com/' target='_blank'><img src='/wp-content/plugins/wp-urvenue-webservices/uvcore/assets/images/brand/powered-landspace-{$uvuitheme}bg.png' alt='Powered By UrVenue'></a>" : "";

	return "<div class='uwspowby'>{$uvurvenuecreditlogo}{$uvextracreditlogo}</div>";
}

/*Get proxies
	Optional: proxysection(section to return, example: events)
	Returns: Proxy array for given key
*/
function uws_get_proxies($uvproxysection = ""){
	global $uws_proxies_lib;

	$uvproxies = "";

	if($uvproxysection and is_array($uws_proxies_lib[$uvproxysection]))
		$uvproxies = $uws_proxies_lib[$uvproxysection];

	return $uvproxies;
}

/**
 * Trims a string to a specified number of words while preserving HTML tags.
 *
 * @param string $uvtext The input string to be trimmed.
 * @param int $uvmaxwords The maximum number of words to keep.
 * @param string $uvmore The string to append if the text is trimmed.
 * @return string The trimmed string.
 */
function uws_trim_words_with_html($uvtext, $uvmaxwords = 50, $uvmore = "..."){
    $uvtext = preg_replace('/<.*?>/', ' $0 ', $uvtext);
    $uvwordsarray = preg_split('/\s+/', $uvtext, -1, PREG_SPLIT_NO_EMPTY);
    $uvwordcount = count($uvwordsarray);

    if ($uvwordcount > $uvmaxwords) {
        $uvtext = implode(' ', array_slice($uvwordsarray, 0, $uvmaxwords));
        $uvtext .= $uvmore;
    }

    return $uvtext;
}

/*Get proxies script
	Optional: proxysection(section to return, example: events)
	Returns: Proxy script html
*/
function uws_get_proxies_script($uvproxysection = ""){
	global $uws_proxies_lib;

	$uvproxies = $uws_proxies_lib;

	if($uvproxysection and is_array($uws_proxies_lib[$uvproxysection]))
		$uvproxies = $uws_proxies_lib[$uvproxysection];

	// @Axl
	// $uvproxiesjson = json_encode($uvproxies);
	$uvproxiesjson = wp_json_encode($uvproxies);
	// @Axl End
	$uvproxiesscript = "";

	// @egt [UWS-7264]
	$uws_proxies_script = "window.uws_proxies = window.uws_proxies || {}; uws_proxies = $uvproxiesjson;";

	wp_register_script('uws_proxies', false, array(), null, true);
	wp_enqueue_script('uws_proxies');
	wp_add_inline_script('uws_proxies', "(function () { {$uws_proxies_script} })();");

	return $uvproxiesscript;
}

/*Get time formated
	Required: time (time on uv format "12200" first digit 1 = same day, 2 = after midnight, las 4 params: HHMM)
*/
function uws_get_formattime($uvtime, $uvreturnarray = 0){
	$uvdtime = "";

	if($uvtime){
    	$uvdtime = date("g:ia", strtotime(substr($uvtime, 1, 4)));
		$uvisaftermid = (substr($uvtime, 0, 1) == "2") ? 1 : 0;

		if($uvreturnarray)
			$uvdtime = array(
				"dtime" => $uvdtime,
				"aftermidnight" => $uvisaftermid,
			);
		else
			$uvdtime = (substr($uvtime, 0, 1) == "2") ? $uvdtime . " After Midnight" : $uvdtime;
	}

	return $uvdtime;
}

/*Get ISO Format date
	Required: date, time (time on uv format "12200" first digit 1 = same day, 2 = after midnight, las 4 params: HHMM): 
	Returns: YYYY-MM-DDThh:mm:ss
*/
function uws_get_iso_time($uvdate = "", $uvtime = ""){
	$uvisotime = "";

	if($uvdate){
		$uvisotime = $uvdate;

		if($uvtime){
			if(substr($uvtime, 0, 1) == "2")
				$uvisotime = date("Y-m-d", strtotime($uvisotime . " +1 day"));

			$uvtimeiso = date("H:i:s", strtotime(substr($uvtime, 1, 4)));
			$uvisotime = $uvisotime . "T" . $uvtimeiso;
		}
	}

	return $uvisotime;
}

/*Get duration format
	Required: minutes
*/
function uws_get_formatduration($uvminutes = ""){
	$uvdduration = "";

	if($uvminutes){
		$uvdduration = $uvminutes / 60;
		$uvdduration = ($uvdduration == 1) ? $uvdduration . " Hour" : $uvdduration . " Hours";
	}

	return $uvdduration;
}

/*Add minutes to time
	Required: time (time on uv format "12200" first digit 1 = same day, 2 = after midnight, las 4 params: HHMM), minutes to add
	Returns: formated time
*/
function uws_add_minutestotime($uvtime, $uvminutes){
	$uvnewtime = substr($uvtime, 0, 1) . date("Hi", strtotime(substr($uvtime, 1, 4) . " +$uvminutes minutes"));

	return $uvnewtime;
}

/*Get time rows
    Requires: starttime(uv format), endtime(uv format), frequency(minutes)
    Returns: n time slots on the range of time
*/
function uws_get_ntimerows($uvstarttime, $uvendtime, $uvfrequency = 60){
    $uvntimerows = 0;

    if($uvstarttime and $uvendtime and $uvfrequency){
        $uvstarttimeminutes = uws_uvtimetominutes($uvstarttime);
        $uvendtimeminutes = uws_uvtimetominutes($uvendtime);

        $uvntimerows = floor(($uvendtimeminutes - $uvstarttimeminutes) / $uvfrequency);
    }

    return $uvntimerows;
}

/*Transform uvtime to minutes
	Required: time (time on uv format "12200" first digit 1 = same day, 2 = after midnight, las 4 params: HHMM), minutes to add
	Returns: n minutes
*/
function uws_uvtimetominutes($uvtime){
    $uvfactor = substr($uvtime, 0, 1) / 1;
    $uvhours = substr($uvtime, 1, 2) / 1;
    $uvminutes = substr($uvtime, 3, 2) / 1;

    return (($uvfactor - 1) * 24 * 60) + ($uvhours * 60) + $uvminutes;
}

/*Get proxy url*/
function uws_get_proxyurl(){
	global $uws_url, $uws_config_proxyurl;

	$uvproxyurl = "";
	if(uws_is_wordpress())//is wordpress
		$uvproxyurl = admin_url('admin-ajax.php');
	else if($uws_config_proxyurl)
		$uvproxyurl = $uws_config_proxyurl;
	else
		$uvproxyurl = $uws_url . "/uvcore.proxy.php";

	return $uvproxyurl;
}

/*Get main proxy script
	Returns: html script with main uws proxy
*/
function uws_get_proxy_script(){
	global $uws_config_addproxyparams;

	$uvproxy = uws_get_proxyurl();
	if (strpos($uvproxy, '?') === false)
		$uvproxy .= "?action=uvpx";
	else
		$uvproxy .= "&action=uvpx";

	$uvproxy .= $uws_config_addproxyparams;
	$uvproxiesscript = "";

	// @egt [UWS-7264]
	$uws_proxy_script = "window.uws_proxy = window.uws_proxy || {}; uws_proxy = '$uvproxy';";

	wp_register_script('uws_proxy', false, array(), null, true);
	wp_enqueue_script('uws_proxy');
	wp_add_inline_script('uws_proxy', "(function () { {$uws_proxy_script} })();");

	return $uvproxiesscript;
}

/*Get venue info from library by venuecode
	Requires: venuecode
	Returns: Array with venue info
*/
function uws_get_venuelibinfo_byvenuecode($uvvenuecode){
	global $uws_core_lib;

	$uvvenueinfo = "";

	if($uvvenuecode and is_array($uws_core_lib) and is_array($uws_core_lib["venues"])){
		foreach($uws_core_lib["venues"] as $uvvenue){
			if($uvvenue["venuecode"] == $uvvenuecode){
				$uvvenueinfo = $uvvenue;
				break;
			}
		}
	}

	return $uvvenueinfo;
}

/*Get venue name from library by venue library key
	Requires: venuekey
	Returns: string with name of the venue
*/
function uws_get_venuename_byvenuekey($uvvenuekey){
	global $uws_core_lib;

	$uvvenuename = "";

	if($uvvenuekey and is_array($uws_core_lib) and is_array($uws_core_lib["venues"]) and isset($uws_core_lib["venues"][$uvvenuekey])){
		$uvvenueinfo = $uws_core_lib["venues"][$uvvenuekey];

		$uvvenuename = (isset($uvvenueinfo["venueforcealias"]) and $uvvenueinfo["venueforcealias"] and $uvvenueinfo['venuealias']) ? $uvvenueinfo['venuealias'] : $uvvenueinfo["venuename"];
	}

	return $uvvenuename;
}

/*Cleanup string variables to make requests
	Requires: uvstring
*/
function uws_cleanup_var($uvstring){
	while(substr($uvstring, -1) == "\\")
  		$uvstring = substr($uvstring, 0, -1);

	$cleantable = array( 
		"<br />" => "",
		"'" => "&#039;", 
		"'" => "&#039;",
		"'"=> "&#039;",
		"<" => "&lt;",
		">" => "&gt;",
		"%" => "&#37;",
		"\"" => "&quot;",
		"(" => "&#40;",
		")" => "&#41;",
		"&lrm;" => "",
		"&#8206;" => "",
		"&#x200e;" => "",
		"&rlm;" => "",
		"&#8207;" => "",
		"%E2%80%8E" => ""
	);

	$uvstring = trim($uvstring);
	$uvstring = strtr($uvstring, $cleantable);

	while(substr($uvstring, -1) == "\\")
		$uvstring = substr($uvstring, 0, -1);

	return $uvstring;
}

function uws_get_theme() {
	global $uws_core_lib, $uws_config_uitheme, $uws_theme_vars;

	$uvuitheme = $uws_core_lib["ui"]["uitheme"];
	$uvuitheme = ($uws_theme_vars[$uvuitheme]) ? $uvuitheme : "light";
	$uvuitheme = ($uws_config_uitheme) ? $uws_config_uitheme : $uvuitheme;

	return "uws-" . $uvuitheme;
}

function uws_get_popup_theme() {
	global $uws_core_lib, $uws_config_uipoptheme, $uws_poptheme_vars;

	$uvuipotheme = $uws_core_lib["ui"]["uipoptheme"];
	$uvuipotheme = ($uws_poptheme_vars[$uvuipotheme]) ? $uvuipotheme : "light";
	$uvuipotheme = ($uws_config_uipoptheme) ? $uws_config_uipoptheme : $uvuipotheme;

	return "uws-" . $uvuipotheme;
}