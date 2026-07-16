<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

urvenue_ws_check_nonce("urvenue_ws_inventory");

$urvenue_ws_mastercode = urvenue_ws_cleanup_request("mastercode");
$urvenue_ws_guests = urvenue_ws_cleanup_request("guests");

$urvenue_ws_item = urvenue_ws_get_invitem($urvenue_ws_mastercode);

$urvenue_ws_dataslots = "";

if(is_array($urvenue_ws_item) and isset($urvenue_ws_item["header"]) and $urvenue_ws_item["header"]["timemode"] == "TimeSlot" and isset($urvenue_ws_item["shifts"]["SHT0"]["all_times"]) and is_array($urvenue_ws_item["shifts"]["SHT0"]["all_times"])){
    $urvenue_ws_timeslist = "";

    //used to get capacity
    $urvenue_ws_masteritemcode = $urvenue_ws_item["info"]["masteritemcode"];

    foreach($urvenue_ws_item["shifts"]["SHT0"]["all_times"] as $urvenue_ws_time => $urvenue_ws_dtime){
        $urvenue_ws_dtime = urvenue_ws_get_formattime($urvenue_ws_time, 1);
        $urvenue_ws_showtype = 1;

        if(isset($urvenue_ws_item["slots"]) and is_array($urvenue_ws_item["slots"]) and isset($urvenue_ws_item["slots"]["SHT" . $urvenue_ws_time]) and $urvenue_ws_item["slots"]["SHT" . $urvenue_ws_time] == "-1")
            $urvenue_ws_showtype = 0;

        //gets slots and sees if they are sufficient for the guests defined
        $urvenue_ws_shiftid = "SHT".$urvenue_ws_time;
        $urvenue_ws_slots = $urvenue_ws_item["slots"][$urvenue_ws_shiftid];

        if($urvenue_ws_guests != "") {
            $urvenue_ws_guests = (int)$urvenue_ws_guests;
            $urvenue_ws_slots = (int)$urvenue_ws_slots;

            if($urvenue_ws_slots && $urvenue_ws_guests <= $urvenue_ws_slots)
                $urvenue_ws_dataslots = " data-slots='$urvenue_ws_slots'";
            else $urvenue_ws_showtype = 0;
        }

        if($urvenue_ws_showtype){
            if(!$urvenue_ws_afmidnight and $urvenue_ws_dtime["aftermidnight"]){
                $urvenue_ws_timeslist .= "<li class='uwsaftermidnight'>After Midnight</li>";
                $urvenue_ws_afmidnight = 1;
            }

            $urvenue_ws_timeslist .= "<li><a class='uwsjs-selectottime' href='#selectottime-$urvenue_ws_time' data-time='$urvenue_ws_time' data-dtime='" . $urvenue_ws_dtime["dtime"] . "'".$urvenue_ws_dataslots."><span>" . $urvenue_ws_dtime["dtime"] . "</span></a></li>";
        }
    }

    $urvenue_ws_timessel = "
        <div class='uwsselscreenbody uwsapi-missing-req' data-apimr-title='Missing item field' data-apimr-descr='To be able to call the OT API to get the available times we need a field called -otdata-, this field is not present in the inventoryitem API'>
            <div class='uwslabel'><i class='uwsicon-clock-1'></i> Select Time</div>
            <ul class='uwsottimeslist'>
                $urvenue_ws_timeslist
            </ul>
        </div>
        <div class='uwsselscreenfooter'>
            <button class='uws-btn uws-btn-s uwsjs-viewshowmain'>Cancel</button>
        </div>
    ";
}

$urvenue_ws_return = array(
    "html" => $urvenue_ws_timessel,
);

$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_return);
header('Content-Type: application/json');
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode()
