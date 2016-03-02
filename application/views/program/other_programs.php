<?php 
	include(APPPATH.'views/home/_create_tabs.php'); 
?>


<!--Set the information to the tabs-->
	<a class="nav-tabs-dropdown btn btn-block btn-primary"><h3>Outros</h3></a>
			
		<ul id="others-programs" class="nav nav-pills nav-stacked well	">
		<?php 
		for($i = MAX_QUANTITY_OF_TABS; $i < $quantityOfPrograms; $i++){ 

			$program = $programs[$i]; ?>

			<li>
			<?php echo anchor(
						"program/{$program['id_program']}",
						"{$program['acronym']} - {$program['program_name']}",
						"class='list-group-item'"); 	
			}?>
			</li>
 
		</ul>
	
		