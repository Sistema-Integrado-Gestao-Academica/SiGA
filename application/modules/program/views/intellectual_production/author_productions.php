<?php 
    $subtype = $production->getSubtypeName();
    $type = $production->getTypeName();

    $year = $production->getYear();
    
    if(empty($year)){
        $year = "Não informado";
    }


    $identifier = $production->getIdentifier();
    
    if(empty($identifier)){
        $identifier = "Não informado";
    }
    
    $qualis = $production->getQualis();

    if(empty($qualis)){
        $qualis = "Não informado";
    }
    
    $periodic = $production->getPeriodic();

    if(empty($periodic)){
        $periodic = "Não informado";
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

      <strong>Ano</strong>

      <p class="text">
        <?= $year ?>
      </p>

      <strong>Tipo</strong>

      <p class="text">
        <?= $type?>
      </p>
     
      <strong>Subtipo</strong>

      <p class="text">
        <?= $subtype ?>
      </p>     

      <strong>Autores</strong>

      <p class="text">
        <?php

          $coauthors = $production->getCoauthors(); 
          if($coauthors !== FALSE){

            foreach ($coauthors as $coauthor) {
                echo "<br>";
                echo $coauthor['order']."-".$coauthor['author_name'];               
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

      <strong>Qualis</strong>

      <p class="text">
        <?= $qualis ?>
      </p>

    </div>
    <!-- /.box-body -->
</div>

<strong><h4> Contexto </h4></strong>
<div class="box box-success">
    <div class="box-header with-border">
    </div>
    <!-- /.box-header -->
    <div class="box-body">

      <strong>Projeto de Pesquisa</strong>
      
      <p class="text">
        <?= $production->getProjectName(); ?>
      </p>

    </div>
    <!-- /.box-body -->
</div>