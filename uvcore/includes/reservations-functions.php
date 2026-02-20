<?php

/*Get inquiry forms
    Optional: args: array with options: venue(default all on library)
    Returns: Prints html controls + events integration
*/
function uws_inquiry_form($uvargs = "")
{
    global $uws_core_lib, $uws_today;

    $uwsinqformhtml = uws_get_template("/reservations/inquiry-form");
    $uvphonecodeopts = uws_get_phonecode_options();
    $uvvenuekeycodes = uws_get_arg($uvargs, "venue", "all");
    $uvvenueid = $uvmanageentid = "";
    $uvvenueselector = "";
    $uws_redirect_to = isset($uvargs['redirect_to']) ? $uvargs['redirect_to'] : "";
    $uvnamefields = isset($uvargs['namefields']) ? $uvargs['namefields'] : "";
    $uvopendays = isset($uvargs['opendays']) ? $uvargs['opendays'] : "";
    $uvopendaysclass = "";

    if($uvopendays) $uvopendaysclass = "uwsopendays";

    $uvdefaultprivacylink = "https://www.urvenue.com/legal/privacy-policy/";
    $uvdefaulttermslink = "https://www.urvenue.com/legal/terms-conditions/";

    // Privacy Policy link
    $uvprivacylink = (is_array($uws_core_lib) and isset($uws_core_lib["pages"]["privacy"]) and $uws_core_lib["pages"]["privacy"] and uws_is_wordpress()) ? get_permalink($uws_core_lib["pages"]["privacy"]) : $uvdefaultprivacylink;

    // Terms link
    $uvtermslink = (is_array($uws_core_lib) and isset($uws_core_lib["pages"]["terms"]) and $uws_core_lib["pages"]["terms"] and uws_is_wordpress()) ? get_permalink($uws_core_lib["pages"]["terms"]) : $uvdefaulttermslink;

    if (is_array($uws_core_lib["venues"])) {
        $uvvenueselectorclass = ($uvvenuekeycodes and $uvvenuekeycodes != "all") ? "uwsisspecificvenue" : "";
        $uvvenueselector = "<div class='uws-inputcont $uvvenueselectorclass'><label for='unvinqvenue'>Venue*</label><select id='unvinqvenue' name='venue' class='uwsjs-inq-selectvenue' required>";
        $uvvenueselector .= "<option value=''>Select a Venue</option>";

        foreach ($uws_core_lib["venues"] as $uvvenuekey => $uvvenue) {
            $uvvenuename = (isset($uvvenue["venueforcealias"]) and $uvvenue["venueforcealias"] and $uvvenue['venuealias']) ? $uvvenue['venuealias'] : $uvvenue["venuename"];

            $uvvenueselector .= "<option value='$uvvenuekey' data-venuecode='{$uvvenue["venuecode"]}' data-manageentid='{$uvvenue["manageentid"]}'>$uvvenuename</option>";

            if ($uvvenuekeycodes and $uvvenuekeycodes != "all" and $uvvenuekeycodes == $uvvenuekey) {
                $uvvenueid = str_replace("VEN", "", $uvvenue["venuecode"]);
                $uvmanageentid = $uvvenue["manageentid"];
            }
        }

        $uvvenueselector .= "</select></div>";
        if ($uvvenuekeycodes and $uvvenuekeycodes != "all")
            $uvvenueselector = "";

        $uvleadtypeselector = $uvleadtypeselectorclass = "";
        if ($uvvenueid and $uvmanageentid) {
            $uvleadtypesargs = array(
                "manageentid" => $uvmanageentid,
                "venueid" => $uvvenueid,
            );
            $uvleadtypes = uws_inquiry_get_leadtypes($uvleadtypesargs);


            if (is_array($uvleadtypes) and count($uvleadtypes)) {
                $uvleadtypeselectorclass = "uwsactive";
                $uvleadtypeselector = "<select id='uwsinqleadtype' class='uwsjs-inq-updateleadtype' name='inqleadtype' required>";
                if (is_array($uvleadtypes)) {
                    $uvleadtypeselector .= "<option value=''>Select Lead Type</option>";
                    foreach ($uvleadtypes as $uvleadtype) {
                        $uvleadtypeselector .= "<option value='{$uvleadtype["id"]}'>{$uvleadtype["name"]}</option>";
                    }
                }

                $uvleadtypeselector .= "</select>";
            }
        }

        $uvvenueselector .= "<div class='uws-inputcont uws-leadtypeselector $uvleadtypeselectorclass'><label for='uwsinqleadtype'>Request Type*</label><div class='uwsdy-leadtypeselector'>$uvleadtypeselector</div></div>";
    }

    $uvdpinitialdate = date("M j, Y", strtotime($uws_today));
    $uvinqdateselector = "
    <div class='uws-inputcont uws-isdateinput'>
        <div class='uws-inquiry-dpinput uws-dropdown-cont'>
            <label for='uwsinqddate'>Date*</label>
            <a id='uwsinqddate' href='#uws-open-inquiry-dateselection' data-date='$uws_today' class='uwsjs-trigger-dropdown uwsinput uwshascalincon $uvopendaysclass'>
                <i class='uwsicon-calendar' role='presentation'></i>
                <span class='uwsdy-inq-ddate'>Select Date</span>
            </a>
            <div class='uws-dropdown'>
                <div class='uws-inq-dp-date'></div>
            </div>
        </div>
        <div class='uwsinputerror'></div>
    </div>
    ";

    // Name fields/Party Name
    $uvpartyfield = "
        <div class='uws-inputcont'>
            <label for='uwsinqpartyname'>Party Name*</label>
            <input id='uwsinqpartyname' type='text' name='partyname' value='' required>
        </div>
    ";
    if($uvnamefields and $uvnamefields == "1") {
        $uvpartyfield = "
                <div class='uws-inputcont-2'>
                    <div class='uwsinput50 uws-inputcont'>
                        <label for='uwsinqfname'>First Name*</label>
                        <input id='uwsinqfname' type='text' name='fname' value='' required>
                    </div>
                    <div class='uwsinput50 uws-inputcont'>
                        <label for='uwsinqlname'>Last Name*</label>
                        <input id='uwsinqlname' type='text' name='lname' value='' required>
                    </div>
                </div>
        ";
    }

    $uwsinqformhtml = str_replace(
        array(
            "{phonecodesopts}",
            "{manageentid}",
            "{venueid}",
            "{caldate}",
            "{leadtype}",
            "{itemcode}",
            "{booktypeid}",
            "{globaltype}",
            "{mastercode}",
            "{itemname}",
            "{venueselector}",
            "{dateselector}",
            "{privacylink}",
            "{termslink}",
            "{redirect_to}",
            "{partyfield}",
            "{opendays}",
        ),
        array(
            $uvphonecodeopts,
            $uvmanageentid,
            $uvvenueid,
            "",
            "",
            "",
            "",
            "general",
            "",
            "",
            $uvvenueselector,
            $uvinqdateselector,
            $uvprivacylink,
            $uvtermslink,
            $uws_redirect_to,
            $uvpartyfield,
            $uvopendays,
        ),
        $uwsinqformhtml
    );

    // @Axl
    // echo "<div class='uws-integration uws-inquiryform-cont'>";
    // echo $uwsinqformhtml;
    // echo "<div class='uws-inquiry-statusmsg'><div class='uwsinqmsgbody uwsdy-inqmessage'></div></div>";
    // echo "<div class='uws-loader-uvicon'></div>";
    // echo "</div>";
    echo wp_kses_post( "<div class='uws-integration uws-inquiryform-cont'>" );
    echo wp_kses_post( $uwsinqformhtml );
    echo wp_kses_post( "<div class='uws-inquiry-statusmsg'><div class='uwsinqmsgbody uwsdy-inqmessage'></div></div>" );
    echo wp_kses_post( "<div class='uws-loader-uvicon'></div>" );
    echo wp_kses_post( "</div>" );
    // @Axl End
}

function uws_inquiry_get_leadtypes($uvargs)
{
    global $uws_feeds_lib;

    $uvleadtypeslist = "";
    $uvmanageentid = uws_get_arg($uvargs, "manageentid", "");
    $uvvenueid = uws_get_arg($uvargs, "venueid", "");
    $uvgetleadsurl = $uws_feeds_lib["inquiry-leadtypes"]["url"];
    $uvgetleadsexpiration = $uws_feeds_lib["inquiry-leadtypes"]["expiration"];

    $uvparams = "venue=" . $uvvenueid;
    $uvgetleadsurl = str_replace(
        array(
            "{manageentid}",
            "{params}",
        ),
        array(
            $uvmanageentid,
            $uvparams,
        ),
        $uvgetleadsurl
    );


    $uvleadtypeslist = "";
    $uvleadtypesfeed = uws_get_feed($uvgetleadsurl, $uvgetleadsexpiration);
    if (is_array($uvleadtypesfeed) and $uvleadtypesfeed["uv"]["success"]["status"] == "success" and $uvleadtypesfeed["uv"]["data"]) {
        $uvleadtypeslist = $uvleadtypesfeed["uv"]["data"];
    }

    return $uvleadtypeslist;
}