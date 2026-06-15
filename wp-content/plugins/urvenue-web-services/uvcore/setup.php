<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
	@module: system/setup
	@author: UrVenue - aa
	@version: 1.0
*/


// $uvs_uvcorepath = realpath(dirname(__FILE__));
$urvenue_ws_uvcorepath = realpath(dirname(__FILE__)); // Axl UWS-7416
// $uvs_uvcoreurl = "//" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; // Axl UWS-7416
// $uvs_uvcoreurl = "//" . sanitize_text_field( wp_unslash( $_SERVER["HTTP_HOST"] ) ) . sanitize_text_field( wp_unslash( $_SERVER["REQUEST_URI"] ) ); // Axl UWS-7418
// $uvs_uvcoreurl = "//" . sanitize_text_field( wp_unslash( isset( $_SERVER["HTTP_HOST"] ) ? $_SERVER["HTTP_HOST"] : '' ) ) . sanitize_text_field( wp_unslash( isset( $_SERVER["REQUEST_URI"] ) ? $_SERVER["REQUEST_URI"] : '' ) ); // Axl UWS-7418
$urvenue_ws_uvcoreurl = "//" . sanitize_text_field( wp_unslash( isset( $_SERVER["HTTP_HOST"] ) ? $_SERVER["HTTP_HOST"] : '' ) ) . sanitize_text_field( wp_unslash( isset( $_SERVER["REQUEST_URI"] ) ? $_SERVER["REQUEST_URI"] : '' ) ); // Axl UWS-7416
$urvenue_ws_uvcoreurl = strtok($urvenue_ws_uvcoreurl, '?');
$urvenue_ws_uvcoreurl = str_replace("/setup.php", "", $urvenue_ws_uvcoreurl);

// $uvpath = isset($path) ? $path : $_REQUEST["path"]; // Axl UWS-7416
// $uvpath = isset($path) ? $path : sanitize_text_field( wp_unslash( $_REQUEST["path"] ?? '' ) ); // Axl UWS-7418
// $urvenue_ws_uvpath = isset($path) ? $path : sanitize_text_field( wp_unslash( $_REQUEST["path"] ?? '' ) ); // Axl UWS-7416
// $urvenue_ws_uvpath = isset($path) ? $path : sanitize_text_field( wp_unslash( $_REQUEST["path"] ?? '' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Setup wizard config form; admin-only page // Axl UWS-7416
$urvenue_ws_uvpath = isset($path) ? $path : ''; // Axl UWS-8152
// $uvurl = isset($url) ? $url : $_REQUEST["url"]; // Axl UWS-7416
// $uvurl = isset($url) ? $url : esc_url_raw( wp_unslash( $_REQUEST["url"] ?? '' ) ); // Axl UWS-7418
// $urvenue_ws_uvurl = isset($url) ? $url : esc_url_raw( wp_unslash( $_REQUEST["url"] ?? '' ) ); // Axl UWS-7416
// $urvenue_ws_uvurl = isset($url) ? $url : esc_url_raw( wp_unslash( $_REQUEST["url"] ?? '' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Setup wizard config form; admin-only page // Axl UWS-7416
$urvenue_ws_uvurl = isset($url) ? $url : ''; // Axl UWS-8152
// $uvlibrary = isset($library) ? $library : $_REQUEST["library"]; // Axl UWS-7416
// $uvlibrary = isset($library) ? $library : sanitize_text_field( wp_unslash( $_REQUEST["library"] ?? '' ) ); // Axl UWS-7418
// $urvenue_ws_uvlibrary = isset($library) ? $library : sanitize_text_field( wp_unslash( $_REQUEST["library"] ?? '' ) ); // Axl UWS-7416
// $urvenue_ws_uvlibrary = isset($library) ? $library : sanitize_text_field( wp_unslash( $_REQUEST["library"] ?? '' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Setup wizard config form; admin-only page // Axl UWS-7416
$urvenue_ws_uvlibrary = isset($library) ? $library : ''; // Axl UWS-8152
// $uvwrite = isset($write) ? $white : $_REQUEST["write"]; // Axl UWS-7416
// $uvwrite = isset($write) ? $write : absint( $_REQUEST["write"] ?? 0 ); // Axl UWS-7418
// $urvenue_ws_uvwrite = isset($write) ? $write : absint( $_REQUEST["write"] ?? 0 ); // Axl UWS-7416
// $urvenue_ws_uvwrite = isset($write) ? $write : absint( $_REQUEST["write"] ?? 0 ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Setup wizard config form; admin-only page // Axl UWS-7416
$urvenue_ws_uvwrite = isset($write) ? $write : 0; // Axl UWS-8152

if ( isset( $_POST['urvenue_ws_setup_nonce'] ) ) { // Axl UWS-8152
	if ( ! current_user_can( 'manage_options' ) ||
	     ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['urvenue_ws_setup_nonce'] ) ), 'urvenue_ws_setup_action' ) ) { // Axl UWS-8152
		wp_die( 'Invalid security token.', '', array( 'response' => 403 ) ); // Axl UWS-8152
	} // Axl UWS-8152
	$urvenue_ws_uvpath    = isset($path)    ? $path    : sanitize_text_field( wp_unslash( $_POST["path"]    ?? '' ) ); // Axl UWS-8152
	$urvenue_ws_uvurl     = isset($url)     ? $url     : esc_url_raw( wp_unslash( $_POST["url"]              ?? '' ) ); // Axl UWS-8152
	$urvenue_ws_uvlibrary = isset($library) ? $library : sanitize_text_field( wp_unslash( $_POST["library"] ?? '' ) ); // Axl UWS-8152
	$urvenue_ws_uvwrite   = isset($write)   ? $write   : absint( $_POST["write"] ?? 0 ); // Axl UWS-8152

	// if($_REQUEST["manual"]){ // Axl UWS-7416
	// if( isset( $_REQUEST["manual"] ) && sanitize_text_field( wp_unslash( $_REQUEST["manual"] ) ) ){ // Axl UWS-7418
	// if( isset( $_REQUEST["manual"] ) && sanitize_text_field( wp_unslash( $_REQUEST["manual"] ) ) ){ // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Setup wizard config form; admin-only page // Axl UWS-7416
	if ( isset( $_POST["manual"] ) && sanitize_text_field( wp_unslash( $_POST["manual"] ) ) ) { // Axl UWS-8152
		// $uvslibinfojson = file_get_contents("uvcore.lib.json");
		$urvenue_ws_uvslibinfojson = file_get_contents("uvcore.lib.json"); // Axl UWS-7416

		// $uvslib = json_decode($uvslibinfojson, true);
		$urvenue_ws_uvslib = json_decode($urvenue_ws_uvslibinfojson, true); // Axl UWS-7416

		// if(is_array($uvslib["system"]) and (!$_REQUEST["nconf"])) // Axl UWS-7416
		// if(is_array($urvenue_ws_uvslib["system"]) and ( !isset( $_REQUEST["nconf"] ) || !sanitize_text_field( wp_unslash( $_REQUEST["nconf"] ) ) )) // Axl UWS-7416
		if(is_array($urvenue_ws_uvslib["system"]) and ( !isset( $_REQUEST["nconf"] ) || !sanitize_text_field( wp_unslash( $_REQUEST["nconf"] ) ) )) // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Setup wizard config form; admin-only page // Axl UWS-7416
			header("location: $urvenue_ws_uvurl" . "/admin.php");
	}
} // Axl UWS-8152
	
if($urvenue_ws_uvwrite == 1){
	// $uvs_lib = array(
	$urvenue_ws_uvs_lib = array( // Axl UWS-7416
		"system" => array(
			"path" => $urvenue_ws_uvpath,
			"url" => $urvenue_ws_uvurl,
			"library" => $urvenue_ws_uvlibrary
		)
	);
	// @Axl
	// $uvs_lib = json_encode($uvs_lib);
	$urvenue_ws_uvs_lib = wp_json_encode($urvenue_ws_uvs_lib); // Axl UWS-7416
	// @Axl End

	// $fp = fopen("$uvlibrary", "w+");
	// $urvenue_ws_fp = fopen("$urvenue_ws_uvlibrary", "w+"); // Axl UWS-7416
	// fwrite($urvenue_ws_fp, $urvenue_ws_uvs_lib); // Axl UWS-7416
	// fclose($urvenue_ws_fp); // Axl UWS-7416
	global $wp_filesystem;
	if ( ! function_exists( 'WP_Filesystem' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}
	if ( empty( $wp_filesystem ) ) {
		WP_Filesystem();
	}
	$wp_filesystem->put_contents( $urvenue_ws_uvlibrary, $urvenue_ws_uvs_lib ); // Axl UWS-7416

	header("location: $urvenue_ws_uvurl" . "/admin.php");

	exit();
}

// $uverrorshtml = "";
$urvenue_ws_uverrorshtml = ""; // Axl UWS-7416
// $uvmanualwritehtml = "";
$urvenue_ws_uvmanualwritehtml = ""; // Axl UWS-7416
// $uvaddsubmitvarscript = "";
$urvenue_ws_uvaddsubmitvarscript = ""; // Axl UWS-7416
// $uvpathok = false;
$urvenue_ws_uvpathok = false; // Axl UWS-7416

if($urvenue_ws_uvpath){
	if(file_exists($urvenue_ws_uvpath)){
		// $uvpathclass = "uvs-setupfield-ok";
		$urvenue_ws_uvpathclass = "uvs-setupfield-ok"; // Axl UWS-7416
		$urvenue_ws_uvpathok = true;
	}
	else{
		// $uvpathclass = "uvs-setupfield-nok";
		$urvenue_ws_uvpathclass = "uvs-setupfield-nok"; // Axl UWS-7416
		$urvenue_ws_uverrorshtml = "<div class='uvs-setup-error'><strong>UvCore Path</strong> is not an existing folder, make sure you use the correct directory.</div>";
	}
}

/*
	Permissions for files: 664 - 666(uv)
	Permissions for folder: 775 - 777(uv)
*/
global $wp_filesystem; // Axl UWS-7416
if ( ! function_exists( 'WP_Filesystem' ) ) { // Axl UWS-7416
	require_once ABSPATH . 'wp-admin/includes/file.php'; // Axl UWS-7416
} // Axl UWS-7416
if ( empty( $wp_filesystem ) ) { // Axl UWS-7416
	WP_Filesystem(); // Axl UWS-7416
} // Axl UWS-7416

if($urvenue_ws_uvlibrary){
	if(file_exists($urvenue_ws_uvlibrary)){
		// if(is_writable($urvenue_ws_uvlibrary)){ // Axl UWS-7416
		if( $wp_filesystem->is_writable( $urvenue_ws_uvlibrary ) ){ // Axl UWS-7416
			// $uvlibraryclass = "uvs-setupfield-ok";
			$urvenue_ws_uvlibraryclass = "uvs-setupfield-ok"; // Axl UWS-7416
			if($urvenue_ws_uvpathok)
				$urvenue_ws_uvaddsubmitvarscript = "uvsetupsubmit = true;";
		}
		else{
			// $uvlibraryclass = "uvs-setupfield-wng";
			$urvenue_ws_uvlibraryclass = "uvs-setupfield-wng"; // Axl UWS-7416
			$urvenue_ws_uverrorshtml .= "<div class='uvs-setup-warning'><strong>Library File</strong> is not writable, please try editing the files permissions or edit the file manually.</div>";

			if($urvenue_ws_uvpathok)
				$urvenue_ws_uvmanualwritehtml = "<button class='uvs-btn uvs-btn-s uvsjs-btn-setup-manually' type='button'>Write File Manually</button>";
		}
	}
	// else if($urvenue_ws_uvpath and is_writable($urvenue_ws_uvpath)){ // Axl UWS-7416
	else if($urvenue_ws_uvpath and $wp_filesystem->is_writable( $urvenue_ws_uvpath )){ // Axl UWS-7416
		if($urvenue_ws_uvpathok)
			$urvenue_ws_uvaddsubmitvarscript = "uvsetupsubmit = true;";
	}
	else{
		// $uvlibraryclass = "uvs-setupfield-nok";
		$urvenue_ws_uvlibraryclass = "uvs-setupfield-nok"; // Axl UWS-7416
		$urvenue_ws_uverrorshtml .= "<div class='uvs-setup-error'><strong>Library File</strong> does not exist. Please create the file.</div>";
	}
}

if($urvenue_ws_uvurl){
	// $uvs_lib = array(
	$urvenue_ws_uvs_lib = array( // Axl UWS-7416
		"system" => array(
			"path" => $urvenue_ws_uvpath,
			"url" => $urvenue_ws_uvurl,
			"library" => $urvenue_ws_uvlibrary
		)
	);
	// @Axl
	// $uvs_lib = json_encode($uvs_lib);
	$urvenue_ws_uvs_lib = wp_json_encode($urvenue_ws_uvs_lib); // Axl UWS-7416
	// @Axl End

	// $uvurlscript = "";
	$urvenue_ws_uvurlscript = ""; // Axl UWS-7416
}

// $uvs_uvcorepath = ($uvpath) ? $uvpath : $uvs_uvcorepath;
$urvenue_ws_uvcorepath = ($urvenue_ws_uvpath) ? $urvenue_ws_uvpath : $urvenue_ws_uvcorepath; // Axl UWS-7416
// $uvs_uvcoreurl = ($uvurl) ? $uvurl : $uvs_uvcoreurl;
$urvenue_ws_uvcoreurl = ($urvenue_ws_uvurl) ? $urvenue_ws_uvurl : $urvenue_ws_uvcoreurl; // Axl UWS-7416
// $uvs_libexit = false;
$urvenue_ws_uvs_libexit = false; // Axl UWS-7416

if(file_exists("uvcore.lib.json") and !$urvenue_ws_uvpath){
	// $uvslibinfojson = file_get_contents("uvcore.lib.json");
	$urvenue_ws_uvslibinfojson = file_get_contents("uvcore.lib.json"); // Axl UWS-7416

	// $uvslib = json_decode($uvslibinfojson, true);
	$urvenue_ws_uvslib = json_decode($urvenue_ws_uvslibinfojson, true); // Axl UWS-7416

	// if(is_array($uvslib["system"]) and (!$_REQUEST["nconf"])) // Axl UWS-7416
	// if(is_array($urvenue_ws_uvslib["system"]) and ( !isset( $_REQUEST["nconf"] ) || !sanitize_text_field( wp_unslash( $_REQUEST["nconf"] ) ) )) // Axl UWS-7416
	if(is_array($urvenue_ws_uvslib["system"]) and ( !isset( $_REQUEST["nconf"] ) || !sanitize_text_field( wp_unslash( $_REQUEST["nconf"] ) ) )) // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Setup wizard config form; admin-only page // Axl UWS-7416
		$urvenue_ws_uvs_libexit = true;
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
			global $urvenue_ws_assetsversion, $urvenue_ws_uvurl, $urvenue_ws_uvs_lib, $urvenue_ws_uvaddsubmitvarscript;

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
			wp_register_style('urvenue_ws_setup_styles', '', array(), $urvenue_ws_assetsversion); // Axl UWS-7416
			// wp_enqueue_style('uvwp_setup_styles');
			wp_enqueue_style('urvenue_ws_setup_styles'); // Axl UWS-7416
			// wp_add_inline_style('uvwp_setup_styles', $uvwp_setup_css);
			wp_add_inline_style('urvenue_ws_setup_styles', $uvwp_setup_css); // Axl UWS-7416

			wp_enqueue_style('urvenue-ws-system-css', $uvbaseurl . 'assets/css/system.css', array(), $urvenue_ws_assetsversion, 'all');
			wp_enqueue_style('setup-css', $uvbaseurl . 'assets/css/setup.css', array(), $urvenue_ws_assetsversion, 'all');
			wp_enqueue_style('urvenue-ws-icons-css', $uvbaseurl . 'assets/css/uwsicons.css', array(), $urvenue_ws_assetsversion, 'all');

			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-validate', $uvbaseurl . 'assets/js/jquery.validate.min.js', array('jquery'), $urvenue_ws_assetsversion, true);
			wp_enqueue_script('urvenue-ws-admin-scripts', $uvbaseurl . 'assets/js/admin.js', array('jquery', 'jquery-validate'), $urvenue_ws_assetsversion, true);
			wp_enqueue_script('setup', $uvbaseurl . 'assets/js/setup.js', array('jquery', 'jquery-validate'), $urvenue_ws_assetsversion, true);

			if ( $urvenue_ws_uvurl ) {
				$urvenue_ws_setup_inline = "var uvcoreinput = '" . esc_js( esc_url( $urvenue_ws_uvurl ) ) . "';\n";
				$urvenue_ws_setup_inline .= "var uvcorejsonlib = '" . esc_js( $urvenue_ws_uvs_lib ) . "';\n";
				$urvenue_ws_setup_inline .= $urvenue_ws_uvaddsubmitvarscript . "\n";
				wp_add_inline_script('setup', $urvenue_ws_setup_inline, 'before');
			}
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
			
			<?php if(!$urvenue_ws_uvs_libexit){ ?>
			<p>Welcome to UvCore. This is the initial configuration. Please, edit the information if necessary and click the button.</p>
			
			<form id="uvs-form-setup" method="post" action="setup.php">
				<input id="uvs-input-write" type="hidden" name="write" value="0">
				<input id="uvs-input-manuallib" type="hidden" name="manual" value="0">
				<?php wp_nonce_field( 'urvenue_ws_setup_action', 'urvenue_ws_setup_nonce' ); // Axl UWS-8152 ?>
				
				<div class="uvs-setupfields">
					<div class="uvs-setupfield <?php /* Old: echo $uvpathclass; */ echo esc_attr( $urvenue_ws_uvpathclass ); ?> uvs-clearfix">
						<div><label for="path">UvCore Path</label></div>
						<div><input id="path" class="uvsjs-copytoinput" data-addafter="/uvcore.lib.json" data-target="#library" type="text" name="path" value="<?php /* Old: echo $uvs_uvcorepath; */ echo esc_attr( $urvenue_ws_uvcorepath ); ?>"></div>
					</div>
					<div class="uvs-setupfield uvs-clearfix">
						<div><label for="url">UvCore URL</label></div>
						<div><input id="url" type="text" name="url" value="<?php /* Old: echo $uvs_uvcoreurl; */ echo esc_attr( $urvenue_ws_uvcoreurl ); ?>"></div>
					</div>
					<div class="uvs-setupfield <?php /* Old: echo $uvlibraryclass; */ echo esc_attr( $urvenue_ws_uvlibraryclass ); ?> uvs-clearfix">
						<div><label for="library">Library File</label></div>
						<div><input id="library" type="text" name="library" value="<?php /* Old: echo $uvs_uvcorepath; */ echo esc_attr( $urvenue_ws_uvcorepath ); ?>/uvcore.lib.json" readonly></div>
					</div>
				</div>
				
				<div class="uvs-setup-errors">
					<?php // @Axl ?>
					<?php /* echo $uverrorshtml; */ echo wp_kses_post( $urvenue_ws_uverrorshtml ); ?>
					<?php // @Axl End ?>
				</div>
				
				<div class="uvs-setupbuttons">
					<?php // @Axl ?>
					<?php /* echo $uvmanualwritehtml; */ echo wp_kses_post( $urvenue_ws_uvmanualwritehtml ); ?>
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
	<?php /* echo $uvurlscript; */ echo wp_kses_post( $urvenue_ws_uvurlscript ); ?>
	<?php // @Axl End ?>
</body>
</html>