<?php

require __DIR__ . '/config.php';

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PassCrack - Tableau</title>

    <?php include __DIR__ . '/templates/header.html.php' ?>
</head>

<?php include __DIR__ . '/templates/navbar.html.php' ?>

<body>

<div class="container mb-5">
    <div class="row justify-content-center mt-3">
        <div class="col-md-10">
            <div class="card bg-gradient-primary">
                <div class="card-body text-center">
                    <h2 class="display-4 text-white text-shadow mb-0">TABLEAU</h2>
                </div>
            </div>
        </div>

        <div class="col-md-10 mt-3">
            <div class="card bg-faded-info">
                <div class="card-body text-center">
                    <h2 class="display-7 mb-0">25 dernières entrées dans la base de données</h2>
                </div>
            </div>
        </div>

        <div class="col-md-10 mt-3">
            <div class="card bg-faded-primary">
                <div class="card-body" id="page-table">

                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/templates/scripts.html.php' ?>

</body>

<?php include __DIR__ . '/templates/footer.html.php' ?>

</html>