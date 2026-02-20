<?php

global $uws_core_lib;

$uvmanagementid = (isset($_REQUEST["manageentid"])) ? uws_cleanup_var($_REQUEST["manageentid"]) : "";
$uvvenuecode = (isset($_REQUEST["venuecode"])) ? uws_cleanup_var($_REQUEST["venuecode"]) : "";
$uvcaldate = (isset($_REQUEST["caldate"])) ? uws_cleanup_var($_REQUEST["caldate"]) : "";
$uvitemcode = (isset($_REQUEST["itemcode"])) ? uws_cleanup_var($_REQUEST["itemcode"]) : "";
$uvbooktypeid = (isset($_REQUEST["booktypeid"])) ? uws_cleanup_var($_REQUEST["booktypeid"]) : "";
$uvglobaltype = (isset($_REQUEST["globaltype"])) ? uws_cleanup_var($_REQUEST["globaltype"]) : "";
$uvmastercode = (isset($_REQUEST["mastercode"])) ? uws_cleanup_var($_REQUEST["mastercode"]) : "";
$uvitemname = (isset($_REQUEST["itemname"])) ? uws_cleanup_var($_REQUEST["itemname"]) : "";
$uvpartysize = (isset($_REQUEST["guests"])) ? uws_cleanup_var($_REQUEST["guests"]) : "";
$uvvenueid = str_replace("VEN", "", $uvvenuecode);

$uwsinqformhtml = uws_get_template("/inventory/inventory-item-inquire-form");
$uvphonecodeopts = uws_get_phonecode_options();

if(!$uvmanagementid){
    $uvlibvenueinfo = uws_get_venuelibinfo_byvenuecode($uvvenuecode);
    
    if(is_array($uvlibvenueinfo) and $uvlibvenueinfo["manageentid"])
        $uvmanagementid = $uvlibvenueinfo["manageentid"];
}

$uvdefaultprivacylink = "https://www.urvenue.com/legal/privacy-policy/";
$uvdefaulttermslink = "https://www.urvenue.com/legal/terms-conditions/";

// Privacy Policy link
$uvprivacylink = (is_array($uws_core_lib) and isset($uws_core_lib["pages"]["privacy"]) and $uws_core_lib["pages"]["privacy"] and uws_is_wordpress()) ? get_permalink($uws_core_lib["pages"]["privacy"]) : $uvdefaultprivacylink;

// Terms link
$uvtermslink = (is_array($uws_core_lib) and isset($uws_core_lib["pages"]["terms"]) and $uws_core_lib["pages"]["terms"] and uws_is_wordpress()) ? get_permalink($uws_core_lib["pages"]["terms"]) : $uvdefaulttermslink;

// Name fields/Party Name
$uvnamefields = (is_array($uws_core_lib) and isset($uws_core_lib["inventory"]["namefields"]) and $uws_core_lib["inventory"]["namefields"]) ? $uws_core_lib["inventory"]["namefields"] : "";
$uvpartyfield = "
    <div class='uws-inputcont'>
        <label for='uwsinqpartyname'>Party Name*</label>
        <input id='uwsinqpartyname' type='text' name='partyname' value='' required>
    </div>
";

if($uvnamefields) {
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
        "{itemcode}",
        "{booktypeid}",
        "{globaltype}",
        "{mastercode}",
        "{itemname}",
        "{privacylink}",
        "{termslink}",
        "{partyfield}",
        "{partysize}",
    ), 
    array(
        $uvphonecodeopts,
        $uvmanagementid,
        $uvvenueid,
        $uvcaldate,
        $uvitemcode,
        $uvbooktypeid,
        $uvglobaltype,
        $uvmastercode,
        $uvitemname,
        $uvprivacylink,
        $uvtermslink,
        $uvpartyfield,
        $uvpartysize,
    ), $uwsinqformhtml
);

$uvreturn = array(
    "html" => $uwsinqformhtml,
);

// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);