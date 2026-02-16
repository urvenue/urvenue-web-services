<?php

//$uws_feeds_debug: if is 1 show debug messages

/*Get API by feed key from library or by url
    Requires: feedkey(key to identify API), uvterms(array with vars to send or expiration time in case it's direct url API)
    Returns: API response in a php array
*/
function uws_get_feed($uvfeedkey, $uvterms = ""){
	global $uws_feeds_lib, $uws_feeds_debug;

	if(preg_match("/^https?:\/\/.+$/", $uvfeedkey)){ //Check if is direct feed url
		$uvfeedexpiration = $uvterms ? $uvterms : 86400; //If expiration is not present set to 1 day
		return uws_call_feed($uvfeedkey, $uvfeedexpiration);
	}
	else{
		if(array_key_exists($uvfeedkey, $uws_feeds_lib)){
			if($uvterms)
				return uws_get_lib_feed($uvfeedkey, $uvterms);
			else if($uws_feeds_debug)
                uws_feed_debug_msg("Parameters are required");
		}
		else if($uws_feeds_debug)
            uws_feed_debug_msg("Given feed key({$uvfeedkey}) does not exist");
	}
}

/*Process a feed key and calls the function uws_call_feed
    Requires: feedkey(key to identify API), uvterms(array or string with the API parameters)
    Returns: same as uws_call_feed
*/
function uws_get_lib_feed($uvfeedkey, $uvterms){
	global $uws_feeds_lib, $uws_feeds_path, $uws_core_lib;
	
    //Get API url and expiration
	$uvlibfeedurl = $uws_feeds_lib[$uvfeedkey]["url"];
	$uvlibfeedexpiration = $uws_feeds_lib[$uvfeedkey]["expiration"];
	
    if(is_array($uvterms)){
		$uvgetandchar = $uvparams = "";
        foreach($uvterms as $uvkey => $uvvalue){
            $uvparams .= $uvgetandchar . $uvkey . "=" . $uvvalue;
            $uvgetandchar = "&";
        }
    }
    else
        $uvparams = $uvterms;
		
	$uvparams .= (uws_is_wordpress() && get_option('cacheword')) ? "&cacheword=" . get_option('cacheword') : "&cacheword=" . $uws_core_lib["system"]["cache-word"];
	$uvlibfeedurl = str_replace("{params}", $uvparams, $uvlibfeedurl);
		
	return uws_call_feed($uvlibfeedurl, $uvlibfeedexpiration, $uvfeedkey);
}

/*Decides between get local feed or call the API
    Requires: uvfeedurl(API url), uvfeedexpiration(time in seconds to expire), uvfeedkey(optional feed key)
    Returns: API response in a php array 
*/
function uws_call_feed($uvfeedurl, $uvfeedexpiration, $uvfeedkey = ""){
	global $uws_feeds_path;
	
	if(uws_feeds_cache_is_writable() and $uvfeedexpiration > 0){
		return uws_get_cached_feed($uvfeedurl, $uvfeedexpiration, $uvfeedkey);
	}
	else{
		return uws_get_feed_nocache($uvfeedurl);
	}
}

/*Check if cached feed exists, if it doesn't exist or is expired it create it again
    Required: feedurl(API url), feedexpiration(time in seconds to expire), uvfeedkey(optional feed key)
    Returns: API response in a php array
*/
function uws_get_cached_feed($uvfeedurl, $uvfeedexpiration, $uvfeedkey = ""){
	global $uws_feeds_path, $uws_feeds_debug, $uws_config_manageentid;

	$uvfileexpiresat = $uvfilelastupdate = "";
    $uvfeefilenof = $uvupdateinfofile = false;
	$uvnowtime = time();
    
    if(!preg_match("/^.+\.(\w{3,4})$/", $uvfeedurl, $uvfeedurlparts))
        if(!preg_match("/^.+\.(\w{3,4})[\?].+$/", $uvfeedurl, $uvfeedurlparts))
            $uvfeefilenof = true;

    if($uvfeefilenof)
        $uvfeedfiletype = "json";
    else
        $uvfeedfiletype = $uvfeedurlparts[1];
    
    $uvfeedhash = hash('md5', $uvfeedurl);

	if($uws_config_manageentid and preg_match("/^\d+$/", $uws_config_manageentid))
		$uvfeedcachefolder = $uws_feeds_path . "/" . $uws_config_manageentid;
	else
		$uvfeedcachefolder = $uws_feeds_path . "/global";
    
	$uvfeedfullpath = $uvfeedcachefolder . "/" . $uvfeedhash . "." . $uvfeedfiletype;

	if(!file_exists("$uvfeedcachefolder"))
		mkdir($uvfeedcachefolder, 0777);
    
    if(file_exists("$uvfeedfullpath")){ //Check if local file exists
        $uvfileexpiresat = filemtime($uvfeedfullpath) + $uvfeedexpiration;
        
        if($uvnowtime > $uvfileexpiresat){//Feed has expired, create again
            $uvfilecontent = uws_create_feed_file($uvfeedurl, $uvfeedfullpath, $uvfeedexpiration, $uvfeedkey ?? "");
			$uvfilelastupdate = $uvnowtime;
			$uvupdateinfofile = 1;
            
            if($uws_feeds_debug)
                uws_feed_debug_msg("Cached feed refreshed: <a href='$uvfeedurl' target='_blank'>$uvfeedurl</a>");
        }
        else{
			$uvnowtime = filemtime($uvfeedfullpath);
            $uvfilecontent = uws_api_call($uvfeedfullpath, true); //Get local cache
            
            if($uws_feeds_debug)
                uws_feed_debug_msg("Feed called from cache: <a href='$uvfeedurl' target='_blank'>$uvfeedurl</a>");
        }
    }
    else{
		$uvfileexpiresat = $uvnowtime + $uvfeedexpiration;
		$uvfilelastupdate = $uvnowtime;
        $uvfilecontent = uws_create_feed_file($uvfeedurl, $uvfeedfullpath, $uvfeedexpiration, $uvfeedkey ?? "");
		$uvupdateinfofile = 1;
        
        if($uws_feeds_debug)
            uws_feed_debug_msg("Cache feed created: <a href='$uvfeedurl' target='_blank'>$uvfeedurl</a>");
    }
    
    $uvfilecontent = uws_get_feed_array($uvfilecontent, $uvfeedfiletype);

	$uvfeedinfo = array(
		"feedhash" => $uvfeedhash,
		"feedurl" => $uvfeedurl,
		"feedpath" => $uvfeedfullpath,
		"expiration" => $uvfileexpiresat,
		"lastupdate" => $uvfilelastupdate,
	);

	if($uvupdateinfofile)
		uws_update_feeds_infofile($uvfeedinfo);
    
    return $uvfilecontent;
}

/*Creates and updates the feeds info files, set the time to clear cache
	Requires: feedinfo(array with feed info)
*/
function uws_update_feeds_infofile($uvfeedinfo){
	global $uws_feeds_path, $uws_feedscleartime, $uws_config_manageentid, $uws_feeds_debug;

	if($uws_feeds_path and is_array($uvfeedinfo)){
		$uvtimenow = time();

		if($uws_config_manageentid and preg_match("/^\d+$/", $uws_config_manageentid))
			$uvfeedcachefolder = $uws_feeds_path . "/" . $uws_config_manageentid;
		else
			$uvfeedcachefolder = $uws_feeds_path . "/global";

		$uvfeedsinfofilepath = $uvfeedcachefolder . "/cachedfeedsinfo.json";

		if(!file_exists($uvfeedsinfofilepath)){//Check if feeds info file exists if not create it
			$uvclearfeedstime = $uvtimenow + $uws_feedscleartime;
			$uvdclearfeedstime = date("Y-m-d H:i:s", $uvclearfeedstime);
		
			$uvfeedsinfofilearray = array(
				"clearfeedstime" => $uvclearfeedstime,
				"dclearfeedstime" => $uvdclearfeedstime,
				"feeds" => array()
			);
		}
		else{
			$uvfeedsinfofile = uws_api_call($uvfeedsinfofilepath, true);
			$uvfeedsinfofilearray = json_decode($uvfeedsinfofile, true);
		}

		$uvclearfeedstime = $uvfeedsinfofilearray["clearfeedstime"];
		$uvfeedsinfofilearray["feeds"][$uvfeedinfo["feedhash"]] = $uvfeedinfo;
		$uvfeedsinfofilejson = json_encode($uvfeedsinfofilearray);

		if(is_writable($uvfeedcachefolder)){
			/*$fp = fopen("$uvfeedsinfofilepath", "w+");
			fwrite($fp, $uvfeedsinfofilejson);
			fclose($fp);*/

			$fp = @fopen($uvfeedsinfofilepath, "w+");
			if ($fp === false) {
				if($uws_feeds_debug)
					uws_feed_debug_msg("Failed to open file for writing: $uvfeedsinfofilepath. Permission denied or path incorrect.<br>");
			} else {
				fwrite($fp, $uvfeedsinfofilejson);
				fclose($fp);
			}
		}

		$uvtimetoclean = $uvclearfeedstime - $uvtimenow;
		
		if($uvtimenow > $uvclearfeedstime)//check if cache has to be cleared
			uws_clean_cached_feeds();
	}
}

/*Calls API and creates or updates the local file
    Requires: feedurl(API url), feedfullpath(local file path), uvfeedkey(optional feed key for special handling)
*/
function uws_create_feed_file($uvfeedurl, $uvfeedfullpath, $uvfeedexpiration = 0, $uvfeedkey = ""){
	$uvvalidfeedcheck = false;
	$uvkeepoldcache = false;
	
	if($uvfeedfullpath and $uvfeedurl){
		$uvfilecontent = uws_api_call($uvfeedurl);
		$uvinveventsonly = ($uvfeedkey === "inventory-eventsonly") ? true : false;

		// Use existing validation functions
		if(uws_check_feed_url($uvfeedurl))
			$uvvalidfeedcheck = uws_check_feed_response($uvfilecontent);
		else
			$uvvalidfeedcheck = uws_check_api_response($uvfilecontent);
		
		// Special handling for inventory-eventsonly
		if($uvinveventsonly && $uvvalidfeedcheck){
			$uvresponse = json_decode($uvfilecontent, true);
			
			// Check if response is empty or has no data
			if(!isset($uvresponse["uv"]["data"]) || empty($uvresponse["uv"]["data"])){
				$uvvalidfeedcheck = false;
				$uvkeepoldcache = true;
				uws_send_integration_alert("empty_events_response", $uvfeedurl);
			}
			// Check if schedules exist
			else if(!isset($uvresponse["uv"]["data"]["schedules"]) || empty($uvresponse["uv"]["data"]["schedules"])){
				$uvvalidfeedcheck = false;
				$uvkeepoldcache = true;
				uws_send_integration_alert("empty_schedules_response", $uvfeedurl);
			}
			else{
				// Check minimum event count threshold first
				$uvmineventscheck = uws_check_min_events($uvresponse, $uvfeedurl);
				
				// Only validate integrity if min events check passed
				if($uvmineventscheck){
					$uvintegritycheck = uws_validate_schedules_integrity($uvresponse["uv"]["data"]["schedules"], $uvfeedurl);
					if(!$uvintegritycheck){
						$uvvalidfeedcheck = false;
						$uvkeepoldcache = true;
					}
				}
				else{
					// Min events check failed, keep old cache
					$uvvalidfeedcheck = false;
					$uvkeepoldcache = true;
				}
			}
		}
		// For inventory-eventsonly, also check API errors
		else if($uvinveventsonly && !$uvvalidfeedcheck){
			$uvkeepoldcache = true;
			uws_send_integration_alert("api_error", $uvfeedurl);
		}
	
		// Only delete old cache if we're not keeping it
		if(file_exists("$uvfeedfullpath") && !$uvkeepoldcache)
			unlink("$uvfeedfullpath");

		if($uvvalidfeedcheck){
			$fp = fopen("$uvfeedfullpath", "w+");
			fwrite($fp, $uvfilecontent);
			fclose($fp);
		}
		
		// If keeping old cache, return the old cached content
		if($uvkeepoldcache && file_exists("$uvfeedfullpath")){
			return uws_api_call($uvfeedfullpath, true);
		}
		
		return $uvfilecontent;
	}
}

/**
 * Checks if the variable $uvfeedurl starts with the specified URL pattern.
 * The URL pattern is "https://$uws_envicode.urvenue.me/v"
 * 
 * @param string $uvfeedurl The URL to be checked.
 * @return bool Returns true if $uvfeedurl starts with the specified URL pattern, false otherwise.
 */
function uws_check_feed_url($uvfeedurl){
	global $uws_envicode;

	return preg_match("/^https:\/\/$uws_envicode.urvenue.me\/v/", $uvfeedurl);
}

/**
 * Checks if the API response is not empty.
 * 
 * Verifies if the API response is not empty and if it is a valid JSON string.
 * 
 * @param void
 * @return bool Returns true if the API response is valid, false otherwise.
 */
function uws_check_api_response($uvfilecontent){
	if($uvfilecontent){
		return true;
	}
	return false;
}

/**
 * Checks if the API response is valid.
 * 
 * Verifies if the API response is not empty and if the 'status' property of the 'success' object is set to 'success'.
 * 
 * @param void
 * @return bool Returns true if the API response is valid, false otherwise.
 */
function uws_check_feed_response($uvfilecontent){
	$uvresponse = json_decode($uvfilecontent, true);

	if(is_array($uvresponse) && isset($uvresponse["uv"]["success"]["status"]) && $uvresponse["uv"]["success"]["status"] === "success"){
		return true;
	}
	
	return false;
}

/*Transforms API response into a php array
    Requires: uvfilecontent(API Responsive), uvfeedfiletype(File type)
*/
function uws_get_feed_array($uvfilecontent, $uvfeedfiletype){
	if($uvfeedfiletype == "json"){
		$uvfilecontent = json_decode($uvfilecontent, true);
	}
	else if($uvfeedfiletype == "pc8"){
		eval($uvfilecontent);
		$uvfilecontent = $xc8;
	}
	
	return $uvfilecontent;
}

/*Get no cached api
	Requires: uvfeedurl(API url)
	Options: feedkey(key to identify API)
*/
function uws_get_feed_nocache($uvfeedurl, $uvfeedkey = ""){
	global $uws_feeds_debug;

	if((preg_match("/^.+\.(\w{3,4})$/", $uvfeedurl, $uvfeedurlparts) or preg_match("/^.+\.(\w{3,4})[\?].+$/", $uvfeedurl, $uvfeedurlparts))){
		$uvfeedfiletype = $uvfeedurlparts[1];
		
		$uvfilecontent = uws_api_call($uvfeedurl);
		$uvfilecontent = uws_get_feed_array($uvfilecontent, $uvfeedfiletype, $uvfeedkey);
		
		if($uws_feeds_debug)
            uws_feed_debug_msg("No cache feed called: $uvfeedurl");
		
		return $uvfilecontent;
	}
	else{
		$uvfeedfiletype = "json";
		
		$uvfilecontent = uws_api_call($uvfeedurl);
		$uvfilecontent = uws_get_feed_array($uvfilecontent, $uvfeedfiletype, $uvfeedkey);
		
		if($uws_feeds_debug)
            uws_feed_debug_msg("No cache feed called: $uvfeedurl");
		
		return $uvfilecontent;
	}
}
    
/*Deletes all cache files*/
function uws_clean_cached_feeds(){
	global $uws_feeds_path, $uws_config_manageentid, $uws_feeds_debug;

	if($uws_feeds_path){
        //$uvcachedir = "$uws_feeds_path/global";
		if($uws_config_manageentid and preg_match("/^\d+$/", $uws_config_manageentid))
			$uvcachedir = $uws_feeds_path . "/" . $uws_config_manageentid;
		else
			$uvcachedir = $uws_feeds_path . "/global";

		$uvfilescount = 0;

		if(file_exists($uvcachedir)){
			$uvfiles = glob($uvcachedir . '/{,.}*', GLOB_BRACE);
			foreach($uvfiles as $uvfile){
				if(is_file($uvfile)){
					if(is_writable($uvfile)){
						if(@unlink($uvfile))
							$uvfilescount++;
					}
				}
			}

            if(@rmdir($uvcachedir) and $uws_feeds_debug)
				uws_feed_debug_msg("Cache folder deleted: $uvcachedir");
			else if($uws_feeds_debug)
				uws_feed_debug_msg("Cache folder could not be deleted: $uvcachedir");
		}
		
		if($uws_feeds_debug)
            uws_feed_debug_msg("$uvfilescount cached files deleted");

		return $uvfilescount;
	}
	return false;
}

/*Makes API Request
    Requires: uvfileurl(API url)
    Optional: uvusefileget(set to true to use file_get_contents)
*/
function uws_api_call($uvfileurl, $uvusefileget = false){
	if(!$uvusefileget){
    	// TESTING @Axl
		// $ch = curl_init();
		// curl_setopt($ch, CURLOPT_URL, $uvfileurl);
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,5);
		// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		// curl_setopt($ch, CURLOPT_TIMEOUT, 60);
   
		// $output = curl_exec($ch);
    	
		// TESTING @Axl
		$response = wp_remote_get($uvfileurl, array(
			'timeout' => 60,
			'redirection' => 5,
		));
		$output = wp_remote_retrieve_body($response);
   }
   else
	   $output = @file_get_contents($uvfileurl);
	
	return($output);
}

/*Check if local cache folder is writtable*/
function uws_feeds_cache_is_writable(){
    global $uws_feeds_path;

    $uvfeedscacheiswritable = 0;

    if($uws_feeds_path and is_writable($uws_feeds_path))
        $uvfeedscacheiswritable = 1;

    return $uvfeedscacheiswritable;
}

/*Shows Debug Message*/
function uws_feed_debug_msg($uwsmsg){
    echo "<pre class='uwsdebugpre'><code>UVDebug: " . $uwsmsg . "</code></pre>";
}

/**
 * Checks if the number of events meets the minimum threshold
 * Counts actual individual events, not just schedule dates
 * 
 * @param array $uvschedules Array of schedules from API response
 * @param string $uvfeedurl The API URL for reference
 * @return bool Returns true if event count meets minimum, false otherwise
 */
function uws_check_min_events($uvresponse, $uvfeedurl){
	global $uws_core_lib;
	
	// Check if $uvresponse is an array
	if(!is_array($uvresponse) || !isset($uvresponse["uv"]["data"]["schedules"])) return false;
	
	// Get minimum events setting (default to 1 if not set)
	$uvminevents = isset($uws_core_lib["notifications"]["minevents"]) ? (int)$uws_core_lib["notifications"]["minevents"] : 1;
	
	// If minevents is 0 or 1, skip validation
	if($uvminevents <= 1) return true;
	
	// Extract from and to dates from URL - support multiple parameter formats
	parse_str(parse_url($uvfeedurl, PHP_URL_QUERY), $uvqueryparams);
	
	$uvfromdate = null;
	$uvtodate = null;
	$uvdaysrequested = null;
	
	if(isset($uvqueryparams['from']) && isset($uvqueryparams['to'])){
		$uvfromdate = $uvqueryparams['from'];
		$uvtodate = $uvqueryparams['to'];
	}
	else if(isset($uvqueryparams['fromdate']) && isset($uvqueryparams['todate'])){
		$uvfromdate = $uvqueryparams['fromdate'];
		$uvtodate = $uvqueryparams['todate'];
	}
	else if(isset($uvqueryparams['caldate']) && isset($uvqueryparams['todate'])){
		// caldate works like from/fromdate and combines with todate
		$uvfromdate = $uvqueryparams['caldate'];
		$uvtodate = $uvqueryparams['todate'];
	}
	
	// Calculate days requested (for reporting purposes)
	if($uvfromdate && $uvtodate){
		$uvfromdatetime = strtotime($uvfromdate);
		$uvtodatetime = strtotime($uvtodate);
		$uvdaysrequested = ceil(($uvtodatetime - $uvfromdatetime) / 86400) + 1;
	}

	// Only run minimum events validation for the initial range requested by the user.
	// If the request `from` date is different than the system `uvinitialdate`, skip alerts
	// to avoid notifying for far/paginated ranges that legitimately contain no events.
	if($uvfromdate){
		// Get initial date of events
		$uvinitialdate = uws_get_events_initial_date("Y-m-d");
		$uvfromnormalized = date("Y-m-d", strtotime($uvfromdate));
		if($uvinitialdate && $uvfromnormalized !== $uvinitialdate){
			return true; // skip min-events validation for non-initial ranges
		}
	}
	
	// Count actual individual events using uws_get_events_array if available
	$uvactualevents = 0;
	if(function_exists('uws_get_events_array')){
		// Use full API response format for uws_get_events_array
		$uveventsarray = uws_get_events_array($uvresponse);
		$uvactualevents = count($uveventsarray);
	}
	else{
		// Fallback: count events manually by iterating through schedules
		$uvschedules = $uvresponse["uv"]["data"]["schedules"];
		foreach($uvschedules as $uvdate => $uvdatedata){
			if(is_array($uvdatedata)){
				foreach($uvdatedata as $uvvenuecode => $uvvenuedata){
					if(is_array($uvvenuedata)){
						foreach($uvvenuedata as $uvecozonecode => $uvecozonedata){
							if(isset($uvecozonedata['event']) && is_array($uvecozonedata['event']) && isset($uvecozonedata['event']['eventid']) && $uvecozonedata['event']['eventid']){
								$uvactualevents++;
							}
						}
					}
				}
			}
		}
	}
	
	// TESTING: Force alert
	// $uvminevents = 100;
	
	// Only validate minimum event count for date ranges of 28+ days (full month or more)
	// This prevents false alerts for short date ranges or single day requests
	if($uvdaysrequested < 28){
		return true;
	}
	
	// Check if actual event count is below minimum
	if($uvactualevents < $uvminevents){
		uws_send_integration_alert("low_event_count", $uvfeedurl, array(
			"min_events" => $uvminevents,
			"actual_count" => $uvactualevents,
			"days_requested" => $uvdaysrequested ?? 'unknown'
		));
		return false;
	}
	
	return true;
}

/**
 * Validates the integrity of event schedules
 * 
 * @param array $uvschedules Array of schedules from API response (keyed by date like "D251208" or "D20260301")
 * @param string $uvfeedurl The API URL for reference
 * @return bool Returns true if schedules are valid, false otherwise
 */
function uws_validate_schedules_integrity($uvschedules, $uvfeedurl){
	if(!is_array($uvschedules)){
		return false;
	}
	
	// Extract from and to dates from URL - support multiple parameter formats
	parse_str(parse_url($uvfeedurl, PHP_URL_QUERY), $uvqueryparams);
	
	// Check for 'from'/'to', 'fromdate'/'todate', or 'caldate' parameters
	$uvfromdate = null;
	$uvtodate = null;
	
	if(isset($uvqueryparams['from']) && isset($uvqueryparams['to'])){
		$uvfromdate = $uvqueryparams['from'];
		$uvtodate = $uvqueryparams['to'];
	}
	else if(isset($uvqueryparams['fromdate']) && isset($uvqueryparams['todate'])){
		$uvfromdate = $uvqueryparams['fromdate'];
		$uvtodate = $uvqueryparams['todate'];
	}
	else if(isset($uvqueryparams['caldate']) && isset($uvqueryparams['todate'])){
		// caldate works like from/fromdate and combines with todate
		$uvfromdate = $uvqueryparams['caldate'];
		$uvtodate = $uvqueryparams['todate'];
	}
	
	if(!$uvfromdate || !$uvtodate){
		// If no date parameters found, skip validation
		return true;
	}
	
	// Count unique dates in schedules (keys are like "D251208" or "D20260301")
	$uvactualdates = count($uvschedules);
	
	// Calculate expected number of dates
	$uvfromdatetime = strtotime($uvfromdate);
	$uvtodatetime = strtotime($uvtodate);
	$uvdaysdiff = ceil(($uvtodatetime - $uvfromdatetime) / 86400) + 1; // +1 to include both start and end dates
	
	// For single day requests, skip date count validation if we have at least one schedule date
	if($uvdaysdiff == 1 && $uvactualdates >= 1){
		return true;
	}
	
	// API might have a limit (e.g., 30 days)
	$uvapilimit = 30;
	$uvexpecteddates = min($uvdaysdiff, $uvapilimit);
	
	// Validate: we should have the expected number of dates (or close to it)
	if($uvdaysdiff > $uvapilimit){
		// Range is bigger than API limit, check if we got the limit
		if($uvactualdates < $uvapilimit){
			uws_send_integration_alert("schedules_integrity_failed", $uvfeedurl, array(
				"expected_dates" => $uvapilimit,
				"actual_dates" => $uvactualdates,
				"from_date" => $uvfromdate,
				"to_date" => $uvtodate,
				"days_requested" => $uvdaysdiff
			));
			return false;
		}
	}
	else{
		// Range is within limit, check if we got all dates
		// Skip validation for single day requests (from = to) when no schedules returned
		// This is normal behavior when there are no events on that specific day
		if($uvdaysdiff == 1 && $uvactualdates == 0){
			return true;
		}
		
		// Allow some tolerance (e.g., 80% of expected dates)
		if($uvactualdates < ($uvexpecteddates * 0.8)){
			uws_send_integration_alert("schedules_integrity_failed", $uvfeedurl, array(
				"expected_dates" => $uvexpecteddates,
				"actual_dates" => $uvactualdates,
				"from_date" => $uvfromdate,
				"to_date" => $uvtodate,
				"days_requested" => $uvdaysdiff
			));
			return false;
		}
	}
	
	return true;
}

/**
 * Sends integration alerts to Slack webhook _uws_integrations_alerts
 * 
 * @param string $uvalerttype Type of alert (empty_events_response, api_error, event_no_schedules, schedules_integrity_failed)
 * @param string $uvfeedurl The API URL
 * @param array $uvdetails Additional details for the alert
 */
function uws_send_integration_alert($uvalerttype, $uvfeedurl = "", $uvdetails = array()){
	global $uws_website_notices_types;
	
	if(!isset($uws_website_notices_types[$uvalerttype])){
		return false;
	}
	
	$uvmessage = $uws_website_notices_types[$uvalerttype]["message_template"];
	
	// Get website URL
	$uvsite_url = function_exists('get_site_url') ? get_site_url() : ($_SERVER['HTTP_HOST'] ?? 'Unknown');
	
	// Replace standard placeholders
	$uvmessage = str_replace('{website_url}', $uvsite_url, $uvmessage);
	$uvmessage = str_replace('{api_url}', $uvfeedurl, $uvmessage);
	
	// Replace custom detail placeholders
	if(!empty($uvdetails)){
		foreach($uvdetails as $uvkey => $uvvalue){
			$uvmessage = str_replace('{'.$uvkey.'}', $uvvalue, $uvmessage);
		}
	}
	
	// Send notification using existing notification system
	// Pass the alert type as first parameter for proper throttling
	if(function_exists('uws_website_notices_send')){
		uws_website_notices_send($uvalerttype, $uvmessage);
	}
	
	return true;
}

if(!uws_is_wordpress() and isset($_REQUEST["uvclearcache"]) and $_REQUEST["uvclearcache"])
    uws_clean_cached_feeds();