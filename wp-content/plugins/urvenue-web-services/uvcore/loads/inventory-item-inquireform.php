<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $urvenue_ws_core_lib;

// @egt [UWS-7297]
// uws_check_nonce("urvenue_ws_inventory");
urvenue_ws_check_nonce("urvenue_ws_inventory"); // Axl UWS-7416

// $urvenue_ws_managementid = uws_cleanup_request("manageentid");
$urvenue_ws_managementid = urvenue_ws_cleanup_request("manageentid"); // Axl UWS-7416
// $urvenue_ws_venuecode = uws_cleanup_request("venuecode");
$urvenue_ws_venuecode = urvenue_ws_cleanup_request("venuecode"); // Axl UWS-7416
// $urvenue_ws_caldate = uws_cleanup_request("caldate");
$urvenue_ws_caldate = urvenue_ws_cleanup_request("caldate"); // Axl UWS-7416
// $urvenue_ws_itemcode = uws_cleanup_request("itemcode");
$urvenue_ws_itemcode = urvenue_ws_cleanup_request("itemcode"); // Axl UWS-7416
// $urvenue_ws_booktypeid = uws_cleanup_request("booktypeid");
$urvenue_ws_booktypeid = urvenue_ws_cleanup_request("booktypeid"); // Axl UWS-7416
// $urvenue_ws_globaltype = uws_cleanup_request("globaltype");
$urvenue_ws_globaltype = urvenue_ws_cleanup_request("globaltype"); // Axl UWS-7416
// $urvenue_ws_mastercode = uws_cleanup_request("mastercode");
$urvenue_ws_mastercode = urvenue_ws_cleanup_request("mastercode"); // Axl UWS-7416
// $urvenue_ws_itemname = uws_cleanup_request("itemname");
$urvenue_ws_itemname = urvenue_ws_cleanup_request("itemname"); // Axl UWS-7416
// $urvenue_ws_partysize = uws_cleanup_request("guests");
$urvenue_ws_partysize = urvenue_ws_cleanup_request("guests"); // Axl UWS-7416
$urvenue_ws_venueid = str_replace("VEN", "", $urvenue_ws_venuecode);

// $urvenue_ws_inqformhtml = uws_get_template("/inventory/inventory-item-inquire-form");
$urvenue_ws_inqformhtml = urvenue_ws_get_template("/inventory/inventory-item-inquire-form"); // Axl UWS-7416
// $urvenue_ws_phonecodeopts = uws_get_phonecode_options();
$urvenue_ws_phonecodeopts = urvenue_ws_get_phonecode_options(); // Axl UWS-7416

if(!$urvenue_ws_managementid){
    // $urvenue_ws_libvenueinfo = uws_get_venuelibinfo_byvenuecode($urvenue_ws_venuecode);
    $urvenue_ws_libvenueinfo = urvenue_ws_get_venuelibinfo_byvenuecode($urvenue_ws_venuecode); // Axl UWS-7416
    
    if(is_array($urvenue_ws_libvenueinfo) and $urvenue_ws_libvenueinfo["manageentid"])
        $urvenue_ws_managementid = $urvenue_ws_libvenueinfo["manageentid"];
}

$urvenue_ws_defaultprivacylink = "https://www.urvenue.com/legal/privacy-policy/";
$urvenue_ws_defaulttermslink = "https://www.urvenue.com/legal/terms-conditions/";

// Privacy Policy link
// $urvenue_ws_privacylink = (is_array($urvenue_ws_core_lib) and isset($urvenue_ws_core_lib["pages"]["privacy"]) and $urvenue_ws_core_lib["pages"]["privacy"] and uws_is_wordpress()) ? get_permalink($urvenue_ws_core_lib["pages"]["privacy"]) : $urvenue_ws_defaultprivacylink;
$urvenue_ws_privacylink = (is_array($urvenue_ws_core_lib) and isset($urvenue_ws_core_lib["pages"]["privacy"]) and $urvenue_ws_core_lib["pages"]["privacy"] and urvenue_ws_is_wordpress()) ? get_permalink($urvenue_ws_core_lib["pages"]["privacy"]) : $urvenue_ws_defaultprivacylink; // Axl UWS-7416

// Terms link
// $urvenue_ws_termslink = (is_array($urvenue_ws_core_lib) and isset($urvenue_ws_core_lib["pages"]["terms"]) and $urvenue_ws_core_lib["pages"]["terms"] and uws_is_wordpress()) ? get_permalink($urvenue_ws_core_lib["pages"]["terms"]) : $urvenue_ws_defaulttermslink;
$urvenue_ws_termslink = (is_array($urvenue_ws_core_lib) and isset($urvenue_ws_core_lib["pages"]["terms"]) and $urvenue_ws_core_lib["pages"]["terms"] and urvenue_ws_is_wordpress()) ? get_permalink($urvenue_ws_core_lib["pages"]["terms"]) : $urvenue_ws_defaulttermslink; // Axl UWS-7416

// Name fields/Party Name
$urvenue_ws_namefields = (is_array($urvenue_ws_core_lib) and isset($urvenue_ws_core_lib["inventory"]["namefields"]) and $urvenue_ws_core_lib["inventory"]["namefields"]) ? $urvenue_ws_core_lib["inventory"]["namefields"] : "";
$urvenue_ws_partyfield = "
    <div class='uws-inputcont'>
        <label for='uwsinqpartyname'>Party Name*</label>
        <input id='uwsinqpartyname' type='text' name='partyname' value='' required>
    </div>
";

if($urvenue_ws_namefields) {
    $urvenue_ws_partyfield = "
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

$urvenue_ws_inqformhtml = str_replace(
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
        $urvenue_ws_phonecodeopts,
        $urvenue_ws_managementid,
        $urvenue_ws_venueid,
        $urvenue_ws_caldate,
        $urvenue_ws_itemcode,
        $urvenue_ws_booktypeid,
        $urvenue_ws_globaltype,
        $urvenue_ws_mastercode,
        $urvenue_ws_itemname,
        $urvenue_ws_privacylink,
        $urvenue_ws_termslink,
        $urvenue_ws_partyfield,
        $urvenue_ws_partysize,
    ), $urvenue_ws_inqformhtml
);

$urvenue_ws_return = array(
    "html" => $urvenue_ws_inqformhtml,
);

// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_return);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416