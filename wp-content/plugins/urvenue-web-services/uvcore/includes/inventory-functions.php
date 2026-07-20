<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*Get date, venueid and ecozone from eventcode
    Returns: array with date, venueid and ecozone
    Requires: eventcode
*/
function urvenue_ws_get_eventcode_data($uveventcode)
{
    $uveventcodedata = "";

    if ($uveventcode) {
        $uveventdateday = substr($uveventcode, -2);
        $uveventdatemonth = substr($uveventcode, -4, -2);
        $uveventdateyear = substr($uveventcode, -8, -4);
        $uveventdate = $uveventdateyear . "-" . $uveventdatemonth . "-" . $uveventdateday;
        $uveventecozone = substr($uveventcode, -11, -8) / 1;
        $uveventecozone = "ECZ" . $uveventecozone;
        $uvvenueid = substr($uveventcode, 0, -11);
        $uvvenueid = str_replace("EVE", "", $uvvenueid);

        $uveventcodedata = array(
            "date" => $uveventdate,
            "venuecode" => "VEN" . $uvvenueid,
            "ecozone" => $uveventecozone,
            "eventcode" => $uveventcode,
        );
    }

    return $uveventcodedata;
}

/*Get inventory list
    Requires: eventdata, array with: date, venuecode, ecozone
    Returns: Array: html of the list of booktypes with items based on templates, plain array with all the items
*/
function urvenue_ws_get_eventinventory_list($uveventdata, $uvargs = "")
{
    global $urvenue_ws_config_inventorynoitems_msg, $urvenue_ws_core_lib, $uvintegration, $urvenue_ws_config_addonvenues;

    $uvglobaltype = urvenue_ws_get_arg($uvargs, "globaltype");
    $uvbooktypename = urvenue_ws_get_arg($uvargs, "booktypename");
    $uvecozonesmap = urvenue_ws_get_arg($uvargs, "ecozonesmap");
    $uvmainvenuecode = urvenue_ws_get_arg($uvargs, "mainvenuecode");
    $uvaddonvenuecode = urvenue_ws_get_arg($uvargs, "addonvenuecode");
    $uvmicrocode = urvenue_ws_get_arg($uvargs, "microcode");
    $uvhomeeventcode = urvenue_ws_get_arg($uvargs, "homeeventcode");

    $uvinventorylist = $uv3dmapbtn = $uveventinventoryitems = $uveventinventoryheader = $uvhasseating = "";
    $uvinventorylisttpl = urvenue_ws_get_template("inventory/inventory-list-container"); //List container template
    $uvinventorybookbtns = urvenue_ws_get_template("inventory/inventory-bookbuttons"); //Book buttons cont

    // Add-On Venues
    $uvaddonvenues = (isset($urvenue_ws_core_lib["events"]["addon-venues"]) && is_array($urvenue_ws_core_lib["events"]["addon-venues"]) && $urvenue_ws_core_lib["events"]["addon-venues"]) ? $urvenue_ws_core_lib["events"]["addon-venues"] : "";
    $uvaddonvenues = ($urvenue_ws_config_addonvenues && is_array($urvenue_ws_config_addonvenues)) ? $urvenue_ws_config_addonvenues : $uvaddonvenues;

    if (is_array($uveventdata)) {
        $uvbooktypeslist = $uveventinventoryfeed = $uvhomeecozone = $uvhomeaddonvenues = $uvaddonvenuesinvfeed = $uvaddonevtvenuesinvfeed = "";

        if ($uveventdata["homeeventcode"] && !$uvaddonvenuecode) {
            $uvhomeecozone = urvenue_ws_get_ecozone_back($uveventdata);
            $uveventdata["ecozones"] = ""; //remove ecozones to avoid
            $uvecozonesmap = "";
            $uvbooktypeslist .= $uvhomeecozone;

            if ($uveventdata["maineventcode"] and ($uveventdata["maineventcode"] != $uveventdata["eventcode"]))
                $uveventdata["map-url"] = str_replace($uveventdata["eventcode"], $uveventdata["maineventcode"], $uveventdata["map-url"]);

        } else if ($uvaddonvenuecode && $uvhomeeventcode) {
            $uveventdata["homeeventcode"] = $uvhomeeventcode;
            $uvhomeaddonvenues = urvenue_ws_get_addonvenues_back($uveventdata, $uvaddonvenues, $uvmainvenuecode, $uvaddonvenuecode, $uvmicrocode);
            $uvbooktypeslist .= $uvhomeaddonvenues;
        }

        //Clean ecozones when there is only 1, force direct unique ecozone with item
        if (is_array($uvecozonesmap) and count($uvecozonesmap) == 1) {
            $uveventmapurl = $uveventdata["map-url"];
            $uveventdata = reset($uvecozonesmap);
            $uveventdata["map-url"] = $uveventmapurl;
            $uvecozonesmap = "";
            $uvhasseating = 1;
        }

        if (is_array($uvecozonesmap)) { //Show Ecozones Selection, no need to load inventory
            $uvbooktypeslist = urvenue_ws_get_ecozones_list($uvecozonesmap);
            $uvhasseating = 1;
        } else {
            $uveventinventoryfeed = urvenue_ws_get_eventinventory_list_feed($uveventdata, $uvglobaltype, $uvbooktypename);
        }

        if (is_array($uveventinventoryfeed)) {
            //$uveventinventorynodes = $uveventinventoryfeed["nodes"];
            $uveventinventoryitems = $uveventinventoryfeed["items"];
            $uveventinventoryecolist = $uveventinventoryfeed["ecolist"];
            $uveventinventorymasterlist = $uveventinventoryfeed["masterlist"];
            $uveventinventoryheader = $uveventinventoryfeed["header"];
            $uveventinventoryecozones = $uveventinventoryfeed["invecozones"];

            $uvhasseating = (!$uvhasseating and (isset($uveventinventoryecozones[urvenue_ws_standardize_ecozone($uveventdata["ecozone"])]) && $uveventinventoryecozones[urvenue_ws_standardize_ecozone($uveventdata["ecozone"])]["info"]["has_seating"])) ? 1 : $uvhasseating;

            //$uvhasmap = ($uveventinventoryitems) ? 1 : 0;
            //$uvhasmap = uws_date_has_map($uvinventoryfeed, $uveventdata["venuecode"]);//Know if date-eco has map
            //$uvinventoryflatlist = uws_get_inventory_flatlist($uvinventoryfeed, array("venuekey" => $uvvenuelibinfo["venuekey"]));//Get items flat list

            $uveventinventoryecolistpro = urvenue_ws_add_masterinfo_to_ecolist($uveventinventoryecolist, $uveventinventorymasterlist);

            // filter by booktypename the ecolist
            if ($uvbooktypename) {
                $uveventinventoryecolistpro = array_filter($uveventinventoryecolistpro, function ($item) use ($uvbooktypename) {
                    return strpos($item["booktype"]["label"], $uvbooktypename) !== false;
                });
            }

            $uveventinventoryplainecolist = urvenue_ws_get_plain_ecolist($uveventinventoryecolistpro);
            $uvbooktypeslist .= urvenue_ws_get_booktypes_list($uveventinventoryitems, $uveventinventoryecolistpro); //Get booktypes list

            // Add-On Venues
            if($uvaddonvenues && !$uvaddonvenuecode)
                $uvbooktypeslist .= urvenue_ws_get_addonvenues_list($uvaddonvenues, $uveventdata);
        }

        if ($uvbooktypeslist and $uveventdata["map-url"] and $uvhasseating and !$uvaddonvenuecode) { //Generate 3d map button if map exists
            $uv3dmapbtntemplate = urvenue_ws_get_template("inventory/inventory-3dmap-button");
            $uv3dmapbtndateformat = ($uvintegration) ? "D, M j, Y" : $urvenue_ws_core_lib["inventory"]["global-dateformat"];
            $uvedmapddate =  gmdate($uv3dmapbtndateformat, strtotime($uveventdata["date"]));
            $uvmapicon = urvenue_ws_get_icon("pin");
            $uv3dmapbtn = str_replace(
                array(
                    "{maplink}",
                    "{ddate}",
                    "{mapicon}",
                ),
                array(
                    $uveventdata["map-url"],
                    $uvedmapddate,
                    $uvmapicon,
                ),
                $uv3dmapbtntemplate
            );
        }
    }

    //Add urls to cart
    if (isset($_COOKIE['urvenue_ws_gcart']) and sanitize_text_field( wp_unslash( $_COOKIE['urvenue_ws_gcart'] ) )) {
        $uvbkgcheckoutlinks = urvenue_ws_get_bkgcheckout_links( sanitize_text_field( wp_unslash( $_COOKIE['urvenue_ws_gcart'] ) ) );
        $uvcarturl = $uvbkgcheckoutlinks["checkout-carturl"];
        $uvcheckurl = $uvbkgcheckoutlinks["checkout-checkurl"];
    } else
        $uvcarturl = $uvcheckurl = "#";

    $uvbookbtnsclass = (isset($urvenue_ws_core_lib["system"]["checkouttype"]) and $urvenue_ws_core_lib["system"]["checkouttype"] == "uvcheckout") ? "uwsjs-sidecheckthis" : "";

    $uvinventorybookbtns = str_replace(
        array(
            "{carturl}",
            "{checkurl}",
            "{bookbtnsclass}",
        ),
        array(
            $uvcarturl,
            $uvcheckurl,
            $uvbookbtnsclass,
        ),
        $uvinventorybookbtns
    );

    $uvnoitemsmessage = "";
    if (!$uvbooktypeslist) { // remove book buttons if there are not items
        $uvinventorybookbtns = "";

        $uvnoitemsbtn = ($uvintegration) ? "<a href='#uws-change-bookingcal-date' class='uwsjs-bkcal-changedate uws-btn uws-btn-s uws-noitems-btn'><i class='uwsicon-calendar-empty'></i> <span>Select a different date</span></a>" : "";

        if (!isset($uveventdata["ticketsurl"]) or !$uveventdata["ticketsurl"]) {
            if ($urvenue_ws_config_inventorynoitems_msg)
                $uvnoitemsmessage = $urvenue_ws_config_inventorynoitems_msg . $uvnoitemsbtn;
            else
                $uvnoitemsmessage = "
                    <div class='uws-inventory-list-noitmes'>
                        <div class='uwstitle'><i class='uwsicon-warning-empty'></i> No Experiences Found</div>
                        <div class='uwstext'>Check back later for updates.</div>
                        $uvnoitemsbtn
                    </div>
                ";
        }
    }

    //Add tickets url
    if (isset($uveventdata["ticketsurl"]) and $uveventdata["ticketsurl"] and !$uvaddonvenuecode)
        $uvbooktypeslist = "<a class='uws-btn uws-btn-p uws-btn-100 uws-extticketsbtn' href='" . $uveventdata["ticketsurl"] . "' target='_blank'><span>Tickets</span> <i class='uwsicon-ticket'></i></a>" . $uvbooktypeslist;

    $uvinventorylist = str_replace(
        array(
            "{3dmaplink}",
            "{booktypes}",
            "{bookbuttons}",
            "{notiemsmessage}",
        ),
        array(
            $uv3dmapbtn,
            $uvbooktypeslist,
            $uvinventorybookbtns,
            $uvnoitemsmessage,
        ),
        $uvinventorylisttpl
    );

    $uvinventoryreturn = array(
        "html" => $uvinventorylist,
        "items" => $uveventinventoryitems,
        "plainecolist" => $uveventinventoryplainecolist,
    );

    if ($urvenue_ws_core_lib["system"]["use-partnerid-fromapi"] and is_array($uveventinventoryheader) and $uveventinventoryheader["partnerid"])
        $uvinventoryreturn["manageentid"] = $uveventinventoryheader["partnerid"];

    return $uvinventoryreturn;
}

/*Get Back Button For Ecozones Flow*/
function urvenue_ws_get_ecozone_back($uveventdata = "")
{
    $uvecozoneback = "";

    if (is_array($uveventdata) and $uveventdata["homeeventcode"]) {
        $uvhomeeventdata = urvenue_ws_get_eventcode_data($uveventdata["homeeventcode"]);
        $uvecozoneid = urvenue_ws_ecozone_to_ecoid($uveventdata["ecozone"]);

        //$uvecozoneinfo = (isset($uveventdata["ecozones"][$uvecozoneid])) ? $uveventdata["ecozones"][$uvecozoneid] : "";//this is not returning the correct ecozone

        $uvecozonename = "Back";
        if (is_array($uvecozoneinfo)) {
            $uvecozone = urvenue_ws_standardize_ecozone($uvecozoneinfo["ecozoneid"]);
            $uvdstartdate = urvenue_ws_get_formattime($uvecozoneinfo["starttime"]);
            $uvdenddate = urvenue_ws_get_formattime($uvecozoneinfo["endtime"]);
            $uvecozonename = ($uvdstartdate and $uvdenddate) ? $uvecozoneinfo["name"] . " $uvdstartdate-$uvdenddate" : $uvecozoneinfo["name"];
            $uvecozonename = ($uvdstartdate and !$uvdenddate) ? $uvecozoneinfo["name"] . " Arrive By $uvdstartdate" : $uvecozonename;
        } else if ($uveventdata["homename"]) {
            $uvecozonename = $uveventdata["homename"];
        }

        $uvecozoneback = "<a href='#uws-ecozone-back' class='uwsjs-list-ecozone-back uws-list-ecozone-back' data-eventcode='" . $uveventdata["homeeventcode"] . "'><i class='uwsicon-right-open'></i> <span>$uvecozonename</span></a>";
    }

    return $uvecozoneback;
}


/**
 * Generates the HTML for the "addon venues back" section based on provided event and venue data.
 *
 * This function retrieves and processes information about addon venues related to a main venue and event,
 * then populates a template with the relevant details.
 *
 * @param array  $uveventdata     Event data array, must contain 'homeeventcode' and 'ecozone'.
 * @param array  $uvaddonvenues   Array of addon venues to process.
 * @param string $uvmainvenuecode Main venue code for context.
 * @param string $uvvenuecode     Venue code to match and display.
 * @param string $uvmicrocode     Microcode used to filter addon venues.
 *
 * @return string The populated HTML template for the addon venues back section, or an empty string if requirements are not met.
 */
function urvenue_ws_get_addonvenues_back($uveventdata = "", $uvaddonvenues = "", $uvmainvenuecode = "", $uvvenuecode = "", $uvmicrocode = "") {
    $uvaddonvenuesback = "";

    if (is_array($uveventdata) and $uveventdata["homeeventcode"] and is_array($uvaddonvenues) and $uvvenuecode and $uvmicrocode) {
        $uvaddonvenuebacktpl = urvenue_ws_get_template("inventory/inventory-list-addonvenue-back");
        $uvhomeeventdata = urvenue_ws_get_eventcode_data($uveventdata["homeeventcode"]);
        $uvecozoneid = urvenue_ws_ecozone_to_ecoid($uveventdata["ecozone"]);
        $uveventcode = $uveventdata["homeeventcode"];

        $uvaddonvenueslist = urvenue_ws_get_addonvenues_list_normalized($uvaddonvenues, $uvmainvenuecode);
        $uvvenuepretitle = ($uvaddonvenueslist["pretitle"]) ? $uvaddonvenueslist["pretitle"] : "";
        $uvvenuetitle = ($uvaddonvenueslist["title"]) ? $uvaddonvenueslist["title"] : "";
        $uvaddonvenueslistinfo = urvenue_ws_get_addonvenues_list_info($uvaddonvenueslist["addonvenues"], $uvmicrocode);

        if(is_array($uvaddonvenueslistinfo)) {
            $uvvenuename = $uvaddonvenueslistinfo["name"];
            $uvvenueaddress = $uvaddonvenueslistinfo["address"];
            $uvvenuelogo = $uvaddonvenueslistinfo["logo"];
            $uvvenuecode = $uvaddonvenueslistinfo["venuecode"];
            $uvvenuedescr = $uvaddonvenueslistinfo["descr"];

            $uvaddonvenuesback = str_replace(
                array(
                    "{venuepretitle}",
                    "{venuetitle}",
                    "{venuecode}",
                    "{eventcode}",
                    "{venuelogo}",
                    "{venuename}",
                    "{venueaddress}",
                    "{venuelogo}",
                    "{venuedescr}",
                ),
                array(
                    $uvvenuepretitle,
                    $uvvenuetitle,
                    $uvvenuecode,
                    $uveventcode,
                    $uvvenuelogo,
                    $uvvenuename,
                    $uvvenueaddress,
                    $uvvenuelogo,
                    $uvvenuedescr,
                ),
                $uvaddonvenuebacktpl
            );
        }
    }

    return $uvaddonvenuesback;
}

/**
 * Normalizes and retrieves a list of addon venues for a given main venue code.
 *
 * This function takes an array of addon venues and a main venue code, then returns
 * a normalized array containing the pretitle, title, and addonvenues for the specified
 * main venue code. If the input is not valid or the main venue code is not found,
 * an empty array is returned.
 *
 * @param array $uvaddonvenues      The array of addon venues, keyed by venue code.
 * @param string $uvmainvenuecode   The main venue code to retrieve addon venues for.
 *
 * @return array Returns a normalized array with keys 'pretitle', 'title', and 'addonvenues',
 *               or an empty array if the main venue code is not found.
 */
function urvenue_ws_get_addonvenues_list_normalized($uvaddonvenues, $uvmainvenuecode = "") {
    $uvaddonvenueslistarray = array();
        
    if (is_array($uvaddonvenues) && $uvmainvenuecode && isset($uvaddonvenues[$uvmainvenuecode])) {
        $uvaddonvenues = array($uvmainvenuecode => $uvaddonvenues[$uvmainvenuecode]);
        
        foreach ($uvaddonvenues as $uvaddonvenuecode => $uvaddonvenue) {
            $uvaddonvenueslistarray = array(
                "pretitle" => (isset($uvaddonvenue["pretitle"]) && $uvaddonvenue["pretitle"]) ? $uvaddonvenue["pretitle"] : "",
                "title" => $uvaddonvenue["title"],
                "addonvenues" => $uvaddonvenue["addonvenues"],
            );
        }
    }
    
    return $uvaddonvenueslistarray;
}

/**
 * Retrieves detailed information for a specific addon venue from a list of addon venues.
 *
 * @param array  $uvaddonvenuesitems  An associative array of addon venues, keyed by microcode.
 * @param string $uvmicrocode         The microcode identifying the specific addon venue to retrieve.
 *
 * @return array|string Returns an associative array with venue information (name, venuecode, address, descr, logo, microcode)
 *                      if found and valid, or an empty string if not found or invalid.
 */
function urvenue_ws_get_addonvenues_list_info($uvaddonvenuesitems, $uvmicrocode = "") {
    $uvvenuesitemslistinfo = "";
    $uvvenuetolook = array();

    if (is_array($uvaddonvenuesitems) and $uvmicrocode) {
        $uvtheme = urvenue_ws_get_theme();

        if (isset($uvaddonvenuesitems[$uvmicrocode]) && is_array($uvaddonvenuesitems[$uvmicrocode])) {
            $uvvenuetolook = $uvaddonvenuesitems[$uvmicrocode];

            $uvaddonglobaltype = (isset($uvvenuetolook["type"]) and $uvvenuetolook["type"]) ? $uvvenuetolook["type"] : "";
            $uvaddonvenuecode = $uvvenuetolook["venuecode"];
            $uvaddonvenuelogo = (isset($uvvenuetolook["logo"]) and $uvvenuetolook["logo"] and $uvvenuetolook["logo"] !== "{logourl}") ? $uvvenuetolook["logo"] : "";
            $uvvenuefeedinfo = urvenue_ws_get_venueinfo($uvaddonvenuecode);

            if (is_array($uvvenuefeedinfo) and $uvvenuefeedinfo["manageentid"] and $uvvenuefeedinfo["code"]) {
                $uvaddonvenuedisplayinfo = urvenue_ws_get_addonvenuedisplayinfo($uvvenuefeedinfo, $uvtheme, $uvvenuetolook);

                $uvvenueitemname = $uvaddonvenuedisplayinfo["venuename"];
                $uvvenueitemaddress = $uvaddonvenuedisplayinfo["venueaddress"];
                $uvvenueitemlogo = ($uvaddonvenuedisplayinfo["venuelogo"]) ? $uvaddonvenuedisplayinfo["venuelogo"] : "";
                $uvvenueitemlogo = ($uvaddonvenuelogo) ? $uvaddonvenuelogo : $uvvenueitemlogo;
                $uvvenueitemdescr = ($uvaddonvenuedisplayinfo["venuedescr"]) ? $uvaddonvenuedisplayinfo["venuedescr"] : "";
                $uvvenueiteminfo = ($uvvenueitemdescr) ? $uvvenueitemdescr : "";
                $uvvenueitemvenuecode = $uvaddonvenuedisplayinfo["venuecode"];

                $uvvenuesitemslistinfo = array(
                    "name" => $uvvenueitemname,
                    "venuecode" => $uvvenueitemvenuecode,
                    "address" => $uvvenueitemaddress,
                    "descr" => $uvvenueiteminfo,
                    "logo" => $uvvenueitemlogo,
                    "microcode" => $uvmicrocode,
                );
            }
        }
    }
    return $uvvenuesitemslistinfo;
}

/**
 * Generates an HTML list of addon venues using a provided template.
 *
 * @param array $uvaddonvenues Array of addon venues, where each venue contains details such as 'icon', 'title', and 'addonvenues' (list of items).
 * @param mixed $uveventdata Optional event data to be passed to the addon venues items list generator.
 * @return string HTML string representing the list of addon venues.
 *
 * The function uses a template to render each addon venue, including its icon, title, and a nested list of items.
 * If an icon URL is not provided or is a placeholder, a default icon is used.
 * The function also checks for active dropdowns in the global $urvenue_ws_core_lib variable to set a CSS class.
 */
function urvenue_ws_get_addonvenues_list($uvaddonvenues, $uveventdata = "") {
    global $urvenue_ws_core_lib;

    $uvaddonvenueslist = "";
    $uvactivedropdowns = (isset($urvenue_ws_core_lib["events"]["event-activedropdowns"]) and $urvenue_ws_core_lib["events"]["event-activedropdowns"]) ? "uwsactive" : "";

    if (is_array($uvaddonvenues) and $uvaddonvenues) {
        $uvaddonvenuestpl = urvenue_ws_get_template("inventory/inventory-list-addonvenues");

        foreach ($uvaddonvenues as $uvaddonvenuecode => $uvaddonvenue) {
            $uvaddonvenueicon = (isset($uvaddonvenue["icon"]) and $uvaddonvenue["icon"] and $uvaddonvenue["icon"] !== "{iconurl}") ? $uvaddonvenue["icon"] : urvenue_ws_get_dummyapi("icons/utensils", "svg");
            if (preg_match('/^https?:\/\//', $uvaddonvenueicon)) {
                $uvaddonvenueicon = "<img src='$uvaddonvenueicon' alt='" . htmlspecialchars($uvaddonvenue["title"], ENT_QUOTES) . " Icon' class='uwsaddonvenueiconimg' />";
            }
            $uvaddonvenuetitle = $uvaddonvenue["title"];
            $uvaddonvenuesitemslist = $uvaddonvenue["addonvenues"];

            if ($uvaddonvenuesitemslist) {
                $uvaddonvenuesitemslist = urvenue_ws_get_addonvenuesitemslist($uvaddonvenuesitemslist, $uveventdata);
                $uvnodecontent = "<div class='uws-invitems-list'>$uvaddonvenuesitemslist</div>";
            }

            $uvaddonvenuehtml = str_replace(
                array(
                    "{addonvenuescode}",
                    "{addonvenuesname}",
                    "{nodecontent}",
                    "{nodetype}",
                    "{addonvenuesicon}",
                    "{activedropdowns}",
                ),
                array(
                    $uvaddonvenuecode,
                    $uvaddonvenuetitle,
                    $uvnodecontent,
                    "addonvenue",
                    $uvaddonvenueicon,
                    $uvactivedropdowns,
                ),
                $uvaddonvenuestpl
            );

            $uvaddonvenueslist .= $uvaddonvenuehtml;
        }
    }

    return $uvaddonvenueslist;
}

/*Get Ecozones List
    Requires: eventdata, array with event info and ecozones
*/
function urvenue_ws_get_ecozones_list($uvecozonesmap = "", $uvargs = "")
{
    $uvecozoneslist = "";

    if (is_array($uvecozonesmap)) {
        $uvecozoneitemtpl = urvenue_ws_get_template("inventory/inventory-ecozones-list-item");

        foreach ($uvecozonesmap as $uvecozone => $uvthisecozone) {
            $uvecozone = urvenue_ws_standardize_ecozone($uvecozone);
            $uvecozonename = $uvthisecozone["econame"];
            $uveventcode = $uvthisecozone["eventcode"];

            $uvactionclass = urvenue_ws_get_arg($uvargs, "actionclass", "uwsjs-select-invlist-ecozone");
            $uvclockicon = urvenue_ws_get_icon("clock");

            $uvthisecozoneitem = str_replace(
                array(
                    "{actionclass}",
                    "{ecozone}",
                    "{ecozonename}",
                    "{eventcode}",
                    "{clockicon}"
                ),
                array(
                    $uvactionclass,
                    $uvecozone,
                    $uvecozonename,
                    $uveventcode,
                    $uvclockicon,
                ),
                $uvecozoneitemtpl
            );

            if ($uvthisecozone["econame"])
                $uvecozoneslist .= $uvthisecozoneitem;
        }
    }

    return $uvecozoneslist;
}

/*Add Master info from masterlist to ecolist
    Requires: uveventinventoryecolist: ecolist from inventory feed, uveventinventorymasterlist: masterlist from inventory feed
*/
function urvenue_ws_add_masterinfo_to_ecolist($uveventinventoryecolist, $uveventinventorymasterlist)
{
    if (is_array($uveventinventoryecolist)) {
        foreach ($uveventinventoryecolist as $uvecobooktypecode => $uvecolistbooktype) {
            foreach ($uvecolistbooktype["ecomasters"] as $uvecomastercd => $uvecomaster) {
                if (isset($uveventinventorymasterlist[$uvecomastercd])) {
                    $uvkeepecoitems = $uvecomaster["ecoitems"];
                    $uveventinventoryecolist[$uvecobooktypecode]["ecomasters"][$uvecomastercd] = $uveventinventorymasterlist[$uvecomastercd];
                    $uveventinventoryecolist[$uvecobooktypecode]["ecomasters"][$uvecomastercd]["ecoitems"] = $uvkeepecoitems;
                }
            }
        }
    }

    return $uveventinventoryecolist;
}

/*Get plan mascode list from ecolist
    Requires: uvecolist: ecolist object
*/
function urvenue_ws_get_plain_ecolist($uvecolist)
{
    $uvplainecolist = "";

    if (is_array($uvecolist)) {
        $uvplainecolist = array();
        foreach ($uvecolist as $uvbooktype) {
            foreach ($uvbooktype["ecomasters"] as $uvmascode => $uvecomasters) {
                $uvplainecolist[$uvmascode] = $uvecomasters["ecoitems"];
            }
        }
    }

    return $uvplainecolist;
}

/*Get Booktypes List
Requires: inventoryitems object with plain list of items, uvinventoryecolist: ecolist from inventory feed
*/
function urvenue_ws_get_booktypes_list($uvinventoryitems, $uvinventoryecolist, $uvinvbooktypetmpl = "")
{
    global $urvenue_ws_core_lib;

    $uvbooktypeslist = "";
    $uvactivedropdowns = (isset($urvenue_ws_core_lib["events"]["event-activedropdowns"]) and $urvenue_ws_core_lib["events"]["event-activedropdowns"]) ? "uwsactive" : "";

    if (is_array($uvinventoryecolist) and is_array($uvinventoryitems)) {
        $uvbooktypedeftempl = "inventory-list-booktype";
        $uvinvbooktypetmpl = ($uvinvbooktypetmpl) ? $uvinvbooktypetmpl : $uvbooktypedeftempl;
        $uvbooktypetpl = urvenue_ws_get_template("inventory/" . $uvinvbooktypetmpl);

        foreach ($uvinventoryecolist as $uvbooktypecode => $uvbooktype) {
            $uvbooktypeinfo = $uvbooktype["booktype"];
            $uvbooktypename = $uvbooktypeinfo["label"];
            $uvecomasters = $uvbooktype["ecomasters"];

            if ($uvecomasters) {
                //$uvecozone = $uvnode["ecocode"];
                $uvitemslist = urvenue_ws_get_ecoitemslist($uvecomasters, $uvinventoryitems);
                $uvnodecontent = "<div class='uws-invitems-list'>$uvitemslist</div>";
                //$uvnodecontent = "<div class='uws-invitems-list'>$uvitemslist</div>";
            }

            $uvbooktypeicon = "";
            $uvbooktypeinfo["icon"] = (isset($uvbooktypeinfo["icon"]) and $uvbooktypeinfo["icon"]) ? $uvbooktypeinfo["icon"] : "wine-bottle";
            if ($uvbooktypeinfo["icon"])
                $uvbooktypeicon = urvenue_ws_get_dummyapi("icons/" . $uvbooktypeinfo["icon"], "svg");

            $uvbooktypehtml = str_replace(
                array(
                    "{booktypecode}",
                    "{booktypename}",
                    "{nodecontent}",
                    "{nodetype}",
                    "{booktypeicon}",
                    "{activedropdowns}",
                ),
                array(
                    $uvbooktypecode,
                    $uvbooktypename,
                    $uvnodecontent,
                    $uvnode["nodetype"],
                    $uvbooktypeicon,
                    $uvactivedropdowns,
                ),
                $uvbooktypetpl
            );

            $uvbooktypeslist .= $uvbooktypehtml;
        }
    }

    return $uvbooktypeslist;
}

/*Get Items List
Requires: uvmasteritems: masteritems object from node, inventoryitems: array with inventory ecozones items
*/
function urvenue_ws_get_itemslist($uvmasteritems, $uviventoryecozonesitems)
{
    $uvitemslist = "";
    if (is_array($uvmasteritems) and is_array($uviventoryecozonesitems)) {
        $uvlistitemtemplate = urvenue_ws_get_template("inventory/inventory-list-item");
        $uvecozone = $uvmasteritems["ecocode"];

        foreach ($uvmasteritems["mastercodes"] as $uvmascode) {
            //$uvitem = uws_inv_normalize_item($uvitem);
            //$uvitemprice = uws_calculate_price($uvitem, $uvitem["capacity"]);

            $uvitem = $uviventoryecozonesitems[$uvecozone]["items"][$uvmascode];
            $uvshowprice = ($uvitem["paynow"]) ? $uvitem["paynow"] : $uvitem["listprice"];
            $uvshowprice = ($uvitem["paytype"] == "deposit") ? $uvitem["listprice"] : $uvshowprice;
            $uvpricetypeclass = (!$uvshowprice) ? "uwspricelistzero" : "";
            $uvpaynow = ($uvitem["paytype"] == "deposit") ? $uvitem["paynow"] : "";
            $uvpaynowdiv = ($uvpaynow) ? "<div class='uwspricing'>Pay Now</div><div class='uwsprice $uvpricetypeclass' data-symbol='" . $uvitem["currency_symbol"] . "'>" . urvenue_ws_frontformat_money($uvpaynow) . "</div>" : "";
            $uvitemdprice = ($uvitem["listprice"]) ? urvenue_ws_frontformat_money($uvshowprice) : $uvitem["listzero"];

            $uvselbtnlabel = ($uvitem["label"]) ? $uvitem["label"] : urvenue_ws_lang("select");
            $uvitemselectbtn = "<a class='uwsjs-inv-item-select uws-btn uws-btn-s' href='#uwsinv-select-" . $uvitem["mastercode"] . "' aria-label='Select " . $uvitem["name"] . "' data-mastercode='" . $uvitem["mastercode"] . "'><i class='uwsicon-basket'></i> <span>" . $uvselbtnlabel . "</span></a>";

            $uvitemhigh = ($uvitem["highlight"]) ? "<div class='uwshighlight'>" . $uvitem["highlight"] . "</div>" : "";
            $uvitemdescr = ($uvitem["descr"] or ($uvitem["highlight"] and is_array($uvitem["itemimages"]))) ? "<div class='uwsmoreinfo'><a href='javascript:;' class='uwsjs-inv-item-showinfo' data-mastercode='" . $uvitem["mastercode"] . "' aria-label='View More Info'><i class='uwsicon-info-circled'></i> <span>" . urvenue_ws_lang("more-info") . "</span></a></div>" : "";
            $uvitemextrainfo = ($uvitemhigh or $uvitemdescr) ? "<div class='uwsextrainfo'>{$uvitemhigh}{$uvitemdescr}</div>" : "";

            $uvitemhtml = str_replace(
                array(
                    "{mastercode}",
                    "{itemname}",
                    "{frontprice}",
                    "{currencysymbol}",
                    "{itemselectbtn}",
                    "{iteminfodiv}",
                    "{pricingdisplay}",
                    "{paytype}",
                    "{pricetypeclass}",
                    "{itempaynowdiv}"
                ),
                array(
                    $uvitem["mastercode"],
                    $uvitem["itemname"],
                    $uvitemdprice,
                    $uvitem["currency_symbol"],
                    $uvitemselectbtn,
                    $uvitemextrainfo,
                    urvenue_ws_lang($uvitem["pricingdisplay"]),
                    $uvitem["paytype"],
                    $uvpricetypeclass,
                    $uvpaynowdiv
                ),
                $uvlistitemtemplate
            );

            $uvitemslist .= $uvitemhtml;
        }
    }

    return $uvitemslist;
}

/*Get Eco Items List
Requires: uvecomasters: uvecomasters object, inventoryitems: array with inventory items
*/
function urvenue_ws_get_ecoitemslist($uvecomasters, $uvinventoryitems) {
    $uvitemslist = "";

    if (is_array($uvecomasters) and is_array($uvinventoryitems)) {
        $uvlistitemtemplate = urvenue_ws_get_template("inventory/inventory-list-ecoitems");

        foreach ($uvecomasters as $uvmascode => $uvecomaster) {
            $uvecoitemdisplayinfo = urvenue_ws_get_ecoitemdisplayinfo($uvecomaster, $uvinventoryitems);

            $uvshowprice = ($uvecoitemdisplayinfo["listprice"]) ? $uvecoitemdisplayinfo["listprice"] / 1 : "";
            $uvpaybase = ($uvecoitemdisplayinfo["paybase"]) ? $uvecoitemdisplayinfo["paybase"] : "";
            $uvitemdprice = ($uvshowprice) ? urvenue_ws_frontformat_money($uvshowprice) : $uvecoitemdisplayinfo["listzero"];
            $uvselbtnlabel = ($uvecoitemdisplayinfo["inactive"] && $uvecoitemdisplayinfo["inactive"] === 1) ? "Unavailable" : (($uvecoitemdisplayinfo["label"]) ? $uvecoitemdisplayinfo["label"] : urvenue_ws_lang("book"));
            $uvitemselectbtn = "<div class='uwsinvitembtncont'><a class='uwsjs-inv-ecoitem-select uws-btn uws-btn-p' href='#uwsinv-select-ecoitem-$uvmascode' aria-label='Select " . $uvecoitemdisplayinfo["itemname"] . "' data-mascode='$uvmascode'><span>" . $uvselbtnlabel . "</span></a></div>";
            $uvitemhigh = ($uvecoitemdisplayinfo["highlight"]) ? $uvecoitemdisplayinfo["highlight"] : "";
            $uvitemmoreinfo = ($uvecoitemdisplayinfo["hasmoreinfo"]) ? " <a href='javascript:;' class='uwsjs-inv-ecoitem-showinfo' data-mascode='$uvmascode' aria-label='View More Info'><span>" . urvenue_ws_lang("more-info") . ".</span></a>" : "";
            $uvitemextrainfo = ($uvitemhigh or $uvitemmoreinfo) ? "<div class='uwsextrainfo'>{$uvitemhigh}{$uvitemmoreinfo}</div>" : "";
            $uvpricetypeclass = (!$uvshowprice) ? "uwspricelistzero" : "";
            $uvglobaltype = isset($uvecoitemdisplayinfo["globaltype"]) ? $uvecoitemdisplayinfo["globaltype"] : "";
            $uvvendor = ($uvecoitemdisplayinfo["vendor"]) ? $uvecoitemdisplayinfo["vendor"] : "";
            $uvpaynowdiv = ($uvpaybase) ? "<div class='uwspaynow'><span>" . $uvecoitemdisplayinfo["basedisplay"] . "</span> <span class='uwsprice $uvpricetypeclass' data-symbol='" . $uvecoitemdisplayinfo["currency_symbol"] . "'>" . urvenue_ws_frontformat_money($uvpaybase) . "</span></div>" : "";
            $uvitembadge = ($uvecoitemdisplayinfo["badge"]) ? $uvecoitemdisplayinfo["badge"] : "";
            $uvitemguestbubble = ($uvecoitemdisplayinfo["capacity"]) ? "<div class='uwsbubble'><i class='uwsicon-itemguests'></i> <span>" . $uvecoitemdisplayinfo["capacity"] . "</span></div>" : "";
            $uvitemtimebubble = ($uvecoitemdisplayinfo["timelabel"]) ? "<div class='uwsbubble'><i class='uwsicon-itemclock'></i> <span>" . $uvecoitemdisplayinfo["timelabel"] . "</span></div>" : "";
            $uvitemdisclaimer = ($uvecoitemdisplayinfo["disclaimer"]) ? "<div class='uwsitemdisclaimer'><span class='uwsasteric'>*</span><span>" . $uvecoitemdisplayinfo["disclaimer"] . "</span></div>" : "";
            $uvpricing = "<a class='uwsjs-inv-ecoitem-pricing uwspricebreakdown uwspricing' href='#uwsinv-select-ecoitem-$uvmascode' aria-label='Pricing Breakdown " . $uvecoitemdisplayinfo["itemname"] . "' data-mascode='$uvmascode'><i class='uwsicon-iteminfo'></i><span>" . urvenue_ws_lang($uvecoitemdisplayinfo["pricing"]) . "</span></a>";
            $uvinactive = ($uvecoitemdisplayinfo["inactive"] and $uvecoitemdisplayinfo["inactive"] === 1) ? " uwsinactive" : "";

            $uvitemhtml = str_replace(
                array(
                    "{mascode}",
                    "{itemname}",
                    "{frontprice}",
                    "{currencysymbol}",
                    "{itemselectbtn}",
                    "{iteminfodiv}",
                    "{pricetypeclass}",
                    "{itempaynowdiv}",
                    "{globaltype}",
                    "{vendor}",
                    "{paytype}",
                    "{itembadge}",
                    "{itemguestbubble}",
                    "{itemtimebubble}",
                    "{itemdisclaimer}",
                    "{itempricing}",
                    "{inactive}",
                ),
                array(
                    $uvmascode,
                    $uvecoitemdisplayinfo["itemname"],
                    $uvitemdprice,
                    $uvecoitemdisplayinfo["currency_symbol"],
                    $uvitemselectbtn,
                    $uvitemextrainfo,
                    $uvpricetypeclass,
                    $uvpaynowdiv,
                    $uvglobaltype,
                    $uvvendor,
                    $uvecoitemdisplayinfo["paytype"],
                    $uvitembadge,
                    $uvitemguestbubble,
                    $uvitemtimebubble,
                    $uvitemdisclaimer,
                    $uvpricing,
                    $uvinactive,
                ),
                $uvlistitemtemplate
            );

            $uvitemslist .= $uvitemhtml;
        }
    }
    /*if(is_array($uvmasteritems) and is_array($uviventoryecozonesitems)){
        $uvlistitemtemplate = urvenue_ws_get_template("inventory/inventory-list-item");
        $uvecozone = $uvmasteritems["ecocode"];
       
        foreach($uvmasteritems["mastercodes"] as $uvmascode){
            //$uvitem = uws_inv_normalize_item($uvitem);
            //$uvitemprice = uws_calculate_price($uvitem, $uvitem["capacity"]);

            $uvitem = $uviventoryecozonesitems[$uvecozone]["items"][$uvmascode];
            $uvitemdprice = ($uvitem["listprice"]) ? urvenue_ws_frontformat_money($uvitem["listprice"]) : $uvitem["listzero"];
            $uvpricetypeclass = (!$uvitem["listprice"]) ? "uwspricelistzero" : "";
            
            $uvselbtnlabel = ($uvitem["label"]) ? $uvitem["label"] : urvenue_ws_lang("select");
            $uvitemselectbtn = "<a class='uwsjs-inv-item-select uws-btn uws-btn-s' href='#uwsinv-select-" . $uvitem["mastercode"] . "' aria-label='Select " . $uvitem["name"] . "' data-mastercode='" . $uvitem["mastercode"] . "'><i class='uwsicon-basket'></i> <span>" . $uvselbtnlabel . "</span></a>";

            $uvitemhigh = ($uvitem["highlight"]) ? "<div class='uwshighlight'>" . $uvitem["highlight"] . "</div>" : "";
            $uvitemdescr = ($uvitem["descr"] or ($uvitem["highlight"] and is_array($uvitem["itemimages"]))) ? "<div class='uwsmoreinfo'><a href='javascript:;' class='uwsjs-inv-item-showinfo' data-mastercode='" . $uvitem["mastercode"] . "' aria-label='View More Info'><i class='uwsicon-info-circled'></i> <span>" . urvenue_ws_lang("more-info") . "</span></a></div>" : "";
            $uvitemextrainfo = ($uvitemhigh or $uvitemdescr) ? "<div class='uwsextrainfo'>{$uvitemhigh}{$uvitemdescr}</div>" : "";
            

            $uvitemhtml = str_replace(
                array(
                    "{mastercode}",
                    "{itemname}",
                    "{frontprice}",
                    "{currencysymbol}",
                    "{itemselectbtn}",
                    "{iteminfodiv}",
                    "{pricingdisplay}",
                    "{paytype}",
                    "{pricetypeclass}"
                ),
                array(
                    $uvitem["mastercode"],
                    $uvitem["itemname"],
                    $uvitemdprice,
                    $uvitem["currency_symbol"],
                    $uvitemselectbtn,
                    $uvitemextrainfo,
                    urvenue_ws_lang($uvitem["pricingdisplay"]),
                    $uvitem["paytype"],
                    $uvpricetypeclass,
                ),
                $uvlistitemtemplate
            );

            $uvitemslist .= $uvitemhtml;
        }
    }*/

    return $uvitemslist;
}

/**
 * Generates a list of HTML venue items for addon venues based on provided data and event date.
 *
 * This function iterates through an array of addon venue items, applies date and exclusion filters,
 * retrieves venue information, and renders each venue using a template. The resulting HTML is returned as a string.
 *
 * @param array $uvaddonvenuesitems Array of addon venue items, each containing venue details such as code, type, logo, start/end dates, and excluded dates.
 * @param array|string $uveventdata (Optional) Event data array, may contain a 'date' key to filter venues by date. Defaults to an empty string.
 * @return string HTML string containing the rendered list of addon venue items.
 */
function urvenue_ws_get_addonvenuesitemslist($uvaddonvenuesitems, $uveventdata = "") {
    global $urvenue_ws_today;

    $uvvenuesitemslist = "";
    $uvshowvenue = true;

    if (is_array($uvaddonvenuesitems) and is_array($uvaddonvenuesitems)) {
        $uvvenueslistitemtemplate = urvenue_ws_get_template("inventory/inventory-list-addonvenues-items");
        $uvtheme = urvenue_ws_get_theme();

        $uvdate = (isset($uveventdata["date"]) and $uveventdata["date"]) ? $uveventdata["date"] : $urvenue_ws_today;
        $uvenddate = urvenue_ws_get_events_max_date("Y-m-d");

        foreach ($uvaddonvenuesitems as $uvaddonvenuekey => $uvaddonvenueitem) {
            $uvaddonglobaltype = (isset($uvaddonvenueitem["type"]) and $uvaddonvenueitem["type"]) ? $uvaddonvenueitem["type"] : "";
            $uvaddonvenuecode = $uvaddonvenueitem["venuecode"];
            $uvaddonvenueexcludeddates = (isset($uvaddonvenueitem["exclude"]) and $uvaddonvenueitem["exclude"]) ? $uvaddonvenueitem["exclude"] : "";
            $uvaddonvenuelogo = (isset($uvaddonvenueitem["logo"]) and $uvaddonvenueitem["logo"] and $uvaddonvenueitem["logo"] !== "{logourl}") ? $uvaddonvenueitem["logo"] : "";
            $uvaddonvenuemicro = $uvaddonvenuekey;
            $uvaddonvenuestartdate = (isset($uvaddonvenueitem["startdate"]) and $uvaddonvenueitem["startdate"]) ? $uvaddonvenueitem["startdate"] : "";
            $uvaddonvenueenddate = (isset($uvaddonvenueitem["enddate"]) and $uvaddonvenueitem["enddate"]) ? $uvaddonvenueitem["enddate"] : "";

            // Validate if venue should be shown based on date and exclude list
            if ($uvaddonvenuestartdate && $uvdate < $uvaddonvenuestartdate) {
                $uvshowvenue = false;
            }
            if ($uvaddonvenueenddate && $uvdate > $uvaddonvenueenddate) {
                $uvshowvenue = false;
            }
            if ($uvaddonvenueexcludeddates) {
                $uvexcludedates = array_map('trim', explode(',', $uvaddonvenueexcludeddates));
                if (in_array($uvdate, $uvexcludedates)) {
                    $uvshowvenue = false;
                }
            }
            if (!$uvshowvenue) continue;

            $uvvenuefeedinfo = urvenue_ws_get_venueinfo($uvaddonvenuecode);

            if (is_array($uvvenuefeedinfo) and $uvvenuefeedinfo["manageentid"] and $uvvenuefeedinfo["code"]) {
                $uvaddonvenuedisplayinfo = urvenue_ws_get_addonvenuedisplayinfo($uvvenuefeedinfo, $uvtheme, $uvaddonvenueitem);

                $uvvenueitemname = $uvaddonvenuedisplayinfo["venuename"];
                $uvvenueitemaddress = $uvaddonvenuedisplayinfo["venueaddress"];
                $uvvenueitemlogo = $uvaddonvenuelogo ?: $uvaddonvenuedisplayinfo["venuelogo"] ?: "";
                if ($uvvenueitemlogo) {
                    $uvvenueitemlogo = "<div class='uwsvenuelogo'><img decoding='async' class='uwsimgloading' src='$uvvenueitemlogo' alt='" . $uvvenueitemname . " - Logo' onload='this.classList.add(\"uwsloaded\")'></div>";
                }
                $uvvenueitemdescr = ($uvaddonvenuedisplayinfo["venuedescr"]) ? $uvaddonvenuedisplayinfo["venuedescr"] : "";
                $uvvenueiteminfo = ($uvvenueitemdescr) ? "<div class='uwsextrainfo'>{$uvvenueitemdescr}</div>" : "";
                $uvvenueitemvenuecode = $uvaddonvenuedisplayinfo["venuecode"];
                $uvvenueitemmanageentid = $uvaddonvenuedisplayinfo["manageentid"];

                $uvvenueitemhtml = str_replace(
                    array(
                        "{venuelogo}",
                        "{venuename}",
                        "{venueaddress}",
                        "{venuedescr}",
                        "{venueinfodiv}",
                        "{venuecode}",
                        "{globaltype}",
                        "{manageentid}",
                        "{microcode}",
                        "{date}",
                        "{managementid}",
                    ),
                    array(
                        $uvvenueitemlogo,
                        $uvvenueitemname,
                        $uvvenueitemaddress,
                        $uvvenueitemdescr,
                        $uvvenueiteminfo,
                        $uvvenueitemvenuecode,
                        $uvaddonglobaltype,
                        $uvvenueitemmanageentid,
                        $uvaddonvenuemicro,
                        $uvdate,
                        $uvvenueitemmanageentid,
                    ),
                    $uvvenueslistitemtemplate
                );

                $uvvenuesitemslist .= $uvvenueitemhtml;
            }
        }
    }

    return $uvvenuesitemslist;
}

/**
 * Retrieves and formats venue display information, optionally overriding with addon venue data and theme.
 *
 * @param array $uvvenuefeedinfo  The main venue feed information array. Expected keys: 'name', 'address', 'descr', 'venueimages', 'code', 'manageentid'.
 * @param string $uvtheme         (Optional) The theme to use for selecting the venue logo. Accepts 'uws-light' or 'uws-dark'. Default is 'uws-light'.
 * @param array|string $uvaddonvenue (Optional) Addon venue information array to override address and description. Default is empty.
 *
 * @return array                  Associative array containing:
 *                                - 'venuelogo'   => (string) URL of the venue logo based on theme.
 *                                - 'venuename'   => (string) Name of the venue.
 *                                - 'venueaddress'=> (string) Address of the venue (overridden if provided in addon).
 *                                - 'venuedescr'  => (string) Description of the venue (overridden if provided in addon).
 *                                - 'venuecode'   => (string) Venue code.
 *                                - 'manageentid' => (string) Management entity ID.
 */
function urvenue_ws_get_addonvenuedisplayinfo($uvvenuefeedinfo, $uvtheme = "uws-light", $uvaddonvenue = "") {
    $uvaddonvenuelistieminfo = "";

    if (is_array($uvvenuefeedinfo)) {
        $uvvenuelogo = "";
        $uvvenuename = $uvvenuefeedinfo["name"];
        $uvvenueaddress = $uvvenuefeedinfo["address"];
        $uvvenueaddress = (isset($uvaddonvenue["address"]) and $uvaddonvenue["address"]) ? $uvaddonvenue["address"] : $uvvenueaddress;
        $uvvenuedescr = (isset($uvvenuefeedinfo["descr"]) and $uvvenuefeedinfo["descr"]) ? $uvvenuefeedinfo["descr"] : "";
        $uvvenuedescr = (isset($uvaddonvenue["descr"]) and $uvaddonvenue["descr"]) ? $uvaddonvenue["descr"] : $uvvenuedescr;

        $uvvenueimages = (isset($uvvenuefeedinfo["venueimages"]) and is_array($uvvenuefeedinfo["venueimages"])) ? $uvvenuefeedinfo["venueimages"] : "";

        if($uvvenueimages && is_array($uvvenueimages)) {
            if ($uvtheme === "uws-dark" && isset($uvvenueimages["logodarkbg"]["url"])) {
                $uvvenuelogo = $uvvenueimages["logodarkbg"]["url"];
            } elseif ($uvtheme === "uws-light" && isset($uvvenueimages["logolightbg"]["url"])) {
                $uvvenuelogo = $uvvenueimages["logolightbg"]["url"];
            } else {
                $uvvenuelogo = isset($uvvenueimages["logolightbg"]["url"]) ? $uvvenueimages["logolightbg"]["url"] : "";
            }
        }

        $uvaddonvenuelistieminfo = array(
            "venuelogo" => $uvvenuelogo,
            "venuename" => $uvvenuename,
            "venueaddress" => $uvvenueaddress,
            "venuedescr" => $uvvenuedescr,
            "venuecode" => $uvvenuefeedinfo["code"],
            "manageentid" => $uvvenuefeedinfo["manageentid"],
        );
    }

    return $uvaddonvenuelistieminfo;
}

/*Get Eco Item Display Info
    Requires: uvecomaster: ecoitem object, inventoryitems: array with inventory items
*/
function urvenue_ws_get_ecoitemdisplayinfo($uvecomaster, $uvinventoryitems) {
    $uveoclistieminfo = "";

    if (is_array($uvecomaster) and is_array($uvinventoryitems)) {
        $uvecolistitem = $uvecomaster["ecoitems"];
        $uvismultiple = (count($uvecolistitem) > 1) ? 1 : 0;
        $uvitemname = $uvlistprice = $uvpaybase = "";
        $uvhasmoreinfo = 0;
        $uvmastername = $uvecomaster["mastername"];
        $uvmasterhighlight = $uvecomaster["masterhighlight"];

        foreach ($uvecolistitem as $uvecozone => $uvmastercode) {
            $uvitem = $uvinventoryitems[$uvmastercode];
            $uvitemname .= $uvitem["itemname"] . " / ";
            $uvlistprice = ($uvlistprice == "" or $uvitem["listprice"] < $uvlistprice) ? $uvitem["listprice"] : $uvlistprice;
            $uvpaybase = ($uvpaybase == "" or $uvitem["paybase"] < $uvpaybase) ? $uvitem["paybase"] : $uvpaybase;

            if ($uvitem["descr"] or ($uvitem["highlight"] and is_array($uvitem["itemimages"]))) {
                $uvhasmoreinfo = 1;
            }
        }
        $uvitemname = rtrim($uvitemname, " / ");

        $uvitemname = ($uvmastername and $uvismultiple) ? $uvmastername : $uvitemname;
        $uvhightlight = ($uvmasterhighlight) ? $uvmasterhighlight : "";
        $uvhightlight = (!$uvismultiple) ? $uvitem["highlight"] : $uvhightlight;
        $uvdescr = (!$uvismultiple) ? $uvitem["descr"] : "";
        $uvpricing = (!$uvismultiple) ? $uvitem["pricingdisplay"] : "";
        $uvlistprice = (!$uvismultiple) ? $uvitem["listprice"] : "";
        $uvlistzero = (!$uvismultiple) ? $uvitem["listzero"] : "";
        $uvpaybase = (!$uvismultiple and $uvitem["paybase"]) ? $uvitem["paybase"] : "";
        $uvpaytype = (!$uvismultiple) ? $uvitem["paytype"] : "";
        $uvglobaltype = (!$uvismultiple) ? $uvitem["globaltype"] : "";
        $uvinactive = (!$uvismultiple) ? $uvitem["inactive"] : "";
        $uvvendor = (!$uvismultiple) ? $uvitem["vendor"] : "";

        $uveoclistieminfo = array(
            "itemname" => $uvitemname,
            "highlight" => $uvhightlight,
            "descr" => $uvdescr,
            "listprice" => $uvlistprice,
            "listzero" => $uvlistzero,
            "pricing" => $uvpricing,
            "currency_symbol" => $uvitem["currency_symbol"],
            "hasmoreinfo" => $uvhasmoreinfo,
            "label" => $uvitem["label"],
            "paytype" => $uvpaytype,
            "paybase" => $uvpaybase,
            "globaltype" => $uvglobaltype,
            "badge" => $uvitem["badge"],
            "capacity" => $uvitem["capacity"],
            "timelabel" => $uvitem["timelabel"],
            "disclaimer" => $uvitem["disclaimer"],
            "basedisplay" => $uvitem["basedisplay"],
            "vendor" => $uvvendor,
            "inactive" => $uvinactive,
        );
    }

    return $uveoclistieminfo;
}

/*Get flat array list with all the inventory items
    Requires: uvinventory: "inventory" object from venueday API, 
    Optional: appendinfo: array with fields to append to items
*/
function urvenue_ws_get_inventory_flatlist($uvinventory, $uvappendinfotoitem = "")
{
    $uvitemslistarray = array();

    if (is_array($uvinventory) and is_array($uvinventory["booktypes"])) {
        $uvbooktypes = $uvinventory["booktypes"];

        if (is_array($uvbooktypes)) {
            foreach ($uvbooktypes as $uvbooktypecode => $uvbooktype) {
                $uvbooktypeitems = $uvbooktype["items"];

                if (is_array($uvbooktypeitems)) {
                    foreach ($uvbooktypeitems as $uvbooktypemastercode => $uvbooktypeitem) {
                        $uvtheitem = urvenue_ws_inv_normalize_item($uvbooktypeitem);

                        if ($uvappendinfotoitem) {
                            $uvtheitem = array_merge($uvtheitem, $uvappendinfotoitem);
                        }

                        $uvitemslistarray[$uvbooktypemastercode] = $uvtheitem;
                        //$uvitemslistarray[$uvbooktypemastercode]["ddate"] = date("F j, Y", strtotime($uvitemslistarray[$uvbooktypemastercode]["date"]));
                        $uvitemslistarray[$uvbooktypemastercode]["booktypecode"] = $uvbooktypecode;
                    }
                }
            }
        }
    }

    return $uvitemslistarray;
}

/*Get inventory feed
    Requires: eventdata: array with date, venuecode, ecozone
*/
function urvenue_ws_get_eventinventory_list_feed($uveventdata, $uvglobaltype = "", $uvbooktypename = "")
{
    global $urvenue_ws_core_lib;

    $uveventinventorylistfeed = "";

    if (is_array($uveventdata)) {
        $uvfeedtoken = array(
            "venuecode" => $uveventdata["venuecode"],
            "caldate" => $uveventdata["date"],
            "todate" => $uveventdata["date"],
        );

        $uvinventorylistfeed = urvenue_ws_get_feed("inventory", $uvfeedtoken);

        if (is_array($uvinventorylistfeed) and $uvinventorylistfeed["uv"]["success"]["status"] == "success") {
            //not ecozones filter at this point anymore
            /*if(isset($uveventdata["ecozones"]) and isset($uveventdata["eventcode"])){
                $uveventarray = $uveventdata;
            }
            else{
                $uveventarray = urvenue_ws_get_events_array($uvinventorylistfeed, array("forcereturneventcode" => $uveventdata["eventcode"]));
                $uveventarray = (isset($uveventarray[$uveventdata["eventcode"]])) ? $uveventarray[$uveventdata["eventcode"]] : "";
            }

            $uvisecogrouping = 0; //Is managed as separated ecozones as default

            if (isset($uveventarray["ecozones"]) and is_array($uveventarray["ecozones"]) and count($uveventarray["ecozones"]) > 1) {
                //Enable multiple ecozones if event info has multiple ecozones
                $uvisecogrouping = 1;
            } else {*/
            $uvinventorylistfeed = urvenue_ws_get_inventory_single_ecozone($uvinventorylistfeed, $uveventdata);
            //}

            $uvinventorylistheader = $uvinventorylistfeed["uv"]["data"]["header"];
            $uvinventorylistfeedinv = $uvinventorylistfeed["uv"]["data"]["inventory"];
            $uveventiventoryitems = $uvinventorylistfeed["uv"]["data"]["items"];
            $uvinventoryecozones = "";

            if (is_array($uveventiventoryitems)) {
                if ($uvglobaltype != "") {
                    $uvglobaltypefilter = $uvglobaltype;
                    // Filter items where globaltype matches the variable
                    $uveventiventoryitems = array_filter($uveventiventoryitems, function ($item) use ($uvglobaltypefilter) {
                        return $item['globaltype'] === $uvglobaltypefilter;
                    });
                }

                // Filter by booktypename
                if ($uvbooktypename != "") {
                    $uvbooktypenamefilter = $uvbooktypename;
                    $uveventiventoryitems = array_filter($uveventiventoryitems, function ($item) use ($uvbooktypenamefilter) {
                        return strpos($item['booktypename'], $uvbooktypenamefilter) !== false;
                    });
                }
            }

            $uvsdate = gmdate("ymd", strtotime($uveventdata["date"]));
            $uveventiventorynodes = $uveventecolist = $uvmasterlist = "";

            if ($uvinventorylistfeedinv and $uvinventorylistfeedinv["D" . $uvsdate] and $uvinventorylistfeedinv["D" . $uvsdate]["tree"] and $uvinventorylistfeedinv["D" . $uvsdate]["tree"]["nodes"])
                $uveventiventorynodes = $uvinventorylistfeedinv["D" . $uvsdate]["tree"]["nodes"];

            if ($uvinventorylistfeedinv and $uvinventorylistfeedinv["D" . $uvsdate] and $uvinventorylistfeedinv["D" . $uvsdate]["venues"][$uveventdata["venuecode"]]) {
                $uveventecolist = $uvinventorylistfeedinv["D" . $uvsdate]["venues"][$uveventdata["venuecode"]]["ecolist"];
                $uvmasterlist = $uvinventorylistfeedinv["D" . $uvsdate]["venues"][$uveventdata["venuecode"]]["masterlist"];
                $uvinventoryecozones = $uvinventorylistfeedinv["D" . $uvsdate]["venues"][$uveventdata["venuecode"]]["ecozones"];
            }

            if (is_array($uveventiventoryitems)) {
                if ($uvglobaltype != "") {
                    $relatedMasterCodes = array_column($uveventiventoryitems, 'masteritemcode');
                    // Filter $array['masterlist'] to keep only the objects whose index matches a value in $relatedMasterCodes
                    $filteredMasterList = array_filter($uvmasterlist, function ($key) use ($relatedMasterCodes) {
                        return in_array($key, $relatedMasterCodes);
                    }, ARRAY_FILTER_USE_KEY);


                    $uvmasterlist = $filteredMasterList;
                    // Collect all masteritemcode values from $array['items']
                    $relatedMasterCodes = array_column($uveventiventoryitems, 'masteritemcode');

                    // Iterate over the ecolist and filter out the ecomasters that are not related to the items' masteritemcode
                    foreach ($uveventecolist as $indexVariable => &$booktype) {
                        // Filter the ecomasters to only include those whose keys are in $relatedMasterCodes
                        $booktype['ecomasters'] = array_filter($booktype['ecomasters'], function ($key) use ($relatedMasterCodes) {
                            return in_array($key, $relatedMasterCodes);
                        }, ARRAY_FILTER_USE_KEY);

                        // Remove the booktype if ecomasters is empty
                        if (empty($booktype['ecomasters'])) {
                            unset($uveventecolist[$indexVariable]);
                        }
                    }
                }
            }

            $uveventinventorylistfeed = array(
                "header" => $uvinventorylistheader,
                "nodes" => $uveventiventorynodes,
                "items" => $uveventiventoryitems,
                "ecolist" => $uveventecolist,
                "masterlist" => $uvmasterlist,
                "invecozones" => $uvinventoryecozones,
            );

            /*$uvsdate = date("ymd", strtotime($uveventdata["date"]));
            if($uvinventorylistfeedinv and $uvinventorylistfeedinv["D" . $uvsdate] and $uvinventorylistfeedinv["D" . $uvsdate]["tree"] and $uvinventorylistfeedinv["D" . $uvsdate]["tree"]["nodes"])
                $uveventiventorynodes = $uvinventorylistfeedinv["D" . $uvsdate]["tree"]["nodes"];
            
            if($uvinventorylistfeedinv)
                $uveventiventoryitems = urvenue_ws_get_plain_items_list($uvinventorylistfeedinv);

            if($uvinventorylistfeedinv and $uvinventorylistfeedinv["D" . $uvsdate] and $uvinventorylistfeedinv["D" . $uvsdate]["venues"][$uveventdata["venuecode"]]){
                $uveventecozoneitems = $uvinventorylistfeedinv["D" . $uvsdate]["venues"][$uveventdata["venuecode"]]["ecozones"];
            }

            $uveventinventorylistfeed = array(
                "header" => $uvinventorylistheader,
                "nodes" => $uveventiventorynodes,
                "items" => $uveventiventoryitems,
                "ecozonesitems" => $uveventecozoneitems,
            );*/
        }
    }

    return $uveventinventorylistfeed;
}

/*Get inventory just for 1 ecozones to show on no multiecozones inv list
    Requires: uvinventoryfeed: raw reponse from inventory feed, uveventdata: array with date, venuecode, ecozone 
*/
function urvenue_ws_get_inventory_single_ecozone($uvinventorylistfeed, $uveventdata)
{
    if ($uvinventorylistfeed and $uveventdata) {
        $uvecozone = urvenue_ws_standardize_ecozone($uveventdata["ecozone"]);
        $uvecolist = $uvinventorylistfeed["uv"]["data"]["inventory"]["D" . gmdate("ymd", strtotime($uveventdata["date"]))]["venues"][$uveventdata["venuecode"]]["ecolist"];
        $uvnewecolist = array();

        if ($uvecolist and is_array($uvecolist)) {
            foreach ($uvecolist as $uvbooktypecode => $uvecolistitem) {
                $uvecomasters = $uvecolistitem["ecomasters"];
                $uvnewecoitems = array();

                foreach ($uvecomasters as $uvmascode => $uvmaster) {
                    $uvecoitems = $uvmaster["ecoitems"];
                    $uvnewecoitem = array();

                    foreach ($uvecoitems as $uvthiseco => $uvmastercode) {
                        if ($uvthiseco == $uvecozone)
                            $uvnewecoitem[$uvthiseco] = $uvmastercode;
                    }

                    if (count($uvnewecoitem)) {
                        $uvnewecoitems[$uvmascode]["ecoitems"] = $uvnewecoitem;
                    }
                }

                if (count($uvnewecoitems)) {
                    $uvnewecolist[$uvbooktypecode] = array();
                    $uvnewecolist[$uvbooktypecode]["ecomasters"] = $uvnewecoitems;
                    $uvnewecolist[$uvbooktypecode]["booktype"] = $uvecolistitem["booktype"];
                }
            }
        }

        $uvinventorylistfeed["uv"]["data"]["inventory"]["D" . gmdate("ymd", strtotime($uveventdata["date"]))]["venues"][$uveventdata["venuecode"]]["ecolist"] = $uvnewecolist;
    }

    return $uvinventorylistfeed;
}

/*Get inventory masteritems plain list
    Requires: uvinventory: "inventory" object from venueday API
*/
function urvenue_ws_get_plain_items_list($uvinventory)
{
    $uvitems = array();

    if (is_array($uvinventory)) {
        foreach ($uvinventory as $uvinvdate) {
            foreach ($uvinvdate["venues"] as $uvinvvenue) {
                foreach ($uvinvvenue["ecozones"] as $uvinvecozone) {
                    foreach ($uvinvecozone["items"] as $uvitem) {
                        $uvmastercode = $uvitem["mastercode"];
                        $uvitems[$uvmastercode] = $uvitem;
                    }
                }
            }
        }
    }

    return $uvitems;
}

/*Return items with specific globaltype
    Requires: uvitems plain list of items, globaltype
    returns plain list with items with globaltype
*/
function urvenue_ws_filter_items_globaltype($uvitems, $uvglobaltype = "")
{

    if ($uvglobaltype) {
        $uvitems = array_filter($uvitems, function ($item) use ($uvglobaltype) {
            return $item["globaltype"] == $uvglobaltype;
        });
    }

    return $uvitems;
}

/*Get inventory list from ecozones
    Requires: ecozoneslist from inventorylist api, ecozones from events api
    returns plain list with all items
*/
function urvenue_ws_get_plainitemlist_frominvlistecozones($uvecozoneslist, $uvecozones)
{
    $uvitems = array();

    if (is_array($uvecozoneslist) and is_array($uvecozones)) {
        foreach ($uvecozones as $uvecozone) {
            $uvecozonecode = urvenue_ws_standardize_ecozone($uvecozone["ecozoneid"]);

            if ($uvecozoneslist[$uvecozonecode] and is_array($uvecozoneslist[$uvecozonecode]["items"])) {
                foreach ($uvecozoneslist[$uvecozonecode]["items"] as $uvitem) {
                    $uvmastercode = $uvitem["mastercode"];
                    $uvitems[$uvmastercode] = $uvitem;
                }
            }
        }
    }

    return $uvitems;
}

/*Get all inventory items for the lib microcode
    Requires: args(date)
*/
function urvenue_ws_inventory_microcode_items($uvargs = "")
{
    global $urvenue_ws_core_lib, $urvenue_ws_today;

    $uvitems = "";
    $uvdate = urvenue_ws_get_arg($uvargs, "date", $urvenue_ws_today);
    $uvsdate = gmdate("ymd", strtotime($uvdate));
    $uvmicrocode = $urvenue_ws_core_lib["system"]["microcode"];
    $uvfeedtoken = "venuecode=MIC" . $uvmicrocode . "&caldate=" . $uvdate . "&todate=" . $uvdate;
    $uvinventorylistfeed = urvenue_ws_get_feed("inventorylist", $uvfeedtoken);

    if (is_array($uvinventorylistfeed) and $uvinventorylistfeed["uv"]["success"]["status"] == "success") {
        $uvinventorylistfeedinv = $uvinventorylistfeed["uv"]["data"]["inventory"];
        $uvdatevenues = (is_array($uvinventorylistfeedinv["D" . $uvsdate]) and is_array($uvinventorylistfeedinv["D" . $uvsdate]["venues"])) ? $uvinventorylistfeedinv["D" . $uvsdate]["venues"] : "";

        if ($uvdatevenues) {
            $uvitems = array();
            foreach ($uvdatevenues as $uvinvvenue) {
                if (is_array($uvinvvenue["ecozones"])) {
                    foreach ($uvinvvenue["ecozones"] as $uvinvecozone) {
                        if (is_array($uvinvecozone["items"]))
                            foreach ($uvinvecozone["items"] as $uvitem)
                                $uvitems[$uvitem["mastercode"]] = $uvitem;
                    }
                }
            }
        }
    }

    return $uvitems;
}

/*Change keys on an array to mastercodes
    Requires items: array with inventory items
    Returns: same array but changes mascode to mastercode as the key of the elements
*/
function urvenue_ws_keys_to_mastercode($uvitems)
{
    $uvinvitems = "";

    if (is_array($uvitems)) {
        $uvinvitems = array();

        foreach ($uvitems as $uvitem) {
            $uvinvitems[$uvitem["mastercode"]] = $uvitem;
        }
    }

    return $uvinvitems;
}

/*Get inventory feed
    Requires: feedtoken: string with ecozone, venuecode, caldate. "ecozone=ECZ1234&venuecode=VEN1234&caldate=XXXX-XX-XX. venuelibinfo: venue info from configuration library, use urvenue_ws_get_venuelibinfo_byvenuecode(venuecode) to get the venuelibinfo
*/
function urvenue_ws_get_inventory_feed($uvfeedtoken, $uvvenuelibinfo)
{
    global $urvenue_ws_feeds_lib;

    $uvinventoryfeed = "";

    if ($uvfeedtoken and is_array($uvvenuelibinfo) and is_array($urvenue_ws_feeds_lib)) {
        $uvfeedurl = $urvenue_ws_feeds_lib["venueday"]["url"];
        $uvfeedexpiration = $urvenue_ws_feeds_lib["venueday"]["expiration"];

        if ($uvfeedurl) {
            $uvfeedurl = str_replace(
                array(
                    "{providerid}",
                    "{resellerid}",
                    "{params}",
                ),
                array(
                    $uvvenuelibinfo["providerid"],
                    $uvvenuelibinfo["resellerid"],
                    $uvfeedtoken,
                ),
                $uvfeedurl
            );

            $uvinventoryfeed = urvenue_ws_get_feed($uvfeedurl, $uvfeedexpiration);
        }
    }

    return $uvinventoryfeed;
}

/*Process the item array
    Requires: uvitem(item info array from API)
    Returns: clean version of iteminfo array, removes not nedeed fields
*/
function urvenue_ws_inv_normalize_item($uvitem)
{
    $uvitemreturn = "";

    if (is_array($uvitem)) {
        $uvitemreturn = (isset($uvitem["iteminfo"])) ? $uvitem["iteminfo"] : $uvitem;
        $uvitemreturn = (isset($uvitem["info"])) ? $uvitem["info"] : $uvitemreturn;
        $uvitemreturn["itemtimes"] = $uvitem["itemtimes"];
        $uvitemreturn["itemimages"] = $uvitem["itemimages"];
        $uvitemreturn["mascode"] = "MAS" . $uvitemreturn["masteritemid"];
        $uvitemreturn["currency_symbol"] = (isset($uvitemreturn["currency_symbol"]) and $uvitemreturn["currency_symbol"]) ? $uvitemreturn["currency_symbol"] : "$";

        //process descr
        $uvitemreturn["descr"] = nl2br($uvitemreturn["descr"]);

        //remove not needed fields
        unset($uvitemreturn["systemcode"]);
        unset($uvitemreturn["cutofftstamp"]);
        unset($uvitemreturn["version"]);
        unset($uvitemreturn["termversion"]);
        unset($uvitemreturn["terms"]);
        //unset($uvitemreturn["booktypeid"]);
        //unset($uvitemreturn["booktypename"]);
        unset($uvitemreturn["providerid"]);
        unset($uvitemreturn["catid"]);
        unset($uvitemreturn["templateid"]);
        unset($uvitemreturn["layoutid"]);
        unset($uvitemreturn["timeslotids"]);
        unset($uvitemreturn["pricingtype"]);
        unset($uvitemreturn["chargetypeid"]);
        unset($uvitemreturn["depositchargetypeid"]);
        unset($uvitemreturn["noshowfee"]);
        unset($uvitemreturn["meta"]);
        unset($uvitemreturn["dependent"]);
    }

    return $uvitemreturn;
}

/*Check if has map
    Requires: uvinventory: "inventory" object from venueday API, venuecode: format -> VEN1234
*/
function urvenue_ws_date_has_map($uvinventory, $uvvenuecode)
{
    $uvdatehasmap = 0;

    if (is_array($uvinventory)) {
        $uvbooktypes = $uvinventory["booktypes"];
        $uvvdayvenues = $uvinventory["venues"];
        $uvvenueinfo = $uvvdayvenues[$uvvenuecode];

        $uvitemsarray = urvenue_ws_get_inventory_flatlist($uvinventory);

        if ($uvvenueinfo["locsbysec"] and $uvvenueinfo["secitems"] and $uvvenueinfo["seclocs"] and is_array($uvitemsarray)) {
            $uvmaploclist = urvenue_ws_get_map_loclist($uvvenueinfo["locsbysec"], $uvvenueinfo["secitems"], $uvvenueinfo["seclocs"], $uvitemsarray);

            if ($uvmaploclist)
                $uvdatehasmap = 1;
        }
    }

    return $uvdatehasmap;
}

/*Get Map Locations List
    Requires: uvloclist: locsbysec from inventory venueinfo, uvseclist: secitems from inventory venueinfo, uvseclocs: seclocs from inventory venueinfo, uvitemarray: flat list of items
*/
function urvenue_ws_get_map_loclist($uvloclist, $uvseclist, $uvseclocs, $uvitemarray)
{
    $uvmaploclisthtml = "";
    if (is_array($uvloclist) and is_array($uvseclist)) {
        foreach ($uvseclist as $uvseccode => $uvseclistitem) {
            foreach ($uvloclist as $uvloclistitem) {
                $uvmaplocid = $uvloclistitem["id"];
                $uvmaplocsecname = $uvloclistitem["section"];
                $uvmaplocname = $uvloclistitem["location"];

                if ($uvseccode == $uvmaplocid) {
                    if (is_array($uvseclistitem) and count($uvseclistitem) and $uviteminfo = $uvitemarray[$uvseclistitem[0]]) {
                        $uvitemhighlighthtml = ($uviteminfo["iteminfo"]["highlight"]) ? "<div class='uvhighlight'>" . $uviteminfo["iteminfo"]["highlight"] . "</div>" : "";
                    }

                    $uvmaploclisthtml .= "<div class='uv-map-listsec-itemcont'><a href='javascript:;' class='uv-map-listsec-item uv-map-listsec-item-$uvseccode uvjs-maplist-showsecinfo' data-secid='$uvseccode'><div class='uvsecname'>$uvmaplocsecname</div><i class='icon-right-open-big'></i></a><div class='uv-map-listsec-iteminfo'><div class='uv-map-listsec-iteminfo-inner'>$uvitemhighlighthtml<a class='uv-btn uvjs-map-openpopbysec' href='javascript:;' data-secid='$uvseccode'>Book Now</a></div></div></div>";

                    break;
                }
            }
        }
    }

    return $uvmaploclisthtml;
}


/*General function to calculate an item price
    Requires: iteminfo, guests, duraction, qty
    Optional: qty duration
    Returns: plain prices without currency sign or html formating
*/
function urvenue_ws_calculate_price($uvitem, $uvguests = 1, $uvduration = 0, $uvqty = 1)
{
    $uvbaseprice = $uvitem["baseprice"];
    $uvtierbaseprice = $uvitem["tierbaseprice"];
    $uvtier = $uvitem["currenttier"];
    $uvovercapacity = $uvitem["overcapacity"];
    $uvcapacity = $uvitem["capacity"];
    $uvtierovercapacity = $uvitem["tierovercapacity"];
    $uvovertime = $uvitem["overtime"];
    $uvblockduration = $uvitem["blockduration"];
    $uvdefaultduration = $uvitem["defaultduration"];
    $uvtierovertime = $uvitem["tierovertime"];
    $uvovercapacitytime = $uvitem["overcapacitytime"];
    $uvtierovercapacitytime = $uvitem["tierovercapacitytime"];
    $uvoverguests = 0;
    $uvcalculatedprice = 0;

    //calculate over guests
    if ($uvguests > $uvcapacity)
        $uvoverguests = $uvguests - $uvcapacity;
    else
        $uvoverguests = 0;

    //calculte extratime
    $uvextratime = $uvduration - $uvdefaultduration;

    //calculate overblocks
    $uvoverblocks = ($uvblockduration) ? $uvextratime / $uvblockduration : 0;

    //calculate price no duration
    $uvpricenoduration = $uvbaseprice + $uvtierbaseprice * $uvtier + $uvovercapacity * $uvoverguests + $uvtierovercapacity * $uvoverguests * $uvtier;

    if ($uvduration) {
        //calculate price duration
        $uvpricewithduration = $uvpricenoduration + $uvovertime * $uvoverblocks + $uvtierovertime * $uvoverblocks * $uvtier + $uvovercapacitytime * $uvoverblocks * $uvoverguests + $uvtierovercapacitytime * $uvoverblocks * $uvoverguests * $uvtier;

        //price no duration price should be the minimum price
        if ($uvpricewithduration < $uvpricenoduration)
            $uvpricewithduration = $uvpricenoduration;

        $uvcalculatedprice = $uvpricewithduration;
    } else {
        $uvcalculatedprice = $uvpricenoduration;
    }

    //multiply price by qty
    $uvcalculatedprice = $uvcalculatedprice * $uvqty;

    return $uvcalculatedprice;
}

/*Get money for fontend
    Requires: money: plain price
    Pptional: removenocents: if true removes the cents digits if they are .00
*/
function urvenue_ws_frontformat_money($uvmoney, $uvremovenocents = false)
{
    $uvmoneyprice = urvenue_ws_format_money($uvmoney, 2);

    $uvmoneypricewdec = preg_replace('/\.(\d+)/i', ".<span>$1</span>", $uvmoneyprice);

    if ($uvremovenocents)
        $uvmoneypricewdec = str_replace(".<span>00</span>", "", $uvmoneypricewdec);

    return $uvmoneypricewdec;
}

/*Get monay base format
    Requires: money: plain price
    Optional: decimarls: number of decimals, currencysign: currency sign if needed
*/
function urvenue_ws_format_money($uvmoney, $uvdecimals = 0, $uvcurrencysign = "")
{
    $uvmoneyreturn = "";

    if ($uvmoney) {
        if ($uvdecimals) {
            $uvmoney /= 1;
            $uvmoney = number_format($uvmoney, $uvdecimals);
            $uvmoneyreturn = $uvcurrencysign . $uvmoney;
        } else if ($uvmoney % 1) {
            $uvmoney /= 1;
            $uvmoney = number_format($uvmoney, 2);
            $uvmoneyreturn = $uvcurrencysign . $uvmoney;
        } else {
            $uvmoney /= 1;
            $uvmoney = number_format($uvmoney, 0);
            $uvmoneyreturn = $uvcurrencysign . $uvmoney;
        }
    }

    return $uvmoneyreturn;
}

/*Get Cart
    Requires: cart code, event data array
    Optional: cartfeedresponse
    Returns: Array with cart items
*/
function urvenue_ws_get_cart($uvcartcode, $uveventdata = "", $uvcartfeedresponse = "", $uvaddparams = "")
{
    global $urvenue_ws_core_lib;

    $uvcart = "";

    if ($uvcartcode) {
        if (is_array($urvenue_ws_core_lib) and isset($urvenue_ws_core_lib["system"]["use-cartv2"]) and $urvenue_ws_core_lib["system"]["use-cartv2"]) { //is sidebar check
            $uvcart = urvenue_ws_get_cartv2($uvcartcode, $uvaddparams);
        } else {
            if (!is_array($uvcartfeedresponse))
                $uvfeed = urvenue_ws_get_apiwvar("cart-get", "cartcode=" . $uvcartcode, $uveventdata);
            else
                $uvfeed = $uvcartfeedresponse;

            if (is_array($uvfeed) and $uvfeed["uv"]["success"]["status"] == "success" and is_array($uvfeed["uv"]["data"]["cart"])) {
                $uvdropcarthtml = urvenue_ws_get_dropcarthtml($uvfeed["uv"]["data"]["cart"], $uvfeed["uv"]["data"]["items"], $uvcartcode, $uvfeed["accountvars"]);

                $uvcartmanagementid = ($urvenue_ws_core_lib["inventory"]["manageentlock"]) ? urvenue_ws_get_cart_managentid($uvfeed["uv"]["data"]["cart"]) : "";
                $uvaccountvars = array(
                    "manageentid" => $uvfeed["uv"]["data"]["manageentid"],
                    "providerid" => $uvfeed["uv"]["data"]["providerid"],
                    "resellerid" => $uvfeed["uv"]["data"]["resellerid"],
                );

                $uvcart = array(
                    "cartitems" => $uvfeed["uv"]["data"]["cart"],
                    "items" => $uvfeed["uv"]["data"]["items"],
                    "cartdrophtml" => $uvdropcarthtml,
                    "cartmanagementid" => $uvcartmanagementid,
                    "accountvars" => $uvaccountvars,
                );
            }
        }
    }

    if (!$uvcart) {
        $uvcart = array(
            "cartdrophtml" => urvenue_ws_get_dropcarthtml()
        );
    }

    return $uvcart;
}

/*Get Cart using v2 apis
    Requires: cartcode
    Returns: array to return on proxy calls
*/
function urvenue_ws_get_cartv2($uvcartcode, $uvaddparams = "")
{
    $uvcart = "";

    if ($uvcartcode) {
        $uvparams = array(
            "cartcode" => $uvcartcode,
        );
        if (is_array($uvaddparams))
            $uvparams = array_merge($uvparams, $uvaddparams);

        $uvcartresult = urvenue_ws_get_feed("cartv2-get", $uvparams);
        $uvcheckoutlinks = urvenue_ws_get_bkgcheckout_links($uvcartcode);

        if (is_array($uvcartresult) and $uvcartresult["success"]) {
            $uvcartcount = (isset($uvcartresult["data"]) and isset($uvcartresult["data"]["cart"]) and is_array($uvcartresult["data"]["cart"])) ? count($uvcartresult["data"]["cart"]) : "";

            $uvdropcarthtml = urvenue_ws_get_dropcartv2html($uvcartresult["data"]["cart"], $uvcartresult["data"]["items"], $uvcartcode);
            $uvsummitems = urvenue_ws_get_summitemshtml($uvcartresult["data"]["cart"], $uvcartresult["data"]["items"]);
            $uvallbullets = isset($uvcartresult["data"]["bullets"]) ? $uvcartresult["data"]["bullets"] : "";

            $uvcart = array(
                "cartcount" => $uvcartcount,
                "cartdrophtml" => $uvdropcarthtml,
                "summitemshtml" => $uvsummitems,
                "carttotalshtml" => urvenue_ws_get_cart_totalshtml($uvcartresult["data"]["totals"]),
                "carttotal" => urvenue_ws_get_cart_total($uvcartresult["data"]["totals"]),
                "checkoutlinks" => $uvcheckoutlinks,
                "checkout-carturl" => $uvcheckoutlinks["checkout-carturl"],
                "checkout-checkurl" => $uvcheckoutlinks["checkout-checkurl"],
                "eventscodes" => urvenue_ws_get_cart_eventscodes($uvcartresult["data"]["cart"]),
                "cartinfo" => $uvcartresult["data"]["cart"],
                "carttotals" => $uvcartresult["data"]["totals"],
                "cartitems" => $uvcartresult["data"]["items"],
                "mixedbullets" => urvenue_ws_get_mixedbullets($uvallbullets),
            );
        } else if (is_array($uvcartresult) and !$uvcartresult["success"]) {
            $uvcart = array(
                "cartcount" => 0,
                "message" => $uvcartresult["message"],
                "checkoutlinks" => $uvcheckoutlinks,
                "checkout-carturl" => $uvcheckoutlinks["checkout-carturl"],
                "checkout-checkurl" => $uvcheckoutlinks["checkout-checkurl"],
            );
        }
    }

    return $uvcart;
}

/*Try to recreate cart v2 if it is expired
    Requires: cartcode
    Returns: array with cart info
*/
function urvenue_ws_recreate_cartv2($uvcartcode = "")
{
    global $urvenue_ws_feeds_lib;

    $uvcartinfo = "";

    if ($uvcartcode) {
        $uvreqdata = array(
            "cartcode" => $uvcartcode,
            "recreate" => 1,
        );
        $uvcreatecartendpoint = $urvenue_ws_feeds_lib["cartv2-create"]["url"];
        $uvrequestinfo = urvenue_ws_get_requestinfo();
        $uvreqdata["requestinfo"] = $uvrequestinfo;
        $uvitemdatabuild = http_build_query($uvreqdata, 'flags_');

        $uvwpresponse = wp_remote_post($uvcreatecartendpoint, array(
            'body' => $uvitemdatabuild,
            'timeout' => 60,
        ));
        $uvresultraw = wp_remote_retrieve_body($uvwpresponse);
        $uvresult = json_decode($uvresultraw, true);

        if (is_array($uvresult) and $uvresult["success"]) {
            $uvapicartcode = $uvresult["data"]["cartcode"];

            if ($uvapicartcode and $uvapicartcode != $uvcartcode) {
                $uvcartinfo = urvenue_ws_get_cart($uvapicartcode);

                if (is_array($uvcartinfo)) {
                    $uvcartinfo["oldcartcode"] = $uvcartcode;
                    $uvcartinfo["newcartcode"] = $uvapicartcode;
                }
            }
        }
    }

    return $uvcartinfo;
}

/*Mix terms bullets based on array of different bullets
    Requires: array with venues bullet points
    Returns: plain array with mixed bullets
*/
function urvenue_ws_get_mixedbullets($uvallbullets)
{
    $uvmixedbullets = "";

    if (is_array($uvallbullets)) {
        $uvmixedbullets = array();

        foreach ($uvallbullets as $uvvenuebullets) {
            foreach ($uvvenuebullets as $uvbullet) {
                $uvmixedbullets[] = $uvbullet;
            }
        }

        $uvmixedbullets = array_unique($uvmixedbullets);
        $uvmixedbullets = array_values($uvmixedbullets);
    }

    return $uvmixedbullets;
}

/* Get cart items for summ table in cart v2
    Requires: cartitems(array of cartitems), uvitems(array with inventory items)
    Returns: html for summ table
*/
function urvenue_ws_get_summitemshtml($uvcartitems = "", $uvitems = "")
{
    $uvsummitemshtml = $uvsummitems = $uvsummitem = $uvitemsterms = "";
    $uvcurrencysymbol = "$";
    $uvcurrencycode = "USD";
    $uvdateformat = "M d, Y";

    if (is_array($uvcartitems)) {
        $uvsummitemsgroups = array();

        foreach ($uvcartitems as $uvitemcartcode => $uvcartitem) {
            $uvcartitemddate = gmdate($uvdateformat, strtotime($uvcartitem["caldate"]));
            $uvgrouptitle = $uvcartitemddate . " @ " . $uvcartitem["venuename"];
            $uvitemsymbol = $uvcartitem["currency_symbol"];
            $uvdpricelabel = "Subtotal";
            $uvdprice = urvenue_ws_frontformat_money($uvcartitem["subtotal"], 1); // listprice
            $uvshiftcode = str_replace("SHT", "", $uvcartitem["shiftcode"]);
            $uvdurcode = str_replace("DUR", "", $uvcartitem["durcode"]);
            $uvcartitemdtime = ($uvshiftcode) ? urvenue_ws_get_formattime($uvshiftcode) : "";
            $uvcartitemdduration = ($uvdurcode) ? urvenue_ws_get_formatduration($uvdurcode) : "";
            $uvcartitemarriveby = (is_array($uvitems)) ? $uvitems[$uvcartitem["mastercode"]]["info"]["arriveby"] : "";
            $uvcartitemdarriveby = ($uvcartitemarriveby) ? urvenue_ws_get_formattime($uvcartitemarriveby) : "";

            /* //Test extra item information
            $uvcartitem["locations"] = "C12";
            $uvcartitem["dtime"] = "8:00 PM";
            $uvcartitem["dduration"] = "2 Hours";
            $uvcartitem["darriveby"] = "7:45 PM";
            */

            $uvpaynow = ($uvcartitem["paytype"] == "deposit") ? urvenue_ws_frontformat_money($uvcartitem["paynow"], 1) : "";
            $uvpprice = ($uvpaynow) ? "<span class='uws-event-paynow'><div class='uwspricelabel'>Pay Now</div><div class='uwsprice' data-symbol='$uvitemsymbol'>$uvpaynow</div></span>" : "";

            //additional item info
            $uvlocationinf = ($uvcartitem["locations"]) ? "<div>" . urvenue_ws_get_icon("signpost") . $uvcartitem["locations"] . "</div>" : "";
            $uvguestsinf = ($uvcartitem["qty"] > 1) ? "<div>" . $uvcartitem["qty"] . " x " . urvenue_ws_get_icon("users") . $uvcartitem["guests"] . "</div>" : "<div>" . urvenue_ws_get_icon("users") . $uvcartitem["guests"] . "</div>";
            $uvtimeinf = ($uvcartitemdtime) ? "<div>" . urvenue_ws_get_icon("clock") . $uvcartitemdtime . "</div>" : "";
            $uvdurationinf = ($uvdurcode) ? "<div>" . urvenue_ws_get_icon("hourglass") . $uvdurcode . "</div>" : "";
            $uvarrivebyinf = ($uvcartitemdarriveby) ? "<div>" . urvenue_ws_get_icon("clock") . $uvcartitemdarriveby . "</div>" : "";

            $uvsummitem = "
                <div class='uws-item-subtotal uv-flex uv-align-center uv-just-sb'>
                    <div class='uws-event-subtext'>
                        <div class='uws-ck-itemsgrouptitle'>$uvgrouptitle</div>
                        <div class='uvname'>{$uvcartitem["itemname"]}</div>
                        <div class='uvinfo'>
                            <a href='#show-item-terms-$uvitemcartcode' class='uwsckjs-showitemterm' data-termstitle='{$uvcartitem["itemname"]} Terms' data-itemcartcode='$uvitemcartcode'>Terms</a>
                            {$uvguestsinf}{$uvlocationinf}{$uvtimeinf}{$uvdurationinf}{$uvarrivebyinf}
                        </div>
                    </div>
                    <span class='uws-event-subtotal'>
                        <div class='uwspricelabel'>$uvdpricelabel</div>
                        <div class='uwsprice' data-symbol='$uvitemsymbol'>$uvdprice</div>
                    </span>
                    {$uvpprice}
                </div>
            ";

            $uvitemsterms .= "<div class='uwsck-term uwsck-term-$uvitemcartcode' data-itemcartcode='$uvitemcartcode' data-itemname='{$uvcartitem["itemname"]}' data-itemvenuedate='$uvgrouptitle'>{$uvcartitem["terms"]}</div>";

            if (!isset($uvsummitemsgroups[$uvgrouptitle]))
                $uvsummitemsgroups[$uvgrouptitle] = array(
                    "items" => array(),
                    "grouptitle" => $uvgrouptitle
                );

            $uvsummitemsgroups[$uvgrouptitle]["items"][] = $uvsummitem;
        }

        //the items are grouped by title ({ddate} @ {venuename})
        foreach ($uvsummitemsgroups as $uvsummitemsgroup) {
            $uvgroupitemhtml = "";

            foreach ($uvsummitemsgroup["items"] as $uvgroupitem)
                $uvgroupitemhtml .= $uvgroupitem;

            $uvsummitems .= "
                <div class='uws-ck-itemsgroup'>
                    <div class='uws-ck-itemsgrouptitle'>{$uvsummitemsgroup["grouptitle"]}</div>
                    $uvgroupitemhtml
                </div>
            ";
        }

        $uvsummitemshtml = "
            <div class='uws-ck-itemslist'>
                $uvsummitems
            </div>
            <div class='uwsck-terms'>$uvitemsterms</div>
        ";
    }

    return $uvsummitemshtml;
}

/*Get cart eventcodes
    Requires: cartitems(array of cartitems)
    Returns: array with eventcodes
*/
function urvenue_ws_get_cart_eventscodes($uvcartitems)
{
    $uveventscodes = "";

    if (is_array($uvcartitems)) {
        $uveventscodes = array();

        foreach ($uvcartitems as $uvcartitemcode => $uvcartitem) {
            $uvevecozone = urvenue_ws_standardize_ecozone($uvcartitem["ecozone"]);
            $uvevecozone = str_replace("ECZ", "", $uvevecozone);
            $uvevsdate = gmdate("Ymd", strtotime($uvcartitem["caldate"]));
            $uvevcode = "EVE" . $uvcartitem["venueid"] . $uvevecozone . $uvevsdate;

            if (!in_array($uvevcode, $uveventscodes))
                $uveventscodes[] = $uvevcode;
        }
    }

    return $uveventscodes;
}

/*Get html for cart totals for v2 cart
    Requires: carttotals(array of carttotals)
    Returns: totals html
*/
function urvenue_ws_get_cart_totalshtml($uvcarttotals)
{
    $uvcarttotalshtml = "";

    if (is_array($uvcarttotals) and is_array($uvcarttotals["charge"])) {
        $uvcarttotalitemtml = urvenue_ws_get_template("cartv2/totals-item");

        $uvcarttotals["charge"] = urvenue_ws_order_summtotal_items($uvcarttotals["charge"]);

        foreach ($uvcarttotals["charge"] as $uvcarttotal) {
            $uvprice = urvenue_ws_format_money($uvcarttotal["value"], 2);

            $uvthiscarttotal = str_replace(
                array(
                    "{price}",
                    "{pricelabel}",
                    "{totalcode}",
                    "{currencysymbol}",
                ),
                array(
                    $uvprice,
                    $uvcarttotal["name"],
                    $uvcarttotal["code"],
                    "$"
                ),
                $uvcarttotalitemtml
            );

            $uvcarttotalshtml .= $uvthiscarttotal;
        }
    }

    return $uvcarttotalshtml;
}

/**
 * Orders the cart totals array by moving the "total" element to the end.
 *
 * @param array $uvcarttotals The cart totals array.
 * @return array The ordered cart totals array.
 */
function urvenue_ws_order_summtotal_items($uvcarttotals)
{
    $uvcarttotalsordered = array();

    if (is_array($uvcarttotals)) {
        $uvcarttotalsordered = $uvcarttotals;

        foreach ($uvcarttotals as $uvcarttotal) {
            if ($uvcarttotal["code"] == "total") {
                $uvcarttotalsordered[] = $uvcarttotal;
                unset($uvcarttotalsordered["total"]);
                break;
            }
        }
    }

    return $uvcarttotalsordered;
}

/* Get cart total for cart v2
    Requires: carttotals(array of carttotals)
    Returns: cart total
*/
function urvenue_ws_get_cart_total($uvcarttotals)
{
    $uvcarttotal = 0;

    if (is_array($uvcarttotals) and is_array($uvcarttotals["charge"]) and $uvcarttotals["charge"]["total"]) {
        $uvcarttotal = $uvcarttotals["charge"]["total"]["value"];
    }

    return $uvcarttotal;
}

/*Get Cart managementid
    Requires: cartitems(array of cartitems)
    Returns: cart management id based on local venue library
*/
function urvenue_ws_get_cart_managentid($uvcartitems)
{
    $uvcartmanagementid = "";

    if (is_array($uvcartitems)) {
        $uvcartmanagementid = array();

        foreach ($uvcartitems as $uvcartitem) {
            $uvitemvenueid = $uvcartitem["venueid"];
            $uvitemvenueinfo = urvenue_ws_get_venuelibinfo_byvenuecode("VEN" . $uvitemvenueid);

            if ($uvcartmanagementid and $uvcartmanagementid != $uvitemvenueinfo["manageentid"]) {
                $uvcartmanagementid = "mixed";
                break;
            } else if (is_array($uvitemvenueinfo) and isset($uvitemvenueinfo["manageentid"]))
                $uvcartmanagementid = $uvitemvenueinfo["manageentid"];
        }
    }

    return $uvcartmanagementid;
}

/*Get Cart V2 html for dropdown cart
    Requires: cartitems(array of cartitems), uvitems(array with inventory items), cartcode
*/
function urvenue_ws_get_dropcartv2html($uvcartitems = "", $uvitems = "", $uvcartcode = "")
{
    $uvdropcart = "";
    $uvncartitems = 0;

    if (is_array($uvcartitems)) {
        $uvncartitems = count($uvcartitems);
        $uvcartitems = urvenue_ws_order_cart_items($uvcartitems);
        $uvdropcartconttemp = urvenue_ws_get_template("cartv2/cart-drop-list-container");
        $uvdropcartdatetemp = urvenue_ws_get_template("cartv2/cart-drop-list-date");
        $uvdropcartitemtemp = urvenue_ws_get_template("cartv2/cart-drop-list-item");

        $uvcurgroupcode = $uvgrouphtml = $uvcartdateshtml = $uvcurgroupinfo = "";
        $uvdategroups = array();

        foreach ($uvcartitems as $uvcartitemcode => $uvcartitem) { //Create groups for each cart date/venue
            //if($uvgroupcode != $uvcurgroupcode or ($uvcartitemcode === array_key_last($uvcartitems))){
            $uvgroupcode = (is_array($urvenue_ws_core_lib) and isset($urvenue_ws_core_lib["cart"]) and $urvenue_ws_core_lib["cart"]["list-groups-as-events"] and $uvcartitem["eventcode"]) ? $uvcartitem["eventcode"] : $uvcartitem["caldate"] . "-" . $uvcartitem["venueid"];

            if ($uvcurgroupcode and $uvcurgroupinfo and $uvgroupcode != $uvcurgroupcode) {
                $uvdategroups[] = array(
                    "info" => $uvcurgroupinfo,
                    "html" => $uvgrouphtml
                );
                $uvgrouphtml = "";
                $uvcurgroupinfo = "";
            }

            $uvitem = $uvitems[$uvcartitem["mastercode"]];
            $uvitem = urvenue_ws_inv_normalize_item($uvitem);
            $uvcartitemhtml = urvenue_ws_replace_item_vars($uvitem, $uvdropcartitemtemp);
            $uvcartitem["guests"] = ($uvcartitem["guests"]) ? $uvcartitem["guests"] : 1;
            $uvguestslabel = (isset($uvitem["unitname"]) and $uvitem["unitname"]) ? $uvitem["unitname"] : "guests";
            $uvguests = "<span>" . urvenue_ws_lang($uvguestslabel) . ":</span> " . $uvcartitem["guests"] * $uvcartitem["qty"];
            $uvitemcarthightlight = $uvcartitem["highlight"] ? nl2br($uvcartitem["highlight"]) : "";
            $uvcurrencysymbol = $uvcartitem["currency_symbol"];
            $uvtimeinfodiv = $uvcartitem["time"] ? "<i class='uwsicon-clock'></i> <span>" . urvenue_ws_get_formattime($uvcartitem["time"]) . "</span>" : "";
            $uvdduration = $uvcartitem["duration"] ? urvenue_ws_get_formatduration($uvcartitem["duration"]) : "";
            $uvtimeinfodiv = ($uvdduration) ? $uvtimeinfodiv . " <span>(" . $uvdduration . ")</span>" : $uvtimeinfodiv;
            $uvcartprice = $uvcartitem["subtotal"];
            $uvdcartprice = urvenue_ws_frontformat_money($uvcartprice, 1);
            $uvevecozone = urvenue_ws_standardize_ecozone($uvcartitem["ecozone"]);
            $uvevecozone = str_replace("ECZ", "", $uvevecozone);
            $uvevsdate = gmdate("Ymd", strtotime($uvcartitem["caldate"]));
            $uvevcode = "EVE" . $uvcartitem["venueid"] . $uvevecozone . $uvevsdate;

            $uvcartitemhtml = str_replace(
                array(
                    "{itemcartguests}",
                    "{itemcarttimeinfo}",
                    "{itemcartcode}",
                    "{itemcartprice}",
                    "{cartitemcurrencysymbol}",
                    "{itemcarthighlight}",
                    "{cartcode}",
                ),
                array(
                    $uvguests,
                    $uvtimeinfodiv,
                    $uvcartitem["itemcartcode"],
                    $uvdcartprice,
                    $uvcurrencysymbol,
                    $uvitemcarthightlight,
                    $uvcartcode,
                ),
                $uvcartitemhtml
            );

            $uvgrouphtml .= $uvcartitemhtml;
            $uvcurgroupcode = $uvgroupcode;
            $uvcurgroupinfo = array(
                "date" => $uvcartitem["caldate"],
                "venueid" => $uvcartitem["venueid"],
                "venuename" => $uvcartitem["venuename"],
                "eventcode" => $uvevcode,
            );

            $uvlastkey = end(array_keys($uvcartitems));
            if ($uvcartitemcode === $uvlastkey) {
                $uvdategroups[] = array(
                    "info" => $uvcurgroupinfo,
                    "html" => $uvgrouphtml
                );
            }
        }

        if (is_array($uvdategroups)) {
            foreach ($uvdategroups as $uvdategroup) {
                $uvvenuediv = ($urvenue_ws_core_lib["events"]["global-addvenuename"]) ? "<div class='uwsvenuename'>" . $uvdategroup["info"]["venuename"] . "</div>" : "";
                $uvgroupddate = gmdate($urvenue_ws_core_lib["events"]["global-dateformat"], strtotime($uvdategroup["info"]["date"]));
                $uvgroupddate = urvenue_ws_lang_date($uvgroupddate);

                $uvgroupevent = "";
                if ($uvdategroup["info"]["eventcode"] and (strpos($uvdropcartdatetemp, "{cartevent}") !== false)) {
                    $uveventargs = array(
                        "eventcode" => $uvdategroup["info"]["eventcode"],
                        "template" => "cart/cart-group-event"
                    );

                    ob_start();
                    urvenue_ws_event($uveventargs);
                    $uvgroupevent = ob_get_contents();
                    ob_end_clean();
                }

                $uvdatehtml = str_replace(
                    array(
                        "{cartddate}",
                        "{cartdateitemslist}",
                        "{venuenamediv}",
                        "{cartevent}"
                    ),
                    array(
                        $uvgroupddate,
                        $uvdategroup["html"],
                        $uvvenuediv,
                        $uvgroupevent
                    ),
                    $uvdropcartdatetemp
                );

                $uvcartdateshtml .= $uvdatehtml;
            }
        }

        $uvbkgcheckoutlinks = urvenue_ws_get_bkgcheckout_links($uvcartcode, $uvaccountvars);
        $uvdropcart = str_replace(
            array(
                "{cartdates}",
                "{ncartitems}",
                "{carturl}",
                "{checkouturl}",
            ),
            array(
                $uvcartdateshtml,
                $uvncartitems,
                $uvbkgcheckoutlinks["checkout-carturl"],
                $uvbkgcheckoutlinks["checkout-checkurl"],
            ),
            $uvdropcartconttemp
        );
    } else {
        $uvdropcartnocontenttemp = urvenue_ws_get_template("cart/cart-drop-nocontent");
        $uvdropcart = $uvdropcartnocontenttemp;
    }

    $uvdropcart = urvenue_ws_apply_filters("urvenue_ws_cart_before_show", $uvdropcart);

    return $uvdropcart;
}

/*Get Cart html for dropdown cart
    Requires: cartitems(array of cartitems), uvitems(array with inventory items)
*/
function urvenue_ws_get_dropcarthtml($uvcartitems = "", $uvitems = "", $uvcartcode = "", $uvaccountvars = "")
{
    global $urvenue_ws_core_lib;

    $uvdropcart = "";
    $uvncartitems = 0;

    if (is_array($uvcartitems)) {
        $uvncartitems = count($uvcartitems);
        $uvcartitems = urvenue_ws_order_cart_items($uvcartitems);
        $uvdropcartconttemp = urvenue_ws_get_template("cart/cart-drop-list-container");
        $uvdropcartdatetemp = urvenue_ws_get_template("cart/cart-drop-list-date");
        $uvdropcartitemtemp = urvenue_ws_get_template("cart/cart-drop-list-item");

        $uvcurgroupcode = $uvgrouphtml = $uvcartdateshtml = $uvcurgroupinfo = "";
        $uvdategroups = array();

        foreach ($uvcartitems as $uvcartitemcode => $uvcartitem) { //Create groups for each cart date/venue
            //if($uvgroupcode != $uvcurgroupcode or ($uvcartitemcode === array_key_last($uvcartitems))){
            $uvdropcartitemtempthis = $uvdropcartitemtemp;
            $uvgroupcode = (is_array($urvenue_ws_core_lib) and isset($urvenue_ws_core_lib["cart"]) and $urvenue_ws_core_lib["cart"]["list-groups-as-events"] and $uvcartitem["eventcode"]) ? $uvcartitem["eventcode"] : $uvcartitem["caldate"] . "-" . $uvcartitem["venueid"];

            if ($uvcurgroupcode and $uvcurgroupinfo and $uvgroupcode != $uvcurgroupcode) {
                $uvdategroups[] = array(
                    "info" => $uvcurgroupinfo,
                    "html" => $uvgrouphtml
                );
                $uvgrouphtml = "";
                $uvcurgroupinfo = "";
            }

            //Cart item price
            $uvcartprice = urvenue_ws_calculate_price($uvitem, $uvcartitem["duration"], $uvcartitem["guests"], $uvcartitem["qty"]);
            $uvcartprice = (isset($uvcartitem["pricing"]) and isset($uvcartitem["pricing"]["total"])) ? $uvcartitem["pricing"]["total"] : $uvcartprice;
            $uvdcartprice = urvenue_ws_frontformat_money($uvcartprice, 1);

            if ($uvcartitem["paytype"] == "deposit")
                $uvdropcartitemtempthis = str_replace("{itempicingdiv}", "<div class='uwspricing'>Pay Now</div>", $uvdropcartitemtempthis);

            $uvitem = $uvitems[$uvcartitem["mastercode"]];
            $uvitem = urvenue_ws_inv_normalize_item($uvitem);
            $uvcartitemhtml = urvenue_ws_replace_item_vars($uvitem, $uvdropcartitemtempthis);
            $uvguests = "<span>" . urvenue_ws_lang("guests") . ":</span> " . $uvcartitem["guests"] * $uvcartitem["qty"];
            $uvcurrencysymbol = $uvcartitem["currency_symbol"];
            $uvtimeinfodiv = $uvcartitem["time"] ? "<i class='uwsicon-clock'></i> <span>" . urvenue_ws_get_formattime($uvcartitem["time"]) . "</span>" : "";
            $uvdduration = $uvcartitem["duration"] ? urvenue_ws_get_formatduration($uvcartitem["duration"]) : "";
            $uvtimeinfodiv = ($uvdduration) ? $uvtimeinfodiv . " <span>(" . $uvdduration . ")</span>" : $uvtimeinfodiv;

            $uvcartitemhtml = str_replace(
                array(
                    "{itemcartguests}",
                    "{itemcarttimeinfo}",
                    "{itemcartcode}",
                    "{itemcartprice}",
                    "{cartitemcurrencysymbol}",
                ),
                array(
                    $uvguests,
                    $uvtimeinfodiv,
                    $uvcartitem["itemcartcode"],
                    $uvdcartprice,
                    $uvcurrencysymbol,
                ),
                $uvcartitemhtml
            );

            $uvgrouphtml .= $uvcartitemhtml;
            $uvcurgroupcode = $uvgroupcode;
            $uvcurgroupinfo = array(
                "date" => $uvcartitem["caldate"],
                "venueid" => $uvcartitem["venueid"],
                "venuename" => $uvcartitem["venuename"],
                "eventcode" => $uvcartitem["eventcode"],
            );

            $uvlastkey = end(array_keys($uvcartitems));
            if ($uvcartitemcode === $uvlastkey) {
                $uvdategroups[] = array(
                    "info" => $uvcurgroupinfo,
                    "html" => $uvgrouphtml
                );
            }
        }

        if (is_array($uvdategroups)) {
            foreach ($uvdategroups as $uvdategroup) {
                $uvvenuediv = ($urvenue_ws_core_lib["events"]["global-addvenuename"]) ? "<div class='uwsvenuename'>" . $uvdategroup["info"]["venuename"] . "</div>" : "";
                $uvgroupddate = gmdate($urvenue_ws_core_lib["events"]["global-dateformat"], strtotime($uvdategroup["info"]["date"]));
                $uvgroupddate = urvenue_ws_lang_date($uvgroupddate);

                $uvgroupevent = "";
                if ($uvdategroup["info"]["eventcode"] and (strpos($uvdropcartdatetemp, "{cartevent}") !== false)) {
                    $uveventargs = array(
                        "eventcode" => $uvdategroup["info"]["eventcode"],
                        "template" => "cart/cart-group-event"
                    );

                    ob_start();
                    urvenue_ws_event($uveventargs);
                    $uvgroupevent = ob_get_contents();
                    ob_end_clean();
                }

                $uvdatehtml = str_replace(
                    array(
                        "{cartddate}",
                        "{cartdateitemslist}",
                        "{venuenamediv}",
                        "{cartevent}"
                    ),
                    array(
                        $uvgroupddate,
                        $uvdategroup["html"],
                        $uvvenuediv,
                        $uvgroupevent
                    ),
                    $uvdropcartdatetemp
                );

                $uvcartdateshtml .= $uvdatehtml;
            }
        }

        $uvbkgcheckoutlinks = urvenue_ws_get_bkgcheckout_links($uvcartcode, $uvaccountvars);
        $uvdropcart = str_replace(
            array(
                "{cartdates}",
                "{ncartitems}",
                "{carturl}",
                "{checkouturl}",
            ),
            array(
                $uvcartdateshtml,
                $uvncartitems,
                $uvbkgcheckoutlinks["checkout-carturl"],
                $uvbkgcheckoutlinks["checkout-checkurl"],
            ),
            $uvdropcartconttemp
        );
    } else {
        $uvdropcartnocontenttemp = urvenue_ws_get_template("cart/cart-drop-nocontent");
        $uvdropcart = $uvdropcartnocontenttemp;
    }

    $uvdropcart = urvenue_ws_apply_filters("urvenue_ws_cart_before_show", $uvdropcart);

    return $uvdropcart;
}

/*Order cart items
    Requires: cartitems(array of cartitems)
    Returns: Elements ordered by 1:itemdate 2:itemvenue
*/
function urvenue_ws_order_cart_items($uvcartitems)
{
    if (is_array($uvcartitems)) {
        uasort($uvcartitems, "urvenue_ws_sorthelp_venuename");
        uasort($uvcartitems, "urvenue_ws_sorthelp_caldate");
    }

    return $uvcartitems;
}
//sort helpers
function urvenue_ws_sorthelp_venuename($a, $b)
{
    return $a["venuename"] > $b["venuename"];
}
function urvenue_ws_sorthelp_caldate($a, $b)
{
    return $a["caldate"] > $b["caldate"];
}

/*Prints Inventory Item Header (Image Header + title)
    Returns: Prints html of header inventory item page (gets varible from url)
*/
function urvenue_ws_item_header()
{
    global $urvenue_ws_core_lib;

    $uvitemhaderhtml = "";
    $uvmastercode = urvenue_ws_get_itempagemastercode();

    if ($uvmastercode) {
        //$uvexperiences = uws_get_dummyapi("experiences");
        //$uvitem = $uvexperiences["items"][$uvmastercode];
        $uvitem = urvenue_ws_get_invitem($uvmastercode);
        $uviteminfo = $uvitem["info"];

        if (is_array($uviteminfo)) {
            $uvitemheadertemplate = urvenue_ws_get_template("inventory/inventory-item-page-header");
            $uviteminfo["banner"] = "/wp-content/plugins/wp-urvenue-webservices/uvcore/assets/images/external/banner.png";

            if ($uviteminfo["banner"]) { //Only if item has banner
                $uvitemhaderhtml = str_replace(
                    array(
                        "{itemname}",
                        "{itembanner}",
                    ),
                    array(
                        $uviteminfo["itemname"],
                        $uviteminfo["banner"],
                    ),
                    $uvitemheadertemplate
                );
            }
        }
    }

    echo wp_kses_post( $uvitemhaderhtml );
}

/*Get Inventory Item
    Requires: mastercode(mastercode of the item)
    Returns: Main inventory item object
*/
function urvenue_ws_get_invitem($uvmastercode)
{
    global $urvenue_ws_core_lib;

    $uvinvitem = "";

    $uvfeedtoken = "mastercode=" . $uvmastercode;

    if (isset($urvenue_ws_core_lib["system"]) and isset($urvenue_ws_core_lib["system"]["filter-marketplace"]) and $urvenue_ws_core_lib["system"]["filter-marketplace"]) {
        $uvfeedtoken .= "&filters=data:marketplace";
    }

    $uvinventoryfeed = urvenue_ws_get_feed("inventoryitem", $uvfeedtoken);

    if (is_array($uvinventoryfeed) and $uvinventoryfeed["uv"]["success"]["status"] == "success") {
        $uvinvitem = $uvinventoryfeed["uv"]["data"];

        if ($uvinvitem["info"] and is_array($uvinvitem["info"]["paytypes"])) {
            $uvinvitem["info"]["basepaytypes"] = $uvinvitem["info"]["paytypes"];
            $uvinvitem["info"]["hasinquire"] = (in_array("inquire", $uvinvitem["info"]["paytypes"])) ? 1 : 0;
            $uvinvitem["info"]["paytypes"] = array_diff($uvinvitem["info"]["paytypes"], array("inquire"));
            $uvinvitem["info"]["paytypes"] = array_values($uvinvitem["info"]["paytypes"]);

            if (is_array($uvinvitem["library"]["pricings"])) {
                foreach ($uvinvitem["library"]["pricings"] as $uvpricingkey => $uvpricing) {
                    $uvinvitem["library"]["pricings"][$uvpricingkey] = urvenue_ws_lang($uvpricing);
                }
            }
        }
    }

    return $uvinvitem;
}

/*Print Inventory Item Page
    Returns: Prints html of inventory item page (gets varible from url)
*/
function urvenue_ws_item_page()
{
    global $urvenue_ws_core_lib;

    $uvinvitempagehtml = "";

    $uvmastercode = urvenue_ws_get_itempagemastercode();

    if ($uvmastercode) {
        //$uvexperiences = uws_get_dummyapi("experiences");
        //$uvitem = $uvexperiences["items"][$uvmastercode];
        $uvitem = urvenue_ws_get_invitem($uvmastercode);
        $uviteminfo = $uvitem["info"];

        if (is_array($uviteminfo)) {
            //Add missing vars --- need to be added to the API
            $uviteminfo["category"] = "Item Category";
            $uviteminfo["activityduration"] = "2 Hours";
            $uviteminfo["timerange"] = "6:30pm - 8:30pm";
            $uviteminfo["included"] = array("Hour at the Campfire", "Wood", "Kindling", "Fire starter");
            $uviteminfo["bring"] = array("Comfortable Shoes", "Light sweater or ajcket", "Extra cash for photos and souvenirs");

            $uvinvitempagetemplate = urvenue_ws_get_template("inventory/inventory-item-page");
            $uvinvitempagehtml = urvenue_ws_replace_item_vars($uviteminfo, $uvinvitempagetemplate);
        }

        /*$uvinvitemproxiesscript = uws_get_proxies_script("inventoryitempop");
        $uvinvitempagehtml .= $uvinvitemproxiesscript;*/
    }

    echo wp_kses_post( $uvinvitempagehtml );
}

/*Get inventory item popup
    Requires: uvitem(inventory item data array)
    Returns: html of the item popup
*/
function urvenue_ws_get_itempop($uvitem, $uvargs = "")
{
    $uvitempop = "";

    if (is_array($uvitem)) {
        $uvtemplatename = urvenue_ws_get_arg($uvargs, "template", "inventory/inventory-item-pop");
        $uvtemplate = urvenue_ws_get_template($uvtemplatename);
        $uvitempop = urvenue_ws_replace_item_vars($uvitem, $uvtemplate);
    }

    return $uvitempop;
}

/*Get mastercode from url vars on item page
    Returns: mastercode
*/
function urvenue_ws_get_itempagemastercode()
{
    $uvmastercode = "";

    if (urvenue_ws_is_wordpress())
        $uvmastercode = get_query_var('mastercode');

    return $uvmastercode;
}

/*Get Account Vars, checking different sources
    Requires: Event Data
    Returns: account vars(array)
*/
function urvenue_ws_get_account_vars($uveventdata)
{
    global $urvenue_ws_focemanageentid;

    $uvaccountvars = "";

    if ($urvenue_ws_focemanageentid)
        $uvaccountvars = array(
            "manageentid" => $urvenue_ws_focemanageentid,
            "providerid" => $urvenue_ws_focemanageentid,
            "resellerid" => $urvenue_ws_focemanageentid,
        );

    if (!is_array($uvaccountvars) and is_array($uveventdata) and isset($uveventdata["manageentid"]) and $uveventdata["manageentid"])
        $uvaccountvars = array(
            "manageentid" => $uveventdata["manageentid"],
            "providerid" => $uveventdata["manageentid"],
            "resellerid" => $uveventdata["manageentid"],
        );

    if (!is_array($uvaccountvars) and is_array($uveventdata) and isset($uveventdata["venuecode"]) and $uveventdata["venuecode"] and !$uvvenuelibinfo)
        $uvaccountvars = urvenue_ws_get_venuelibinfo_byvenuecode($uveventdata["venuecode"]);

    if (!is_array($uvaccountvars) and is_array($uveventdata) and isset($uveventdata["eventcode"]) and $uveventdata["eventcode"]) {
        $uvvenuefeedinfo = urvenue_ws_get_venueinfo_by_eventcode($uveventdata["eventcode"]);

        if (is_array($uvvenuefeedinfo) and $uvvenuefeedinfo["info"] and $uvvenuefeedinfo["info"]["manageentid"]) {
            $uvaccountvars = array(
                "manageentid" => $uvvenuefeedinfo["info"]["manageentid"],
                "providerid" => $uvvenuefeedinfo["info"]["manageentid"],
                "resellerid" => $uvvenuefeedinfo["info"]["manageentid"],
            );
        }
    }

    if (!is_array($uvaccountvars))
        $uvaccountvars = urvenue_ws_get_primary_venue();

    return $uvaccountvars;
}

/*Get inventory api, replace account vars
    Requires: libfeedcode, feedtoken, eventdata: array with date, venuecode, ecozone
*/
function urvenue_ws_get_apiwvar($uvlibfeedcode, $uvfeedtoken, $uveventdata = "")
{
    global $urvenue_ws_feeds_lib, $urvenue_ws_core_lib, $urvenue_ws_focemanageentid;

    $uvfeed = $uvvenuelibinfo = "";

    $uvfeedurl = $urvenue_ws_feeds_lib[$uvlibfeedcode]["url"];
    $uvfeedexpiration = $urvenue_ws_feeds_lib[$uvlibfeedcode]["expiration"];

    if ($urvenue_ws_focemanageentid)
        $uvvenuelibinfo = array(
            "manageentid" => $urvenue_ws_focemanageentid,
            "providerid" => $urvenue_ws_focemanageentid,
            "resellerid" => $urvenue_ws_focemanageentid,
        );
    else if (is_array($uveventdata) and isset($uveventdata["manageentid"]) and $uveventdata["manageentid"])
        $uvvenuelibinfo = array(
            "manageentid" => $uveventdata["manageentid"],
            "providerid" => $uveventdata["manageentid"],
            "resellerid" => $uveventdata["manageentid"],
        );

    if (is_array($uveventdata) and isset($uveventdata["venuecode"]) and $uveventdata["venuecode"] and !$uvvenuelibinfo)
        $uvvenuelibinfo = urvenue_ws_get_venuelibinfo_byvenuecode($uveventdata["venuecode"]);

    if (!is_array($uvvenuelibinfo) and is_array($uveventdata) and isset($uveventdata["eventcode"]) and $uveventdata["eventcode"]) {
        $uvvenuefeedinfo = urvenue_ws_get_venueinfo_by_eventcode($uveventdata["eventcode"]);

        if (is_array($uvvenuefeedinfo) and $uvvenuefeedinfo["info"] and $uvvenuefeedinfo["info"]["manageentid"]) {
            $uvvenuelibinfo = array(
                "manageentid" => $uvvenuefeedinfo["info"]["manageentid"],
                "providerid" => $uvvenuefeedinfo["info"]["manageentid"],
                "resellerid" => $uvvenuefeedinfo["info"]["manageentid"],
            );
        }
    }

    if (!is_array($uvvenuelibinfo))
        $uvvenuelibinfo = urvenue_ws_get_primary_venue();

    if (!is_array($uvvenuelibinfo) and is_array($uveventdata) and $uveventdata["managementid"])
        $uvvenuelibinfo = array(
            "manageentid" => $uveventdata["managementid"],
            "providerid" => $uveventdata["managementid"],
            "resellerid" => $uveventdata["managementid"],
        );


    if ($uvfeedurl and is_array($uvvenuelibinfo)) {
        $uvfeedurl = str_replace(
            array(
                "{manageentid}",
                "{providerid}",
                "{resellerid}",
                "{params}",
            ),
            array(
                $uvvenuelibinfo["manageentid"],
                $uvvenuelibinfo["providerid"],
                $uvvenuelibinfo["resellerid"],
                $uvfeedtoken,
            ),
            $uvfeedurl
        );

        $uvfeed = urvenue_ws_get_feed($uvfeedurl, $uvfeedexpiration);

        //Add account vars
        if (is_array($uvfeed)) {
            $uvfeed["accountvars"] = array(
                "manageentid" => $uvvenuelibinfo["manageentid"],
                "providerid" => $uvvenuelibinfo["providerid"],
                "resellerid" => $uvvenuelibinfo["resellerid"],
            );
        }
    }

    return $uvfeed;
}

/*Get api url, replace account vars
    Requires: libfeedcode, eventdata: array with date, venuecode, ecozone
*/
function urvenue_ws_get_apiwvarurl($uvlibfeedcode, $uveventdata = "")
{
    global $urvenue_ws_feeds_lib, $urvenue_ws_core_lib, $urvenue_ws_focemanageentid;

    $uvfeedurl = $uvvenuelibinfo = "";

    $uvfeedurl = $urvenue_ws_feeds_lib[$uvlibfeedcode]["url"];
    $uvfeedexpiration = $urvenue_ws_feeds_lib[$uvlibfeedcode]["expiration"];

    if ($urvenue_ws_focemanageentid)
        $uvvenuelibinfo = array(
            "manageentid" => $urvenue_ws_focemanageentid,
            "providerid" => $urvenue_ws_focemanageentid,
            "resellerid" => $urvenue_ws_focemanageentid,
        );
    else if (is_array($uveventdata) and isset($uveventdata["manageentid"]) and $uveventdata["manageentid"])
        $uvvenuelibinfo = array(
            "manageentid" => $uveventdata["manageentid"],
            "providerid" => $uveventdata["manageentid"],
            "resellerid" => $uveventdata["manageentid"],
        );

    if (is_array($uveventdata) and isset($uveventdata["venuecode"]) and $uveventdata["venuecode"] and !$uvvenuelibinfo)
        $uvvenuelibinfo = urvenue_ws_get_venuelibinfo_byvenuecode($uveventdata["venuecode"]);

    if (!is_array($uvvenuelibinfo) and is_array($uveventdata) and isset($uveventdata["eventcode"]) and $uveventdata["eventcode"]) {
        $uvvenuefeedinfo = urvenue_ws_get_venueinfo_by_eventcode($uveventdata["eventcode"]);

        if (is_array($uvvenuefeedinfo) and $uvvenuefeedinfo["info"] and $uvvenuefeedinfo["info"]["manageentid"]) {
            $uvvenuelibinfo = array(
                "manageentid" => $uvvenuefeedinfo["info"]["manageentid"],
                "providerid" => $uvvenuefeedinfo["info"]["manageentid"],
                "resellerid" => $uvvenuefeedinfo["info"]["manageentid"],
            );
        }
    }

    if (!is_array($uvvenuelibinfo))
        $uvvenuelibinfo = urvenue_ws_get_primary_venue();

    if (!is_array($uvvenuelibinfo) and is_array($uveventdata) and $uveventdata["managementid"])
        $uvvenuelibinfo = array(
            "manageentid" => $uveventdata["managementid"],
            "providerid" => $uveventdata["managementid"],
            "resellerid" => $uveventdata["managementid"],
        );

    if ($uvfeedurl and is_array($uvvenuelibinfo)) {
        $uvfeedurl = str_replace(
            array(
                "{manageentid}",
                "{providerid}",
                "{resellerid}",
                "{params}",
            ),
            array(
                $uvvenuelibinfo["manageentid"],
                $uvvenuelibinfo["providerid"],
                $uvvenuelibinfo["resellerid"],
                "",
            ),
            $uvfeedurl
        );
    }

    return $uvfeedurl;
}

/*Get booketing checkout links
    Required: cartcode
    Optional: accountvars
*/
function urvenue_ws_get_bkgcheckout_links($uvcartcode, $uvaccountvars = "")
{
    global $urvenue_ws_actionlinks_lib, $urvenue_ws_core_lib, $urvenue_ws_sourceloc, $urvenue_ws_sourcecode;

    if (!is_array($uvaccountvars)) //get main venue vars if no account vars
        $uvaccountvars = urvenue_ws_get_primary_venue();

    $uvcheckoutlinks = array();

    $uvcheckouttype = (isset($urvenue_ws_core_lib["system"]["checkouttype"])) ? $urvenue_ws_core_lib["system"]["checkouttype"] : "microsite";

    if (is_array($uvaccountvars) and is_array($urvenue_ws_actionlinks_lib)) {
        $uvsourceloc = $urvenue_ws_sourceloc;
        if (isset($urvenue_ws_core_lib["manageentidinfomap"]) and isset($urvenue_ws_core_lib["manageentidinfomap"][$uvaccountvars["manageentid"]]) and isset($urvenue_ws_core_lib["manageentidinfomap"][$uvaccountvars["manageentid"]]["sourceloc"]))
            $uvsourceloc = $urvenue_ws_core_lib["manageentidinfomap"][$uvaccountvars["manageentid"]]["sourceloc"];

        foreach ($urvenue_ws_actionlinks_lib[$uvcheckouttype] as $uvactlinkkey => $uvactlink) {
            $uvcheckoutlinks[$uvactlinkkey] = str_replace(
                array(
                    "{cartcode}",
                    "{manageentid}",
                    "{resellerid}",
                    "{providerid}",
                    "{sourceloc}",
                    "{sourcecode}"
                ),
                array(
                    $uvcartcode,
                    $uvaccountvars["manageentid"],
                    $uvaccountvars["resellerid"],
                    $uvaccountvars["providerid"],
                    $uvsourceloc,
                    $urvenue_ws_sourcecode
                ),
                $uvactlink
            );
        }
    } else if (is_array($urvenue_ws_actionlinks_lib)) {
        $uvsourceloc = $urvenue_ws_sourceloc;
        $uvsourcecode = $urvenue_ws_sourcecode;

        foreach ($urvenue_ws_actionlinks_lib[$uvcheckouttype] as $uvactlinkkey => $uvactlink) {
            $uvcheckoutlinks[$uvactlinkkey] = str_replace(
                array(
                    "{cartcode}",
                    "{sourceloc}",
                    "{sourcecode}"
                ),
                array(
                    $uvcartcode,
                    $uvsourceloc,
                    $uvsourcecode
                ),
                $uvactlink
            );
        }
    }

    return $uvcheckoutlinks;
}

/*Replate inventory item variables codes
    Requires: item(inventory item array), template(html template with varriable codes)
*/
function urvenue_ws_replace_item_vars($uvitem, $uvtemplate)
{
    global $urvenue_ws_core_lib, $urvenue_ws_today, $urvenue_ws_config_menu;

    $uvinvitem = "";

    if (is_array($uvitem) and isset($uvitem["info"]) and is_array($uvitem["info"]))
        $uvitem = urvenue_ws_normalize_item_to_replace($uvitem);

    if (is_array($uvitem)) {
        $uvitemincludedlist = (isset($uvitem["included"])) ? urvenue_ws_get_optionslist($uvitem["included"], "", "", 1) : "";
        $uvitembringlist = (isset($uvitem["bring"])) ? urvenue_ws_get_optionslist($uvitem["bring"], "", "", 1) : "";
        $uvcreditstype = (isset($uvitem["globaltype"]) and $uvitem["globaltype"] == "opentable") ? "uv+ot" : "";
        $uvcredits = urvenue_ws_get_uwscredits($uvcreditstype);
        $uvmaxdate = gmdate("Y-m-d", strtotime($urvenue_ws_today . " +4 months"));
        $uvddate = gmdate($urvenue_ws_core_lib["inventory"]["global-dateformat"], strtotime($uvitem["caldate"]));
        $uvcapacitylabel = ($uvitem["capacity"] > 1) ? urvenue_ws_lang("guests") : urvenue_ws_lang("guest");
        $uvitemguestsel = urvenue_ws_get_item_guests_select($uvitem);
        $uvitemprimaryaddonsel = urvenue_ws_get_item_primaddon($uvitem);
        $uvitemtimesel = urvenue_ws_get_item_timesel($uvitem);
        $uvshowdigitalmenu = (isset($urvenue_ws_core_lib["events"]["addon-bottles"]["showdigitalmenu"]) && $urvenue_ws_core_lib["events"]["addon-bottles"]["showdigitalmenu"]) 
            ? $urvenue_ws_core_lib["events"]["addon-bottles"]["showdigitalmenu"] 
            : false;
        $uvitembottlesel = ($urvenue_ws_config_menu || $uvshowdigitalmenu) ? urvenue_ws_get_item_bottlesel($uvitem) : "";
        $uvitemactbtns = urvenue_ws_get_item_actionsbtns($uvitem);
        $uvitemaddons = urvenue_ws_get_item_addons($uvitem);
        $uvitemdurationsel = urvenue_ws_get_item_durationsel($uvitem);
        $uvitempricingdiv = (isset($uvitem["pricing"]) and $uvitem["pricing"]) ? "<div class='uwspricing'>" . urvenue_ws_lang($uvitem["pricing"]) . "</div>" : "";
        $uvitemdbaseprice = (isset($uvitem["baseprice"])) ? urvenue_ws_frontformat_money($uvitem["baseprice"], 1) : "";
        $uvitemname = (isset($uvitem["itemname"])) ? $uvitem["itemname"] : $uvitem["name"];
        $uvitemprice = (isset($uvitem["price"])) ? $uvitem["price"] : $uvitem["listprice"];
        $uvitemdcapacity = (isset($uvitem["capacity"])) ? $uvitem["capacity"] . " Guests" : "";
        $uvitemdcapacity = ($uvitemdcapacity == "1 Guests") ? "1 Guest" : $uvitemdcapacity;
        $uvdstartdtime = ($uvitem["startuvtime"]) ? urvenue_ws_get_formattime($uvitem["startuvtime"]) : "";
        $uvdenddtime = ($uvitem["enduvtime"]) ? urvenue_ws_get_formattime($uvitem["enduvtime"]) : "";
        $uvdtimerange = ($uvdstartdtime) ? $uvdstartdtime : "";
        $uvdtimerange = ($uvdstartdtime and $uvdenddtime) ? $uvdstartdtime . " - " . $uvdenddtime : $uvdtimerange;
        $uvinfoiconlink = ($uvitem["itemdescr"]) ? "<a href='javascript:;' class='uwsjs-inv-item-showinfo' data-mastercode='" . $uvitem["mastercode"] . "' aria-label='View More Info'><i class='uwsicon-info-circled'></i> <span>" . urvenue_ws_lang("monre-info") . "</span></a>" : "";
        $uvdtimerange = ($uvitem["arriveby"]) ? urvenue_ws_lang("arriveby") . " " . urvenue_ws_get_formattime($uvitem["arriveby"]) : $uvdtimerange;
        $uviteminlineinfo = ($urvenue_ws_core_lib["inventory"]["showiteminfoinline"]) ? urvenue_ws_get_item_fullinfohtml($uvitem) : "<div class='uwshighlight'>" . $uvitem["highlight"] . "</div>";
        //$uvpoptheme = ($urvenue_ws_core_lib["ui"]["uipoptheme"]) ? "uws-" . $urvenue_ws_core_lib["ui"]["uipoptheme"] : "uws-light";
        $uvpoptheme = urvenue_ws_get_popup_theme();

        $uvinvitem = str_replace(
            array(
                "{mastercode}",
                "{itemcategory}",
                "{itemname}",
                "{frontprice}",
                "{currencysymbol}",
                "{itembasedprice}",
                "{itempicingdiv}",
                "{actduration}",
                "{itemhighlight}",
                "{itemdescription}",
                "{itemincludedlist}",
                "{itembringlist}",
                "{uwscredits}",
                "{itemdate}",
                "{itemmaxdate}",
                "{itemddate}",
                "{itemcapacity}",
                "{itemcapacitylabel}",
                "{itemtimerange}",
                "{itemguestsel}",
                "{itemreqaddonsel}",
                "{itemtimesel}",
                "{itemactionbuttons}",
                "{itemaddons}",
                "{itemdurationsel}",
                "{itemdcapacity}",
                "{iteminfoicon}",
                "{iteminlineinfo}",
                "{poptheme}",
                "{itembottlesel}",
            ),
            array(
                $uvitem["mastercode"],
                $uvitem["category"],
                $uvitemname,
                urvenue_ws_frontformat_money($uvitemprice, 1),
                $uvitem["currenty_symbol"],
                $uvitemdbaseprice,
                $uvitempricingdiv,
                "",
                $uvitem["highlight"],
                $uvitem["itemdescr"],
                $uvitemincludedlist,
                $uvitembringlist,
                $uvcredits,
                $urvenue_ws_today,
                $uvmaxdate,
                urvenue_ws_lang_date($uvddate),
                $uvitem["capacity"],
                $uvcapacitylabel,
                $uvdtimerange,
                $uvitemguestsel,
                $uvitemprimaryaddonsel,
                $uvitemtimesel,
                $uvitemactbtns,
                $uvitemaddons,
                $uvitemdurationsel,
                $uvitemdcapacity,
                $uvinfoiconlink,
                $uviteminlineinfo,
                $uvpoptheme,
                $uvitembottlesel,
            ),
            $uvtemplate
        );

        //Conditional replaces
        if ($uvitembringlist)
            $uvinvitem = str_replace(array("{ifwhattobring}", "{/ifwhattobring}"), array("", ""), $uvinvitem);
        else
            $uvinvitem = preg_replace('/{ifwhattobring}(.|\n)*{\/ifwhattobring}/', '', $uvinvitem);

        if (isset($uvitem["complimentary"]) and $uvitem["complimentary"])
            $uvinvitem = str_replace(array("{ifcomplimentary}", "{/ifcomplimentary}"), array("", ""), $uvinvitem);
        else
            $uvinvitem = preg_replace('/{ifcomplimentary}(.|\n)*{\/ifcomplimentary}/', '', $uvinvitem);
    }

    return $uvinvitem;
}

/*Get item info html
    Requires: uvitem(inventory item array from inventoryitem api)
    Returns: html with image, hightlight and description
*/
function urvenue_ws_get_item_fullinfohtml($uvitem)
{
    $uviteminfohtml = $uvitemtourlinkhtml = "";

    if (is_array($uvitem)) {
        $uvmaxtotalofwords = 25;
        $uvitemname = $uvitem["itemname"];
        $uvitemhighlight = $uvitemdescription = "";
        $uvitemimageurl = (is_array($uvitem["images"])) ? reset($uvitem["images"]) : "";
        $uvitemimageurl = (is_array($uvitemimageurl)) ? reset($uvitemimageurl) : $uvitemimageurl;
        $uvitemimageurl = (is_array($uvitemimageurl)) ? $uvitemimageurl["folder"] . "/500SC0/" . $uvitemimageurl["file"] : "";
        $uvitemtourlink = (isset($uvitem["tourlink"]) and $uvitem["tourlink"]) ? $uvitem["tourlink"] : "";
        $uvitemtourlinkclass = "uwsjs-inv-item-showtourlink uwstourlink";

        $uvitemdescr = (isset($uvitem["itemdescr"])) ? $uvitem["itemdescr"] : $uvitem["descr"];
        $uvitemreadmore = "...<a href='javascript:;' class='uwsjs-inv-item-showinfo' data-mastercode='" . $uvitem["mastercode"] . "'>Read More</a>";

        if ($uvitemdescr && $uvitem["highlight"]) {
            $uvitemhighlight = (urvenue_ws_count_words_without_html($uvitem["highlight"]) > $uvmaxtotalofwords) ? urvenue_ws_trim_words_with_html($uvitem["highlight"], $uvmaxtotalofwords, $uvitemreadmore) : $uvitem["highlight"] . $uvitemreadmore;
        } else if ($uvitemdescr && !$uvitem["highlight"]) {
            $uvitemdescription = (urvenue_ws_count_words_without_html($uvitemdescr) > $uvmaxtotalofwords) ? urvenue_ws_trim_words_with_html($uvitemdescr, $uvmaxtotalofwords, $uvitemreadmore) : $uvitemdescr;
        }
        // } else if(uws_count_words_without_html($uvitem["highlight"]) > $uvmaxtotalofwords){
        //     $uvitemhighlight = uws_trim_words_with_html($uvitem["highlight"], $uvmaxtotalofwords, "...<a href='javascript:;' class='uwsjs-inv-item-showinfo' data-mastercode='" . $uvitem["mastercode"] . "'>Read More</a>");
        // }
        // else if(uws_count_words_without_html($uvitem["highlight"]) + uws_count_words_without_html($uvitemdescr) > $uvmaxtotalofwords){
        //     $uvitemhighlight = $uvitem["highlight"];
        //     $uvitemdescription = uws_trim_words_with_html($uvitemdescr, $uvmaxtotalofwords - uws_count_words_without_html($uvitem["highlight"]), " ...<a href='javascript:;' class='uwsjs-inv-item-showinfo' data-mastercode='" . $uvitem["mastercode"] . "'>Read More</a>");
        // }
        // else{
        //     $uvitemhighlight = $uvitem["highlight"];
        //     $uvitemdescription = $uvitemdescr;
        // }

        if ($uvitemtourlink and !$uvitemimageurl) {
            $uvinlinetour = urvenue_ws_get_icon('inlinetour');
            $uvitemtourlinkhtml = "<a href='javascript:;' class='$uvitemtourlinkclass' data-view='$uvitemtourlink'>
                                        $uvinlinetour
                                    </a>";
        } else if ($uvitemtourlink and $uvitemimageurl) {
            $uvfullviewicon = urvenue_ws_get_icon('fullview');
            $uvtouricon = urvenue_ws_get_icon('tour');
            $uvitemtourlinkhtml = "<div class='uwsitemtourlink'>
                                        <a href='$uvitemimageurl' class='uwsjs-show-image uwsshowfullimg' data-pop-title='$uvitemname'>
                                            <span class='uwsviewfullbtn'>
                                                $uvfullviewicon
                                            </span>
                                        </a>
                                        <a href='javascript:;' class='$uvitemtourlinkclass' data-view='$uvitemtourlink'>
                                            $uvtouricon
                                        </a>
                                    </div>";
        }

        $uviteminfohtml = "<div class='uwsiteminlineinfo'>";
        $uviteminfohtml = ($uvitemimageurl) ? $uviteminfohtml . "<div class='uwsimage'><img class='uwsimgloading' src='$uvitemimageurl' alt='" . $uvitem["itemname"] . "' onload=\"this.classList.add('uwsloaded')\">$uvitemtourlinkhtml</div>" : $uviteminfohtml;
        $uviteminfohtml .= "<div class='uwshighlight'>$uvitemhighlight</div>";
        $uviteminfohtml .= "<div class='uwsdescription'>$uvitemdescription</div>";
        $uviteminfohtml .= ($uvitemtourlink and !$uvitemimageurl) ? $uvitemtourlinkhtml : "";
        $uviteminfohtml .= "</div>";
    }

    return $uviteminfohtml;
}

/**
 * Counts the number of words in a string without considering HTML tags.
 *
 * @param string $uvstring The input string to count words from.
 * @return int The number of words in the input string.
 */
function urvenue_ws_count_words_without_html($uvstring)
{
    // $uvstring = strip_tags($uvstring);
    $uvstring = wp_strip_all_tags($uvstring);
    $uvstring = preg_replace('/\s+/', ' ', $uvstring);
    $uvstring = trim($uvstring);
    $uvwords = explode(" ", $uvstring);
    $uvnwords = count($uvwords);

    return $uvnwords;
}

/*Proccess item array   
    Requires: uvitem(inventory item array from inventoryitem api)
    Return: array ready to replace vars
*/
function urvenue_ws_normalize_item_to_replace($uvitem)
{
    $uvitemreturn = "";

    if (is_array($uvitem)) {
        $uvitemreturn = $uvitem["info"];
        $uvitemreturn["header"] = $uvitem["header"];
        $uvitemreturn["library"] = $uvitem["library"];
        $uvitemreturn["shifts"] = $uvitem["shifts"];
        $uvitemreturn["elements"] = $uvitem["elements"];
        $uvitemreturn["slots"] = $uvitem["slots"];

        //Remove 0 if in array
        $uvqtys = $uvitemreturn["elements"][$uvitemreturn["masteritemcode"]]["qtys"];
        if (is_array($uvqtys) and count($uvqtys) > 1) {
            $uvqtys = array_filter($uvqtys, function ($value) {
                return $value !== 0;
            });

            $uvqtys = array_values($uvqtys);
            $uvitemreturn["elements"][$uvitemreturn["masteritemcode"]]["qtys"] = $uvqtys;
        }

        //$uvitemreturn["pricing"] = ($uvitemreturn["pricing"]) ? uws_lang($uvitemreturn["pricing"]) : "";

        //Add placeholders for now
        if (isset($_REQUEST["apireq"]) && sanitize_text_field( wp_unslash( $_REQUEST["apireq"] ) )) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only flag controlling placeholder data, no state change
            $uvitemreturn["primaryaddon"] = array(
                "itemname" => "Gingerbread Kits",
                "min" => 1,
                "max" => 4
            );
            $uvitemreturn["complimentary"] = 1;
            $uvitemreturn["addons"] = array(
                "ADN1" => array(
                    "id" => "ADN1",
                    "itemname" => "Smores Kit",
                    "min" => 1,
                    "max" => 4,
                    "listprice" => 10
                ),
                "ADN2" => array(
                    "id" => "ADN2",
                    "itemname" => "Campagne Bottle & Bucket",
                    "min" => 1,
                    "max" => 4,
                    "listprice" => 5
                )
            );
        }
    }

    return $uvitemreturn;
}

/*Get deration selection html
    Requires: uvitem(inventory item array)
    Returns: html with duration selector
*/
function urvenue_ws_get_item_durationsel($uvitem)
{
    $uvitemdurationsel = "";

    if (is_array($uvitem) and isset($uvitem["header"]["timemode"]) and $uvitem["header"]["timemode"] == "TimeDuration" and is_array($uvitem["shifts"]["SHT0"]["start_times"])) {
        $uvtimeslots = "";
        $uvdfirsttime = "";
        $uvdfirstltime = "";
        $uvslots = $uvitem["shifts"]["SHT0"]["start_times"];

        //Remove last element, it should not be an slot
        //$uvlastslotkeys = array_keys($uvslots);
        //$uvlastslotkey = end($uvlastslotkeys);
        //unset($uvslots[$uvlastslotkey]);
        //array_pop($uvslots);
        //array_pop($uvslots);

        $uvnslots = count($uvslots);
        $uvfrequency = $uvitem["shifts"]["SHT0"]["frequency"] / 1;

        //Frequency is wrong need to calculate ourselves
        $uvstarttimeskeys = array_keys($uvslots);
        $uvfrequency = urvenue_ws_get_timebetween($uvstarttimeskeys[0], $uvstarttimeskeys[1]);

        // $uvdefaultduration = str_replace("DUR", "", $uvitem["shifts"]["SHT0"]["default_duration"]) / 1;
        $uvdefaultduration = (strpos($uvitem["shifts"]["SHT0"]["default_duration"], "DUR") === 0) ? str_replace("DUR", "", $uvitem["shifts"]["SHT0"]["default_duration"]) : str_replace("D", "", $uvitem["shifts"]["SHT0"]["default_duration"]);
        $uvdefaultduration = $uvdefaultduration / 1;
        $uvdefaultduration = ($uvdefaultduration < $uvfrequency) ? $uvfrequency : $uvdefaultduration;
        $uvinitslots = $uvdefaultduration / $uvfrequency;
        $uvslotsstrtoolong = $uvslotswithmins = 0;

        foreach ($uvslots as $uvtime => $uvdtime) {
            //$uvdtime = str_replace(":00", "", uws_get_formattime($uvtime["time"]));
            $uvdtime = str_replace(":00", "", $uvdtime);
            $uvthistimeslots = (isset($uvitem["slots"]["SHT" . $uvtime])) ? $uvitem["slots"]["SHT" . $uvtime] : -1;
            $uvslotclass = ($uvthistimeslots == -1) ? "uwsunavailabletimeslot" : "";

            $uvtimeslots .= "<div class='uwstimeslot uwstimeslot-$uvtime $uvslotclass' data-time='$uvtime'><span>$uvdtime</span></div>";

            if (substr($uvtime, -2) !== "00")
                $uvslotswithmins++;

            if (!$uvdfirsttime)
                $uvdfirsttime = urvenue_ws_get_formattime($uvtime);
            else if (!$uvdfirstltime)
                $uvdfirstltime = urvenue_ws_get_formattime($uvtime);
        }

        $uvlastreftime = urvenue_ws_add_minutestotime($uvtime, $uvfrequency);
        $uvlastrefdtime = str_replace(":00", "", urvenue_ws_get_formattime($uvlastreftime));
        $uvlastrefdtime = str_replace("After Midnight", "", $uvlastrefdtime);

        $uvslotsstrtoolong = ($uvslotswithmins > 6) ? 1 : 0;
        $uvdurcontclass = (($uvnslots > 10) or $uvslotsstrtoolong) ? "uwsdurationhidehours" : "";

        $uvitemdurationsel = "
            <div class='uws-item-durationsel-cont $uvdurcontclass' data-initslots='$uvinitslots' data-nslots='$uvnslots' data-frequency='$uvfrequency'>
                <div class='uwsdurationinfo'>
                    <div class='uwslabel'>Duration</div>
                    <div class='uwsinfo'>
                        <i class='uwsicon-clock-1'></i>
                        <div class='uwsdduration uwsdy-dduration'></div>
                        <div class='uwstimerange uwsdy-ddurationrange'>$uvdfirsttime - $uvdfirstltime</div>
                    </div>
                </div>
                <div class='uws-duration-timesline'>
                    <div class='uwstimeline'>
                        $uvtimeslots
                    </div>
                    <span class='uwslastreftime'>$uvlastrefdtime</span>
                </div>
                <div class='uwsdurationerror uwsdy-duration-error'></div>
            </div>
        ";
    }

    return $uvitemdurationsel;
}

/*Get time between two times
    Requires: uvtime1, uvtime2 (both uvtimes)
    Returns: minutes between values
*/
function urvenue_ws_get_timebetween($uvtime1 = "", $uvtime2 = "")
{
    $uvminutes = 0;

    if ($uvtime1 and $uvtime2) {
        $uvhours1 = intval(substr($uvtime1, 1, 2));
        $uvminutes1 = intval(substr($uvtime1, 3, 2));
        $uvtotminutes1 = ($uvhours1 * 60) + $uvminutes1;

        $uvhours2 = intval(substr($uvtime2, 1, 2));
        $uvminutes2 = intval(substr($uvtime2, 3, 2));
        $uvtotminutes2 = ($uvhours2 * 60) + $uvminutes2;

        $uvminutes = $uvtotminutes2 - $uvtotminutes1;
    }

    return $uvminutes;
}

/*Get html for addons selection
    Requires: item(inventory item array)
    Returns: addons dropdowns
*/
function urvenue_ws_get_item_addons($uvitem)
{
    $uvaddons = "";

    if (is_array($uvitem) and isset($uvitem["addons"]) and is_array($uvitem["addons"])) {
        $uvaddonslist = "";
        $uvcontainertemplate = urvenue_ws_get_template("inventory/inventory-item-list-addons-container");
        $uvaddonitemtemplate = urvenue_ws_get_template("inventory/inventory-item-list-addon");

        foreach ($uvitem["addons"] as $uvaddon) {
            $uvaddonqtysel = urvenue_ws_get_addon_qtysel($uvaddon);

            $uvaddonitem = str_replace(
                array(
                    "{addonid}",
                    "{addonname}",
                    "{addonqtysel}"
                ),
                array(
                    $uvaddon["id"],
                    $uvaddon["itemname"],
                    $uvaddonqtysel
                ),
                $uvaddonitemtemplate
            );

            $uvaddonslist .= $uvaddonitem;
        }

        $uvaddons = str_replace("{itemaddonslist}", $uvaddonslist, $uvcontainertemplate);
    }

    return $uvaddons;
}

/*Get html of addon qty selector
    Requires: addon(inventory addon array)
*/
function urvenue_ws_get_addon_qtysel($uvaddon)
{
    $uvaddonqtysel = "";

    if (is_array($uvaddon)) {
        $uvlabel = "Add +<div class='uwsprice'>" . urvenue_ws_frontformat_money($uvaddon["listprice"]) . "</div>";

        $uvargs = array(
            "label" => $uvlabel,
            "min" => "0",
            "max" => $uvaddon["max"],
            "value" => "0",
            "name" => "addon-" . $uvaddon["id"],
            "id" => "addon-" . $uvaddon["id"],
        );

        $uvaddonqtysel = urvenue_ws_get_numselbox($uvargs);
    }

    return $uvaddonqtysel;
}

/*Get html for guest selector for an item
    Requires: item(inventory item array)
    Returns: html with the guest selector
*/
function urvenue_ws_get_item_guests_select($uvitem)
{
    $uvguestsel = "";

    if (is_array($uvitem) and isset($uvitem["elements"])) {
        $uvmax = $uvitem["capacity"];
        $uvmin = $uvitem["minqty"];
        $uvminusclass = ($uvmax <= $uvmin) ? "uwsdisabled" : "";
        $uvmaxclass = ($uvmax >= $uvmax) ? "uwsdisabled" : "";
        $uvvalue = $uvitem["elements"][$uvitem["masteritemcode"]]["header"]["qtydefault"];
        $uvqtys = $uvitem["elements"][$uvitem["masteritemcode"]]["qtys"];
        $uvmaxqty = ($uvqtys) ? max($uvqtys) : $uvmax;
        $uvinputclass = "uwsjs-inv-updateguests";

        //Values if globaltype is virtual
        $uvvirtualitem = $uvitem["globaltype"] == "virtual";
        $uvallowinputedit = 0; //$uvvirtualitem ? 1 : 0;
        $uvremoveplusless = 0; //$uvvirtualitem ? 1 : 0;
        $uvqtys = $uvvirtualitem ? "" : $uvqtys;
        $uvmax = $uvvirtualitem ? $uvmaxqty : "";
        $uvmin = $uvvirtualitem ? $uvvalue : "";
        $uvinputclass .= $uvvirtualitem ? " uws-virtual-input" : "";

        $uvargs = array(
            "label" => urvenue_ws_lang("guests"),
            "icon" => "uwsicon-user-1",
            "min" => $uvmin,
            "max" => $uvmax,
            "values" => $uvqtys,
            "value" => $uvvalue,
            "name" => "guests",
            "id" => "uwsguestsel-" . $uvitem["mastercode"],
            "class" => $uvinputclass,
            "allowinputedit" => $uvallowinputedit,
            "removeplusless" => $uvremoveplusless
        );

        $uvguestsel = urvenue_ws_get_numselbox($uvargs);
    }

    return $uvguestsel;
}

/*Get html of Primary addon selector for an item
    Requires: item(inventory item array)
*/
function urvenue_ws_get_item_primaddon($uvitem)
{
    $uvselhtml = "";

    if (is_array($uvitem) and isset($uvitem["primaryaddon"]) and is_array($uvitem["primaryaddon"])) {
        $uvargs = array(
            "label" => $uvitem["primaryaddon"]["itemname"],
            "min" => $uvitem["primaryaddon"]["min"],
            "max" => $uvitem["primaryaddon"]["max"],
            "value" => $uvitem["primaryaddon"]["min"],
            "name" => "primaryaddon",
            "id" => "primaryaddon-" . $uvitem["mastercode"],
        );

        $uvselhtml = urvenue_ws_get_numselbox($uvargs);
        $uvselhtml = "<div class='uwsapi-missing-req' data-apimr-title='Not in API' data-apimr-descr='inventoryitem API does NOT have anything related to this type of addons'>" . $uvselhtml . "</div>";
    }

    return $uvselhtml;
}

/*Get time slot selection box for an inventory item
    Requires: item(inventory item array)
    added class to recognize selectable times
*/
function urvenue_ws_get_item_timesel($uvitem) {
    $uvtimeselectionbox = "";

    if (is_array($uvitem) and isset($uvitem["header"]) and $uvitem["header"]["timemode"] == "TimeSlot" and isset($uvitem["shifts"]["SHT0"]["all_times"]) and is_array($uvitem["shifts"]["SHT0"]["all_times"])) {
        $uvtimeslist = "";

        foreach ($uvitem["shifts"]["SHT0"]["all_times"] as $uvtime => $uvdtime) {

            $uvtimeslist .= "<li><button class='uwsjs-item-update-time' aria-label='Select $uvdtime' type='button' data-time='$uvtime'>$uvdtime</button></li>";
        }

        if ($uvitem["globaltype"] == "opentable") {
            $uvtimeselectionbox = "
                <button class='uwsitemselbtn uwstimeselector uwsjs-show-otselect'>
                    <div class='uwslabel'><i class='uwsicon-clock-1'></i> <span>Time</span></div>
                    <span class='uwsdy-otdtime uws-selbtn uws-selectable'>Select Time</span>
                </button>
            ";
        } else {
            $uvtimeselectionbox = "
                <button class='uwsitemselbtn uwstimeselector uwsjs-show-timeselect'>
                    <div class='uwslabel'><i class='uwsicon-clock-1'></i> <span>Time</span></div>
                    <span class='uwsdy-otdtime uws-selbtn uws-selectable'>Select Time</span>
                </button>
            ";
            /*$uvtimeselectionbox = "
            <div class='uws-dropdown-cont'>
                <a href='#uws-opentimeselection' class='uwsjs-trigger-dropdown' aria-label='Select Time'><i class='uwsicon-clock-1'></i> <span class='uwsdy-dropvalue'>Select Time</span></a>
                <div class='uws-dropdown'>
                    <ul>$uvtimeslist</ul>
                </div>
            </div>
            ";*/
        }
    }
    else if(is_array($uvitem) and isset($uvitem["header"]) and $uvitem["header"]["timemode"] == "SingleTime" && $uvitem["vendor"] != "book4time"){
        $uvseltime = $uvitem["shifts"]["SHT0"]["single_time"]["starttime"];
        $uvseldtime = urvenue_ws_get_formattime($uvseltime);

        $uvtimeselectionbox = "
            <button class='uwsitemselbtn uwstimeselector uwsblocked'>
                <div class='uwslabel'><i class='uwsicon-clock-1'></i> <span>Time</span></div>
                <span class='uwsdy-otdtime uws-selbtn'>$uvseldtime</span>
            </button>
        ";
    }
    else if(is_array($uvitem) and isset($uvitem["vendor"]) and $uvitem["vendor"] == "book4time"){
        $uvtimeselectionbox = "
            <button class='uwsitemselbtn uwstimeselector uwsjs-show-bk4select'>
                <div class='uwslabel'><i class='uwsicon-clock-1'></i> <span>Time</span></div>
                <span class='uwsdy-otdtime uws-selbtn'>Select Time</span>
            </button>
        ";
    }

    return $uvtimeselectionbox;
}

/*Get book4time times from api*/
function urvenue_ws_get_itembk4sel($uvargs = ""){
    $uvbk4timesel = "";

    $uvvenuecode = urvenue_ws_get_arg($uvargs, "venuecode", "");
    $uvdate = urvenue_ws_get_arg($uvargs, "date", "");
    $uvmastercode = urvenue_ws_get_arg($uvargs, "mastercode", "");
    $uvextdata = urvenue_ws_get_arg($uvargs, "extdata", "");

    $uvvenueid = str_replace("VEN", "", $uvvenuecode);

    $uvapiparams = "venueid=$uvvenueid&caldate=$uvdate&mastercode=$uvmastercode&ext_datajson=" . urlencode($uvextdata);
    $uvbk4times = urvenue_ws_get_feed("bk4-itemtimes", $uvapiparams);

    if(is_array($uvbk4times) and $uvbk4times["uv"]["success"]["status"] == "success"){
        $uvbk4timesslots = (isset($uvbk4times["uv"]["data"]["slots"])) ? $uvbk4times["uv"]["data"]["slots"] : "";

        if(is_array($uvbk4timesslots)){
            $uvafmidnight = 0;
            $uvbk4timeslist = "";

            foreach($uvbk4timesslots as $uvbk4timeslot){
                $uvtime = $uvbk4timeslot["starttime"];
                $uvdtime = urvenue_ws_get_formattime($uvtime, 1);
                $uvduration = $uvbk4timeslot["duration"];
                $uvtechnician = $uvbk4timeslot["technician"];
                $uvprice = $uvbk4timeslot["price"];
                $uvdduration = urvenue_ws_get_formatduration($uvduration);
                $uvdprice = urvenue_ws_frontformat_money($uvprice, 1);
                $uvdduration = ($uvdduration) ? "($uvdduration)" : $uvdduration;

                if(!$uvafmidnight and $uvdtime["aftermidnight"]){
                    $uvbk4timeslist .= "<li class='uwsaftermidnight'>After Midnight</li>";
                    $uvafmidnight = 1;
                }
                $uvdtime = $uvdtime["dtime"];
                $uvbk4timeslotjson = wp_json_encode($uvbk4timeslot);

                $uvbk4timeslist .= "<li><a class='uwsjs-selectbk4time' href='#selectbk4time-$uvtime' data-time='$uvtime' data-dtime='$uvdtime' data-bk4data='$uvbk4timeslotjson'><div class='uvtimemaininfo'><span>$uvdtime</span><span class='uvdduration' >$uvdduration</span></div><div class='uwsprice' data-symbol='$'>$uvdprice</div><span class='uvtechnician'>$uvtechnician</span></a></li>";
            }

            $uvbk4credits = urvenue_ws_get_uwscredits("bk4");
            if($uvbk4timeslist){
                $uvbk4timesel = "
                    <div class='uwsselscreenbody'>
                        <div class='uwslabel'><i class='uwsicon-clock-1'></i> Select Time</div>
                        <ul class='uwsottimeslist uvisbk4timeslist'>
                            $uvbk4timeslist
                        </ul>
                        $uvbk4credits
                    </div>
                    <div class='uwsselscreenfooter'>
                        <button class='uws-btn uws-btn-s uwsjs-viewshowmain'>Cancel</button>
                    </div>
                ";
            }
        }
    }
    
    if(!$uvbk4timesel){
        $uvbk4timesel = "
            <div class='uwsselscreenbody'>
                <div class='uwsnocontent'>No times available</div>
            </div>
            <div class='uwsselscreenfooter'>
                <button class='uws-btn uws-btn-s uwsjs-viewshowmain'>Cancel</button>
            </div>
        ";
    }

    return $uvbk4timesel;
}

// Bottle Selection
function urvenue_ws_get_item_bottlesel($uvitem)
{
    global $urvenue_ws_config_menu_requiresumm, $urvenue_ws_core_lib;

    $uvbottleselectionbox = "";
    $uvbottleicon = "wine-bottle";
    $uvbottleicon = urvenue_ws_get_dummyapi("icons/" . $uvbottleicon, "svg");
    $uvcurrencysymbol = (isset($uvitem["currency_symbol"]) and $uvitem["currency_symbol"]) ? $uvitem["currency_symbol"] : "$";
    $uvshowsummary = (isset($urvenue_ws_core_lib["events"]["addon-bottles"]["showsummary"]) && $urvenue_ws_core_lib["events"]["addon-bottles"]["showsummary"]) 
        ? $urvenue_ws_core_lib["events"]["addon-bottles"]["showsummary"] 
        : false;

    $uvbottletotals = ($urvenue_ws_config_menu_requiresumm || $uvshowsummary) ? "<span class='uws-bottle-total'>Total <span class='uwsprice uwsdy-bottlestotal' data-symbol='$uvcurrencysymbol'></span></span>" : "";

    $uvbottleselectionbox = "
        <button class='uwsitemselbtn uwsbottleselector uwsjs-show-bottleselect'>
            <div class='uwslabel'>$uvbottleicon <span>Bottle</span></div>
            <span class='uwsdy-bottle uws-selbtn'>Select Bottle</span>
            <div class='uws-bottle-selection'>
                <span class='uws-bottle-text'></span>
                $uvbottletotals
            </div>
        </button>
    ";

    return $uvbottleselectionbox;
}

function urvenue_ws_get_itembottlesel($uvargs = "")
{
    global $urvenue_ws_config_menu_requiresumm, $urvenue_ws_core_lib;

    $uvbottlesel = $uvbottleicon = "";
    $uvbottledata = array();

    $uvvenueid = urvenue_ws_get_arg($uvargs, "venueid", "");
    $uvminspend = urvenue_ws_get_arg($uvargs, "minspend", 0);
    $uvcurrencysymbol = urvenue_ws_get_arg($uvargs, "currencysymbol", "$");

    $uvapiparams = "venueid=$uvvenueid";
    $uvbottles = urvenue_ws_get_feed("digital-menu", $uvapiparams);
    $uvshowsummary = (isset($urvenue_ws_core_lib["events"]["addon-bottles"]["showsummary"]) && $urvenue_ws_core_lib["events"]["addon-bottles"]["showsummary"]) 
        ? $urvenue_ws_core_lib["events"]["addon-bottles"]["showsummary"] 
        : false;

    if (is_array($uvbottles) and $uvbottles["uv"]["success"]["status"] == "success") {
        $uvmenuitems = $uvbottles["uv"]["data"]["menu"];
        $uvrootitem = "";
        $uvmenusbypapa = array();
        $uvthemenuitems = array();
        $uvbottlesel = "";

        if (is_array($uvmenuitems)) {
            foreach ($uvmenuitems as $uvmenuitem) {
                $uvmenuitemtype = $uvmenuitem["type"];

                if ($uvmenuitemtype == "root") {
                    $uvrootitem = $uvmenuitem;
                }

                $uvmenusbypapa["papa-" . $uvmenuitem["papaid"]][] = $uvmenuitem;
                $uvthemenuitems["MI" . $uvmenuitem["id"]] = $uvmenuitem;
                $uvthemenuitems["MI" . $uvmenuitem["id"]]["pricenum"] = $uvmenuitem["price"] / 1;
            }

            $uvpoptitle = urvenue_ws_lang("selectbottle");
            $uvbottleicon = "wine-bottle";
            $uvbottleicon = urvenue_ws_get_dummyapi("icons/" . $uvbottleicon, "svg");

            $uvrootid = $uvrootitem["id"];
            $uvbottlessummclass = ($urvenue_ws_config_menu_requiresumm || $uvshowsummary) ? "uwsdy-bottlessumm" : "";
            $uvbottlessummreqclass = ($urvenue_ws_config_menu_requiresumm || $uvshowsummary) ? "uwsdisabled" : "";
            $uvbottlesel = "<div class='uwsselscreenbody $uvbottlessummclass'>
                                <div class='uwslabel'>
                                    $uvbottleicon
                                    $uvpoptitle
                                </div>";

            $uvbottleslist = urvenue_ws_get_menuitems_plainlist_html($uvmenusbypapa, $uvrootid, $uvvenueid, $uvcurrencysymbol);
            $uvbottlessumm = ($urvenue_ws_config_menu_requiresumm || $uvshowsummary) ? "<div class='uwsdy-bottleselinfo'>
                        <div class='uwsdy-bottleselinfo-inner'>
                            <div class='uwslabel'>" . urvenue_ws_lang("Minimum Spend") . "</div>
                            <div class='uwsprice uwsdy-globalminspend' data-symbol='$uvcurrencysymbol' data-minspend='$uvminspend'>$uvminspend</div>
                        </div>
                        <div class='uwsdy-bottleselinfo-inner'>
                            <div class='uwslabel'>" . urvenue_ws_lang("total") . "</div>
                            <div class='uwsprice uwsdy-bottlestotal uwslowerror' data-symbol='$uvcurrencysymbol'>0</div>
                        </div>
                    </div>" : "";

            $uvbottlesel = $uvbottlesel . "<div class='uws-inventory-list uws-bottles-list'>" . $uvbottleslist . "</div></div>";

            $uvbottlesel .= "
                <div class='uwsselscreenfooter'>
                    $uvbottlessumm
                    <div class='uwsactions'>
                        <a href='javascript:;' class='uws-btn uws-btn-p uwsjs-additembottles $uvbottlessummreqclass'>" . urvenue_ws_lang("continue") . "</a>
                        <button class='uws-btn uws-btn-s uwsjs-viewshowmain'>Cancel</button>
                    </div>
                </div>
            ";
        }
    }

    if (!$uvbottlesel) {
        $uvbottlesel = "
            <div class='uwsselscreenbody'>
                <div class='uwsnocontent'>No bottles available</div>
            </div>
            <div class='uwsselscreenfooter'>
                <button class='uws-btn uws-btn-s uwsjs-viewshowmain'>Cancel</button>
            </div>
        ";
    }

    $uvbottledata = array(
        "html" => $uvbottlesel,
        "menubottles" => $uvthemenuitems,
    );

    return $uvbottledata;
}

function urvenue_ws_get_menuitems_plainlist_html($uvitemsbypapa, $uvpapaid, $uvvenueid, $uvcurrencysymbol)
{

    $uvmenulist = $uvbottleprice = "";

    if (is_array($uvitemsbypapa) and $uvitemsbypapa["papa-" . $uvpapaid]) {
        foreach ($uvitemsbypapa["papa-" . $uvpapaid] as $uvmenuitem) {
            if ($uvmenuitem["type"] == "container") {
                $uvinnermenulist = urvenue_ws_get_menuitems_plainlist_html($uvitemsbypapa, $uvmenuitem["id"], $uvvenueid, $uvcurrencysymbol);
                $uvinnermenuname = $uvmenuitem["name"];

                $uvmenulist .= "
                    <div class='uws-booktype uws-booktype-item uws-bottlelist-item'>
                        <a href='javascript:;' class='uwsjs-booktypetoggle'>
                            <span>
                                $uvinnermenuname
                                <span class='uwscartcount'>0</span>
                            </span>
                            <i class='uwsicon-right-open'></i>
                        </a>
                        <div class='uws-bootypelist-body'>
                            <div class='uws-bootypelist-inner'>
                                <div class='uws-invitems-list'>
                                    $uvinnermenulist
                                </div>
                            </div>
                        </div>
                    </div>
                ";
            } else {
                $uvselnumber = 10;

                $uvselecthtml = "<select class='uwsbottlessel uwsjs-updatebottle'>";
                for ($i = 0; $i <= $uvselnumber; $i++)
                    $uvselecthtml .= "<option value='$i'>$i</option>";
                $uvselecthtml .= "</select>";
                $uvfrontprice = urvenue_ws_frontformat_money($uvmenuitem["price"], 1);
                $uvbottleprice =  ($uvfrontprice > 0) ? "<div class='uwsprice' data-symbol='$uvcurrencysymbol'>$uvfrontprice</div>" : "";

                $uvdescr = $uvmenuitem["descr"];
                $uvmenulist .= "
                    <div class='uws-inventory-item' data-itemid='" . $uvmenuitem["id"] . "'>
                        <i class='uwsicon-shop'></i>
                        <div class='uwsselections'>
                            $uvselecthtml
                        </div>
                        <div class='uwsinfo'>
                            <div class='uwsname'>" . $uvmenuitem["name"] . "</div>
                        </div>
                        $uvbottleprice
                    </div>
                ";
            }
        }
    }

    return $uvmenulist;
}

function urvenue_ws_get_itemotsel($uvargs = "")
{
    $uvottimessel = "";

    $uvotdata = urvenue_ws_get_arg($uvargs, "otdata", "");
    $uvdate = urvenue_ws_get_arg($uvargs, "date", "");
    $uvguests = urvenue_ws_get_arg($uvargs, "guests", "");
    $uvmastercode = urvenue_ws_get_arg($uvargs, "mastercode", "");

    $uvotdatacompt = explode("|", $uvotdata);
    $uvotrid = str_replace("otid:", "", $uvotdatacompt[0]);
    $uvotattr = str_replace("resatt:", "", $uvotdatacompt[1]);

    $uvapiparams = "caldate=$uvdate&guests=$uvguests&rid=$uvotrid&attribute=$uvotattr&mastercode=$uvmastercode";
    $uvottimes = urvenue_ws_get_feed("ot-itemtimes", $uvapiparams);

    if (is_array($uvottimes) and $uvottimes["uv"]["success"]["status"] == "success") {
        $uvotthetimes = (isset($uvottimes["uv"]["data"]["times"])) ? $uvottimes["uv"]["data"]["times"] : "";
        $uvottimesdet = (isset($uvottimes["uv"]["data"]["times_detailed"])) ? $uvottimes["uv"]["data"]["times_detailed"] : "";
        $uvottimereqcc = $uvottimes["uv"]["data"]["credit_card_required"];

        if (is_array($uvotthetimes) or is_array($uvottimesdet)) {
            $uvafmidnight = 0;
            $uvottimeslist = "";

            if (is_array($uvottimesdet)) {
                foreach ($uvottimesdet as $uvdettime) {
                    if (!$uvdettime["overbook"]) {
                        $uvtime = $uvdettime["time"];
                        $uvdtime = urvenue_ws_get_formattime($uvtime, 1);

                        if (!$uvafmidnight and $uvdtime["aftermidnight"]) {
                            $uvottimeslist .= "<li class='uwsaftermidnight'>After Midnight</li>";
                            $uvafmidnight = 1;
                        }

                        $uvaddotstock = ($uvdettime["stock"]) ? "<span class='uvtimestock'>Stock: " . $uvdettime["stock"] . "</span>" : "";

                        $uvottimeslist .= "<li><a class='uwsjs-selectottime' href='#selectottime-$uvtime' data-time='$uvtime' data-dtime='" . $uvdtime["dtime"] . "' data-type='" . $uvdettime["type"] . "' data-category='" . $uvdettime["category"] . "'><span>" . $uvdtime["dtime"] . "</span>$uvaddotstock</a></li>";
                    }
                }
            } else {
                foreach ($uvotthetimes as $uvtime) {
                    $uvdtime = urvenue_ws_get_formattime($uvtime, 1);

                    if (!$uvafmidnight and $uvdtime["aftermidnight"]) {
                        $uvottimeslist .= "<li class='uwsaftermidnight'>After Midnight</li>";
                        $uvafmidnight = 1;
                    }

                    if (!$uvottimereqcc[$uvtime])
                        $uvottimeslist .= "<li><a class='uwsjs-selectottime' href='#selectottime-$uvtime' data-time='$uvtime' data-dtime='" . $uvdtime["dtime"] . "'><span>" . $uvdtime["dtime"] . "</span></a></li>";
                }
            }



            $uvotcredits = urvenue_ws_get_uwscredits("ot");
            if ($uvottimeslist)
                $uvottimessel = "
                    <div class='uwsselscreenbody'>
                        <div class='uwslabel'><i class='uwsicon-clock-1'></i> Select Time</div>
                        <ul class='uwsottimeslist'>
                            $uvottimeslist
                        </ul>
                        $uvotcredits
                    </div>
                    <div class='uwsselscreenfooter'>
                        <button class='uws-btn uws-btn-s uwsjs-viewshowmain'>Cancel</button>
                    </div>
                ";
        }
    }

    if (!$uvottimessel) {
        $uvottimessel = "
            <div class='uwsselscreenbody'>
                <div class='uwsnocontent'>No times available</div>
            </div>
            <div class='uwsselscreenfooter'>
                <button class='uws-btn uws-btn-s uwsjs-viewshowmain'>Cancel</button>
            </div>
        ";
    }

    return $uvottimessel;
}

/*Get inventory item action buttons
    Requires: item(inventory item array)
    Returns: html of the action buttons for an inventory item
*/
function urvenue_ws_get_item_actionsbtns($uvitem)
{
    global $urvenue_ws_config_menu;

    $uvitembtns = $uvitemcancelbutton = "";

    if (is_array($uvitem)) {
        $uvitemaddcalcprice = "";

        //$uvitemaddcalcprice = "<span class='uwsitemcalcprice uwsdy-addtocart-price'></span>";
        $uvitemaddinqbutton = ($uvitem["hasinquire"]) ? "<button class='uws-btn uws-btn-s uwsjs-item-inquire'><span>" . urvenue_ws_lang("inquire") . "</span></button>" : "";

        $uvitembtns = "<div class='uwsactions uwsdisabled'>";

        if (is_array($uvitem["paytypes"]) and count($uvitem["paytypes"]) > 0)
            $uvitembtns .= "
                <button class='uws-btn uws-btn-p uwsjs-item-addtocart-andcheck'><span>" . urvenue_ws_lang("checkout") . "</span>$uvitemaddcalcprice</button>
                <button class='uws-btn uws-btn-s uwsjs-item-addtocart'><span>" . urvenue_ws_lang("add-to-cart") . "</span>$uvitemaddcalcprice</button>
            ";

        /*if (is_array($uvitem) and isset($uvitem["header"]["timemode"]) and $uvitem["header"]["timemode"] == "TimeDuration" and is_array($uvitem["shifts"]["SHT0"]["all_times"]))
            // $uvitemcancelbutton .= "<button class='uws-btn uws-btn-inl uwsjs-closepop-force'><span>" . uws_lang("cancel") . "</span></button>";*/
            $uvitemcancelbutton .= "<button class='uws-btn uws-btn-inl uwsjs-closepop-force'><span>" . urvenue_ws_lang("cancel") . "</span></button>";

        $uvitembtns .= "
                $uvitemaddinqbutton
                $uvitemcancelbutton
            </div>
        ";
    }

    return $uvitembtns;
}

/*Get number selector box
    Requires: args: name, icon, min, max, value, name, id
    Returns: html with box number selector
*/
function urvenue_ws_get_numselbox($uvargs)
{
    global $urvenue_ws_config_guestsdropdown;

    $uvselbox = $uvselboxitem = "";

    if (is_array($uvargs)) {
        $uvlabel = urvenue_ws_get_arg($uvargs, "label");
        $uvicon = urvenue_ws_get_arg($uvargs, "icon");
        $uvmin = urvenue_ws_get_arg($uvargs, "min");
        $uvmax = urvenue_ws_get_arg($uvargs, "max");
        $uvvalues = urvenue_ws_get_arg($uvargs, "values");
        $uvvalue = urvenue_ws_get_arg($uvargs, "value");
        $uvname = urvenue_ws_get_arg($uvargs, "name");
        $uvinpid = urvenue_ws_get_arg($uvargs, "id");
        $uvclass = urvenue_ws_get_arg($uvargs, "class");
        $uvallowinputedit = urvenue_ws_get_arg($uvargs, "allowinputedit");
        $uvremoveplusless = urvenue_ws_get_arg($uvargs, "removeplusless");

        //In case there are no min and max but we have values array
        $uvvaluesstrattr = "";
        if (is_array($uvvalues)) {
            if (!$uvmin)
                $uvmin = $uvvalues[0];
            if (!$uvmax)
                $uvmax = $uvvalues[count($uvvalues) - 1];

            $uvvaluesstrattr = "data-values='" . implode(",", $uvvalues) . "'";
        }

        $uvvalue = (!$uvvalue) ? "0" : $uvvalue;
        $uvmin = (!$uvmin) ? "0" : $uvmin;
        $uvminusclass = ($uvvalue <= $uvmin) ? "uwsdisabled" : "";
        $uvmaxclass = ($uvvalue >= $uvmax) ? "uwsdisabled" : "";
        $uviconhtml = ($uvicon) ? "<i class='$uvicon'></i> " : "";
        $uvboxclass = ($uvallowinputedit) ? "uwsallowinputedit" : "";
        $uvboxclass .= ($uvremoveplusless) ? " uwsremoveplusless" : "";
        $uvaddreadonly = ($uvallowinputedit) ? "" : "readonly";

        // Use dropdown selector
        if ($urvenue_ws_config_guestsdropdown) {
            $uvdropdownoptions = "";
            
            // If values array is provided, use those specific values
            if (is_array($uvvalues)) {
                foreach ($uvvalues as $uvoptionvalue) {
                    $uvselected = ($uvoptionvalue == $uvvalue) ? "selected" : "";
                    $uvdropdownoptions .= "<option value='$uvoptionvalue' $uvselected>$uvoptionvalue</option>";
                }
            } else {
                // Generate options from min to max
                for ($uvi = $uvmin; $uvi <= $uvmax; $uvi++) {
                    $uvselected = ($uvi == $uvvalue) ? "selected" : "";
                    $uvdropdownoptions .= "<option value='$uvi' $uvselected>$uvi</option>";
                }
            }
            
            $uvselboxitem = "
                    <select class='$uvclass' id='$uvinpid' name='$uvname' $uvvaluesstrattr>
                        $uvdropdownoptions
                    </select>
            ";
        } else {
            $uvselboxitem = "
                        <button class='uwsjs-selnum-minus $uvminusclass' type='button' aria-label='Remove 1 Guests'><i class='uwsicon-minus-1'></i> <span>Minus</span></button>
                        <input type='number' class='$uvclass' id='$uvinpid' name='$uvname' value='$uvvalue' max='$uvmax' min='$uvmin' $uvvaluesstrattr $uvaddreadonly>
                        <button class='uwsjs-selnum-plus $uvmaxclass' type='button' aria-label='Add 1 Guests'><i class='uwsicon-plus-1'></i> <span>Plus</span></button>
            ";
        }

        $uvselbox = "
            <div class='uwsitemselbox $uvboxclass'>
                <div class='uwslabel'>$uviconhtml<label for='$uvinpid'>$uvlabel</label></div>
                <div class='uwsselnum'>
                    $uvselboxitem
                </div>
            </div>
        ";
    }

    return $uvselbox;
}

/*Get dates with no inventory from a mont
    Requires: args: venuecode, date, ecozone
    Returns: array with dates
*/
function urvenue_ws_get_month_noinventory_dates($uvargs = "") {
    $uvvenuecode = urvenue_ws_get_arg($uvargs, "venuecode");
    $uvdate = urvenue_ws_get_arg($uvargs, "date");
    $uvecozone = urvenue_ws_get_arg($uvargs, "ecozone");
    $uvuseeczdetails = ($uvecozone > 2) ? 1 : 0;
    $uvecozone = urvenue_ws_standardize_old_ecozone($uvecozone);
    $uvfromdate = gmdate("Y-m-01", strtotime($uvdate));
    $uvtodate = gmdate("Y-m-t", strtotime($uvdate));
    $uvglobaltype = urvenue_ws_get_arg($uvargs, "globaltype");
    $uvmixecozones = urvenue_ws_get_arg($uvargs, "mixecozones");

    $uvfeedtoken = "venuecode=$uvvenuecode&startdate=$uvfromdate&" . ($uvuseeczdetails ? "enddate" : "todate") . "=$uvtodate&" . ($uvuseeczdetails ? "ecozone" : "ecozones") . "=$uvecozone";
    $uvavailabilityfeed = ($uvuseeczdetails) ? urvenue_ws_get_feed("ecozonedetails", $uvfeedtoken) : urvenue_ws_get_feed("gxnavailability", $uvfeedtoken);

    $uvnoinventorydates = array(
        "monthdate" => $uvfromdate,
        "noinventorydates" => array(),
    );
    if (is_array($uvavailabilityfeed) and $uvavailabilityfeed["uv"]["success"]["status"] == "success") {
        $uvinventorydates = ($uvuseeczdetails) ? $uvavailabilityfeed["uv"]["data"] : $uvavailabilityfeed["uv"]["data"][$uvvenuecode];
        $uvloopstartdate = new DateTime($uvfromdate);
        $uvloopenddate = new DateTime($uvtodate);
        $uvloopenddate->modify('+1 day');

        $uvloopinterval = new DateInterval('P1D');
        $uvloopperiod = new DatePeriod($uvloopstartdate, $uvloopinterval, $uvloopenddate);

        foreach ($uvloopperiod as $uvthisdate) {
            $uvthedate = $uvthisdate->format('Y-m-d');
            $uvsdate = gmdate("Ymd", strtotime($uvthedate));

            if($uvmixecozones){//Mix all ecozones, if one ecozone has inventory it won't be on "noinventorydates"
                $uvdatehasitems = 0;

                if($uvinventorydates["D" . $uvsdate]){
                    foreach($uvinventorydates["D" . $uvsdate] as $uvavdate){
                        if (is_array($uvavdate) && $uvavdate["dayopen"] && isset($uvavdate["stocks"])){
                            $uvdatehasitems = 1;
                            break;
                        }
                    }
                }
                else
                    $uvdatehasitems = 1;//avoid closing dates that are not on the api

                if(!$uvdatehasitems)
                    $uvnoinventorydates["noinventorydates"][] = $uvthedate;
            } else if($uvuseeczdetails) {
                $uvdateinvinfo = $uvinventorydates["D" . $uvsdate];
                // Only add dates that are NOT "Open"
                if (!is_array($uvdateinvinfo) || $uvdateinvinfo["status"] != "Open")
                    $uvnoinventorydates["noinventorydates"][] = $uvthedate;
            } else {
                $uvdateinvinfo = $uvinventorydates["D" . $uvsdate][$uvecozone];
                if (is_array($uvdateinvinfo) && ($uvdateinvinfo["status"] != "Open" || ((!$uvglobaltype || isset($uvdateinvinfo["stocks"][$uvglobaltype])) && (!isset($uvdateinvinfo["stocks"]) || !is_array($uvdateinvinfo["stocks"])))))
                    $uvnoinventorydates["noinventorydates"][] = $uvthedate;
            }
        }
    }

    return $uvnoinventorydates;
}

/*Get dates closed dates from a mont
    Requires: args: venuecode, date, ecozone
    Returns: array with dates
*/
function urvenue_ws_get_month_closed_dates($uvargs = "")
{
    $uvvenuecode = urvenue_ws_get_arg($uvargs, "venuecode");
    $uvdate = urvenue_ws_get_arg($uvargs, "date");
    $uvecozone = urvenue_ws_get_arg($uvargs, "ecozone");
    $uvecozone = urvenue_ws_standardize_old_ecozone($uvecozone);
    $uvfromdate = gmdate("Y-m-01", strtotime($uvdate));
    $uvtodate = gmdate("Y-m-t", strtotime($uvdate));

    $uvfeedtoken = "venuecode=" . $uvvenuecode . "&startdate=" . $uvfromdate . "&todate=" . $uvtodate . "&ecozones=" . $uvecozone;
    $uvavailabilityfeed = urvenue_ws_get_feed("gxnavailability", $uvfeedtoken);

    $uvcloseddates = array(
        "monthdate" => $uvfromdate,
        "closeddates" => array(),
    );
    if (is_array($uvavailabilityfeed) and $uvavailabilityfeed["uv"]["success"]["status"] == "success") {
        $uvinventorydates = $uvavailabilityfeed["uv"]["data"][$uvvenuecode];
        $uvloopstartdate = new DateTime($uvfromdate);
        $uvloopenddate = new DateTime($uvtodate);
        $uvloopenddate->modify('+1 day');

        $uvloopinterval = new DateInterval('P1D');
        $uvloopperiod = new DatePeriod($uvloopstartdate, $uvloopinterval, $uvloopenddate);

        foreach ($uvloopperiod as $uvthisdate) {
            $uvthedate = $uvthisdate->format('Y-m-d');
            $uvsdate = gmdate("Ymd", strtotime($uvthedate));
            $uvdateinvinfo = $uvinventorydates["D" . $uvsdate][$uvecozone];

            if (is_array($uvdateinvinfo) && ($uvdateinvinfo["status"] != "Open"))
                $uvcloseddates["closeddates"][] = $uvthedate;
        }
    }

    return $uvcloseddates;
}

/*Get ecozone 6 characters format: ECZXXX
    Requires: ecozone
*/
function urvenue_ws_standardize_ecozone($uvecozone)
{
    $uvecozone = str_replace("ECZ", "", $uvecozone);

    if (strlen($uvecozone) == 1)
        $uvecozone = "00" . $uvecozone;

    if (strlen($uvecozone) == 2)
        $uvecozone = "0" . $uvecozone;

    $uvecozone = "ECZ" . $uvecozone;

    return $uvecozone;
}

/*Get ecozone 6 characters format: ECZX
    Requires: ecozone
*/
function urvenue_ws_standardize_old_ecozone($uvecozone)
{
    $uvecozone = str_replace("ECZ", "", $uvecozone);
    $uvecozone = ($uvecozone) ? $uvecozone / 1 : "0";

    $uvecozone = "ECZ" . $uvecozone;

    return $uvecozone;
}

/*Get ecozoneid change format: ECZXXX to plainid
    Requires: ecozone(format: ECZXXX)
*/
function urvenue_ws_ecozone_to_ecoid($uvecozone)
{
    $uvecozone = str_replace(
        array(
            "ECZ00",
            "ECZ0",
        ),
        array(
            "",
            "",
        ),
        $uvecozone
    );

    if (!$uvecozone)
        $uvecozone = "0";

    return $uvecozone;
}

/*Get inventory item url
    Requires: item(item data array)
*/
function urvenue_ws_get_item_url($uvitem)
{
    global $urvenue_ws_core_lib;

    $uvbaseurl = (urvenue_ws_is_wordpress()) ? get_permalink($urvenue_ws_core_lib["pages"]["itempage"]) : $urvenue_ws_core_lib["pages"]["itempage"];
    $uvbaseurl = $uvbaseurl . "MC{mastercode}/{itemnameurl}";

    $uvitemurl = "#";
    if (is_array($uvitem)) {
        $uvitemnameurl = urvenue_ws_get_linkstring($uvitem["itemname"]);

        $uvitemurl = str_replace(
            array(
                "{mastercode}",
                "{itemnameurl}",
            ),
            array(
                $uvitem["mastercode"],
                $uvitemnameurl,
            ),
            $uvbaseurl
        );
    }

    return $uvitemurl;
}

/*Get Booking Calendar
    Requires: args: venuecode, date, ecozone
*/
function urvenue_ws_booking_calendar($uvargs = "")
{
    global $urvenue_ws_today;

    $uvvenuecode = urvenue_ws_get_arg($uvargs, "venuecode");
    $uvdate = urvenue_ws_get_arg($uvargs, "date");
    $uvecozone = urvenue_ws_get_arg($uvargs, "ecozone");
    $uvmindate = urvenue_ws_get_arg($uvargs, "mindate", $urvenue_ws_today);
    $uveventcode = urvenue_ws_get_arg($uvargs, "eventcode");
    $uvmaxdate = urvenue_ws_get_events_max_date("Y-m-d");
    $uvbookingcalendar = "";
    $uvbookcalclass = ($uveventcode) ? "uwsbookactive" : "";

    if ($uvvenuecode and $uvdate and $uvecozone) {
        $uvbkcalendartemplate = urvenue_ws_get_template("inventory/booking-calendar-stage");

        $uvbookingcalendar = str_replace(
            array(
                "{venuecode}",
                "{date}",
                "{ecozone}",
                "{maxdate}",
                "{mindate}",
                "{eventcode}",
                "{bkcalclass}"
            ),
            array(
                $uvvenuecode,
                $uvdate,
                $uvecozone,
                $uvmaxdate,
                $uvmindate,
                $uveventcode,
                $uvbookcalclass
            ),
            $uvbkcalendartemplate
        );
    }

    echo wp_kses_post( $uvbookingcalendar );
}

function urvenue_ws_get_requestinfo()
{
    // $uvredqstring = (isset($_SERVER['REDIRECT_QUERY_STRING'])) ? $_SERVER['REDIRECT_QUERY_STRING'] : "";
    $uvredqstring = (isset($_SERVER['REDIRECT_QUERY_STRING'])) ? sanitize_text_field( wp_unslash( $_SERVER['REDIRECT_QUERY_STRING'] ) ) : "";

    return array(
        // "HTTP_HOST" => $_SERVER['HTTP_HOST'],
        "HTTP_HOST" => isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '',
        // "HTTP_USER_AGENT" => $_SERVER['HTTP_USER_AGENT'],
        "HTTP_USER_AGENT" => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '',
        "REDIRECT_QUERY_STRING" => $uvredqstring,
        "REMOTE_ADDR" => urvenue_ws_get_ipaddress()
    );
}

function urvenue_ws_get_ipaddress()
{
    $uvip = "";

    if (!empty($_SERVER['HTTP_CLIENT_IP']))
        // $uvip = $_SERVER['HTTP_CLIENT_IP'];
        $uvip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        // $uvip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        $uvip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
    else
        // $uvip = $_SERVER['REMOTE_ADDR'];
        $uvip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';

    return $uvip;
}


/* uws_inventorywidget */
function urvenue_ws_inventorywidget($uvargs)
{
    $uvglobaltypewidget = urvenue_ws_get_inventorywidget($uvargs);

    echo wp_kses_post( $uvglobaltypewidget );
}

function urvenue_ws_get_inventorywidget($uvargs)
{
    global $urvenue_ws_today;

    $urvenue_ws_venuecode = $uvargs['venuecode'];

    /* Dates Settings */
    $onlyweekdays = $uvargs['onlyweekdays'];

    $urvenue_ws_args_mindate = $uvargs['min-date'];

    if(isset($urvenue_ws_args_mindate) && $urvenue_ws_args_mindate != "")
        if($urvenue_ws_args_mindate < $urvenue_ws_today)
            $urvenue_ws_args_mindate = $urvenue_ws_today;

    $urvenue_ws_startdate = (!isset($urvenue_ws_args_mindate) || $urvenue_ws_args_mindate == "") ? $urvenue_ws_today : $urvenue_ws_args_mindate;

    $urvenue_ws_tempstartdate = ($urvenue_ws_args_mindate != "") ?  $urvenue_ws_args_mindate : "";

    $uvsearchnextavdate = urvenue_ws_get_arg($uvargs, "search-next-available-date", 0);

    if (!empty($onlyweekdays) && strtolower($onlyweekdays) !== "all") {
        $allowedWeekdays = array_map('trim', explode(',', $onlyweekdays));
        $allowedWeekdaysLower = array_map('strtolower', $allowedWeekdays);

        // Adjust date to next allowed weekday if needed
        $adjustedDate = new DateTime($urvenue_ws_startdate);
        while (!in_array(strtolower($adjustedDate->format('l')), $allowedWeekdaysLower)) {
            $adjustedDate->modify('+1 day');
        }
        $urvenue_ws_startdate = $adjustedDate->format('Y-m-d');
    }
    $urvenue_ws_maxdays = (!isset($uvargs['max-days']) or $uvargs['max-days'] == "") ? "" : $uvargs['max-days'];

    $uvdate = ($urvenue_ws_tempstartdate != "") ? $urvenue_ws_tempstartdate : urvenue_ws_get_arg($uvargs, "date", $urvenue_ws_startdate);

    $urvenue_ws_maxdate = ($urvenue_ws_maxdays) ? gmdate("Y-m-d", strtotime("+" . $urvenue_ws_maxdays . " days")) : urvenue_ws_get_events_max_date("Y-m-d");
    $urvenue_ws_enddate = ($urvenue_ws_maxdate) ? $urvenue_ws_maxdate : $urvenue_ws_enddate;
    $urvenue_ws_enddate = isset($uvargs['end-date']) ? $uvargs['end-date'] : $urvenue_ws_enddate;

    if (($urvenue_ws_enddate != "") and $urvenue_ws_maxdate > $urvenue_ws_enddate) {
        $urvenue_ws_maxdate = $urvenue_ws_enddate;
    }

    $uwsstartdateformat = str_replace("-", "", $urvenue_ws_startdate);

    $uwsinitddate = gmdate('M j, Y', strtotime($uvdate));

    /* Ecozone Settings */
    $uvecozone = urvenue_ws_get_arg($uvargs, "ecozone", "ECZ000");
    $uvecozoneid = urvenue_ws_ecozone_to_ecoid($uvecozone);
    $uvecozone3 = urvenue_ws_standardize_ecozone($uvecozone);
    $uvecozone3 = str_replace("ECZ", "", $uvecozone3);

    /* Globaltype Settings*/
    $urvenue_ws_globaltype = isset($uvargs['globaltype']) ? $uvargs['globaltype'] : "";

    /* Booktypename Settings */
    $urvenue_ws_booktypename = isset($uvargs['booktypename']) ? $uvargs['booktypename'] : "";

    /* Event Code Settings*/
    $uwsstarteventcode = str_replace("VEN", "EVE", $urvenue_ws_venuecode);
    $uwsstarteventcode = $uwsstarteventcode . $uvecozone3 . $uwsstartdateformat;
    $uveventcode = urvenue_ws_get_arg($uvargs, "eventcode", $uwsstarteventcode);

    // Check if the start date is a no inventory date
    $uwsnoinvargs = array(
        "venuecode" => $urvenue_ws_venuecode,
        "date" => $urvenue_ws_startdate,
        "ecozone" => $uvecozone3,
    );

     /* Mix Ecozones Settings */
    $urvenue_ws_mixecozones = (isset($uvargs['mixecozones']) and $uvargs['mixecozones'] == 1) ? 1 : 0;
    if ($urvenue_ws_mixecozones) $uwsnoinvargs["mixecozones"] = 1;

    $urvenue_ws_no_inventory_dates = urvenue_ws_get_month_noinventory_dates($uwsnoinvargs);

    if ($uvsearchnextavdate and is_array($urvenue_ws_no_inventory_dates) && isset($urvenue_ws_no_inventory_dates["noinventorydates"]) && in_array($uvdate, $urvenue_ws_no_inventory_dates["noinventorydates"])) {
        while (in_array($uvdate, $urvenue_ws_no_inventory_dates["noinventorydates"])) {
            $uvdate = gmdate('Y-m-d', strtotime($uvdate . ' +1 day'));
        }

        $urvenue_ws_startdate = $uvdate;
        $uwsstartdateformat = str_replace("-", "", $urvenue_ws_startdate);
    $uwsinitddate = gmdate('M j, Y', strtotime($uvdate));
    }

    /* Custom Message Settings*/
    $urvenue_ws_errortitle = (isset($uvargs['errortitle']) and $uvargs['errortitle'] != "") ? $uvargs['errortitle'] : "We are sorry.";
    $urvenue_ws_errorcontent = (isset($uvargs['errorcontent']) and $uvargs['errorcontent'] != "") ? $uvargs['errorcontent'] : "We could not find any available items for the selected date.";

    /* Custom Buttons Search (optional) */
    $urvenue_ws_displaybuttonlabel = isset($uvargs['displaybuttonlabel']) ? $uvargs['displaybuttonlabel'] : "";
    $urvenue_ws_displaybutton = ($uvargs['displaybutton'] != "") ? "<div class='uws-gtwb-wrapper'><button class='uws-btn uws-btn-s uws-to-show-button'>$urvenue_ws_displaybuttonlabel</button></div>" : "";

    $uvwidgetclasses = "";
    if ($urvenue_ws_displaybutton != "") {
        $uvwidgetclasses = "uws-hide-inventory";
    }

    $uvhidedatepicker = urvenue_ws_get_arg($uvargs, "hidedatepicker", 0);
    $uvshowinventorybuttons = urvenue_ws_get_arg($uvargs, "showinventorybuttons", 0);
    $uvshowevtinfo = urvenue_ws_get_arg($uvargs, "showevtinfo", 0);
    $uvwidgetclasses = ($uvhidedatepicker) ? $uvwidgetclasses . " uwshidedp" : $uvwidgetclasses;
    $uvwidgetclasses = ($uvshowinventorybuttons) ? $uvwidgetclasses . " uwsshowinvbuttons" : $uvwidgetclasses;
    $uvwidgetclasses = ($uvshowevtinfo) ? $uvwidgetclasses . " uwsshowevtinfo" : $uvwidgetclasses;
    $uvwpglobalwidget = urvenue_ws_get_template("inventory/inventory-items-widget");

    //Dropdown Filters
    $uvdropdownfilters = "";
    $uvshoweventsdropdown = urvenue_ws_get_arg($uvargs, "showeventsdropdown", 0);
    if ($uvshoweventsdropdown)
        $uvdropdownfilters = "<div class='uwseventsel'></div>";

    $urvenue_ws_globaltype_widget = str_replace(
        array(
            "{startdate}",
            "{max-date}",
            "{initdate}",
            "{initeventcode}",
            "{venuecode}",
            "{ecozone}",
            "{globaltype}",
            "{errortitle}",
            "{errorcontent}",
            "{widgetclasses}",
            "{ctacontent}",
            "{date}",
            "{dropdownfilters}",
            "{showeventsdropdown}",
            "{onlyweekdays}",
            "{booktypename}",
            "{mixecozones}",
        ),
        array(
            $urvenue_ws_startdate,
            $urvenue_ws_maxdate,
            $uwsinitddate,
            $uveventcode,
            $urvenue_ws_venuecode,
            $uvecozone3,
            $urvenue_ws_globaltype,
            $urvenue_ws_errortitle,
            $urvenue_ws_errorcontent,
            $uvwidgetclasses,
            $urvenue_ws_displaybutton,
            $uvdate,
            $uvdropdownfilters,
            $uvshoweventsdropdown,
            $onlyweekdays,
            $urvenue_ws_booktypename,
            $urvenue_ws_mixecozones,
        ),
        $uvwpglobalwidget
    );

    return $urvenue_ws_globaltype_widget;
}