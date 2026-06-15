<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('URVENUE_WS_SECURITY_MAX_BODY', 1024 * 1024);
define('URVENUE_WS_SECURITY_BLOCK', true);

$urvenue_ws_patterns = array_values(array_unique([
    '/DBMS_PIPE/i',
    '/RECEIVE_MESSAGE/i',
    '/CHR\(\d+\)/i',
    '/\bwaitfor\s+delay\b/i',
    '/\bsleep\(/i',
    '/%7C%7C/i',
    '/\|\|/',
    '/\b(exec|system|shell_exec|passthru|popen|proc_open|pcntl_exec)\s*\(/i',
    '/phar:\/\//i',
    '/expect:\/\//i',
    '/data:text\/html/i',
    '/file:\/\//i',
    '/\bUNION\b[\s\S]*\bSELECT\b/i',
    '/INFORMATION_SCHEMA/i',
    '/DBMS_LOCK/i',
    '/venuecode=[^&]*(%7C|%27)/i',
    '/venuecode=[^&]*\|/i',
    '/%(?:C0|C1)%[0-9A-F]{2}/i',
    '/%[89ABab][0-9A-F]{2}/i',
    '/%25[0-9A-F]{2}/i',
    '/%00|%0A|%0D|%1F|%08/i',
    '/\|/',
    '/%7C/i',
    '/%27/i',
    '/venuecode=[^&]*\|.+\|/i',
    '/%25(?:[0-9A-F]{2}|[^&])/i',
    '/\d%25\d/i',
    '/\bSELECT\b.+\bFROM\b/i',
    '/\bINSERT\s+INTO\b/i',
    '/\bUPDATE\b.+\bSET\b/i',
    '/\bDELETE\s+FROM\b/i',
    '/\bDROP\s+TABLE\b/i',
    '/\bTRUNCATE\b/i',
    '/(--|%2D%2D|;--)/',
    '/%27|%22|%3B/',
    '/<script\b/i',
    '/<\/script>/i',
    '/onerror\s*=/i',
    '/onload\s*=/i',
    '/javascript:/i',
    '/document\.cookie/i',
    '/\.\.\/|\.\.\\\\/',
    '/etc\/passwd/i',
    '/\beval\s*\(/i',
    '/base64_decode\s*\(/i',
    '/gzuncompress\s*\(/i',
    '/http:\/\/169\.254\.169\.254/i',
    '/http:\/\/127\.0\.0\.1/i',
    '/http:\/\/localhost/i',
    '/<!DOCTYPE\s+DOCTYPE/i',
    '/<!DOCTYPE\s+/i',
    '/SYSTEM\s+"/i',
    '/ENTITY\s+/i',
    '/%2527/i',
    '/%3C|%3E/',
    '/sqlmap|acunetix|nikto|zaproxy|havij|whatweb/i',
    '/%u[0-9A-F]{4}/i',
    '/%25u[0-9A-F]{4}/i',
    '/[|].*%27|%27.*[|]/i',
    '/\%25\%25/i',
    '/%C0%[0-9A-F]{2}/i',
    '/%C1%[0-9A-F]{2}/i',
    '/%[C-Fc-f][0-9A-F]{2}/i',
    '/%C0/i',
    '/%C1/i',
    '/%25/i',
]));

function urvenue_ws_security_log($msg) {
    error_log('[UWS_SECURITY] ' . $msg);
}

function urvenue_ws_security_matches(array $patterns, $haystack, &$which = null) {
    if (!is_string($haystack) || $haystack === '') return false;
    foreach ($patterns as $p) {
        if (@preg_match($p, $haystack, $m)) {
            if (!empty($m)) { $which = $p; return true; }
            if ($m === null || $m === []) { $which = $p; return true; }
        }
    }
    return false;
}

function urvenue_ws_security_check_params_injection(){
    global $urvenue_ws_patterns;

    $max = URVENUE_WS_SECURITY_MAX_BODY;
    $bodyRaw = @file_get_contents('php://input', false, null, 0, $max + 1); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- raw request body stream, not a remote file
    if ($bodyRaw === false) $bodyRaw = '';
    $GLOBALS['URVENUE_WS_RAW_BODY'] = $bodyRaw;

    $param_parts = [];
    foreach ($_GET as $k => $v) $param_parts[] = $k . '=' . (is_array($v) ? implode(',', $v) : (string)$v); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Intentionally scanning raw request data for WAF/security pattern detection
    foreach ($_POST as $k => $v) $param_parts[] = $k . '=' . (is_array($v) ? implode(',', $v) : (string)$v); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Intentionally scanning raw POST data for WAF/security pattern detection
    $params_string = implode('&', $param_parts);

    $ct = sanitize_text_field( wp_unslash( $_SERVER['CONTENT_TYPE'] ?? ($_SERVER['HTTP_CONTENT_TYPE'] ?? '') ) );
    $body_to_check = $body_decoded = '';
    if (stripos($ct, 'application/json') !== false) {
        $data = @json_decode($bodyRaw, true);
        $body_to_check = $body_decoded = is_array($data) ? wp_json_encode($data) : $bodyRaw;
    } elseif (stripos($ct, 'application/x-www-form-urlencoded') !== false) {
        $body_to_check = $bodyRaw;
        $body_decoded  = urldecode($bodyRaw);
    } else {
        $body_to_check = $bodyRaw;
        $body_decoded  = urldecode($bodyRaw);
    }

    $combined_raw     = trim($params_string . ' ' . $body_to_check);
    $combined_decoded = trim($params_string . ' ' . $body_decoded);
    $query_raw        = sanitize_text_field( wp_unslash( $_SERVER['QUERY_STRING'] ?? '' ) );
    $query_decoded    = urldecode($query_raw);

    $haystacks = [
        'query_raw'       => $query_raw,
        'query_decoded'   => $query_decoded,
        'combined_raw'    => $combined_raw,
        'combined_decoded'=> $combined_decoded,
    ];
    foreach ($_GET as $k => $v) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Intentionally scanning raw GET data for WAF/security pattern detection
        $haystacks["GET:$k"] = is_array($v) ? implode(',', $v) : (string)$v;
    }

    foreach ($haystacks as $hv) {
        foreach ($urvenue_ws_patterns as $p) {
            if (@preg_match($p, $hv)) {
                http_response_code(403);
                exit('Forbidden');
            }
        }
    }
}
