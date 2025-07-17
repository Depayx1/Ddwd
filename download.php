<?php
if (isset($_POST['url'], $_POST['format_id'])) {
    $url = escapeshellarg($_POST['url']);
    $format = escapeshellarg($_POST['format_id']);
    $output = "downloaded_video.mp4";

    $cmd = "yt-dlp -f $format -o '$output' $url";
    shell_exec($cmd);

    if (file_exists($output)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=\"" . basename($output) . "\"");
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($output));
        readfile($output);
        unlink($output);
        exit;
    } else {
        echo "Gagal mengunduh video. Coba lagi.";
    }
} else {
    echo "Data tidak lengkap.";
}
?>
