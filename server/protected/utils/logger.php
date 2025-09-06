<?php

function log_sftp_error(string $user, string $remote_dir, string $file, string $error)
{
	$msg  = "[LOG SFTP] Error detectado:\n";
	$msg .= "Usuario: $user\n";
	$msg .= "Directorio remoto: $remote_dir\n";
	$msg .= "Archivo: $file\n";
	$msg .= "Mensaje: $error\n";
	error_log($msg);
}
