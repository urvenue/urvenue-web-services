<?php
if($uvs_libexits)
	include_once($uvs_path . "/system/uvs-admin-init.php");

$uvsve = isset($uvsve) ? $uvsve : $_REQUEST["uvsve"];
$uvsnv = isset($uvsnv) ? $uvsnv : $_REQUEST["uvsnv"];
$uvsvenueinfofeedurl = $uvs_admin_feeds["venueinfo"];
$uvsvenueinfofeedurl = str_replace("{params}", "ve" . $uvsve, $uvsvenueinfofeedurl);

$uvsvenueinfofeed = uvs_pullfeed($uvsvenueinfofeedurl);
$uvsvenueinfofeed = json_decode($uvsvenueinfofeed, true);

if(is_array($uvsvenueinfofeed) and is_array($uvsvenueinfofeed["venues"]) and is_array($uvsvenueinfofeed["venues"][0]) and ($uvsvenueinfofeed["venues"][0]["id"] == $uvsve)){
	$uvsvenueinfoarray = $uvsvenueinfofeed["venues"][0];
	$uvsvenuename = $uvsvenueinfoarray["name"];
	$uvsvenuealias = $uvsvenueinfo["venuealias"];
	$uvsvenueforcealias = $uvsvenueinfo["venueforcealias"];
	$uvsvenuehideinevents = $uvsvenueinfo["venuehideinevents"];
	$uvsvenueveaid = $uvsvenueinfoarray["id"];
	$uvsvenueuvid = $uvsvenueinfoarray["urvenueid"];
	$uvsvenueserver = $uvsvenueinfoarray["urvenueurl"];
	$uvsvenueclientid = $uvsvenueinfoarray["urclientid"];
	$uvsvenuewbcode = $uvsvenueinfoarray["wbcode"];
	$uvsvenuelogo = $uvsvenueinfoarray["logos"]["transpwhitebg"]["raw_url"];
	$uvsvenueisprimary = ($uvsnv == 0) ? 1 : 0;

	if(!$uvsvenuewbcode)
		$uvsvenuewbcode = str_replace(array(" ", "-"), array("", ""), $uvsvenuename);

	$uvsvenuelogoclass = (!$uvsvenuelogo) ? "noimg" : "";
	$uvsvenueforcealias_checked = ($uvsvenueforcealias) ? " checked":"";
	$uvsvenuehideinevents_checked = ($uvsvenuehideinevents) ? " checked":"";

	$uvsvenueforminfo = "<input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][wbcode]' value='$uvsvenuewbcode'>";
	$uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][venue-name]' value='$uvsvenuename'>";
	$uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][veaid]' value='$uvsvenueveaid'>";
	$uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][urvenueid]' value='$uvsvenueuvid'>";
	$uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][clientid]' value='$uvsvenueclientid'>";
	$uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][logourl]' value='$uvsvenuelogo'>";
	$uvsvenueforminfo .= "<input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][uvserver]' value='$uvsvenueserver'>";
	$uvsvenueforminfo .= "<input class='uvsjson venueprimary' type='hidden' name='venues[$uvsvenuewbcode][isprimary]' value='$uvsvenueisprimary'>";

	$uvsvenueisprimarylabel = ($uvsvenueisprimary) ? "Is Primary" : "Make Primary";
	$uvsvenueisprimaryclass = ($uvsvenueisprimary) ? "active" : "";

	$uvvenuealiasswitchclass = ($uvsvenueforcealias) ? "uvs-on" : "";
	$uvvenuealiasinput = "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Use Alias as Venue Name:</div><div class='uvsvalue'><div class='uvs-switch-ui $uvvenuealiasswitchclass'><button class='uvsjs-trigger-switch' type='button'><span class='uvs-lb-on'>Yes</span><span class='uvs-lb-off'>No</span></button><input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][venueforcealias]' value='$uvsvenueforcealias' data-value-on='1' data-value-off=''></div></div></div>";

	$uvhideeventsswitchclass = ($uvsvenuehideinevents) ? "uvs-on" : "";
	$uvhideeventsinput = "<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Use Alias as Venue Name:</div><div class='uvsvalue'><div class='uvs-switch-ui $uvhideeventsswitchclass'><button class='uvsjs-trigger-switch' type='button'><span class='uvs-lb-on'>Yes</span><span class='uvs-lb-off'>No</span></button><input class='uvsjson' type='hidden' name='venues[$uvsvenuewbcode][venuehideinevents]' value='$uvsvenuehideinevents' data-value-on='1' data-value-off=''></div></div></div>";
	
	// @egt [UWS-7264]
	$uvs_pendchanges_script = 'uvs_pendchanges = true;';
	
	wp_register_script('uvs_pendchanges', false, array(), null, true);
	wp_enqueue_script('uvs_pendchanges');
	wp_add_inline_script('uvs_pendchanges', "(function () { {$uvs_pendchanges_script} })();");

	$uvsvenueinfoinfohtml = "<div class='uvs-admin-venueinf uvs-admin-venueinf-veaid-$uvsvenueveaid'>$uvsvenueforminfo<div class='uvs-infolist-item-img $uvsvenuelogoclass' style='background-image: url($uvsvenuelogo);'></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Code:</div><div class='uvsvalue'><strong>$uvsvenuewbcode</strong></div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Name:</div><div class='uvsvalue'>$uvsvenuename</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Venue Name Alias:</div><div class='uvsvalue'><input type='text' name='venues[$uvsvenuekey][venuealias]' value='$uvsvenuealias' class='uvsjson'></div></div>{$uvvenuealiasinput}{$uvhideeventsinput}<div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>VEA Venue ID:</div><div class='uvsvalue'>$uvsvenueveaid</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>UrVenue Venue ID:</div><div class='uvsvalue'>$uvsvenueuvid</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>Client ID:</div><div class='uvsvalue'>$uvsvenueclientid</div></div><div class='uvs-infolist-item uvs-clearfix'><div class='uvsname'>UrVenue Server:</div><div class='uvsvalue'>$uvsvenueserver</div></div><div class='actions'><a class='uvsjs-triggervenueprimary $uvsvenueisprimaryclass' href='javascript:;' data-isprimary='$uvsvenueisprimary'>$uvsvenueisprimarylabel</a><a class='uvsjs-removevenue' href='javascript:;'>Remove</a></div></div>";
	
	// @Axl
	// echo $uvsvenueinfoinfohtml;
	echo wp_kses( $uvsvenueinfoinfohtml, uvs_allowed_admin_html() );
	// @Axl End
}
else {
	// @Axl
	// echo "<div class='uvs-admin-errormsg'>We did not find a venue with this VEA Venue ID: <strong>$uvsve</strong>, check your ID or contact support.</div>";
	echo "<div class='uvs-admin-errormsg'>We did not find a venue with this VEA Venue ID: <strong>" . esc_html( $uvsve ) . "</strong>, check your ID or contact support.</div>";
	// @Axl End
}