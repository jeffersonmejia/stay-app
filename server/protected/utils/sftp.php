<?php

function sftp_connect_server(string $server, string $user, string $pass): array
{
	if (!extension_loaded('ssh2')) {
		return [null, 'Extension ssh2 no instalada'];
	}

	$connection = @ssh2_connect($server, 22);
	if (!$connection) return [null, 'No se pudo conectar al servidor SFTP'];

	if (!@ssh2_auth_password($connection, $user, $pass)) {
		return [null, 'Error de autenticación SFTP'];
	}

	$sftp = @ssh2_sftp($connection);
	if (!$sftp) return [null, 'No se pudo inicializar SFTP'];

	return [$sftp, ''];
}

function sftp_ensure_dir($sftp, string $path): bool
{
	if (!is_resource($sftp)) return false;

	$sftp_fd = intval($sftp);
	$sftp_path = "ssh2.sftp://$sftp_fd$path";

	if (!@file_exists($sftp_path)) {
		return @mkdir($sftp_path, 0777, true);
	}

	return true;
}

function sftp_upload_file($sftp, string $remote_file, string $local_file): bool
{
	if (!is_resource($sftp)) return false;

	$sftp_fd = intval($sftp);
	$remote_stream = @fopen("ssh2.sftp://$sftp_fd$remote_file", 'w');
	$local_stream  = @fopen($local_file, 'r');

	if (!$remote_stream || !$local_stream) {
		if ($remote_stream) fclose($remote_stream);
		if ($local_stream) fclose($local_stream);
		return false;
	}

	while (!feof($local_stream)) {
		fwrite($remote_stream, fread($local_stream, 8192));
	}

	fclose($local_stream);
	fclose($remote_stream);

	return true;
}

function sftp_file_exists($sftp, string $path): bool
{
	if (!is_resource($sftp)) return false;

	$sftp_fd = intval($sftp);
	return @file_exists("ssh2.sftp://$sftp_fd$path");
}

function sftp_delete_file($sftp, string $remote_file): bool
{
	if (!is_resource($sftp)) return false;

	$sftp_fd = intval($sftp);
	return @unlink("ssh2.sftp://$sftp_fd$remote_file");
}
