<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// autogenerate cache apikey only 1 time, if not exists
// $uvs_cacheapikey = (function_exists('get_option') and function_exists('add_menu_page')) ? get_option("uvs_cacheapikey") : "";
// $uvs_cacheapikey = (function_exists('get_option') and function_exists('add_menu_page')) ? get_option("urvenue_ws_cacheapikey") : ""; // Axl UWS-7416
$urvenue_ws_cacheapikey = (function_exists('get_option') and function_exists('add_menu_page')) ? get_option("urvenue_ws_cacheapikey") : ""; // Axl UWS-7416
// $uvsiteURL = (function_exists('get_option') and function_exists('add_menu_page')) ? get_site_url() : "";
$urvenue_ws_siteURL = (function_exists('get_option') and function_exists('add_menu_page')) ? get_site_url() : ""; // Axl UWS-7416

if(!$urvenue_ws_cacheapikey && function_exists('get_option') and function_exists('add_menu_page')){
    $urvenue_ws_cacheapikey = md5(uniqid(rand(), true)); // Axl UWS-7416
    // update_option("uvs_cacheapikey", $uvs_cacheapikey);
    update_option("urvenue_ws_cacheapikey", $urvenue_ws_cacheapikey); // Axl UWS-7416
}

if(isset($uv_uwscore_overwrite_lib_defaults) and is_array($uv_uwscore_overwrite_lib_defaults)){
    // $urvenue_ws_core_defaults_lib = $uv_uwscore_overwrite_lib_defaults;
    $urvenue_ws_core_defaults_lib = $uv_uwscore_overwrite_lib_defaults; // Axl UWS-7416
}
else{
    // $urvenue_ws_core_defaults_lib = array(
    $urvenue_ws_core_defaults_lib = array( // Axl UWS-7416
        "system" => array(
            "sourceloc" => "uwscore",
            "sourcecode" => "public",
            "apikey" => "",
            "microcode" => "",
            "use-inventorylist-forevents" => 0,
            "use-partnerid-fromapi" => 0,
            "use-market-events" => 0,
            "filter-marketplace" => 0,
            "include-stocks-on-events" => 0,
            "cache-word" => "asd",
            "templates-custom-folder" => "uwstemplates",
            "use-staging" => 0,
            "use-cartv2" => 0,
            "checkouttype" => "microsite",
        ),
        "events" => array(
            "global-source" => "all",
            "global-addvenuename" => 0,
            "global-nmonths" => 2,
            "global-hidenoflyer" => 0,
            "global-usevenuelogoasflyer" => 0,
            "global-initaldate" => "",
            "global-maxdate" => "",
            "global-dateformat" => "D, M j",
            "global-addperformerfilter" => 0,
            "global-updateurl" => 0,
            "global-defaulteventurl" => "event",
            "eventspage-dateselector" => "datepicker-date",
            "eventspage-monthsrange" => 6,
            "eventspage-addvenuefilter" => 0,
            "eventspage-viewmenu" => "icons+text",
            "eventspage-views" => array(
                "calendar" => array(
                    "show" => 1,
                    "order" => 1,
                    "label" => "Calendar",
                    "defaultview" => 1,
                    "icon" => "uwsicon-calendar-1",
                ),
                "agenda" => array(
                    "show" => 1,
                    "order" => 2,
                    "label" => "Agenda",
                    "defaultview" => 0,
                    "icon" => "uwsicon-th-thumb-empty",
                ),
                "list" => array(
                    "show" => 0,
                    "order" => 3,
                    "label" => "List",
                    "defaultview" => 0,
                    "icon" => "uwsicon-th-list",
                ),
            ),
            /*"calendar-nmonths" => 4,*/
            /*"calendar-addlist" => 0,*/
            /*"calendar-initialview" => "calendar",*/
            "calendar-onlyoneevent" => 0,
            "calendar-alwayslist" => 0,
            /*"calendar-viewmenu" => "icons+text",*/
            /*"calendar-monthseltype" => "dropdown",*/
            "agenda-columns" => 4,
            /*"list-listtype" => "rows",
            "list-maxevents" => "100",*/
            "slider-showarrows" => "1",
            "slider-showdots" => "1",
            "slider-animation" => "slide",
            "slider-maxevents" => "5",
            /*"event-url" => "/event/",
            "event-showartist" => 1,*/
            "event-layout" => "container",
            "event-columns" => "inventory-flyer",
            "event-activedropdowns" => 0,
            "market-events-venueid" => "",
            "addon-venues" => "",
            "addon-bottles" => array(
                "showdigitalmenu" => 0,
                "showsummary"     => 0,
                "menuapikey"      => "",
            ),
            "noinventorydates-enabled" => 0,
        ),
        "map" => array(
            "mappage-views" => array(
                "list" => array(
                    "label" => "List",
                ),
                "map" => array(
                    "label" => "Map",
                ),
            ),
            "mappage-addadmissionopt" => 1,
        ),
        "inventory" => array(
            "global-dateformat" => "l M j",
            "showiteminfoinline" => 1,
            "manageentlock" => 0,
            "namefields" => 0,
            "closepopupafteraddtocart" => 0,
        ),
        "cart" => array(
            "list-groups-as-events" => 0,
        ),
        /*"artists" => array(
            "artist-url" => "/artist/",
            "artist-imagetype" => "Profile Picture",
            "artist-imageratio" => "Square",
            "artist-listview" => "squares",
            "artist-buttonlabel" => "Artist Bio",
        ),*/
        "pages" => array(
            "events" => "",
            "singleevent" => "",
            "map" => "",
            "itempage" => "",
        ),
        "seo" => array(
            "enabledata" => "1",
            "enabletags" => "1",
            "seotitle" => "{eventname} | {sitetitle}",
            "seotakeapidescr" => "1",
            "seodescription" => "{eventname} at {venuename}, {eventddate}",
        ),
        "ui" => array(
            "uitheme" => "light",
            "primarycolor" => "",
            "secondarycolor" => "",
            "accentcolor" => "",
            "uipoptheme" => "light",
            "popaccentcolor" => "",
        ),
        "flyers" => array(
            "placeholderurl" => "",
            "eventpage-hideifnomatch" => 0,
            "eventpage-useplaceholder" => 1,
            "eventpage-sizecode" => "500SC0",
            "eventpage-placeholderurl" => "",
            "eventpage" => array(
                0 => array(
                    "type" => "Flyer",
                    "ratio" => "Vertical",
                ),
            ),
            "calendar-hideifnomatch" => 0,
            "calendar-useplaceholder" => 1,
            "calendar-sizecode" => "500SC0",
            "calendar-placeholderurl" => "",
            "calendar" => array(
                0 => array(
                    "type" => "Flyer",
                    "ratio" => "Square",
                ),
            ),
            "list-hideifnomatch" => 0,
            "list-useplaceholder" => 1,
            "list-sizecode" => "500SC0",
            "list-placeholderurl" => "",
            "list" => array(
                0 => array(
                    "type" => "Flyer",
                    "ratio" => "Square",
                ),
            ),
            "slider-hideifnomatch" => 1,
            "slider-useplaceholder" => 0,
            "slider-sizecode" => "1600SC0",
            "slider-placeholderurl" => "",
            "slider" => array(
                0 => array(
                    "type" => "Flyer",
                    "ratio" => "Horizontal",
                ),
            ),
            "slidermobile-hideifnomatch" => 1,
            "slidermobile-useplaceholder" => 0,
            "slidermobile-sizecode" => "500SC0",
            "slidermobile-placeholderurl" => "",
            "slidermobile" => array(
                0 => array(
                    "type" => "Flyer",
                    "ratio" => "Square",
                ),
            ),
            "share-hideifnomatch" => 0,
            "share-useplaceholder" => 1,
            "share-sizecode" => "1200SC0",
            "share-placeholderurl" => "",
            "share" => array(
                0 => array(
                    "type" => "Flyer",
                    "ratio" => "Horizontal",
                ),
            ),
            "custom1-hideifnomatch" => 0,
            "custom1-useplaceholder" => 1,
            "custom1-sizecode" => "800SC0",
            "custom1-placeholderurl" => "",
            "custom1" => array(
                0 => array(
                    "type" => "Flyer",
                    "ratio" => "Vertical",
                ),
            ),
            "custom2-hideifnomatch" => 0,
            "custom2-useplaceholder" => 1,
            "custom2-sizecode" => "800SC0",
            "custom2-placeholderurl" => "",
            "custom2" => array(
                0 => array(
                    "type" => "Flyer",
                    "ratio" => "Vertical",
                ),
            )
        ),
        "venueimages" => array(
            "placeholderurl" => "",
            "logodarkbg-hideifnomatch" => 1,
            "logodarkbg-useplaceholder" => 0,
            "logodarkbg-sizecode" => "300SC0",
            "logodarkbg-placeholderurl" => "",
            "logodarkbg" => array(
                0 => array(
                    "type" => "Logo",
                    "ratio" => "any",
                    "bgtype" => "clear_dark"
                ),
                1 => array(
                    "type" => "Logo",
                    "ratio" => "any",
                    "bgtype" => "dark"
                ),
                2 => array(
                    "type" => "Logo",
                    "ratio" => "any",
                    "bgtype" => "any"
                ),
            ),
            "logolightbg-hideifnomatch" => 1,
            "logolightbg-useplaceholder" => 0,
            "logolightbg-sizecode" => "300SC0",
            "logolightbg-placeholderurl" => "",
            "logolightbg" => array(
                0 => array(
                    "type" => "Logo",
                    "ratio" => "any",
                    "bgtype" => "clear_light"
                ),
                1 => array(
                    "type" => "Logo",
                    "ratio" => "any",
                    "bgtype" => "light"
                ),
                2 => array(
                    "type" => "Logo",
                    "ratio" => "any",
                    "bgtype" => "any"
                ),
            ),
            "gallery-hideifnomatch" => 1,
            "gallery-useplaceholder" => 0,
            "gallery-returnmultiple" => 1,
            "gallery-sizecode" => "600SC0",
            "gallery-placeholderurl" => "",
            "gallery" => array(
                0 => array(
                    "type" => "Visit",
                    "ratio" => "any",
                ),
                1 => array(
                    "type" => "View",
                    "ratio" => "any",
                ),
                2 => array(
                    "type" => "Action Shot",
                    "ratio" => "any",
                ),
                3 => array(
                    "type" => "Crowd",
                    "ratio" => "any",
                ),
            ),
        ),
        "cache" => array(
            "username" => "",
            "password" => "",
            "wpeinst" => "", // WP Engine installation ID
            "apikey" => "$urvenue_ws_cacheapikey",
            "endpoint" => "$urvenue_ws_siteURL/apis/uvclearcache/?apikey=$urvenue_ws_cacheapikey",
        ),
        "notifications" => array(
            "enable" => 0,
            "webhook" => "",
            "minevents" => 1,
        ),
    );
}

// $urvenue_ws_website_notices_types = array(
$urvenue_ws_website_notices_types = array( // Axl UWS-7416
    "noevents" => array(
        "message_template" => 
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n".
        "🚨 *Events are not being shown on* {website_url}.\n\n".
        "*1️⃣ Verify on the Website:*\n".
        "Click the link above and confirm whether events are actually missing on the live site.\n\n".
        "*2️⃣ Check the UrVenue System:*\n".
        "Confirm if this venue has upcoming events registered in the UrVenue backend.\n".
        "If there are no events, you can ignore this alert or disable these notifications in the website CMS settings.\n\n".
        "*3️⃣ Check the Venue Microsite:*\n".
        "If events exist in UrVenue but not on the website, check the venue microsite.\n".
        "- If events are missing in *both*, notify the *UWS or backend team*.\n".
        "- If events show in the microsite but not on the website, notify the *UWS team*.\n\n".
        "_Note: Always include the full website URL when reporting._\n".
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    ),
	"missingevents" => array(
		"message_template" => 
		"━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n".
		"🚨 *Some events are missing on* {website_url}.\n\n".
		"*1️⃣ Verify on the Website:*\n".
		"Click the link above and confirm whether specific events are actually missing on the live site.\n\n".
		"*2️⃣ Check the UrVenue System:*\n".
		"Confirm if these events are registered in the UrVenue backend for this venue.\n\n".
		"*3️⃣ Check the Venue Microsite:*\n".
		"If events exist in UrVenue but not on the website, check the venue microsite.\n".
		"- If events are missing in *both*, notify the *UWS or backend team*.\n".
		"- If events show in the microsite but not on the website, notify the *UWS team*.\n\n".
		"_Note: Always include the full website URL when reporting._\n".
		"━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
	),
    "low_event_count" => array(
        "message_template" => 
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n".
        "⚠️ *Low Event Count Detected on* {website_url}\n\n".
        "*Event Count Details:*\n".
        "• Minimum Expected: {min_events}\n".
        "• Actual Count: {actual_count}\n".
        "• API URL: {api_url}\n\n".
        "*1️⃣ Verify on the Website:*\n".
        "Click the link above and confirm whether the event count looks incomplete on the live site.\n\n".
        "*2️⃣ Check the UrVenue System:*\n".
        "Confirm if this venue has the expected number of upcoming events registered in the UrVenue backend for the requested date range.\n".
        "If the actual count is correct, you may need to adjust the minimum threshold in the website CMS settings.\n\n".
        "*3️⃣ Check the Venue Microsite:*\n".
        "If events exist in UrVenue but fewer are showing on the website, check the venue microsite.\n".
        "- If the microsite shows the same low count, notify the *UWS or backend team*.\n".
        "- If the microsite shows more events than the website, notify the *UWS team*.\n\n".
        "*Action Taken:*\n".
        "✓ Keeping previous cached data to prevent showing incomplete event listings.\n\n".
        "_Note: Always include the full website URL when reporting._\n".
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    ),
    "empty_events_response" => array(
        "message_template" => 
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n".
        "⚠️ *Empty Events Response on* {website_url}\n\n".
        "*API URL:* {api_url}\n\n".
        "*1️⃣ Verify on the Website:*\n".
        "Click the link above and confirm whether events are actually missing on the live site.\n\n".
        "*2️⃣ Check the UrVenue System:*\n".
        "Confirm if this venue has upcoming events registered in the UrVenue backend for the requested date range.\n".
        "If there are no events, you can ignore this alert or disable these notifications in the website CMS settings.\n\n".
        "*3️⃣ Check the Venue Microsite:*\n".
        "If events exist in UrVenue but not on the website, check the venue microsite.\n".
        "- If events are missing in *both*, notify the *UWS or backend team*.\n".
        "- If events show in the microsite but not on the website, notify the *UWS team*.\n\n".
        "*Action Taken:*\n".
        "✓ Keeping previous cached data to prevent empty event pages.\n\n".
        "_Note: Always include the full website URL when reporting._\n".
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    ),
    "empty_schedules_response" => array(
        "message_template" => 
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n".
        "⚠️ *Empty Schedules Detected on* {website_url}\n\n".
        "*API URL:* {api_url}\n\n".
        "*1️⃣ Verify on the Website:*\n".
        "Click the link above and confirm whether event schedules are actually missing on the live site.\n\n".
        "*2️⃣ Check the UrVenue System:*\n".
        "Confirm if events have schedules/dates assigned in the UrVenue backend for the requested date range.\n".
        "Events without schedules will not appear on the calendar.\n\n".
        "*3️⃣ Check the Venue Microsite:*\n".
        "If schedules exist in UrVenue but not on the website, check the venue microsite.\n".
        "- If schedules are missing in *both*, notify the *UWS or backend team*.\n".
        "- If schedules show in the microsite but not on the website, notify the *UWS team*.\n\n".
        "*Action Taken:*\n".
        "✓ Keeping previous cached data to prevent empty schedules.\n\n".
        "_Note: Always include the full website URL when reporting._\n".
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    ),
    "api_error" => array(
        "message_template" => 
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n".
        "🚨 *Events API Error on* {website_url}\n\n".
        "*API URL:* {api_url}\n\n".
        "*1️⃣ Verify on the Website:*\n".
        "Click the link above and confirm whether events are displaying correctly on the live site.\n".
        "The site may still show cached events from the last successful API call.\n\n".
        "*2️⃣ Check the UrVenue API Status:*\n".
        "Verify if the UrVenue API is functioning properly and accessible.\n".
        "Check API credentials and permissions are still valid.\n\n".
        "*3️⃣ Check the Venue Microsite:*\n".
        "If the microsite is also showing errors, notify the *UWS or backend team* immediately.\n".
        "If only the website has issues, notify the *UWS team*.\n\n".
        "*Action Taken:*\n".
        "✓ Keeping previous cached data to prevent error messages to visitors.\n\n".
        "_Note: This requires immediate attention. Always include the full website URL when reporting._\n".
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    ),
    "event_no_schedules" => array(
        "message_template" => 
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n".
        "⚠️ *Event Without Schedules on* {website_url}\n\n".
        "*Event ID:* {event_id}\n".
        "*Event Name:* {event_name}\n".
        "*API URL:* {api_url}\n\n".
        "*1️⃣ Verify on the Website:*\n".
        "Click the link above and search for the event by name to confirm if it's missing or incomplete.\n\n".
        "*2️⃣ Check the UrVenue System:*\n".
        "Look up the event ID in UrVenue backend and verify if schedules/dates are assigned.\n".
        "Events must have at least one schedule to display on the calendar.\n\n".
        "*3️⃣ Check the Venue Microsite:*\n".
        "If the event exists in UrVenue but has no schedules, check the venue microsite.\n".
        "- If the event is missing schedules in *both*, notify the *UWS or backend team*.\n".
        "- If schedules show in the microsite but not on the website, notify the *UWS team*.\n\n".
        "*Action Taken:*\n".
        "✓ Keeping previous cached data due to integrity validation failure.\n\n".
        "_Note: Always include the event ID and website URL when reporting._\n".
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    ),
    "schedules_integrity_failed" => array(
        "message_template" => 
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n".
        "🚨 *Incomplete Schedules Detected on* {website_url}\n\n".
        "*Date Range Details:*\n".
        "• From Date: {from_date}\n".
        "• To Date: {to_date}\n".
        "• Days Requested: {days_requested}\n".
        "• Expected Dates: {expected_dates}\n".
        "• Actual Dates Received: {actual_dates}\n".
        "• API URL: {api_url}\n\n".
        "*1️⃣ Verify on the Website:*\n".
        "Click the link above and check if the event calendar shows gaps or missing dates.\n\n".
        "*2️⃣ Check the UrVenue System:*\n".
        "Confirm if events exist for the full date range in the UrVenue backend.\n".
        "There may be missing events, unpublished events, or date range limitations.\n\n".
        "*3️⃣ Check the Venue Microsite:*\n".
        "If events exist in UrVenue but dates are missing on the website, check the venue microsite.\n".
        "- If the microsite also shows incomplete schedules, notify the *UWS or backend team*.\n".
        "- If the microsite shows complete schedules but the website doesn't, notify the *UWS team*.\n\n".
        "*Action Taken:*\n".
        "✓ Keeping previous cached data to prevent incomplete event listings.\n\n".
        "_Note: Always include the full website URL and date range when reporting._\n".
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    ),
    "default" => array(
        "message_template" => 
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n".
        "🚨 *Alert from* {website_url}\n\n".
        "*Issue:* Something went wrong. Please investigate.\n\n".
        "*Next Steps:*\n".
        "1️⃣ Verify the issue on the live website\n".
        "2️⃣ Check relevant settings in the CMS or UrVenue backend\n".
        "3️⃣ Contact the appropriate team if the issue persists\n\n".
        "_Note: Include the full website URL when reporting._\n".
        "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    ),
);

// $urvenue_ws_cleanchars_lib = array(
$urvenue_ws_cleanchars_lib = array('ě' => 'e', 'Ě' => 'E', 'š' => 's', 'Š' => 'S', 'č' => 'c', 'Č' => 'C', 'ř' => 'r', 'Ř' => 'R', 'ž' => 'z', 'Ž' => 'Z', 'ý' => 'y', 'Ý' => 'Y', 'á' => 'a', 'Á' => 'A', 'í' => 'i', 'Í' => 'I', 'é' => 'e', 'É' => 'E', 'ú' => 'u', 'ů' => 'u', 'Ů' => 'U', 'ď' => 'd', 'Ď' => 'D', 'ť' => 't', 'Ť' => 'T', 'ň' => 'n', 'Ň' => 'N', 'ü' => 'u');