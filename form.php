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
                    <div class="progress mb-3">
                        <div class="progress-bar bg-gradient-primary" id="form-progress-bar" role="progressbar" style="width: 1%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="form-text">Taux de réussite (indicatif)</div>
                    <div class="mt-2">
                        <input class="form-control" type="password" id="input-password" placeholder="Password123!">
                        <div class="form-text">Nous utiliserons votre adresse ip pour vous afficher les résultats et
                            comme
                            moyen de rate limit.
                        </div>

                        <div class="d-flex btn-group justify-content-center mt-3 btn-group-hash">
                            <button type="button" class="btn btn-outline-primary btn-hash-type active" id="1">md5</button>
                            <button type="button" class="btn btn-outline-primary btn-hash-type" id="2">sha1</button>
                            <button type="button" class="btn btn-outline-primary btn-hash-type" id="3">sha256</button>
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

<!-- Modal markup -->
<div class="modal" id="modalForm" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Titre</h5>
            </div>
            <div class="modal-body">
                <p id="modal-content">Contenu</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
                <a type="button" class="btn btn-primary btn-sm" href="table.php">Voir</a>
            </div>
        </div>
    </div>
</div>

    <?php include __DIR__ . '/templates/scripts.html.php' ?>

</body>

<?php include __DIR__ . '/templates/footer.html.php' ?>

</html>