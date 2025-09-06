<?php
if (!isset($_SESSION['user_id'])) {
	http_response_code(403);
	exit;
}

$user_id = (int)$_GET['user_id'];
$file    = basename($_GET['file']);

require_once __DIR__ . '/sftp.php';

$sftp_server = "stay-app-sftp-1";
$sftp_user   = "user";
$sftp_pass   = "pass";

[$sftp, $error] = sftp_connect_server($sftp_server, $sftp_user, $sftp_pass);

if ($sftp) {
	$sftp_fd = intval($sftp);
	$remote_file = "/upload/$user_id/$file";
	$stream = @fopen("ssh2.sftp://$sftp_fd$remote_file", 'r');
	if ($stream) {
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . $file . '"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		fpassthru($stream);
		fclose($stream);
		exit;
	} else {
		http_response_code(404);
		exit;
	}
} else {
	http_response_code(500);
	exit;
}
