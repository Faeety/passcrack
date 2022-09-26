<?php

require __DIR__ . '/config.php';

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PassCrack - Formulaire</title>

    <?php include __DIR__ . '/templates/header.html.php' ?>
</head>

<?php include __DIR__ . '/templates/navbar.html.php' ?>

<body>

<div class="container mb-5">
    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            <div class="card bg-gradient-primary">
                <div class="card-body text-center">
                    <h2 class="display-4 text-white text-shadow mb-0">CRACKING</h2>
                </div>
            </div>
        </div>

        <div class="col-md-8 mt-3">
            <div class="card bg-faded-primary">
                <div class="card-body">
                    <h2 class="mb-0">Testez votre mot de passe</h2>
                    <hr class="my-2">
                    <div class="mt-2">
                        <input class="form-control" type="password" id="input-password" placeholder="Password123!">
                        <div class="form-text">Nous utiliserons votre adresse ip pour vous afficher les résultats et
                            comme
                            moyen de rate limit.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 mt-3">
            <div class="card bg-faded-primary">
                <button class="btn btn-primary shadow-primary" id="btn-crack">Craquer</button>
            </div>
        </div>
    </div>
</div>

    <?php include __DIR__ . '/templates/scripts.html.php' ?>

</body>

<?php include __DIR__ . '/templates/footer.html.php' ?>

</html>