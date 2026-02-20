<?php

/*Prints Itinerary
    Requires: guestinfo(array with all the guest/itinerary info)
    Returns: Prints html of guest itinerary
*/
function uws_itinerary(){ 
    $uvitinerary = "";
    $uvguestinfo = uws_get_dummyapi("guestinfo");
    $uvitineraryconttemp = uws_get_template("itinerary/itinerary-container");
    $uvresstartddate = date("M j", strtotime($uvguestinfo["reservationinfo"]["checkin"]));
    $uvresendddate = date("M j, Y", strtotime($uvguestinfo["reservationinfo"]["checkout"]));
    //$uvstartdate = uws_get_arg($uvargs, "date", $uvguestinfo["reservationinfo"]["checkin"]);
    //$uvenddate = date("Ymd", strtotime($uvstartdate . " +5 days"));

    $uvitineraryweekview = uws_get_itinerary_weekview($uvguestinfo);
    $uvitinerarydayview = uws_get_itinerary_dayview($uvguestinfo);
    $uvitinerarydaytimeview = uws_get_itinerary_daytimeview($uvguestinfo);

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
            uws_get_invmonths($uvguestinfo["reservationinfo"]["checkin"], $uvguestinfo["reservationinfo"]["checkout"])
        ),
        $uvitineraryconttemp
    );

    // @Axl
    // echo $uvitinerary;
    echo wp_kses_post( $uvitinerary );
    // @Axl End
}

/*Get Week View html
    Requires: guestinfo(array with all the guest/itinerary info)
    Returns: Week view html
*/
function uws_get_itinerary_weekview($uvguestinfo, $uvargs = ""){
    $uvitineraryweekview = "";

    if(is_array($uvguestinfo)){
        $uvweekheader = $uvweekrows = "";
        $uvweekviewtemp = uws_get_template("itinerary/itinerary-view-week");
        $uvititemtemplate = uws_get_template("itinerary/itinerary-card-item");
        $uvdate = uws_get_arg($uvargs, "date", $uvguestinfo["reservationinfo"]["checkin"]);
        $uvndates = uws_get_arg($uvargs, "ndates", 5);
        $uvinvertal = uws_get_arg($uvargs, "inverval", 60);
        $uvminhour = uws_get_arg($uvargs, "minhour", "10600");
        $uvmaxhour = uws_get_arg($uvargs, "minhour", "12300");
        $uvitineraryitemslist = uws_get_itineraryitemlist($uvguestinfo);

        //Create week headers
        $uvweekheader = "<div class='uwshourlabel'></div>";
        for($i=0; $i<$uvndates; $i++){
            $uvdweekday = date("D", strtotime($uvdate . " +$i days"));
            $uvmonthdday = date("d", strtotime($uvdate . " +$i days"));

            $uvweekheader .= "
                <div class='uwsweekviewcol uwsddate'>
                    <div class='uwsdweedday'>$uvdweekday</div>
                    <div class='uwsmonthdday'><span>$uvmonthdday<span></div>
                </div>
            ";
        }

        //Create week rows
        $uvntimerows = uws_get_ntimerows($uvminhour, $uvmaxhour, $uvinvertal);
        for($i=0; $i<$uvntimerows; $i++){
            $uvthistime = uws_add_minutestotime($uvminhour, $i * $uvinvertal);
            $uvdtime = uws_get_formattime($uvthistime);
            $uvinsttime = substr($uvthistime, 0, 3);

            $uvweekrows .= "<div class='uwsweekviewrow uws-weekview-insttime-$uvinsttime'><div class='uwshourlabel'><span class='uwstimelabel'>$uvdtime</span></div>";
            for($j=0; $j<$uvndates; $j++){
                $uvittimeitems = "";
                $uvthiscoldate = date("Ymd", strtotime($uvdate . " +$j days"));
                $uvniitems = (isset($uvitineraryitemslist["ITD" . $uvinsttime . $uvthiscoldate]) and is_array($uvitineraryitemslist["ITD" . $uvinsttime . $uvthiscoldate])) ? count($uvitineraryitemslist["ITD" . $uvinsttime . $uvthiscoldate]) : 0;

                if(isset($uvitineraryitemslist["ITD" . $uvinsttime . $uvthiscoldate]) and is_array($uvitineraryitemslist["ITD" . $uvinsttime . $uvthiscoldate])){
                    foreach($uvitineraryitemslist["ITD" . $uvinsttime . $uvthiscoldate] as $uvitineraryitem){
                        $uvititem = uws_replace_itinerary_item_vars($uvitineraryitem, $uvititemtemplate, $uvguestinfo);
                        $uvthisititemendtime = (isset($uvitineraryitem["endtime"])) ? $uvitineraryitem["endtime"] : "";
                        $uvititemnslots = uws_get_ntimerows($uvitineraryitem["starttime"], $uvthisititemendtime, $uvinvertal);
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
function uws_replace_itinerary_item_vars($uvititem, $uvtemplate, $uvguestinfo = ""){
    global $uws_core_lib;

    $uvtheititem = $uvtemplate;

    if(is_array($uvititem)){
        $uvititemimage = (isset($uvititem["image"]) and $uvititem["image"]) ? $uvititem["image"] : "";
        $uvititemguestscircles = (isset($uvititem["guests"]) and $uvguestinfo) ? uws_get_guestscircles($uvguestinfo, $uvititem["guests"]) : "";
        $uvdateddate = date($uws_core_lib["events"]["global-dateformat"], strtotime($uvititem["date"]));
        $uvitemdtimes = uws_get_formattime($uvititem["starttime"]);
        $uvitemdtimes = (isset($uvititem["endtime"]) and $uvititem["starttime"]) ? $uvitemdtimes . " - " . uws_get_formattime($uvititem["endtime"]) : $uvitemdtimes;

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
                uws_get_formattime($uvititem["starttime"]),
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
function uws_get_guestscircles($uvguestinfo, $uvguests){
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
function uws_get_itineraryitemlist($uvguestinfo, $uvkeytype = ""){
    $uvitineraryitemslist = "";

    if(is_array($uvguestinfo)){
        //Add checkin and checkout items
        $uvcheckininstimedt = substr($uvguestinfo["reservationinfo"]["checkintime"], 0, 3) . date("Ymd", strtotime($uvguestinfo["reservationinfo"]["checkin"]));
        $uvcheckininsdt = date("Ymd", strtotime($uvguestinfo["reservationinfo"]["checkin"]));
        $uvcheckinitd = ($uvkeytype == "onlydate") ? "ITD" . $uvcheckininsdt : "ITD" . $uvcheckininstimedt;
        $uvcheckinitd = ($uvkeytype == "iniq") ? "ITDcheckin" : $uvcheckinitd;

        $uvcheckoutstimedt = substr($uvguestinfo["reservationinfo"]["checkouttime"], 0, 3) . date("Ymd", strtotime($uvguestinfo["reservationinfo"]["checkout"]));
        $uvcheckoutsdt = date("Ymd", strtotime($uvguestinfo["reservationinfo"]["checkout"]));
        $uvcheckoutitd = ($uvkeytype == "onlydate") ? "ITD" . $uvcheckoutsdt : "ITD" . $uvcheckoutstimedt;
        $uvcheckoutitd = ($uvkeytype == "iniq") ? "ITDcheckout" : $uvcheckoutitd;

        $uvitineraryitemslist = uws_add_itineraryitemtolist($uvitineraryitemslist, $uvcheckinitd, 
            array(
                "itd" => "ITDcheckin",
                "name" => "Check in",
                "guests" => uws_get_itineraryguestslist($uvguestinfo),
                "type" => "checkin",
                "starttime" => $uvguestinfo["reservationinfo"]["checkintime"],
                "date" => $uvguestinfo["reservationinfo"]["checkin"],
                "instructions" => "Checkin on the lobby"
            ),
        );

        $uvitineraryitemslist = uws_add_itineraryitemtolist($uvitineraryitemslist, $uvcheckoutitd, 
            array(
                "itd" => "ITDcheckout",
                "name" => "Check-Out",
                "guests" => uws_get_itineraryguestslist($uvguestinfo),
                "type" => "checkout",
                "starttime" => $uvguestinfo["reservationinfo"]["checkouttime"],
                "date" => $uvguestinfo["reservationinfo"]["checkout"],
                "instructions" => "Checkout on the lobby"
            ),
        );

        //Add activities items
        foreach($uvguestinfo["itinerary"] as $uviacid => $uviact){
            $uviactinsttimedt = substr($uviact["starttime"], 0, 3) . date("Ymd", strtotime($uviact["date"]));
            $uviactinstdt = date("Ymd", strtotime($uviact["date"]));
            $uviactinstitduniq = "ITD" . $uviacid;
            $uviactinstitd = ($uvkeytype == "onlydate") ? "ITD" . $uviactinstdt : "ITD" . $uviactinsttimedt;
            $uviactinstitd = ($uvkeytype == "iniq") ? $uviactinstitduniq : $uviactinstitd;
            $uviactarr = $uviact;
            $uviactarr["type"] = "activity";
            $uviactarr["itd"] = $uviactinstitduniq;

            $uvitineraryitemslist = uws_add_itineraryitemtolist($uvitineraryitemslist, $uviactinstitd, $uviactarr);
        }

        //Add offers items
        foreach($uvguestinfo["offers"] as $uvoffid => $uvoffer){
            $uvioffinsttimedt = substr($uvoffer["starttime"], 0, 3) . date("Ymd", strtotime($uvoffer["date"]));
            $uvioffinstdt = date("Ymd", strtotime($uvoffer["date"]));
            $uvioffinstitduniq = "ITD" . $uvoffid;
            $uvioffinstitd = ($uvkeytype == "onlydate") ? "ITD" . $uvioffinstdt : "ITD" . $uvioffinsttimedt;
            $uvioffinstitd = ($uvkeytype == "iniq") ? $uvioffinstitduniq : $uvioffinstitd;
            $uvioffarr = $uvoffer;
            $uvioffarr["type"] = "offer";
            $uvioffarr["itd"] = $uvioffinstitduniq;

            $uvitineraryitemslist = uws_add_itineraryitemtolist($uvitineraryitemslist, $uvioffinstitd, $uvioffarr);
        }
    }

    return $uvitineraryitemslist;
}

/*Add itinerary item to the array list
    Requires: curitems(array with the current list), key of the time date, info for the itinerary item
    Returns: array including new item
*/
function uws_add_itineraryitemtolist($uvcuritems, $uvitemkey, $uviteminfo){
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
function uws_get_itineraryguestslist($uvguestinfo, $uvgueststype = "all"){
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
function uws_get_itinerary_dayview($uvguestinfo, $uvargs = ""){
    global $uws_core_lib;
    $uvitinerarydayview = "";

    if(is_array($uvguestinfo)){
        $uvitdates = $uvitedate = "";
        $uvdayviewtemp = uws_get_template("itinerary/itinerary-view-day");
        $uvdaydateviewtemp = uws_get_template("itinerary/itinerary-view-day-single");
        $uvititemtemplate = uws_get_template("itinerary/itinerary-card-item");
        $uvdate = uws_get_arg($uvargs, "date", $uvguestinfo["reservationinfo"]["checkin"]);
        $uvcheckoutdate = $uvguestinfo["reservationinfo"]["checkout"];
        $uvndates = uws_get_arg($uvargs, "ndates", 5);
        $uvinvertal = uws_get_arg($uvargs, "inverval", 60);
        $uvitineraryitemslist = uws_get_itineraryitemlist($uvguestinfo, "onlydate");

        for($i=0; $i<$uvndates; $i++){
            $uvdateititems = $uvittimeitems = "";
            $uvdatedday = date("l", strtotime($uvdate . " +$i days"));
            $uvdatedate = date("Y-m-d", strtotime($uvdate . " +$i days"));
            $uvdateddate = date($uws_core_lib["events"]["global-dateformat"], strtotime($uvdate . " +$i days"));
            $uvthissdate = date("Ymd", strtotime($uvdate . " +$i days"));
            $uvniitems = (is_array($uvitineraryitemslist["ITD" . $uvthissdate])) ? count($uvitineraryitemslist["ITD" . $uvthissdate]) : 0;
            $uvdayviewclass = ($uvcheckoutdate == $uvdatedate) ? "uwsislastbookdate" : "";

            if(is_array($uvitineraryitemslist["ITD" . $uvthissdate])){
                foreach($uvitineraryitemslist["ITD" . $uvthissdate] as $uvitineraryitem){
                    $uvititem = uws_replace_itinerary_item_vars($uvitineraryitem, $uvititemtemplate, $uvguestinfo);
                    $uvthisendtime = (isset($uvitineraryitem["endtime"])) ? $uvitineraryitem["endtime"] : "";
                    $uvititemnslots = uws_get_ntimerows($uvitineraryitem["starttime"], $uvthisendtime, $uvinvertal);
                    $uvititem = str_replace("{ititemnslots}", $uvititemnslots, $uvititem);
                    $uvititemdtime = uws_get_formattime($uvitineraryitem["starttime"]);
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
function uws_get_itinerary_daytimeview($uvguestinfo, $uvargs = ""){
    global $uws_core_lib;
    $uvitinerarydaytimeview = "";

    if(is_array($uvguestinfo)){
        $uvdaytimerows = "";
        $uvdaytimeviewtemp = uws_get_template("itinerary/itinerary-view-daytime");
        $uvititemtemplate = uws_get_template("itinerary/itinerary-card-item");
        $uvdate = uws_get_arg($uvargs, "date", $uvguestinfo["reservationinfo"]["checkin"]);
        //$uvdate = "2023-06-04";

        $uvinvertal = uws_get_arg($uvargs, "inverval", 60);
        $uvminhour = uws_get_arg($uvargs, "minhour", "10600");
        $uvmaxhour = uws_get_arg($uvargs, "minhour", "12300");

        $uvdatedday = date("l", strtotime($uvdate));
        $uvdateddate = date($uws_core_lib["events"]["global-dateformat"], strtotime($uvdate));
        $uvdatedate = date("Y-m-d", strtotime($uvdate));

        $uvcheckoutdate = $uvguestinfo["reservationinfo"]["checkout"];
        $uvdayviewclass = ($uvcheckoutdate == $uvdatedate) ? "uwsislastbookdate" : "";
        $uvitineraryitemslist = uws_get_itineraryitemlist($uvguestinfo);

        //Create week rows
        $uvntimerows = uws_get_ntimerows($uvminhour, $uvmaxhour, $uvinvertal);

        for($i=0; $i<$uvntimerows; $i++){
            $uvthistime = uws_add_minutestotime($uvminhour, $i * $uvinvertal);
            $uvdtime = uws_get_formattime($uvthistime);
            $uvinsttime = substr($uvthistime, 0, 3);

            $uvdaytimerows .= "<div class='uwsdaytimerow uws-daytimeview-insttime-$uvinsttime'><div class='uwshourlabel'><span class='uwstimelabel'>$uvdtime</span></div>";

            $uvittimeitems = "";
            $uvthisrowdate = date("Ymd", strtotime($uvdate));

            $uvniitems = (isset($uvitineraryitemslist["ITD" . $uvinsttime . $uvthisrowdate])) ? count($uvitineraryitemslist["ITD" . $uvinsttime . $uvthisrowdate]) : 0;

            if(isset($uvitineraryitemslist["ITD" . $uvinsttime . $uvthisrowdate])){
                foreach($uvitineraryitemslist["ITD" . $uvinsttime . $uvthisrowdate] as $uvitineraryitem){
                    $uvititem = uws_replace_itinerary_item_vars($uvitineraryitem, $uvititemtemplate, $uvguestinfo);
                    $uvthisendtime = (isset($uvitineraryitem["endtime"])) ? $uvitineraryitem["endtime"] : "";
                    $uvititemnslots = uws_get_ntimerows($uvitineraryitem["starttime"], $uvthisendtime, $uvinvertal);
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
function uws_get_itinerarytooltips(){
    $uvitinerarytooltips = "";

    $uvguestinfo = uws_get_dummyapi("guestinfo");
    $uvititems = uws_get_itineraryitemlist($uvguestinfo, "iniq");

    if(is_array($uvititems)){
        $uvitinerarytooltips = array();
        $uvcarditemtooltiptemp = uws_get_template("itinerary/itinerary-card-item-tooltip");

        foreach($uvititems as $uvititemkey => $uvititem){
            $uvititem = $uvititem[0];
            $uvititemhtml = uws_replace_itinerary_item_vars($uvititem, $uvcarditemtooltiptemp, $uvguestinfo);

            $uvitinerarytooltips[$uvititemkey] = $uvititemhtml;
        }
    }

    return $uvitinerarytooltips;
}

/*Get the number of different months between 2 dates
    Returns: number of months
    Reestrictions: startdate should be always < enddate
*/
function uws_get_invmonths($uvstartdate, $uvenddate){
    $uvnmonths = 1;

    if($uvstartdate and $uvenddate){
        $uvstartmonth = date("n", strtotime($uvstartdate)) / 1;
        $uvendmonth = date("n", strtotime($uvenddate)) / 1;

        if($uvstartmonth > $uvendmonth)
            $uvendmonth = $uvendmonth + 12;

        $uvnmonths = $uvendmonth - $uvstartmonth + 1;
    }

    return $uvnmonths;
}