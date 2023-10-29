<?php ob_start(); ?>
<h1>La liste de toute les questions :</h1>

<?php foreach($oeuvres as $oeuvre): ?>
    <div class="oeuvre">
        <h2><a href="/oeuvre/<?= $oeuvre["id"] ?>"><?= $oeuvre["text"] ?></a></h2>
        <p><?= $oeuvre["text"] ?></p>
    </div>
<?php endforeach; ?>

<p><a href="/oeuvre/add">Ajouter une oeuvre</a></p>

<?php $content = ob_get_clean(); ?>

<?php require('layout.tpl.php') ?>