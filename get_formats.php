<?php
if (!isset($_GET['url'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No URL']);
    exit;
}

$url = escapeshellarg($_GET['url']);
$cmd = "yt-dlp -j $url"; // Get JSON metadata

$output = shell_exec($cmd);
$data = json_decode($output, true);

$formats = [];

if (!empty($data['formats'])) {
    foreach ($data['formats'] as $f) {
        if (isset($f['format_id']) && strpos($f['ext'], 'mp4') !== false && isset($f['height'])) {
            $formats[] = [
                'format_id' => $f['format_id'],
                'ext' => $f['ext'],
                'resolution' => isset($f['height']) ? $f['height'] . 'p' : 'N/A',
                'filesize' => isset($f['filesize']) ? round($f['filesize'] / 1048576, 2) . ' MB' : 'Unknown',
            ];
        }
    }
}

header('Content-Type: application/json');
echo json_encode($formats);
