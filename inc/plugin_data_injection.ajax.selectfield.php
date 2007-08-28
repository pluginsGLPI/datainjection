<?php
define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");

include("../plugin_data_injection.includes.php");

if(isset($_POST['id']))
	{
	$key = $_POST['id'];
	
	echo "<table id='tab$key'>";
	echo "<tr>";
	echo "<td><input type='hidden' id='add$key' value='0'></td><td><select name='field[$key][0]' id='table$key' onchange='go_info($key);addelete_info($key)' style='width: 150px'>";
	echo "<option value='-1'>".$DATAINJECTIONLANG['step9'][3]."</option>";
		
	$types = getAllMappingsDefinitionsTypes();
		
	foreach($types as $type)
		echo "<option value='".$type[0]."'>".$type[1]."</option>";
		
	echo "</select></td>";
	echo "<td id='field$key'><select name='field[$key][1]' style='width: 150px'><option value='-1'>".$DATAINJECTIONLANG["step9"][4]."</option></select></td>";
	echo "<td><input type='checkbox' name='field[$key][2]' id='check$key' style='text-align:center;width:60px;visibility:hidden' /></td>";
	echo "</tr>";
	echo "</table>";
	
	echo "</div>";
	
	$key++;
	
	echo "<div id='select$key'>";
	
	}

?>
