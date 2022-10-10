<?php

require __DIR__ . '/../config.php';

?>

<div class="card">
    <div class="card-body">
        <h2>E-Mail Sender</h2>
        <div id="div-alert"></div>
        <hr>
        <div class="mt-3">
            <label for="email-sender" class="form-label">Envoyeur</label>
            <input type="email" class="form-control" id="email-sender" placeholder="contact@exemple.ch">
        </div>

        <div class="mt-3">
            <label for="email-recipient" class="form-label">Destinataire</label>
            <input type="email" class="form-control" id="email-recipient" placeholder="test@exemple.ch">
        </div>

        <div class="mt-3">
            <label for="email-title" class="form-label">Titre</label>
            <input type="text" class="form-control" id="email-title" placeholder="Salut!">
        </div>

        <div class="mt-3">
            <label for="email-content" class="form-label">Contenu</label>
            <textarea class="form-control" id="email-content" rows="5" placeholder="Hello World!"></textarea>
        </div>

        <div class="mt-3">
            <button class="btn btn-primary" id="btn-email-send">Envoyer</button>
        </div>
    </div>
</div>
