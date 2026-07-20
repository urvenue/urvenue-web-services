<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*Get Map implementation
    Optional: args
    Returns: Prints html map integration
*/
function urvenue_ws_map($uvargs = ""){
    global $urvenue_ws_hooks;

    if(isset($urvenue_ws_hooks['urvenue_ws_map_replace'])){ //replace map integration on hook action
        $uveventcode = urvenue_ws_get_eventcode();
        $uveventinfo = ($uveventcode) ? urvenue_ws_get_event($uveventcode) : "";

        urvenue_ws_do_action("urvenue_ws_map_replace", $uveventinfo);
    }
    else{
        $uvmaphtml = urvenue_ws_get_map($uvargs);
        echo wp_kses_post( $uvmaphtml );
    }
}

/*Get List of views menu*/
function urvenue_ws_get_map_view_menu(){
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
            $uvviewlabel = urvenue_ws_lang($uvview["label"]);

            $uvviewsmenu .= "<li class='$uvviewliclass'><a class='uwsjs-changemapview $uvviewclass' href='#uws-mapview-" . $uvview["key"] . "'  data-view='uws-map-view-" . $uvview["key"] . "' aria-label='" . urvenue_ws_lang("change-view-to") . " " . $uvviewlabel . "'><span> " . $uvviewlabel . "</span></a></li>";

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
function urvenue_ws_get_map($uvargs = ""){
    global $urvenue_ws_today, $urvenue_ws_config_mapdpmaxdate, $urvenue_ws_core_lib;

    $uvprimvenue = urvenue_ws_get_primary_venue();
    $uvprimvenuecode = (is_array($uvprimvenue)) ? $uvprimvenue["venuecode"] : "";

    $uvdate = urvenue_ws_get_arg($uvargs, "date", $urvenue_ws_today);
    $uvvenuecode = urvenue_ws_get_arg($uvargs, "venuecode", $uvprimvenuecode);
    $uvecozone = urvenue_ws_get_arg($uvargs, "ecozone", "ECZ0");
    $uvhidevenuesel = urvenue_ws_get_arg($uvargs, "hidevenuesel", 0);
    $uvforcelisttype = urvenue_ws_get_arg($uvargs, "forcelisttype", "");
    $uvmapdatesel = urvenue_ws_get_map_datesel($uvargs);
    //$uvmapecozonesel = uws_get_map_ecozonessel($uvargs);
    $uvmapselsstring = urvenue_ws_get_map_selsstring($uvargs);
    $uvmaxdate = urvenue_ws_get_events_max_date("Y-m-d");
    $uvmaxdate = ($urvenue_ws_config_mapdpmaxdate) ? $urvenue_ws_config_mapdpmaxdate : $uvmaxdate;
    $uvtheme = urvenue_ws_get_theme();

    $uvdate = ($uvdate < $urvenue_ws_today) ? $urvenue_ws_today : $uvdate;

    $uvhidevenuesel = (!$urvenue_ws_core_lib["events"]["eventspage-addvenuefilter"]) ? 1 : 0;

    $uvmapvenuesel = "";
    if(!$uvhidevenuesel)
        $uvmapvenuesel = urvenue_ws_get_map_venuesel($uvvenuecode);

    $uvhidevenuesel = (!$uvmapvenuesel) ? 1 : $uvhidevenuesel;

    $uvmapcontrolsclass = ($uvhidevenuesel) ? "uwsissinglevenue" : "";
    //$uvmapcontrolsclass = ($uvmapecozonesel) ? $uvmapcontrolsclass . " uwshasecozonesel" : $uvmapcontrolsclass;

    $uvmapviews = urvenue_ws_get_map_view_menu();
    $uvmapviewclass = (is_array($uvmapviews) and isset($uvmapviews["viewselected"]) and $uvmapviews["viewselected"]) ? "uws-map-view-" . $uvmapviews["viewselected"] : "uws-map-view-list";

    $uvaddcartdrop = urvenue_ws_get_arg($uvargs, "addcartdropdown", 0);
    $uvaddcartdrop = ($uvaddcartdrop) ? "<div class='uwsmapcart uwsjs-cartdrop'><a href='javascript:;'><i class='uwsicon-shop'></i> <span>Cart</span></a></div>" : "";

    $uvmaptempl = urvenue_ws_get_template("map/map-container");
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
function urvenue_ws_get_ecomaps_sel($uvecomaps = "", $uvargs = ""){
    $uvecomapsdropdown = "";

    if(is_array($uvecomaps) and count($uvecomaps) > 1){
        $uvecomapsarray = array();
        $uvselecozone = urvenue_ws_get_arg($uvargs, "ecozone", "ECZ000");
        $uvselecozone = urvenue_ws_standardize_ecozone($uvselecozone);
        $uvselecomapname = "";

        foreach($uvecomaps as $uvecozone => $uvecomap){
            $uvecomapsarray[$uvecozone] = array(
                "name" => $uvecomap["layout"]["mapname"],
                "ecozone" => $uvecozone,
            );
        }

        $uvecomapsarray = urvenue_ws_apply_filters("urvenue_ws_ecomaps_before_list", $uvecomapsarray);

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
function urvenue_ws_get_map_ecozonessel($uvargs = ""){
    $uvecozonesdropdown = "";

    if(is_array($uvargs) and isset($uvargs["venuecode"]) and isset($uvargs["date"])){
        $uvnogroupings = urvenue_ws_get_arg($uvargs, "nogroupings");
        $uvecozoneevent = urvenue_ws_get_arg($uvargs, "ecozoneevent");
        $uvorigecozone = urvenue_ws_get_arg($uvargs, "ecozone", "ECZ0");
        $uvecozone = ($uvnogroupings and $uvecozoneevent) ? $uvecozoneevent : $uvorigecozone;
        $uvvenuecode = urvenue_ws_get_arg($uvargs, "venuecode");
        $uvdate = urvenue_ws_get_arg($uvargs, "date");
        $uvecozone = ($uvnogroupings) ? "ECZ0" : $uvecozone;
        $uvecozonestd = urvenue_ws_standardize_ecozone($uvecozone);
        $uveventcode = "EVE" . str_replace("VEN", "", $uvvenuecode) . str_replace("ECZ", "", $uvecozonestd) . gmdate("Ymd", strtotime($uvdate));
        $uveventinfo = urvenue_ws_get_event($uveventcode);

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
function urvenue_ws_get_map_selsstring($uvargs = ""){
    global $urvenue_ws_today, $urvenue_ws_core_lib;

    $uvdate = urvenue_ws_get_arg($uvargs, "date", $urvenue_ws_today);
    $uvddate = gmdate($urvenue_ws_core_lib["events"]["global-dateformat"], strtotime($uvdate));

    $uvvenuecode = urvenue_ws_get_arg($uvargs, "venuecode");

    if(!$uvvenuecode){
        $uvprimvenue = urvenue_ws_get_primary_venue();
        $uvvenuecode = (is_array($uvprimvenue)) ? $uvprimvenue["venuecode"] : "";
    }

    $uvvenue = urvenue_ws_get_venuelibinfo_byvenuecode($uvvenuecode);
    $uvvenuename = (isset($uvvenue["venueforcealias"]) and $uvvenue["venueforcealias"] and isset($uvvenue['venuealias']) and $uvvenue['venuealias']) ? $uvvenue['venuealias'] : "";
    $uvvenuename = (!$uvvenuename and isset($uvvenue["venuename"]) and $uvvenue["venuename"]) ? $uvvenue["venuename"] : "";

    $uvselstring = "$uvddate";
    $uvselstring = ($uvvenuename) ? "$uvselstring - <span>$uvvenuename</span>" : $uvselstring;

    $uvecozones = urvenue_ws_get_arg($uvargs, "ecozones");
    if($uvecozones and is_array($uvecozones) and count($uvecozones) > 1){
        $uvtheecozonename = "View All";
        $uvecozone = urvenue_ws_get_arg($uvargs, "ecozone");
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
function urvenue_ws_get_map_datesel($uvargs = ""){
    global $urvenue_ws_today, $urvenue_ws_core_lib;

    $uvdate = urvenue_ws_get_arg($uvargs, "date", $urvenue_ws_today);
    $uvdate = ($uvdate < $urvenue_ws_today) ? $urvenue_ws_today : $uvdate;
    //$uvddate = date($urvenue_ws_core_lib["events"]["global-dateformat"], strtotime($uvdate));
    $uvddate = gmdate("M j, Y", strtotime($uvdate));
    $uvchangemapdatelabel = urvenue_ws_lang("change-map-date");
    $uvddatelang = urvenue_ws_lang_date($uvddate);

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
function urvenue_ws_get_map_venuesel($uvvenuecode){
    $uvvenuesel = "";

    $uvvenueslist = urvenue_ws_get_venueslis($uvvenuecode, "uwsjs-map-selectvenue");
    $uvvenue = urvenue_ws_get_venuelibinfo_byvenuecode($uvvenuecode);
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
function urvenue_ws_get_map_stage($uvargs){
    global $urvenue_ws_core_lib;

    $uvmapstage = array();
    $uvlisttype = urvenue_ws_get_arg($uvargs, "listtype", "sections");
    $uvforcelisttype = urvenue_ws_get_arg($uvargs, "forcelisttype", "");
    $uvlisttype = ($uvforcelisttype) ? $uvforcelisttype : $uvlisttype;
    $uvdate = urvenue_ws_get_arg($uvargs, "date");
    $uvvenuecode = urvenue_ws_get_arg($uvargs, "venuecode");
    $uvecozone = $uvecozoneevent = urvenue_ws_get_arg($uvargs, "ecozone");
    $uvnogroupings = urvenue_ws_get_arg($uvargs, "nogroupings");
    $uvecozoneevent = ($uvnogroupings) ? "ECZ0" : $uvecozoneevent;
    $uvargs["ecozoneevent"] = $uvecozoneevent;
    $uvvenuelibinfo = "";
    $uvtheme = urvenue_ws_get_theme();

    if($uvvenuecode)
        $uvvenuelibinfo = urvenue_ws_get_venuelibinfo_byvenuecode($uvvenuecode);

    if(!$uvvenuelibinfo){
        $uvmapmanageentid = urvenue_ws_get_arg($uvargs, "manageentid");

        if($uvmapmanageentid)
            $uvvenuelibinfo = array(
                "manageentid" => $uvmapmanageentid,
                "providerid" => $uvmapmanageentid,
                "resellerid" => $uvmapmanageentid,
            );
    }

    if(is_array($uvargs) and $uvdate and $uvvenuecode and $uvecozone){
        $uvecozonestd = urvenue_ws_standardize_ecozone($uvecozoneevent);
        $uveventcode = "EVE" . str_replace("VEN", "", $uvvenuecode) . str_replace("ECZ", "", $uvecozonestd) . gmdate("Ymd", strtotime($uvdate));
        $uveventinfoandeczmap = urvenue_ws_get_event($uveventcode, array("returnecozonesmap" => 1));
        $uveventinfo = $uveventinfoandeczmap["event"];
        $uvinventorymapdata = $uvmapstage = $uvecozoneslist = $uvecozoneback = "";

        if(urvenue_ws_get_arg($uvargs, "homeecozone")){
            $uveventinfoandeczmap["ecozonesmap"] = "";
            $uveventinfo["ecozones"] = "";
            $uvecozoneback = "<div class='uws-map-ecozone-back-cont'><a href='#uws-ecozone-back' class='uwsjs-map-ecozone-back uws-list-ecozone-back' data-ecozone='" . urvenue_ws_get_arg($uvargs, "homeecozone") . "'><i class='uwsicon-right-open'></i> <span>" . urvenue_ws_get_arg($uvargs, "homename") . "</span></a></div>";

            if($uveventinfo["maineventcode"] and ($uveventinfo["maineventcode"] != $uveventinfo["eventcode"]))
                $uveventinfo["event-page-url"] = str_replace($uveventinfo["eventcode"], $uveventinfo["maineventcode"], $uveventinfo["event-page-url"]);
        }

        //Clean ecozones when there is only 1, force direct unique ecozone with item
        if(is_array($uveventinfoandeczmap["ecozonesmap"]) and count($uveventinfoandeczmap["ecozonesmap"]) == 1){
            $uveventinfo = reset($uveventinfoandeczmap["ecozonesmap"]);
            $uvecozonestd = urvenue_ws_standardize_ecozone($uveventinfo["ecozone"]);
            $uveventinfoandeczmap["ecozonesmap"] = "";
            $uveventinfo["ecozones"] = "";
        }

        //Check if we should show ecozones selection first
        if(is_array($uveventinfoandeczmap["ecozonesmap"])) {//has ecozones and we should show ecozones selection
            $uvargstmp = $uvargs;
            $uvargstmp["customfeedkey"] = "mapinventorykeep";
            $uvinventorymapdata = urvenue_ws_get_map_inventory_data($uvargstmp, $uveventinfo);

            if(isset($uvinventorymapdata["map"]) and isset($uvinventorymapdata["map"]["layout"]) and isset($uvinventorymapdata["map"]["layout"]["svgpath"]) and $uvinventorymapdata["map"]["layout"]["svgpath"]){
                $uvvenuemapsvg = $uvinventorymapdata["map"]["layout"]["svgpath"];
                $uvmapsvghtml = urvenue_ws_get_feed($uvvenuemapsvg);

                $uvecozoneslist = urvenue_ws_get_ecozones_list($uveventinfoandeczmap["ecozonesmap"], array("actionclass" => "uwsjs-select-invmap-ecozone"));
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
            $uvinventorymapdata = urvenue_ws_get_map_inventory_data($uvargs, $uveventinfo);
        }

        if(is_array($uvinventorymapdata)){
            $uvmapstagetempl = urvenue_ws_get_template("map/map-stage");
            //$uvmapitems = uws_get_plain_items_list($uvinventorymapdata["inventory"]);
            $uvmapitems = $uvinventorymapdata["items"];

            $uvmapinfo = $uvinventorymapdata["map"];

            //Is multiecozone integration
            if(isset($uveventinfo["ecozones"]) and is_array($uveventinfo["ecozones"]) and count($uveventinfo["ecozones"]) > 1){
                $uvinventorymasterlist = $uvinventorymapdata["inventory"]["D" . gmdate("ymd", strtotime($uvdate))]["venues"][$uvvenuecode]["masterlist"];
                $uvmapinfo = urvenue_ws_map_tomultiecozones($uvmapinfo, $uvinventorymasterlist);
            }
            else{
                $uvmapinfo = (isset($uvinventorymapdata["ecomaps"]) and isset($uvinventorymapdata["ecomaps"][$uvecozonestd])) ? $uvinventorymapdata["ecomaps"][$uvecozonestd] : "";

                if($uvmapinfo)
                    $uvmapinfo["secitems"] = urvenue_ws_map_mascodes_to_mastercodes($uvmapinfo["secitems"], $uvmapitems, $uveventinfo);
            }

            if($uvmapinfo){
                //$uvmapinfo = $uvinventorymapdata["map"];
                $uvvenuemapsvg = $uvmapinfo["layout"]["svgpath"];
                $uvseatingtype = ($uvmapinfo["header"]["seatingtype"]) ? $uvmapinfo["header"]["seatingtype"] : "section";
                $uvcredits = urvenue_ws_get_uwscredits();
                $uvmapecozonesel = "";

                if(isset($urvenue_ws_core_lib["map"]) and isset($urvenue_ws_core_lib["map"]["mappage-showecomaps"]) and $urvenue_ws_core_lib["map"]["mappage-showecomaps"])
                    $uvmapecozonesel = urvenue_ws_get_ecomaps_sel($uvinventorymapdata["ecomaps"], $uvargs);

                /*if($uveventinfo && is_array($uveventinfo) && isset($uveventinfo["ecozones"]) && is_array($uveventinfo["ecozones"]) && count($uveventinfo["ecozones"]) > 1)
                    $uvargs["ecozones"] = $uveventinfo["ecozones"];*/

                $uvsdate = gmdate("ymd", strtotime($uvdate));
                $uvecozone = urvenue_ws_standardize_ecozone($uvecozone);

                //$uvmapitems = uws_get_eventinventory_list_feed($uvargs);
                //$uvmapitems = (is_array($uvmapitems) and is_array($uvmapitems["items"])) ? uws_keys_to_mastercode($uvmapitems["items"]) : "";

                $uvinventorymapdata["map"] = $uvmapinfo;
                $uvmaplist = urvenue_ws_map_get_list($uvinventorymapdata, $uvargs, $uvlisttype, $uvmapitems);

                if($uvvenuemapsvg){
                    //$uvvenuemapsvgurl = "https://" . $uvvenuemapsvg;
                    $uvmapsvghtml = urvenue_ws_get_feed($uvvenuemapsvg);
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
                $uveventinfotempl = urvenue_ws_get_template("map/map-event-info");
                $uveventinfohtml = ($uveventinfo) ? urvenue_ws_replace_event_vars($uveventinfo, $uveventinfotempl) : "";

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
                    "selsstring" => urvenue_ws_get_map_selsstring($uvargs),
                    "eventinfo" => $uveventinfohtml,
                    "ecozonesel" => $uvmapecozonesel,
                    "ecozoneback" => $uvecozoneback,
                    "theme" => $uvtheme,
                );
            }
        }
    }

    if((!$uvmapinfo or !$uvmapinfo["locations"] or !$uvmapinfo["seclocs"] or !$uvmapinfo["secitems"] or !$uvmapitems) and !$uvecozoneslist){//if not map items
        $uveventinfotempl = urvenue_ws_get_template("map/map-stage-nomap");
        if(!is_array($uvmapstage))
            $uvmapstage = array();
        $uvmapstage["stagehtml"] = $uveventinfotempl;
    }

    return $uvmapstage;
}

//Get Admission items for map
function urvenue_ws_map_get_admission($uvitems, $uvecozone = ""){
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
            $uvitemecozone = (isset($uvitem["ecocode"]) and $uvitem["ecocode"]) ? urvenue_ws_standardize_ecozone($uvitem["ecocode"]) : "";
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
function urvenue_ws_get_map_inventory_data($uvargs, $uveventinfo = ""){
    $uvinventorymapdata = "";
    $uvdate = urvenue_ws_get_arg($uvargs, "date");
    $uvvenuecode = urvenue_ws_get_arg($uvargs, "venuecode");
    $uvecozone = $uvecozoneevent = urvenue_ws_get_arg($uvargs, "ecozone");
    $uvcustomfeedkey = urvenue_ws_get_arg($uvargs, "customfeedkey", "mapinventory");
    $uvecozone = urvenue_ws_standardize_ecozone($uvecozone);
    $uvincludeecozone = 1;

    //Remove ecozone parameter if event has more than one ecozone (known as groupings on old API)
    /*if(isset($uveventinfo["ecozones"]) and is_array($uveventinfo["ecozones"]) and count($uveventinfo["ecozones"]) > 1)
        $uvincludeecozone = 0;*/

    $uvfeedtoken = "venuecode=" . $uvvenuecode . "&caldate=" . $uvdate;
    if($uvincludeecozone)
        $uvfeedtoken .= "&ecozone=" . $uvecozone;

    $uvinventorymapfeed = urvenue_ws_get_feed($uvcustomfeedkey, $uvfeedtoken);

    if(is_array($uvinventorymapfeed) and $uvinventorymapfeed["uv"]["success"]["status"] == "success"){
        $uvinventorymapdata = $uvinventorymapfeed["uv"]["data"];
    }

    return $uvinventorymapdata;
}

/*Get map list
    Requires: inventory feed array, venuecode, listtype
    Returns: html of the map list
*/
function urvenue_ws_map_get_list($uvinventorymapfeed, $uvargs, $uvlisttype = "sections", $uvmapitems = ""){
    $uvmaplist = $uvmaplistitems = "";

    if(is_array($uvinventorymapfeed)){
        $uvmaplisttempl = urvenue_ws_get_template("map/map-list");
        $uvmapinfo = $uvinventorymapfeed["map"];
        $uvsections = $uvmapinfo["sections"];
        $uvsecitems = $uvmapinfo["secitems"];
        $uvlocations = $uvmapinfo["locations"];
        $uvinactiveclass = "";
        $uvecozone = urvenue_ws_get_arg($uvargs, "ecozone");
        $uvecozone = urvenue_ws_standardize_ecozone($uvecozone);

        $uvmapadmissionitem = urvenue_ws_map_get_admission($uvmapitems, $uvecozone);

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
            $uvdate = urvenue_ws_get_arg($uvargs, "date");
            $uvvenuecode = urvenue_ws_get_arg($uvargs, "venuecode");
            $uvsdate = gmdate("ymd", strtotime($uvdate));
            $uvmapitemslist = $uvinventorymapfeed["items"]; //$uvinventorymapfeed["inventory"]["D" . $uvsdate]["venues"][$uvvenuecode]["ecozones"][$uvecozone]["items"];
            $uvmaplistitems = urvenue_ws_get_map_booktypeslist($uvmapitemslist, $uvmapinfo);
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
function urvenue_ws_get_map_booktypeslist($uvitems, $uvmapinfo){
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
function urvenue_ws_map_mascodes_to_mastercodes($uvmascodesarray, $uvitems, $uveventdata = ""){
    $uvmastercodesarray = array();

    if(is_array($uvmascodesarray) and is_array($uvitems)){
        $uvecozone = urvenue_ws_get_arg($uveventdata, "ecozone", "ECZ000");
        $uvecozone = urvenue_ws_standardize_ecozone($uvecozone);

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
function urvenue_ws_map_tomultiecozones($uvmapinfo, $uvinventorymasterlist){
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
