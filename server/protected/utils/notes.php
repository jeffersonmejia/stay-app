<?php
require_once __DIR__ . '/sftp.php';
require_once __DIR__ . '/logger.php';

function delete_note(PDO $conn, int $note_id, int $user_id)
{
	$userDir = __DIR__ . "/../ftp/$user_id";
	$files = glob("$userDir/{$note_id}.*");
	foreach ($files as $file) {
		unlink($file);
	}

	$stmt = $conn->prepare("DELETE FROM notes WHERE id = :id AND user_id = :user_id");
	$stmt->execute([':id' => $note_id, ':user_id' => $user_id]);
}

function create_note(PDO $conn, string $title, string $description, int $user_id, array $file): string
{
	// Inserta la nota en la base de datos
	$stmt = $conn->prepare("INSERT INTO notes (title, description, user_id) VALUES (:title, :description, :user_id)");
	$stmt->execute([
		':title' => $title,
		':description' => $description,
		':user_id' => $user_id
	]);
	$note_id = $conn->lastInsertId();

	// Si no hay archivo adjunto, retorna
	if (empty($file['name'])) return $note_id;

	$sftp_server = "stay-app-sftp-1";
	$sftp_user   = "user";
	$sftp_pass   = "pass";

	// Conectar al servidor SFTP una sola vez
	[$sftp, $error] = sftp_connect_server($sftp_server, $sftp_user, $sftp_pass);
	if (!$sftp) {
		log_sftp_error($sftp_user, "", "", $error);
		return $note_id;
	}

	// Preparar paths
	$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
	$tmp_file  = $file['tmp_name'];
	$remote_user_dir = "/upload/$user_id";

	// Asegurarse de que la carpeta del usuario exista
	sftp_ensure_dir($sftp, $remote_user_dir);

	$remote_file = "$remote_user_dir/{$note_id}.$extension";

	// Subir archivo
	if (!sftp_upload_file($sftp, $remote_file, $tmp_file)) {
		log_sftp_error($sftp_user, $remote_user_dir, "{$note_id}.$extension", "Error uploading file to SFTP");
	}

	return $note_id;
}


function get_notes(PDO $conn, int $user_id): array
{
	$stmt = $conn->prepare("SELECT * FROM notes WHERE user_id = :user_id ORDER BY id DESC");
	$stmt->execute([':user_id' => $user_id]);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
