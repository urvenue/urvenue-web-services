<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $uws_core_lib;

// @egt [UWS-7297]
uws_check_nonce("uwsinventory");

$uvmanagementid = uws_cleanup_request("manageentid");
$uvvenuecode = uws_cleanup_request("venuecode");
$uvcaldate = uws_cleanup_request("caldate");
$uvitemcode = uws_cleanup_request("itemcode");
$uvbooktypeid = uws_cleanup_request("booktypeid");
$uvglobaltype = uws_cleanup_request("globaltype");
$uvmastercode = uws_cleanup_request("mastercode");
$uvitemname = uws_cleanup_request("itemname");
$uvpartysize = uws_cleanup_request("guests");
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