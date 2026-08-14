<?php
// print_server/get_ip.php
// Local IPv4 Address Auto-Detection API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

function getLocalIP() {
    // 1. hostname සහ gethostbynamel භාවිතයෙන් IP සෙවීම
    if (function_exists('gethostname') && function_exists('gethostbynamel')) {
        $ips = gethostbynamel(gethostname());
        if ($ips) {
            foreach ($ips as $i) {
                if ($i !== '127.0.0.1' && filter_var($i, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    return $i;
                }
            }
        }
    }
    // 2. gethostbyname භාවිතයෙන් IP සෙවීම
    if (function_exists('gethostname')) {
        $i = gethostbyname(gethostname());
        if ($i !== '127.0.0.1' && filter_var($i, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return $i;
        }
    }
    // 3. Server Address පරීක්ෂා කිරීම
    if (isset($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] !== '127.0.0.1' && $_SERVER['SERVER_ADDR'] !== '::1') {
        return $_SERVER['SERVER_ADDR'];
    }
    // 4. HTTP_HOST පරීක්ෂා කිරීම
    if (isset($_SERVER['HTTP_HOST'])) {
        $host = $_SERVER['HTTP_HOST'];
        $host = explode(':', $host)[0];
        if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && $host !== '127.0.0.1') {
            return $host;
        }
    }
    return '127.0.0.1';
}

echo json_encode([
    'status' => 'success',
    'ip'     => getLocalIP()
]);
?>
