<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}
if (isset($_GET['css'])) {
    $file = realpath(__DIR__ . '/../server/protected/css/' . basename($_GET['css']));
    if ($file && file_exists($file)) {
        header("Content-Type: text/css");
        readfile($file);
        exit;
    } else {
        http_response_code(404);
        exit;
    }
}

if (isset($_GET['js'])) {
    $file = realpath(__DIR__ . '/../server/protected/js/' . basename($_GET['js']));
    if ($file && file_exists($file)) {
        header("Content-Type: application/javascript");
        readfile($file);
        exit;
    } else {
        http_response_code(404);
        exit;
    }
}

if (isset($_GET['user_id'], $_GET['file'])) {
    require_once __DIR__ . '/../server/protected/utils/download.php';
    exit;
}

define('ACCESS_ALLOWED', true);
include "../server/db/db.php";
include "../server/protected/home.php";
