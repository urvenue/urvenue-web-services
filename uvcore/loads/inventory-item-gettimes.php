<?php

$uvmastercode = (isset($_REQUEST["mastercode"])) ? uws_cleanup_var($_REQUEST["mastercode"]) : "";
$uvguests = (isset($_REQUEST["guests"])) ? uws_cleanup_var($_REQUEST["guests"]) : "";

$uvitem = uws_get_invitem($uvmastercode);

$uvdataslots = "";

if(is_array($uvitem) and isset($uvitem["header"]) and $uvitem["header"]["timemode"] == "TimeSlot" and isset($uvitem["shifts"]["SHT0"]["all_times"]) and is_array($uvitem["shifts"]["SHT0"]["all_times"])){
    $uvtimeslist = "";

    //@egt used to get capacity
    $uvmasteritemcode = $uvitem["info"]["masteritemcode"];

    foreach($uvitem["shifts"]["SHT0"]["all_times"] as $uvtime => $uvdtime){
        $uvdtime = uws_get_formattime($uvtime, 1);
        $uvshowtype = 1;

        if(isset($uvitem["slots"]) and is_array($uvitem["slots"]) and isset($uvitem["slots"]["SHT" . $uvtime]) and $uvitem["slots"]["SHT" . $uvtime] == "-1")
            $uvshowtype = 0;

        //@egt gets slots and sees if they are sufficient for the guests defined
        $uvshiftid = "SHT".$uvtime;
        // $uvCap = $uvitem["elements"][$uvmasteritemcode]["shifts"][$uvshiftid]["DUR0"]["breakdowns"]["fullunit"]["internal"]["capacity"];
        $uvslots = $uvitem["slots"][$uvshiftid];

        if($uvguests != "") {
            $uvguests = (int)$uvguests;
            $uvslots = (int)$uvslots;

            if($uvslots && $uvguests <= $uvslots)
                $uvdataslots = " data-slots='$uvslots'";
            else $uvshowtype = 0;
        }

        if($uvshowtype){
            if(!$uvafmidnight and $uvdtime["aftermidnight"]){
                $uvtimeslist .= "<li class='uwsaftermidnight'>After Midnight</li>";
                $uvafmidnight = 1;
            }

            $uvtimeslist .= "<li><a class='uwsjs-selectottime' href='#selectottime-$uvtime' data-time='$uvtime' data-dtime='" . $uvdtime["dtime"] . "'".$uvdataslots."><span>" . $uvdtime["dtime"] . "</span></a></li>";
        }
    }

    $uvtimessel = "
        <div class='uwsselscreenbody uwsapi-missing-req' data-apimr-title='Missing item field' data-apimr-descr='To be able to call the OT API to get the available times we need a field called -otdata-, this field is not present in the inventoryitem API'>
            <div class='uwslabel'><i class='uwsicon-clock-1'></i> Select Time</div>
            <ul class='uwsottimeslist'>
                $uvtimeslist
            </ul>
        </div>
        <div class='uwsselscreenfooter'>
            <button class='uws-btn uws-btn-s uwsjs-viewshowmain'>Cancel</button>
        </div>
    ";
}

$uvreturn = array(
    "html" => $uvtimessel,
);
    
$uvreturnjson = json_encode($uvreturn);
header('Content-Type: application/json');
echo($uvreturnjson);