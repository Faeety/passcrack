<?php

require __DIR__ . '/../config.php';

?>

<div class="table-responsive">
    <table class="table table-striped table-layout-fixed">
        <thead>
        <tr>
            <th>#</th>
            <th>Hash</th>
            <th>Type</th>
            <th>Result</th>
            <th>Status</th>
            <th>Temps</th>
        </tr>
        </thead>
        <?php echo $utility->TableConstruct($_SERVER['REMOTE_ADDR']); ?>
    </table>
</div>