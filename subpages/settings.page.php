<?php

$ADMIN = true;
require __DIR__ . '/../config.php';

?>

<div class="card">
    <div class="card-body">
        <h2>Paramètres</h2>
        <div id="div-alert"></div>
        <hr>
        <div class="form-check form-switch mt-3">
            <input type="checkbox" class="form-check-input" id="blacklist-switch">
            <label class="form-check-label" for="blacklist-switch">IP Blacklist</label>
        </div>

        <button class="btn btn-primary mt-3" id="btn-start-cron">Lancer le cron</button>
    </div>
</div>
