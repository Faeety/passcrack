<?php

$ADMIN = true;
require __DIR__ . '/config.php';

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PassCrack - Admin</title>

    <?php include __DIR__ . '/templates/header.html.php' ?>
</head>

<?php include __DIR__ . '/templates/navbar.html.php' ?>

<body>

<div class="container mb-5 mt-5">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-tabs flex-column" role="tablist">
                <li class="nav-item" id="admin-btn-settings">
                    <a class="nav-link active" href="#" data-bs-toggle="tab" role="tab">
                        Paramètres
                    </a>
                </li>

                <li class="nav-item" id="admin-btn-sender">
                    <a class="nav-link active" href="#" data-bs-toggle="tab" role="tab">
                        E-Mail Sender
                    </a>
                </li>
            </ul>
        </div>

        <div class="col-md-9" id="settings-content">

        </div>
    </div>
</div>

<?php include __DIR__ . '/templates/scripts.html.php' ?>

</body>

<?php include __DIR__ . '/templates/footer.html.php' ?>

</html>