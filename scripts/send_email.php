<?php

$ADMIN = true;
require __DIR__ . "/../config.php";

$sender = $_POST['sender'];
$recipient = $_POST['recipient'];
$title = $_POST['title'];
$content = $_POST['content'];

$headers = array(
    'From' => $sender,
    'Reply-To' => 'webmaster@passcrack.ch',
    'X-Mailer' => 'PHP/' . phpversion()
);

if (str_contains($recipient, ",")){
    $recipients = explode(",", $recipient);

    foreach ($recipients as $rec){
        mail($rec, $title, $content, $headers);
    }
}else{
    mail($recipient, $title, $content, $headers);
}

die("done");