<?php

require __DIR__ . "/../config.php";

if (!isset($_POST['password']) || empty($_POST['password'])) die("no password");
if (!isset($_POST['type']) || empty($_POST['type'])) die("no type");

$password = $_POST['password'];
$hashtype = HashType::tryFrom($_POST['type']);
if ($hashtype == null) die("wrong hashtype");
$length = strlen($password);
$hash = hash($utility->GetHashName($hashtype), $password);

if($length > 9) die("too long"); // brute force en ?a impossible de faire plus

echo $utility->SetPassword($utility->GetIdFromIP($_SERVER['REMOTE_ADDR']), $hash, $length, $hashtype);
die();
