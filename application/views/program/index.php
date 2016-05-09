<h2 class="principal">Programas</h2>

<?= anchor("program/registerNewProgram", "<i class='fa fa-plus-circle'></i> Cadastrar Programa", "class='btn-lg'") ?>

<br>
<br>

<?php displayRegisteredPrograms($programs, TRUE); ?>