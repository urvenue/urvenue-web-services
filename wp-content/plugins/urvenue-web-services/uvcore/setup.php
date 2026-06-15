<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
	@module: system/setup
	@author: UrVenue - aa
	@version: 1.0
*/


$urvenue_ws_uvcorepath = realpath(dirname(__FILE__));
$urvenue_ws_uvcoreurl = "//" . sanitize_text_field( wp_unslash( isset( $_SERVER["HTTP_HOST"] ) ? $_SERVER["HTTP_HOST"] : '' ) ) . sanitize_text_field( wp_unslash( isset( $_SERVER["REQUEST_URI"] ) ? $_SERVER["REQUEST_URI"] : '' ) );
$urvenue_ws_uvcoreurl = strtok($urvenue_ws_uvcoreurl, '?');
$urvenue_ws_uvcoreurl = str_replace("/setup.php", "", $urvenue_ws_uvcoreurl);

$urvenue_ws_uvpath = isset($path) ? $path : '';
$urvenue_ws_uvurl = isset($url) ? $url : '';
$urvenue_ws_uvlibrary = isset($library) ? $library : '';
$urvenue_ws_uvwrite = isset($write) ? $write : 0;

if ( isset( $_POST['urvenue_ws_setup_nonce'] ) ) {
	if ( ! current_user_can( 'manage_options' ) ||
	     ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['urvenue_ws_setup_nonce'] ) ), 'urvenue_ws_setup_action' ) ) {
		wp_die( 'Invalid security token.', '', array( 'response' => 403 ) );
	}
	$urvenue_ws_uvpath    = isset($path)    ? $path    : sanitize_text_field( wp_unslash( $_POST["path"]    ?? '' ) );
	$urvenue_ws_uvurl     = isset($url)     ? $url     : esc_url_raw( wp_unslash( $_POST["url"]              ?? '' ) );
	$urvenue_ws_uvlibrary = isset($library) ? $library : sanitize_text_field( wp_unslash( $_POST["library"] ?? '' ) );
	$urvenue_ws_uvwrite   = isset($write)   ? $write   : absint( $_POST["write"] ?? 0 );

	if ( isset( $_POST["manual"] ) && sanitize_text_field( wp_unslash( $_POST["manual"] ) ) ) {
		$urvenue_ws_uvslibinfojson = file_get_contents("uvcore.lib.json");

		$urvenue_ws_uvslib = json_decode($urvenue_ws_uvslibinfojson, true);

		if(is_array($urvenue_ws_uvslib["system"]) and ( !isset( $_REQUEST["nconf"] ) || !sanitize_text_field( wp_unslash( $_REQUEST["nconf"] ) ) )) // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Setup wizard config form; admin-only page
			header("location: $urvenue_ws_uvurl" . "/admin.php");
	}
}
	
if($urvenue_ws_uvwrite == 1){
	$urvenue_ws_uvs_lib = array(
		"system" => array(
			"path" => $urvenue_ws_uvpath,
			"url" => $urvenue_ws_uvurl,
			"library" => $urvenue_ws_uvlibrary
		)
	);
	$urvenue_ws_uvs_lib = wp_json_encode($urvenue_ws_uvs_lib);

	global $wp_filesystem;
	if ( ! function_exists( 'WP_Filesystem' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}
	if ( empty( $wp_filesystem ) ) {
		WP_Filesystem();
	}
	$wp_filesystem->put_contents( $urvenue_ws_uvlibrary, $urvenue_ws_uvs_lib );

	header("location: $urvenue_ws_uvurl" . "/admin.php");

	exit();
}

$urvenue_ws_uverrorshtml = "";
$urvenue_ws_uvmanualwritehtml = "";
$urvenue_ws_uvaddsubmitvarscript = "";
$urvenue_ws_uvpathok = false;

if($urvenue_ws_uvpath){
	if(file_exists($urvenue_ws_uvpath)){
		$urvenue_ws_uvpathclass = "uvs-setupfield-ok";
		$urvenue_ws_uvpathok = true;
	}
	else{
		$urvenue_ws_uvpathclass = "uvs-setupfield-nok";
		$urvenue_ws_uverrorshtml = "<div class='uvs-setup-error'><strong>UvCore Path</strong> is not an existing folder, make sure you use the correct directory.</div>";
	}
}

/*
	Permissions for files: 664 - 666(uv)
	Permissions for folder: 775 - 777(uv)
*/
global $wp_filesystem;
if ( ! function_exists( 'WP_Filesystem' ) ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
}
if ( empty( $wp_filesystem ) ) {
	WP_Filesystem();
}

if($urvenue_ws_uvlibrary){
	if(file_exists($urvenue_ws_uvlibrary)){
		if( $wp_filesystem->is_writable( $urvenue_ws_uvlibrary ) ){
			$urvenue_ws_uvlibraryclass = "uvs-setupfield-ok";
			if($urvenue_ws_uvpathok)
				$urvenue_ws_uvaddsubmitvarscript = "uvsetupsubmit = true;";
		}
		else{
			$urvenue_ws_uvlibraryclass = "uvs-setupfield-wng";
			$urvenue_ws_uverrorshtml .= "<div class='uvs-setup-warning'><strong>Library File</strong> is not writable, please try editing the files permissions or edit the file manually.</div>";

			if($urvenue_ws_uvpathok)
				$urvenue_ws_uvmanualwritehtml = "<button class='uvs-btn uvs-btn-s uvsjs-btn-setup-manually' type='button'>Write File Manually</button>";
		}
	}
	else if($urvenue_ws_uvpath and $wp_filesystem->is_writable( $urvenue_ws_uvpath )){
		if($urvenue_ws_uvpathok)
			$urvenue_ws_uvaddsubmitvarscript = "uvsetupsubmit = true;";
	}
	else{
		$urvenue_ws_uvlibraryclass = "uvs-setupfield-nok";
		$urvenue_ws_uverrorshtml .= "<div class='uvs-setup-error'><strong>Library File</strong> does not exist. Please create the file.</div>";
	}
}

if($urvenue_ws_uvurl){
	$urvenue_ws_uvs_lib = array(
		"system" => array(
			"path" => $urvenue_ws_uvpath,
			"url" => $urvenue_ws_uvurl,
			"library" => $urvenue_ws_uvlibrary
		)
	);
	
	$urvenue_ws_uvs_lib = wp_json_encode($urvenue_ws_uvs_lib);
	 End

	$urvenue_ws_uvurlscript = "";
}

$urvenue_ws_uvcorepath = ($urvenue_ws_uvpath) ? $urvenue_ws_uvpath : $urvenue_ws_uvcorepath;
$urvenue_ws_uvcoreurl = ($urvenue_ws_uvurl) ? $urvenue_ws_uvurl : $urvenue_ws_uvcoreurl;
$urvenue_ws_uvs_libexit = false;

if(file_exists("uvcore.lib.json") and !$urvenue_ws_uvpath){
	$urvenue_ws_uvslibinfojson = file_get_contents("uvcore.lib.json");

	$urvenue_ws_uvslib = json_decode($urvenue_ws_uvslibinfojson, true);

	if(is_array($urvenue_ws_uvslib["system"]) and ( !isset( $_REQUEST["nconf"] ) || !sanitize_text_field( wp_unslash( $_REQUEST["nconf"] ) ) )) // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Setup wizard config form; admin-only page
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

			wp_register_style('urvenue_ws_setup_styles', '', array(), $urvenue_ws_assetsversion);
			wp_enqueue_style('urvenue_ws_setup_styles');
			wp_add_inline_style('urvenue_ws_setup_styles', $uvwp_setup_css);

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
				<?php wp_nonce_field( 'urvenue_ws_setup_action', 'urvenue_ws_setup_nonce' ); ?>
				
				<div class="uvs-setupfields">
					<div class="uvs-setupfield <?php echo esc_attr( $urvenue_ws_uvpathclass ); ?> uvs-clearfix">
						<div><label for="path">UvCore Path</label></div>
						<div><input id="path" class="uvsjs-copytoinput" data-addafter="/uvcore.lib.json" data-target="#library" type="text" name="path" value="<?php echo esc_attr( $urvenue_ws_uvcorepath ); ?>"></div>
					</div>
					<div class="uvs-setupfield uvs-clearfix">
						<div><label for="url">UvCore URL</label></div>
						<div><input id="url" type="text" name="url" value="<?php echo esc_attr( $urvenue_ws_uvcoreurl ); ?>"></div>
					</div>
					<div class="uvs-setupfield <?php echo esc_attr( $urvenue_ws_uvlibraryclass ); ?> uvs-clearfix">
						<div><label for="library">Library File</label></div>
						<div><input id="library" type="text" name="library" value="<?php echo esc_attr( $urvenue_ws_uvcorepath ); ?>/uvcore.lib.json" readonly></div>
					</div>
				</div>
				
				<div class="uvs-setup-errors">
					<?php  ?>
					<?php echo wp_kses_post( $urvenue_ws_uverrorshtml ); ?>
					<?php  End ?>
				</div>
				
				<div class="uvs-setupbuttons">
					<?php  ?>
					<?php echo wp_kses_post( $urvenue_ws_uvmanualwritehtml ); ?>
					<?php  End ?>
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
	
	
	<?php  ?>
	<?php echo wp_kses_post( $urvenue_ws_uvurlscript ); ?>
	<?php  End ?>
</body>
</html>