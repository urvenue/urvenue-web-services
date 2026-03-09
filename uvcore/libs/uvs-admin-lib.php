<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$uws_core_version = "1.0.52";
$uvs_envicode = "api";
$uvs_adminbox_tabs = array("venues", "inventory", "status", "flyers", "map","events-event", "events-list", "events-global", "events-calendar", "events-agenda", "events-slider", "dashboard", "artists-artistpage", "artists-list", "pages", "api", "seo", "cache", "ui-color-palette", "notifications");

// $uvs_proxyurl = uvs_get_proxyurl();
$uvs_proxyurl = urvenue_ws_adm_get_proxyurl(); // Axl UWS-7416

$uvs_admin_lib = array(
	"loads" => array(
		"checkveaid" => $uvs_proxyurl . "?action=uvpx&uvaction=uvsp_veaidinfo",
		"adminsave" => $uvs_proxyurl . "?action=uvpx&uvaction=uvsp_adminsave",
		"checkapiconfig" => $uvs_proxyurl . "?action=uvpx&uvaction=uvsp_checkapiconfig",
	)
);

$uvs_flyertypes_lib = array("Flyer", "Action Shot", "Avatar", "Back of Flyer", "Background", "Caricature", "Crowd", "Guest", "Head Shot", "Header", "Item", "Logo", "Marquee Images", "Menu", "Profile Picture", "Promotional", "Secondary Flyer", "Social", "View", "Visit");

$uvs_flyersratios_lib = array("Vertical", "Square", "Horizontal", "Banner");

$uvs_admin_feeds = array(
	"venueinfo" => "https://uvtix.com/api/v3/{params}/venues.json",
	"microsite" => "https://{envicode}.urvenue.me/v1/microsite/user/json/?apikey={apikey}&sourcecode={sourcecode}&sourceloc={sourceloc}&{params}",
);

$uvs_cleanchars = array('ě' => 'e', 'Ě' => 'E', 'š' => 's', 'Š' => 'S', 'č' => 'c', 'Č' => 'C', 'ř' => 'r', 'Ř' => 'R', 'ž' => 'z', 'Ž' => 'Z', 'ý' => 'y', 'Ý' => 'Y', 'á' => 'a', 'Á' => 'A', 'í' => 'i', 'Í' => 'I', 'é' => 'e', 'É' => 'E', 'ú' => 'u', 'ů' => 'u', 'Ů' => 'U', 'ď' => 'd', 'Ď' => 'D', 'ť' => 't', 'Ť' => 'T', 'ň' => 'n', 'Ň' => 'N', 'ü' => 'u');

$uvs_admin_fields = array(
	"events->global-source" => array(
		"type" => "select",
		"name" => "events[global-source]",
		"values" => array(
			"primary" => array(
				"label" => "Primary Venue",
				"value" => "primary"
			),
			"all" => array(
				"label" => "All Registered Venues",
				"value" => "all",
			)
		)
	),
	"events->global-addvenuename" => array(
		"type" => "switchui",
		"name" => "events[global-addvenuename]",
	),
	"events->global-nmonths" => array(
		"type" => "select",
		"name" => "events[global-nmonths]",
		"values" => array(1, 2, 3, 4),
	),
	"events->global-hidenoflyer" => array(
		"type" => "switchui",
		"name" => "events[global-hidenoflyer]",
	),
	"events->global-initaldate" => array(
		"type" => "text",
		"name" => "events[global-initaldate]",
		"addclass" => "uvsjs-datepicker",
	),
	"events->global-addperformerfilter" => array(
		"type" => "switchui",
		"name" => "events[global-addperformerfilter]",
	),
	"events->global-updateurl" => array(
		"type" => "switchui",
		"name" => "events[global-updateurl]",
	),
	"events->global-defaulteventurl" => array(
		"type" => "select",
		"name" => "events[global-defaulteventurl]",
		"values" => array(
			array(
				"label" => "Event Page",
				"value" => "event",
			),
			array(
				"label" => "Map Page",
				"value" => "map",
			),
		)
	),
	"events->eventspage-addvenuefilter" => array(
		"type" => "switchui",
		"name" => "events[eventspage-addvenuefilter]",
	),
	"events->eventspage-viewmenu" => array(
		"type" => "select",
		"name" => "events[eventspage-viewmenu]",
		"values" => array(
			array(
				"label" => "Icons + text",
				"value" => "icons+text",
			),
			array(
				"label" => "Icons",
				"value" => "icons",
			),
			array(
				"label" => "Text",
				"value" => "text",
			)
		)
	),
	"events->eventspage-dateselector" => array(
		"type" => "select",
		"name" => "events[eventspage-dateselector]",
		"values" => array(
			array(
				"label" => "Datepicker Date",
				"value" => "datepicker-date",
			),
			array(
				"label" => "Datepicker Range",
				"value" => "datepicker-range",
			),
			array(
				"label" => "Month Dropdown",
				"value" => "month-dropdown",
			),
			array(
				"label" => "Month Arrows",
				"value" => "month-arrows",
			),
		)
	),
	"events->eventspage-monthsrange" => array(
		"type" => "select",
		"name" => "events[eventspage-monthsrange]",
		"values" => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12),
	),
	"events->addon-bottles->showdigitalmenu" => array(
		"type" => "switchui",
		"name" => "events[addon-bottles][showdigitalmenu]",
	),
	"events->addon-bottles->showsummary" => array(
		"type" => "switchui",
		"name" => "events[addon-bottles][showsummary]",
	),
	"events->addon-bottles->menuapikey" => array(
		"type" => "text",
		"name" => "events[addon-bottles][menuapikey]",
	),
	"map->mappage-views" => array(
		"type" => "select",
		"name" => "map[mappage-views]",
		"values" => array(
			array(
				"label" => "List",
				"value" => "list",
			),
			array(
				"label" => "Map",
				"value" => "map",
			)
		)
	),
	"map->mappage-addadmissionopt" => array(
		"type" => "switchui",
		"name" => "map[mappage-addadmissionopt]",
	),
	"map->mappage-showecomaps" => array(
		"type" => "switchui",
		"name" => "map[mappage-showecomaps]",
	),
	"flyers->placeholderurl" => array(
		"type" => "text",
		"name" => "flyers[placeholderurl]",
	),
	"flyers->eventpage-hideifnomatch" => array(
		"type" => "switchui",
		"name" => "flyers[eventpage-hideifnomatch]",
	),
	"flyers->eventpage-useplaceholder" => array(
		"type" => "switchui",
		"name" => "flyers[eventpage-useplaceholder]",
	),
	"flyers->eventpage-sizecode" => array(
		"type" => "text",
		"name" => "flyers[eventpage-sizecode]",
	),
	"flyers->eventpage-placeholderurl" => array(
		"type" => "text",
		"name" => "flyers[eventpage-placeholderurl]",
	),
	"flyers->calendar-hideifnomatch" => array(
		"type" => "switchui",
		"name" => "flyers[calendar-hideifnomatch]",
	),
	"flyers->calendar-useplaceholder" => array(
		"type" => "switchui",
		"name" => "flyers[calendar-useplaceholder]",
	),
	"flyers->calendar-sizecode" => array(
		"type" => "text",
		"name" => "flyers[calendar-sizecode]",
	),
	"flyers->calendar-placeholderurl" => array(
		"type" => "text",
		"name" => "flyers[calendar-placeholderurl]",
	),
	"flyers->list-hideifnomatch" => array(
		"type" => "switchui",
		"name" => "flyers[list-hideifnomatch]",
	),
	"flyers->list-useplaceholder" => array(
		"type" => "switchui",
		"name" => "flyers[list-useplaceholder]",
	),
	"flyers->list-sizecode" => array(
		"type" => "text",
		"name" => "flyers[list-sizecode]",
	),
	"flyers->list-placeholderurl" => array(
		"type" => "text",
		"name" => "flyers[list-placeholderurl]",
	),
	"flyers->slider-hideifnomatch" => array(
		"type" => "switchui",
		"name" => "flyers[slider-hideifnomatch]",
	),
	"flyers->slider-useplaceholder" => array(
		"type" => "switchui",
		"name" => "flyers[slider-useplaceholder]",
	),
	"flyers->slider-sizecode" => array(
		"type" => "text",
		"name" => "flyers[slider-sizecode]",
	),
	"flyers->slider-placeholderurl" => array(
		"type" => "text",
		"name" => "flyers[slider-placeholderurl]",
	),
	"flyers->slidermobile-hideifnomatch" => array(
		"type" => "switchui",
		"name" => "flyers[slidermobile-hideifnomatch]",
	),
	"flyers->slidermobile-useplaceholder" => array(
		"type" => "switchui",
		"name" => "flyers[slidermobile-useplaceholder]",
	),
	"flyers->slidermobile-sizecode" => array(
		"type" => "text",
		"name" => "flyers[slidermobile-sizecode]",
	),
	"flyers->slidermobile-placeholderurl" => array(
		"type" => "text",
		"name" => "flyers[slidermobile-placeholderurl]",
	),
	"flyers->share-hideifnomatch" => array(
		"type" => "switchui",
		"name" => "flyers[share-hideifnomatch]",
	),
	"flyers->share-useplaceholder" => array(
		"type" => "switchui",
		"name" => "flyers[share-useplaceholder]",
	),
	"flyers->share-sizecode" => array(
		"type" => "text",
		"name" => "flyers[share-sizecode]",
	),
	"flyers->share-placeholderurl" => array(
		"type" => "text",
		"name" => "flyers[share-placeholderurl]",
	),
	"flyers->custom1-hideifnomatch" => array(
		"type" => "switchui",
		"name" => "flyers[custom1-hideifnomatch]",
	),
	"flyers->custom1-useplaceholder" => array(
		"type" => "switchui",
		"name" => "flyers[custom1-useplaceholder]",
	),
	"flyers->custom1-sizecode" => array(
		"type" => "text",
		"name" => "flyers[custom1-sizecode]",
	),
	"flyers->custom1-placeholderurl" => array(
		"type" => "text",
		"name" => "flyers[custom1-placeholderurl]",
	),
	"flyers->custom2-hideifnomatch" => array(
		"type" => "switchui",
		"name" => "flyers[custom2-hideifnomatch]",
	),
	"flyers->custom2-useplaceholder" => array(
		"type" => "switchui",
		"name" => "flyers[custom2-useplaceholder]",
	),
	"flyers->custom2-sizecode" => array(
		"type" => "text",
		"name" => "flyers[custom2-sizecode]",
	),
	"flyers->custom2-placeholderurl" => array(
		"type" => "text",
		"name" => "flyers[custom2-placeholderurl]",
	),
	/*"events->calendar-nmonths" => array(
		"type" => "select",
		"name" => "events[calendar-nmonths]",
		"values" => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12),
	),*/
	/*"events->calendar-addlist" => array(
		"type" => "switchui",
		"name" => "events[calendar-addlist]",
		"addclass" => "uvsjs-controlfieldview",
		"addattrs" => "data-target='.uvs-fieldcallistrel' data-showon='1' data-hideon='0'",
	),*/
	/*"events->calendar-initialview" => array(
		"type" => "select",
		"name" => "events[calendar-initialview]",
		"values" => array(
			array(
				"label" => "Calendar",
				"value" => "calendar",
			),
			array(
				"label" => "List",
				"value" => "list",
			)
		)
	),*/
	/*"events->calendar-onlyoneevent" => array(
		"type" => "switchui",
		"name" => "events[calendar-onlyoneevent]",
	),*/
	"events->calendar-alwayslist" => array(
		"type" => "switchui",
		"name" => "events[calendar-alwayslist]",
	),
	/*"events->calendar-viewmenu" => array(
		"type" => "select",
		"name" => "events[calendar-viewmenu]",
		"values" => array(
			array(
				"label" => "Icons + text",
				"value" => "icons+text",
			),
			array(
				"label" => "Icons",
				"value" => "icons",
			),
			array(
				"label" => "Text",
				"value" => "text",
			)
		)
	),*/
	/*"events->calendar-monthseltype" => array(
		"type" => "select",
		"name" => "events[calendar-monthseltype]",
		"values" => array(
			array(
				"label" => "Dropdown",
				"value" => "dropdown",
			),
			array(
				"label" => "Arrows",
				"value" => "arrows",
			)
		)
	),*/
	"events->agenda-columns" => array(
		"type" => "select",
		"name" => "events[agenda-columns]",
		"values" => array(3, 4, 5),
	),
	/*"events->list-listtype" => array(
		"type" => "select",
		"name" => "events[list-listtype]",
		"values" => array(
			array(
				"label" => "Rows",
				"value" => "rows",
			),
			array(
				"label" => "Grid",
				"value" => "grid",
			)
		)
	),
	"events->list-maxevents" => array(
		"type" => "number",
		"name" => "events[list-maxevents]",
	),*/
	"events->slider-showarrows" => array(
		"type" => "switchui",
		"name" => "events[slider-showarrows]",
	),
	"events->slider-showdots" => array(
		"type" => "switchui",
		"name" => "events[slider-showdots]",
	),
	"events->slider-animation" => array(
		"type" => "select",
		"name" => "events[slider-animation]",
		"values" => array(
			array(
				"label" => "Slide",
				"value" => "slide",
			),
			array(
				"label" => "FadeIn",
				"value" => "fadein",
			)
		)
	),
	"events->slider-maxevents" => array(
		"type" => "number",
		"name" => "events[slider-maxevents]",
	),
	/*"events->event-url" => array(
		"type" => "text",
		"name" => "events[event-url]",
	),*/
	/*"events->event-showartist" => array(
		"type" => "switchui",
		"name" => "events[event-showartist]",
	),*/
	"events->event-layout" => array(
		"type" => "select",
		"name" => "events[event-layout]",
		"values" => array(
			array(
				"label" => "Container: Title + Collapsable Tabs",
				"value" => "container",
			),
			array(
				"label" => "Full: Header + Horizontal Menu Tabs",
				"value" => "full-header",
			),
		)
	),
	"events->event-columns" => array(
		"type" => "select",
		"name" => "events[event-columns]",
		"values" => array(
			array(
				"label" => "Inventory(left) + Flyer(right)",
				"value" => "inventory-flyer",
			),
			array(
				"label" => "Flyer(left) + Inventory(right)",
				"value" => "flyer-inventory",
			),
		)
	),
	"events->event-activedropdowns" => array(
		"type" => "switchui",
		"name" => "events[event-activedropdowns]",
	),
	"artists->artist-url" => array(
		"type" => "text",
		"name" => "artists[artist-url]",
	),
	"artists->artist-imagetype" => array(
		"type" => "select",
		"name" => "artists[artist-imagetype]",
		"values" => $uvs_flyertypes_lib,
	),
	"artists->artist-imageratio" => array(
		"type" => "select",
		"name" => "artists[artist-imageratio]",
		"values" => $uvs_flyersratios_lib,
	),
	"artists->artist-listview" => array(
		"type" => "select",
		"name" => "artists[artist-listview]",
		"values" => array(
			array(
				"label" => "Squares",
				"value" => "squares",
			),
			array(
				"label" => "Mosaic",
				"value" => "mosaic",
			)
		)
	),
	"artists->artist-buttonlabel" => array(
		"type" => "text",
		"name" => "artists[artist-buttonlabel]",
	),
	"system->sourcecode" => array(
		"type" => "text",
		"name" => "system[sourcecode]",
	),
	"system->sourceloc" => array(
		"type" => "text",
		"name" => "system[sourceloc]",
	),
	"system->apikey" => array(
		"type" => "text",
		"name" => "system[apikey]",
		"id" => "uvinputapikey",
	),
	"system->microcode" => array(
		"type" => "text",
		"name" => "system[microcode]",
		"id" => "uvinputmicrocode",
	),
	"system->use-staging" => array(
		"type" => "switchui",
		"name" => "system[use-staging]",
	),
	"cache->wpeinst" => array(
		"type" => "text",
		"name" => "cache[wpeinst]",
		"id" => "uvinputwpeinst",
	),
	"cache->username" => array(
		"type" => "text",
		"name" => "cache[username]",
		"id" => "uvinputusername",
	),
	"cache->password" => array(
		"type" => "password",
		"name" => "cache[password]",
		"id" => "uvinputpassword",
	),
	"cache->apikey" => array(
		"type" => "text",
		"name" => "cache[cacheapikey]",
		"id" => "uvinputcachecacheapikey",
		"addattrs" => "readonly",
		"addclass" => "uvsjs-copyfield uvsread",
	),
	"cache->endpoint" => array(
		"type" => "text",
		"name" => "cache[endpoint]",
		"id" => "uvinputcacheendpoint",
		"addattrs" => "readonly",
		"addclass" => "uvsjs-copyfield uvsread",
	),
	"pages->events" => array(
		"type" => "page",
		"name" => "pages[events]",
	),
	"pages->singleevent" => array(
		"type" => "page",
		"name" => "pages[singleevent]",
	),
	"pages->map" => array(
		"type" => "page",
		"name" => "pages[map]",
	),
	"pages->itempage" => array(
		"type" => "page",
		"name" => "pages[itempage]",
	),
	"pages->privacy" => array(
		"type" => "page",
		"name" => "pages[privacy]",
	),
	"pages->terms" => array(
		"type" => "page",
		"name" => "pages[terms]",
	),
	"inventory->manageentlock" => array(
		"type" => "switchui",
		"name" => "inventory[manageentlock]",
	),
	"inventory->showiteminfoinline" => array(
		"type" => "switchui",
		"name" => "inventory[showiteminfoinline]",
	),
	"ui->uitheme" => array(
		"type" => "select",
		"name" => "ui[uitheme]",
		"values" => array(
			array(
				"label" => "Light",
				"value" => "light",
			),
			array(
				"label" => "Dark",
				"value" => "dark",
			)
		)
	),
	"ui->primarycolor" => array(
		"type" => "colorpicker",
		"name" => "ui[primarycolor]",
	),
	"ui->secondarycolor" => array(
		"type" => "colorpicker",
		"name" => "ui[secondarycolor]",
	),
	"ui->accentcolor" => array(
		"type" => "colorpicker",
		"name" => "ui[accentcolor]",
		"addclass" => "uvsjs-choosecolor",
	),
	"ui->uipoptheme" => array(
		"type" => "select",
		"name" => "ui[uipoptheme]",
		"values" => array(
			array(
				"label" => "Light",
				"value" => "light",
			),
			array(
				"label" => "Dark",
				"value" => "dark",
			)
		)
	),
	"ui->popaccentcolor" => array(
		"type" => "colorpicker",
		"name" => "ui[popaccentcolor]",
		"addclass" => "uvsjs-choosecolor",
	),
	"seo->enabledata" => array(
		"type" => "switchui",
		"name" => "seo[enabledata]",
	),
	"seo->enabletags" => array(
		"type" => "switchui",
		"name" => "seo[enabletags]",
	),
	"seo->seotitle" => array(
		"type" => "text",
		"name" => "seo[seotitle]",
	),
	"seo->seotakeapidescr" => array(
		"type" => "switchui",
		"name" => "seo[seotakeapidescr]",
	),
	"seo->seodescription" => array(
		"type" => "text",
		"name" => "seo[seodescription]",
	),
	"notifications->enable" => array(
		"type" => "switchui",
		"name" => "notifications[enable]",
	),
	"notifications->webhook" => array(
		"type" => "text",
		"name" => "notifications[webhook]",
	),
	"notifications->minevents" => array(
		"type" => "number",
		"name" => "notifications[minevents]",
	),
);