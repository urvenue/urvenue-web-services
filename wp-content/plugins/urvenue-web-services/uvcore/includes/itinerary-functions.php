<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*Prints Itinerary
    Requires: guestinfo(array with all the guest/itinerary info)
    Returns: Prints html of guest itinerary
*/
function urvenue_ws_itinerary(){
    $uvitinerary = "";
    $uvguestinfo = urvenue_ws_get_dummyapi("guestinfo");
    $uvitineraryconttemp = urvenue_ws_get_template("itinerary/itinerary-container");
    $uvresstartddate = gmdate("M j", strtotime($uvguestinfo["reservationinfo"]["checkin"]));
    $uvresendddate = gmdate("M j, Y", strtotime($uvguestinfo["reservationinfo"]["checkout"]));
    //$uvstartdate = uws_get_arg($uvargs, "date", $uvguestinfo["reservationinfo"]["checkin"]);
    //$uvenddate = date("Ymd", strtotime($uvstartdate . " +5 days"));

    $uvitineraryweekview = urvenue_ws_get_itinerary_weekview($uvguestinfo);
    $uvitinerarydayview = urvenue_ws_get_itinerary_dayview($uvguestinfo);
    $uvitinerarydaytimeview = urvenue_ws_get_itinerary_daytimeview($uvguestinfo);

    $uvitinerary = str_replace(
        array(
            "{reservationdrange}",
            "{itineraryweekview}",
            "{itinerarydayview}",
            "{itinerarydaytimeview}",
            "{itinerarydate}",
            "{reservationenddate}",
            "{ninvmonths}"
        ),
        array(
            $uvresstartddate . " - " . $uvresendddate,
            $uvitineraryweekview,
            $uvitinerarydayview,
            $uvitinerarydaytimeview,
            $uvguestinfo["reservationinfo"]["checkin"],
            $uvguestinfo["reservationinfo"]["checkout"],
            urvenue_ws_get_invmonths($uvguestinfo["reservationinfo"]["checkin"], $uvguestinfo["reservationinfo"]["checkout"])
        ),
        $uvitineraryconttemp
    );

    echo wp_kses_post( $uvitinerary );
}

/*Get Week View html
    Requires: guestinfo(array with all the guest/itinerary info)
    Returns: Week view html
*/
function urvenue_ws_get_itinerary_weekview($uvguestinfo, $uvargs = ""){
    $uvitineraryweekview = "";

    if(is_array($uvguestinfo)){
        $uvweekheader = $uvweekrows = "";
        $uvweekviewtemp = urvenue_ws_get_template("itinerary/itinerary-view-week");
        $uvititemtemplate = urvenue_ws_get_template("itinerary/itinerary-card-item");
        $uvdate = urvenue_ws_get_arg($uvargs, "date", $uvguestinfo["reservationinfo"]["checkin"]);
        $uvndates = urvenue_ws_get_arg($uvargs, "ndates", 5);
        $uvinvertal = urvenue_ws_get_arg($uvargs, "inverval", 60);
        $uvminhour = urvenue_ws_get_arg($uvargs, "minhour", "10600");
        $uvmaxhour = urvenue_ws_get_arg($uvargs, "minhour", "12300");
        $uvitineraryitemslist = urvenue_ws_get_itineraryitemlist($uvguestinfo);

        //Create week headers
        $uvweekheader = "<div class='uwshourlabel'></div>";
        for($i=0; $i<$uvndates; $i++){
            $uvdweekday = gmdate("D", strtotime($uvdate . " +$i days"));
            $uvmonthdday = gmdate("d", strtotime($uvdate . " +$i days"));

            $uvweekheader .= "
                <div class='uwsweekviewcol uwsddate'>
                    <div class='uwsdweedday'>$uvdweekday</div>
                    <div class='uwsmonthdday'><span>$uvmonthdday<span></div>
                </div>
            ";
        }

        //Create week rows
        $uvntimerows = urvenue_ws_get_ntimerows($uvminhour, $uvmaxhour, $uvinvertal);
        for($i=0; $i<$uvntimerows; $i++){
            $uvthistime = urvenue_ws_add_minutestotime($uvminhour, $i * $uvinvertal);
            $uvdtime = urvenue_ws_get_formattime($uvthistime);
            $uvinsttime = substr($uvthistime, 0, 3);

            $uvweekrows .= "<div class='uwsweekviewrow uws-weekview-insttime-$uvinsttime'><div class='uwshourlabel'><span class='uwstimelabel'>$uvdtime</span></div>";
            for($j=0; $j<$uvndates; $j++){
                $uvittimeitems = "";
                $uvthiscoldate = gmdate("Ymd", strtotime($uvdate . " +$j days"));
                $uvniitems = (isset($uvitineraryitemslist["ITD" . $uvinsttime . $uvthiscoldate]) and is_array($uvitineraryitemslist["ITD" . $uvinsttime . $uvthiscoldate])) ? count($uvitineraryitemslist["ITD" . $uvinsttime . $uvthiscoldate]) : 0;

                if(isset($uvitineraryitemslist["ITD" . $uvinsttime . $uvthiscoldate]) and is_array($uvitineraryitemslist["ITD" . $uvinsttime . $uvthiscoldate])){
                    foreach($uvitineraryitemslist["ITD" . $uvinsttime . $uvthiscoldate] as $uvitineraryitem){
                        $uvititem = urvenue_ws_replace_itinerary_item_vars($uvitineraryitem, $uvititemtemplate, $uvguestinfo);
                        $uvthisititemendtime = (isset($uvitineraryitem["endtime"])) ? $uvitineraryitem["endtime"] : "";
                        $uvititemnslots = urvenue_ws_get_ntimerows($uvitineraryitem["starttime"], $uvthisititemendtime, $uvinvertal);
                        $uvititem = str_replace("{ititemnslots}", $uvititemnslots, $uvititem);
                        $uvittimeitems .= $uvititem;
                    }
                }

                $uvweekrows .= "
                    <div class='uwsweekviewcol uws-weekview-instimedt-{$uvinsttime}{$uvthiscoldate} uws-weekview-nitems-$uvniitems'>
                        $uvittimeitems
                    </div>
                ";
            }
            $uvweekrows .= "</div>";
        }

        $uvitineraryweekview = str_replace(
            array(
                "{weekviewheaders}",
                "{weekviewrows}"
            ),
            array(
                $uvweekheader,
                $uvweekrows
            ),
            $uvweekviewtemp
        );
    }

    return $uvitineraryweekview;
}

/*Replate itinerary item variables codes
    Requires: item(itinerary item array), template(html template with varriable codes), guestinfo(array with all the guest/itinerary info)
*/
function urvenue_ws_replace_itinerary_item_vars($uvititem, $uvtemplate, $uvguestinfo = ""){
    global $urvenue_ws_core_lib;

    $uvtheititem = $uvtemplate;

    if(is_array($uvititem)){
        $uvititemimage = (isset($uvititem["image"]) and $uvititem["image"]) ? $uvititem["image"] : "";
        $uvititemguestscircles = (isset($uvititem["guests"]) and $uvguestinfo) ? urvenue_ws_get_guestscircles($uvguestinfo, $uvititem["guests"]) : "";
        $uvdateddate = gmdate($urvenue_ws_core_lib["events"]["global-dateformat"], strtotime($uvititem["date"]));
        $uvitemdtimes = urvenue_ws_get_formattime($uvititem["starttime"]);
        $uvitemdtimes = (isset($uvititem["endtime"]) and $uvititem["starttime"]) ? $uvitemdtimes . " - " . urvenue_ws_get_formattime($uvititem["endtime"]) : $uvitemdtimes;

        $uvtheititem = str_replace(
            array(
                "{ititemname}",
                "{ititemimage}",
                "{ititemtype}",
                "{ititemguestscircles}",
                "{ititemdtime}",
                "{ititemid}",
                "{ititemddate}",
                "{ititemdtimes}",
                "{ititeminstructions}",
            ),
            array(
                $uvititem["name"],
                $uvititemimage,
                $uvititem["type"],
                $uvititemguestscircles,
                urvenue_ws_get_formattime($uvititem["starttime"]),
                $uvititem["itd"],
                $uvdateddate,
                $uvitemdtimes,
                $uvititem["instructions"]
            ),
            $uvtemplate
        );

        //Conditional replaces
        if($uvititemimage)
            $uvtheititem = str_replace(array("{ifititemimage}", "{/ifititemimage}"), array("", ""), $uvtheititem);
        else
            $uvtheititem = preg_replace('/{ifititemimage}(.|\n)*{\/ifititemimage}/', '', $uvtheititem);

        if($uvititem["type"] == "offer")
            $uvtheititem = str_replace(array("{ifititemisoffer}", "{/ifititemisoffer}"), array("", ""), $uvtheititem);
        else
            $uvtheititem = preg_replace('/{ifititemisoffer}(.|\n)*{\/ifititemisoffer}/', '', $uvtheititem);
    }

    return $uvtheititem;
}

/*Get guests circles
    Requires:
    Returns: html with the guest circles for an actity
*/
function urvenue_ws_get_guestscircles($uvguestinfo, $uvguests){
    $uvguestscircles = "";

    if(is_array($uvguestinfo) and is_array($uvguests)){
        $uvguestscircles = "<ul class='uwsguestscir'>";
        foreach($uvguests as $uvguestcode){
            $uvguestfname = $uvguestinfo["guestsinfo"][$uvguestcode]["firstname"];
            $uvguestchar = substr($uvguestfname, 0, 1);

            $uvguestscircles .= "<li>$uvguestchar</li>";
        }
        $uvguestscircles .= "</ul>";
    }

    return $uvguestscircles;
}

/*Get array with items(activities) list ready to proccess on the itinerary
    Requires: guestinfo(array with all the guest/itinerary info)
    Optional: uvkeytype(key time+date by default, if 'onlydate' it will group the items only by date, if uniq it will be indivitual)
    Returns: array with items for the itinerary builder
*/
function urvenue_ws_get_itineraryitemlist($uvguestinfo, $uvkeytype = ""){
    $uvitineraryitemslist = "";

    if(is_array($uvguestinfo)){
        //Add checkin and checkout items
        $uvcheckininstimedt = substr($uvguestinfo["reservationinfo"]["checkintime"], 0, 3) . gmdate("Ymd", strtotime($uvguestinfo["reservationinfo"]["checkin"]));
        $uvcheckininsdt = gmdate("Ymd", strtotime($uvguestinfo["reservationinfo"]["checkin"]));
        $uvcheckinitd = ($uvkeytype == "onlydate") ? "ITD" . $uvcheckininsdt : "ITD" . $uvcheckininstimedt;
        $uvcheckinitd = ($uvkeytype == "iniq") ? "ITDcheckin" : $uvcheckinitd;

        $uvcheckoutstimedt = substr($uvguestinfo["reservationinfo"]["checkouttime"], 0, 3) . gmdate("Ymd", strtotime($uvguestinfo["reservationinfo"]["checkout"]));
        $uvcheckoutsdt = gmdate("Ymd", strtotime($uvguestinfo["reservationinfo"]["checkout"]));
        $uvcheckoutitd = ($uvkeytype == "onlydate") ? "ITD" . $uvcheckoutsdt : "ITD" . $uvcheckoutstimedt;
        $uvcheckoutitd = ($uvkeytype == "iniq") ? "ITDcheckout" : $uvcheckoutitd;

        $uvitineraryitemslist = urvenue_ws_add_itineraryitemtolist($uvitineraryitemslist, $uvcheckinitd,
            array(
                "itd" => "ITDcheckin",
                "name" => "Check in",
                "guests" => urvenue_ws_get_itineraryguestslist($uvguestinfo),
                "type" => "checkin",
                "starttime" => $uvguestinfo["reservationinfo"]["checkintime"],
                "date" => $uvguestinfo["reservationinfo"]["checkin"],
                "instructions" => "Checkin on the lobby"
            ),
        );

        $uvitineraryitemslist = urvenue_ws_add_itineraryitemtolist($uvitineraryitemslist, $uvcheckoutitd,
            array(
                "itd" => "ITDcheckout",
                "name" => "Check-Out",
                "guests" => urvenue_ws_get_itineraryguestslist($uvguestinfo),
                "type" => "checkout",
                "starttime" => $uvguestinfo["reservationinfo"]["checkouttime"],
                "date" => $uvguestinfo["reservationinfo"]["checkout"],
                "instructions" => "Checkout on the lobby"
            ),
        );

        //Add activities items
        foreach($uvguestinfo["itinerary"] as $uviacid => $uviact){
            $uviactinsttimedt = substr($uviact["starttime"], 0, 3) . gmdate("Ymd", strtotime($uviact["date"]));
            $uviactinstdt = gmdate("Ymd", strtotime($uviact["date"]));
            $uviactinstitduniq = "ITD" . $uviacid;
            $uviactinstitd = ($uvkeytype == "onlydate") ? "ITD" . $uviactinstdt : "ITD" . $uviactinsttimedt;
            $uviactinstitd = ($uvkeytype == "iniq") ? $uviactinstitduniq : $uviactinstitd;
            $uviactarr = $uviact;
            $uviactarr["type"] = "activity";
            $uviactarr["itd"] = $uviactinstitduniq;

            $uvitineraryitemslist = urvenue_ws_add_itineraryitemtolist($uvitineraryitemslist, $uviactinstitd, $uviactarr);
        }

        //Add offers items
        foreach($uvguestinfo["offers"] as $uvoffid => $uvoffer){
            $uvioffinsttimedt = substr($uvoffer["starttime"], 0, 3) . gmdate("Ymd", strtotime($uvoffer["date"]));
            $uvioffinstdt = gmdate("Ymd", strtotime($uvoffer["date"]));
            $uvioffinstitduniq = "ITD" . $uvoffid;
            $uvioffinstitd = ($uvkeytype == "onlydate") ? "ITD" . $uvioffinstdt : "ITD" . $uvioffinsttimedt;
            $uvioffinstitd = ($uvkeytype == "iniq") ? $uvioffinstitduniq : $uvioffinstitd;
            $uvioffarr = $uvoffer;
            $uvioffarr["type"] = "offer";
            $uvioffarr["itd"] = $uvioffinstitduniq;

            $uvitineraryitemslist = urvenue_ws_add_itineraryitemtolist($uvitineraryitemslist, $uvioffinstitd, $uvioffarr);
        }
    }

    return $uvitineraryitemslist;
}

/*Add itinerary item to the array list
    Requires: curitems(array with the current list), key of the time date, info for the itinerary item
    Returns: array including new item
*/
function urvenue_ws_add_itineraryitemtolist($uvcuritems, $uvitemkey, $uviteminfo){
    if(!is_array($uvcuritems))
        $uvcuritems = array();

    if(!isset($uvcuritems[$uvitemkey]))
        $uvcuritems[$uvitemkey] = array();

    $uvcuritems[$uvitemkey][] = $uviteminfo;

    return $uvcuritems;
}

/*Get list of guests from guest info
    Requires: guestinfo(array with all the guest/itinerary info)
    Returns: array guests
*/
function urvenue_ws_get_itineraryguestslist($uvguestinfo, $uvgueststype = "all"){
    $uvguests = "";

    if(is_array($uvguestinfo)){
        $uvguests = array();
        foreach($uvguestinfo["guestsinfo"] as $uvguestcode => $uvguest){
            $uvguests[] = $uvguestcode;
        }
    }

    return $uvguests;
}

/*Get Day View html
    Requires: guestinfo(array with all the guest/itinerary info)
    Returns: Day view html
*/
function urvenue_ws_get_itinerary_dayview($uvguestinfo, $uvargs = ""){
    global $urvenue_ws_core_lib;
    $uvitinerarydayview = "";

    if(is_array($uvguestinfo)){
        $uvitdates = $uvitedate = "";
        $uvdayviewtemp = urvenue_ws_get_template("itinerary/itinerary-view-day");
        $uvdaydateviewtemp = urvenue_ws_get_template("itinerary/itinerary-view-day-single");
        $uvititemtemplate = urvenue_ws_get_template("itinerary/itinerary-card-item");
        $uvdate = urvenue_ws_get_arg($uvargs, "date", $uvguestinfo["reservationinfo"]["checkin"]);
        $uvcheckoutdate = $uvguestinfo["reservationinfo"]["checkout"];
        $uvndates = urvenue_ws_get_arg($uvargs, "ndates", 5);
        $uvinvertal = urvenue_ws_get_arg($uvargs, "inverval", 60);
        $uvitineraryitemslist = urvenue_ws_get_itineraryitemlist($uvguestinfo, "onlydate");

        for($i=0; $i<$uvndates; $i++){
            $uvdateititems = $uvittimeitems = "";
            $uvdatedday = gmdate("l", strtotime($uvdate . " +$i days"));
            $uvdatedate = gmdate("Y-m-d", strtotime($uvdate . " +$i days"));
            $uvdateddate = gmdate($urvenue_ws_core_lib["events"]["global-dateformat"], strtotime($uvdate . " +$i days"));
            $uvthissdate = gmdate("Ymd", strtotime($uvdate . " +$i days"));
            $uvniitems = (is_array($uvitineraryitemslist["ITD" . $uvthissdate])) ? count($uvitineraryitemslist["ITD" . $uvthissdate]) : 0;
            $uvdayviewclass = ($uvcheckoutdate == $uvdatedate) ? "uwsislastbookdate" : "";

            if(is_array($uvitineraryitemslist["ITD" . $uvthissdate])){
                foreach($uvitineraryitemslist["ITD" . $uvthissdate] as $uvitineraryitem){
                    $uvititem = urvenue_ws_replace_itinerary_item_vars($uvitineraryitem, $uvititemtemplate, $uvguestinfo);
                    $uvthisendtime = (isset($uvitineraryitem["endtime"])) ? $uvitineraryitem["endtime"] : "";
                    $uvititemnslots = urvenue_ws_get_ntimerows($uvitineraryitem["starttime"], $uvthisendtime, $uvinvertal);
                    $uvititem = str_replace("{ititemnslots}", $uvititemnslots, $uvititem);
                    $uvititemdtime = urvenue_ws_get_formattime($uvitineraryitem["starttime"]);
                    $uvittimeitems .= "<div class='uwsititemcont'>" . $uvititem . "<span class='uwsdtime'>$uvititemdtime</span></div>";
                }
            }

            $uvdateititems .= "
                <div class='uwsdayviewitems uws-dayview-insdt-{$uvthissdate} uws-dayview-nitems-$uvniitems'>
                    $uvittimeitems
                </div>
            ";

            $uvitedate = str_replace(
                array(
                    "{itdday}",
                    "{itddate}",
                    "{experiencesurl}",
                    "{dateititems}",
                    "{dayviewclass}"
                ),
                array(
                    $uvdatedday,
                    $uvdateddate,
                    "{experiencesurl--}",
                    $uvdateititems,
                    $uvdayviewclass,
                ),
                $uvdaydateviewtemp
            );

            if($uvcheckoutdate == $uvdatedate)
                $uvitedate = str_replace(array("{iflastbookdate}", "{/iflastbookdate}"), array("", ""), $uvitedate);
            else
                $uvitedate = preg_replace('/{iflastbookdate}(.|\n)*{\/iflastbookdate}/', '', $uvitedate);

            $uvitdates .= $uvitedate;
        }

        $uvitinerarydayview = str_replace(
            array(
                "{dayviewrows}"
            ),
            array(
                $uvitdates
            ),
            $uvdayviewtemp
        );
    }

    return $uvitinerarydayview;
}


/*Get Day Time (1 day with hours) View html
    Requires: guestinfo(array with all the guest/itinerary info)
    Returns: day time view html
*/
function urvenue_ws_get_itinerary_daytimeview($uvguestinfo, $uvargs = ""){
    global $urvenue_ws_core_lib;
    $uvitinerarydaytimeview = "";

    if(is_array($uvguestinfo)){
        $uvdaytimerows = "";
        $uvdaytimeviewtemp = urvenue_ws_get_template("itinerary/itinerary-view-daytime");
        $uvititemtemplate = urvenue_ws_get_template("itinerary/itinerary-card-item");
        $uvdate = urvenue_ws_get_arg($uvargs, "date", $uvguestinfo["reservationinfo"]["checkin"]);
        //$uvdate = "2023-06-04";

        $uvinvertal = urvenue_ws_get_arg($uvargs, "inverval", 60);
        $uvminhour = urvenue_ws_get_arg($uvargs, "minhour", "10600");
        $uvmaxhour = urvenue_ws_get_arg($uvargs, "minhour", "12300");

        $uvdatedday = gmdate("l", strtotime($uvdate));
        $uvdateddate = gmdate($urvenue_ws_core_lib["events"]["global-dateformat"], strtotime($uvdate));
        $uvdatedate = gmdate("Y-m-d", strtotime($uvdate));

        $uvcheckoutdate = $uvguestinfo["reservationinfo"]["checkout"];
        $uvdayviewclass = ($uvcheckoutdate == $uvdatedate) ? "uwsislastbookdate" : "";
        $uvitineraryitemslist = urvenue_ws_get_itineraryitemlist($uvguestinfo);

        //Create week rows
        $uvntimerows = urvenue_ws_get_ntimerows($uvminhour, $uvmaxhour, $uvinvertal);

        for($i=0; $i<$uvntimerows; $i++){
            $uvthistime = urvenue_ws_add_minutestotime($uvminhour, $i * $uvinvertal);
            $uvdtime = urvenue_ws_get_formattime($uvthistime);
            $uvinsttime = substr($uvthistime, 0, 3);

            $uvdaytimerows .= "<div class='uwsdaytimerow uws-daytimeview-insttime-$uvinsttime'><div class='uwshourlabel'><span class='uwstimelabel'>$uvdtime</span></div>";

            $uvittimeitems = "";
            $uvthisrowdate = gmdate("Ymd", strtotime($uvdate));

            $uvniitems = (isset($uvitineraryitemslist["ITD" . $uvinsttime . $uvthisrowdate])) ? count($uvitineraryitemslist["ITD" . $uvinsttime . $uvthisrowdate]) : 0;

            if(isset($uvitineraryitemslist["ITD" . $uvinsttime . $uvthisrowdate])){
                foreach($uvitineraryitemslist["ITD" . $uvinsttime . $uvthisrowdate] as $uvitineraryitem){
                    $uvititem = urvenue_ws_replace_itinerary_item_vars($uvitineraryitem, $uvititemtemplate, $uvguestinfo);
                    $uvthisendtime = (isset($uvitineraryitem["endtime"])) ? $uvitineraryitem["endtime"] : "";
                    $uvititemnslots = urvenue_ws_get_ntimerows($uvitineraryitem["starttime"], $uvthisendtime, $uvinvertal);
                    $uvititem = str_replace("{ititemnslots}", $uvititemnslots, $uvititem);
                    $uvittimeitems .= $uvititem;
                }
            }

            $uvdaytimerows .= "
                <div class='uwsdaytimeviewcell uws-weekview-instimedt-{$uvinsttime}{$uvthisrowdate} uws-weekview-nitems-$uvniitems'>
                    $uvittimeitems
                </div>
            ";
            $uvdaytimerows .= "</div>";
        }

        if($uvcheckoutdate <= $uvdatedate)
            $uvdaytimeviewtempthis = str_replace(array("{iflastbookdate}", "{/iflastbookdate}"), array("", ""), $uvdaytimeviewtemp);
        else
            $uvdaytimeviewtempthis = preg_replace('/{iflastbookdate}(.|\n)*{\/iflastbookdate}/', '', $uvdaytimeviewtemp);

        $uvitinerarydaytimeview = str_replace(
            array(
                "{itdday}",
                "{itddate}",
                "{experiencesurl}",
                "{dateititems}",
                "{dayviewclass}",
                "{daytimeviewrows}",
            ),
            array(
                $uvdatedday,
                "$uvdateddate",
                "{experiencesurl}",
                "{dateititems--}",
                $uvdayviewclass,
                $uvdaytimerows,
            ),
            $uvdaytimeviewtempthis
        );
    }

    return $uvitinerarydaytimeview;
}

/*Get Itinerary Tooltips
    Returns: array with itinerary tooltips
*/
function urvenue_ws_get_itinerarytooltips(){
    $uvitinerarytooltips = "";

    $uvguestinfo = urvenue_ws_get_dummyapi("guestinfo");
    $uvititems = urvenue_ws_get_itineraryitemlist($uvguestinfo, "iniq");

    if(is_array($uvititems)){
        $uvitinerarytooltips = array();
        $uvcarditemtooltiptemp = urvenue_ws_get_template("itinerary/itinerary-card-item-tooltip");

        foreach($uvititems as $uvititemkey => $uvititem){
            $uvititem = $uvititem[0];
            $uvititemhtml = urvenue_ws_replace_itinerary_item_vars($uvititem, $uvcarditemtooltiptemp, $uvguestinfo);

            $uvitinerarytooltips[$uvititemkey] = $uvititemhtml;
        }
    }

    return $uvitinerarytooltips;
}

/*Get the number of different months between 2 dates
    Returns: number of months
    Reestrictions: startdate should be always < enddate
*/
function urvenue_ws_get_invmonths($uvstartdate, $uvenddate){
    $uvnmonths = 1;

    if($uvstartdate and $uvenddate){
        $uvstartmonth = gmdate("n", strtotime($uvstartdate)) / 1;
        $uvendmonth = gmdate("n", strtotime($uvenddate)) / 1;

        if($uvstartmonth > $uvendmonth)
            $uvendmonth = $uvendmonth + 12;

        $uvnmonths = $uvendmonth - $uvstartmonth + 1;
    }

    return $uvnmonths;
}
