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
	global $DB,$LANG;

	if ($table == "glpi_entities")
	{
		if ($id==0)
			return $LANG["entity"][2];
		elseif ($id==-1)
			return $LANG["common"][77];	
	}
	
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

function dropdownDateFormat($name,$format)
{
	global $DATAINJECTIONLANG;
	$date_format[DATE_TYPE_DDMMYYYY]=$DATAINJECTIONLANG["modelStep"][22];
	$date_format[DATE_TYPE_MMDDYYYY]=$DATAINJECTIONLANG["modelStep"][23];
	$date_format[DATE_TYPE_YYYYMMDD]=$DATAINJECTIONLANG["modelStep"][24];

	dropdownArrayValues($name,$date_format,$format);
}

function dropdownFloatFormat($name,$format)
{
	global $DATAINJECTIONLANG;
	$float_format[FLOAT_TYPE_DOT]=$DATAINJECTIONLANG["modelStep"][25];
	$float_format[FLOAT_TYPE_COMMA]=$DATAINJECTIONLANG["modelStep"][26];
	$float_format[FLOAT_TYPE_DOT_AND_COM]=$DATAINJECTIONLANG["modelStep"][27];

	dropdownArrayValues($name,$float_format,$format);
}

function dropdownPrimaryTypeSelection($name,$model=null,$disable=false)
{
	echo "<select name='$name' ".($disable?"style='background-color:#e6e6e6' disabled":"").">";
	
	$default_value=($model==null?0:$model->getDeviceType());
		
	foreach(getAllPrimaryTypes() as $type)
		echo "<option value='".$type[1]."' ".(($default_value == $type[1])?"selected":"").">".$type[0]."</option>";
	
	echo "</select>";
}

function dropdownFileTypes($name,$model=null,$disable=false)
{
	
	if($_SESSION["plugin_data_injection"]["choice"]==1)
		{
		$id=0;
		echo "<td><select id='dropdown_type' name='dropdown_type' onchange='show_backend($id)'>";
		}
	else
		{
		echo "<td><select name='dropdown_type' style='background-color:#e6e6e6' disabled>";
		$id=$model->getModelType();
		}
	
		
	$types = getAllTypes();
	
	foreach($types as $key => $type)
		{
		if(isset($model))
			{
			if($model->getModelType() == $type->getBackendID())
				echo "<option value='".$type->getBackendID()."' selected>".$type->getBackendName()."</option>";
			else
				echo "<option value='".$type->getBackendID()."'>".$type->getBackendName()."</option>";
			}
		else
			echo "<option value='".$type->getBackendID()."'>".$type->getBackendName()."</option>";
		}
		
	echo "</select></td></tr>";
	
}

function dropdownModels($disable=false,$models,$with_select=true)
{
		$nbmodel = count($models);
		if ($with_select)
		{
			if ($disable)	
				echo "\n<select style='background-color:#e6e6e6' disabled name='dropdown' id='dropdown' onchange='show_comments($nbmodel)'>";
			else			
				echo "\n<select name='dropdown' id='dropdown' onchange='show_comments($nbmodel)'>";
		}

		$prev = -2;
			
		foreach($models as $model)
		{
			if ($model->getEntity() != $prev) {
				if ($prev >= -1) {
					echo "</optgroup>\n";
				}
				$prev = $model->getEntity();
				echo "\n<optgroup label=\"" . getDropdownMinimalName("glpi_entities", $prev) . "\">";
			}
			echo "\n<option value='".$model->getModelID()."'>".$model->getModelName()." , ".getDropdownName('glpi_plugin_data_injection_filetype',$model->getModelType())."</option>";
		}
		
		if ($prev >= -1) {
			echo "</optgroup>";
		}
	

		if ($with_select)
			echo "</select>\n";
	
}

function dropdownFileEncoding($name)
{
	global $DATAINJECTIONLANG;
	$values[ENCODING_AUTO]=$DATAINJECTIONLANG["fileStep"][10];
	$values[ENCODING_UFT8]=$DATAINJECTIONLANG["fileStep"][11];
	$values[ENCODING_ISO8859_1]=$DATAINJECTIONLANG["fileStep"][12];
	dropdownArrayValues($name,$values,ENCODING_AUTO);
}

function dropdownPortUnicity($name,$value)
{
	global $LANG;
	$values[MODEL_NETPORT_LOGICAL_NUMER] = $LANG["networking"][21];
	$values[MODEL_NETPORT_NAME] = $LANG["common"][16];
	$values[MODEL_NETPORT_MACADDRESS] = $LANG["device_iface"][2];
	$values[MODEL_NETPORT_LOGICAL_NUMER_NAME] =$LANG["networking"][21]."+".$LANG["common"][16];
	$values[MODEL_NETPORT_LOGICAL_NUMER_MAC]=$LANG["networking"][21]."+".$LANG["device_iface"][2];
	$values[MODEL_NETPORT_LOGICAL_NUMER_NAME_MAC]=$LANG["networking"][21]."+".$LANG["common"][16]."+".$LANG["device_iface"][2];
	dropdownArrayValues($name,$values,$value);
}
?>
