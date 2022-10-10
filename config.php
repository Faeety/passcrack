<?php
declare(strict_types=1);

require __DIR__ . "/Status.php";
require __DIR__ . "/HashType.php";

$servername = "eg3es.myd.infomaniak.com";
$username = "eg3es_passcrack";
$password = getenv('REDIRECT_HOST_MYSQL');
$dbname = "eg3es_passcrack";

$conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbname;charset=utf8mb4", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

function getRandomString($n) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}

session_start();
if (!isset($_COOKIE['user'])) setcookie("user", getRandomString(20), time()+60*60*24*365, "/", "passcrack.ch", true, false);

$API_TOKEN = getenv('REDIRECT_API_TOKEN');
$VAST_API_KEY = getenv('REDIRECT_VAST_API_KEY');
$CRON_TOKEN = getenv('REDIRECT_CRON_TOKEN');
$ADMIN_PASSWORD = getenv('REDIRECT_ADMIN_PASSWORD');

if(isset($API) && $API){
    if(!isset($_POST['token'])) { http_response_code(400); die(); }
    if(!hash_equals($API_TOKEN, $_POST['token'])) { http_response_code(403); die(); }
}

if(isset($CRON) && $CRON){
    if(!isset($_GET['token']) and !isset($_SESSION['admin'])) { http_response_code(400); die(); }

    if (isset($_GET['token']) && !hash_equals($CRON_TOKEN, $_GET['token'])){
        http_response_code(403); die();
    }

    if(isset($_SESSION['admin']) && !$_SESSION['admin']) {
        http_response_code(403); die();
    }
}

if(isset($ADMIN) && $ADMIN){
    if(!isset($_SESSION['admin'])) { http_response_code(403); die(); }
    if(!$_SESSION['admin']) { http_response_code(403); die(); }
}

spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

$utility = new Utility();
$enc = new Encryption();