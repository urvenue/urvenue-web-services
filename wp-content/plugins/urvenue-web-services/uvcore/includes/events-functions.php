<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*Get Calendar implementation
    Returns: Prints html controls + events integration
*/
function urvenue_ws_events($uvargs = ""){
    global $urvenue_ws_core_lib, $urvenue_ws_today;

    $uvdate = urvenue_ws_get_arg($uvargs, "date", "");
    $uvenddate = urvenue_ws_get_arg($uvargs, "enddate", "");
    $uvbuttonlabel = urvenue_ws_get_arg($uvargs, "buttonlabel");
    $uvmaxdate = urvenue_ws_get_events_max_date("Y-m-d");
    $uvuitheme = urvenue_ws_get_theme();

    if($uvdate){
        $uvinitialdate = $uvdate;
        $uvenddate = (!$uvenddate) ? urvenue_ws_get_events_endinit_date("Y-m-d", $uvdate) : $uvenddate;
    }
    else{
        $uvinitialdate = urvenue_ws_get_events_initial_date("Y-m-d");
        $uvenddate = (!$uvenddate) ? urvenue_ws_get_events_endinit_date("Y-m-d") : $uvenddate;
    }
    
    $uvinitialdate = ($uvinitialdate and $uvinitialdate < $uvmaxdate) ? $uvinitialdate : $uvmaxdate;
    $uvenddate = ($uvenddate and $uvenddate < $uvmaxdate) ? $uvenddate : $uvmaxdate;

    if($uvenddate and is_array($uvargs))
        $uvargs["todate"] = $uvenddate;

    $uvnextloaddate = gmdate("Y-m-d", strtotime($uvenddate . " +1 day"));

    $uveventsactions = urvenue_ws_events_controls($uvargs);
    $uveventsviews = urvenue_ws_events_views($uvargs);

    $uvactionsclass = ($uvmaxdate > $uvenddate) ? "uwsactive" : "";
    $uvvenuekeycodes = urvenue_ws_get_arg($uvargs, "venue", "all");
    $uvvenuefiltercodes = urvenue_ws_get_arg($uvargs, "venuecodes", $uvvenuekeycodes);
    $uvupdateurl = (isset($urvenue_ws_core_lib["events"]["global-updateurl"]) and $urvenue_ws_core_lib["events"]["global-updateurl"]) ? 1 : 0;

    $uvaddattr = "";
    if(urvenue_ws_get_arg($uvargs, "performer", ""))
        $uvaddattr .= " data-initperfomer='" . esc_attr( urvenue_ws_get_arg($uvargs, "performer", "") ) . "'";

    //Enddate only for range datepicker
    $uvdataenddate = ($urvenue_ws_core_lib["events"]["eventspage-dateselector"] == "datepicker-range") ? $uvenddate : "";

    $uvuitheme_esc          = esc_attr( $uvuitheme );
    $uvinitialdate_esc      = esc_attr( $uvinitialdate );
    $uvdataenddate_esc      = esc_attr( $uvdataenddate );
    $uvmaxdate_esc          = esc_attr( $uvmaxdate );
    $uvvenuefiltercodes_esc = esc_attr( $uvvenuefiltercodes );
    $uvupdateurl_esc        = intval( $uvupdateurl );
    $uvbuttonlabel_esc      = esc_attr( $uvbuttonlabel );
    $uvactionsclass_esc     = esc_attr( $uvactionsclass );
    $uvnextloaddate_esc     = esc_attr( $uvnextloaddate );

    $uveventshtml = "
    <div class='uws-integration uws-events {$uvuitheme_esc}' data-filter-date='{$uvinitialdate_esc}' data-filter-enddate='{$uvdataenddate_esc}' data-filter-maxdate='{$uvmaxdate_esc}' data-filter-venue='{$uvvenuefiltercodes_esc}' data-update-url='{$uvupdateurl_esc}' data-buttonlabel='{$uvbuttonlabel_esc}' {$uvaddattr}>
        {$uveventsactions}
        <div class='uws-events-views'>{$uveventsviews}</div>
        <div class='uws-events-actions {$uvactionsclass_esc}'>
            <button class='uws-btn uws-btn-s uwsjs-events-loadmore' data-load-date='{$uvnextloaddate_esc}'>Load More</button>
            <div class='uws-events-loadmoremsg'>No More Events To Show</div>
            <div class='uws-loader-uvicon'></div>
        </div>
    </div>
    ";

    echo wp_kses_post( $uveventshtml );
}

/*Get events views
    Optional: returnarray(set to true to return the html for each view in an array)
*/
function urvenue_ws_events_views($uvargs = "", $uvreturnarray = false)
{
    global $urvenue_ws_core_lib, $urvenue_ws_assetsversion;

    $uvdate = urvenue_ws_get_arg($uvargs, "date", "");

    if ($uvdate) {
        $uvfromdate = urvenue_ws_get_arg($uvargs, "fromdate", $uvdate);
        $uvmaxdate = urvenue_ws_get_arg($uvargs, "todate", urvenue_ws_get_events_endinit_date("Y-m-d", $uvdate));
    } else {
        $uvfromdate = urvenue_ws_get_arg($uvargs, "fromdate", urvenue_ws_get_events_initial_date("Y-m-d"));
        $uvmaxdate = urvenue_ws_get_arg($uvargs, "todate", urvenue_ws_get_events_endinit_date("Y-m-d", $uvfromdate));
    }

    $uvnowrap = urvenue_ws_get_arg($uvargs, "nowrap", "");
    $uvtodate = gmdate("Y-m-d", strtotime($uvmaxdate . " +7 days"));

    //todate = end date to call on the API
    //maxdate = maximum date to show on the events list
    if (is_array($uvargs)) {
        $uvargs["todate"] = $uvtodate;
        $uvargs["maxdate"] = $uvmaxdate;
    } else {
        $uvargs = array(
            "todate" => $uvtodate,
            "maxdate" => $uvmaxdate,
        );
    }

    $uvevents = urvenue_ws_get_events($uvargs);

    $uvviewshtml = $uvviewsreturn = $uveventsschemainline = "";
    $uvviewsarray = array();
    $uvviews = (isset($urvenue_ws_core_lib["events"])) ? $urvenue_ws_core_lib["events"]["eventspage-views"] : "";
    $uvdefaultview = urvenue_ws_get_arg($uvargs, "defaultview", "");

    // Process views parameter if provided (for consistent behavior across endpoints)
    $uvviewsparams = urvenue_ws_get_arg($uvargs, "views", "");
    if ($uvviewsparams && is_array($uvviews)) {
        $uvdefaultviews = ['calendar', 'agenda', 'list'];
        $uvviewsarray_param = is_array($uvviewsparams) ? $uvviewsparams : explode(",", $uvviewsparams);
        
        // Add defaultview to views array if it's not already included and is a valid default view
        if ($uvdefaultview && !in_array($uvdefaultview, $uvviewsarray_param) && 
            in_array($uvdefaultview, $uvdefaultviews) && isset($uvviews[$uvdefaultview])) {
            $uvviewsarray_param[] = $uvdefaultview;
        }
        
        // Hide all views by default
        foreach ($uvviews as $uvviewkey => $uvviewdata) {
            $uvviews[$uvviewkey]['show'] = 0;
            $uvviews[$uvviewkey]['defaultview'] = 0;
        }
        
        // Show requested views
        foreach ($uvviewsarray_param as $view) {
            if (isset($uvviews[$view])) {
                $uvviews[$view]['show'] = 1;
            }
        }

        // Set default view (priority: custom override > calendar > agenda > list)
        $uvselviews = array_intersect($uvdefaultviews, $uvviewsarray_param);
        $uvcustdefaultview = $uvdefaultview && in_array($uvdefaultview, $uvviewsarray_param) && isset($uvviews[$uvdefaultview])
            ? $uvdefaultview
            : reset($uvselviews);
            
        if ($uvcustdefaultview) {
            $uvviews = urvenue_ws_set_default_view($uvviews, $uvcustdefaultview);
        }
    }

    if (is_array($uvviews)) {

        if ($uvdefaultview) {
            $uvviews = urvenue_ws_set_default_view($uvviews, $uvdefaultview);
        } elseif (isset($uvargs["view"]) && $uvargs["view"] != "") {
            $customView = $uvargs["view"];
            foreach ($uvviews as $key => &$singleview) {
                if (isset($singleview['defaultview'])) {
                    $singleview['defaultview'] = (strtolower($key) === strtolower($customView)) ? 1 : 0;
                }
            }
        }

        $uvviewsmenu = "<ul>";

        foreach ($uvviews as $uvviewkey => $uvview) {
            if ($uvview["show"]) {
                $uvviewclass = ($uvview["defaultview"]) ? "uvsactive" : "";
                $uvviewhtml = "";

                if ($uvviewkey == "calendar") {
                    if ($uvreturnarray) {
                        $uvviewsarray[$uvviewkey] = urvenue_ws_calendar($uvevents, $uvargs);
                    } else {
                        $uvcalcellwidth_script = 'const uvcalcellwidth = document.querySelector(".uws-events-views").offsetWidth / 7; document.documentElement.style.setProperty("--uws-cal-cell-minheight", uvcalcellwidth + "px");';
                        
                        wp_register_script('urvenue-ws-calcellwidth', false, array(), $urvenue_ws_assetsversion, true);
                        wp_enqueue_script('urvenue-ws-calcellwidth');
                        wp_add_inline_script('urvenue-ws-calcellwidth', "(function () { {$uvcalcellwidth_script} })();"); //add css var to add min height to cells

                        $uvdaysnames = "<div class='uwscaldaysnames'><div>" . urvenue_ws_lang("cal-monday") . "</div><div>" . urvenue_ws_lang("cal-tuesday") . "</div><div>" . urvenue_ws_lang("cal-wednesday") . "</div><div>" . urvenue_ws_lang("cal-thursday") . "</div><div>" . urvenue_ws_lang("cal-friday") . "</div><div>" . urvenue_ws_lang("cal-saturday") . "</div><div>" . urvenue_ws_lang("cal-sunday") . "</div></div>";
                        $uvviewhtml .= $uvdaysnames . "<div class='uws-events-calendar'>" . urvenue_ws_calendar($uvevents, $uvargs) . "</div>";
                    }
                } else {
                    $uvlistargs = array(
                        "wrap-template" => "events/events-$uvviewkey-wrap-default",
                        "item-template" => "events/events-$uvviewkey-item-default",
                        "maxdate" => $uvmaxdate,
                    );

                    if ($uvnowrap)
                        $uvlistargs["wrap-template"] = "{eventslist}";

                    if($uvbuttonlabel = urvenue_ws_get_arg($uvargs, "buttonlabel"))
                        $uvlistargs["buttonlabel"] = $uvbuttonlabel;
                    
                    $uvthisviewhtml = urvenue_ws_get_events_list($uvevents, $uvlistargs);

                    if ($uvreturnarray) {
                        $uvviewsarray[$uvviewkey] = $uvthisviewhtml;
                    } else {
                        $uvviewhtml .= $uvthisviewhtml;
                    }
                }

                //Add number of columns class for agenda view
                if ($uvviewkey == "agenda") {
                    $uvviewclass .= " uws-agenda-cols-" . $urvenue_ws_core_lib["events"]["agenda-columns"];
                }

                $uvviewshtml .= "<div class='uws-events-view uws-events-view-$uvviewkey $uvviewclass' data-viewkey='$uvviewkey'>$uvviewhtml<div class='uws-nocontent'>There are no events to show</div></div>";
            }
        }
    }

    // SEO Schema
    $uveventsseo = (is_array($uvevents)) ? array_slice($uvevents, 0, 10) : "";
    $uveventsschema = (is_array($uveventsseo)) ? urvenue_ws_get_events_schema($uveventsseo) : "";

    if ($uveventsschema) {
        $uveventsschemajson = wp_json_encode($uveventsschema);
        $uveventsschemainline .= "";
        $uvviewshtml .= $uveventsschemainline;

        add_action('wp_footer', function () use ($uveventsschemajson) {
            wp_print_inline_script_tag( $uveventsschemajson, array( 'type' => 'application/ld+json' ) );
        });
    }

    if ($uvreturnarray) {
        $uvviewsarray["todate"] = $uvmaxdate;
        $uvviewsarray["nevents"] = (is_array($uvevents)) ? count($uvevents) : 0;
        $uvviewsreturn = $uvviewsarray;
    } else
        $uvviewsreturn = $uvviewshtml;

    return $uvviewsreturn;
}

/**
 * Sets the default view for the views array
 * @param array $uvviews The views array to modify
 * @param string $uvdefaultview The view key to set as default
 * @return array The modified views array
 */
function urvenue_ws_set_default_view($uvviews, $uvdefaultview) {
    if (!is_array($uvviews) || !$uvdefaultview) {
        return $uvviews; // Return original if not an array or no default view provided
    }
    
    foreach ($uvviews as $uvviewkey => $uvview) {
        $uvviews[$uvviewkey]["defaultview"] = ($uvviewkey == $uvdefaultview) ? 1 : 0;
    }
    return $uvviews;
}

/*Get list of events on html based on template
    Requires: events(array with urvenue events), args(array with args)
    Args: wrap-template(template key or html for wrap), item-template(template key or html for item)
    Returns: HTML with events
*/
function urvenue_ws_get_events_list($uvevents, $uvargs = ""){
    $uveventslist = "";

    $uvmaxdate = urvenue_ws_get_arg($uvargs, "maxdate", "");

    if(is_array($uvevents) and count($uvevents)){
        $uvwraptemplatecode = urvenue_ws_get_arg($uvargs, "wrap-template", "events/events-wrap-default");
        $uvitemtemplatecode = urvenue_ws_get_arg($uvargs, "item-template", "events/events-item-default"); 
        $uvnevents = urvenue_ws_get_arg($uvargs, "nevents", 200);

        $uvwraptemplate = ($uvwraptemplatecode) ? urvenue_ws_get_template($uvwraptemplatecode) : "";
        $uvitemtemplate = urvenue_ws_get_template($uvitemtemplatecode);
        $uveventslistitems = "";

        $uvbtnlabel = urvenue_ws_get_arg($uvargs, "buttonlabel", "");
        $uvbtnspanlabel = urvenue_ws_get_arg($uvargs, "buttonspanlabel", "");
        
        $uvbtnspanlabel = (!$uvbtnspanlabel and !$uvbtnlabel) ? "Book<span> Now</span>" : $uvbtnspanlabel;
        $uvbtnspanlabel = (!$uvbtnspanlabel and $uvbtnlabel) ? $uvbtnlabel : "Book<span> Now</span>";
        $uvbtnlabel = ($uvbtnlabel) ? $uvbtnlabel : "Book Now";

        $uvitemtemplate = str_replace(
            array(
                "{buttonlabel}",
                "{buttonspanlabel}",
            ),
            array(
                $uvbtnlabel,
                $uvbtnspanlabel,
            ),
            $uvitemtemplate
        );

        foreach($uvevents as $uvevent){
            $uveventcode = $uvevent["eventcode"];
            $uvmaineventcode = $uvevent["maineventcode"];

            if($uveventcode === $uvmaineventcode) {
                if($uvevent["date"] <= $uvmaxdate or !$uvmaxdate and $uvnevents){
                    $uveventitem = urvenue_ws_replace_event_vars($uvevent, $uvitemtemplate);
                    $uveventslistitems .= $uveventitem;

                    $uvnevents--;
                }
            }
        }

        if($uvwraptemplate)
            $uveventslist = str_replace(
                array(
                    "{eventslist}"
                ),
                array(
                    $uveventslistitems
                ),
                $uvwraptemplate
            );
        else
            $uveventslist = $uveventslistitems;
    }

    return $uveventslist;
}

/*Replate events variables codes
    event(event array), template(html template with varriable codes)
*/
function urvenue_ws_replace_event_vars($uvevent, $uvtemplate){
    global $urvenue_ws_core_lib, $urvenue_ws_config_cal_add_ecozone_data, $urvenue_ws_config_cal_show_flyers, $urvenue_ws_config_event_on_newtab;

    $uvvenuekey = $uvevent["venuekey"];
    if(isset($urvenue_ws_core_lib["venues"]["$uvvenuekey"]["venueforcealias"]) and $urvenue_ws_core_lib["venues"]["$uvvenuekey"]["venueforcealias"]){
        $uvvenuealias=$urvenue_ws_core_lib["venues"]["$uvvenuekey"]["venuealias"];
        $uvvenuename=($uvvenuealias) ? $uvvenuealias:$uvevent["venuename"];
    }
    else{
        $uvvenuename=$uvevent["venuename"];
    }
    $uvvenuealias = (isset($uvvenuealias) and $uvvenuealias) ? $uvvenuealias:$uvevent["venuename"];
    // $uvvenuealias = (isset($urvenue_ws_core_lib["venues"]["$uvvenuekey"]["venueforcealias"]) and $urvenue_ws_core_lib["venues"]["$uvvenuekey"]["venueforcealias"] and $urvenue_ws_core_lib["venues"]["$uvvenuekey"]["venuealias"]) ? $urvenue_ws_core_lib["venues"]["$uvvenuekey"]["venuealias"] : $uvevent["venuename"];
    $uvperformersclass = "";
    if(isset($uvevent["performers"]) and $uvevent["performers"]){
        $uvperformersclass = urvenue_ws_get_performersclass($uvevent["performers"]);
    }
    $uvevtdescrdefault = "";
    //$uvevtdescrdefault = "See " . $uvevent["name"] . " at " . $uvevent["venuename"] . " on ".date("l, F d, Y", strtotime($uvevent["date"]))."<br>";

    $uvevent["descr"] = ($uvevent["descr"]) ? $uvevent["descr"] : $uvevtdescrdefault;

    $uveventitem = "";
    // $uvddate = date($urvenue_ws_core_lib["events"]["global-dateformat"], strtotime($uvevent["date"]));
    $uvddate = gmdate($urvenue_ws_core_lib["events"]["global-dateformat"], strtotime($uvevent["date"]));
    $uvvenuediv = ($urvenue_ws_core_lib["events"]["global-addvenuename"]) ? "<div class='uwsvenuename uws-venkey-{venuekey}'>" . $uvvenuename . "</div>" : "";
    $uveventinfodiv = ($uvevent["descr"]) ? "<div class='uwseventdescr'><div class='uwstitle'>Event Info</div><div class='uwsdescr'>" . $uvevent["descr"] . "</div></div>" : "";
    $uveventdescrtoggle = ($uvevent["descr"] && strlen($uvevent["descr"]) > 250) ? "<button class='uwsjs-descr-toggle uwsreadmore' aria-label='" . urvenue_ws_lang('read-more') . "'>" . urvenue_ws_lang('read-more') . "</button><button class='uwsjs-descr-toggle uwsreadless' aria-label='" . urvenue_ws_lang('read-less') . "'>" . urvenue_ws_lang('read-less') . "</button>" : "";
    $uveventdescrdiv = ($uvevent["descr"]) ? "<div class='uws-event-description'><div class='uwseventdescr'><div class='uwsdescr uwsclamptext uwsclamp-3'>" . $uvevent["descr"] . "$uveventdescrtoggle</div></div></div>" : "";
    $uvdstarttime = ($uvevent["dstarttime"]) ? $uvevent["dstarttime"] : "";
    $uvdstarttimediv = ($uvdstarttime) ? "<div class='uwsdtime'>" . $uvdstarttime . "</div>" : "";
    $uvddoorsopen = ($uvevent["ndoorsopen"]) ? substr($uvevent["ndoorsopen"], 1,4) : "";
    // $uvddoorsopen = ($uvddoorsopen) ? date("g:i A", strtotime($uvddoorsopen)) : "";
    $uvddoorsopen = ($uvddoorsopen) ? gmdate("g:i A", strtotime($uvddoorsopen)) : "";
    $uvddoorsopendiv = ($uvddoorsopen) ? "<div class='uwsddoorsopen'><span>Doors Open: </span>" . $uvddoorsopen . "</div>" : "";
    $uvsharelinks = urvenue_ws_get_share_links($uvevent["event-page-url"]);
    // $eventgcalstartdate = date("Ymd", strtotime($uvevent["date"]));
    $eventgcalstartdate = gmdate("Ymd", strtotime($uvevent["date"]));
    // $eventgcalenddate = date("Ymd", strtotime($uvevent["date"]));
    $eventgcalenddate = gmdate("Ymd", strtotime($uvevent["date"]));
    $uvgooglecalendarlink = "https://www.google.com/calendar/event?action=TEMPLATE&text=" . urlencode($uvevent["name"]) . "&details=" . urlencode($uvevent["name"]) . "&dates={$eventgcalstartdate}/{$eventgcalenddate}&location=" . urlencode($uvevent["venuename"]);
    $uveventcontclasses = (isset($uvevent["isnoevent"]) and $uvevent["isnoevent"]) ? "uwsisnoevent" : "";
    $uveventcontclasses = (!(isset($uvevent["flyers"]["eventpage"]["url"]) and $uvevent["flyers"]["eventpage"]["url"])) ? $uveventcontclasses . " uwsnoflyer" : $uveventcontclasses;
    $uveventsliderfull = $uvevent["flyers"]["slider"]["full"];
    $uvtheme = urvenue_ws_get_theme();
    $uveventdp = urvenue_ws_get_event_datesel($uvevent["date"], $uvevent["venuecode"]);
    $uveventdpicon = "<div class='uwsdpicon'>" . urvenue_ws_get_icon("caret") . "</div>";

    $uveventecozone = (isset($uvevent["ecozone"]) and $uvevent["ecozone"] and $urvenue_ws_config_cal_add_ecozone_data) ? "data-ecozone='" . $uvevent["ecozone"] . "'" : "";
    $uveventaddflyer = (isset($uvevent["flyers"]["calendar"]["url"]) && $uvevent["flyers"]["calendar"]["url"] && $urvenue_ws_config_cal_show_flyers)
        ? "<div class='uwsflyercont uws-ratio-" . $uvevent["flyers"]["calendar"]["ratio"] . "'>
            <img class='uwsimgloading' src='" . $uvevent["flyers"]["calendar"]["url"] . "' alt='" . $uvevent["name"] . " - Flyer' onload=\"this.classList.add('uwsloaded'); this.closest('li').classList.add('uwsloaded')\">
           </div>"
        : "";

    if(isset($urvenue_ws_core_lib["system"]) and isset($urvenue_ws_core_lib["system"]["include-stocks-on-events"]) and $urvenue_ws_core_lib["system"]["include-stocks-on-events"])
        $uveventcontclasses .= (isset($uvevent["stocks"]) and is_array($uvevent["stocks"]) and count($uvevent["stocks"])) ? " uwshasstock" : " uwsoutofstock";

    if(isset($urvenue_ws_core_lib["system"]) and isset($urvenue_ws_core_lib["events"]["global-usevenuelogoasflyer"]) and $urvenue_ws_core_lib["events"]["global-usevenuelogoasflyer"]){ //Use venue logo as flyer
        $uvlogoasflyer = array(
            "url" => $uvevent["venuelogodarkbg"]["url"],
            "ratio" => "venuelogo",
            "full" => $uvevent["venuelogodarkbg"]["full"],
        );

        if(!isset($uvevent["flyers"])){
            $uvevent["flyers"] = array(
                "eventpage" => $uvlogoasflyer,
                "calendar" => $uvlogoasflyer,
                "list" => $uvlogoasflyer,
                "slider" => $uvlogoasflyer,
                "slidermobile" => $uvlogoasflyer,
                "share" => $uvlogoasflyer,
                "custom1" => $uvlogoasflyer,
                "custom2" => $uvlogoasflyer,
            );
        }
    }

    $uveventtarget = ($urvenue_ws_config_event_on_newtab) ? " target='_blank' rel='noopener'" : "";
 
    $uveventitem = str_replace(
        array(
            "{eventname}",
            "{eventdate}",
            "{eventlink}",
            "{eventpagelink}",
            "{eventflyer-calendar}",
            "{eventflyer-calendar-ratio}",
            "{eventflyer-list}",
            "{eventflyer-list-ratio}",
            "{eventflyer-eventpage}",
            "{eventflyer-eventpage-full}",
            "{eventflyer-eventpage-ratio}",
            "{eventflyer-slider-full}",
            "{eventflyer-custom1}",
            "{eventflyer-custom2}",
            "{eventflyer-slidermobile}",
            "{eventddate}",
            "{eventvenuediv}",
            "{eventdsday}",
            "{eventdsdaylong}",
            "{eventdday}",
            "{eventdsmonth}",
            "{eventdsmonthlong}",
            "{eventdsyear}",
            "{eventdescrtitlediv}",
            "{dstarttimediv}",
            "{venueaddress}",
            "{ddooropendiv}",
            "{eventsharelinks}",
            "{venuename}",
            "{venuefullname}",
            "{venuegmapurl}",
            "{venuealias}",
            "{venuekey}",
            "{venuecode}",
            "{venuegooglecalendarlink}",
            "{performersclass}",
            "{eventcontclasses}",
            "{eventtypename}",
            "{eventcode}",
            "{uveventecozone}",
            "{theme}",
            "{venueurl}",
            "{eventdp}",
            "{eventdpicon}",
            "{eventddescr}",
            "{addecozone}",
            "{eventaddflyer}",
            "{eventlinktarget}",
            "{eventdescrdiv}",
        ),
        array(
            $uvevent["name"],
            $uvevent["date"],
            $uvevent["event-url"],
            $uvevent["event-page-url"],
            $uvevent["flyers"]["calendar"]["url"],
            $uvevent["flyers"]["calendar"]["ratio"],
            $uvevent["flyers"]["list"]["url"],
            $uvevent["flyers"]["list"]["ratio"],
            $uvevent["flyers"]["eventpage"]["url"],
            $uvevent["flyers"]["eventpage"]["full"],
            $uvevent["flyers"]["eventpage"]["ratio"],
            $uveventsliderfull,
            $uvevent["flyers"]["custom1"]["url"],
            $uvevent["flyers"]["custom2"]["url"],
            $uvevent["flyers"]["slidermobile"]["url"],
            urvenue_ws_lang_date($uvddate),
            $uvvenuediv,
            urvenue_ws_lang_date(gmdate("D", strtotime($uvevent["date"]))),
            gmdate("l", strtotime($uvevent["date"])),
            gmdate("j", strtotime($uvevent["date"])),
            urvenue_ws_lang_date(gmdate("M", strtotime($uvevent["date"]))),
            gmdate("F", strtotime($uvevent["date"])),
            urvenue_ws_lang_date(gmdate("Y", strtotime($uvevent["date"]))),
            $uveventinfodiv,
            $uvdstarttimediv,
            $uvevent["venueaddress"],
            $uvddoorsopendiv,
            $uvsharelinks,
            $uvevent["venuename"],
            $uvevent["venuename"],
            $uvevent["venuegmapurl"],
            $uvvenuealias,
            $uvevent["venuekey"],
            $uvevent["venuecode"],
            $uvgooglecalendarlink,
            $uvperformersclass,
            $uveventcontclasses,
            $uvevent["eventtypename"],
            $uvevent["eventcode"],
            $uvevent["ecozone"],
            $uvtheme,
            $uvevent["venue-url"],
            $uveventdp,
            $uveventdpicon,
            $uvevent["descr"],
            $uveventecozone,
            $uveventaddflyer,
            $uveventtarget,
            $uveventdescrdiv,
        ),
        $uvtemplate
    );

    $uveventitem = urvenue_ws_apply_filters("urvenue_ws_event_after_replace", $uveventitem, $uvevent);

    return $uveventitem;
}

/*Get date selector field
    Returns: HTML with date selector
*/
function urvenue_ws_get_event_datesel($uvdate = "", $uvvenuecode = ""){
    $uvmindate = gmdate('Y-m-01', strtotime(urvenue_ws_get_events_initial_date("Y-m-d")));
    $uvmaxdate = urvenue_ws_get_events_max_date("Y-m-d");
    // $uvfromdate = date('Y-m-01', strtotime($uvdate));
    $uvfromdate = gmdate('Y-m-01', strtotime($uvdate));
    // $uvtodate = date("Y-m-t", strtotime($uvdate));
    $uvtodate = gmdate("Y-m-t", strtotime($uvdate));
    // $uvddate = date("M j, Y", strtotime($uvdate));
    $uvddate = gmdate("M j, Y", strtotime($uvdate));
    $uvchangeeventdatelabel = urvenue_ws_lang("change-event-date");
    $uvddatelang = urvenue_ws_lang_date($uvddate);
    $uvpoptheme = urvenue_ws_get_popup_theme();

    $uveventcontrols = "
        <div class='uws-event-dpinput uwshascalincon uws-dropdown-cont' data-poptheme='$uvpoptheme'>
            <a id='uwseventfilterdate' href='#uws-openeventdateselection' 
                data-date='$uvdate' data-fromdate='$uvfromdate' data-todate='$uvtodate'
                data-mindate='$uvmindate' data-maxdate='$uvmaxdate' 
                data-filter-maxdate='$uvmaxdate' data-lang='$uvddatelang'
                data-venuecode='$uvvenuecode'
                class='uwseventdp uwsjs-show-evtdp uwsjs-trigger-dropdown' 
                aria-label='$uvchangeeventdatelabel'></a>
            <div class='uws-dropdown'>
                <div class='uws-loader-uvicon'></div>
                <div class='uws-dp-eventfilterdate'></div>
            </div>
        </div>
    ";

    return $uveventcontrols;
}

/*Get events calendar view
    Requires: events:(array with urvenue events)
*/
function urvenue_ws_calendar($uvevents, $uvargs = ""){
    global $urvenue_ws_core_lib;

    $uvfromdate = urvenue_ws_get_arg($uvargs, "fromdate", urvenue_ws_get_events_initial_date("Y-m-d"));
    $uvtodate = urvenue_ws_get_arg($uvargs, "todate", urvenue_ws_get_events_endinit_date("Y-m-d", $uvfromdate));
    $uvmaxdate = urvenue_ws_get_arg($uvargs, "maxdate", $uvtodate);
    $uvnopredates = urvenue_ws_get_arg($uvargs, "nopredates", "");

    $uvevents = (is_array($uvevents)) ? urvenue_ws_eventskeys_to_date($uvevents) : $uvevents;
    $uvcelltemplate = urvenue_ws_get_template("events/events-calendar-cell-default");
    $uvsingletemplate = urvenue_ws_get_template("events/events-calendar-single-default");
    $uvcellmobilelinktemplate = urvenue_ws_get_template("events/calendar-cell-mobile-link");
    $uvcalendarcells = $uvncells = $uvcellclass = "";

    //get start date monday
    $uvcalstartdate = (gmdate("N", strtotime($uvfromdate)) != 1 and !$uvnopredates) ? gmdate('Y-m-d', strtotime('previous monday', strtotime($uvfromdate))) : $uvfromdate;
    $uvcalstartdatetms = strtotime($uvcalstartdate);

    $uvcalenddate = (gmdate("N", strtotime($uvmaxdate)) != 7 and !$uvnopredates) ? gmdate('Y-m-d', strtotime('next sunday', strtotime($uvmaxdate))) : $uvmaxdate;
    $uvcalenddatetms = strtotime($uvcalenddate);

    $uvcaldaysbetween = $uvcalenddatetms - $uvcalstartdatetms;
    $uvcaldaysbetween = round($uvcaldaysbetween / (60 * 60 * 24));

    for($i=0; $i<=$uvcaldaysbetween; $i++){//124 max 4 months loaded
        // $uvceldate = date("Y-m-d", $uvcalstartdatetms);
        $uvceldate = gmdate("Y-m-d", $uvcalstartdatetms);
		// $uvcelldate = date("M j", $uvcalstartdatetms);
		$uvcelldate = gmdate("M j", $uvcalstartdatetms);
		// $uvcellsdate = date("Ymd", $uvcalstartdatetms);
		$uvcellsdate = gmdate("Ymd", $uvcalstartdatetms);
        // $uvcellddate = date($urvenue_ws_core_lib["events"]["global-dateformat"], strtotime($uvcalstartdatetms));
        $uvcellddate = gmdate($urvenue_ws_core_lib["events"]["global-dateformat"], strtotime($uvcalstartdatetms));

        $uvcalendarcell = $uvcelleventslist = $uvcellmobilelink = "";
        $uvcellevents = (is_array($uvevents) and isset($uvevents["D" . $uvcellsdate])) ? $uvevents["D" . $uvcellsdate] : "";
        $uvcellnevents = (is_array($uvcellevents)) ? count($uvcellevents) : 0;

        //$urvenue_ws_core_lib["events"]["calendar-alwayslist"];
        if($uvcellnevents and ($uvcellnevents > 1 or $urvenue_ws_core_lib["events"]["calendar-alwayslist"])){
            $uvlistargs = array(
                "wrap-template" => "<div class='uwsinstructions'><span>Select Event</span></div><ul class='uws-cal-multi-default'>{eventslist}</ul>",
                "item-template" => "events/events-calendar-list-default"
            );
            $uvcelleventslist = urvenue_ws_get_events_list($uvcellevents, $uvlistargs);
        }
        else if($uvcellnevents){ //When there is only 1 event
            $uvcelleventslist = urvenue_ws_replace_event_vars($uvcellevents[0], $uvsingletemplate);
        }
        
        if($uvcelleventslist){ //add mobile link only when date has events
            if($uvcellnevents === 1) $uvcellclass .= "uws-cal-cell-single";
            else if($uvcellnevents > 1) $uvcellclass .= "uws-cal-cell-multi";
            else $uvcellclass .= "uws-cal-cell-empty";
            
            $uvcellmobilelink = str_replace(
                array(
                    "{sdate}",
                    "{ddate}",
                ),
                array(
                    $uvcellsdate,
                    urvenue_ws_lang_date($uvcellddate),
                ),
                $uvcellmobilelinktemplate
            );
        }

        $uvcalendarcell = str_replace(
            array(
                "{calevents}",
                "{sdate}",
                "{calddate}",
                "{cellclass}",
                "{calcellmobilelink}",
            ),
            array(
                $uvcelleventslist,
                $uvcellsdate,
                urvenue_ws_lang_date($uvcelldate),
                $uvcellclass,
                $uvcellmobilelink,
            ),
            $uvcelltemplate
        );
        $uvcalendarcells .= $uvcalendarcell;//add cell to return var

        $uvcalstartdatetms = strtotime("+1 day", $uvcalstartdatetms);
        
        if($uvceldate >= $uvmaxdate){
            // $uvcurndate = date("N", strtotime($uvceldate));
            $uvcurndate = gmdate("N", strtotime($uvceldate));
            $uvmissingcells = 7 - $uvcurndate;
            $uvmissingcells = ($uvmissingcells < 0) ? "0" : $uvmissingcells;
            $i = $uvcaldaysbetween - $uvmissingcells;

            $uvcellclass = "uwsfillcell ";
        }
        else
            $uvcellclass = "";

        $uvncells++;
    }

    return $uvcalendarcells;
}

/*Change array keys to events dates
    Requires: events:(array with urvenue events)
*/
function urvenue_ws_eventskeys_to_date($uvevents){
    $uveventsreturn = "";

    if(is_array($uvevents) and count($uvevents)){
        $uveventsreturn = array();
        foreach($uvevents as $uvevent){
            $uveventcode = $uvevent["eventcode"];
            $uvmaineventcode = $uvevent["maineventcode"];

            if($uveventcode === $uvmaineventcode) {
                // $uveventsdate = date("Ymd", strtotime($uvevent["date"]));
                $uveventsdate = gmdate("Ymd", strtotime($uvevent["date"]));
                $uveventsreturn["D" . $uveventsdate][] = $uvevent;
            }
        }
    }

    return $uveventsreturn;
}

/*Get events filter/controls
    Returns: Controls html
*/
function urvenue_ws_events_controls($uvargs){
    global $urvenue_ws_core_lib, $urvenue_ws_config_events_disabled_dates;

    $uvdateselectortype = $urvenue_ws_core_lib["events"]["eventspage-dateselector"]; //"datepicker-date", "datepicker-range", "month-dropdown", "month-arrows"
    $uvdateselectorclass = $uvdateloader = $uvdateselectorattrs = "";

    $uvdate = urvenue_ws_get_arg($uvargs, "date", "");
    if($uvdate){
        // $uvinitialddate = date("M j, Y", strtotime($uvdate));
        $uvinitialddate = gmdate("M j, Y", strtotime($uvdate));
        $uvinitialdate = $uvdate;
    }
    else{
        $uvinitialddate = urvenue_ws_get_events_initial_date("M j, Y");
        $uvinitialdate = urvenue_ws_get_events_initial_date("Y-m-d");
    }

    $uvsetdisableddates = (isset($urvenue_ws_core_lib["events"]["noinventorydates-enabled"]) and $urvenue_ws_core_lib["events"]["noinventorydates-enabled"]) ? $urvenue_ws_core_lib["events"]["noinventorydates-enabled"] : 0;
    $uvsetdisableddates = ($urvenue_ws_config_events_disabled_dates) ? $urvenue_ws_config_events_disabled_dates : $uvsetdisableddates;

    if($uvsetdisableddates) {
        $uvvenuekeycode = urvenue_ws_get_arg($uvargs, "venue", "all");
        $urvenue_ws_venuecodes = urvenue_ws_get_arg($uvargs, "venuecodes", "");
        $uvvenuescodesstring = ($urvenue_ws_venuecodes) ? $urvenue_ws_venuecodes : urvenue_ws_get_venuecodes_string($uvvenuekeycode);
        $uvmaxdate = urvenue_ws_get_events_max_date("Y-m-d");
        // $uvfromdate = date('Y-m-01', strtotime($uvinitialdate));
        $uvfromdate = gmdate('Y-m-01', strtotime($uvinitialdate));
        // $uvtodate = date("Y-m-t", strtotime($uvinitialdate));
        $uvtodate = gmdate("Y-m-t", strtotime($uvinitialdate));
        // $uvddate = date("M j, Y", strtotime($uvinitialdate));
        $uvddate = gmdate("M j, Y", strtotime($uvinitialdate));
        $uvchangeeventsdatelabel = urvenue_ws_lang("change-events-date");
        $uvddatelang = urvenue_ws_lang_date($uvddate);

        $uvdateselectorclass = "uws-disableddates";
        $uvdateloader = "<div class='uws-loader-uvicon'></div>";

        $uvdateselectorattrs = "data-date='$uvinitialdate' data-fromdate='$uvfromdate' data-todate='$uvtodate' data-mindate='$uvinitialdate' data-maxdate='$uvmaxdate' data-filter-maxdate='$uvmaxdate' data-lang='$uvddatelang'" .
            " data-venuecode='$uvvenuescodesstring' aria-label='$uvchangeeventsdatelabel'";
    }

    if($uvdateselectortype == "datepicker-date"){
        $uvcalmonthselhtml = "
        <div class='uws-events-dpinput uwshascalincon uws-dropdown-cont $uvdateselectorclass'>
            <label for='uwsfilterdate'>Date</label>
            <i class='uwsicon-calendar'></i>
            <a id='uwsfilterdate' href='#uws-openeventsdateselection' data-date='$uvinitialdate' $uvdateselectorattrs class='uwsjs-trigger-dropdown'>$uvinitialddate</a>
            <div class='uws-dropdown'>
                $uvdateloader
                <div class='uws-dp-filterdate'></div>
            </div>
        </div>
        ";
    }
    else if($uvdateselectortype == "datepicker-range"){
        // $uvinitialddate = date("M j", strtotime($uvinitialdate));
        $uvinitialddate = gmdate("M j", strtotime($uvinitialdate));
        $uvenddate = urvenue_ws_get_arg($uvargs, "enddate", "");
        $uvendddate = ($uvenddate) ? gmdate("M j, Y", strtotime($uvenddate)) : urvenue_ws_get_events_endinit_date("M j, Y");
        $uvenddate = (!$uvenddate) ? urvenue_ws_get_events_endinit_date("Y-m-d") : $uvenddate;
        $uvrangeddate = urvenue_ws_lang_date("$uvinitialddate - $uvendddate");

        $uvcalmonthselhtml = "
        <div class='uws-events-dpinput uws-dropdown-cont uwshascalincon'>
            <label for='uwsfilterrange'>" . urvenue_ws_lang("date-range") . "</label>
            <i class='uwsicon-calendar'></i>
            <a id='uwsfilterrange' href='#uws-openeventsdaterangeselection' class='uwsjs-trigger-dropdown' data-date='$uvinitialdate' data-enddate='$uvenddate' data-lang='" . urvenue_ws_get_cur_lang() . "'>$uvrangeddate</a>
            <div class='uws-dropdown'>
                <div class='uws-dp-filterdaterange'></div>
                <div class='uws-dp-filterdaterange-label'>" . urvenue_ws_lang("select-range") . "</div>
            </div>
        </div>
        ";
    }
    else if($uvdateselectortype == "month-dropdown"){
        $uvinitialddate = urvenue_ws_get_events_initial_date("F Y");
        $uvinitialddate = urvenue_ws_lang_date($uvinitialddate);
        $uvmonthslist = urvenue_ws_get_monthslis();
        $uvcalmonthselhtml = "
        <div class='uws-dropdown-cont uwshascalincon'>
            <i class='uwsicon-calendar'></i>
            <a href='#uws-openmonthselection' class='uwsjs-trigger-dropdown' aria-label='Select Month'><span class='uwsdy-dropvalue'>$uvinitialddate</span></a>
            <div class='uws-dropdown'>
                <ul>$uvmonthslist</ul>
            </div>
        </div>
        ";
    }
    else{//Month Arrows
        $uvinitialddate = urvenue_ws_get_events_initial_date("F Y");
        $uvinitialdate = urvenue_ws_get_events_initial_date("Y-m-d");
        $uvmonthsstring = urvenue_ws_get_monthsoptsstring();
        $uvcalmonthselhtml = "
        <div class='uwsmonthssteps'>
            <div class='uwsmonthsstepsbtns' data-currentdate='$uvinitialdate' data-months='$uvmonthsstring'>
                <a class='uwsjs-events-prevmonth uwsdisabled' href='#uws-eventsprevmonth' aria-label='Previous Month'><i class='uwsicon-left-open'></i><span>Previous Month</span></a>
                <a class='uwsjs-events-nextmonth' href='#uws-eventsnextmonth' aria-label='Next Month'><i class='uwsicon-right-open'></i><span>Next Month</span></a>
            </div>
            <div class='uwsmonth uwsdy-eventsmonth'>$uvinitialddate</div>
        </div>
        ";
    }

    $uveventsviewmenu = urvenue_ws_get_events_view_menu($uvargs);
    $uveventsviewmenuhtml = $uveventsviewmenu["menu"];
    $uveventsviewmenuselected = $uveventsviewmenu["selected"];
  
    //Venues Dropdowns
    $uvvenuesdropdown = "";
    if($urvenue_ws_core_lib["events"]["eventspage-addvenuefilter"]){
        $uvvenuekeycodes = urvenue_ws_get_arg($uvargs, "venue", "all");
        $uvnvenues = (urvenue_ws_get_arg($uvargs, "venuecodes", "")) ? count(array_filter(explode(',', urvenue_ws_get_arg($uvargs, "venuecodes", "")), fn($v) => trim($v) !== '')) : 0;

        if(!is_array($urvenue_ws_core_lib["venues"]) and $uvnvenues > 0)
            $uvvenuekeycodes = urvenue_ws_get_arg($uvargs, "venuecodes", "all");

        $uvvenuesinfilter = (urvenue_ws_get_arg($uvargs, "venuesinfilter", "")) ? urvenue_ws_get_arg($uvargs, "venuesinfilter", "") : $uvvenuekeycodes;
        $uvallcurrent = ($uvvenuekeycodes == "all") ? "uwscurrent" : "";
        $uvvenueslist = urvenue_ws_get_venueslis($uvvenuesinfilter, "", $uvargs);
        $uvselectedvenues = explode(",", $uvvenuesinfilter);
        $uvfilterclass = (is_array($uvselectedvenues) and count($uvselectedvenues) > 1) ? "uwsismultiselected" : "";
        $uvallvenueskey = (is_array($uvselectedvenues) and count($uvselectedvenues) > 1) ? $uvvenuesinfilter : "all";

        $uvselectedvenuename = (urvenue_ws_get_venuename_byvenuekey($uvvenuekeycodes)) ? urvenue_ws_get_venuename_byvenuekey($uvvenuekeycodes) : "All Venues";

        $uvvenuesdropdown = "
        <div class='uwsvenuesel'>
            <div class='uws-dropdown-cont'>
                <a href='#uws-openvenueselection' class='uwsjs-trigger-dropdown' aria-label='Select Venue'><span class='uwsdy-dropvalue'>$uvselectedvenuename</span></a>
                <div class='uws-dropdown'>
                    <ul class='$uvfilterclass'>
                        <li class='$uvallcurrent'><button class='uwsjs-events-selectvenue uwseventsvenueselall' aria-label='Select All Venues' type='button' data-venue='$uvallvenueskey'>All<span> Venues</span></button></li>
                        $uvvenueslist
                    </ul>
                </div>
            </div>
        </div>
        ";
    }
   
    //Performer Dropdowns
    $uvperformersdropdown = "";
    if($urvenue_ws_core_lib["events"]["global-addperformerfilter"]){
        $uvargs["onlyevents"] = 1;
        $uvperformerlist = urvenue_ws_get_performerlis($uvargs);

        if($uvperformerlist)
            $uvperformersdropdown = "
            <div class='uwsperformersel'>
                <div class='uws-dropdown-cont '>
                    <a id='uwsfilterperformer' href='#uws-openperformerselection' class='uwsjs-trigger-dropdown' aria-label='Select Artist'><span class='uwsdy-dropvalue'>All Artists</span></a>
                    <div class='uws-dropdown'>
                        <ul>
                            <li class='uwscurrent' arial-label='Select All Artists' data-performer='all'>
                                <button class='uwsjs-events-selectperformer uwseventsartistsselall' aria-label='Select all Artists' type='button' data-performer='all' data-performercode='all'>
                                    All<span> Artists</span>
                                </button>
                            </li>
                            $uvperformerlist
                        </ul>
                    </div>
                </div>
            </div>
            ";
    }

    $uvviewsclass = str_replace("+", "-", $urvenue_ws_core_lib["events"]["eventspage-viewmenu"]);

    $uvcontrols = "
    <div class='uws-events-controls'>
        <div class='uwsfilters uwsisdatesel-$uvdateselectortype'>
            <div class='uwsdatesel'>$uvcalmonthselhtml</div>
            $uvperformersdropdown
            $uvvenuesdropdown
            <div class='uws-loader-uvicon'></div>
        </div>
        <div class='uwsviews uwsviews-$uvviewsclass'>
            <div class='uws-dropdown-cont'>
                <a href='#uws-openviewselection' class='uwsjs-trigger-dropdown' aria-label='Select View'><span class='uwsdy-dropvalue'>$uveventsviewmenuselected</span></a>
                <div class='uws-dropdown'>
                    $uveventsviewmenuhtml
                </div>
            </div>
            $uveventsviewmenuhtml
        </div>
    </div>";

    return $uvcontrols;
}

/*Get List of views menu*/
function urvenue_ws_get_events_view_menu($uvargs = "")
{
    global $urvenue_ws_core_lib;

    
    $uvviewsmenu = "";
    $uvviewselected = "";
    $uvviewordered = array();
    $uvviews = (isset($urvenue_ws_core_lib["events"])) ? $urvenue_ws_core_lib["events"]["eventspage-views"] : "";
    $uvdefaultview = urvenue_ws_get_arg($uvargs, "defaultview", "");

    if (is_array($uvviews)) {
        if ($uvdefaultview)
            $uvviews = urvenue_ws_set_default_view($uvviews, $uvdefaultview);

        $uvviewsmenu = "<ul>";

        foreach ($uvviews as $uvviewkey => $uvview) {
            if ($uvview["show"]) {
                $uvview["key"] = $uvviewkey;
                $uvviewordered["ord" . $uvview["order"]] = $uvview;
            }
        }

        ksort($uvviewordered);

        foreach ($uvviewordered as $uvview) {
            $uvviewkey = isset($uvview["key"]) ? $uvview["key"] : '';
            $uvviewlabel = urvenue_ws_lang($uvview["label"]);

            // Determine active view:
            if (!empty($uvargs['view'])) {
                $isActive = strtolower($uvargs['view']) === strtolower($uvviewkey);
            } else {
                $isActive = !empty($uvview['defaultview']);
            }

            $uvviewclass = $isActive ? "uvsactive" : "";
            $uvviewliclass = $isActive ? "uwscurrent" : "";

            $uvviewsmenu .= "<li class='$uvviewliclass'><a class='uwsjs-events-changeview $uvviewclass' href='#uws-view-$uvviewkey' data-view='$uvviewkey' aria-label='" . urvenue_ws_lang("change-view-to") . " $uvviewlabel'><i class='" . $uvview["icon"] . "'></i><span> $uvviewlabel</span></a></li>";

            if ($isActive) {
                $uvviewselected = "<i class='" . $uvview["icon"] . "'></i><span> $uvviewlabel</span>";
            }
        }

        $uvviewsmenu .= "</ul>";
    }

    $uvviewsmenuarray = array(
        "menu" => $uvviewsmenu,
        "selected" => $uvviewselected
    );

    return $uvviewsmenuarray;
}

/*Get list of venues
    Optional: selected(selected venuecode), actionclass(class for the all the link elements), 
*/
function urvenue_ws_get_venueslis($uvselected = "", $uvactionclass = ""){
    global $urvenue_ws_core_lib, $urvenue_ws_today;

    $uvvenueslist = "";
    $uvvenueclass = "";

    $uvselected = ($uvselected) ? explode(",", $uvselected) : "";

    if(is_array($urvenue_ws_core_lib["venues"])){
        foreach($urvenue_ws_core_lib["venues"] as $uvvenuekey => $uvvenue){
            $uvvenueclass = (is_array($uvselected) and in_array($uvvenuekey, $uvselected)) ? "uwscurrent" : "";
            $uvlinkclass = ($uvactionclass) ? $uvactionclass : "uwsjs-events-selectvenue";
            $uvvenuename = (isset($uvvenue["venueforcealias"]) and $uvvenue["venueforcealias"] and $uvvenue['venuealias']) ? $uvvenue['venuealias'] : $uvvenue["venuename"];
            $uvshowvenue = 1;

            //if selected venue are more than 1 it means the calendar is for specific venues, we should not show all the venues from the library
            if(is_array($uvselected) and (count($uvselected) > 1) and !in_array($uvvenuekey, $uvselected))
                $uvshowvenue = 0;

            if($uvshowvenue)
                $uvvenueslist .= "<li class='$uvvenueclass'><button class='$uvlinkclass' aria-label='Select " . $uvvenue["venuename"] . "' type='button' data-venue='$uvvenuekey' data-venuecode='" . $uvvenue["venuecode"] . "'>$uvvenuename</button></li>";
        }
    }
    else if(is_array($uvargs) and $uvargs["venuecodes"]){
        $uveventsapiname = "inventory-eventsonly";
        $uvterms = array(
            "caldate" => $urvenue_ws_today,
            "todate" => $urvenue_ws_today,
            "venuecode" => $uvargs["venuecodes"],
        );

        $uvapidata = urvenue_ws_get_feed($uveventsapiname, $uvterms);

        if(is_array($uvapidata) and $uvapidata["uv"]["success"]["status"] == "success" and is_array($uvapidata["uv"]["data"]["venues"])){
            $uvvenuesdata = $uvapidata["uv"]["data"]["venues"];

            foreach($uvvenuesdata as $uvvenuekey => $uvvenue){
                $uvlinkclass = ($uvactionclass) ? $uvactionclass : "uwsjs-events-selectvenue";

                $uvvenueslist .= "<li class='$uvvenueclass'><button class='$uvlinkclass' aria-label='Select " . $uvvenue["info"]["name"] . "' type='button' data-venue='" . $uvvenue["info"]["code"] . "' data-venuecode='" . $uvvenue["info"]["code"] . "'>" . $uvvenue["info"]["name"] . "</button></li>";
            }
        }
    }

    return $uvvenueslist;
}

/*Get venue info by eventcode
    Requires: eventcode
    Returns: venueinfo from urquery api
*/
function urvenue_ws_get_venueinfo_by_eventcode($uveventcode){
    $uvvenueinforeturn = "";

    $uveventcodedata = urvenue_ws_get_eventcode_data($uveventcode);
    $uvterms = array(
        "venuecode" => $uveventcodedata["venuecode"],
        "ecozone" => $uveventcodedata["ecozone"],
        "caldate" => $uveventcodedata["date"],
        "todate" => $uveventcodedata["date"],
    );

    $uvfeeddata = urvenue_ws_get_feed("inventory-eventsonly", $uvterms);
    $uveventdatavars = urvenue_ws_get_eventcode_data($uveventcode);

    if(is_array($uvfeeddata) and $uvfeeddata["uv"]["success"]["status"] == "success" and $uvfeeddata["uv"]["data"]["venues"]){
		$uvvenueinforeturn = $uvfeeddata["uv"]["data"]["venues"][$uveventdatavars["venuecode"]];
    }

    return $uvvenueinforeturn;
}

// Get list of months for calendar dropdown
function urvenue_ws_get_monthslis($uvcurrentdate = ""){
    global $urvenue_ws_core_lib, $urvenue_ws_today;

    $uvcurrentdate = ($uvcurrentdate) ? $uvcurrentdate : $urvenue_ws_today;
    $uvnmonths = $urvenue_ws_core_lib["events"]["eventspage-monthsrange"];
    $uvmonthslis = "";
    $uvfirstmonthclass = "uwscurrent";
    
    $uvmonthlidate = $uvcurrentdate;
    $uvmonthlidate = strtotime($uvmonthlidate);

    for($i=0; $i<$uvnmonths; $i++){
        // $uvmonthlifdate = date("Y-m-01", $uvmonthlidate);
        $uvmonthlifdate = gmdate("Y-m-01", $uvmonthlidate);
        $uvmonthlimname = urvenue_ws_lang_date(gmdate("F", $uvmonthlidate));
        // $uvmonthliyear = date("Y", $uvmonthlidate);
        $uvmonthliyear = gmdate("Y", $uvmonthlidate);

        $uvmonthlifdate = ($uvmonthlifdate < $uvcurrentdate) ? $uvcurrentdate : $uvmonthlifdate;

        $uvmonthslis .= "<li class='$uvfirstmonthclass'><button class='uwsjs-events-selectmonth' aria-label='Select $uvmonthlimname' type='button' data-date='$uvmonthlifdate'>$uvmonthlimname $uvmonthliyear</button></li>";

        $uvmonthlidate = strtotime("+1 month", $uvmonthlidate);
        $uvfirstmonthclass = "";
    }

    return $uvmonthslis;
}

// Get string with the months to navigate with arrows
function urvenue_ws_get_monthsoptsstring($uvcurrentdate = ""){
    global $urvenue_ws_core_lib, $urvenue_ws_today;

    $uvcurrentdate = ($uvcurrentdate) ? $uvcurrentdate : $urvenue_ws_today;
    $uvnmonths = $urvenue_ws_core_lib["events"]["eventspage-monthsrange"];
    $uvmonthsstring = "";

    $uvmonthlidate = $uvcurrentdate;
    $uvmonthlidate = strtotime($uvmonthlidate);

    for($i=0; $i<$uvnmonths; $i++){
        // $uvmonthlifdate = date("Y-m-01", $uvmonthlidate);
        $uvmonthlifdate = gmdate("Y-m-01", $uvmonthlidate);
        // $uvmonthlimname = date("F", $uvmonthlidate);
        $uvmonthlimname = gmdate("F", $uvmonthlidate);
        // $uvmonthliyear = date("Y", $uvmonthlidate);
        $uvmonthliyear = gmdate("Y", $uvmonthlidate);

        $uvmonthlifdate = ($uvmonthlifdate < $uvcurrentdate) ? $uvcurrentdate : $uvmonthlifdate;
        $uvmonthsstring .= $uvmonthlifdate . ",";

        $uvmonthlidate = strtotime("+1 month", $uvmonthlidate);
    }

    $uvmonthsstring = rtrim($uvmonthsstring, ',');

    return $uvmonthsstring;
}

/*Get Events Max Date
    Optional: dateformat
*/
function urvenue_ws_get_events_max_date($uvdateformat = ""){
    global $urvenue_ws_core_lib, $urvenue_ws_today, $urvenue_ws_config_cal_nmonths;

    $uvnmonths = (isset($urvenue_ws_core_lib["events"]) and $urvenue_ws_core_lib["events"]["eventspage-monthsrange"]) ? $urvenue_ws_core_lib["events"]["eventspage-monthsrange"] : 6;
    $uvnmonths = ($urvenue_ws_config_cal_nmonths) ? $urvenue_ws_config_cal_nmonths : $uvnmonths;
    // $uvmaxdate = date("Y-m-d", strtotime($urvenue_ws_today . " +$uvnmonths months"));
    $uvmaxdate = gmdate("Y-m-d", strtotime($urvenue_ws_today . " +$uvnmonths months"));

    if(isset($urvenue_ws_core_lib["system"]["global-maxdate"]) and $urvenue_ws_core_lib["system"]["global-maxdate"])
        $uvmaxdate = $urvenue_ws_core_lib["system"]["global-maxdate"];

    if($uvdateformat)
        // $uvmaxdate = date($uvdateformat, strtotime($uvmaxdate));
        $uvmaxdate = gmdate($uvdateformat, strtotime($uvmaxdate));

    return $uvmaxdate;
}

/*Get Events Initial Date
    Optional: dateformat
*/
function urvenue_ws_get_events_initial_date($uvdateformat = ""){
    global $urvenue_ws_core_lib, $urvenue_ws_today;

    $uvlibeventsinitialdate = (isset($urvenue_ws_core_lib["events"]) and $urvenue_ws_core_lib["events"]["global-initaldate"]) ? $urvenue_ws_core_lib["events"]["global-initaldate"] : $urvenue_ws_today;

    if($uvlibeventsinitialdate < $urvenue_ws_today) $uvlibeventsinitialdate = $urvenue_ws_today;

    if($uvdateformat)
        // $uvlibeventsinitialdate = date($uvdateformat, strtotime($uvlibeventsinitialdate));
        $uvlibeventsinitialdate = gmdate($uvdateformat, strtotime($uvlibeventsinitialdate));

    return $uvlibeventsinitialdate;
}

/*Get Events End date
    Returns: enddate = initial date + number of months to load (global-nmonths)
    Optional: dateformat
*/
function urvenue_ws_get_events_endinit_date($uvdateformat = "", $uvdate = ""){
    global $urvenue_ws_core_lib;

    $uvinitialdate = ($uvdate) ? $uvdate : urvenue_ws_get_events_initial_date("Y-m-d");
    $uvnmonths = (isset($urvenue_ws_core_lib["events"]) and $urvenue_ws_core_lib["events"]["global-nmonths"]) ? $urvenue_ws_core_lib["events"]["global-nmonths"] : 2;
    // $uvenddate = date("Y-m-d", strtotime($uvinitialdate . " +$uvnmonths months"));
    $uvenddate = gmdate("Y-m-d", strtotime($uvinitialdate . " +$uvnmonths months"));
    $uvmaxdate = urvenue_ws_get_events_max_date("Y-m-d");
    $uvenddate = ($uvenddate > $uvmaxdate) ? $uvmaxdate : $uvenddate;

    if($uvdateformat)
        // $uvenddate = date($uvdateformat, strtotime($uvenddate));
        $uvenddate = gmdate($uvdateformat, strtotime($uvenddate));

    return $uvenddate;
}

/*Get Events array
    Optional: args(arguments for events)
    Args: fromdate, todate, venue (venuekeycode, "all")
*/
function urvenue_ws_get_events($uvargs = ""){
    global $urvenue_ws_core_lib;

    $uvvenuekeycode = urvenue_ws_get_arg($uvargs, "venue", "all");
    $urvenue_ws_venuecodes = urvenue_ws_get_arg($uvargs, "venuecodes", "");
    $uvvenuescodesstring = ($urvenue_ws_venuecodes) ? $urvenue_ws_venuecodes : urvenue_ws_get_venuecodes_string($uvvenuekeycode);

    $uvdate = urvenue_ws_get_arg($uvargs, "date", "");
    if($uvdate){
        $uvfromdate = urvenue_ws_get_arg($uvargs, "fromdate", $uvdate);
        $uvtodate = urvenue_ws_get_arg($uvargs, "todate", urvenue_ws_get_events_endinit_date("Y-m-d", $uvdate));
    }
    else{
        $uvfromdate = urvenue_ws_get_arg($uvargs, "fromdate", urvenue_ws_get_events_initial_date("Y-m-d"));
        $uvtodate = urvenue_ws_get_arg($uvargs, "todate", urvenue_ws_get_events_endinit_date("Y-m-d", $uvfromdate));
    }

    $uveventsgroup = urvenue_ws_get_arg($uvargs, "eventsgroup", "");

    $uveventsapiname = "inventory-eventsonly";

    //Determinate if it uses inventorylist or urquery
    /*if(isset($urvenue_ws_core_lib["system"]) and isset($urvenue_ws_core_lib["system"]["use-inventorylist-forevents"]) and $urvenue_ws_core_lib["system"]["use-inventorylist-forevents"]){
        $uvterms = array(
            "caldate" => $uvfromdate,
            "todate" => $uvtodate,
        );
        $uveventsapiname = "inventorylist-events";
    }
    if(isset($urvenue_ws_core_lib["system"]) and isset($urvenue_ws_core_lib["system"]["filter-marketplace"]) and $urvenue_ws_core_lib["system"]["filter-marketplace"]){
        $uvterms = array(
            "caldate" => $uvfromdate,
            "todate" => $uvtodate,
        );
        $uveventsapiname = "inventorylist-events-marketplace";
    }
    if(isset($urvenue_ws_core_lib["system"]) and isset($urvenue_ws_core_lib["system"]["include-stocks-on-events"]) and $urvenue_ws_core_lib["system"]["include-stocks-on-events"]){
        $uvterms = array(
            "caldate" => $uvfromdate,
            "todate" => $uvtodate,
            "venuecode" => $uvvenuescodesstring,
        );
        $uveventsapiname = "inventorylist-events-stocks";
    }*/

    //if($uveventsapiname == "inventory")
    $uvterms = array(
        "caldate" => $uvfromdate,
        "todate" => $uvtodate,
        "venuecode" => $uvvenuescodesstring
    );

    if(isset($urvenue_ws_core_lib["system"]) and isset($urvenue_ws_core_lib["system"]["use-market-events"]) and $urvenue_ws_core_lib["system"]["use-market-events"] and isset($urvenue_ws_core_lib["events"]["market-events-venueid"]) and $urvenue_ws_core_lib["events"]["market-events-venueid"]){
        $uveventsapiname = "marketevents";
        $uvterms["fromdate"] = $uvfromdate;
        $uvterms["venueid"] = $urvenue_ws_core_lib["events"]["market-events-venueid"];
        unset($uvterms["caldate"]);
        unset($uvterms["venuecode"]);
    }

    $uveventsdata = urvenue_ws_get_feed($uveventsapiname, $uvterms);

    if(isset($urvenue_ws_core_lib["system"]) and isset($urvenue_ws_core_lib["system"]["use-market-events"]) and $urvenue_ws_core_lib["system"]["use-market-events"] and isset($urvenue_ws_core_lib["events"]["market-events-venueid"]) and $urvenue_ws_core_lib["events"]["market-events-venueid"])
        $uveventsdata = urvenue_ws_preprocess_market_events($uveventsdata);

    if($uveventsgroup == "featured")
        $uveventsarray = urvenue_ws_get_featured_events_array($uveventsdata, $uvargs);
    else
        $uveventsarray = urvenue_ws_get_events_array($uveventsdata, $uvargs);

    $uveventsarray = urvenue_ws_apply_filters("urvenue_ws_events_before_return_list", $uveventsarray);

    return $uveventsarray;
}

/*Get Event array
    Requires: eventcode
    Returns: Array with event info
*/
function urvenue_ws_get_event($uveventcode, $uvargs = ""){
    $uveventreturnarray = "";

    $uveventcodedata = urvenue_ws_get_eventcode_data($uveventcode);
    $uvterms = array(
        "venuecode" => $uveventcodedata["venuecode"],
        "ecozone" => $uveventcodedata["ecozone"],
        "caldate" => $uveventcodedata["date"],
        "todate" => $uveventcodedata["date"],
    );

    $uveventdata = urvenue_ws_get_feed("inventory-eventsonly", $uvterms);

    if(!is_array($uvargs))
        $uvargs = array();
    
    $uvargs["forcereturneventcode"] = $uveventcode;

    $uveventarray = urvenue_ws_get_events_array($uveventdata, $uvargs);

    if(is_array($uveventarray) and isset($uveventarray[$uveventcode]) and is_array($uveventarray[$uveventcode]))
        $uveventreturnarray = $uveventarray[$uveventcode];

    //Build Ecozones Map
    if(urvenue_ws_get_arg($uvargs, "returnecozonesmap")){
        $uveventecozonesmap = urvenue_ws_get_event_ecozonemap($uveventdata, $uveventcode);

        $uveventreturnarray = array(
            "event" => $uveventreturnarray,
            "ecozonesmap" => $uveventecozonesmap,
        );
    }

    return $uveventreturnarray;
}

/*Precess API data and returns array based on featured events
    Requires: apidata(Raw data from API)
*/
function urvenue_ws_get_featured_events_array($uvapidata, $uvargs = ""){
    global $urvenue_ws_core_lib;

    $uvevents = "";
    $uvvenueskeysbycode = urvenue_ws_get_venuekesybycode();

    if(is_array($uvapidata) and $uvapidata["uv"]["success"]["status"] == "success"){
		$uvfeatured = $uvapidata["uv"]["data"]["featured"];
        $uvnevents = urvenue_ws_get_arg($uvargs, "nevents", 200);

        if(is_array($uvfeatured)){
            $uvevents = array();

            foreach($uvfeatured as $uvfeaturedevent){
                $uvvenuecode = $uvfeaturedevent["venuecode"];
                $uvvenueid = str_replace("VEN", "", $uvvenuecode);
                $uvvenueinfo = $uvapidata["uv"]["data"]["venues"][$uvvenuecode];
                $uveventfulladdress = ($uvvenueinfo["info"]["city"]) ? $uvvenueinfo["info"]["address"] . "<br>" . $uvvenueinfo["info"]["city"] . ", " . $uvvenueinfo["info"]["province"] . " " . $uvvenueinfo["info"]["zip"] : $uvvenueinfo["info"]["address"];
                $uvvenuegmapurl = wp_strip_all_tags($uveventfulladdress);
                $uvvenuegmapurl = "https://www.google.com/maps/search/?api=1&query=" . urlencode($uvvenuegmapurl);
                $uvvenuemanageentid = $uvvenueresellerid = $uvvenueprividerid = $uvvenueinfo["info"]["manageentid"];

                $uvevent = $uvfeaturedevent;
                $uvincludeevent = 1;
                $uveventplainflyers = urvenue_ws_get_event_flyersarray($uvevent["flyers"]);

                $uvflyeraspects = urvenue_ws_get_arg($uvargs, "flyeraspects", "");
                if(is_array($uvflyeraspects)){
                    $uvaspectsflyers = urvenue_ws_get_flyersbypriority($uveventplainflyers, $uvflyeraspects);
                    if(!$uvaspectsflyers)
                        $uvincludeevent = 0;
                    else
                        $uvevent["aspectsflyers"] = $uvaspectsflyers;
                }

                if($uvincludeevent){
                    $uvdefaultlink = urvenue_ws_get_event_url($uvevent, $urvenue_ws_core_lib["events"]["global-defaulteventurl"]);

                    $uvevent = urvenue_ws_normalize_event($uvevent);
                    $uvevent["dstarttime"] = urvenue_ws_get_formattime($uvevent["nstarttime"]);
                    $uvevent["venuekey"] = $uvvenueskeysbycode["$uvvenuecode"];
                    $uvevent["venueaddress"] = $uveventfulladdress;
                    $uvevent["venuegmapurl"] = $uvvenuegmapurl;
                    $uvevent["ecozone"] = "";
                    $uvevent["flyers"] = urvenue_ws_get_event_flyers($uveventplainflyers);
                    $uvevent["event-url"] = $uvdefaultlink;
                    $uvevent["event-page-url"] = urvenue_ws_get_event_url($uvevent, "event");
                    $uvevent["map-url"] = urvenue_ws_get_event_url($uvevent, "map");
                    $uvevent["venue-default-manageentid"] = $uvvenuemanageentid;
                    $uvevent["venue-default-resellerid"] = $uvvenueresellerid;
                    $uvevent["venue-default-providerid"] = $uvvenueprividerid;
                        
                    //add event to array
                    $uvevents[$uvevent["eventcode"]] = $uvevent;
                    
                    $uvnevents--;
                    if(!$uvnevents) break;
                }
            }
        }
    }

    return $uvevents;
}

/*Process Market Events Feed to Mimic regular schedule array
    Requires: eventsdata
    Returns: Array with schedules normal array (DXXXXXX -> VENXXXXXX -> ECOZONE -> EVENT)
*/
function urvenue_ws_preprocess_market_events($uveventsdata){
    $uvschedulesarray = "";

    if(is_array($uveventsdata) and $uveventsdata["uv"]["data"]){
        $uvschedulesarray = array();

        foreach($uveventsdata["uv"]["data"] as $uvmarketevent){
            $uvmarketeventdate = $uvmarketevent["caldate"];
            // $uvmarketeventsdate = date("ymd", strtotime($uvmarketeventdate));
            $uvmarketeventsdate = gmdate("ymd", strtotime($uvmarketeventdate));
            // $uvmarketeventsldate = date("Ymd", strtotime($uvmarketeventdate));
            $uvmarketeventsldate = gmdate("Ymd", strtotime($uvmarketeventdate));
            $uvmarketeventvencode = "VEN" . $uvmarketevent["venueid"];
            $uvmarketeventecozone = "ECZ" . $uvmarketevent["ecozoneid"];
            $uvmarketevent["eventcode"] = "EVE" . $uvmarketevent["venueid"] . $uvmarketevent["ecozoneid"] . $uvmarketeventsldate;
            $uvmarketevent["flyers"] = urvenue_ws_preprocess_market_flyers($uvmarketevent["flyers"]);

            if(!isset($uvschedulesarray["D" . $uvmarketeventsdate]))
                $uvschedulesarray["D" . $uvmarketeventsdate] = array();
            if(!isset($uvschedulesarray["D" . $uvmarketeventsdate][$uvmarketeventvencode]))
                $uvschedulesarray["D" . $uvmarketeventsdate][$uvmarketeventvencode] = array();
            if(!isset($uvschedulesarray["D" . $uvmarketeventsdate][$uvmarketeventvencode][$uvmarketeventecozone]))
                $uvschedulesarray["D" . $uvmarketeventsdate][$uvmarketeventvencode][$uvmarketeventecozone] = array();

            $uvschedulesarray["D" . $uvmarketeventsdate][$uvmarketeventvencode][$uvmarketeventecozone]["event"] = $uvmarketevent;
            $uvschedulesarray["D" . $uvmarketeventsdate][$uvmarketeventvencode][$uvmarketeventecozone]["eventcode"] = $uvmarketevent["eventcode"];

        }
    }

    return array(
        "uv" => array(
            "data" => array(
                "schedules" => $uvschedulesarray
            ),
            "success" => array(
                "status" => "success"
            ),
        ),
    );
}

/*Process Market Events Flyer to Mimic regular flyers array
    Requires: marketeventflyers
    Returns: Array with flyers normal array (Flyers -> IMT -> array)
*/
function urvenue_ws_preprocess_market_flyers($uvmarketflyers){
    $uvflyers = "";

    if(is_array($uvmarketflyers) and is_array($uvmarketflyers["event"])){
        $uvflyers = array();
        foreach($uvmarketflyers["event"] as $uvmarketflyer){
            $uvtypecode = ($uvmarketflyer["typeid"]) ? "IMT" . $uvmarketflyer["typeid"] : "IMT0";
            $uvmarketflyer["path"] = $uvmarketflyer["folder"];

            if(!isset($uvflyers[$uvtypecode]))
                $uvflyers[$uvtypecode] = array();

            $uvflyers[$uvtypecode][] = $uvmarketflyer;
        }
    }

    return $uvflyers;
}

/*Process API data and returns array with events
    Requires: apidata(Raw data from API)
*/
function urvenue_ws_get_events_array($uvapidata, $uvargs = ""){
    global $urvenue_ws_core_lib;

    $uvevents = "";
    $uvvenueskeysbycode = urvenue_ws_get_venuekesybycode();

    if(is_array($uvapidata) and $uvapidata["uv"]["success"]["status"] == "success"){
		$uvschedules = $uvapidata["uv"]["data"]["schedules"];
        $uvinventory = (isset($uvapidata["uv"]["data"]["inventory"])) ? $uvapidata["uv"]["data"]["inventory"] : "";
        $uvevents = array();

        $uvnevents = urvenue_ws_get_arg($uvargs, "nevents", 200);

        if(is_array($uvschedules)){
            foreach($uvschedules as $uvschedulekey => $uvscheduleitem){
                $uvschedulekeydate = str_replace("D", "", $uvschedulekey);
                $uvschedulekeydate = (strlen($uvschedulekeydate) == 6) ? "20" . $uvschedulekeydate : $uvschedulekeydate;
                $uvscheduledate = DateTime::createFromFormat("Ymd", $uvschedulekeydate);
                $uvscheduledate = $uvscheduledate->format("Y-m-d");

                if(is_array($uvscheduleitem)){
                    foreach($uvscheduleitem as $uvscehdulevenuecode => $uvschedulevenue){
                        $uvscehduleveaid = str_replace("VEN", "", $uvscehdulevenuecode);
                        $uvvenueinfo = $uvapidata["uv"]["data"]["venues"][$uvscehdulevenuecode];
                        $uvvenueinfo = ($uvvenueinfo and is_array($uvvenueinfo)) ? urvenue_ws_get_venue_array($uvvenueinfo) : $uvvenueinfo;
                        $uveventfulladdress = $uvvenueinfo["venueaddress"];
                        $uvvenueaddress = (isset($uvvenueinfo["address"])) ? $uvvenueinfo["address"] : "";
                        $uvvenueprovince = (isset($uvvenueinfo["province"])) ? $uvvenueinfo["province"] : "";
                        $uvvenuezip = (isset($uvvenueinfo["zip"])) ? $uvvenueinfo["zip"] : "";
                        $uvvenueaddressdetails = array(
                            "address" => $uvvenueaddress,
                            "city" => $uvvenueinfo["city"],
                            "province" => $uvvenueprovince,
                            "zip" => $uvvenuezip,
                            "country" => $uvvenueinfo["country"]
                        );
                        $uv5venueid = (isset($uvvenueinfo["urvenueid"]) and $uvvenueinfo["urvenueid"]) ? $uvvenueinfo["urvenueid"] : "";
                        $uvvenuegmapurl = $uvvenueinfo["venuegmapurl"];
                        $uvvenuemanageentid = $uvvenueresellerid = $uvvenueprividerid = (isset($uvvenueinfo["manageentid"])) ? $uvvenueinfo["manageentid"] : "";
                        $uvvenuelogodarkbg = (isset($uvvenueinfo["venueimages"]["logodarkbg"])) ? $uvvenueinfo["venueimages"]["logodarkbg"] : "";
                        $uvvenuelogolightbg = (isset($uvvenueinfo["venueimages"]["logolightbg"]) ? $uvvenueinfo["venueimages"]["logolightbg"] : "");
                        $uvvenueurl = $uvvenueinfo["venue-url"];
                        $uvvenuemarketcode = $uvvenueinfo["marketareacode"];

                        if(is_array($uvschedulevenue)){
                            foreach($uvschedulevenue as $uvecozone => $uvscheduleeco){
                                $uvforcereturneventcode = urvenue_ws_get_arg($uvargs, "forcereturneventcode");

                                if($uvscheduleeco["status"] != "Closed"){
                                    if(isset($uvscheduleeco["event"]) and is_array($uvscheduleeco["event"]) and isset($uvscheduleeco["event"]["caldate"])){
                                        $uvperformername = $uvperformercode = "";
                            
                                        //Get Performer Name
                                        if(isset($uvscheduleeco["event"]["performers"]) and is_array($uvscheduleeco["event"]["performers"])){
                                            $uvfirstperformer = reset($uvscheduleeco["event"]["performers"]);
                                            $uvperformercode = $uvfirstperformer["perfcode"];

                                            if(isset($uvapidata["uv"]["data"]["performers"]) and is_array($uvapidata["uv"]["data"]["performers"]) and is_array($uvapidata["uv"]["data"]["performers"][$uvperformercode])){
                                                $uvperformername = $uvapidata["uv"]["data"]["performers"][$uvperformercode]["profiles"]["global"]["profile"]["name"];
                                            }
                                        }

                                        $uvevent = $uvscheduleeco["event"];
                                        $uvevent = urvenue_ws_normalize_event($uvevent);
                                        $uvevent["dstarttime"] = urvenue_ws_get_formattime($uvevent["nstarttime"]);
                                        $uvevent["venuecode"] = $uvscehdulevenuecode;
                                        $uvevent["venuekey"] = isset($uvvenueskeysbycode["$uvscehdulevenuecode"]) ? $uvvenueskeysbycode["$uvscehdulevenuecode"] : $uvscehdulevenuecode;
                                        $uvevent["venueaddress"] = $uveventfulladdress;
                                        $uvevent["venueaddressdetails"] = $uvvenueaddressdetails;
                                        $uvevent["venuegmapurl"] = $uvvenuegmapurl;
                                        $uvevent["performername"] = $uvperformername;
                                        $uvevent["uv5venueid"] = $uv5venueid;
                                        $uvevent["ecozone"] = $uvecozone;
                                        $uvevent["eventcode"] = $uvscheduleeco["eventcode"];
                                        $uvevent["maineventcode"] = $uvscheduleeco["maineventcode"];
                                        $uvevent["venue-marketareacode"] = $uvvenuemarketcode;
                                        $uvevent["flyers"] = urvenue_ws_get_event_flyersarray($uvevent["flyers"]);
                                        $uvevent["flyers"] = urvenue_ws_get_event_flyers($uvevent["flyers"]);
                                        $uvevent["venue-default-manageentid"] = $uvvenuemanageentid;
                                        $uvdefaultlink = urvenue_ws_get_event_url($uvevent, $urvenue_ws_core_lib["events"]["global-defaulteventurl"]);
                                        $uvevent["event-url"] = $uvdefaultlink;
                                        $uvevent["event-page-url"] = urvenue_ws_get_event_url($uvevent, "event");
                                        $uvevent["map-url"] = urvenue_ws_get_event_url($uvevent, "map");
                                        $uvevent["venue-default-resellerid"] = $uvvenueresellerid;
                                        $uvevent["venue-default-providerid"] = $uvvenueprividerid;
                                        $uvevent["venue-url"] = $uvvenueurl;

                                        if(isset($urvenue_ws_core_lib["events"]) and isset($urvenue_ws_core_lib["events"]["global-usevenuelogoasflyer"]) and $urvenue_ws_core_lib["events"]["global-usevenuelogoasflyer"]){
                                            $uvevent["venuelogodarkbg"] = $uvvenuelogodarkbg;
                                            $uvevent["venuelogolightbg"] = $uvvenuelogolightbg;
                                        }
                                        
                                        //add event to array
                                        $uvevents[$uvscheduleeco["eventcode"]] = $uvevent;

                                        //add stocks to events
                                        if(isset($urvenue_ws_core_lib["events"]) and isset($urvenue_ws_core_lib["system"]["include-stocks-on-events"]) and $urvenue_ws_core_lib["system"]["include-stocks-on-events"]){
                                            $uveventstocks = urvenue_ws_get_event_stocks($uvinventory, $uvscheduleeco["eventcode"]);
                                            $uvevents[$uvscheduleeco["eventcode"]]["stocks"] = $uveventstocks;
                                        }

                                        $uvnevents--;
                                    }
                                    else if($uvforcereturneventcode and $uvscheduleeco["eventcode"] == $uvforcereturneventcode){
                                        $uvevent = array(
                                            "name" => $uvscheduleeco["venuename"],
                                            "venuename" => $uvscheduleeco["venuename"],
                                            "date" => $uvscheduledate,
                                            "venuecode" => $uvscehdulevenuecode,
                                            "uv5venueid" => $uv5venueid,
                                            "venuekey" => isset($uvvenueskeysbycode["$uvscehdulevenuecode"]) ? $uvvenueskeysbycode["$uvscehdulevenuecode"] : $uvscehdulevenuecode,
                                            "venueaddress" => $uveventfulladdress,
                                            "venueaddressdetails" => $uvvenueaddressdetails,
                                            "venuegmapurl" => $uvvenuegmapurl,
                                            "ecozone" => $uvecozone,
                                            "eventcode" => $uvforcereturneventcode,
                                            "maineventcode" => $uvscheduleeco["maineventcode"],
                                            "isnoevent" => 1,
                                        );
                                        $uvevent["venue-marketareacode"] = $uvvenuemarketcode;
                                        $uvevent["venue-default-manageentid"] = $uvvenuemanageentid;
                                        $uvdefaultlink = urvenue_ws_get_event_url($uvevent, $urvenue_ws_core_lib["events"]["global-defaulteventurl"]);
                                        $uvevent["event-url"] = $uvdefaultlink;
                                        $uvevent["event-page-url"] = urvenue_ws_get_event_url($uvevent, "event");
                                        $uvevent["map-url"] = urvenue_ws_get_event_url($uvevent, "map");
                                        $uvevent["venue-default-resellerid"] = $uvvenueresellerid;
                                        $uvevent["venue-default-providerid"] = $uvvenueprividerid;
                                        $uvevent["venue-url"] = $uvvenueurl;

                                        if(isset($urvenue_ws_core_lib["events"]) and isset($urvenue_ws_core_lib["events"]["global-usevenuelogoasflyer"]) and $urvenue_ws_core_lib["events"]["global-usevenuelogoasflyer"]){
                                            $uvevent["venuelogodarkbg"] = $uvvenuelogodarkbg;
                                            $uvevent["venuelogolightbg"] = $uvvenuelogolightbg;
                                        }
                                        
                                        //add event to array
                                        $uvevents[$uvscheduleeco["eventcode"]] = $uvevent;

                                        $uvnevents--;
                                    }

                                    if(!$uvnevents) break;
                                }
                            }
                        }

                        if(!$uvnevents) break;
                    }
                }

                if(!$uvnevents) break;
            }
        }
    }

    return $uvevents;
}

/*Process API data and returns array of ecozones related with the event
    Requires: apidata(Raw data from API)
    Returns: array with ecozones in case it has related eczones, returns nothing if it doesn't have related ecozones
*/
function urvenue_ws_get_event_ecozonemap($uvapidata, $uveventcode){
    global $urvenue_ws_core_lib;

    $uvecozonesmap = "";
    $uvecozonemapunfl = array();

    if(is_array($uvapidata) and $uvapidata["uv"]["success"]["status"] == "success"){
		$uvschedules = $uvapidata["uv"]["data"]["schedules"];

        if(is_array($uvschedules)){
            $uveventdata = urvenue_ws_get_eventcode_data($uveventcode);
            // $uvsdate = date("ymd", strtotime($uveventdata["date"]));
            $uvsdate = gmdate("ymd", strtotime($uveventdata["date"]));

            //reroute to maineventcode in case the eventcode is different than maineventcode
            if(isset($uvschedules["D" . $uvsdate]) and isset($uvschedules["D" . $uvsdate][$uveventdata["venuecode"]]) and isset($uvschedules["D" . $uvsdate][$uveventdata["venuecode"]][$uveventdata["ecozone"]]) and isset($uvschedules["D" . $uvsdate][$uveventdata["venuecode"]][$uveventdata["ecozone"]]["eventcode"]) and isset($uvschedules["D" . $uvsdate][$uveventdata["venuecode"]][$uveventdata["ecozone"]]["maineventcode"]) and ($uvschedules["D" . $uvsdate][$uveventdata["venuecode"]][$uveventdata["ecozone"]]["eventcode"] != $uvschedules["D" . $uvsdate][$uveventdata["venuecode"]][$uveventdata["ecozone"]]["maineventcode"])){
                $uveventcode = $uvschedules["D" . $uvsdate][$uveventdata["venuecode"]][$uveventdata["ecozone"]]["maineventcode"];
            }

            foreach($uvschedules as $uvschedulekey => $uvscheduleitem){
                $uvschedulekeydate = str_replace("D", "", $uvschedulekey);
                $uvschedulekeydate = (strlen($uvschedulekeydate) == 6) ? "20" . $uvschedulekeydate : $uvschedulekeydate;
                $uvscheduledate = DateTime::createFromFormat("Ymd", $uvschedulekeydate);
                $uvscheduledate = $uvscheduledate->format("Y-m-d");

                if(is_array($uvscheduleitem)){
                    foreach($uvscheduleitem as $uvscehdulevenuecode => $uvschedulevenue){
                        if(is_array($uvschedulevenue)){
                            foreach($uvschedulevenue as $uvecozone => $uvscheduleeco){
                                if(($uvscheduleeco["maineventcode"] == $uveventcode) and (isset($uvscheduleeco["items"]))){
                                    $uvecozonemapunfl[$uvecozone] = $uvscheduleeco;
                                    $uvecozonemapunfl[$uvecozone]["venuecode"] = $uvscehdulevenuecode;
                                    $uvecozonemapunfl[$uvecozone]["ecozone"] = $uvecozone;
                                    $uvecozonemapunfl[$uvecozone]["date"] = $uvscheduledate;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    if(count($uvecozonemapunfl) == 1){
        $uvecofirst = reset($uvecozonemapunfl);
        if($uvecofirst["maineventcode"] == $uvecofirst["eventcode"])
            $uvecozonemapunfl = array();
    }

    if(count($uvecozonemapunfl))
        $uvecozonesmap = $uvecozonemapunfl;

    return $uvecozonesmap;
}

/*Get the booktypes array from eventcode
    Requires: inventory (inventory array from inventorylist with filters=data:events), eventcode
    Returns: array with booktypes for the event
*/
function urvenue_ws_get_event_stocks($uvinventory, $uveventcode){
    $uveventstocks = "";

    
    if(is_array($uvinventory) and $uveventcode){
        $uveventdata = urvenue_ws_get_eventcode_data($uveventcode);
        $uveventecozone = urvenue_ws_standardize_ecozone($uveventdata["ecozone"]);
        // $uvsdate = date("ymd", strtotime($uveventdata["date"]));
        $uvsdate = gmdate("ymd", strtotime($uveventdata["date"]));

        if(isset($uvinventory["D" . $uvsdate]) and isset($uvinventory["D" . $uvsdate]["venues"][$uveventdata["venuecode"]]) and isset($uvinventory["D" . $uvsdate]["venues"][$uveventdata["venuecode"]]["ecozones"][$uveventecozone]) and isset($uvinventory["D" . $uvsdate]["venues"][$uveventdata["venuecode"]]["ecozones"][$uveventecozone]["booktypes"]))
            $uveventstocks = $uvinventory["D" . $uvsdate]["venues"][$uveventdata["venuecode"]]["ecozones"][$uveventecozone]["booktypes"];
    }

    return $uveventstocks;
}

/*Get event url
    Requires: event(event data array)
    Optional: linkcode(event or map, depending on what link is required)
*/
function urvenue_ws_get_event_url($uvevent, $uvlinkcode = "event"){
    global $urvenue_ws_core_lib, $urvenue_ws_config_customeventurl, $urvenue_ws_config_manageentid, $urvenue_ws_config_custommapurl, $urvenue_ws_config_microcode;

    $uvlinkcode = ($uvlinkcode == "event") ? "singleevent" : $uvlinkcode;
    $uvbaseurl = (urvenue_ws_is_wordpress()) ? get_permalink($urvenue_ws_core_lib["pages"][$uvlinkcode]) : $urvenue_ws_core_lib["pages"][$uvlinkcode];
    $uvbaseurl = $uvbaseurl . "{eventcode}/{eventnameurl}/";
    
    // Ensure trailing slash
    if (substr($uvbaseurl, -1) !== '/') $uvbaseurl .= '/';
    
    $uvmanageentid = ($urvenue_ws_config_manageentid) ? $urvenue_ws_config_manageentid : (isset($uvevent["venue-default-manageentid"]) ? $uvevent["venue-default-manageentid"] : "");
    
    if($urvenue_ws_config_customeventurl and $uvlinkcode == "singleevent")
        $uvbaseurl = $urvenue_ws_config_customeventurl;
    else if($urvenue_ws_config_custommapurl and $uvlinkcode == "map")
        $uvbaseurl = $urvenue_ws_config_custommapurl;

    //Check if there are event pages map
    if(urvenue_ws_is_wordpress() and is_array($uvevent) and isset($uvevent["venuecode"]) and isset($urvenue_ws_core_lib["eventpagesmap"]) and isset($urvenue_ws_core_lib["eventpagesmap"][$uvevent["venuecode"]]) and isset($urvenue_ws_core_lib["eventpagesmap"][$uvevent["venuecode"]][$uvlinkcode])){
        $uvbaseurl = get_permalink($urvenue_ws_core_lib["eventpagesmap"][$uvevent["venuecode"]][$uvlinkcode]);
        $uvbaseurl = $uvbaseurl . "{eventcode}/{eventnameurl}/";
    }

    if(urvenue_ws_is_wordpress() and is_array($uvevent) and isset($urvenue_ws_core_lib["eventpagesmap"]) and isset($urvenue_ws_core_lib["eventpagesmap"]["langs"]) and isset($urvenue_ws_core_lib["eventpagesmap"]["langs"][urvenue_ws_get_cur_lang()]) and isset($urvenue_ws_core_lib["eventpagesmap"]["langs"][urvenue_ws_get_cur_lang()][$uvlinkcode])){
        $uvbaseurl = get_permalink($urvenue_ws_core_lib["eventpagesmap"]["langs"][urvenue_ws_get_cur_lang()][$uvlinkcode]);
        $uvbaseurl = $uvbaseurl . "{eventcode}/{eventnameurl}/";
    }

    $uveventurl = "#";

    if(is_array($uvevent)){
        $uveventnameurl = urvenue_ws_get_linkstring($uvevent["name"]);
        $uveventvenuenameurl = urvenue_ws_get_linkstring($uvevent["venuename"]);
        $uvvenueid = str_replace("VEN", "", $uvevent["venuecode"]);
        $uv5venueid = (isset($uvevent["uv5venueid"])) ? $uvevent["uv5venueid"] : "";
        // $uvssdate = date("ymd", strtotime($uvevent["date"]));
        $uvssdate = gmdate("ymd", strtotime($uvevent["date"]));

        $uveventurl = str_replace(
            array(
                "{eventcode}",
                "{eventnameurl}",
                "{venueid}",
                "{manageentid}",
                "{uv5venueid}",
                "{ssdate}",
                "{microcode}",
                "{eventvenuenameurl}",
                "{venuecode}"
            ),
            array(
                $uvevent["eventcode"],
                $uveventnameurl,
                $uvvenueid,
                $uvmanageentid,
                $uv5venueid,
                $uvssdate,
                $urvenue_ws_config_microcode,
                $uveventvenuenameurl,
                $uvevent["venuecode"],
            ),
            $uvbaseurl);
    }

    $uveventurl = urvenue_ws_apply_filters("urvenue_ws_event_before_return_url", $uveventurl, $uvevent);

    return $uveventurl;
}

/*Get event flyers for different places
    Requires: flyers(plain flyers array)
*/
function urvenue_ws_get_event_flyers($uvflyers){
    global $urvenue_ws_core_lib;

    $uvflyersreturn = "";

    if(is_array($urvenue_ws_core_lib["flyers"])){
        $uvflyersreturn = array();

        foreach($urvenue_ws_core_lib["flyers"] as $uvflyerloccode => $uvflyerprior){
            $uvlocflyer = "";

            if(is_array($uvflyerprior)){
                $uvthishideifnomatch = $urvenue_ws_core_lib["flyers"][$uvflyerloccode . "-hideifnomatch"];
                $uvthisuseplaceholder = $urvenue_ws_core_lib["flyers"][$uvflyerloccode . "-useplaceholder"];
                $uvthisplaceholcerurl = $urvenue_ws_core_lib["flyers"][$uvflyerloccode . "-placeholderurl"];
                $uvthissizecode = $urvenue_ws_core_lib["flyers"][$uvflyerloccode . "-sizecode"];

                $uvlocflyer = urvenue_ws_get_flyersbypriority($uvflyers, $uvflyerprior, $uvthishideifnomatch);

                if(is_array($uvlocflyer)){
                    $uvflyerurl = $uvlocflyer["path"] . "/$uvthissizecode/" . $uvlocflyer["file"];
                    $uvflyerfull = $uvlocflyer["path"] . "/raw/" . $uvlocflyer["file"];
                    $uvflyerurlcode = $uvlocflyer["path"] . "/{sizecode}/" . $uvlocflyer["file"];

                    $uvlocflyer["url"] = $uvflyerurl;
                    $uvlocflyer["full"] = $uvflyerfull;
                    $uvlocflyer["urlcode"] = $uvflyerurlcode;
                }

                if(!is_array($uvlocflyer) and $uvthisuseplaceholder and ($uvthisplaceholcerurl or $urvenue_ws_core_lib["flyers"]["placeholderurl"])){
                    $uvthisplaceholder = ($uvthisplaceholcerurl) ? $uvthisplaceholcerurl : $urvenue_ws_core_lib["flyers"]["placeholderurl"];

                    $uvlocflyer = array(
                        "url" => $uvthisplaceholder,
                        "full" => $uvthisplaceholder,
                        "urlcode" => $uvthisplaceholder,
                        "ratio" => "placeholder",
                    );
                }
                else if(!is_array($uvlocflyer)){
                    $uvlocflyer = array(
                        "url" => "",
                        "full" => "",
                        "urlcode" => "",
                        "ratio" => "noflyer",
                    );
                }
            }

            if(is_array($uvlocflyer)){
                $uvflyersreturn[$uvflyerloccode] = $uvlocflyer;
            }
        }
    }

    return $uvflyersreturn;
}

/*Compare Flyer priorities and flyers array
    Requires: flyers(plain flyers array), flyerprior(array with flyers priority), forcematch(1 if the flyer must match), returnmultiple(1 if multiple flyers should be returned)
*/
function urvenue_ws_get_flyersbypriority($uvflyers, $uvflyerprior, $uvforcematch = 0, $uvreturnmultiple = 0){
    global $urvenue_ws_config_flyercode;
    
    $uvflyer = "";

    //Add flyer to the priority array if match
    if(is_array($uvflyers) and is_array($uvflyerprior)){
        foreach($uvflyerprior as $uvflyerprkey => $uvflyerpr){
            $uvtheflyerpr = "";
            $uvtheflyerprarray = array();

            foreach($uvflyers as $uvthisflyer){
                $uvflyerpr["bgtype"] = (isset($uvflyerpr["bgtype"])) ? $uvflyerpr["bgtype"] : "any";
                $uvthisflyerbgtype = (isset($uvthisflyer)) ? $uvthisflyer["bgtype"] : "any";

                if(($urvenue_ws_config_flyercode and $uvthisflyer["imagetypename"] == $urvenue_ws_config_flyercode) || ($uvthisflyer["imagerationame"] == $uvflyerpr["ratio"] or $uvflyerpr["ratio"] == "any") and ($uvthisflyer["imagetypename"] == $uvflyerpr["type"]) and ($uvthisflyerbgtype == $uvflyerpr["bgtype"] or $uvflyerpr["bgtype"] == "any")){
                    $uvtheflyerpr = array(
                        "path" => $uvthisflyer["path"],
                        "file" => $uvthisflyer["file"],
                        "ratio" => $uvthisflyer["imagerationame"],
                        "type" => $uvthisflyer["imagetypename"],
                        "bgtype" => $uvthisflyer["bgtype"],
                    );

                    if($uvreturnmultiple){
                        $uvtheflyerprarray[] = $uvtheflyerpr;
                    }
                    else{
                        $uvtheflyerprarray = $uvtheflyerpr;
                        break;
                    }
                }
            }

            if($uvtheflyerpr)
                $uvflyerprior[$uvflyerprkey]["flyer"] = $uvtheflyerprarray;
        }
    }
    
    //Get the first flyer that matches
    if(is_array($uvflyerprior)){
        foreach($uvflyerprior as $uvflyerpr){
            if(isset($uvflyerpr["flyer"]) and is_array($uvflyerpr["flyer"])){
                if($uvreturnmultiple){
                    if(!is_array($uvflyer)) $uvflyer = array();
                    $uvflyer = array_merge($uvflyer, $uvflyerpr["flyer"]);
                }
                else{
                    $uvflyer = $uvflyerpr["flyer"];
                    break;
                }
            }
        }
    }

    //Return first flyer if match is not required
    if(!$uvforcematch and !$uvflyer and is_array($uvflyers) and count($uvflyers)){
        $uvflyer = array(
            "path" => $uvflyers[0]["path"],
            "file" => $uvflyers[0]["file"],
            "ratio" => $uvflyers[0]["imagerationame"],
            "type" => $uvflyers[0]["imagetypename"],
            "bgtype" => $uvflyers[0]["bgtype"],
        );
    }

    return $uvflyer;
}

/*Get plain flyers array
    Requires: flyers(Raw event flyers array)
*/
function urvenue_ws_get_event_flyersarray($uvflyers){
	if(is_array($uvflyers)){
		$uvfluyersarray = array();
		foreach($uvflyers as $uvflyertypekey => $uvflyertypearray){
			if(is_array($uvflyertypearray)){
				foreach($uvflyertypearray as $uvflyeritem){
					$uvfluyersarray[] = $uvflyeritem;
				}
			}
		}
	}
	else
		$uvfluyersarray = "";

	return $uvfluyersarray;
}

/*Get Dropdown of events for a date and venue
    Requires: eventdata(date, ecozone, venuecode)
*/
function urvenue_ws_get_date_events_dropdown($uveventdata){
    $uveventsdropdown = "";

    if(is_array($uveventdata) and isset($uveventdata["date"]) and isset($uveventdata["ecozone"]) and isset($uveventdata["venuecode"])){
        $uvselecozone = urvenue_ws_standardize_ecozone($uveventdata["ecozone"]);
        $uvterms = array(
            "fromdate" => $uveventdata["date"],
            "todate" => $uveventdata["date"],
            "venuecodes" => $uveventdata["venuecode"],
        );
        $uvdateevents = urvenue_ws_get_events($uvterms);

        $uvdateeventsarray = array();
        if(is_array($uvdateevents)){
            foreach($uvdateevents as $uvdateevent){
                $uvdateeventecozone = urvenue_ws_standardize_ecozone($uvdateevent["ecozone"]);
                $uvdateeventsarray[$uvdateeventecozone] = array(
                    "name" => $uvdateevent["name"],
                    "eventcode" => $uvdateevent["eventcode"],
                    "ecozone" => $uvdateeventecozone,
                    "date" => $uvdateevent["date"],
                    "venuecode" => $uvdateevent["venuecode"],
                );

            }
        }

        if(count($uvdateeventsarray) > 1){
            $uveventslis = "";
            $uvselecozonename = "Select Event";
            foreach($uvdateeventsarray as $uvdateeventecozone => $uvdateevent){
                $uveventslis .= "<li><button class='uwsjs-inventorywidget-selectevent' aria-label='Select " . $uvdateevent["name"] . "' type='button' data-eventcode='" . $uvdateevent["eventcode"] . "' data-ecozone='" . $uvdateevent["ecozone"] . "' data-date='" . $uvdateevent["date"] . "' data-venuecode='" . $uvdateevent["venuecode"] . "'>" . $uvdateevent["name"] . "</button></li>";

                if($uvselecozone == $uvdateeventecozone){
                    $uvselecozonename = $uvdateevent["name"];
                }
            }

            $uveventsdropdown = "
                <div class='uws-dropdown-cont'>
                    <a href='#uws-openeventselection' class='uwsjs-trigger-dropdown' aria-label='Select Event'><span class='uwsdy-dropvalue'>$uvselecozonename</span></a>
                    <div class='uws-dropdown'>
                        <ul>
                            $uveventslis
                        </ul>
                    </div>
                </div>
            ";
        }
    }

    return $uveventsdropdown;
}

/*Normalize event array, removes all unused variables
    Requires: event(Raw data event array)
*/
function urvenue_ws_normalize_event($uvevent){
    $uveventreturn = "";

    if(is_array($uvevent)){
        $uveventreturn = $uvevent;
        
        if(isset($uveventreturn["caldate"]))
            $uveventreturn["date"] = $uveventreturn["caldate"];

        unset($uveventreturn["roomid"]);
        unset($uveventreturn["caldate"]);
        unset($uveventreturn["private"]);
        unset($uveventreturn["eventid"]);
        unset($uveventreturn["eventtypeid"]);
        //unset($uveventreturn["performers"]);
        //unset($uveventreturn["ecozones"]);
    }

    return $uveventreturn;
}

/*Get venues codes in an string to use as API parameter
    Optional: venuekey(key that identifies the venue)
*/
function urvenue_ws_get_venuecodes_string($uvvenuekey = "all"){
    global $urvenue_ws_core_lib;

    $uvvenuescodesstring = "";

    if(isset($urvenue_ws_core_lib["venues"]) and is_array($urvenue_ws_core_lib["venues"])){
        if($uvvenuekey and $uvvenuekey != "all" and isset($urvenue_ws_core_lib["venues"][$uvvenuekey])){//is uniq venue
            //if(!$urvenue_ws_core_lib["venues"][$uvvenuekey]["venuehideinevents"]){
                $uvvenuescodesstring = $urvenue_ws_core_lib["venues"][$uvvenuekey]["venuecode"];
            //}
        }
        else if(strpos($uvvenuekey, ",") !== false){//List more than 1 specific venues
            $uvvenueskeys = explode(",", $uvvenuekey);
            foreach($uvvenueskeys as $uvthisvenuekey){
                if(isset($urvenue_ws_core_lib["venues"][$uvthisvenuekey])){
                    $uvvenuescodesstring .= $urvenue_ws_core_lib["venues"][$uvthisvenuekey]["venuecode"] . ",";
                }
            }
            $uvvenuescodesstring = rtrim($uvvenuescodesstring, ',');
        }
        else if($uvvenuekey == "all"){//list all venues
            foreach($urvenue_ws_core_lib["venues"] as $uvvenue){
                if(!isset($uvvenue["venuehideinevents"]) or !$uvvenue["venuehideinevents"]){
                    $uvvenuescodesstring .= $uvvenue["venuecode"] . ",";
                }
            }

            $uvvenuescodesstring = rtrim($uvvenuescodesstring, ',');
        }
    }

    return $uvvenuescodesstring;
}

/*Get venues keys in an array by venuecode
*/
function urvenue_ws_get_venuekesybycode(){
    global $urvenue_ws_core_lib;

    if(isset($urvenue_ws_core_lib["venues"]) and is_array($urvenue_ws_core_lib["venues"])){
        foreach($urvenue_ws_core_lib["venues"] as $key => $uvvenue){
            $uvvenuecode=$uvvenue["venuecode"];
            $uvvenueskeysbycode["$uvvenuecode"] = $key;
        }
    }

    return $uvvenueskeysbycode;
}

/*Get primary venue
    Returns: array with primary venue from library
*/
function urvenue_ws_get_primary_venue(){
    global $urvenue_ws_core_lib;

    $uvprimaryvenue = "";

    if(is_array($urvenue_ws_core_lib) and is_array($urvenue_ws_core_lib["venues"])){
        foreach($urvenue_ws_core_lib["venues"] as $uvvenue){
            if($uvvenue["isprimary"]){
                $uvprimaryvenue = $uvvenue;
                break;
            }
        }

        // If only one venue exists, set as primary
        if (count($urvenue_ws_core_lib["venues"]) === 1 && empty($uvprimaryvenue)) {
            $uvprimaryvenue = reset($urvenue_ws_core_lib["venues"]);
        }
    }

    return $uvprimaryvenue;
}

/*Get event implementation
    Returns: Prints html of event page + inventory
*/
function urvenue_ws_event($uvargs = ""){
    global $urvenue_ws_core_lib;

    $uveventhtml = $uveventinfo = "";
    $uveventcode = urvenue_ws_get_arg($uvargs, "eventcode", urvenue_ws_get_eventcode());
    $uvgetvenuecode = (isset($_REQUEST["venuecode"])) ? urvenue_ws_cleanup_var( sanitize_text_field( wp_unslash( $_REQUEST["venuecode"] ) ) ) : ""; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only URL param for event display filtering, no state change

    if(isset($urvenue_ws_core_lib["system"]) and isset($urvenue_ws_core_lib["system"]["use-market-events"]) and isset($urvenue_ws_core_lib["system"]["use-market-eventsvenues"]) and !$urvenue_ws_core_lib["system"]["use-market-eventsvenues"] and $urvenue_ws_core_lib["system"]["use-market-events"] and isset($urvenue_ws_core_lib["events"]["market-events-venueid"]) and $urvenue_ws_core_lib["events"]["market-events-venueid"] and $uvgetvenuecode){
        $uveventcodedata = urvenue_ws_get_eventcode_data($uveventcode);
        
        if(is_array($uveventcodedata)){
            $uveventcodedata["venuecode"] = $uvgetvenuecode;
            $uveventcode = "EVE" . str_replace("VEN", "", $uvgetvenuecode) . str_replace("ECZ", "", $uveventcodedata["ecozone"]) . gmdate("Ymd", strtotime($uveventcodedata["date"]));
        }
    }

    if(isset($urvenue_ws_core_lib["system"]) and isset($urvenue_ws_core_lib["system"]["use-market-events"]) and $urvenue_ws_core_lib["system"]["use-market-events"] and isset($urvenue_ws_core_lib["events"]["market-events-venueid"]) and $urvenue_ws_core_lib["events"]["market-events-venueid"] and !$uvgetvenuecode){
        $uveventinfo = "iseventvenues";
        $uvusemarket = 0;

        if(isset($urvenue_ws_core_lib["system"]["use-market-eventsvenues"]) and $urvenue_ws_core_lib["system"]["use-market-eventsvenues"])
            $uvusemarket = 1;
    }
    else{
        $uveventinfo = ($uveventcode) ? urvenue_ws_get_event($uveventcode) : "";
    }

    if(is_array($uveventinfo)){
        $uveventschemainline = "";
        $uvaddschema = (urvenue_ws_get_arg($uvargs, "template")) ? 0 : 1;
        $uvgeteventschema = ($uvaddschema) ? urvenue_ws_get_eventschema($uveventinfo) : "";

        $uveventlayout = $urvenue_ws_core_lib["events"]["event-layout"]; //full-header or container
        $uveventscolumns = $urvenue_ws_core_lib["events"]["event-columns"]; //inventory-flyer or flyer-inventory
        $uvtemplatename = urvenue_ws_get_arg($uvargs, "template", "event/event-" . $uveventlayout);

        $uveventtemple = urvenue_ws_get_template($uvtemplatename);
        $uveventconthtml = urvenue_ws_replace_event_vars($uveventinfo, $uveventtemple);
        $uveventinventoryblock = "<div class='uwsjs-loadeventinventory uws-event-inventory' data-eventcode='$uveventcode'><div class='uws-integration uws-inventory-stage uwsdy-cartactive-class uws-inventory-stage-1 uwsloading' data-instance='1'><div class='uws-inventoryloader'><div class='uwsloadingmsg'><div class='uws-loader-uvicon'></div><div class='uwsloadingtxt'>Loading Experiences...</div></div><div class='uwsloadingbkt'></div><div class='uwsloadingbitem'></div><div class='uwsloadingbitem'></div><div class='uwsloadingbkt'></div></div><div class='uws-inventory-load'></div></div></div>";
        $uveventconthtml = str_replace("{eventinventoryblock}", $uveventinventoryblock, $uveventconthtml);
        

        if($uvgeteventschema){
            // @Axl
            // $uvgeteventschemajson = json_encode($uvgeteventschema);
            $uvgeteventschemajson = wp_json_encode($uvgeteventschema);
            // @Axl End
	        $uveventschemainline .= "";

            add_action('wp_footer', function () use ($uvgeteventschemajson) {
                wp_print_inline_script_tag( $uvgeteventschemajson, array( 'type' => 'application/ld+json' ) );
            });
        }

        $uveventhtml = "
        <div class='uws-integration uws-event uws-event-$uveventcode uws-event-layout-$uveventlayout uws-event-columns-$uveventscolumns'>
            $uveventconthtml
            $uveventschemainline
        </div>
        ";
    }
    else if($uveventinfo == "iseventvenues" and $uveventcode){
        $uvfeedtouse = ($uvusemarket) ? "marketeventvenues" : "eventvenues";
        $uvterms = array(
            "eventcode" => $uveventcode,
        );
        $uveventcodedata = urvenue_ws_get_eventcode_data($uveventcode);
        $uveventvenuesfeed = urvenue_ws_get_feed($uvfeedtouse, $uvterms);

        $uvargs = array(
            "forcereturneventcode" => $uveventcode,
        );
        $uveventarray = urvenue_ws_get_events_array($uveventvenuesfeed, $uvargs);
        $uvvenuesarray = "";

        if(isset($uveventvenuesfeed["uv"]["data"]["venues"])){
            $uvvenuesarray = array();

            if(is_array($uveventvenuesfeed["uv"]["data"]["venues"])){
                foreach($uveventvenuesfeed["uv"]["data"]["venues"] as $uvvenuecode => $uvvenue){
                    $uvvenuearray = urvenue_ws_get_venue_array($uvvenue);

                    $uvvenuesarray[$uvvenuecode] = $uvvenuearray;
                }
            }
        }

        $uveventinfo = array(
            "iseventvenues" => 1,
            "event" => $uveventarray,
            "venues" => $uvvenuesarray,
        );
    }

    $uveventhtml = urvenue_ws_apply_filters("urvenue_ws_event_page_after_replace", $uveventhtml, $uveventinfo);

    // echo $uveventhtml;
    echo wp_kses_post( $uveventhtml );
}

/*Get event schema
    Requires: Event info array
    Returns: Array with event data schema markup
*/
function urvenue_ws_get_eventschema($uvevent = ""){
    global $urvenue_ws_today;

    $uveventschema = "";

    if(is_array($uvevent)){
        $uvschemavenueaddress = array(
            "@type" => "PostalAddress",
            "streetAddress" => $uvevent["venueaddressdetails"]["address"],
            "addressLocality" => $uvevent["venueaddressdetails"]["city"],
            "addressRegion" => $uvevent["venueaddressdetails"]["province"],
            "postalCode" => $uvevent["venueaddressdetails"]["zip"],
            "addressCountry" => $uvevent["venueaddressdetails"]["country"]
        );

        //YY-MM-DDTHH:MM:SS Not recommeded by google, if no timezone we can't add time

        $uveventschema = array(
            "@context" => "http://schema.org",
            "@type" => "Event",
            "eventAttendanceMode" => "https://schema.org/OfflineEventAttendanceMode",
            "eventStatus" => "https://schema.org/EventScheduled",
            "name" => $uvevent["name"],
            "startDate" => $uvevent["date"],
            "endDate" => $uvevent["date"],
            "doorTime" => $uvevent["date"],
            "description" => $uvevent["descr"],
            "location" => array(
                "@type" => "Place",
                "name" => $uvevent["venuename"],
                "address" => $uvschemavenueaddress
            ),
            "organizer" => array(
                "@type" => "Organization",
                "name" => $uvevent["venuename"],
                "address" => $uvschemavenueaddress,
            ),
            /*"offers" => array(
                "@type" => "Offer",
                "url" => $uvevent["event-url"],
                "price" => "",
                "priceCurrency" => $uvevent["eventcurrency"],
                "availability" => "http://schema.org/InStock",
                "validFrom" => $urvenue_ws_today,
                "validThrough" => $uvstarttime,
                "seller" => array(
                    "@type" => "Organization",
                    "name" => "UrVenue"
                )
            )*/
        );

        if(isset($uvevent["flyers"]["share"]) and is_array($uvevent["flyers"]["share"]) and $uvevent["flyers"]["share"]["url"])
            $uveventschema["image"] = array(
                $uvevent["flyers"]["share"]["url"]
            );
        
        if(isset($uvevent["performername"]) and $uvevent["performername"])
            $uveventschema["performer"] = array(
                "@type" => "PerformingGroup",
                "name" => $uvevent["performername"]
            );
        //if($uvevent["ndoorsopen"])
    }

    $uveventschema = urvenue_ws_apply_filters("urvenue_ws_event_before_return_schema", $uvevent, $uveventschema);

    return $uveventschema;
}

/**
 * Generates an array of event schemas from a given array of events.
 *
 * @param array $uvevents An array of events.
 * @return array|string An array of event schemas if input is an array, otherwise an empty string.
 */
function urvenue_ws_get_events_schema($uvevents){
    $uveventsschema = "";

    if(is_array($uvevents)){
        $uveventsschema = array();

        foreach($uvevents as $uvevent){
            $uveventschema = urvenue_ws_get_eventschema($uvevent);
            $uveventsschema[] = $uveventschema;
        }
    }

    return $uveventsschema;
}

/*Get event SEO info
    Returns: Array with seo metatags
*/
function urvenue_ws_get_event_seo(){
    global $urvenue_ws_core_lib;

    $uveventseo = "";

    $uveventcode = urvenue_ws_get_eventcode();
    $uveventinfo = ($uveventcode) ? urvenue_ws_get_event($uveventcode) : "";

    if(is_array($uveventinfo)){
        $uvseotitle = urvenue_ws_replace_event_vars($uveventinfo, $urvenue_ws_core_lib["seo"]["seotitle"]);
        $uvsitetitle = urvenue_ws_get_site_title() ? urvenue_ws_get_site_title() : $uveventinfo["venuename"];
        $uvseotitle = str_replace("{sitetitle}", $uvsitetitle, $uvseotitle);
        $uvseodescription = ($uveventinfo["shortdescr"]) ? wp_strip_all_tags($uveventinfo["shortdescr"]) : (($uveventinfo["descr"]) ? wp_strip_all_tags($uveventinfo["descr"]) : wp_strip_all_tags(urvenue_ws_replace_event_vars($uveventinfo, $urvenue_ws_core_lib["seo"]["seodescription"])));

        $uveventseo = array(
            "title" => $uvseotitle,
            "description" => $uvseodescription,
            "url" => $uveventinfo["event-url"],
            "image" => $uveventinfo["flyers"]["share"]["url"],
        );   
    }

    return $uveventseo;
}

/*Get site title
    Returns: Site title if it's wordpress
*/
function urvenue_ws_get_site_title(){
    $uvsitetitle = "";

    if(urvenue_ws_is_wordpress())
        $uvsitetitle = get_bloginfo( 'name' );

    return $uvsitetitle;
}

/*Get eventcode from url vars
    Returns: eventcode
*/
function urvenue_ws_get_eventcode(){
    $uveventcode = "";

    if(urvenue_ws_is_wordpress())
        $uveventcode = get_query_var('eventcode');
    else{
        global $urvenue_ws_eventcode;

        $uveventcode = $urvenue_ws_eventcode;
    }
        

    return $uveventcode;
}

/*
    Get eventcode from the next event in the list
    requires: venuename
    returns: eventcode
*/
function urvenue_ws_get_next_eventcode($uvvenuekey){
    $uveventcode = "";

    $uvargs = array(
        "venue" => $uvvenuekey,
        "fromdate" => gmdate("Y-m-d"),
        "nevents" => 10
    );
    $uvevents = urvenue_ws_get_events($uvargs);
    $uvevent = reset($uvevents);

    if(is_array($uvevent))
        $uveventcode = $uvevent["eventcode"];

    return $uveventcode;
}

/*Get performers codes in an string to use as class
    Returns: performers class
*/
function urvenue_ws_get_performersclass($uvperformers){
    $uvperformersclass = "";

    if(is_array($uvperformers)){
        foreach($uvperformers as $uvperformer){
            $uvperformersclass .= "uws-performer-" . $uvperformer["perfcode"] . " ";
        }
    }

    return $uvperformersclass;
}

/*Get performers list for calendar controls
    Returns: performers list
*/
function urvenue_ws_get_performerlis($uvargs = ""){
    $uvperformerlist = "";
    $uvvenuekeycode = urvenue_ws_get_arg($uvargs, "venue", "all");
    $uvvenuesinfilter = (urvenue_ws_get_arg($uvargs, "venuesinfilter", "")) ? urvenue_ws_get_arg($uvargs, "venuesinfilter", "") : $uvvenuekeycode;
    $urvenue_ws_venuecodes = urvenue_ws_get_arg($uvargs, "venuecodes", "");
    $uvvenuescodesstring = ($urvenue_ws_venuecodes) ? $urvenue_ws_venuecodes : urvenue_ws_get_venuecodes_string($uvvenuesinfilter);
    $uvonlyevents = urvenue_ws_get_arg($uvargs, "onlyevents", 0);

    $uvdate = urvenue_ws_get_arg($uvargs, "date", "");
    if($uvdate){
        $uvfromdate = urvenue_ws_get_arg($uvargs, "fromdate", $uvdate);
        $uvmaxdate = urvenue_ws_get_arg($uvargs, "todate", urvenue_ws_get_events_endinit_date("Y-m-d", $uvdate));
    }
    else{
        $uvfromdate = urvenue_ws_get_arg($uvargs, "fromdate", urvenue_ws_get_events_initial_date("Y-m-d"));
        $uvmaxdate = urvenue_ws_get_arg($uvargs, "todate", urvenue_ws_get_events_endinit_date("Y-m-d", $uvfromdate));
    }

    // $uvtodate = date("Y-m-d", strtotime($uvmaxdate . " +7 days"));
    $uvtodate = gmdate("Y-m-d", strtotime($uvmaxdate . " +7 days"));

    $uvterms = array(
        "venuecode" => $uvvenuescodesstring,
        "caldate" => $uvfromdate,
        "todate" => $uvtodate,
    );
    $uveventperformerfeed = urvenue_ws_get_feed("inventory-eventsonly", $uvterms);
    $uveventperformers = (isset($uveventperformerfeed["uv"]["data"]["performers"])) ? urvenue_ws_sort_performers($uveventperformerfeed["uv"]["data"]["performers"]) : '';

    if(is_array($uveventperformers)){
        foreach($uveventperformers as $uveventperformer){
            $uvperformerprofile = $uveventperformer["profiles"];
            $uvperformerprofile = reset($uvperformerprofile);
            $uvprofile = $uvperformerprofile["profile"];
            $uvprofilename = $uvprofile["name"];
            $uvprofilecode = $uvprofile["code"];

            $uvincludethisartist = 1;
            if($uvonlyevents and !isset($uveventperformer["shows"]))
                $uvincludethisartist = 0;

            if($uvincludethisartist)
                $uvperformerlist .= " <li>
                    <button class='uwsjs-events-selectperformer' aria-label='Select $uvprofilename Artists' type='button' data-performer='$uvprofilename' data-performercode='$uvprofilecode'>
                        $uvprofilename
                    </button>
                </li>";
        
        }
    }

    return $uvperformerlist;
}

/**
 * Sorts the performers of a UV event.
 *
 * This function takes an array of performers and sorts them based on the name of their first profile.
 *
 * @param array $uveventperformers The array of performers.
 * @return array The sorted array of performers.
 */
function urvenue_ws_sort_performers($uveventperformers){
    $uvperformers = array();

    foreach($uveventperformers as $key => $row){
        $firstKey = array_key_first($row["profiles"]);
        $uvperformers[$key] = $row["profiles"][$firstKey]["profile"]["name"];
    }
    array_multisort($uvperformers, SORT_ASC, $uveventperformers);

    return $uveventperformers;
}