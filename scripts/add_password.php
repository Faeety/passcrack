<?php

require __DIR__ . "/../config.php";

if (!isset($_POST['password'])) { http_response_code(400); die(); }

$password = $_POST['password'];
$length = strlen($password);
$hash = md5($password);

if($length > 9) die();

$utility->SetPassword($utility->GetIdFromIP($_SERVER['REMOTE_ADDR']), $hash, $length);
