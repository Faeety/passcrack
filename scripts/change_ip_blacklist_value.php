<?php

$ADMIN = true;
require __DIR__ . "/../config.php";

$ipblacklistEnabled = $_POST["newval"];

$stmt = $conn->prepare("UPDATE setting SET value = :val WHERE name = 'IP_Blacklist'");
$stmt->bindParam(":val", $ipblacklistEnabled);
$stmt->execute();

die("done");