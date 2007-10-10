<?php
/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2005 by the INDEPNET Development Team.

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

// Original Author of file: Walid Nouh (walid.nouh@atosorigin.com)
// Purpose of file:
// ----------------------------------------------------------------------

/*
 * Check if the data to import is the good type
 * @param the type of data waited
 * @data the data to import
 * @return true if the data is the correct type
 */
function checkType($type, $name, $data,$mandatory)
{
	global $DATA_INJECTION_MAPPING;

	if (isset($DATA_INJECTION_MAPPING[$type][$name]))
	{
		$field_type = $DATA_INJECTION_MAPPING[$type][$name]['type'];

		//If no data provided AND this mapping is not mandatory
		if (!$mandatory && ($data == null || $data == "NULL" || $data == ''))
			return TYPE_CHECK_OK;
			
		switch($field_type)
		{
			case 'text' :
				return TYPE_CHECK_OK;
			break;
			case 'integer' :
				if (is_numeric($data))
					return TYPE_CHECK_OK;
				else
					return ERROR_IMPORT_WRONG_TYPE;
			break;
			case 'float':
				if (is_float($data))
					return TYPE_CHECK_OK;
				else
					return ERROR_IMPORT_WRONG_TYPE;
			break;
			case 'date' :
					return TYPE_CHECK_OK;
			break;	
			case 'ip':
				ereg("([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})",$data,$regs);
				if (count($regs) > 0)
					return TYPE_CHECK_OK;
				else
					return ERROR_IMPORT_WRONG_TYPE;
			break;
			case 'mac':
				ereg("([0-9a-fA-F]{2}([:-]|$)){6}$",$data,$regs);
				if (count($regs) > 0)
					return TYPE_CHECK_OK;
				else
					return ERROR_IMPORT_WRONG_TYPE;
			break;
			default :
				return ERROR_IMPORT_WRONG_TYPE;
		}
	}
	else
		return ERROR_IMPORT_WRONG_TYPE;

}

/*
 * check one line of data to import
 * @param model the model to use
 * @param line the line of datas
 * @return an array to give the result of the check ("result"=>value,"message"=>error message if any)
 */
function checkLine($model,$line,$res)
	{
		//Get all mappings for a model
		for ($i=0, $mappings = $model->getMappings(); $i < count($mappings); $i++)
		{
			$mapping = $mappings[$i];
			$rank = $mapping->getRank();
			//If field is mandatory AND not mapped -> error
			
			if ($mapping->isMandatory() && (!isset($line[$rank]) || $line[$rank] == NULL || $line[$rank] == "" || $line[$rank] == -1))
			{
				$res->setStatus(false);
				$res->setCheckStatus(-1);
				$res->addCheckMessage($mapping->getName(),ERROR_IMPORT_FIELD_MANDATORY);
					break;				
			}
			else
			{
				//If field exists and if field is mapped
				if (isset($line[$rank]) && $line[$rank] != "" && $mapping->getValue() != NOT_MAPPED)
				{
					//Check type
					$field = $line[$rank];
					$res_check_type = checkType($mapping->getMappingType(), $mapping->getValue(), $field,$mapping->isMandatory());

					//If field is not the good type -> error
					if ($res_check_type != TYPE_CHECK_OK)
					{
						$res->setStatus(false);
						$res->setCheckStatus(-1);
						$res->addCheckMessage($mapping->getName(),$res_check_type);
						break;
					}
					else
					{
						$res->setStatus(true);
						$res->setCheckStatus(TYPE_CHECK_OK);
					}
				}	
			}
		}

		return $res;
	}

/*
 * Get the ID of an element in a dropdown table, create it if the value doesn't exists and if user has the right
 * @param mapping the mapping informations
 * @param mapping_definition the definition of the mapping
 * @param value the value to add
 * @param entity the active entity
 * @return the ID of the insert value in the dropdown table
 */	
function getDropdownValue($mapping, $mapping_definition,$value,$entity,$canadd=0)
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
			default:
				$input["tablename"] = $mapping_definition["table"];
				$input["value"] = $value;
				$input["value2"] = "";
				$input["type"] = "";
				$input["comments"] = "";
				$input["FK_entities"] = $entity;
				break;
		}
		
		$ID = getDropdownID($input);
		if ($ID != -1)
			return $ID;
		else if ($rightToAdd)	
			return addDropdown($input);
		else
			return '';	
}

function findUser($value,$entity)
{
	global $DB;
	$sql = "SELECT ID FROM glpi_users WHERE LOWER(name)=\"".strtolower($value)."\" OR (CONCAT(LOWER(realname),' ',LOWER(firstname))=\"".strtolower($value)."\" OR CONCAT(LOWER(firstname),' ',LOWER(realname))=\"".strtolower($value)."\")";
	$result = $DB->query($sql);
	if ($DB->numrows($result)>0)
	{
		//check if user has right on the current entity
		$ID = $DB->result($result,0,"ID");
		$entities = getUserEntities($ID,true);
		if (in_array($entity,$entities))
			return $ID;
		else
			return '';	
	}
	else
		return '';		
}
/*
 * Function to check if the datas to inject already exists in DB
 * @param type the type of datas to inject
 * @param fields the datas to inject
 * @param mapping_definition the definition of the mapping
 * @param model the current injection model
 * @return true if the data exists, false if it doesn't already exists
 */
function dataAlreadyInDB($type,$fields,$mapping_definition,$model)
{
	global $DB;
	$where = "";
	$mandatories = getAllMandatoriesMappings($type,$model);
	
	if ($model->getDeviceType() == $type)
		$primary = true;
	else
		$primary = false;	

	$obj = getInstance($type);
	
	//TODO : determine when to put ' or not
	$delimiter = "'";
		
	if ($primary)
	{
		foreach ($mandatories as $mapping)
		{
				$mapping_definition = getMappingDefinitionByTypeAndName($type,$mapping->getValue());
				
					
				$where.=" AND ".$mapping->getValue()."=".$delimiter.$fields[$mapping->getValue()].$delimiter;
		}	

		switch ($obj->table)
		{
			case "glpi_users":
				$where_entity = " 1";
				break;	
			default:
				$where_entity = " FK_entities=".$fields["FK_entities"];
				break;
		}
	}
	else
	{
		$where_entity = " 1";
		switch ($type)
		{
			case INFOCOM_TYPE :
				$where.=" AND device_type=".$model->getDeviceType()." AND FK_device=".$fields["FK_device"];
			break;

			default:
			break;	
		}
	}

	$sql = "SELECT * FROM ".$obj->table." WHERE".$where_entity." ".$where;
	$result = $DB->query($sql);

	if ($DB->numrows($result) > 0 )
		return $DB->fetch_array($result);
	else		
		return array("ID"=>-1);
}

function getInstance($device_type)
{
		$commonitem = new CommonItem;
		$commonitem->setType($device_type,1);
		return $commonitem->obj;
}

/*
 * Add new values to the array of common values
 * @param common_fields the array of common values
 * @param type the type of value
 * @param fields the fields associated with the type
 * @param entity the current entity
 * @param id the ID of the main object
 * @return the update common values array
 */
function addCommonFields($common_fields,$type,$fields,$entity,$id)
{
	switch ($type)
	{
		case COMPUTER_TYPE:
		case MONITOR_TYPE:
		case PRINTER_TYPE:
		case PHONE_TYPE:
		case NETWORKING_TYPE:
		case PERIPHERAL_TYPE:
			$common_fields["device_id"] = $id;
			$common_fields["device_type"] = $type;
			$common_fields["FK_entities"] = $entity;
			break;
		case GROUP_TYPE:
		case CONTRACT_TYPE:
			$common_fields["FK_entities"] = $entity;
			break;
		case USER_TYPE:
			$common_fields["FK_user"] = $id;
				
			if (isset($fields["FK_group"]))
				$common_fields["FK_group"] = $fields["FK_group"];
			break;		
		default:
			break;	
	}	
	return $common_fields;
}

/*
 * Add necessary fields
 */
function addNecessaryFields($model,$mapping,$mapping_definition,$entity,$type,$fields,$common_fields)
{
	global $DB;
	switch ($type)
	{
		case MONITOR_TYPE:
		case COMPUTER_TYPE:
		case PRINTER_TYPE:
		case PHONE_TYPE:
		case NETWORKING_TYPE:
			if (isset($fields["ipaddr"]))
				unset ($fields["ipaddr"]);

			if (isset($fields["macaddr"]))
				unset ($fields["macaddr"]);
		case GROUP_TYPE:
		case CONTRACT_TYPE:
		case PERIPHERAL_TYPE:
			if (!isset($fields["FK_entities"]))
				$fields["FK_entities"] = $entity;
			break;
		case USER_TYPE:
			if (isset ($fields["password"])) 
			{
				if (empty ($fields["password"])) {
					unset ($fields["password"]);
				} else {
					$fields["password_md5"] = md5(unclean_cross_side_scripting_deep($fields["password"]));
					$fields["password"] = "";
				}
			}
			
			//Add auth and profiles fields	
			if (!isset($fields["auth_method"]))
				$fields["auth_method"] = AUTH_DB_GLPI;
				
			if (isset($fields["FK_profiles"]))
				$fields["FK_profiles"] = getFieldIDByName($mapping,$mapping_definition,$fields["FK_profiles"],$entity);

			break;
		case INFOCOM_TYPE:
			//Set the device_id
			if (!isset($fields["device_id"]))
				$fields["FK_device"] = $common_fields["device_id"];
			
			//Set the device type
			if (!isset($fields["device_type"]))
				$fields["device_type"] = $model->getDeviceType();			
			break;
		default:
			break;	
	}
	return $fields;
}

function getFieldValue($mapping, $mapping_definition,$field_value,$entity,$obj,$canadd)
{
	global $DB;
	
	//If the value is a date, try to reformat it if it's not the good type (dd-mm-yyyy instead of yyyy-mm-dd)
	if (isset($mapping_definition["type"]) && $mapping_definition["type"]=="date")
		$field_value = reformatDate($field_value);
		
	if (isset($mapping_definition["table_type"]))
	{
		switch ($mapping_definition["table_type"])
		{
			//Read and add in a dropdown table
			case "dropdown":
				$obj[$mapping_definition["linkfield"]] = getDropdownValue($mapping,$mapping_definition,$field_value,$entity,$canadd);
				break;
			
			case "user":
				$obj[$mapping_definition["linkfield"]] = findUser($field_value,$entity);
				break;
				
			//Read in a single table	
			case "single":
				switch ($mapping_definition["table"].".".$mapping_definition["field"])
				{
					case "glpi_groups.name" :
					case "glpi_users.name" :
						$sql = "SELECT ID FROM ".$mapping_definition["table"]." WHERE ".$mapping_definition["field"]."='".$field_value."' AND FK_entities=".$entity;
						$result = $DB->query($sql);
						if ($DB->numrows($result))
							$obj[$mapping_definition["linkfield"]] = $DB->result($result,0, "ID");
						break;
					default:
						break;
								
				}
				break;
			case "multitext":
				if (!isset($obj[$mapping_definition["field"]]))
					$obj[$mapping_definition["field"]]="";
					
				$obj[$mapping_definition["field"]] .= $mapping->getName()."=".$field_value."\n";		
				break;	
			default :
				$obj[$mapping_definition["field"]] = $field_value;
				break;
		}
	}
	else
		$obj[$mapping_definition["field"]] = $field_value;
	
	return $obj;
}

function processBeforeEnd($model,$type,$fields,$common_fields)
{
	switch ($type)
	{
		case USER_TYPE:
			//If user ID is given, add the user in this group
			if (isset($common_fields["FK_user"]) && isset($common_fields["FK_group"]))
				addUserGroup($common_fields["FK_user"],$common_fields["FK_group"]);
				
		break;
		default:
		break;
	}
}

/*
 * Check is the user has the right to add datas in a dropdown table
 * @param table the dropdown table
 * @canadd_dropdown boolean to indicate if the model allows user to add datas in a dropdown table
 * @return true if the user can add, false if he can't 
 */
function haveRightDropdown($table,$canadd_dropdown)
{
	global $CFG_GLPI;
	if (!$canadd_dropdown)
		return false;
	else	
	{
		if (in_array($table,$CFG_GLPI["specif_entities_tables"]))
			return haveRight("entity_dropdown","w");
		else
			return haveRight("dropdown","w");	
	}
}
	
function addInfosFields($fields,$infos)
{
	foreach ($infos as $info)
		if (keepInfo($info))	
			$fields[$info->getInfosType()][$info->getValue()] = $info->getInfosText();

	return $fields;
}

function keepInfo($info)
{
	global $DATA_INJECTION_INFOS;
	
	if (!isset($DATA_INJECTION_INFOS[$info->getInfosType()][$info->getValue()]["input_type"]))
		return true;

	switch ($DATA_INJECTION_INFOS[$info->getInfosType()][$info->getValue()]["input_type"])
	{
		case "text":
			if ($info->getInfosText() != NULL && $info->getInfosText() != '')
				return true;
		break;
		case "dropdown":
			if ($info->getInfosText() != 0)
				return true;
		break;		
	}	
	return false;
}

function logAddOrUpdate($device_type,$device_id,$action_type)
{
	global $DATAINJECTIONLANG;
	
	$changes[0]=0;
	
	if ($action_type == INJECTION_ADD)
		$changes[2] = $DATAINJECTIONLANG["result"][8]." ".$DATAINJECTIONLANG["history"][1];
	else
		$changes[2] = $DATAINJECTIONLANG["result"][9]." ".$DATAINJECTIONLANG["history"][1];
	
	$changes[1] = "";		
	historyLog ($device_id,$device_type,$changes,0,HISTORY_LOG_SIMPLE_MESSAGE);
}

/*
 * 
 */
function filterFields($fields,$fields_from_db,$can_overwrite)
{
	//If no write to overwrite existing fields in DB -> unset the field
	foreach ($fields as $field=>$value)
		if ($field != "ID" && !$can_overwrite && (isset($fields_from_db[$field])))
			unset ($fields[$field]);

	return $fields;
}

function checkLocation ($location, $entity, $canadd)
{
	$location_id = 0;
	$locations = explode('>',$location);
	
	foreach ($locations as $location)
		if ($location_id !== '')
			$location_id = addLocation(trim($location),$entity,$location_id,$canadd);
		
	return $location_id;	
}

function addLocation($location,$entity,$parentid,$canadd)
{
	$input["tablename"] = "glpi_dropdown_locations";
	$input["value"] = $location;
	$input["value2"] = $parentid;
	$input["type"] = "under";
	$input["comments"] = "";
	$input["FK_entities"] = $entity;
	
	$ID = getDropdownID($input);
	
	if ($ID != -1)
		return $ID;

	if ($canadd)	
		return addDropdown($input);
	else
		return '';	
}
?>