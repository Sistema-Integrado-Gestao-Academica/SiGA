<?php

function goBackBtn($url){
    echo "<div class='row'>";
        echo anchor(
            $url,
            "Voltar",
            "class='pull-right btn btn-danger'"
        );
    echo "</div>";
}