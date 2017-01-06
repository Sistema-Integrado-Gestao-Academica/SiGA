
<?php
    $msg = $filled ? "" : "não";
?>
<h2 class="principal">Relatório de preenchimento de produções do ano de <?= $year ?></h2>

<h4><i class='fa fa-list'></i> Discentes que <?= $msg ?> produziram no ano de <?= $year ?>:</h4>

<?php listUserProductions($users->students, $filled, $year); ?>

<?php listUserProductions($users->teachers, $filled, $year); ?>

<?php
    function listUserProductions($users, $filled, $year){

    }
?>

<script>
    $(document).ready(function(){
        // window.print();
    });
</script>