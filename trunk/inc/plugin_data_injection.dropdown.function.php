<?php
/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

	This file is part of GLPI.

    GLPI is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    GLPI is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GLPI; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
*/

// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------


function getDropdownMinimalName ($table, $id) 
{
	global $DB;
	
	$name = EMPTY_VALUE;	
	if ($id>0){
		$query = "SELECT name FROM ". $table ." WHERE ID=$id";
		if ($result = $DB->query($query)){
			if($DB->numrows($result) > 0) {
				$data=$DB->fetch_assoc($result);
				$name = $data["name"];
			}
		}
	}
	return addslashes($name); 
}

/*
 * Get the ID of an element in a dropdown table, create it if the value doesn't exists and if user has the right
 * @param mapping the mapping informations
 * @param mapping_definition the definition of the mapping
 * @param value the value to add
 * @param entity the active entity
 * @return the ID of the insert value in the dropdown table
 */	
function getDropdownValue($mapping, $mapping_definition,$value,$entity,$canadd=0,$location=EMPTY_VALUE)
{
	global $DB, $CFG_GLPI;

	if (empty ($value))
		return 0;

		$rightToAdd = haveRightDropdown($mapping_definition["table"],$canadd);

		//Value doesn't exists -> add the value in the dropdown table
		switch ($mapping_definition["table"])
		{
			case "glpi_dropdown_locations":
				return checkLocation($value,$entity,$rightToAdd);
			case "glpi_dropdown_netpoint":
				// not handle here !
				return EMPTY_VALUE;	
			break;
			default:
				$input["value2"] = EMPTY_VALUE;
				break;
		}

		$input["tablename"] = $mapping_definition["table"];
		$input["value"] = $value;
		$input["FK_entities"] = $entity;
		$input["type"] = EMPTY_VALUE;
		$input["comments"] = EMPTY_VALUE;
		
		$ID = getDropdownID($input);
		if ($ID != -1)
			return $ID;
		else if ($rightToAdd)	
			return addDropdown($input);
		else
			return EMPTY_VALUE;	
}


function dropdownTemplate($name,$entity,$table,$value='')
{
	global $DB;
	$result = $DB->query("SELECT tplname, ID FROM ".$table." WHERE FK_entities=".$entity." AND tplname <> '' GROUP BY tplname ORDER BY tplname");
	
	$rand=mt_rand();
	echo "<select name='$name' id='dropdown_".$name.$rand."'>";

	echo "<option value='0'".($value==0?" selected ":"").">-------------</option>";

	while ($data = $DB->fetch_array($result))
		echo "<option value='".$data["ID"]."'".($value==$data["tplname"]?" selected ":"").">".$data["tplname"]."</option>";

	echo "</select>";	
	return $rand;
}
?>
