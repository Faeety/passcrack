<?php

$API = true;
require __DIR__ . "/../config.php";

if (!isset($_POST['id']) || !isset($_POST['result'])) { http_response_code(400); die(); }

$id = $_POST['id'];
$result = $_POST['result'];

# utile pour autre chose
# SELECT password_status.name as name FROM password INNER JOIN password_status ON password.status = password_status.id WHERE status.id = :id

$stmt = $conn->prepare("SELECT id FROM password WHERE id = :id");
$stmt->bindParam(":id", $id, PDO::PARAM_INT);
$stmt->execute();

$row = $stmt->fetch();

if (!$row) die("no row found with this id");

if (!$result) {
    $utility->ChangePasswordStatus($row['id'], Status::IMPOSSIBLE);
}else{
    $passwordArray = explode(':', $result);
    $password = $passwordArray[1];

    $utility->ChangePasswordStatus($row['id'], Status::CRACKED, $password);
}

die();
