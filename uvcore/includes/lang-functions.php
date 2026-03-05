<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*Set the current language
	Returns: language code: (en, ja, es, fr, etc)
*/
if (function_exists('add_action'))
	// add_action('wp_loaded', 'uws_set_cur_lang');
	add_action('wp_loaded', 'urvenue_ws_set_cur_lang'); // Axl UWS-7416

// function uws_set_cur_lang() {
function urvenue_ws_set_cur_lang() { // Axl UWS-7416
    global $uvcurlang;
    $uvcurlang = "en";

    // if (uws_is_wordpress()) {
    if (urvenue_ws_is_wordpress()) { // Axl UWS-7416
        
		// Check for WPML
        if (apply_filters('wpml_current_language', null)) {
            $uvcurlang = apply_filters('wpml_current_language', null);
        }
        // Check for Polylang
        elseif (function_exists('pll_current_language')) {
            $uvcurlang = pll_current_language();
        } 
        // Fallback to pll_the_languages if pll_current_language is not available
        elseif (function_exists('pll_the_languages')) {
            $languages = pll_the_languages(array('raw' => 1));
            foreach ($languages as $language) {
                if ($language['current_lang']) {
                    $uvcurlang = $language['slug'];
                    break;
                }
            }
        }
    }
}

/*Get the current language
    Returns: language code: (en, ja, es, fr, etc)
*/
// function uws_get_cur_lang(){
function urvenue_ws_get_cur_lang(){ // Axl UWS-7416
	global $uvcurlang;

	// if(!$uvcurlang) uws_set_cur_lang();
	if(!$uvcurlang) urvenue_ws_set_cur_lang(); // Axl UWS-7416

    return $uvcurlang;
}

/*Get traslation based on key
    Requires: Key of the translation
    Returns: Traslated text for the current language if exists
*/
$uws_langvars = $uws_langvars_en = "";
// function uws_lang($uvkey){
function urvenue_ws_lang($uvkey){ // Axl UWS-7416
	global $uws_langvars, $uws_path, $uws_langvars_en;

    // $uvcurlang = uws_get_cur_lang();
    $uvcurlang = urvenue_ws_get_cur_lang(); // Axl UWS-7416

	if(!$uws_langvars){
		$uvlangfile = $uws_path . "/langs/" . $uvcurlang . ".json";
		
		if(file_exists($uvlangfile)){
			// $uvlangjson = uws_api_call($uvlangfile, 1);
			$uvlangjson = urvenue_ws_api_call($uvlangfile, 1); // Axl UWS-7416
			$uws_langvars = json_decode($uvlangjson, true);
		}
		else{
			$uvlangfile = $uws_path . "/langs/en.json";
			// $uvlangjson = uws_api_call($uvlangfile, 1);
			$uvlangjson = urvenue_ws_api_call($uvlangfile, 1); // Axl UWS-7416
			$uws_langvars = json_decode($uvlangjson, true);
		}
	}

	$uvlangtext = (isset($uws_langvars[$uvkey])) ? $uws_langvars[$uvkey] : $uvkey;

	if(!$uws_langvars[$uvkey]){
		if(!$uws_langvars_en){
			$uvlangfile = $uws_path . "/langs/en.json";
			// $uvlangjson = uws_api_call($uvlangfile, 1);
			$uvlangjson = urvenue_ws_api_call($uvlangfile, 1); // Axl UWS-7416
			$uws_langvars_en = json_decode($uvlangjson, true);
		}

		if($uws_langvars_en[$uvkey])
			$uvlangtext = $uws_langvars_en[$uvkey];
	}

	return $uvlangtext;
}

/*Get date translation
    Requires: Date in english
    Returns: Date in the current language if exists
*/
// function uws_lang_date($uvddate){
function urvenue_ws_lang_date($uvddate){ // Axl UWS-7416
    // $uvcurlang = uws_get_cur_lang();
    $uvcurlang = urvenue_ws_get_cur_lang(); // Axl UWS-7416

	if($uvcurlang and $uvcurlang != "en"){
		// $uvlangfulldate = uws_lang("uv-date-fullday");
		$uvlangfulldate = urvenue_ws_lang("uv-date-fullday"); // Axl UWS-7416
		// $uvlangfullmonths = uws_lang("uv-date-fullmonth");
		$uvlangfullmonths = urvenue_ws_lang("uv-date-fullmonth"); // Axl UWS-7416
		// $uvlangabbdate = uws_lang("uv-date-abbday");
		$uvlangabbdate = urvenue_ws_lang("uv-date-abbday"); // Axl UWS-7416
		// $uvlangabbmonths = uws_lang("uv-date-abbmonth");
		$uvlangabbmonths = urvenue_ws_lang("uv-date-abbmonth"); // Axl UWS-7416

		if(is_array($uvlangfulldate)){
			foreach($uvlangfulldate as $uvfulldatekey => $uvfulldate){
				$uvddate = str_replace($uvfulldatekey, $uvfulldate, $uvddate);
			}
		}
		if(is_array($uvlangfullmonths)){
			foreach($uvlangfullmonths as $uvfullmonthkey => $uvfullmonth){
				$uvddate = str_replace($uvfullmonthkey, $uvfullmonth, $uvddate);
			}
		}
		if(is_array($uvlangabbdate)){
			foreach($uvlangabbdate as $uvabbdatekey => $uvabbdate){
				$uvddate = str_replace($uvabbdatekey, $uvabbdate, $uvddate);
			}
		}
		if(is_array($uvlangabbmonths)){
			foreach($uvlangabbmonths as $uvabbmonthkey => $uvabbmonth){
				$uvddate = str_replace($uvabbmonthkey, $uvabbmonth, $uvddate);
			}
		}
	}

	return $uvddate;
}