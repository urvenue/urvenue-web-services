<?php

/*Get Packages
    Optional: args
    Returns: Prints html map integration
*/
function uws_packages($uvargs = ""){
    $uvpackageshtml = uws_get_packages($uvargs);
    // @Axl
    // echo $uvpackageshtml;
    echo wp_kses_post( $uvpackageshtml );
    // @Axl End
}

/*Get html of the packages integration
    Optional: args
    Returns: HTML of the map
*/
function uws_get_packages($uvargs = ""){
    global $uws_today;

    $uvpackageshtml = "";
    $uvvenuecode = uws_get_arg($uvargs, "venuecode", "");
    $uvdate = uws_get_arg($uvargs, "date", uws_get_events_initial_date("Y-m-d"));

    // If $uvdate is set and is in the past, use $uws_today instead
	$uvdate = ($uvdate && strtotime($uvdate) < strtotime($uws_today)) ? $uws_today : $uvdate;

    $uvtodate = uws_get_arg($uvargs, "todate", uws_get_events_endinit_date("Y-m-d", $uvdate));
    $uvglobaltype = uws_get_arg($uvargs, "globaltype", "package");

    //Set venuecode as default venuecode
    if(!$uvvenuecode){
        $uvprimvenue = uws_get_primary_venue();
        $uvvenuecode = (is_array($uvprimvenue)) ? $uvprimvenue["venuecode"] : "";
        $uvargs["venuecode"] = $uvvenuecode;
    }

    $uvterms = array(
        "venuecode" => $uvvenuecode,
        "caldate" => $uvdate,
        "todate" => $uvtodate
    );

    $uvinventory = uws_get_feed("packagesinventory", $uvterms);

    if(is_array($uvinventory) and $uvinventory["uv"]["success"]["status"] == "success" and isset($uvinventory["uv"]["data"]["items"])) {
        $uvitems = $uvinventory["uv"]["data"]["items"];
        $uvitems = uws_filter_items_globaltype($uvitems, $uvglobaltype);

        if(is_array($uvitems) and count($uvitems) > 0){
            $uvmasteritemcodes = array();

            foreach($uvitems as $uvitem){
                $uvmasteritemcode = $uvitem["masteritemcode"];

                if(!isset($uvmasteritemcodes[$uvmasteritemcode])){
                    $uvmasteritemcodes[$uvmasteritemcode] = $uvitem["mastercode"];
                }
            }

            $uvmasteritemsinfo = uws_get_packages_masterinfo($uvmasteritemcodes);
            $uvmasteritemshtml = uws_get_packages_list($uvmasteritemsinfo, $uvargs);
            $uvpackageshtml = $uvmasteritemshtml["markup"];
        }
    }

    return $uvpackageshtml;
}

function uws_get_packages_list($uvmasteritemsinfo, $uvargs = ""){
    $uvpackagesmarkup = "";

    if(is_array($uvmasteritemsinfo)){
        $uvpackageslist = "";
        $uvcontainertpl = uws_get_template("packages/list-container");//List container template
        $uvitemtpl = uws_get_template("packages/list-item");//List item template

        foreach($uvmasteritemsinfo as $uvmasteriteminfo){
            $uvimageurl = $uvitdescr = "";
            $uvhasimage = "no";
            if(isset($uvmasteriteminfo["images"]) and is_array($uvmasteriteminfo["images"])){
                $uvimagekey = array_key_first($uvmasteriteminfo['images']);
                $uvfirstimage = $uvmasteriteminfo['images'][$uvimagekey][0];

                $uvimageurl = $uvfirstimage["folder"] . "/500SC0/" . $uvfirstimage["file"];
                $uvhasimage = "yes";
            }

            // if($uvmasteriteminfo["itemdescr"]){
            //     $uvitdescr = uws_get_package_description_lis($uvmasteriteminfo["itemdescr"]);
            // }

            $uvitemmarkup = str_replace(
                array(
                    "{masteritemcode}",
                    "{hasimage}",
                    "{imageurl}",
                    "{itemname}",
                    "{itemdescr}",
                    "{highlight}",
                    "{globaltype}",
                ),
                array(
                    $uvmasteriteminfo["masteritemcode"],
                    $uvhasimage,
                    $uvimageurl,
                    $uvmasteriteminfo["itemname"],
                    $uvitdescr,
                    $uvmasteriteminfo["highlight"],
                    $uvmasteriteminfo["globaltype"],
                ),
                $uvitemtpl
            );

            $uvpackageslist .= $uvitemmarkup;
        }

        $uvdate = uws_get_arg($uvargs, "date", uws_get_events_initial_date("Y-m-d"));
        $uvtodate = uws_get_arg($uvargs, "todate", uws_get_events_endinit_date("Y-m-d", $uvdate));
        $uvvenuecode = uws_get_arg($uvargs, "venuecode", "");
        $uvmaxdate = uws_get_events_max_date("Y-m-d");

        $uvpackagesmarkup = str_replace(
            array(
                "{packageslist}",
                "{mindate}",
                "{maxdate}",
                "{venuecode}",
            ),
            array(
                $uvpackageslist,
                $uvdate,
                $uvmaxdate,
                $uvvenuecode,
            ),
            $uvcontainertpl
        );
    }

    $uvpackagesreturn = array(
        "markup" => $uvpackagesmarkup,
    );

    return $uvpackagesreturn;
}

/*Get cached items info calling inventoryapi based on a list of mastercodes
    Optional: masteritemcodes: list with items with a field "mastercode"
    Returns: array with masteritemcodes info
*/
function uws_get_packages_masterinfo($uvmasteritemcodes){
    $uvmasteritemsinfo = "";

    if(is_array($uvmasteritemcodes)){
        $uvmasteritemsinfo = array();

        foreach($uvmasteritemcodes as $uvmasteritem => $uvmastercode){
            $uvterms = array(
                "mastercode" => $uvmastercode
            );

            $uvmastinfo = uws_get_feed("packagesiteminfo", $uvterms);
            if(is_array($uvmastinfo) and $uvmastinfo["uv"]["success"]["status"] == "success" and isset($uvmastinfo["uv"]["data"]["info"])) {
                $uvmasteritemsinfo[$uvmasteritem] = $uvmastinfo["uv"]["data"]["info"];
            }
        }
    }

    return $uvmasteritemsinfo;
}

/*Get description as a ul li list
    Requires: uvdescr string
    Returns: html list
*/
function uws_get_package_description_lis($uvdescr){
    $uvitems = preg_split("/\n{2,}/", $uvdescr);

    $uvlistitems = array_map(function($uvitem) {
        return '<li>' . trim($uvitem) . '</li>';
    }, $uvitems);

    return '<ul>' . implode("\n", $uvlistitems) . '</ul>';
}

/*Get mastercode by masteritemcode
    Requires: uvargs(masteritemcode, venuecode, date)
    Returns: mastercode if exist on the context
*/
function uws_get_mastercode_by_masteritemcode($uvargs = ""){
    $uvmastercode = "";

    $uvmasteritemcode = uws_get_arg($uvargs, "masteritemcode", "");
    $uvvenuecode = uws_get_arg($uvargs, "venuecode", "");
    $uvdate = uws_get_arg($uvargs, "date", "");

    $uvfeedtoken = array(
        "venuecode" => $uvvenuecode,
        "caldate" => $uvdate,
        "todate" => $uvdate,
    );
    $uvinventoryfeed = uws_get_feed("inventory", $uvfeedtoken);

    if(is_array($uvinventoryfeed) and $uvinventoryfeed["uv"]["success"]["status"] == "success" and isset($uvinventoryfeed["uv"]["data"]["items"])) {
        $uvitems = $uvinventoryfeed["uv"]["data"]["items"];

        if(is_array($uvitems)){
            foreach($uvitems as $uvitem){
                if($uvitem["masteritemcode"] == $uvmasteritemcode and $uvitem["caldate"] == $uvdate){
                    $uvmastercode = $uvitem["mastercode"];
                    break;
                }
            }
        }
    }

    return $uvmastercode;
}