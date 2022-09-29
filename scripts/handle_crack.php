<?php

$API = true;
require __DIR__ . "/../config.php";

if (!isset($_POST['id']) || !isset($_POST['cracked'])) { http_response_code(400); die(); }

$id = $_POST['id'];
$result = $_POST['result'];
$cracked = $_POST['cracked'];

# utile pour autre chose
# SELECT password_status.name as name FROM password INNER JOIN password_status ON password.status = password_status.id WHERE status.id = :id

$stmt = $conn->prepare("SELECT id, pbkey FROM password WHERE id = :id");
$stmt->bindParam(":id", $id, PDO::PARAM_INT);
$stmt->execute();

$row = $stmt->fetch();

if (!$row) die("no row found with this id");
$pbkey = preg_replace('/-----(\w+) (\w+) (\w+)-----/', "\n-----$1 $2 $3-----\n", $row["pbkey"]);

if ($cracked == "false") {
    $utility->ChangePasswordStatus($row['id'], Status::IMPOSSIBLE);
}else{
    $passwordArray = explode(':', $result);
    $password = $enc->Encrypt($passwordArray[1], $pbkey);

    $utility->ChangePasswordStatus($row['id'], Status::CRACKED, $password);
}

die();
