<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! current_user_can( 'manage_options' ) ) { // Axl UWS-8152
	wp_send_json_error( array( 'message' => 'Insufficient permissions' ), 403 ); // Axl UWS-8152
} // Axl UWS-8152
urvenue_ws_check_nonce( 'uvsp_checkapiconfig' ); // Axl UWS-8152

//print_r($_REQUEST);
//print_r($urvenue_ws_core_lib);

// $urvenue_ws_adm_apikey = $_REQUEST["apikey"]; // Axl UWS-7418
// $urvenue_ws_adm_apikey = isset( $_REQUEST["apikey"] ) ? sanitize_text_field( wp_unslash( $_REQUEST["apikey"] ) ) : ''; // Axl UWS-7418
$urvenue_ws_adm_apikey = isset( $_REQUEST["apikey"] ) ? sanitize_text_field( wp_unslash( $_REQUEST["apikey"] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin config check load; admin capability check handles authorization // Axl UWS-7416
// $urvenue_ws_adm_microcode = $_REQUEST["microcode"]; // Axl UWS-7418
// $urvenue_ws_adm_microcode = isset( $_REQUEST["microcode"] ) ? sanitize_text_field( wp_unslash( $_REQUEST["microcode"] ) ) : ''; // Axl UWS-7418
$urvenue_ws_adm_microcode = isset( $_REQUEST["microcode"] ) ? sanitize_text_field( wp_unslash( $_REQUEST["microcode"] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin config check load; admin capability check handles authorization // Axl UWS-7416
$urvenue_ws_adm_sourcecode = $urvenue_ws_core_lib["system"]["sourcecode"];
$urvenue_ws_adm_sourceloc = $urvenue_ws_core_lib["system"]["sourceloc"];

$urvenue_ws_adm_microcodeurl = $urvenue_ws_adm_admin_feeds["microsite"];
$urvenue_ws_adm_microcodeurl = str_replace("{envicode}", $urvenue_ws_adm_envicode, $urvenue_ws_adm_microcodeurl);
$urvenue_ws_adm_microcodeurl = str_replace("{apikey}", $urvenue_ws_adm_apikey, $urvenue_ws_adm_microcodeurl);
$urvenue_ws_adm_microcodeurl = str_replace("{sourcecode}", $urvenue_ws_adm_sourcecode, $urvenue_ws_adm_microcodeurl);
$urvenue_ws_adm_microcodeurl = str_replace("{sourceloc}", $urvenue_ws_adm_sourceloc, $urvenue_ws_adm_microcodeurl);
$urvenue_ws_adm_microcodeurl = str_replace("{params}", "code=$urvenue_ws_adm_microcode&venueimages=1", $urvenue_ws_adm_microcodeurl);

// $urvenue_ws_adm_microcodefeed = uvs_pullfeed($urvenue_ws_adm_microcodeurl);
$urvenue_ws_adm_microcodefeed = urvenue_ws_adm_pullfeed($urvenue_ws_adm_microcodeurl); // Axl UWS-7416
$urvenue_ws_adm_microcodefeed = ($urvenue_ws_adm_microcodefeed) ? json_decode($urvenue_ws_adm_microcodefeed, true) : "";

if(is_array($urvenue_ws_adm_microcodefeed) and $urvenue_ws_adm_microcodefeed["uv"]["success"]){
    if($urvenue_ws_adm_microcodefeed["uv"]["success"]["status"] == "error"){
        $urvenue_ws_adm_errormsg = $urvenue_ws_adm_microcodefeed["uv"]["success"]["message"];

        $urvenue_ws_adm_errormsg = ($urvenue_ws_adm_errormsg == "Invalid API key") ? "Invalid <strong>API Key</strong>." : $urvenue_ws_adm_errormsg;
        $urvenue_ws_adm_errormsg = ($urvenue_ws_adm_errormsg == "Invalid microsite") ? "Your <strong>Micro Code</strong> has not been found." : $urvenue_ws_adm_errormsg;

        $urvenue_ws_adm_returnarray = array(
            "status" => "error",
            "error-msg" => $urvenue_ws_adm_errormsg,
        );
    }
    else if($urvenue_ws_adm_microcodefeed["uv"]["success"]["status"] == "success"){
        $urvenue_ws_adm_venues = $urvenue_ws_adm_microcodefeed["uv"]["data"]["venues"];

        if(is_array($urvenue_ws_adm_venues) and count($urvenue_ws_adm_venues)){
            $urvenue_ws_adm_venueshtml = "";
            $urvenue_ws_adm_venuescounter = 0;

            foreach($urvenue_ws_adm_venues as $urvenue_ws_adm_venue){
                $urvenue_ws_adm_venueinfo = $urvenue_ws_adm_venue[array_key_first($urvenue_ws_adm_venue)];
                $urvenue_ws_adm_venuescounter++;

                $urvenue_ws_adm_venuewbcode = $urvenue_ws_adm_venueinfo["wbcode"];
                $urvenue_ws_adm_venuename = $urvenue_ws_adm_venueinfo["venuename"];
                $urvenue_ws_adm_manageentid = $urvenue_ws_adm_venueinfo["manageentid"];
                $urvenue_ws_adm_venueveaid = $urvenue_ws_adm_venueinfo["veaid"];
                $urvenue_ws_adm_venueuvid = $urvenue_ws_adm_venueinfo["urvenueid"];
                $urvenue_ws_adm_venueclientid = $urvenue_ws_adm_venueinfo["clientid"];
                $urvenue_ws_adm_venuelogofolder = ($urvenue_ws_adm_venueinfo["images"]["logo"]["folder"]) ? $urvenue_ws_adm_venueinfo["images"]["logo"]["folder"] : "";
                $urvenue_ws_adm_venuelogofile = ($urvenue_ws_adm_venueinfo["images"]["logo"]["file"]) ? $urvenue_ws_adm_venueinfo["images"]["logo"]["file"] : "";
                $urvenue_ws_adm_venuelogo = ($urvenue_ws_adm_venuelogofolder and $urvenue_ws_adm_venuelogofile) ? $urvenue_ws_adm_venuelogofolder . "/raw/" . $urvenue_ws_adm_venuelogofile : "";
                $urvenue_ws_adm_venueserver = $urvenue_ws_adm_venueinfo["uvurl"];
                $urvenue_ws_adm_venueisprimary = $urvenue_ws_adm_venueinfo["isprimary"];

                $urvenue_ws_adm_venuelogoclass = (!$urvenue_ws_adm_venuelogo) ? "noimg" : "";
                $urvenue_ws_adm_venueisprimary = ($urvenue_ws_adm_venuescounter == 1) ? 1 : 0;

                //Assign venuekey if no wbcode 
                // $urvenue_ws_adm_venuewbcode = ($urvenue_ws_adm_venuewbcode) ? $urvenue_ws_adm_venuewbcode : uvs_get_linkstring($urvenue_ws_adm_venuename);
                $urvenue_ws_adm_venuewbcode = ($urvenue_ws_adm_venuewbcode) ? $urvenue_ws_adm_venuewbcode : urvenue_ws_adm_get_linkstring($urvenue_ws_adm_venuename); // Axl UWS-7416

                //Provider + Reseller equal to manageetid
                $urvenue_ws_adm_providerid = $urvenue_ws_adm_resellerid = $urvenue_ws_adm_manageentid;

                // $urvenue_ws_adm_venueforminfo = "<input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][venuekey]' value='$urvenue_ws_adm_venuewbcode'>"; // Axl UWS-7416
                // $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][manageentid]' value='$urvenue_ws_adm_manageentid'>"; // Axl UWS-7416
                // $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][providerid]' value='$urvenue_ws_adm_providerid'>"; // Axl UWS-7416
                // $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][resellerid]' value='$urvenue_ws_adm_resellerid'>"; // Axl UWS-7416
                // $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][venuename]' value='$urvenue_ws_adm_venuename'>"; // Axl UWS-7416
                // $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][venuecode]' value='VEN$urvenue_ws_adm_venueveaid'>"; // Axl UWS-7416
                // $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][urvenueid]' value='$urvenue_ws_adm_venueuvid'>"; // Axl UWS-7416
                // $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][clientid]' value='$urvenue_ws_adm_venueclientid'>"; // Axl UWS-7416
                // $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][logourl]' value='$urvenue_ws_adm_venuelogo'>"; // Axl UWS-7416
                // $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][uvserver]' value='$urvenue_ws_adm_venueserver'>"; // Axl UWS-7416
                // $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson venueprimary' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][isprimary]' value='$urvenue_ws_adm_venueisprimary'>"; // Axl UWS-7416
                $urvenue_ws_adm_wbcode_esc = esc_attr( $urvenue_ws_adm_venuewbcode ); // Axl UWS-8151
                $urvenue_ws_adm_venueforminfo = "<input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][venuekey]' value='{$urvenue_ws_adm_wbcode_esc}'>"; // Axl UWS-8151
                $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][manageentid]' value='" . esc_attr( $urvenue_ws_adm_manageentid ) . "'>"; // Axl UWS-8151
                $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][providerid]' value='" . esc_attr( $urvenue_ws_adm_providerid ) . "'>"; // Axl UWS-8151
                $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][resellerid]' value='" . esc_attr( $urvenue_ws_adm_resellerid ) . "'>"; // Axl UWS-8151
                $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][venuename]' value='" . esc_attr( $urvenue_ws_adm_venuename ) . "'>"; // Axl UWS-8151
                $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][venuecode]' value='VEN" . esc_attr( $urvenue_ws_adm_venueveaid ) . "'>"; // Axl UWS-8151
                $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][urvenueid]' value='" . esc_attr( $urvenue_ws_adm_venueuvid ) . "'>"; // Axl UWS-8151
                $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][clientid]' value='" . esc_attr( $urvenue_ws_adm_venueclientid ) . "'>"; // Axl UWS-8151
                $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][logourl]' value='" . esc_url( $urvenue_ws_adm_venuelogo ) . "'>"; // Axl UWS-8151
                $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][uvserver]' value='" . esc_url( $urvenue_ws_adm_venueserver ) . "'>"; // Axl UWS-8151
                $urvenue_ws_adm_venueforminfo .= "<input class='uvsjson venueprimary' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][isprimary]' value='" . esc_attr( $urvenue_ws_adm_venueisprimary ) . "'>"; // Axl UWS-8151

                $urvenue_ws_adm_venueisprimarylabel = ($urvenue_ws_adm_venueisprimary) ? "Is Primary" : "Make Primary";
                $urvenue_ws_adm_venueisprimaryclass = ($urvenue_ws_adm_venueisprimary) ? "active" : "";

                // $urvenue_ws_adm_venueidhtml = ($urvenue_ws_adm_venueuvid) ? "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue ID:</div><div class='uvsvalue'>$urvenue_ws_adm_venueuvid</div></div>" : ""; // Axl UWS-7416
                // $urvenue_ws_adm_clientidhtml = ($urvenue_ws_adm_venueclientid) ? "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Client ID:</div><div class='uvsvalue'>$urvenue_ws_adm_venueclientid</div></div>" : ""; // Axl UWS-7416
                // $urvenue_ws_adm_serverhtml = ($urvenue_ws_adm_venueserver) ? "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>UrVenue Server:</div><div class='uvsvalue'>$urvenue_ws_adm_venueserver</div></div>" : ""; // Axl UWS-7416
                $urvenue_ws_adm_venueidhtml = ($urvenue_ws_adm_venueuvid) ? "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue ID:</div><div class='uvsvalue'>" . esc_html( $urvenue_ws_adm_venueuvid ) . "</div></div>" : ""; // Axl UWS-8151
                $urvenue_ws_adm_clientidhtml = ($urvenue_ws_adm_venueclientid) ? "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Client ID:</div><div class='uvsvalue'>" . esc_html( $urvenue_ws_adm_venueclientid ) . "</div></div>" : ""; // Axl UWS-8151
                $urvenue_ws_adm_serverhtml = ($urvenue_ws_adm_venueserver) ? "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>UrVenue Server:</div><div class='uvsvalue'>" . esc_html( $urvenue_ws_adm_venueserver ) . "</div></div>" : ""; // Axl UWS-8151

                // $urvenue_ws_venuealiasinput = "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Use Alias as Venue Name:</div><div class='uvsvalue'><div class='uvs-switch-ui'><button class='uvsjs-trigger-switch' type='button'><span class='uvs-lb-on'>Yes</span><span class='uvs-lb-off'>No</span></button><input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][venueforcealias]' value='' data-value-on='1' data-value-off=''></div></div></div>"; // Axl UWS-7416
                $urvenue_ws_venuealiasinput = "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Use Alias as Venue Name:</div><div class='uvsvalue'><div class='uvs-switch-ui'><button class='uvsjs-trigger-switch' type='button'><span class='uvs-lb-on'>Yes</span><span class='uvs-lb-off'>No</span></button><input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][venueforcealias]' value='' data-value-on='1' data-value-off=''></div></div></div>"; // Axl UWS-8151

                // $urvenue_ws_hideeventsinput = "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Hide Events:</div><div class='uvsvalue'><div class='uvs-switch-ui'><button class='uvsjs-trigger-switch' type='button'><span class='uvs-lb-on'>Yes</span><span class='uvs-lb-off'>No</span></button><input class='uvsjson' type='hidden' name='venues[$urvenue_ws_adm_venuewbcode][venuehideinevents]' value='' data-value-on='1' data-value-off=''></div></div></div>"; // Axl UWS-7416
                $urvenue_ws_hideeventsinput = "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Hide Events:</div><div class='uvsvalue'><div class='uvs-switch-ui'><button class='uvsjs-trigger-switch' type='button'><span class='uvs-lb-on'>Yes</span><span class='uvs-lb-off'>No</span></button><input class='uvsjson' type='hidden' name='venues[{$urvenue_ws_adm_wbcode_esc}][venuehideinevents]' value='' data-value-on='1' data-value-off=''></div></div></div>"; // Axl UWS-8151

                // $urvenue_ws_adm_venueshtml .= "<div class='uvs-admin-venueinf uvs-admin-venueinf-vc-VEN$urvenue_ws_adm_venueveaid'>$urvenue_ws_adm_venueforminfo<div class='uvs-infolist-item-img $urvenue_ws_adm_venuelogoclass' style='background-image: url($urvenue_ws_adm_venuelogo);'></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue KEY:</div><div class='uvsvalue'><strong>$urvenue_ws_adm_venuewbcode</strong></div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Name:</div><div class='uvsvalue'>$urvenue_ws_adm_venuename</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Name Alias:</div><div class='uvsvalue'><input type='text' name='venues[$urvenue_ws_adm_venuewbcode][venuealias]' value='' class='uvsjson'></div></div>{$urvenue_ws_venuealiasinput}{$urvenue_ws_hideeventsinput}<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Code:</div><div class='uvsvalue'>VEN$urvenue_ws_adm_venueveaid</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Manageentid:</div><div class='uvsvalue'>$urvenue_ws_adm_manageentid</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Providerid:</div><div class='uvsvalue'>$urvenue_ws_adm_providerid</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Resellerid:</div><div class='uvsvalue'>$urvenue_ws_adm_resellerid</div></div>" . $urvenue_ws_adm_venueidhtml . $urvenue_ws_adm_clientidhtml . $urvenue_ws_adm_serverhtml . "<div class='actions'><a class='uvsjs-triggervenueprimary $urvenue_ws_adm_venueisprimaryclass' href='javascript:;' data-isprimary='$urvenue_ws_adm_venueisprimary'>$urvenue_ws_adm_venueisprimarylabel</a><a class='uvsjs-removevenue' href='javascript:;'>Remove</a></div></div>"; // Axl UWS-7416
                $urvenue_ws_adm_venueshtml .= "<div class='uvs-admin-venueinf uvs-admin-venueinf-vc-VEN" . esc_attr( $urvenue_ws_adm_venueveaid ) . "'>{$urvenue_ws_adm_venueforminfo}<div class='uvs-infolist-item-img " . esc_attr( $urvenue_ws_adm_venuelogoclass ) . "' style='background-image: url(" . esc_url( $urvenue_ws_adm_venuelogo ) . ");'></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue KEY:</div><div class='uvsvalue'><strong>" . esc_html( $urvenue_ws_adm_venuewbcode ) . "</strong></div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Name:</div><div class='uvsvalue'>" . esc_html( $urvenue_ws_adm_venuename ) . "</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Name Alias:</div><div class='uvsvalue'><input type='text' name='venues[{$urvenue_ws_adm_wbcode_esc}][venuealias]' value='' class='uvsjson'></div></div>{$urvenue_ws_venuealiasinput}{$urvenue_ws_hideeventsinput}<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Code:</div><div class='uvsvalue'>VEN" . esc_html( $urvenue_ws_adm_venueveaid ) . "</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Manageentid:</div><div class='uvsvalue'>" . esc_html( $urvenue_ws_adm_manageentid ) . "</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Providerid:</div><div class='uvsvalue'>" . esc_html( $urvenue_ws_adm_providerid ) . "</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Resellerid:</div><div class='uvsvalue'>" . esc_html( $urvenue_ws_adm_resellerid ) . "</div></div>" . $urvenue_ws_adm_venueidhtml . $urvenue_ws_adm_clientidhtml . $urvenue_ws_adm_serverhtml . "<div class='actions'><a class='uvsjs-triggervenueprimary " . esc_attr( $urvenue_ws_adm_venueisprimaryclass ) . "' href='javascript:;' data-isprimary='" . esc_attr( $urvenue_ws_adm_venueisprimary ) . "'>" . esc_html( $urvenue_ws_adm_venueisprimarylabel ) . "</a><a class='uvsjs-removevenue' href='javascript:;'>Remove</a></div></div>"; // Axl UWS-8151

                if($urvenue_ws_adm_venuescounter >= 50)
                    break;
            }

            $urvenue_ws_adm_returnarray = array(
                "status" => "success",
                "venueshtml" => $urvenue_ws_adm_venueshtml,
            );
        }
        else{
            $urvenue_ws_adm_returnarray = array(
                "status" => "error",
                "error-msg" => "You need to add <strong>Venues</strong> to your account",
            );
        }
    }
}
else{
    $urvenue_ws_adm_returnarray = array(
        "status" => "error",
        "error-msg" => "Something went wrong while pulling the information",
    );
}

// @Axl
// $urvenue_ws_returnjson = json_encode($urvenue_ws_adm_returnarray);
$urvenue_ws_returnjson = wp_json_encode($urvenue_ws_adm_returnarray);
// @Axl End
header('Content-Type: application/json');
// echo($urvenue_ws_returnjson);
echo( $urvenue_ws_returnjson ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON API response encoded with wp_json_encode() // Axl UWS-7416