<?php
declare(strict_types=1);

require __DIR__ . "/Status.php";

$servername = "eg3es.myd.infomaniak.com";
$username = "eg3es_passcrack";
$password = getenv('REDIRECT_HOST_MYSQL');
$dbname = "eg3es_passcrack";

$conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbname;charset=utf8mb4", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

session_start();

$API_TOKEN = getenv('REDIRECT_API_TOKEN');

if(isset($API) && $API){
    if(!isset($_POST['token'])) { http_response_code(400); die(); }
    if(!hash_equals($API_TOKEN, $_POST['token'])) { http_response_code(401); die(); }
}

spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

$utility = new Utility();