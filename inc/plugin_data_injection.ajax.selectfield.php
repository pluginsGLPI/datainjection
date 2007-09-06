<?php
define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");

include("../plugin_data_injection.includes.php");

if(isset($_POST['id']))
	{
	$key = $_POST['id'];
	
	$model = unserialize($_SESSION["plugin_data_injection"]["model"]);
	
	echo "<table id='tab$key'>";
	echo "<tr>";
	echo "<td><input type='hidden' id='add$key' value='0'></td><td><select name='field[$key][0]' id='table$key' onchange='go_info($key);addelete_info($key)' style='width: 150px'>";
	echo "<option value='-1'>".$DATAINJECTIONLANG["mappingStep"][6]."</option>";
		
	$types = getAllInfosDefinitionsTypes($model->getDeviceType());
		
	foreach($types as $type)
		echo "<option value='".$type[0]."'>".$type[1]."</option>";
		
	echo "</select></td>";
	echo "<td id='field$key'><select name='field[$key][1]' style='width: 150px'><option value='-1'>".$DATAINJECTIONLANG["mappingStep"][7]."</option></select></td>";
	echo "<td style='text-align:center'><input type='checkbox' name='field[$key][2]' id='check$key' style='width:60px;' disabled /></td>";
	echo "</tr>";
	echo "</table>";
	
	echo "</div>";
	
	$key++;
	
	echo "<div id='select$key'>";
	
	}

?>
