<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*Get Calendar implementation
    Returns: Prints html controls + events integration
*/
function uws_events(){
    global $uws_core_lib, $uws_today;

    $uvinitialdate = uws_get_events_initial_date("Y-m-d");
    $uvenddate = uws_get_events_endinit_date("Y-m-d");
    $uvmaxdate = uws_get_events_max_date("Y-m-d");

    $uveventsactions = uws_events_controls();
    $uveventsviews = uws_events_views();
    $uveventshtml = "
    <div class='uws-integration uws-events' data-filter-date='$uvinitialdate' data-filter-enddate='$uvenddate' data-filter-maxdate='$uvmaxdate' data-filter-venue='all'>
        $uveventsactions
        <div class='uws-events-views'>$uveventsviews</div>
    </div>";

    // @Axl
    // echo $uveventshtml;
    echo wp_kses_post( $uveventshtml );
    // @Axl End
}

/*Get events views*/
function uws_events_views(){
    global $uws_core_lib;

    $uvviewshtml = "";
    $uvviews = (isset($uws_core_lib["events"])) ? $uws_core_lib["events"]["eventspage-views"] : "";

    if(is_array($uvviews)){
        $uvviewsmenu = "<ul>";

        foreach($uvviews as $uvviewkey => $uvview){
            if($uvview["show"]){
                $uvviewclass = ($uvview["defaultview"]) ? "uvsactive" : "";

                $uvviewshtml .= "<div class='uws-events-view uws-events-view-$uvviewkey $uvviewclass'>" . $uvview["label"] . "</div>";
            }
        }
    }

    return $uvviewshtml;
}

/*Get events filter/controls
    Returns: Controls html
*/
function uws_events_controls(){
    global $uws_core_lib;

    $uvdateselectortype = $uws_core_lib["events"]["eventspage-dateselector"]; //"datepicker-date", "datepicker-range", "month-dropdown", "month-arrows"

    if($uvdateselectortype == "datepicker-date"){
        $uvinitialddate = uws_get_events_initial_date("M j, Y");
        $uvinitialdate = uws_get_events_initial_date("Y-m-d");
        $uvcalmonthselhtml = "
        <div class='uws-events-dpinput uwshascalincon uws-dropdown-cont'>
            <label for='uwsfilterdate'>Date</label>
            <i class='uwsicon-calendar'></i>
            <input id='uwsfilterdate' class='uwsjs-trigger-dropdown' name='uwsfilterdate' type='text' value='$uvinitialddate' data-date='$uvinitialdate' placeholder='Select a Date' readonly>
            <div class='uws-dropdown'>
                <div class='uws-dp-filterdate'></div>
            </div>
        </div>
        ";
    }
    else if($uvdateselectortype == "datepicker-range"){
        $uvinitialddate = uws_get_events_initial_date("M j");
        $uvinitialdate = uws_get_events_initial_date("Y-m-d");
        $uvendddate = uws_get_events_endinit_date("M j, Y");
        $uvenddate = uws_get_events_endinit_date("Y-m-d");
        $uvcalmonthselhtml = "
        <div class='uws-events-dpinput uws-dropdown-cont uwshascalincon'>
            <label for='uwsfilterrange'>Date Range</label>
            <i class='uwsicon-calendar'></i>
            <input id='uwsfilterrange' class='uwsjs-trigger-dropdown' name='uwsfilterrange' type='text' value='$uvinitialddate - $uvendddate' data-date='$uvinitialdate' data-enddate='$uvenddate' readonly>
            <div class='uws-dropdown'>
                <div class='uws-dp-filterdaterange'></div>
                <div class='uws-dp-filterdaterange-label'>Select Range</div>
            </div>
        </div>
        ";
    }
    else if($uvdateselectortype == "month-dropdown"){
        $uvinitialddate = uws_get_events_initial_date("F Y");
        $uvmonthslist = uws_get_monthslis();
        $uvcalmonthselhtml = "
        <div class='uws-dropdown-cont'>
            <a href='#uws-openmonthselection' class='uwsjs-trigger-dropdown' aria-label='Select Month'><span class='uwsdy-dropvalue'>$uvinitialddate</span></a>
            <div class='uws-dropdown'>
                <ul>$uvmonthslist</ul>
            </div>
        </div>
        ";
    }
    else{//Month Arrows
        $uvinitialddate = uws_get_events_initial_date("F Y");
        $uvinitialdate = uws_get_events_initial_date("Y-m-d");
        $uvmonthsstring = uws_get_monthsoptsstring();
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

    $uveventsviewmenu = uws_get_events_view_menu();

    $uvcontrols = "
    <div class='uws-events-controls'>
        <div class='uwsfilters'>
            <div class='uwsdatesel'>$uvcalmonthselhtml</div>
        </div>
        <div class='uvsviews'>$uveventsviewmenu</div>
    </div>";

    return $uvcontrols;
}

/*Get List of views menu*/
function uws_get_events_view_menu(){
    global $uws_core_lib;

    $uvviewsmenu = "";
    $uvviewordered = array();
    $uvviews = (isset($uws_core_lib["events"])) ? $uws_core_lib["events"]["eventspage-views"] : "";

    if(is_array($uvviews)){
        $uvviewsmenu = "<ul>";

        foreach($uvviews as $uvviewkey => $uvview){
            if($uvview["show"]){
                $uvview["key"] = $uvviewkey;
                $uvviewordered["ord" . $uvview["order"]] = $uvview;
            }
        }

        ksort($uvviewordered);
        foreach($uvviewordered as $uvview){
            $uvviewclass = ($uvview["defaultview"]) ? "uvsactive" : "";

            $uvviewsmenu .= "<li><a class='uwsjs-events-changeview $uvviewclass' href='#uws-view-" . $uvview["key"] . "' data-view='" . $uvview["key"] . "' aria-label='Change view to " . $uvview["label"] . "'><i class='" . $uvview["icon"] . "'></i><span> " . $uvview["label"] . "</span></a></li>";
        }

        $uvviewsmenu .= "</ul>";
    }

    return $uvviewsmenu;
}

// Get list of months for calendar dropdown
function uws_get_monthslis($uvcurrentdate = ""){
    global $uws_core_lib, $uws_today;

    $uvcurrentdate = ($uvcurrentdate) ? $uvcurrentdate : $uws_today;
    $uvnmonths = $uws_core_lib["events"]["eventspage-monthsrange"];
    $uvmonthslis = "";
    $uvfirstmonthclass = "uwscurrent";
    
    $uvmonthlidate = $uvcurrentdate;
    $uvmonthlidate = strtotime($uvmonthlidate);

    for($i=0; $i<$uvnmonths; $i++){
        $uvmonthlifdate = date("Y-m-01", $uvmonthlidate);
        $uvmonthlimname = date("F", $uvmonthlidate);
        $uvmonthliyear = date("Y", $uvmonthlidate);

        $uvmonthlifdate = ($uvmonthlifdate < $uvcurrentdate) ? $uvcurrentdate : $uvmonthlifdate;
        
        $uvmonthslis .= "<li class='$uvfirstmonthclass'><button class='uwsjs-events-selectmonth' aria-label='Select $uvmonthlimname' type='button' data-date='$uvmonthlifdate'>$uvmonthlimname $uvmonthliyear</button></li>";

        $uvmonthlidate = strtotime("+1 month", $uvmonthlidate);
        $uvfirstmonthclass = "";
    }

    return $uvmonthslis;
}

// Get string with the months to navigate with arrows
function uws_get_monthsoptsstring($uvcurrentdate = ""){
    global $uws_core_lib, $uws_today;

    $uvcurrentdate = ($uvcurrentdate) ? $uvcurrentdate : $uws_today;
    $uvnmonths = $uws_core_lib["events"]["eventspage-monthsrange"];
    $uvmonthsstring = "";

    $uvmonthlidate = $uvcurrentdate;
    $uvmonthlidate = strtotime($uvmonthlidate);

    for($i=0; $i<$uvnmonths; $i++){
        $uvmonthlifdate = date("Y-m-01", $uvmonthlidate);
        $uvmonthlimname = date("F", $uvmonthlidate);
        $uvmonthliyear = date("Y", $uvmonthlidate);

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
function uws_get_events_max_date($uvdateformat = ""){
    global $uws_core_lib, $uws_today;

    $uvnmonths = (isset($uws_core_lib["events"]) and $uws_core_lib["events"]["eventspage-monthsrange"]) ? $uws_core_lib["events"]["eventspage-monthsrange"] : 6;
    $uvmaxdate = date("Y-m-d", strtotime($uws_today . " +$uvnmonths months"));

    if($uvdateformat)
        $uvmaxdate = date($uvdateformat, strtotime($uvmaxdate));

    return $uvmaxdate;
}

/*Get Events Initial Date
    Optional: dateformat
*/
function uws_get_events_initial_date($uvdateformat = ""){
    global $uws_core_lib, $uws_today;

    $uvlibeventsinitialdate = (isset($uws_core_lib["events"]) and $uws_core_lib["events"]["global-initaldate"]) ? $uws_core_lib["events"]["global-initaldate"] : $uws_today;
    
    if($uvlibeventsinitialdate < $uws_today) $uvlibeventsinitialdate = $uws_today;

    if($uvdateformat)
        $uvlibeventsinitialdate = date($uvdateformat, strtotime($uvlibeventsinitialdate));

    return $uvlibeventsinitialdate;
}

/*Get Events End date
    Returns: enddate = initial date + number of months to load (global-nmonths)
    Optional: dateformat
*/
function uws_get_events_endinit_date($uvdateformat = ""){
    global $uws_core_lib;

    $uvinitialdate = uws_get_events_initial_date("Y-m-d");
    $uvnmonths = (isset($uws_core_lib["events"]) and $uws_core_lib["events"]["global-nmonths"]) ? $uws_core_lib["events"]["global-nmonths"] : 2;
    $uvenddate = date("Y-m-d", strtotime($uvinitialdate . " +$uvnmonths months"));

    if($uvdateformat)
        $uvenddate = date($uvdateformat, strtotime($uvenddate));

    return $uvenddate;
}