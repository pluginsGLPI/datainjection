<?php

	if(isset($_POST['id']))
		{
		echo "<select name='field".$_POST['id']."' style='width: 150px'>";
		
		if(isset($_POST['idMapping']))
			{
			switch($_POST['idMapping'])
				{
					case -1:
						echo "<option value='-1'>-------Choisir un champ-------</option>";
					break;
					case 1:
						echo "<option value='1'>Name</option>";
						echo "<option value='2'>Serial</option>";
						echo "<option value='3'>Os licence number</option>";
						echo "<option value='4'>Os licence id</option>";
					break;
					case 2:
						echo "<option value='1'>Name</option>";
					break;
					case 3:
						echo "<option value='1'>Name</option>";
						echo "<option value='2'>Password</option>";
						echo "<option value='3'>E-mail</option>";
						echo "<option value='4'>Language</option>";
					break;
					case 4:
						echo "<option value='1'>Name</option>";
						echo "<option value='2'>Serial</option>";
					break;
					case 5:
						echo "<option value='1'>Name</option>";
						echo "<option value='2'>Serial</option>";
						echo "<option value='3'>Other serial</option>";
						echo "<option value='4'>Mac</option>";
						echo "<option value='5'>Address</option>";
					break;
				}
			}
			
		echo "</select>";
		}
?>
