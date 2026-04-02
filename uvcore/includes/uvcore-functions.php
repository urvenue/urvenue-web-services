<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Get uvcore library, set global variables
// function uws_get_core_library(){
function urvenue_ws_get_core_library(){ // Axl UWS-7416
	global $urvenue_ws_path, $urvenue_ws_core_defaults_lib;
	
	$uvscorelibarray = "";
	
	// if(uws_is_wordpress()){//if is wordpress
	if(urvenue_ws_is_wordpress()){//if is wordpress // Axl UWS-7416
        // $uvscorelibjson = get_option("uvcore_lib");
        $uvscorelibjson = get_option("urvenue_ws_uvcore_lib"); // Axl UWS-7416
        $uvscorelibarray = json_decode($uvscorelibjson, true);
    }
	if($urvenue_ws_path and file_exists($urvenue_ws_path . "/uvcore.lib.json")){
		$uvscorelibjson = file_get_contents($urvenue_ws_path . "/uvcore.lib.json");
		$uvscorelibarray = json_decode($uvscorelibjson, true);
	}
	
	if(!is_array($uvscorelibarray) or !is_array($uvscorelibarray["system"]))
		$uvscorelibarray = false;

	// $uvscorelibarray = (is_array($uvscorelibarray)) ? uws_lib_add_defaults($uvscorelibarray) : $urvenue_ws_core_defaults_lib;
	$uvscorelibarray = (is_array($uvscorelibarray)) ? urvenue_ws_lib_add_defaults($uvscorelibarray) : $urvenue_ws_core_defaults_lib; // Axl UWS-7416

	//Set Basic Variables if they are not pressent
	if(is_array($uvscorelibarray) and !isset($uvscorelibarray["system"]["path"]))
		$uvscorelibarray["system"]["path"] = "";
	if(is_array($uvscorelibarray) and !isset($uvscorelibarray["system"]["url"]))
		$uvscorelibarray["system"]["url"] = "";
	if(is_array($uvscorelibarray) and !isset($uvscorelibarray["system"]["library"]))
		$uvscorelibarray["system"]["library"] = "";
		
	return $uvscorelibarray;
}
// function uws_get_today(){
function urvenue_ws_get_today(){ // Axl UWS-7416
    global $urvenue_ws_today;

    $uvtoday = ($urvenue_ws_today) ? $urvenue_ws_today : gmdate("Y-m-d"); // Axl UWS-7416

    return $uvtoday;
}

// Add default values to library
// function uws_lib_add_defaults($uvcorelibarray){
function urvenue_ws_lib_add_defaults($uvcorelibarray){ // Axl UWS-7416
    global $urvenue_ws_core_defaults_lib;

    //$uvnewcorelibarray = array_merge($urvenue_ws_core_defaults_lib, $uvcorelibarray);
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

// function uws_get_share_links($uvshareurl){
function urvenue_ws_get_share_links($uvshareurl){ // Axl UWS-7416
	$uvsharelinks = "";

	if($uvshareurl){
		$uvsharelinks = "
			<ul class='uws-social-shares'>
				<li><a class='uwsjs-fbshare' href='#uws-share-fb' data-shareurl='$uvshareurl' aria-label='" . urvenue_ws_lang("share-fb") . "'><i class='uwsicon-facebook'></i> <span>" . urvenue_ws_lang("share") . "</span></a></li>
				<li><a class='uwsjs-twshare' href='#uws-share-tw' data-shareurl='$uvshareurl' aria-label='" . urvenue_ws_lang("share-tw") . "'><i class='uwsicon-x-logo'></i> <span>" . urvenue_ws_lang("post") . "</span></a></li>
				<li><a class='uwsjs-copytext uws-addcopyedtag' href='#uws-share-url' data-copytext='$uvshareurl' aria-label='" . urvenue_ws_lang("share-url") . "'><i class='uwsicon-link'></i> <span>" . urvenue_ws_lang("copy-link") . "</span></a></li>
			</ul>
		";
	}

	return $uvsharelinks;
}

// Include styles on head
//updated with enqueue for @egt [UWS-7264]
// function uws_include_styles(){
function urvenue_ws_core_include_styles(){ // Axl UWS-7416
    global $urvenue_ws_url;
	
	wp_enqueue_style('uvcore-css', $urvenue_ws_url . '/assets/css/uvcore.css', array(), null, 'all');
    wp_enqueue_style('uwsicons-css', $urvenue_ws_url . '/assets/css/uwsicons.css', array(), null, 'all');
}
// add_action('wp_enqueue_scripts', 'uws_include_styles');

// Include styles on head 
//updated with enqueue for @egt [UWS-7264]
// function uws_include_scripts(){
function urvenue_ws_core_include_scripts(){ // Axl UWS-7416
    global $urvenue_ws_url;

	wp_register_script('uvcore-js',  $urvenue_ws_url . '/assets/js/uvcore.js', array(), null, array('strategy' => 'async'));
	wp_enqueue_script('uvcore-js');
}
// add_action('wp_enqueue_scripts', 'uws_include_scripts');

/*Get css vars script
    Returns: string with css vars form global styles
*/ 
// function uws_get_css_vars(){
function urvenue_ws_get_css_vars(){ // Axl UWS-7416
	global $urvenue_ws_theme_vars, $urvenue_ws_poptheme_vars, $urvenue_ws_core_lib, $uws_config_uitheme, $uws_config_uipoptheme;

	$uvcssstyles = "";
	$uvcssvars = "";
	//print_r($urvenue_ws_theme_vars);
	// print_r($urvenue_ws_core_lib);
	$uvuitheme = $urvenue_ws_core_lib["ui"]["uitheme"];
	$uvuitheme = ($urvenue_ws_theme_vars[$uvuitheme]) ? $uvuitheme : "light";
	$uvuitheme = ($uws_config_uitheme) ? $uws_config_uitheme : $uvuitheme;

	$uvuipoptheme = $urvenue_ws_core_lib["ui"]["uipoptheme"];
	$uvuipoptheme = ($urvenue_ws_poptheme_vars[$uvuipoptheme]) ? $uvuipoptheme : "light";
	$uvuipoptheme = ($uws_config_uipoptheme) ? $uws_config_uipoptheme : $uvuipoptheme;
	
	if(is_array($urvenue_ws_theme_vars[$uvuitheme])){
		foreach($urvenue_ws_theme_vars[$uvuitheme] as $uvuivarkey => $uvuivar){
			$uvcssvars .= "--uws-$uvuivarkey: $uvuivar; ";
		}

		// add accentcolor
		if($urvenue_ws_theme_vars[$uvuitheme]["accentcolor"]){
			$uwsaccentcolor = ($urvenue_ws_core_lib["ui"]["accentcolor"]) ? $urvenue_ws_core_lib["ui"]["accentcolor"] : $urvenue_ws_theme_vars[$uvuitheme]["accentcolor"];
			$uws_accentcolor_opacity = ($uvuitheme == "light") ? $uwsaccentcolor . '1F' : $uwsaccentcolor . '66';	
			$uws_accentcolor_opacitylight = ($uvuitheme == "light") ? $uwsaccentcolor . '14' : $uwsaccentcolor . '42';
			$uws_primarycolor = $urvenue_ws_theme_vars[$uvuitheme]["primary-color"]; //($urvenue_ws_core_lib["ui"]["primarycolor"]) ? $urvenue_ws_core_lib["ui"]["primarycolor"] : $urvenue_ws_theme_vars[$uvuitheme]["primary-color"];		
			$uws_secondarycolor = $urvenue_ws_theme_vars[$uvuitheme]["secondary-color"]; //($urvenue_ws_core_lib["ui"]["secondarycolor"]) ? $urvenue_ws_core_lib["ui"]["secondarycolor"] : $urvenue_ws_theme_vars[$uvuitheme]["secondary-color"];
			
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
	if(is_array($urvenue_ws_poptheme_vars[$uvuipoptheme])){
		foreach($urvenue_ws_poptheme_vars[$uvuipoptheme] as $uvuivarkey => $uvuivar){
			$uvcssvars .= "--uws-$uvuivarkey: $uvuivar; ";
		}

		// uipoptheme and popaccentcolor
		if($urvenue_ws_poptheme_vars[$uvuitheme]["popaccentcolor"]){
			$uwspopaccentcolor = ($urvenue_ws_core_lib["ui"]["popaccentcolor"]) ? $urvenue_ws_core_lib["ui"]["popaccentcolor"] : $urvenue_ws_poptheme_vars[$uvuipoptheme]["popaccentcolor"];
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
// function uws_is_wordpress(){
function urvenue_ws_is_wordpress(){ // Axl UWS-7416
	$uviswordpress = 0;

	if(function_exists('get_option') and function_exists('add_menu_page'))
		$uviswordpress = 1;
	
	return $uviswordpress;
}

/*Get string returns string for url*/
// function uws_get_linkstring($string){
function urvenue_ws_get_linkstring($string){ // Axl UWS-7416
    // $string = uws_get_string2u($string, "-");
    $string = urvenue_ws_get_string2u($string, "-"); // Axl UWS-7416
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
// function uws_get_string2u($string, $uchar){
function urvenue_ws_get_string2u($string, $uchar){ // Axl UWS-7416
    global $urvenue_ws_cleanchars_lib;
 
    if(!$uchar)
        $uchar="-";
    $string = strtr($string, $urvenue_ws_cleanchars_lib);

    $string = ucwords($string);

    $string = preg_replace("|[&][#0-9a-zA-Z]+[;]|", "", $string);
    $string = preg_replace("|[^0-9a-zA-Z]|", $uchar, $string);
    $string = preg_replace("|[$uchar][$uchar]+|", "$uchar", $string);
    
    return $string;
}

/*Get argument by code
	Requires: args(var with args), argcode(code for the argument), argdef(default value for the argument)
*/
// function uws_get_arg($uvargs, $uvargcode, $uvargdef = ""){
function urvenue_ws_get_arg($uvargs, $uvargcode, $uvargdef = ""){ // Axl UWS-7416
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
// function uws_get_template($uvtemplate){
function urvenue_ws_get_template($uvtemplate){ // Axl UWS-7416
	global $urvenue_ws_path, $uws_alttemplatepath, $urvenue_ws_core_lib;

	$uvtemplatecontent = "";

	if(strpos($uvtemplate, '{') !== false){//Template is already html
		$uvtemplatecontent = $uvtemplate;
	}
	else{
		$uvcustomtemplatename = (isset($urvenue_ws_core_lib["system"]["templates-custom-folder"])) ? $urvenue_ws_core_lib["system"]["templates-custom-folder"] : "uwstemplates";
		$uvteamplatepath = $urvenue_ws_path . "/includes/templates/" . $uvtemplate . ".html";
		
		// if(uws_get_cur_lang() != "en" and file_exists($urvenue_ws_path . "/includes/templates/langs/" . uws_get_cur_lang() . "/" . $uvtemplate . ".html"))
		if(urvenue_ws_get_cur_lang() != "en" and file_exists($urvenue_ws_path . "/includes/templates/langs/" . urvenue_ws_get_cur_lang() . "/" . $uvtemplate . ".html")) // Axl UWS-7416
			// $uvteamplatepath = $urvenue_ws_path . "/includes/templates/langs/" . uws_get_cur_lang() . "/" . $uvtemplate . ".html";
			$uvteamplatepath = $urvenue_ws_path . "/includes/templates/langs/" . urvenue_ws_get_cur_lang() . "/" . $uvtemplate . ".html"; // Axl UWS-7416

		// if(uws_is_wordpress() and file_exists(get_stylesheet_directory() . "/" . $uvcustomtemplatename .  "/" . $uvtemplate . ".html"))
		if(urvenue_ws_is_wordpress() and file_exists(get_stylesheet_directory() . "/" . $uvcustomtemplatename .  "/" . $uvtemplate . ".html")) // Axl UWS-7416
			$uvteamplatepath = get_stylesheet_directory() . "/" . $uvcustomtemplatename . "/" . $uvtemplate . ".html";

		// if(uws_is_wordpress() and uws_get_cur_lang() != "en" and file_exists(get_stylesheet_directory() . "/" . $uvcustomtemplatename . "/langs/" . uws_get_cur_lang() . "/" . $uvtemplate . ".html"))
		if(urvenue_ws_is_wordpress() and urvenue_ws_get_cur_lang() != "en" and file_exists(get_stylesheet_directory() . "/" . $uvcustomtemplatename . "/langs/" . urvenue_ws_get_cur_lang() . "/" . $uvtemplate . ".html")) // Axl UWS-7416
			// $uvteamplatepath = get_stylesheet_directory() . "/" . $uvcustomtemplatename . "/langs/" . uws_get_cur_lang() . "/" . $uvtemplate . ".html";
			$uvteamplatepath = get_stylesheet_directory() . "/" . $uvcustomtemplatename . "/langs/" . urvenue_ws_get_cur_lang() . "/" . $uvtemplate . ".html"; // Axl UWS-7416

		if($uws_alttemplatepath and file_exists($uws_alttemplatepath . "/" . $uvtemplate . ".html"))
			$uvteamplatepath = $uws_alttemplatepath . "/" . $uvtemplate . ".html";

		// if($uws_alttemplatepath and uws_get_cur_lang() != "en" and file_exists($uws_alttemplatepath . "/langs/" . uws_get_cur_lang() . "/" . $uvtemplate . ".html"))
		if($uws_alttemplatepath and urvenue_ws_get_cur_lang() != "en" and file_exists($uws_alttemplatepath . "/langs/" . urvenue_ws_get_cur_lang() . "/" . $uvtemplate . ".html")) // Axl UWS-7416
			// $uvteamplatepath = $uws_alttemplatepath . "/langs/" . uws_get_cur_lang() . "/" . $uvtemplate . ".html";
			$uvteamplatepath = $uws_alttemplatepath . "/langs/" . urvenue_ws_get_cur_lang() . "/" . $uvtemplate . ".html"; // Axl UWS-7416

		if(file_exists($uvteamplatepath)){
			// $uvtemplatecontent  = uws_api_call($uvteamplatepath, 1);
			$uvtemplatecontent  = urvenue_ws_api_call($uvteamplatepath, 1); // Axl UWS-7416
		}
	}

	return $uvtemplatecontent;
}

/*Get dummy api (json object with dummy content for different apis)
	Requires: Name of the dummy
	Returns: Array with the dummy content
*/
// function uws_get_dummyapi($uvdummycode = "", $uvdummytype = "json"){
function urvenue_ws_get_dummyapi($uvdummycode = "", $uvdummytype = "json"){ // Axl UWS-7416
	global $urvenue_ws_path;

	$uvdummycontent = "";

	if($uvdummycode){
		$uvdummypath = $urvenue_ws_path . "/libs/dummies/" . $uvdummycode . "." . $uvdummytype;

		if(file_exists($uvdummypath)){
			// $uvdummycontent  = uws_api_call($uvdummypath, 1);
			$uvdummycontent  = urvenue_ws_api_call($uvdummypath, 1); // Axl UWS-7416
			if($uvdummycontent and $uvdummytype == "json")
				$uvdummycontent = json_decode($uvdummycontent, 1);
		}
	}

	return $uvdummycontent;
}

/*Get svg icon 
	Requires: Icon key
*/
// function uws_get_icon($uviconkey = ""){
function urvenue_ws_get_icon($uviconkey = ""){ // Axl UWS-7416
	global $urvenue_ws_path;

	$uvicon = "";

	if($uviconkey){
		$uviconpath = $urvenue_ws_path . "/includes/icons/" . $uviconkey . ".svg";

		if(file_exists($uviconpath)){
			// $uvicon  = uws_api_call($uviconpath, 1);
			$uvicon  = urvenue_ws_api_call($uviconpath, 1); // Axl UWS-7416
		}
	}

	return $uvicon;
}

/*Get html list of options only <li>
	Requires: Array with the list to show
	Returns: html with the list (only <li>)
*/
// function uws_get_optionslist($uvlist, $uvselected = "", $uvtype = "", $uvnolink = ""){
function urvenue_ws_get_optionslist($uvlist, $uvselected = "", $uvtype = "", $uvnolink = ""){ // Axl UWS-7416
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
// function uws_get_phonecode_options($uvphonecode = ""){
function urvenue_ws_get_phonecode_options($uvphonecode = ""){ // Axl UWS-7416
    $uvphonecodeopts = "";
    $uvphonecode = ($uvphonecode == "") ? "[US]+1" : $uvphonecode;

    // $uvphonecodes = uws_get_dummyapi("phonecodes");
    $uvphonecodes = urvenue_ws_get_dummyapi("phonecodes"); // Axl UWS-7416

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
// function uws_get_uwscredits($uvcreditstype = ""){
function urvenue_ws_get_uwscredits($uvcreditstype = ""){ // Axl UWS-7416
	global $urvenue_ws_core_lib, $uws_config_uitheme, $urvenue_ws_theme_vars, $urvenue_ws_url;

	$uvuitheme = $urvenue_ws_core_lib["ui"]["uitheme"];
	$uvuitheme = ($urvenue_ws_theme_vars[$uvuitheme]) ? $uvuitheme : "light";
	$uvuitheme = ($uws_config_uitheme) ? $uws_config_uitheme : $uvuitheme;

	$uvextracreditlogo = ($uvcreditstype == "uv+ot" or $uvcreditstype == "ot") ? "<a href='https://www.opentable.com/' target='_blank'><img src='/wp-content/plugins/wp-urvenue-webservices/uvcore/assets/images/external/opentablelogo.svg' class='uwspowbyot' alt='Powered By OpenTable'></a>" : "";
	$uvextracreditlogo = ($uvcreditstype == "bk4") ? "<a href='https://book4time.com/' target='_blank'><img src='$urvenue_ws_url/assets/images/b4t-logo.svg' class='uwspwby-bk4' alt='Powered By book4time'></a>" : $uvextracreditlogo;
	$uvurvenuecreditlogo = ($uvcreditstype != "ot" and $uvcreditstype != "bk4") ? "<a href='https://www.urvenue.com/' target='_blank'><img src='/wp-content/plugins/wp-urvenue-webservices/uvcore/assets/images/brand/powered-landspace-{$uvuitheme}bg.png' alt='Powered By UrVenue'></a>" : "";

	return "<div class='uwspowby'>{$uvurvenuecreditlogo}{$uvextracreditlogo}</div>";
}

/*Get proxies
	Optional: proxysection(section to return, example: events)
	Returns: Proxy array for given key
*/
// function uws_get_proxies($uvproxysection = ""){
function urvenue_ws_get_proxies($uvproxysection = ""){ // Axl UWS-7416
	global $urvenue_ws_proxies_lib;

	$uvproxies = "";

	if($uvproxysection and is_array($urvenue_ws_proxies_lib[$uvproxysection]))
		$uvproxies = $urvenue_ws_proxies_lib[$uvproxysection];

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
// function uws_trim_words_with_html($uvtext, $uvmaxwords = 50, $uvmore = "..."){
function urvenue_ws_trim_words_with_html($uvtext, $uvmaxwords = 50, $uvmore = "..."){ // Axl UWS-7416
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
// function uws_get_proxies_script($uvproxysection = ""){
function urvenue_ws_get_proxies_script($uvproxysection = ""){ // Axl UWS-7416
	global $urvenue_ws_proxies_lib;

	$uvproxies = $urvenue_ws_proxies_lib;

	if($uvproxysection and is_array($urvenue_ws_proxies_lib[$uvproxysection]))
		$uvproxies = $urvenue_ws_proxies_lib[$uvproxysection];

	// @Axl
	// $uvproxiesjson = json_encode($uvproxies);
	$uvproxiesjson = wp_json_encode($uvproxies);
	// @Axl End
	$uvproxiesscript = "";

	// @egt [UWS-7264]
	// $uws_proxies_script = "window.uws_proxies = window.uws_proxies || {}; uws_proxies = $uvproxiesjson;";
	$uws_proxies_script = "window.urvenue_ws_proxies = window.urvenue_ws_proxies || {}; urvenue_ws_proxies = $uvproxiesjson;"; // Axl UWS-7416

	// wp_register_script('uws_proxies', false, array(), null, true);
	wp_register_script('urvenue_ws_proxies', false, array(), null, true); // Axl UWS-7416
	// wp_enqueue_script('uws_proxies');
	wp_enqueue_script('urvenue_ws_proxies'); // Axl UWS-7416
	// wp_add_inline_script('uws_proxies', "(function () { {$uws_proxies_script} })();");
	wp_add_inline_script('urvenue_ws_proxies', "(function () { {$uws_proxies_script} })();"); // Axl UWS-7416

	return $uvproxiesscript;
}

/*Get time formated
	Required: time (time on uv format "12200" first digit 1 = same day, 2 = after midnight, las 4 params: HHMM)
*/
// function uws_get_formattime($uvtime, $uvreturnarray = 0){
function urvenue_ws_get_formattime($uvtime, $uvreturnarray = 0){ // Axl UWS-7416
	$uvdtime = "";

	if($uvtime){
    	// $uvdtime = date("g:ia", strtotime(substr($uvtime, 1, 4)));
    	$uvdtime = gmdate("g:ia", strtotime(substr($uvtime, 1, 4))); // Axl UWS-7416
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
// function uws_get_iso_time($uvdate = "", $uvtime = ""){
function urvenue_ws_get_iso_time($uvdate = "", $uvtime = ""){ // Axl UWS-7416
	$uvisotime = "";

	if($uvdate){
		$uvisotime = $uvdate;

		if($uvtime){
			if(substr($uvtime, 0, 1) == "2")
				// $uvisotime = date("Y-m-d", strtotime($uvisotime . " +1 day"));
				$uvisotime = gmdate("Y-m-d", strtotime($uvisotime . " +1 day")); // Axl UWS-7416

			// $uvtimeiso = date("H:i:s", strtotime(substr($uvtime, 1, 4)));
			$uvtimeiso = gmdate("H:i:s", strtotime(substr($uvtime, 1, 4))); // Axl UWS-7416
			$uvisotime = $uvisotime . "T" . $uvtimeiso;
		}
	}

	return $uvisotime;
}

/*Get duration format
	Required: minutes
*/
// function uws_get_formatduration($uvminutes = ""){
function urvenue_ws_get_formatduration($uvminutes = ""){ // Axl UWS-7416
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
// function uws_add_minutestotime($uvtime, $uvminutes){
function urvenue_ws_add_minutestotime($uvtime, $uvminutes){ // Axl UWS-7416
	$uvnewtime = substr($uvtime, 0, 1) . gmdate("Hi", strtotime(substr($uvtime, 1, 4) . " +$uvminutes minutes")); // Axl UWS-7416

	return $uvnewtime;
}

/*Get time rows
    Requires: starttime(uv format), endtime(uv format), frequency(minutes)
    Returns: n time slots on the range of time
*/
// function uws_get_ntimerows($uvstarttime, $uvendtime, $uvfrequency = 60){
function urvenue_ws_get_ntimerows($uvstarttime, $uvendtime, $uvfrequency = 60){ // Axl UWS-7416
    $uvntimerows = 0;

    if($uvstarttime and $uvendtime and $uvfrequency){
        // $uvstarttimeminutes = uws_uvtimetominutes($uvstarttime);
        $uvstarttimeminutes = urvenue_ws_uvtimetominutes($uvstarttime); // Axl UWS-7416
        // $uvendtimeminutes = uws_uvtimetominutes($uvendtime);
        $uvendtimeminutes = urvenue_ws_uvtimetominutes($uvendtime); // Axl UWS-7416

        $uvntimerows = floor(($uvendtimeminutes - $uvstarttimeminutes) / $uvfrequency);
    }

    return $uvntimerows;
}

/*Transform uvtime to minutes
	Required: time (time on uv format "12200" first digit 1 = same day, 2 = after midnight, las 4 params: HHMM), minutes to add
	Returns: n minutes
*/
// function uws_uvtimetominutes($uvtime){
function urvenue_ws_uvtimetominutes($uvtime){ // Axl UWS-7416
    $uvfactor = substr($uvtime, 0, 1) / 1;
    $uvhours = substr($uvtime, 1, 2) / 1;
    $uvminutes = substr($uvtime, 3, 2) / 1;

    return (($uvfactor - 1) * 24 * 60) + ($uvhours * 60) + $uvminutes;
}

/*Get proxy url*/
// function uws_get_proxyurl(){
function urvenue_ws_get_proxyurl(){ // Axl UWS-7416
	global $urvenue_ws_url, $uws_config_proxyurl;

	$uvproxyurl = "";
	// if(uws_is_wordpress())//is wordpress
	if(urvenue_ws_is_wordpress())//is wordpress // Axl UWS-7416
		$uvproxyurl = admin_url('admin-ajax.php');
	else if($uws_config_proxyurl)
		$uvproxyurl = $uws_config_proxyurl;
	else
		$uvproxyurl = $urvenue_ws_url . "/uvcore.proxy.php";

	return $uvproxyurl;
}

/*Get main proxy script
	Returns: html script with main uws proxy
*/
// function uws_get_proxy_script(){
function urvenue_ws_get_proxy_script(){ // Axl UWS-7416
	global $urvenue_ws_config_addproxyparams;

	// $uvproxy = uws_get_proxyurl();
	$uvproxy = urvenue_ws_get_proxyurl(); // Axl UWS-7416
	if (strpos($uvproxy, '?') === false)
		$uvproxy .= "?action=uvpx";
	else
		$uvproxy .= "&action=uvpx";

	$uvproxy .= $urvenue_ws_config_addproxyparams;
	$uvproxiesscript = "";

	// @egt [UWS-7264]
	// $uws_proxy_script = "window.uws_proxy = window.uws_proxy || {}; uws_proxy = '$uvproxy';";
	// $uws_proxy_script = "window.urvenue_ws_proxy = window.urvenue_ws_proxy || {}; urvenue_ws_proxy = '$uvproxy';";
	$uws_proxy_script = "window.uws_proxy = window.uws_proxy || {}; uws_proxy = '$uvproxy';"; // Axl UWS-7416

	// wp_register_script('uws_proxy', false, array(), null, true);
	// wp_register_script('urvenue_ws_proxy', false, array(), null, true);
	wp_register_script('uws_proxy', false, array(), null, true); // Axl UWS-7416
	// wp_enqueue_script('uws_proxy');
	// wp_enqueue_script('urvenue_ws_proxy');
	wp_enqueue_script('uws_proxy'); // Axl UWS-7416
	// wp_add_inline_script('uws_proxy', "(function () { {$uws_proxy_script} })();");
	// wp_add_inline_script('urvenue_ws_proxy', "(function () { {$uws_proxy_script} })();");
	wp_add_inline_script('uws_proxy', "(function () { {$uws_proxy_script} })();"); // Axl UWS-7416

	return $uvproxiesscript;
}

/*Get venue info from library by venuecode
	Requires: venuecode
	Returns: Array with venue info
*/
// function uws_get_venuelibinfo_byvenuecode($uvvenuecode){
function urvenue_ws_get_venuelibinfo_byvenuecode($uvvenuecode){ // Axl UWS-7416
	global $urvenue_ws_core_lib;

	$uvvenueinfo = "";

	if($uvvenuecode and is_array($urvenue_ws_core_lib) and is_array($urvenue_ws_core_lib["venues"])){
		foreach($urvenue_ws_core_lib["venues"] as $uvvenue){
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
// function uws_get_venuename_byvenuekey($uvvenuekey){
function urvenue_ws_get_venuename_byvenuekey($uvvenuekey){ // Axl UWS-7416
	global $urvenue_ws_core_lib;

	$uvvenuename = "";

	if($uvvenuekey and is_array($urvenue_ws_core_lib) and is_array($urvenue_ws_core_lib["venues"]) and isset($urvenue_ws_core_lib["venues"][$uvvenuekey])){
		$uvvenueinfo = $urvenue_ws_core_lib["venues"][$uvvenuekey];

		$uvvenuename = (isset($uvvenueinfo["venueforcealias"]) and $uvvenueinfo["venueforcealias"] and $uvvenueinfo['venuealias']) ? $uvvenueinfo['venuealias'] : $uvvenueinfo["venuename"];
	}

	return $uvvenuename;
}

/*Cleanup string variables to make requests
	Requires: uvstring
*/
// function uws_cleanup_var($uvstring){
function urvenue_ws_cleanup_var($uvstring){ // Axl UWS-7416
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

// function uws_get_theme() {
function urvenue_ws_get_theme() { // Axl UWS-7416
	global $urvenue_ws_core_lib, $uws_config_uitheme, $urvenue_ws_theme_vars;

	$uvuitheme = $urvenue_ws_core_lib["ui"]["uitheme"];
	$uvuitheme = ($urvenue_ws_theme_vars[$uvuitheme]) ? $uvuitheme : "light";
	$uvuitheme = ($uws_config_uitheme) ? $uws_config_uitheme : $uvuitheme;

	return "uws-" . $uvuitheme;
}

// function uws_get_popup_theme() {
function urvenue_ws_get_popup_theme() { // Axl UWS-7416
	global $urvenue_ws_core_lib, $uws_config_uipoptheme, $urvenue_ws_poptheme_vars;

	$uvuipotheme = $urvenue_ws_core_lib["ui"]["uipoptheme"];
	$uvuipotheme = ($urvenue_ws_poptheme_vars[$uvuipotheme]) ? $uvuipotheme : "light";
	$uvuipotheme = ($uws_config_uipoptheme) ? $uws_config_uipoptheme : $uvuipotheme;

	return "uws-" . $uvuipotheme;
}

// @egt [UWS-7297]
// function uws_check_nonce($uvnonce) {
function urvenue_ws_check_nonce($uvnonce) { // Axl UWS-7416
	// if(!isset($_REQUEST['uws_nonce']) || !wp_verify_nonce(wp_unslash($_REQUEST['uws_nonce']), $uvnonce)) {
	// if(!isset($_REQUEST['uws_nonce']) || !wp_verify_nonce(wp_unslash($_REQUEST['uws_nonce']), $uvnonce)) { // Axl UWS-7416
	if(!isset($_REQUEST['uws_nonce']) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['uws_nonce'] ) ), $uvnonce)) { // Axl UWS-7418
		wp_send_json_error(array('message' => 'Invalid nonce'), 403);
	}
}

// @egt [UWS-7297]
// function uws_cleanup_request(string $uv_request, string $uv_default = ''): string {
function urvenue_ws_cleanup_request(string $uv_request, string $uv_default = ''): string { // Axl UWS-7416
    if(!isset($_REQUEST[$uv_request])) {
        return $uv_default;
    }

    // return uws_cleanup_var(
    return urvenue_ws_cleanup_var( // Axl UWS-7416
        sanitize_text_field(
            wp_unslash($_REQUEST[$uv_request])
        )
    );
}
