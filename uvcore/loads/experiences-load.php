<?php

global $uws_path, $uws_today;

// @egt [UWS-7297]
uws_check_nonce("uwsexperiences");

include_once($uws_path . "/includes/experiences-functions.php");

$uvdate = (isset($_REQUEST["date"])) ? uws_cleanup_var($_REQUEST["date"]) : $uws_today;

$uvexperiences = uws_get_dummyapi("experiences");
$uvinvitems = uws_inventory_microcode_items(array("date" => $uvdate));
//Add real items to dummy filters api --- shound connect the filters latter
$uvexperiences["items"] = $uvinvitems;
$uvexperienceslist = uws_get_experiences_list($uvexperiences);

$uvreturn = array(
    "list" => $uvexperienceslist
);
    
$uvreturnjson = json_encode($uvreturn);
header('Content-Type: application/json');
echo($uvreturnjson);