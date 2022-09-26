<?php

require __DIR__ . '/config.php';

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PassCrack - Accueil</title>

    <?php include __DIR__ . '/templates/header.html.php' ?>
</head>

<?php include __DIR__ . '/templates/navbar.html.php' ?>

<body>

<div class="px-4 py-5 my-5 text-center">
    <h1 class="display-5 fw-bold">Pass Crack</h1>
    <div class="col-lg-6 mx-auto">
        <p class="lead mb-4">PassCrack est une application gratuite et respectueuse de la vie privée qui a pour but de tester la complexité de vos mots de passe en tentant de les craquer. Toutes les interactions que vous pouvez avoir avec notre site ne sont pas analysées ou gardées sur nos serveurs.</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a type="button" class="btn btn-primary btn-lg px-4 gap-3" href="form.php">Soumettre votre mot de passe</a>
            <a type="button" class="btn btn-outline-secondary btn-lg px-4" href="table.php">Tableau</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/templates/scripts.html.php' ?>

</body>

<?php include __DIR__ . '/templates/footer.html.php' ?>

</html>