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
    global $urvenue_ws_curlang;
    $urvenue_ws_curlang = "en";

    // if (uws_is_wordpress()) {
    if (urvenue_ws_is_wordpress()) { // Axl UWS-7416

		// Check for WPML
        if (apply_filters('wpml_current_language', null)) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Third-party WPML filter
            $urvenue_ws_curlang = apply_filters('wpml_current_language', null); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Third-party WPML filter
        }
        // Check for Polylang
        elseif (function_exists('pll_current_language')) {
            $urvenue_ws_curlang = pll_current_language();
        }
        // Fallback to pll_the_languages if pll_current_language is not available
        elseif (function_exists('pll_the_languages')) {
            $languages = pll_the_languages(array('raw' => 1));
            foreach ($languages as $language) {
                if ($language['current_lang']) {
                    $urvenue_ws_curlang = $language['slug'];
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
	global $urvenue_ws_curlang;

	// if(!$urvenue_ws_curlang) uws_set_cur_lang();
	if(!$urvenue_ws_curlang) urvenue_ws_set_cur_lang(); // Axl UWS-7416

    return $urvenue_ws_curlang;
}

/*Get traslation based on key
    Requires: Key of the translation
    Returns: Traslated text for the current language if exists
*/
// $urvenue_ws_langvars = $urvenue_ws_langvars_en = "";
$urvenue_ws_langvars = $urvenue_ws_langvars_en = ""; // Axl UWS-7416
// function uws_lang($uvkey){
function urvenue_ws_lang($uvkey){ // Axl UWS-7416
	global $urvenue_ws_langvars, $urvenue_ws_path, $urvenue_ws_langvars_en;

    // $uvcurlang = uws_get_cur_lang();
    $urvenue_ws_curlang = urvenue_ws_get_cur_lang(); // Axl UWS-7416

	if(!$urvenue_ws_langvars){
		$uvlangfile = $urvenue_ws_path . "/langs/" . $urvenue_ws_curlang . ".json";

		if(file_exists($uvlangfile)){
			// $uvlangjson = uws_api_call($uvlangfile, 1);
			$uvlangjson = urvenue_ws_api_call($uvlangfile, 1); // Axl UWS-7416
			$urvenue_ws_langvars = json_decode($uvlangjson, true);
		}
		else{
			$uvlangfile = $urvenue_ws_path . "/langs/en.json";
			// $uvlangjson = uws_api_call($uvlangfile, 1);
			$uvlangjson = urvenue_ws_api_call($uvlangfile, 1); // Axl UWS-7416
			$urvenue_ws_langvars = json_decode($uvlangjson, true);
		}
	}

	$uvlangtext = (isset($urvenue_ws_langvars[$uvkey])) ? $urvenue_ws_langvars[$uvkey] : $uvkey;

	if(!$urvenue_ws_langvars[$uvkey]){
		if(!$urvenue_ws_langvars_en){
			$uvlangfile = $urvenue_ws_path . "/langs/en.json";
			// $uvlangjson = uws_api_call($uvlangfile, 1);
			$uvlangjson = urvenue_ws_api_call($uvlangfile, 1); // Axl UWS-7416
			$urvenue_ws_langvars_en = json_decode($uvlangjson, true);
		}

		if($urvenue_ws_langvars_en[$uvkey])
			$uvlangtext = $urvenue_ws_langvars_en[$uvkey];
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
    $urvenue_ws_curlang = urvenue_ws_get_cur_lang(); // Axl UWS-7416

	if($urvenue_ws_curlang and $urvenue_ws_curlang != "en"){
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