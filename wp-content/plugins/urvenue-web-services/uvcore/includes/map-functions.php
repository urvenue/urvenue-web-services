<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*Get Map implementation
    Optional: args
    Returns: Prints html map integration
*/
// function uws_map($uvargs = ""){
function urvenue_ws_map($uvargs = ""){ // Axl UWS-7416
    // global $urvenue_ws_hooks;
    global $urvenue_ws_hooks; // Axl UWS-7634

    // if(isset($urvenue_ws_hooks['uws_map_replace'])){ //replace map integration on hook action
    // if(isset($urvenue_ws_hooks['urvenue_ws_map_replace'])){ //replace map integration on hook action // Axl UWS-7416
    if(isset($urvenue_ws_hooks['urvenue_ws_map_replace'])){ //replace map integration on hook action // Axl UWS-7634
        // $uveventcode = uws_get_eventcode();
        $uveventcode = urvenue_ws_get_eventcode(); // Axl UWS-7416
        // $uveventinfo = ($uveventcode) ? uws_get_event($uveventcode) : "";
        $uveventinfo = ($uveventcode) ? urvenue_ws_get_event($uveventcode) : ""; // Axl UWS-7416

        // uws_do_action("uws_map_replace", $uveventinfo);
        urvenue_ws_do_action("urvenue_ws_map_replace", $uveventinfo); // Axl UWS-7416
    }
    else{
        // $uvmaphtml = uws_get_map($uvargs);
        $uvmaphtml = urvenue_ws_get_map($uvargs); // Axl UWS-7416
        // @Axl
        // echo $uvmaphtml;
        echo wp_kses_post( $uvmaphtml );
        // @Axl End
    }
}

/*Get List of views menu*/
// function uws_get_map_view_menu(){
function urvenue_ws_get_map_view_menu(){ // Axl UWS-7416
    global $urvenue_ws_core_lib;

    $uvviewsmenu = "";
    $uvviewselected = "";
    $uvmapview = (isset($urvenue_ws_core_lib["map"]) and !is_array($urvenue_ws_core_lib["map"]["mappage-views"])) ? $urvenue_ws_core_lib["map"]["mappage-views"] : "list";

    $uvmapviews = array(
        "map" => array(
            "label" => "Map",
            "defaultview" => 0,
        ),
        "list" => array(
            "label" => "List",
            "defaultview" => 0,
        ),
    );

    if($uvmapview && $uvmapviews[$uvmapview]){
        $uvviewsmenu = "<ul>";
        
        $uvmapviews[$uvmapview]["defaultview"] = 1;

        foreach($uvmapviews as $uvviewkey => $uvview){
            $uvview["key"] = $uvviewkey;
            $uvviewclass = ($uvview["defaultview"]) ? "uvsactive" : "";
            $uvviewliclass = ($uvview["defaultview"]) ? "uwscurrent" : "";
            // $uvviewlabel = uws_lang($uvview["label"]);
            $uvviewlabel = urvenue_ws_lang($uvview["label"]); // Axl UWS-7416

            // $uvviewsmenu .= "<li class='$uvviewliclass'><a class='uwsjs-changemapview $uvviewclass' href='#uws-mapview-" . $uvview["key"] . "'  data-view='uws-map-view-" . $uvview["key"] . "' aria-label='" . uws_lang("change-view-to") . " " . $uvviewlabel . "'><span> " . $uvviewlabel . "</span></a></li>";
            $uvviewsmenu .= "<li class='$uvviewliclass'><a class='uwsjs-changemapview $uvviewclass' href='#uws-mapview-" . $uvview["key"] . "'  data-view='uws-map-view-" . $uvview["key"] . "' aria-label='" . urvenue_ws_lang("change-view-to") . " " . $uvviewlabel . "'><span> " . $uvviewlabel . "</span></a></li>"; // Axl UWS-7416

            if($uvview["defaultview"])
                $uvviewselected = "<span> " . $uvviewlabel . "</span>";
        }

        $uvviewsmenu .= "</ul>";
    }

    $uvviewsmap = array(
        "viewhtml" => $uvviewsmenu,
        "viewselected" => $uvmapview,
    );

    return $uvviewsmap;
}

/*Get html of the map integration
    Optional: args
    Returns: HTML of the map
*/
// function uws_get_map($uvargs = ""){
function urvenue_ws_get_map($uvargs = ""){ // Axl UWS-7416
    global $urvenue_ws_today, $urvenue_ws_config_mapdpmaxdate, $urvenue_ws_core_lib;

    // $uvprimvenue = uws_get_primary_venue();
    $uvprimvenue = urvenue_ws_get_primary_venue(); // Axl UWS-7416
    $uvprimvenuecode = (is_array($uvprimvenue)) ? $uvprimvenue["venuecode"] : "";

    // $uvdate = uws_get_arg($uvargs, "date", $urvenue_ws_today);
    $uvdate = urvenue_ws_get_arg($uvargs, "date", $urvenue_ws_today); // Axl UWS-7416
    // $uvvenuecode = uws_get_arg($uvargs, "venuecode", $uvprimvenuecode);
    $uvvenuecode = urvenue_ws_get_arg($uvargs, "venuecode", $uvprimvenuecode); // Axl UWS-7416
    // $uvecozone = uws_get_arg($uvargs, "ecozone", "ECZ0");
    $uvecozone = urvenue_ws_get_arg($uvargs, "ecozone", "ECZ0"); // Axl UWS-7416
    // $uvhidevenuesel = uws_get_arg($uvargs, "hidevenuesel", 0);
    $uvhidevenuesel = urvenue_ws_get_arg($uvargs, "hidevenuesel", 0); // Axl UWS-7416
    // $uvforcelisttype = uws_get_arg($uvargs, "forcelisttype", "");
    $uvforcelisttype = urvenue_ws_get_arg($uvargs, "forcelisttype", ""); // Axl UWS-7416
    // $uvmapdatesel = uws_get_map_datesel($uvargs);
    $uvmapdatesel = urvenue_ws_get_map_datesel($uvargs); // Axl UWS-7416
    //$uvmapecozonesel = uws_get_map_ecozonessel($uvargs);
    // $uvmapselsstring = uws_get_map_selsstring($uvargs);
    $uvmapselsstring = urvenue_ws_get_map_selsstring($uvargs); // Axl UWS-7416
    // $uvmaxdate = uws_get_events_max_date("Y-m-d");
    $uvmaxdate = urvenue_ws_get_events_max_date("Y-m-d"); // Axl UWS-7416
    $uvmaxdate = ($urvenue_ws_config_mapdpmaxdate) ? $urvenue_ws_config_mapdpmaxdate : $uvmaxdate;
    // $uvtheme = uws_get_theme();
    $uvtheme = urvenue_ws_get_theme(); // Axl UWS-7416

    $uvdate = ($uvdate < $urvenue_ws_today) ? $urvenue_ws_today : $uvdate;

    $uvhidevenuesel = (!$urvenue_ws_core_lib["events"]["eventspage-addvenuefilter"]) ? 1 : 0;

    $uvmapvenuesel = "";
    if(!$uvhidevenuesel)
        // $uvmapvenuesel = uws_get_map_venuesel($uvvenuecode);
        $uvmapvenuesel = urvenue_ws_get_map_venuesel($uvvenuecode); // Axl UWS-7416

    $uvhidevenuesel = (!$uvmapvenuesel) ? 1 : $uvhidevenuesel;

    $uvmapcontrolsclass = ($uvhidevenuesel) ? "uwsissinglevenue" : "";
    //$uvmapcontrolsclass = ($uvmapecozonesel) ? $uvmapcontrolsclass . " uwshasecozonesel" : $uvmapcontrolsclass;
    
    // $uvmapviews = uws_get_map_view_menu();
    $uvmapviews = urvenue_ws_get_map_view_menu(); // Axl UWS-7416
    $uvmapviewclass = (is_array($uvmapviews) and isset($uvmapviews["viewselected"]) and $uvmapviews["viewselected"]) ? "uws-map-view-" . $uvmapviews["viewselected"] : "uws-map-view-list";

    // $uvaddcartdrop = uws_get_arg($uvargs, "addcartdropdown", 0);
    $uvaddcartdrop = urvenue_ws_get_arg($uvargs, "addcartdropdown", 0); // Axl UWS-7416
    $uvaddcartdrop = ($uvaddcartdrop) ? "<div class='uwsmapcart uwsjs-cartdrop'><a href='javascript:;'><i class='uwsicon-shop'></i> <span>Cart</span></a></div>" : "";
    
    // $uvmaptempl = uws_get_template("map/map-container");
    $uvmaptempl = urvenue_ws_get_template("map/map-container"); // Axl UWS-7416
    $uvmaphtml = str_replace(
        array(
            "{mapdate}",
            "{venuecode}",
            "{ecozone}",
            "{mapdatesel}",
            "{mapvenuesel}",
            "{filtermaxdate}",
            "{mapselsstring}",
            "{mapcontrolsclass}",
            "{mapcartdrop}",
            "{mapviewclass}",
            "{mapmindate}",
            "{mapforcelisttype}",
            "{mapviews}",
            "{theme}",
        ),
        array(
            $uvdate,
            $uvvenuecode,
            $uvecozone,
            $uvmapdatesel,
            $uvmapvenuesel,
            $uvmaxdate,
            $uvmapselsstring,
            $uvmapcontrolsclass,
            $uvaddcartdrop,
            $uvmapviewclass,
            $urvenue_ws_today,
            $uvforcelisttype,
            $uvmapviews["viewhtml"],
            $uvtheme,
        ),
        $uvmaptempl
    );

    return $uvmaphtml;
}

/*Get ecomaps dropdown if exists
    Requireds: uvecomaps, uvargs(ecozone)
    Returns: HTML of ecomaps dropdown
*/
// function uws_get_ecomaps_sel($uvecomaps = "", $uvargs = ""){
function urvenue_ws_get_ecomaps_sel($uvecomaps = "", $uvargs = ""){ // Axl UWS-7416
    $uvecomapsdropdown = "";

    if(is_array($uvecomaps) and count($uvecomaps) > 1){
        $uvecomapsarray = array();
        // $uvselecozone = uws_get_arg($uvargs, "ecozone", "ECZ000");
        $uvselecozone = urvenue_ws_get_arg($uvargs, "ecozone", "ECZ000"); // Axl UWS-7416
        // $uvselecozone = uws_standardize_ecozone($uvselecozone);
        $uvselecozone = urvenue_ws_standardize_ecozone($uvselecozone); // Axl UWS-7416
        $uvselecomapname = "";

        foreach($uvecomaps as $uvecozone => $uvecomap){
            $uvecomapsarray[$uvecozone] = array(
                "name" => $uvecomap["layout"]["mapname"],
                "ecozone" => $uvecozone,
            );
        }

        // $uvecomapsarray = uws_apply_filters("uws_ecomaps_before_list", $uvecomapsarray);
        $uvecomapsarray = urvenue_ws_apply_filters("urvenue_ws_ecomaps_before_list", $uvecomapsarray); // Axl UWS-7416

        foreach($uvecomapsarray as $uvecomapitem){
            if($uvselecozone == $uvecomapitem["ecozone"])
                $uvselecomapname = $uvecomapitem["name"];
            $uvecomapslis .= "<li><button class='uwsjs-map-selectecozone' aria-label='Select " . $uvecomapitem["name"] . "' type='button' data-ecozone='" . $uvecomapitem["ecozone"] . "' data-nogroupings=''>" . $uvecomapitem["name"] . "</button></li>";
        }

        $uvecomapsdropdown = "
            <div class='uws-dropdown-cont'>
                <a href='#uws-openecozoneselection' class='uwsjs-trigger-dropdown' aria-label='Select Layout'><span class='uwsdy-dropvalue'>$uvselecomapname</span></a>
                <div class='uws-dropdown'>
                    <ul>
                        $uvecomapslis
                    </ul>
                </div>
            </div>
        ";
    }

    return $uvecomapsdropdown;
}

/*Get ecozone dropdown if exists
    Requireds: uvargs(ecozone, venuecode, date)
    Returns: HTML of ecozone dropdown
*/
// function uws_get_map_ecozonessel($uvargs = ""){
function urvenue_ws_get_map_ecozonessel($uvargs = ""){ // Axl UWS-7416
    $uvecozonesdropdown = "";

    if(is_array($uvargs) and isset($uvargs["venuecode"]) and isset($uvargs["date"])){
        // $uvnogroupings = uws_get_arg($uvargs, "nogroupings");
        $uvnogroupings = urvenue_ws_get_arg($uvargs, "nogroupings"); // Axl UWS-7416
        // $uvecozoneevent = uws_get_arg($uvargs, "ecozoneevent");
        $uvecozoneevent = urvenue_ws_get_arg($uvargs, "ecozoneevent"); // Axl UWS-7416
        // $uvorigecozone = uws_get_arg($uvargs, "ecozone", "ECZ0");
        $uvorigecozone = urvenue_ws_get_arg($uvargs, "ecozone", "ECZ0"); // Axl UWS-7416
        $uvecozone = ($uvnogroupings and $uvecozoneevent) ? $uvecozoneevent : $uvorigecozone;
        // $uvvenuecode = uws_get_arg($uvargs, "venuecode");
        $uvvenuecode = urvenue_ws_get_arg($uvargs, "venuecode"); // Axl UWS-7416
        // $uvdate = uws_get_arg($uvargs, "date");
        $uvdate = urvenue_ws_get_arg($uvargs, "date"); // Axl UWS-7416
        $uvecozone = ($uvnogroupings) ? "ECZ0" : $uvecozone;
        // $uvecozonestd = uws_standardize_ecozone($uvecozone);
        $uvecozonestd = urvenue_ws_standardize_ecozone($uvecozone); // Axl UWS-7416
        $uveventcode = "EVE" . str_replace("VEN", "", $uvvenuecode) . str_replace("ECZ", "", $uvecozonestd) . gmdate("Ymd", strtotime($uvdate)); // Axl UWS-7416
        // $uveventinfo = uws_get_event($uveventcode);
        $uveventinfo = urvenue_ws_get_event($uveventcode); // Axl UWS-7416

        if(is_array($uveventinfo) and isset($uveventinfo["ecozones"]) and is_array($uveventinfo["ecozones"]) and count($uveventinfo["ecozones"]) > 1){
            $uvselectedeconame = "View All";
            $uvecozoneslis = "<li><button class='uwsjs-map-selectecozone' aria-label='Select View All' type='button' data-ecozone='$uvecozone' data-nogroupings='0'>View All</button></li>";

            foreach($uveventinfo["ecozones"] as $uvecozone){
                $uvthisecozone = "ECZ" . $uvecozone["ecozoneid"];
                $uvthisecozonename = $uvecozone["name"];

                if($uvthisecozonename){
                    $uvecozoneslis .= "<li><button class='uwsjs-map-selectecozone' aria-label='Select $uvthisecozonename' type='button' data-ecozone='$uvthisecozone' data-nogroupings='1'>$uvthisecozonename</button></li>";

                    if($uvthisecozone == $uvorigecozone)
                        $uvselectedeconame = $uvthisecozonename;
                }
            }

            $uvecozonesdropdown = "
            <div class='uws-dropdown-cont'>
                <a href='#uws-openecozoneselection' class='uwsjs-trigger-dropdown' aria-label='Select Venue'><span class='uwsdy-dropvalue'>$uvselectedeconame</span></a>
                <div class='uws-dropdown'>
                    <ul>
                        $uvecozoneslis
                    </ul>
                </div>
            </div>
            ";
        }
    }

    return $uvecozonesdropdown;
}

/*Get string of the map current controls selection
    Requires: args
*/
// function uws_get_map_selsstring($uvargs = ""){
function urvenue_ws_get_map_selsstring($uvargs = ""){ // Axl UWS-7416
    global $urvenue_ws_today, $urvenue_ws_core_lib;

    // $uvdate = uws_get_arg($uvargs, "date", $urvenue_ws_today);
    $uvdate = urvenue_ws_get_arg($uvargs, "date", $urvenue_ws_today); // Axl UWS-7416
    // $uvddate = date($urvenue_ws_core_lib["events"]["global-dateformat"], strtotime($uvdate));
    $uvddate = gmdate($urvenue_ws_core_lib["events"]["global-dateformat"], strtotime($uvdate)); // Axl UWS-7416

    // $uvvenuecode = uws_get_arg($uvargs, "venuecode");
    $uvvenuecode = urvenue_ws_get_arg($uvargs, "venuecode"); // Axl UWS-7416

    if(!$uvvenuecode){
        // $uvprimvenue = uws_get_primary_venue();
        $uvprimvenue = urvenue_ws_get_primary_venue(); // Axl UWS-7416
        $uvvenuecode = (is_array($uvprimvenue)) ? $uvprimvenue["venuecode"] : "";
    }

    // $uvvenue = uws_get_venuelibinfo_byvenuecode($uvvenuecode);
    $uvvenue = urvenue_ws_get_venuelibinfo_byvenuecode($uvvenuecode); // Axl UWS-7416
    $uvvenuename = (isset($uvvenue["venueforcealias"]) and $uvvenue["venueforcealias"] and isset($uvvenue['venuealias']) and $uvvenue['venuealias']) ? $uvvenue['venuealias'] : "";
    $uvvenuename = (!$uvvenuename and isset($uvvenue["venuename"]) and $uvvenue["venuename"]) ? $uvvenue["venuename"] : "";

    $uvselstring = "$uvddate";
    $uvselstring = ($uvvenuename) ? "$uvselstring - <span>$uvvenuename</span>" : $uvselstring;

    // $uvecozones = uws_get_arg($uvargs, "ecozones");
    $uvecozones = urvenue_ws_get_arg($uvargs, "ecozones"); // Axl UWS-7416
    if($uvecozones and is_array($uvecozones) and count($uvecozones) > 1){
        $uvtheecozonename = "View All";
        // $uvecozone = uws_get_arg($uvargs, "ecozone");
        $uvecozone = urvenue_ws_get_arg($uvargs, "ecozone"); // Axl UWS-7416
        $uvecozonenumb = str_replace("ECZ", "", $uvecozone);

        foreach($uvecozones as $ecozone){
            if($ecozone["ecozoneid"] == $uvecozonenumb and $ecozone["name"]){
                $uvtheecozonename = $ecozone["name"];
                break;
            }
        }

        $uvselstring = "$uvselstring - <span>$uvtheecozonename</span>";
    }

    return $uvselstring;
}

/*Get date selector field
    Returns: HTML with date selector
*/
// function uws_get_map_datesel($uvargs = ""){
function urvenue_ws_get_map_datesel($uvargs = ""){ // Axl UWS-7416
    global $urvenue_ws_today, $urvenue_ws_core_lib;

    // $uvdate = uws_get_arg($uvargs, "date", $urvenue_ws_today);
    $uvdate = urvenue_ws_get_arg($uvargs, "date", $urvenue_ws_today); // Axl UWS-7416
    $uvdate = ($uvdate < $urvenue_ws_today) ? $urvenue_ws_today : $uvdate;
    //$uvddate = date($urvenue_ws_core_lib["events"]["global-dateformat"], strtotime($uvdate));
    // $uvddate = date("M j, Y", strtotime($uvdate));
    $uvddate = gmdate("M j, Y", strtotime($uvdate)); // Axl UWS-7416
    // $uvchangemapdatelabel = uws_lang("change-map-date");
    $uvchangemapdatelabel = urvenue_ws_lang("change-map-date"); // Axl UWS-7416
    // $uvddatelang = uws_lang_date($uvddate);
    $uvddatelang = urvenue_ws_lang_date($uvddate); // Axl UWS-7416

    $uvmapcontrols = "
    <div class='uws-map-dpinput uwshascalincon uws-dropdown-cont'>
        <i class='uwsicon-calendar-2'></i>
        <a id='uwsmapfilterdate' href='#uws-openmapdateselection' data-date='$uvdate' class='uwsjs-trigger-dropdown' aria-label='$uvchangemapdatelabel'>$uvddatelang</a>
        <div class='uws-dropdown'>
            <div class='uws-loader-uvicon'></div>
            <div class='uws-dp-mapfilterdate'></div>
        </div>
    </div>
    ";

    return $uvmapcontrols;
}

/*Get venue selector field
    Returns: HTML dropwdown of venue selector
*/
// function uws_get_map_venuesel($uvvenuecode){
function urvenue_ws_get_map_venuesel($uvvenuecode){ // Axl UWS-7416
    $uvvenuesel = "";

    // $uvvenueslist = uws_get_venueslis($uvvenuecode, "uwsjs-map-selectvenue");
    $uvvenueslist = urvenue_ws_get_venueslis($uvvenuecode, "uwsjs-map-selectvenue"); // Axl UWS-7416
    // $uvvenue = uws_get_venuelibinfo_byvenuecode($uvvenuecode);
    $uvvenue = urvenue_ws_get_venuelibinfo_byvenuecode($uvvenuecode); // Axl UWS-7416
    $uvvenuename = (isset($uvvenue["venueforcealias"]) and $uvvenue["venueforcealias"] and $uvvenue['venuealias']) ? $uvvenue['venuealias'] : "";
    $uvvenuename = (!$uvvenuename and isset($uvvenue["venuename"])) ? $uvvenue["venuename"] : $uvvenuename;
    

    if($uvvenueslist)
        $uvvenuesel = "
        <div class='uws-dropdown-cont'>
            <a href='#uws-openvenueselection' class='uwsjs-trigger-dropdown' aria-label='Select Venue'><span class='uwsdy-dropvalue'>$uvvenuename</span></a>
            <div class='uws-dropdown'>
                <ul>
                    $uvvenueslist
                </ul>
            </div>
        </div>
        ";

    return $uvvenuesel;
}

/*Get map stage
    Required: args(arguments with venuecode, date and ecozone)
    Returns: array with html and required arrays for map
*/
// function uws_get_map_stage($uvargs){
function urvenue_ws_get_map_stage($uvargs){ // Axl UWS-7416
    global $urvenue_ws_core_lib;

    $uvmapstage = array();
    // $uvlisttype = uws_get_arg($uvargs, "listtype", "sections");
    $uvlisttype = urvenue_ws_get_arg($uvargs, "listtype", "sections"); // Axl UWS-7416
    // $uvforcelisttype = uws_get_arg($uvargs, "forcelisttype", "");
    $uvforcelisttype = urvenue_ws_get_arg($uvargs, "forcelisttype", ""); // Axl UWS-7416
    $uvlisttype = ($uvforcelisttype) ? $uvforcelisttype : $uvlisttype;
    // $uvdate = uws_get_arg($uvargs, "date");
    $uvdate = urvenue_ws_get_arg($uvargs, "date"); // Axl UWS-7416
    // $uvvenuecode = uws_get_arg($uvargs, "venuecode");
    $uvvenuecode = urvenue_ws_get_arg($uvargs, "venuecode"); // Axl UWS-7416
    // $uvecozone = $uvecozoneevent = uws_get_arg($uvargs, "ecozone");
    $uvecozone = $uvecozoneevent = urvenue_ws_get_arg($uvargs, "ecozone"); // Axl UWS-7416
    // $uvnogroupings = uws_get_arg($uvargs, "nogroupings");
    $uvnogroupings = urvenue_ws_get_arg($uvargs, "nogroupings"); // Axl UWS-7416
    $uvecozoneevent = ($uvnogroupings) ? "ECZ0" : $uvecozoneevent;
    $uvargs["ecozoneevent"] = $uvecozoneevent;
    $uvvenuelibinfo = "";
    // $uvtheme = uws_get_theme();
    $uvtheme = urvenue_ws_get_theme(); // Axl UWS-7416

    if($uvvenuecode)
        // $uvvenuelibinfo = uws_get_venuelibinfo_byvenuecode($uvvenuecode);
        $uvvenuelibinfo = urvenue_ws_get_venuelibinfo_byvenuecode($uvvenuecode); // Axl UWS-7416

    if(!$uvvenuelibinfo){
        // $uvmapmanageentid = uws_get_arg($uvargs, "manageentid");
        $uvmapmanageentid = urvenue_ws_get_arg($uvargs, "manageentid"); // Axl UWS-7416

        if($uvmapmanageentid)
            $uvvenuelibinfo = array(
                "manageentid" => $uvmapmanageentid,
                "providerid" => $uvmapmanageentid,
                "resellerid" => $uvmapmanageentid,
            );
    }

    if(is_array($uvargs) and $uvdate and $uvvenuecode and $uvecozone){
        // $uvecozonestd = uws_standardize_ecozone($uvecozoneevent);
        $uvecozonestd = urvenue_ws_standardize_ecozone($uvecozoneevent); // Axl UWS-7416
        $uveventcode = "EVE" . str_replace("VEN", "", $uvvenuecode) . str_replace("ECZ", "", $uvecozonestd) . gmdate("Ymd", strtotime($uvdate)); // Axl UWS-7416
        // $uveventinfoandeczmap = uws_get_event($uveventcode, array("returnecozonesmap" => 1));
        $uveventinfoandeczmap = urvenue_ws_get_event($uveventcode, array("returnecozonesmap" => 1)); // Axl UWS-7416
        $uveventinfo = $uveventinfoandeczmap["event"];
        $uvinventorymapdata = $uvmapstage = $uvecozoneslist = $uvecozoneback = "";

        // if(uws_get_arg($uvargs, "homeecozone")){
        if(urvenue_ws_get_arg($uvargs, "homeecozone")){ // Axl UWS-7416
            $uveventinfoandeczmap["ecozonesmap"] = "";
            $uveventinfo["ecozones"] = "";
            // $uvecozoneback = "<div class='uws-map-ecozone-back-cont'><a href='#uws-ecozone-back' class='uwsjs-map-ecozone-back uws-list-ecozone-back' data-ecozone='" . uws_get_arg($uvargs, "homeecozone") . "'><i class='uwsicon-right-open'></i> <span>" . uws_get_arg($uvargs, "homename") . "</span></a></div>";
            $uvecozoneback = "<div class='uws-map-ecozone-back-cont'><a href='#uws-ecozone-back' class='uwsjs-map-ecozone-back uws-list-ecozone-back' data-ecozone='" . urvenue_ws_get_arg($uvargs, "homeecozone") . "'><i class='uwsicon-right-open'></i> <span>" . urvenue_ws_get_arg($uvargs, "homename") . "</span></a></div>"; // Axl UWS-7416

            if($uveventinfo["maineventcode"] and ($uveventinfo["maineventcode"] != $uveventinfo["eventcode"]))
                $uveventinfo["event-page-url"] = str_replace($uveventinfo["eventcode"], $uveventinfo["maineventcode"], $uveventinfo["event-page-url"]);
        }

        //Clean ecozones when there is only 1, force direct unique ecozone with item
        if(is_array($uveventinfoandeczmap["ecozonesmap"]) and count($uveventinfoandeczmap["ecozonesmap"]) == 1){
            $uveventinfo = reset($uveventinfoandeczmap["ecozonesmap"]);
            // $uvecozonestd = uws_standardize_ecozone($uveventinfo["ecozone"]);
            $uvecozonestd = urvenue_ws_standardize_ecozone($uveventinfo["ecozone"]); // Axl UWS-7416
            $uveventinfoandeczmap["ecozonesmap"] = "";
            $uveventinfo["ecozones"] = "";
        }

        //Check if we should show ecozones selection first
        if(is_array($uveventinfoandeczmap["ecozonesmap"])) {//has ecozones and we should show ecozones selection
            $uvargstmp = $uvargs;
            $uvargstmp["customfeedkey"] = "mapinventorykeep";
            // $uvinventorymapdata = uws_get_map_inventory_data($uvargstmp, $uveventinfo);
            $uvinventorymapdata = urvenue_ws_get_map_inventory_data($uvargstmp, $uveventinfo); // Axl UWS-7416
            
            if(isset($uvinventorymapdata["map"]) and isset($uvinventorymapdata["map"]["layout"]) and isset($uvinventorymapdata["map"]["layout"]["svgpath"]) and $uvinventorymapdata["map"]["layout"]["svgpath"]){
                $uvvenuemapsvg = $uvinventorymapdata["map"]["layout"]["svgpath"];
                // $uvmapsvghtml = uws_get_feed($uvvenuemapsvg);
                $uvmapsvghtml = urvenue_ws_get_feed($uvvenuemapsvg); // Axl UWS-7416

                // $uvecozoneslist = uws_get_ecozones_list($uveventinfoandeczmap["ecozonesmap"], array("actionclass" => "uwsjs-select-invmap-ecozone"));
                $uvecozoneslist = urvenue_ws_get_ecozones_list($uveventinfoandeczmap["ecozonesmap"], array("actionclass" => "uwsjs-select-invmap-ecozone")); // Axl UWS-7416
                $uvmapstage = array(
                    "stagehtml" => "<div class='uws-map-holder-graph'>
    {mapgraph}</div><div class='uwsecozonessellist'><div class='uwsecozonessellistinner'>" . $uvecozoneslist . "</div></div>",
                    "isecozonelist" => 1,
                    "mapgraph" => $uvmapsvghtml,
                );
                $uvinventorymapdata = "";
            }
        }
        else{
            // $uvinventorymapdata = uws_get_map_inventory_data($uvargs, $uveventinfo);
            $uvinventorymapdata = urvenue_ws_get_map_inventory_data($uvargs, $uveventinfo); // Axl UWS-7416
        }

        if(is_array($uvinventorymapdata)){
            // $uvmapstagetempl = uws_get_template("map/map-stage");
            $uvmapstagetempl = urvenue_ws_get_template("map/map-stage"); // Axl UWS-7416
            //$uvmapitems = uws_get_plain_items_list($uvinventorymapdata["inventory"]);
            $uvmapitems = $uvinventorymapdata["items"];

            $uvmapinfo = $uvinventorymapdata["map"];

            //Is multiecozone integration
            if(isset($uveventinfo["ecozones"]) and is_array($uveventinfo["ecozones"]) and count($uveventinfo["ecozones"]) > 1){
                $uvinventorymasterlist = $uvinventorymapdata["inventory"]["D" . gmdate("ymd", strtotime($uvdate))]["venues"][$uvvenuecode]["masterlist"]; // Axl UWS-7416
                // $uvmapinfo = uws_map_tomultiecozones($uvmapinfo, $uvinventorymasterlist);
                $uvmapinfo = urvenue_ws_map_tomultiecozones($uvmapinfo, $uvinventorymasterlist); // Axl UWS-7416
            }
            else{
                $uvmapinfo = (isset($uvinventorymapdata["ecomaps"]) and isset($uvinventorymapdata["ecomaps"][$uvecozonestd])) ? $uvinventorymapdata["ecomaps"][$uvecozonestd] : "";

                if($uvmapinfo)
                    // $uvmapinfo["secitems"] = uws_map_mascodes_to_mastercodes($uvmapinfo["secitems"], $uvmapitems, $uveventinfo);
                    $uvmapinfo["secitems"] = urvenue_ws_map_mascodes_to_mastercodes($uvmapinfo["secitems"], $uvmapitems, $uveventinfo); // Axl UWS-7416
            }

            if($uvmapinfo){
                //$uvmapinfo = $uvinventorymapdata["map"];
                $uvvenuemapsvg = $uvmapinfo["layout"]["svgpath"];
                $uvseatingtype = ($uvmapinfo["header"]["seatingtype"]) ? $uvmapinfo["header"]["seatingtype"] : "section";
                // $uvcredits = uws_get_uwscredits();
                $uvcredits = urvenue_ws_get_uwscredits(); // Axl UWS-7416
                $uvmapecozonesel = "";

                if(isset($urvenue_ws_core_lib["map"]) and isset($urvenue_ws_core_lib["map"]["mappage-showecomaps"]) and $urvenue_ws_core_lib["map"]["mappage-showecomaps"])
                    // $uvmapecozonesel = uws_get_ecomaps_sel($uvinventorymapdata["ecomaps"], $uvargs);
                    $uvmapecozonesel = urvenue_ws_get_ecomaps_sel($uvinventorymapdata["ecomaps"], $uvargs); // Axl UWS-7416

                /*if($uveventinfo && is_array($uveventinfo) && isset($uveventinfo["ecozones"]) && is_array($uveventinfo["ecozones"]) && count($uveventinfo["ecozones"]) > 1)
                    $uvargs["ecozones"] = $uveventinfo["ecozones"];*/   

                // $uvsdate = date("ymd", strtotime($uvdate));
                $uvsdate = gmdate("ymd", strtotime($uvdate)); // Axl UWS-7416
                // $uvecozone = uws_standardize_ecozone($uvecozone);
                $uvecozone = urvenue_ws_standardize_ecozone($uvecozone); // Axl UWS-7416
                
                //$uvmapitems = uws_get_eventinventory_list_feed($uvargs);
                //$uvmapitems = (is_array($uvmapitems) and is_array($uvmapitems["items"])) ? uws_keys_to_mastercode($uvmapitems["items"]) : "";

                $uvinventorymapdata["map"] = $uvmapinfo;
                // $uvmaplist = uws_map_get_list($uvinventorymapdata, $uvargs, $uvlisttype, $uvmapitems);
                $uvmaplist = urvenue_ws_map_get_list($uvinventorymapdata, $uvargs, $uvlisttype, $uvmapitems); // Axl UWS-7416

                if($uvvenuemapsvg){
                    //$uvvenuemapsvgurl = "https://" . $uvvenuemapsvg;
                    // $uvmapsvghtml = uws_get_feed($uvvenuemapsvg);
                    $uvmapsvghtml = urvenue_ws_get_feed($uvvenuemapsvg); // Axl UWS-7416
                }
                else
                    $uvmapsvghtml = "";

                $uvmapstagehtml = str_replace(
                    array(
                        "{mapadmissionitem}",
                        "{mapitemslist}",
                        "{uwscredits}"
                    ),
                    array(
                        $uvmapadmissionitem,
                        $uvmaplist,
                        $uvcredits
                    ),
                    $uvmapstagetempl
                );

                //Get Event Info
                // $uveventinfotempl = uws_get_template("map/map-event-info");
                $uveventinfotempl = urvenue_ws_get_template("map/map-event-info"); // Axl UWS-7416
                // $uveventinfohtml = ($uveventinfo) ? uws_replace_event_vars($uveventinfo, $uveventinfotempl) : "";
                $uveventinfohtml = ($uveventinfo) ? urvenue_ws_replace_event_vars($uveventinfo, $uveventinfotempl) : ""; // Axl UWS-7416

                $uvmapstage = array(
                    "seatingtype" => $uvseatingtype,
                    "listtype" => $uvlisttype,
                    "stagehtml" => $uvmapstagehtml,
                    "mapgraph" => $uvmapsvghtml,
                    "locations" => $uvmapinfo["locations"],
                    "seclocs" => $uvmapinfo["seclocs"],
                    "secitems" => $uvmapinfo["secitems"],
                    "sections" => $uvmapinfo["sections"],
                    "items" => $uvmapitems,
                    // "selsstring" => uws_get_map_selsstring($uvargs),
                    "selsstring" => urvenue_ws_get_map_selsstring($uvargs), // Axl UWS-7416
                    "eventinfo" => $uveventinfohtml,
                    "ecozonesel" => $uvmapecozonesel,
                    "ecozoneback" => $uvecozoneback,
                    "theme" => $uvtheme,
                );
            }
        }
    }

    if((!$uvmapinfo or !$uvmapinfo["locations"] or !$uvmapinfo["seclocs"] or !$uvmapinfo["secitems"] or !$uvmapitems) and !$uvecozoneslist){//if not map items
        // $uveventinfotempl = uws_get_template("map/map-stage-nomap");
        $uveventinfotempl = urvenue_ws_get_template("map/map-stage-nomap"); // Axl UWS-7416
        if(!is_array($uvmapstage))
            $uvmapstage = array();
        $uvmapstage["stagehtml"] = $uveventinfotempl;
    }

    return $uvmapstage;
}

//Get Admission items for map
// function uws_map_get_admission($uvitems, $uvecozone = ""){
function urvenue_ws_map_get_admission($uvitems, $uvecozone = ""){ // Axl UWS-7416
    global $urvenue_ws_core_lib, $urvenue_ws_config_mapshowadm, $urvenue_ws_config_mapsnameadm, $urvenue_ws_config_mapamdgt, $urvenue_ws_config_mapadm_every_ecozone;

    
    $uvmapadm = (isset($urvenue_ws_core_lib["map"]) and isset($urvenue_ws_core_lib["map"]["mappage-addadmissionopt"]) and $urvenue_ws_core_lib["map"]["mappage-addadmissionopt"]) ? $urvenue_ws_core_lib["map"]["mappage-addadmissionopt"] : "";
    $uvmapadm = ($urvenue_ws_config_mapshowadm) ? $urvenue_ws_config_mapshowadm : $uvmapadm;
    $uvmapadmname = ($urvenue_ws_config_mapsnameadm) ? $urvenue_ws_config_mapsnameadm : "Admissions";
    $uvmapamdgt = ($urvenue_ws_config_mapamdgt) ? $urvenue_ws_config_mapamdgt : "admission";

    $uvonlyadmitems = array();
	$uvmapadmlisthtml = "";
    $uvmapadm_every_ecozone = ($urvenue_ws_config_mapadm_every_ecozone) ? $urvenue_ws_config_mapadm_every_ecozone : false;

    if(is_array($uvitems)){
        $uvmapadmgts = array_map('trim', explode(',', $uvmapamdgt));
        foreach($uvitems as $uvitemcode => $uvitem){
            // $uvitemecozone = (isset($uvitem["ecocode"]) and $uvitem["ecocode"]) ? uws_standardize_ecozone($uvitem["ecocode"]) : "";
            $uvitemecozone = (isset($uvitem["ecocode"]) and $uvitem["ecocode"]) ? urvenue_ws_standardize_ecozone($uvitem["ecocode"]) : ""; // Axl UWS-7416
            $uvitemglobaltype = $uvitem["globaltype"];
            
            if(in_array($uvitemglobaltype, $uvmapadmgts) && 
                (!isset($uvitem["inactive"]) || $uvitem["inactive"] != 1) &&
                ($uvmapadm_every_ecozone || !$uvecozone || !$uvitemecozone || $uvecozone == $uvitemecozone)) {
                 $uvonlyadmitems[$uvitemcode] = $uvitem;
            }
        }
    }

    $uvactiveclass = (is_array($uvonlyadmitems) && count($uvonlyadmitems) === 1) ? "uwsactive" : "";

	if($uvmapadm and is_array($uvonlyadmitems)){
        $uvmapadmlisthtml = "<div class='uws-booktype uws-booktype-item $uvactiveclass'>
                                <a class='uwsjs-booktypetoggle' href='#uvmap-admissions'>
                                    <span class='uwsbooktypenamenamecont'>
                                        <span class='uwsbooktypename'>$uvmapadmname</span>
                                    </span>
                                    <i class='uwsicon-ticket'></i>
                                </a>
                                <div class='uws-bootypelist-body'>
                                    <div class='uws-bootypelist-inner'>
                                        <div class='uws-invitems-list'>";

		foreach($uvonlyadmitems as $uvitemcode => $uvitem){
            $uvitemname = $uvitem["itemname"];
            $uvitemmnamne = $uvitem["mastername"];
            $uvitemdescr = $uvitem["descr"];
            $uvitemhighlight = ($uvitem["highlight"]) ? $uvitem["highlight"] : $uvitemdescr;
            $uvmastercode = $uvitem["mastercode"];
            $uvbooktypecode = $uvitem["booktypecode"];
            
            $uvitembktname = ($uvitem["booktypename"]) ? $uvitem["booktypename"] : $uvitemname;

            $uvmapadmlisthtml .= "<div class='uwsinv-item uws-inventory-item uws-inv-item-$uvmastercode' data-mastercode='$uvmastercode'>
                                        <div class='uwsinfo'>
                                            <div class='uwsname'>$uvitemmnamne</div>
                                            <div class='uwsextrainfo'>
                                                <div class='uwshighlight'>$uvitemhighlight</div>
                                            </div>
                                        </div>
                                        <div class='uwsactions'>
                                            <a class='uwsjs-inv-item-select uws-btn uws-btn-s' href='#uwsinv-select-ecoitem-$uvmastercode' aria-label='Select $uvitemname' data-mastercode='$uvmastercode'>
                                                <span>Get Tickets</span>
                                            </a>
                                        </div>
                                </div>";
		}
            
            $uvmapadmlisthtml .= "</div>
                                </div>
                            </div>
                        </div>";
	}

	return $uvmapadmlisthtml;
}

/*Get inventorymap feed
    Requires: uvargs: ecozone, venuecode, date. will create token: "ecozone=ECZ1234&venuecode=VEN1234&caldate=XXXX-XX-XX
    Returns: data needed by map
*/
// function uws_get_map_inventory_data($uvargs, $uveventinfo = ""){
function urvenue_ws_get_map_inventory_data($uvargs, $uveventinfo = ""){ // Axl UWS-7416
    $uvinventorymapdata = "";
    // $uvdate = uws_get_arg($uvargs, "date");
    $uvdate = urvenue_ws_get_arg($uvargs, "date"); // Axl UWS-7416
    // $uvvenuecode = uws_get_arg($uvargs, "venuecode");
    $uvvenuecode = urvenue_ws_get_arg($uvargs, "venuecode"); // Axl UWS-7416
    // $uvecozone = $uvecozoneevent = uws_get_arg($uvargs, "ecozone");
    $uvecozone = $uvecozoneevent = urvenue_ws_get_arg($uvargs, "ecozone"); // Axl UWS-7416
    // $uvcustomfeedkey = uws_get_arg($uvargs, "customfeedkey", "mapinventory");
    $uvcustomfeedkey = urvenue_ws_get_arg($uvargs, "customfeedkey", "mapinventory"); // Axl UWS-7416
    // $uvecozone = uws_standardize_ecozone($uvecozone);
    $uvecozone = urvenue_ws_standardize_ecozone($uvecozone); // Axl UWS-7416
    $uvincludeecozone = 1;

    //Remove ecozone parameter if event has more than one ecozone (known as groupings on old API)
    /*if(isset($uveventinfo["ecozones"]) and is_array($uveventinfo["ecozones"]) and count($uveventinfo["ecozones"]) > 1)
        $uvincludeecozone = 0;*/

    $uvfeedtoken = "venuecode=" . $uvvenuecode . "&caldate=" . $uvdate;
    if($uvincludeecozone)
        $uvfeedtoken .= "&ecozone=" . $uvecozone;

    // $uvinventorymapfeed = uws_get_feed($uvcustomfeedkey, $uvfeedtoken);
    $uvinventorymapfeed = urvenue_ws_get_feed($uvcustomfeedkey, $uvfeedtoken); // Axl UWS-7416

    if(is_array($uvinventorymapfeed) and $uvinventorymapfeed["uv"]["success"]["status"] == "success"){
        $uvinventorymapdata = $uvinventorymapfeed["uv"]["data"];
    }

    return $uvinventorymapdata;
}

/*Get map list
    Requires: inventory feed array, venuecode, listtype
    Returns: html of the map list
*/
// function uws_map_get_list($uvinventorymapfeed, $uvargs, $uvlisttype = "sections", $uvmapitems = ""){
function urvenue_ws_map_get_list($uvinventorymapfeed, $uvargs, $uvlisttype = "sections", $uvmapitems = ""){ // Axl UWS-7416
    $uvmaplist = $uvmaplistitems = "";

    if(is_array($uvinventorymapfeed)){
        // $uvmaplisttempl = uws_get_template("map/map-list");
        $uvmaplisttempl = urvenue_ws_get_template("map/map-list"); // Axl UWS-7416
        $uvmapinfo = $uvinventorymapfeed["map"];
        $uvsections = $uvmapinfo["sections"];
        $uvsecitems = $uvmapinfo["secitems"];
        $uvlocations = $uvmapinfo["locations"];
        $uvinactiveclass = "";
        // $uvecozone = uws_get_arg($uvargs, "ecozone");
        $uvecozone = urvenue_ws_get_arg($uvargs, "ecozone"); // Axl UWS-7416
        // $uvecozone = uws_standardize_ecozone($uvecozone);
        $uvecozone = urvenue_ws_standardize_ecozone($uvecozone); // Axl UWS-7416

        // $uvmapadmissionitem = uws_map_get_admission($uvmapitems, $uvecozone);
        $uvmapadmissionitem = urvenue_ws_map_get_admission($uvmapitems, $uvecozone); // Axl UWS-7416

        $uvmaplistitems = ($uvmapadmissionitem) ? $uvmapadmissionitem : "";

        if($uvlisttype == "sections" and is_array($uvsections) and is_array($uvsecitems)){
            foreach($uvsections as $uvsectioncode => $uvsection){
                if(isset($uvsecitems[$uvsectioncode]) and is_array($uvsecitems[$uvsectioncode])){//show section only if has items
                    $uvshowsecitem = 0;

                    foreach($uvsecitems[$uvsectioncode] as $uvsecitemmascode){
                        if(isset($uvmapitems[$uvsecitemmascode])){
                            $uvshowsecitem = 1;

                            if(isset($uvmapitems[$uvsecitemmascode]["inactive"]) and $uvmapitems[$uvsecitemmascode]["inactive"] === 1)
                                $uvinactiveclass = "uwsinactive";

                            break;
                        }
                    }

                    if($uvshowsecitem){
                        $uvsectionname  = $uvsection["name"];
                        $uvmaplistitems .= "
                        <div>
                            <a class='uwsjs-open-section-listtooltip uws-map-list-elem uws-map-list-sec-$uvsectioncode uws-btn uws-btn-s $uvinactiveclass' href='#uws-map-opensectooltip-$uvsectioncode' data-seccode='$uvsectioncode'>
                                <span>$uvsectionname</span>
                                <i class='uwsicon-right-open'></i>
                            </a>
                        </div>
                        ";
                    }
                }
            }
        }
        if($uvlisttype == "booktypes" and is_array($uvsections) and is_array($uvsecitems)){
            // $uvdate = uws_get_arg($uvargs, "date");
            $uvdate = urvenue_ws_get_arg($uvargs, "date"); // Axl UWS-7416
            // $uvvenuecode = uws_get_arg($uvargs, "venuecode");
            $uvvenuecode = urvenue_ws_get_arg($uvargs, "venuecode"); // Axl UWS-7416
            // $uvsdate = date("ymd", strtotime($uvdate));
            $uvsdate = gmdate("ymd", strtotime($uvdate)); // Axl UWS-7416
            $uvmapitemslist = $uvinventorymapfeed["items"]; //$uvinventorymapfeed["inventory"]["D" . $uvsdate]["venues"][$uvvenuecode]["ecozones"][$uvecozone]["items"];
            // $uvmaplistitems = uws_get_map_booktypeslist($uvmapitemslist, $uvmapinfo);
            $uvmaplistitems = urvenue_ws_get_map_booktypeslist($uvmapitemslist, $uvmapinfo); // Axl UWS-7416
        }

        $uvmaplist = str_replace(
            array(
                "{maplistitems}",
                "{listtype}",
            ),
            array(
                $uvmaplistitems,
                $uvlisttype,
            ),
            $uvmaplisttempl
        );
    }

    return $uvmaplist;
}

/*Get map booktypes list
    Requires: map items array, locsbysec array
    Returns: html with map booktypes list
*/
// function uws_get_map_booktypeslist($uvitems, $uvmapinfo){
function urvenue_ws_get_map_booktypeslist($uvitems, $uvmapinfo){ // Axl UWS-7416
    $uvbooktypeslisthtml = "";

    if(is_array($uvitems) and is_array($uvmapinfo)){
        $uvlocations = $uvmapinfo["locations"];
		$uvbooktypeslist = array();

		foreach($uvitems as $uvitem){ //Get All booktypes list

			$uvitembooktypename = (isset($uvitem["booktypename"])) ? $uvitem["booktypename"] : "";
			$uvitembooktypecode = (isset($uvitem["booktypecode"])) ? $uvitem["booktypecode"] : "";
			$uvitemlocids = (isset($uvitem["locids"])) ? $uvitem["locids"] : "";
			$uvitemmastercode = (isset($uvitem["mastercode"])) ? $uvitem["mastercode"] : "";
            $uvitemlocation = ($uvitemlocids) ? explode(",", $uvitemlocids) : "";

			if(!isset($uvbooktypeslist[$uvitembooktypecode]) and !isset($uvbooktypeslist[$uvitembooktypecode]["locs"]) and is_array($uvitemlocation)){
				$uvitembooktypearray = array();

				$uvbooktypeslist[$uvitembooktypecode]["booktypecode"] = $uvitembooktypecode;
                $uvbooktypeslist[$uvitembooktypecode]["booktypename"] = $uvitembooktypename;
				$uvbooktypeslist[$uvitembooktypecode]["locs"] = $uvitemlocation;
			}
            else if(is_array($uvitemlocation)){
				$uvbooktypeslist[$uvitembooktypecode]["locs"] = array_merge($uvbooktypeslist[$uvitembooktypecode]["locs"], $uvitemlocation);
			}
		}
	}

    if(is_array($uvbooktypeslist)){
        foreach($uvbooktypeslist as $uvbooktypelocs){
            $uvbooktypelocslist = "";
            $uvbooktypename = $uvbooktypelocs["booktypename"];
            $uvthisbooktypecode = $uvbooktypelocs["booktypecode"];
            $uvcurlocsids = array();
    
            if($uvbooktypelocs["locs"]) {
                foreach($uvbooktypelocs["locs"] as $uvbooktypeloc){
                    $uvlocsecid = "SEC" . $uvlocations["LOC" . $uvbooktypeloc]["sectionid"];
                    $uvlocname = $uvlocations["LOC" . $uvbooktypeloc]["locationname"];
                    $uvloccode = "LOC" . $uvlocations["LOC" . $uvbooktypeloc]["locationid"];
                    $uvlocid = $uvlocations["LOC" . $uvbooktypeloc]["locationid"];
        
                    if(!in_array($uvlocid, $uvcurlocsids) and $uvlocname){
                        $uvbooktypelocslist .= "
                            <div>
                                <a class='uwsjs-open-section-listtooltip uws-map-list-elem uws-map-list-sec-$uvlocsecid uws-btn uws-btn-s' href='#uws-map-opensectooltip-$uvlocsecid' data-seccode='$uvlocsecid' data-loccode='$uvloccode'>
                                    <span>$uvlocname</span>
                                    <i class='uwsicon-right-open'></i>
                                </a>
                            </div>
                        ";
        
                        array_push($uvcurlocsids, $uvlocid);
                    }
                }
            }

            //print_r($uvcurlocsids);
    
            if($uvbooktypelocslist){
                $uvbooktypeslisthtml .= "<div class='uws-togglecoll uws-map-listgroup' data-booktypecode='$uvthisbooktypecode'>
                    <a class='uwsjs-toggle-collapse uws-btn uws-btn-p uws-btn-100' href='#open-addon-toggle-{addonid}'><span class='uwsname'>$uvbooktypename</span><i class='uwsicon-right-open'></i></a>
                    <div class='uws-togglecoll-body'>
                        <div class='uws-togglecoll-inner'>
                            <div class='uws-map-list-innerlist'>$uvbooktypelocslist</div>
                        </div>
                    </div>
                </div>";
            }
               // $uvbooktypeslisthtml .= "<div class='uv-map-listsec-itemcont uvtests1'><a href='javascript:;' class='uv-map-listsec-item uvjs-maplist-showsecinfo uviscolltogg'><div class='uvsecname'>$uvbooktypename</div><i class='fa fa-angle-right'></i></a><div class='uv-map-listsec-iteminfo'><div class='uv-map-listsec-iteminfo-inner'>$uvbooktypelocslist</div></div></div>";
        }
    }

    return $uvbooktypeslisthtml;
}

/*Change all mas codes to master codes
    Requires: array with mascodes arrays
    Returns: same structure replacing mascodes with mastercodes
*/
// function uws_map_mascodes_to_mastercodes($uvmascodesarray, $uvitems, $uveventdata = ""){
function urvenue_ws_map_mascodes_to_mastercodes($uvmascodesarray, $uvitems, $uveventdata = ""){ // Axl UWS-7416
    $uvmastercodesarray = array();

    if(is_array($uvmascodesarray) and is_array($uvitems)){
        // $uvecozone = uws_get_arg($uveventdata, "ecozone", "ECZ000");
        $uvecozone = urvenue_ws_get_arg($uveventdata, "ecozone", "ECZ000"); // Axl UWS-7416
        // $uvecozone = uws_standardize_ecozone($uvecozone);
        $uvecozone = urvenue_ws_standardize_ecozone($uvecozone); // Axl UWS-7416

        foreach($uvmascodesarray as $uvkey => $uvmascodear){
            $uvmastercodesarray[$uvkey] = array();

            foreach($uvmascodear as $uvmascode){
                foreach($uvitems as $uvitem){
                    if(($uvitem["masteritemcode"] == $uvmascode) and ($uvitem["ecocode"] == $uvecozone)){
                        $uvmastercodesarray[$uvkey][] = $uvitem["mastercode"];
                        break;
                    }
                }
            }
        }
    }

    return $uvmastercodesarray;
}

/*Change add all ecos to secitems*/
// function uws_map_tomultiecozones($uvmapinfo, $uvinventorymasterlist){
function urvenue_ws_map_tomultiecozones($uvmapinfo, $uvinventorymasterlist){ // Axl UWS-7416
    $uvnewsecitems = "";

    if(is_array($uvmapinfo) and is_array($uvinventorymasterlist) and is_array($uvmapinfo["eco_secitems"])){
        $uvnewsecitems = array();

        foreach($uvmapinfo["eco_secitems"] as $uvseccode => $uvmapsec){
            $uvnewsecitems[$uvseccode] = array();

            foreach($uvmapsec as $uvmapecozone => $uvmapsececo){
                foreach($uvmapsececo as $uvmapsecitem){
                    $uvthemastercode = "";

                    if(isset($uvinventorymasterlist[$uvmapsecitem]) and isset($uvinventorymasterlist[$uvmapsecitem]["ecoitems"][$uvmapecozone])){
                        $uvthemastercode = $uvinventorymasterlist[$uvmapsecitem]["ecoitems"][$uvmapecozone];
                    }

                    if(!empty($uvseccode) && !empty($uvmapsecitem) && $uvthemastercode)
                        $uvnewsecitems[$uvseccode][] = $uvthemastercode;
                }
            }
        }
    }

    if(is_array($uvnewsecitems) and is_array($uvmapinfo))
        $uvmapinfo["secitems"] = $uvnewsecitems;

    return $uvmapinfo;
}