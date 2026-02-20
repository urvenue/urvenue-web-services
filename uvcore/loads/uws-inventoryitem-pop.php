<?php

global $uws_proxies_lib;

$uvmastercode = (isset($_REQUEST["mastercode"])) ? uws_cleanup_var($_REQUEST["mastercode"]) : "";
$uvsectionid = (isset($_REQUEST["sectionid"])) ? uws_cleanup_var($_REQUEST["sectionid"]) : "";
$uvlocationid = (isset($_REQUEST["locationid"])) ? uws_cleanup_var($_REQUEST["locationid"]) : "";
$uvforcenew = (isset($_REQUEST["forcenew"])) ? uws_cleanup_var($_REQUEST["forcenew"]) : "";
$uvpricingbreakdown = (isset($_REQUEST["pricingbreakdown"])) ? uws_cleanup_var($_REQUEST["pricingbreakdown"]) : "";
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
    
// @Axl
// $uvreturnjson = json_encode($uvreturn);
$uvreturnjson = wp_json_encode($uvreturn);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);