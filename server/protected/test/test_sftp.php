<?php
$sftp_server = 'stay-app-sftp-1';
$sftp_user   = 'user';
$sftp_pass   = 'pass';

$connection = ssh2_connect($sftp_server, 22);
ssh2_auth_password($connection, $sftp_user, $sftp_pass);
$sftp = ssh2_sftp($connection);

$remote_file = '/upload/test.txt';
file_put_contents("ssh2.sftp://$sftp$remote_file", "Hello SFTP from PHP");

if (file_exists("ssh2.sftp://$sftp$remote_file")) {
	echo "[SFTP] Test upload succeeded!\n";
} else {
	echo "[SFTP] Test upload failed\n";
}
