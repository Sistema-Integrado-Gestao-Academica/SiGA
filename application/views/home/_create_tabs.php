<?php 
	const MAX_QUANTITY_OF_TABS = 10; 
	const ID_FOR_OTHERS = 0;
?>

</div></aside>
<div class="container">
</br></br>
</br>
<img src="<?php echo base_url('img/base_logo_siga.png'); ?>" alt="Logo SiGA" class="img-responsive img-center" style="width:240px;height:110px;" />

</br><center><h4>
Sistema Integrado de Gestão Acadêmica 
</h4></center></br>
</br>

<!--Creating the tabs-->
<ul class="nav nav-tabs nav-justified">
	<?php
		$hasTabWithId = FALSE;
		for($i = 0; $i < $quantityOfPrograms; $i++){ 
			
			$programToTab = $programs[$i];

			if($i != MAX_QUANTITY_OF_TABS){  

				if ($id != $programToTab['id_program']){  ?>
					<li id = tabs>	
				<?php 
				}
				else{ 
					$hasTabWithId = TRUE;?>
					<li class=active>
				<?php
				} 				 
				echo anchor(
							"program/{$programs[$i]['id_program']}",
							$programToTab['acronym'],
							"class='btn-lg'"); 	?>
				</li>
			<?php }	
			else { 
				if ($id != ID_FOR_OTHERS){ ?>
					<li  id = tabs>	
				<?php 
				}
				else{ ?>
					<li class=active>
				<?php
				} 
				echo anchor(
							"program/others",
							"<i class='fa fa-plus-square'></i> Outros",
							"class='btn-lg'"); ?>
				</li>
			<?php
				break;
			} ?>

		<?php
			
		}
	?>	
</ul>