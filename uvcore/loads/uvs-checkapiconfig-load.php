<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//print_r($_REQUEST);
//print_r($uvs_core_lib);

$uvapikey = $_REQUEST["apikey"];
$uvmicrocode = $_REQUEST["microcode"];
$uvsourcecode = $uvs_core_lib["system"]["sourcecode"];
$uvsourceloc = $uvs_core_lib["system"]["sourceloc"];

$uvsmicrocodeurl = $uvs_admin_feeds["microsite"];
$uvsmicrocodeurl = str_replace("{envicode}", $uvs_envicode, $uvsmicrocodeurl);
$uvsmicrocodeurl = str_replace("{apikey}", $uvapikey, $uvsmicrocodeurl);
$uvsmicrocodeurl = str_replace("{sourcecode}", $uvsourcecode, $uvsmicrocodeurl);
$uvsmicrocodeurl = str_replace("{sourceloc}", $uvsourceloc, $uvsmicrocodeurl);
$uvsmicrocodeurl = str_replace("{params}", "code=$uvmicrocode&venueimages=1", $uvsmicrocodeurl);

$uvsmicrocodefeed = uvs_pullfeed($uvsmicrocodeurl);
$uvsmicrocodefeed = ($uvsmicrocodefeed) ? json_decode($uvsmicrocodefeed, true) : "";

if(is_array($uvsmicrocodefeed) and $uvsmicrocodefeed["uv"]["success"]){
    if($uvsmicrocodefeed["uv"]["success"]["status"] == "error"){
        $uverrormsg = $uvsmicrocodefeed["uv"]["success"]["message"];

        $uverrormsg = ($uverrormsg == "Invalid API key") ? "Invalid <strong>API Key</strong>." : $uverrormsg;
        $uverrormsg = ($uverrormsg == "Invalid microsite") ? "Your <strong>Micro Code</strong> has not been found." : $uverrormsg;

        $uvreturnarray = array(
            "status" => "error",
            "error-msg" => $uverrormsg,
        );
    }
    else if($uvsmicrocodefeed["uv"]["success"]["status"] == "success"){
        $uvvenues = $uvsmicrocodefeed["uv"]["data"]["venues"];

        if(is_array($uvvenues) and count($uvvenues)){
            $uvvenueshtml = "";
            $uvvenuescounter = 0;

            foreach($uvvenues as $uvvenue){
                $uvsvenueinfo = $uvvenue[array_key_first($uvvenue)];
                $uvvenuescounter++;

                $uvsvenuewbcode = $uvsvenueinfo["wbcode"];
                $uvsvenuename = $uvsvenueinfo["venuename"];
                $uvsmanageentid = $uvsvenueinfo["manageentid"];
                $uvsvenueveaid = $uvsvenueinfo["veaid"];
                $uvsvenueuvid = $uvsvenueinfo["urvenueid"];
                $uvsvenueclientid = $uvsvenueinfo["clientid"];
                $uvsvenuelogofolder = ($uvsvenueinfo["images"]["logo"]["folder"]) ? $uvsvenueinfo["images"]["logo"]["folder"] : "";
                $uvsvenuelogofile = ($uvsvenueinfo["images"]["logo"]["file"]) ? $uvsvenueinfo["images"]["logo"]["file"] : "";
                $uvsvenuelogo = ($uvsvenuelogofolder and $uvsvenuelogofile) ? $uvsvenuelogofolder . "/raw/" . $uvsvenuelogofile : "";
                $uvsvenueserver = $uvsvenueinfo["uvurl"];
                $uvsvenueisprimary = $uvsvenueinfo["isprimary"];

                $uvsvenuelogoclass = (!$uvsvenuelogo) ? "noimg" : "";
                $uvsvenueisprimary = ($uvvenuescounter == 1) ? 1 : 0;

                //Assign venuekey if no wbcode 
                $uvsvenuewbcode = ($uvsvenuewbcode) ? $uvsvenuewbcode : uvs_get_linkstring($uvsvenuename);

                //Provider + Reseller equal to manageetid
                $uvsproviderid = $uvsresellerid = $uvsmanageentid;

                $uvsvenueforminfo = "<input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][venuekey]' value='$uvsvenuewbcode'>";
                $uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][manageentid]' value='$uvsmanageentid'>";
                $uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][providerid]' value='$uvsproviderid'>";
                $uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][resellerid]' value='$uvsresellerid'>";
                $uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][venuename]' value='$uvsvenuename'>";
                $uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][venuecode]' value='VEN$uvsvenueveaid'>";
                $uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][urvenueid]' value='$uvsvenueuvid'>";
                $uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][clientid]' value='$uvsvenueclientid'>";
                $uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][logourl]' value='$uvsvenuelogo'>";
                $uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][uvserver]' value='$uvsvenueserver'>";
                $uvsvenueforminfo .= "<input class='uvsjson venueprimary' type='hidden' name='venues[$uvsvenuewbcode][isprimary]' value='$uvsvenueisprimary'>";

                $uvsvenueisprimarylabel = ($uvsvenueisprimary) ? "Is Primary" : "Make Primary";
                $uvsvenueisprimaryclass = ($uvsvenueisprimary) ? "active" : "";

                $uvsvenueidhtml = ($uvsvenueuvid) ? "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue ID:</div><div class='uvsvalue'>$uvsvenueuvid</div></div>" : "";
                $uvsclientidhtml = ($uvsvenueclientid) ? "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Client ID:</div><div class='uvsvalue'>$uvsvenueclientid</div></div>" : "";
                $uvsserverhtml = ($uvsvenueserver) ? "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>UrVenue Server:</div><div class='uvsvalue'>$uvsvenueserver</div></div>" : "";

                $uvvenuealiasinput = "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Use Alias as Venue Name:</div><div class='uvsvalue'><div class='uvs-switch-ui'><button class='uvsjs-trigger-switch' type='button'><span class='uvs-lb-on'>Yes</span><span class='uvs-lb-off'>No</span></button><input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][venueforcealias]' value='' data-value-on='1' data-value-off=''></div></div></div>";

                $uvhideeventsinput = "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Hide Events:</div><div class='uvsvalue'><div class='uvs-switch-ui'><button class='uvsjs-trigger-switch' type='button'><span class='uvs-lb-on'>Yes</span><span class='uvs-lb-off'>No</span></button><input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][venuehideinevents]' value='' data-value-on='1' data-value-off=''></div></div></div>";

                $uvvenueshtml .= "<div class='uvs-admin-venueinf uvs-admin-venueinf-vc-VEN$uvsvenueveaid'>$uvsvenueforminfo<div class='uvs-infolist-item-img $uvsvenuelogoclass' style='background-image: url($uvsvenuelogo);'></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue KEY:</div><div class='uvsvalue'><strong>$uvsvenuewbcode</strong></div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Name:</div><div class='uvsvalue'>$uvsvenuename</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Name Alias:</div><div class='uvsvalue'><input type='text' name='venues[$uvsvenuewbcode][venuealias]' value='' class='uvsjson'></div></div>{$uvvenuealiasinput}{$uvhideeventsinput}<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Code:</div><div class='uvsvalue'>VEN$uvsvenueveaid</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Manageentid:</div><div class='uvsvalue'>$uvsmanageentid</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Providerid:</div><div class='uvsvalue'>$uvsproviderid</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Resellerid:</div><div class='uvsvalue'>$uvsresellerid</div></div>" . $uvsvenueidhtml . $uvsclientidhtml . $uvsserverhtml . "<div class='actions'><a class='uvsjs-triggervenueprimary $uvsvenueisprimaryclass' href='javascript:;' data-isprimary='$uvsvenueisprimary'>$uvsvenueisprimarylabel</a><a class='uvsjs-removevenue' href='javascript:;'>Remove</a></div></div>";

                if($uvvenuescounter >= 50)
                    break;
            }

            $uvreturnarray = array(
                "status" => "success",
                "venueshtml" => $uvvenueshtml,
            );
        }
        else{
            $uvreturnarray = array(
                "status" => "error",
                "error-msg" => "You need to add <strong>Venues</strong> to your account",
            );
        }
    }
}
else{
    $uvreturnarray = array(
        "status" => "error",
        "error-msg" => "Something went wrong while pulling the information",
    );
}

// @Axl
// $uvreturnjson = json_encode($uvreturnarray);
$uvreturnjson = wp_json_encode($uvreturnarray);
// @Axl End
header('Content-Type: application/json');
echo($uvreturnjson);