<?php

$ADMIN = true;
require __DIR__ . "/../config.php";

echo $utility->GetIpBlacklistStatus();
die();
