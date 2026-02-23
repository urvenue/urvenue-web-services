<?php

global $uws_proxies_lib;

// @egt [UWS-7297]
uws_check_nonce("uwsinventory");

$uvmastercode = uws_cleanup_request("mastercode");
$uvsectionid = uws_cleanup_request("sectionid");
$uvlocationid = uws_cleanup_request("locationid");
$uvforcenew = uws_cleanup_request("forcenew");
$uvpricingbreakdown = uws_cleanup_request("pricingbreakdown");
$uvitem = uws_get_invitem($uvmastercode);
$uviteminfo = $uvitem["info"];
$uvitempop = "";
$uvpopitemmodule = "default";

if(is_array($uvitem)){
    $uvargs = "";
    if(isset($uvitem["info"]) and $uvitem["info"]["globaltype"] == "membership"){
        $uvpopitemmodule = "membership";
        $uvargs = array(
            "template" => "memberships/item-pop"
        );
    }

    if($uvpricingbreakdown)
        $uvargs = array(
            "template" => "inventory/inventory-itembreakdown-pop"
        );
        

    $uvitempop = uws_get_itempop($uvitem, $uvargs);

    if($uvsectionid)
        $uvitem["selectedsectionid"] = $uvsectionid;
    if($uvlocationid)
        $uvitem["selectedlocationid"] = $uvlocationid;
    if($uvforcenew)
        $uvitem["selectedforcenew"] = $uvforcenew;
}

$uvreturn = array(
    "html" => $uvitempop,
    "popitem" => $uvitem,
    "popitem-module" => $uvpopitemmodule,
);

if($uvpopitemmodule == "membership"){
    $uvtempls = array(
        "membership-primmembership-sel-item" => uws_get_template("memberships/primmembership-sel-item"),
    );

    $uvreturn["templates"] = $uvtempls;
}

if($_REQUEST["returnprox"])
    $uvreturn["proxies"] = uws_get_proxies("inventory");
    
$uvreturnjson = json_encode($uvreturn);
header('Content-Type: application/json');
echo($uvreturnjson);