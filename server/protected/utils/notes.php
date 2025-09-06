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
	$stmt = $conn->prepare("INSERT INTO notes (title, description, user_id) VALUES (:title, :description, :user_id)");
	$stmt->execute([
		':title' => $title,
		':description' => $description,
		':user_id' => $user_id
	]);
	$note_id = $conn->lastInsertId();

	if (!empty($file['name'])) {
		$sftp_server = "stay-app-sftp-1";
		$sftp_user   = "user";
		$sftp_pass   = "pass";

		[$sftp, $error] = sftp_connect_server($sftp_server, $sftp_user, $sftp_pass);

		if (!$sftp) {
			log_sftp_error($sftp_user, "", "", $error);
			return $note_id;
		}

		$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
		$tmp_file  = $file['tmp_name'];

		$remote_user_dir = "/home/user/upload/$user_id";
		$remote_note_dir = "$remote_user_dir";
		sftp_ensure_dir($sftp, $remote_user_dir);
		sftp_ensure_dir($sftp, $remote_note_dir);

		$remote_file = "$remote_note_dir/{$note_id}.$extension";

		if (!sftp_upload_file($sftp, $remote_file, $tmp_file)) {
			log_sftp_error($sftp_user, $remote_note_dir, "{$note_id}.$extension", "Error al subir el archivo al SFTP");
		}
	}

	return $note_id;
}

function get_notes(PDO $conn, int $user_id): array
{
	$stmt = $conn->prepare("SELECT * FROM notes WHERE user_id = :user_id ORDER BY id DESC");
	$stmt->execute([':user_id' => $user_id]);
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
