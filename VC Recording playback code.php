<?php
$secretKey = '4c1bd31839c654000ef0289ad8ba6da61bab53d5fa452ccb344f4b5e116aa581';

$token = $_GET['token'] ?? '';
if (!$token) {
    http_response_code(403);
    exit('Missing token');
}

list($base64Payload, $base64Sig) = explode('.', $token);
$payloadJson = base64_decode(strtr($base64Payload, '-_', '+/'));
$payload = json_decode($payloadJson, true);

// Verify signature
$expectedSig = hash_hmac('sha256', $base64Payload, $secretKey, true);
if (!hash_equals($expectedSig, base64_decode(strtr($base64Sig, '-_', '+/')))) {
    http_response_code(403);
    exit('Invalid token signature');
}

// Verify expiry
if ($payload['exp'] < time()) {
    http_response_code(403);
    exit('Link expired');
}

$filePath = $payload['file'];

// --- stream file as before ---
if (!file_exists($filePath)) {
    http_response_code(404);
    exit('File not found');
}

$size = filesize($filePath);
$length = $size;
$start = 0;
$end = $size - 1;
header("Content-Type: video/mp4");
header("Accept-Ranges: bytes");

if (isset($_SERVER['HTTP_RANGE'])) {
    [$param, $range] = explode('=', $_SERVER['HTTP_RANGE']);
    if ($param === 'bytes') {
        [$start, $end] = explode('-', $range);
        $start = intval($start);
        $end = $end ? intval($end) : ($size - 1);
        $length = $end - $start + 1;
        header("HTTP/1.1 206 Partial Content");
        header("Content-Range: bytes $start-$end/$size");
    }
}

header("Content-Length: $length");
$fp = fopen($filePath, 'rb');
fseek($fp, $start);
$buffer = 8192;
while (!feof($fp) && ftell($fp) <= $end) {
    echo fread($fp, $buffer);
    flush();
}
fclose($fp);
?>

