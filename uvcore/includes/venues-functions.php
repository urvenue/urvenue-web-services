<?php

/*Get Venue Info
    Requires: venuecode
    Returns: array with venue info
*/
function uws_get_venueinfo($uvvenuecode = ""){
    global $uws_today;

    $uvvenuesinfo = "";

    if($uvvenuecode){
        $uvterms = array(
            "venuecode" => $uvvenuecode,
            "caldate" => $uws_today,
            "todate" => $uws_today,
        );
        $uvapidata = uws_get_feed("inventorylist-venues", $uvterms);

        if(is_array($uvapidata) and $uvapidata["uv"]["success"]["status"] == "success"){
            if(is_array($uvapidata["uv"]["data"]["venues"]) and is_array($uvapidata["uv"]["data"]["venues"][$uvvenuecode]))
                $uvvenuesinfo = uws_get_venue_array($uvapidata["uv"]["data"]["venues"][$uvvenuecode]);
        }
    }

    return $uvvenuesinfo;
}

/*Get Venues Info
    Requires: venuecodes
    Returns: array with venue info
*/
function uws_get_venuesinfo($uvvenuecode = ""){
    global $uws_today;

    $uvvenuesinfo = "";

    if($uvvenuecode){
        $uvterms = array(
            "venuecode" => $uvvenuecode,
            "caldate" => $uws_today,
            "todate" => $uws_today,
        );
        $uvapidata = uws_get_feed("inventorylist-venues", $uvterms);

        if(is_array($uvapidata) and $uvapidata["uv"]["success"]["status"] == "success"){
            $uvvenues = $uvapidata["uv"]["data"]["venues"];
            $uvvenuesinfo = array();

            if(is_array($uvvenues)){
                foreach($uvvenues as $uvvenuecode => $uvvenue){
                    $uvvenuearray = uws_get_venue_array($uvvenue);

                    $uvvenuesinfo[$uvvenuecode] = $uvvenuearray;
                }
            }
        }
    }

    //print_r($uvvenuesinfo);

    return $uvvenuesinfo;
}

/*Process API data and returns array with events
    Requires: apidata(Raw data from API)
*/
function uws_get_venue_array($uvvenue){
    $uvvenuearray = "";

    if(is_array($uvvenue)){
        $uvvenuearray = $uvvenue["info"];
        $uvvenueurl = uws_get_venue_url($uvvenuearray);
        $uvvenueimagesarray = uws_get_venue_imagesarray($uvvenue["images"]);
        $uvvenueimages = uws_get_venue_images($uvvenueimagesarray);
        $uvvenueaddress = isset($uvvenuearray["address"]) ? $uvvenuearray["address"] : "";
        $uvvenueprovince = isset($uvvenuearray["province"]) ? $uvvenuearray["province"] : "";
        $uvvenuezip = isset($uvvenuearray["zip"]) ? $uvvenuearray["zip"] : "";
        $uvvenuearray["venueaddress"] = ($uvvenuearray["city"]) ? $uvvenueaddress . "<br>" . $uvvenuearray["city"] . ", " . $uvvenueprovince . " " . $uvvenuezip : $uvvenueaddress;
        $uvvenuegmapurl = strip_tags($uvvenuearray["venueaddress"]);
        $uvvenuearray["venuegmapurl"] = "https://www.google.com/maps/search/?api=1&query=" . urlencode($uvvenuegmapurl);
        $uvvenuearray["venueimages"] = $uvvenueimages;
        $uvvenuearray["venue-url"] = $uvvenueurl;
        $uvvenuearray["hours-of-operation"] = (isset($uvvenue["currentophours"]) and  isset($uvvenue["currentophours"]["weekdays"])) ? $uvvenue["currentophours"]["weekdays"] : "";
    }

    return $uvvenuearray;
}

/*Get venue images for different places
    Requires: venue images(plain venue images array)
*/
function uws_get_venue_images($uvimages){
    global $uws_core_lib;

    $uvimagesreturn = "";

    if(is_array($uws_core_lib["venueimages"])){
        $uvimagesreturn = array();

        foreach($uws_core_lib["venueimages"] as $uvimageloccode => $uvimageprior){
            $uvlocimage = "";

            if(is_array($uvimageprior)){
                $uvthishideifnomatch = $uws_core_lib["venueimages"][$uvimageloccode . "-hideifnomatch"];
                $uvthisuseplaceholder = $uws_core_lib["venueimages"][$uvimageloccode . "-useplaceholder"];
                $uvthisplaceholcerurl = $uws_core_lib["venueimages"][$uvimageloccode . "-placeholderurl"];
                $uvthisreturnmultiple = (isset($uws_core_lib["venueimages"][$uvimageloccode . "-returnmultiple"])) ? $uws_core_lib["venueimages"][$uvimageloccode . "-returnmultiple"] : 0;
                $uvthissizecode = $uws_core_lib["venueimages"][$uvimageloccode . "-sizecode"];

                $uvlocimage = uws_get_flyersbypriority($uvimages, $uvimageprior, $uvthishideifnomatch, $uvthisreturnmultiple);

                if(is_array($uvlocimage) and !$uvthisreturnmultiple){
                    $uvimageurl = $uvlocimage["path"] . "/$uvthissizecode/" . $uvlocimage["file"];
                    $uvimagefull = $uvlocimage["path"] . "/raw/" . $uvlocimage["file"];
                    $uvimageurlcode = $uvlocimage["path"] . "/{sizecode}/" . $uvlocimage["file"];

                    $uvlocimage["url"] = $uvimageurl;
                    $uvlocimage["full"] = $uvimagefull;
                    $uvlocimage["urlcode"] = $uvimageurlcode;
                    $uvlocimage["bgtype"] = $uvlocimage["bgtype"];
                }
                else if(is_array($uvlocimage) and $uvthisreturnmultiple){
                    $uvlocimagearray = array();

                    foreach($uvlocimage as $uvlocimageitem){
                        $uvimageurl = $uvlocimageitem["path"] . "/$uvthissizecode/" . $uvlocimageitem["file"];
                        $uvimagefull = $uvlocimageitem["path"] . "/raw/" . $uvlocimageitem["file"];
                        $uvimageurlcode = $uvlocimageitem["path"] . "/{sizecode}/" . $uvlocimageitem["file"];

                        $uvlocimageitem["url"] = $uvimageurl;
                        $uvlocimageitem["full"] = $uvimagefull;
                        $uvlocimageitem["urlcode"] = $uvimageurlcode;
                        $uvlocimageitem["bgtype"] = $uvlocimageitem["bgtype"];

                        $uvlocimagearray[] = $uvlocimageitem;
                    }

                    $uvlocimage = $uvlocimagearray;
                }

                if(!is_array($uvlocimage) and $uvthisuseplaceholder and ($uvthisplaceholcerurl or $uws_core_lib["venueimages"]["placeholderurl"])){
                    $uvthisplaceholder = ($uvthisplaceholcerurl) ? $uvthisplaceholcerurl : $uws_core_lib["venueimages"]["placeholderurl"];

                    $uvlocimage = array(
                        "url" => $uvthisplaceholder,
                        "full" => $uvthisplaceholder,
                        "urlcode" => $uvthisplaceholder,
                        "bgtype" => "",
                        "ratio" => "placeholder",
                    );
                }
                else if(!is_array($uvlocimage)){
                    $uvlocimage = array(
                        "url" => "",
                        "full" => "",
                        "urlcode" => "",
                        "bgtype" => "",
                        "ratio" => "noimage",
                    );
                }
            }

            if(is_array($uvlocimage)){
                $uvimagesreturn[$uvimageloccode] = $uvlocimage;
            }
        }
    }

    return $uvimagesreturn;
}

/*Get plain venue images array
    Requires: venue images(Raw venue images array)
*/
function uws_get_venue_imagesarray($uvimages){
	if(is_array($uvimages)){
		$uvimagesarray = array();
		foreach($uvimages as $uvimagetypekey => $uvimagetypearray){
			if(is_array($uvimagetypearray)){
				foreach($uvimagetypearray as $uvimageitem){
					$uvimagesarray[] = $uvimageitem;
				}
			}
		}
	}
	else
		$uvimagesarray = "";

	return $uvimagesarray;
}

/*Get venue url
    Requires: venue(venue data array)
    Optional: linkcode(event or map, depending on what link is required)
*/
function uws_get_venue_url($uvvenue, $uvlinkcode = "venue"){
    global $uws_config_venueurl;

    $uvvenueurl = "#";

    if(isset($uws_config_venueurl) and $uws_config_venueurl){
        $uvbaseurl = $uws_config_venueurl;
        $uvvenuenameurl = uws_get_linkstring($uvvenue["name"]);

        $uvvenueurl = str_replace(
            array(
                "{manageentid}",
                "{venuecode}",
                "{venuenameurl}",
            ),
            array(
                $uvvenue["manageentid"],
                $uvvenue["code"],
                $uvvenuenameurl,
            ),
            $uvbaseurl
        );
    }

    return $uvvenueurl;
}