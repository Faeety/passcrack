<?php

require __DIR__ . "/../config.php";

if (!isset($_POST['password']) || empty($_POST['password'])) die("no password");

$id = $_POST['password'];

if(hash_equals($ADMIN_PASSWORD, $_POST['password'])){
    $_SESSION['admin'] = true;
    die("good password");
}else{
    die("bad password");
}