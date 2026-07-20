<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*Get Calendar implementation
    Returns: Prints html controls + events integration
*/
function urvenue_ws_events(){
    global $urvenue_ws_core_lib, $urvenue_ws_today;

    $uvinitialdate = urvenue_ws_get_events_initial_date("Y-m-d");
    $uvenddate = urvenue_ws_get_events_endinit_date("Y-m-d");
    $uvmaxdate = urvenue_ws_get_events_max_date("Y-m-d");

    $uveventsactions = urvenue_ws_events_controls();
    $uveventsviews = urvenue_ws_events_views();
    $uveventshtml = "
    <div class='uws-integration uws-events' data-filter-date='$uvinitialdate' data-filter-enddate='$uvenddate' data-filter-maxdate='$uvmaxdate' data-filter-venue='all'>
        $uveventsactions
        <div class='uws-events-views'>$uveventsviews</div>
    </div>";

    echo wp_kses_post( $uveventshtml );
}

/*Get events views*/
function urvenue_ws_events_views(){
    global $urvenue_ws_core_lib;

    $uvviewshtml = "";
    $uvviews = (isset($urvenue_ws_core_lib["events"])) ? $urvenue_ws_core_lib["events"]["eventspage-views"] : "";

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
function urvenue_ws_events_controls(){
    global $urvenue_ws_core_lib;

    $uvdateselectortype = $urvenue_ws_core_lib["events"]["eventspage-dateselector"]; //"datepicker-date", "datepicker-range", "month-dropdown", "month-arrows"

    if($uvdateselectortype == "datepicker-date"){
        $uvinitialddate = urvenue_ws_get_events_initial_date("M j, Y");
        $uvinitialdate = urvenue_ws_get_events_initial_date("Y-m-d");
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
        $uvinitialddate = urvenue_ws_get_events_initial_date("M j");
        $uvinitialdate = urvenue_ws_get_events_initial_date("Y-m-d");
        $uvendddate = urvenue_ws_get_events_endinit_date("M j, Y");
        $uvenddate = urvenue_ws_get_events_endinit_date("Y-m-d");
        $uvcalmonthselhtml = "
        <div class='uws-events-dpinput uws-dropdown-cont uwshascalincon'>
            <label for='uwsfilterrange'>Date Range</label>
            <i class='uwsicon-calendar'></i>
            <input id='uwsfilterrange' class='uwsjs-trigger-dropdown' name='uwsfilterrange' type='text' value='" . esc_attr( $uvinitialddate . ' - ' . $uvendddate ) . "' data-date='" . esc_attr( $uvinitialdate ) . "' data-enddate='" . esc_attr( $uvenddate ) . "' readonly>
            <div class='uws-dropdown'>
                <div class='uws-dp-filterdaterange'></div>
                <div class='uws-dp-filterdaterange-label'>Select Range</div>
            </div>
        </div>
        ";
    }
    else if($uvdateselectortype == "month-dropdown"){
        $uvinitialddate = urvenue_ws_get_events_initial_date("F Y");
        $uvmonthslist = urvenue_ws_get_monthslis();
        $uvcalmonthselhtml = "
        <div class='uws-dropdown-cont'>
            <a href='#uws-openmonthselection' class='uwsjs-trigger-dropdown' aria-label='Select Month'><span class='uwsdy-dropvalue'>" . esc_html( $uvinitialddate ) . "</span></a>
            <div class='uws-dropdown'>
                <ul>{$uvmonthslist}</ul>
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

    $uveventsviewmenu = urvenue_ws_get_events_view_menu();

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
function urvenue_ws_get_events_view_menu(){
    global $urvenue_ws_core_lib;

    $uvviewsmenu = "";
    $uvviewordered = array();
    $uvviews = (isset($urvenue_ws_core_lib["events"])) ? $urvenue_ws_core_lib["events"]["eventspage-views"] : "";

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
function urvenue_ws_get_monthslis($uvcurrentdate = ""){
    global $urvenue_ws_core_lib, $urvenue_ws_today;

    $uvcurrentdate = ($uvcurrentdate) ? $uvcurrentdate : $urvenue_ws_today;
    $uvnmonths = $urvenue_ws_core_lib["events"]["eventspage-monthsrange"];
    $uvmonthslis = "";
    $uvfirstmonthclass = "uwscurrent";

    $uvmonthlidate = $uvcurrentdate;
    $uvmonthlidate = strtotime($uvmonthlidate);

    for($i=0; $i<$uvnmonths; $i++){
        $uvmonthlifdate = gmdate("Y-m-01", $uvmonthlidate);
        $uvmonthlimname = gmdate("F", $uvmonthlidate);
        $uvmonthliyear = gmdate("Y", $uvmonthlidate);

        $uvmonthlifdate = ($uvmonthlifdate < $uvcurrentdate) ? $uvcurrentdate : $uvmonthlifdate;

        $uvmonthslis .= "<li class='" . esc_attr( $uvfirstmonthclass ) . "'><button class='uwsjs-events-selectmonth' aria-label='Select " . esc_attr( $uvmonthlimname ) . "' type='button' data-date='" . esc_attr( $uvmonthlifdate ) . "'>" . esc_html( $uvmonthlimname ) . ' ' . esc_html( $uvmonthliyear ) . "</button></li>";

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
        $uvmonthlifdate = gmdate("Y-m-01", $uvmonthlidate);
        $uvmonthlimname = gmdate("F", $uvmonthlidate);
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
    global $urvenue_ws_core_lib, $urvenue_ws_today;

    $uvnmonths = (isset($urvenue_ws_core_lib["events"]) and $urvenue_ws_core_lib["events"]["eventspage-monthsrange"]) ? $urvenue_ws_core_lib["events"]["eventspage-monthsrange"] : 6;
    $uvmaxdate = gmdate("Y-m-d", strtotime($urvenue_ws_today . " +$uvnmonths months"));

    if($uvdateformat)
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
        $uvlibeventsinitialdate = gmdate($uvdateformat, strtotime($uvlibeventsinitialdate));

    return $uvlibeventsinitialdate;
}

/*Get Events End date
    Returns: enddate = initial date + number of months to load (global-nmonths)
    Optional: dateformat
*/
function urvenue_ws_get_events_endinit_date($uvdateformat = ""){
    global $urvenue_ws_core_lib;

    $uvinitialdate = urvenue_ws_get_events_initial_date("Y-m-d");
    $uvnmonths = (isset($urvenue_ws_core_lib["events"]) and $urvenue_ws_core_lib["events"]["global-nmonths"]) ? $urvenue_ws_core_lib["events"]["global-nmonths"] : 2;
    $uvenddate = gmdate("Y-m-d", strtotime($uvinitialdate . " +$uvnmonths months"));

    if($uvdateformat)
        $uvenddate = gmdate($uvdateformat, strtotime($uvenddate));

    return $uvenddate;
}
