<?php
if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

$urvenue_ws_coredir = realpath(__DIR__);
$urvenue_ws_assetsversion = "1.2.6";

$urvenue_ws_uvurlpath = wp_parse_url(sanitize_text_field(wp_unslash(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '')), PHP_URL_PATH);

if (isset($_REQUEST["uvwpdeleteuvcorelib"]) and sanitize_text_field(wp_unslash($_REQUEST["uvwpdeleteuvcorelib"]))) // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin debug utility, no state change beyond option delete
	delete_option("urvenue_ws_uvcore_lib");

//Check if is wordpress
if (function_exists('get_option')) {//is wordpress
	$urvenue_ws_path = $urvenue_ws_corepath;
	$urvenue_ws_libexits = true;
} else if (file_exists("$urvenue_ws_coredir/uvcore.lib.json")) {
	global $wp_filesystem;
	if ( ! function_exists( 'WP_Filesystem' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}
	if ( empty( $wp_filesystem ) ) {
		WP_Filesystem();
	}
	$urvenue_ws_uvlibinfojson = $wp_filesystem->get_contents("$urvenue_ws_coredir/uvcore.lib.json");
	$urvenue_ws_uvlib = json_decode($urvenue_ws_uvlibinfojson, true);

	if (is_array($urvenue_ws_uvlib["system"])) {
		$urvenue_ws_libexits = true;
		$urvenue_ws_path = $urvenue_ws_uvlib["system"]["path"];
	}
}
if (!isset($urvenue_ws_path) or !$urvenue_ws_path)
	$urvenue_ws_path = $urvenue_ws_corepath;
if (!isset($urvenue_ws_uvs_path) or !$urvenue_ws_uvs_path)
	$urvenue_ws_uvs_path = $urvenue_ws_corepath;

if ($urvenue_ws_path) {
	include_once($urvenue_ws_path . "/libs/uv-defaults-lib.php");
	include_once($urvenue_ws_path . "/libs/ui-lib.php");
	include_once($urvenue_ws_path . "/includes/uvcore-hooks.php");
	include_once($urvenue_ws_path . "/includes/uvcore-functions.php");
	include_once($urvenue_ws_path . "/includes/lang-functions.php");
	include_once($urvenue_ws_path . "/includes/events-functions.php");
	include_once($urvenue_ws_path . "/includes/map-functions.php");
	include_once($urvenue_ws_path . "/includes/venues-functions.php");
	include_once($urvenue_ws_path . "/includes/inventory-functions.php");
	include_once($urvenue_ws_path . "/includes/reservations-functions.php");
	include_once($urvenue_ws_path . "/includes/packages-functions.php");

	$urvenue_ws_feeds_path = $urvenue_ws_uvs_path . "/uvfeeds";

	if (!urvenue_ws_is_wordpress())
		$urvenue_ws_feeds_debug = (isset($_REQUEST["uvdbg"]) and sanitize_text_field(wp_unslash($_REQUEST["uvdbg"])) and (sanitize_text_field(wp_unslash($_REQUEST["uvdbg"])) == gmdate("j"))) ? 1 : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only debug flag for non-WordPress context, no state change
	else
		$urvenue_ws_feeds_debug = 0;

	$urvenue_ws_core_lib = urvenue_ws_get_core_library();
	$urvenue_ws_url = $urvenue_ws_core_lib["system"]["url"];
	$urvenue_ws_lib_path = $urvenue_ws_core_lib["system"]["library"];
	$urvenue_ws_today = urvenue_ws_get_today();

	include_once($urvenue_ws_path . "/libs/uv-feeds-lib.php");
	include_once($urvenue_ws_path . "/libs/uv-proxy-lib.php");
	include_once($urvenue_ws_path . "/includes/uvcore-notifications.php");
	include_once($urvenue_ws_path . "/includes/uvcore-feeds.php");

	if (function_exists('get_option') && $urvenue_ws_uvurlpath == '/apis/uvclearcache/') {
		if (FALSE === get_option('urvenue_ws_cacheword') && FALSE === update_option('urvenue_ws_cacheword', FALSE))
			add_option('urvenue_ws_cacheword', '');

		include_once($urvenue_ws_path . "/includes/uvcore-cleancache.php");
	}
} else if ($uv_isproxy)
	echo ("UVError 02-001: UV Core Init, uvcore path not found<br> ");