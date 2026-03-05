<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
	@module: system/setup
	@author: UrVenue - aa
	@version: 1.0
*/


$uvs_uvcorepath = realpath(dirname(__FILE__));
$uvs_uvcoreurl = "//" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$uvs_uvcoreurl = strtok($uvs_uvcoreurl, '?');
$uvs_uvcoreurl = str_replace("/setup.php", "", $uvs_uvcoreurl);

$uvpath = isset($path) ? $path : $_REQUEST["path"];
$uvurl = isset($url) ? $url : $_REQUEST["url"];
$uvlibrary = isset($library) ? $library : $_REQUEST["library"];
$uvwrite = isset($write) ? $white : $_REQUEST["write"];


if($_REQUEST["manual"]){
	$uvslibinfojson = file_get_contents("uvcore.lib.json");
	
	$uvslib = json_decode($uvslibinfojson, true);
	
	if(is_array($uvslib["system"]) and (!$_REQUEST["nconf"]))
		header("location: $uvurl" . "/admin.php");
}
	
if($uvwrite == 1){
	$uvs_lib = array(
		"system" => array(
			"path" => $uvpath,
			"url" => $uvurl,
			"library" => $uvlibrary
		)
	);
	// @Axl
	// $uvs_lib = json_encode($uvs_lib);
	$uvs_lib = wp_json_encode($uvs_lib);
	// @Axl End

	$fp = fopen("$uvlibrary", "w+");
	fwrite($fp, $uvs_lib);
	fclose($fp);
	
	header("location: $uvurl" . "/admin.php");
	
	exit();
}

$uverrorshtml = "";
$uvmanualwritehtml = "";
$uvaddsubmitvarscript = "";
$uvpathok = false;

if($uvpath){
	if(file_exists($uvpath)){
		$uvpathclass = "uvs-setupfield-ok";
		$uvpathok = true;
	}
	else{
		$uvpathclass = "uvs-setupfield-nok";
		$uverrorshtml = "<div class='uvs-setup-error'><strong>UvCore Path</strong> is not an existing folder, make sure you use the correct directory.</div>";
	}	
}

/*
	Permissions for files: 664 - 666(uv)
	Permissions for folder: 775 - 777(uv)
*/
if($uvlibrary){
	if(file_exists($uvlibrary)){
		if(is_writable($uvlibrary)){
			$uvlibraryclass = "uvs-setupfield-ok";
			if($uvpathok)
				$uvaddsubmitvarscript = "uvsetupsubmit = true;";
		}
		else{
			$uvlibraryclass = "uvs-setupfield-wng";
			$uverrorshtml .= "<div class='uvs-setup-warning'><strong>Library File</strong> is not writable, please try editing the files permissions or edit the file manually.</div>";
			
			if($uvpathok)
				$uvmanualwritehtml = "<button class='uvs-btn uvs-btn-s uvsjs-btn-setup-manually' type='button'>Write File Manually</button>";
		}
	}
	else if($uvpath and is_writable($uvpath)){
		if($uvpathok)
			$uvaddsubmitvarscript = "uvsetupsubmit = true;";
	}
	else{
		$uvlibraryclass = "uvs-setupfield-nok";
		$uverrorshtml .= "<div class='uvs-setup-error'><strong>Library File</strong> does not exist. Please create the file.</div>";
	}	
}

if($uvurl){
	$uvs_lib = array(
		"system" => array(
			"path" => $uvpath,
			"url" => $uvurl,
			"library" => $uvlibrary
		)
	);
	// @Axl
	// $uvs_lib = json_encode($uvs_lib);
	$uvs_lib = wp_json_encode($uvs_lib);
	// @Axl End

	$uvurlscript = "";

	// @egt [UWS-7264]
	add_action('wp_footer', function () use ($uvurl, $uvs_lib, $uvaddsubmitvarscript) {
		echo "
			<script>
				var uvcoreinput = '$uvurl';
				var uvcorejsonlib = '$uvs_lib';
				$uvaddsubmitvarscript
			</script>
		";
	});
}

$uvs_uvcorepath = ($uvpath) ? $uvpath : $uvs_uvcorepath;
$uvs_uvcoreurl = ($uvurl) ? $uvurl : $uvs_uvcoreurl;
$uvs_libexit = false;

if(file_exists("uvcore.lib.json") and !$uvpath){
	$uvslibinfojson = file_get_contents("uvcore.lib.json");
	
	$uvslib = json_decode($uvslibinfojson, true);
	
	if(is_array($uvslib["system"]) and (!$_REQUEST["nconf"]))
		$uvs_libexit = true;
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="width=device-width" />
	<link rel="icon" type="image/png" href="assets/images/urvenueicon.png">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex,nofollow" />
	<title>UvCore | Setup</title>
	
	<?php
		// @egt [UWS-7264]
		add_action('setup_enqueue_scripts', function(){
			$uvbaseurl = plugin_dir_url( __FILE__ );

			$uvwp_setup_css = "
				.uvs-setupbox, .uvs-logo{display: none;}
				body{background-color: #fafafa;}
				.uvs-nostyleserror{
					display: block;
					font-size: 30px;
					position: absolute;
					width: 80%;
					top: 45vh;
					left: 10%;
					text-align: center;
				}
			";

			// wp_register_style('uvwp_setup_styles', '');
			wp_register_style('urvenue_ws_setup_styles', ''); // Axl UWS-7416
			// wp_enqueue_style('uvwp_setup_styles');
			wp_enqueue_style('urvenue_ws_setup_styles'); // Axl UWS-7416
			// wp_add_inline_style('uvwp_setup_styles', $uvwp_setup_css);
			wp_add_inline_style('urvenue_ws_setup_styles', $uvwp_setup_css); // Axl UWS-7416

			wp_enqueue_style('system-css', $uvbaseurl . 'assets/css/system.css', array(), null, 'all');
			wp_enqueue_style('setup-css', $uvbaseurl . 'assets/css/setup.css', array(), null, 'all');
			wp_enqueue_style('uwsicons-css', $uvbaseurl . 'assets/css/uwsicons.css', array(), null, 'all');

			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-validate', $uvbaseurl . 'assets/js/jquery.validate.min.js', array('jquery'), null, true);
			wp_enqueue_script('admin', $uvbaseurl . 'assets/js/admin.js', array('jquery', 'jquery-validate'), null, true);
			wp_enqueue_script('setup', $uvbaseurl . 'assets/js/setup.js', array('jquery', 'jquery-validate'), null, true);
		});
	?>
</head>
<body class="uvs-systempage">
	<div class="uvs-nostyleserror">
		<div>We could not include our files :(<br>Please, contact support@urvenue.com</div>
	</div>

	<div class="uvs-logo">
		<a href="https://urvenue.com/"><img src="assets/images/urvenuelogo-light.svg" alt="UrVenue"></a>
	</div>
	
	<div class="uvs-content">
		<div class="uvs-setupbox uvs-boxpanel">
			
			<?php if(!$uvs_libexit){ ?>
			<p>Welcome to UvCore. This is the initial configuration. Please, edit the information if necessary and click the button.</p>
			
			<form id="uvs-form-setup" method="post" action="setup.php">
				<input id="uvs-input-write" type="hidden" name="write" value="0">
				<input id="uvs-input-manuallib" type="hidden" name="manual" value="0">
				
				<div class="uvs-setupfields">
					<div class="uvs-setupfield <?php /* Old: echo $uvpathclass; */ echo esc_attr( $uvpathclass ); ?> uvs-clearfix">
						<div><label for="path">UvCore Path</label></div>
						<div><input id="path" class="uvsjs-copytoinput" data-addafter="/uvcore.lib.json" data-target="#library" type="text" name="path" value="<?php /* Old: echo $uvs_uvcorepath; */ echo esc_attr( $uvs_uvcorepath ); ?>"></div>
					</div>
					<div class="uvs-setupfield uvs-clearfix">
						<div><label for="url">UvCore URL</label></div>
						<div><input id="url" type="text" name="url" value="<?php /* Old: echo $uvs_uvcoreurl; */ echo esc_attr( $uvs_uvcoreurl ); ?>"></div>
					</div>
					<div class="uvs-setupfield <?php /* Old: echo $uvlibraryclass; */ echo esc_attr( $uvlibraryclass ); ?> uvs-clearfix">
						<div><label for="library">Library File</label></div>
						<div><input id="library" type="text" name="library" value="<?php /* Old: echo $uvs_uvcorepath; */ echo esc_attr( $uvs_uvcorepath ); ?>/uvcore.lib.json" readonly></div>
					</div>
				</div>
				
				<div class="uvs-setup-errors">
					<?php // @Axl ?>
					<?php /* echo $uverrorshtml; */ echo wp_kses_post( $uverrorshtml ); ?>
					<?php // @Axl End ?>
				</div>
				
				<div class="uvs-setupbuttons">
					<?php // @Axl ?>
					<?php /* echo $uvmanualwritehtml; */ echo wp_kses_post( $uvmanualwritehtml ); ?>
					<?php // @Axl End ?>
					<button class="uvs-btn uvs-btn-p" type="submit">Submit</button>
				</div>
			</form>	
			<?php } else{ ?>
				<p class="uvs-text-center">The library file is already configured.</p>
				
				<div class="uvs-text-center uvs-mt20">
					<p>Go to the Admin page of the current configuration:</p>
					<a class="uvs-btn uvs-btn-p" href="admin.php">Admin</a>
				</div>
				<div class="uvs-text-center uvs-mt40">
					<p>Make a new configuration.<br><strong>Warning:</strong> if you make a new configuration you will lose the current data in the library file.</p>
					<a class="uvs-btn uvs-btn-s" href="?nconf=1">New Configuration</a>
				</div>
			<?php } ?>
		</div>
	
	</div>
	
	
	<?php // @Axl ?>
	<?php /* echo $uvurlscript; */ echo wp_kses_post( $uvurlscript ); ?>
	<?php // @Axl End ?>
</body>
</html>