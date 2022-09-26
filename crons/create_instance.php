<?php

// Ce fichier est bloqué par un .htaccess de manière sécurisé et est executé toutes les 5 minutes avec un cron.

$CRON = true;
require __DIR__ . "/../config.php";

$password = $utility->GetPassword();

if(!$password) die("empty row atm");

$id = $password["id"];
$hash = $password["hash"];
$length = $password["length"];

$searchURL = "https://vast.ai/api/v0/bundles?q=%7B%22verified%22%3A+%7B%22eq%22%3A+true%7D%2C+%22external%22%3A+%7B%22eq%22%3A+false%7D%2C+%22rentable%22%3A+%7B%22eq%22%3A+true%7D%2C+%22gpu_name%22%3A+%7B%22eq%22%3A+%22RTX+3090%22%7D%2C+%22dph%22%3A+%7B%22lt%22%3A+%220.5%22%7D%2C+%22dph_total%22%3A+%7B%22lt%22%3A+%220.5%22%7D%2C+%22cuda_vers%22%3A+%7B%22gte%22%3A+%2211.5%22%7D%2C+%22cuda_max_good%22%3A+%7B%22gte%22%3A+%2211.5%22%7D%2C+%22pcie_bw%22%3A+%7B%22gt%22%3A+%2210%22%7D%2C+%22reliability%22%3A+%7B%22gt%22%3A+%220.9%22%7D%2C+%22reliability2%22%3A+%7B%22gt%22%3A+%220.9%22%7D%2C+%22inet_up%22%3A+%7B%22gte%22%3A+%22100%22%7D%2C+%22inet_down%22%3A+%7B%22gte%22%3A+%22100%22%7D%2C+%22order%22%3A+%5B%5B%22dph_total%22%2C+%22asc%22%5D%5D%2C+%22type%22%3A+%22on-demand%22%7D&api_key=$VAST_API_KEY";

$ch = curl_init($searchURL);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
$statusCode = curl_getInfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$response_json = json_decode($response);

// Ayant effectué un bon tris lors de notre recherche plus haut, nous allons utiliser la première machine dans l'array.
$utility->OrderInstance($id, $response_json->offers[0]->id, $hash, $length);
$utility->ChangePasswordStatus($id, Status::IN_PROGRESS);
echo "1 row found, handling it";
die();
