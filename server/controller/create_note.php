<?php
session_start();
include "../db/db_connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$title = $_POST['title'] ?? '';
	$description = $_POST['description'] ?? '';
	$user_id = $_SESSION['user_id'] ?? 1;

	if ($title && $description) {
		$stmt = $conn->prepare("INSERT INTO notes (title, description, user_id) VALUES (:title, :description, :user_id)");
		$stmt->execute([
			':title' => $title,
			':description' => $description,
			':user_id' => $user_id
		]);
		header("Location: home.php");
		exit;
	}
}
