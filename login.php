<?php

require __DIR__ . '/config.php';

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PassCrack - Connexion</title>

    <?php include __DIR__ . '/templates/header.html.php' ?>
</head>

<?php include __DIR__ . '/templates/navbar.html.php' ?>

<body>

<div class="container mb-5 mt-5">
    <div class="row">
        <div class="col" id="settings-content">
            <div class="card">
                <div class="card-body">
                    <h2>Connexion</h2>

                    <div class="mt-3 w-25">
                        <label for="admin-password" class="form-label">Mot de Passe</label>
                        <input type="password" class="form-control" id="admin-password">
                    </div>

                    <button class="btn btn-primary mt-3" id="btn-connexion">Se connecter</button>
                </div>
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
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/templates/scripts.html.php' ?>

</body>

<?php include __DIR__ . '/templates/footer.html.php' ?>

</html>