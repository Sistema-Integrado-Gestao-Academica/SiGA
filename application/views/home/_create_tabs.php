<?php
	const MAX_QUANTITY_OF_TABS = 10;
	echo "<ul class='nav nav-tabs nav-justified'>";
		$first =  0;
		foreach($programs as $program){
			
			$isFirst = $first === 0;
			
			if($isFirst){	
				echo "<li class='active'>";
			}
			
			else{
				echo "<li id='tabs'>";
			}			
			
			if($first < MAX_QUANTITY_OF_TABS){  
				echo anchor(
					"#program".$first,
					bold($program['acronym']),
					"class='btn btn-tab' data-toggle='tab'"
				);
			}
			else{
				echo anchor(
					"#program".MAX_QUANTITY_OF_TABS,
					"<i class='fa fa-plus-square'></i> Outros"	,
					"class='btn-lg' data-toggle='tab'"
				);
				$first++;
				break;
			}
			
			echo "</li>";

			$first++;

		}
		echo "</ul>";

	$quantityOfTabs = $first;
?>
