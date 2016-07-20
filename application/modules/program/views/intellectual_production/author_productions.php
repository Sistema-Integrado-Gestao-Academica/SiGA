<?php 
    $subtype = $production->getSubtypeName();
    $type = $production->getTypeName();

    $year = $production->getYear();
    
    if(empty($year)){
        $year = "-";
    }


    $identifier = $production->getIdentifier();
    
    if(empty($identifier)){
        $identifier = "-";
    }
    
    $qualis = $production->getQualis();

    if(empty($qualis)){
        $qualis = "-";
    }
    
    $periodic = $production->getPeriodic();

    if(empty($periodic)){
        $periodic = "-";
    }
    
?>


<strong><h4> Dados da produção</h4></strong>
<div class="box box-success">
    <div class="box-header with-border">
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <strong> Título</strong>

      <p class="text">
        <?= $production->getTitle() ?>
      </p>

      <hr>

      <strong>Ano</strong>

      <p class="text">
        <?= $year ?>
      </p>


      <hr>

      <strong>Tipo</strong>

      <p class="text">
        <?= $type?>
      </p>
     
     <hr>

      <strong>Subtipo</strong>

      <p class="text">
        <?= $subtype ?>
      </p>     

      <hr>

      <strong>Autores</strong>

      <p class="text">
        <?= $user->getName(); ?>

        <?php

          $coauthors = $production->getCoauthors(); 
          if($coauthors !== FALSE){

            foreach ($coauthors as $coauthor) {
                echo "<br>";
                echo $coauthor['author_name'];               
            }

          } 

        ?>
      </p>     

    </div>
</div>

<strong><h4> <?= $type?> - <?= $subtype?></h4></strong>
<div class="box box-success">
    <div class="box-header with-border">
    </div>
    <!-- /.box-header -->
    <div class="box-body">

      <strong>Periódico/

      <?php if ($subtype == "Livro"){

            echo "ISBN";
        } 
        else{

            echo "ISSN";
        }?> 
      </strong>
      
      <p class="text">
        <?= $periodic ?> / <?= $identifier ?>  
      </p>

      <hr>

      <strong>Qualis</strong>

      <p class="text">
        <?= $qualis ?>
      </p>

    </div>
    <!-- /.box-body -->
</div>