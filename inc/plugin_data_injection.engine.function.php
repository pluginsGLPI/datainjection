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
function checkType($type, $name, $data)
{
	global $DATA_INJECTION_MAPPING;

	if (isset($DATA_INJECTION_MAPPING[$type][$name]))
	{
		$field_type = $DATA_INJECTION_MAPPING[$type][$name]['type'];
		
		switch($field_type)
		{
			case 'text' :
				return TYPE_CHECK_OK;
			break;
			case 'integer' :
				if (is_int($data))
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
				//TODO : check date
				return TYPE_CHECK_OK;			
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
function checkLine($model,$line)
	{
		$res = array ("result"=>true, "message"=>TYPE_CHECK_OK);
		
		//Get all mappings for a model
		for ($i=0, $mappings = $model->getMappings()->getAllMappings(); $i < count($mappings); $i++)
		{
			$mapping = $mappings[$i];
			$rank = $mapping->getRank();
			//If field is mandatory AND not mapped -> error
			if ($mapping->isMandatory() && (!isset($line[$rank]) || $line[$rank] == "" || $line[$rank] == -1))
			{
				 	$res = array ("result" => false, "message" => ERROR_IMPORT_FIELD_MANDATORY);
					break;				
			}
			else
			{
				//If field exists and if field is mapped
				if (isset($line[$rank]) && $line[$rank] != "" && $mapping->getValue() != NOT_MAPPED)
				{
					//Check type
					$field = $line[$rank];
					$res_check_type = checkType($mapping->getMappingType(), $mapping->getValue(), $field);
					
					//If field is not the good type -> error
					if ($res_check_type != TYPE_CHECK_OK)
					{
						$res = array ("result" => false, "message" => $res_check_type);
						break;
					}
				}	
			}
		}

		return $res;
	}

/*
 * Insert a value in a dropdown table
 * @param mapping the mapping informations
 * @param mapping_definition the definition of the mapping
 * @param value the value to add
 * @param entity the active entity
 * @return the ID of the insert value in the dropdown table
 */	
function insertDropdownValue($mapping, $mapping_definition,$value,$entity)
{
	global $DB, $CFG_GLPI;

	if (empty ($value))
		return 0;

		//Value doesn't exists -> add the value in the dropdown table
		switch ($mapping_definition["table"])
		{
			default:
			$input["tablename"] = $mapping_definition["table"];
			$input["value"] = $value;
			$input["value2"] = "";
			$input["type"] = "";
			$input["comments"] = "";
			$input["FK_entities"] = $entity;
			break;
		}
		return addDropdown($input);
}

/*
 * Function to get the ID of a field by giving his name
 * @param type the type of datas to inject
 * @param fields the datas to inject
 * @param mapping_definition the definition of the mapping
 * @param value the value to look for
 * @param entity the current entity
 * @return the ID if it exists, "" if not
 */
function getFieldIDByName($mapping,$mapping_definition,$value,$entity)
{
	global $DB;
	$sql = "SELECT ID FROM ".$mapping_definition["table"]." WHERE";
	
	switch ($mapping_definition["table"]." ".$mapping_definition["field"])
	{
		case "glpi_profiles.name":
			$where = $mapping_definition["table"]."=".$value;
			break;
		default:
			$where = "";
			break;	
	}
	
	$result = $DB->query($sql);
	if ($DB->numrows($result))
		return $DB->result($result,0,"ID");
	else
		return "";	
	
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

	$sql = "SELECT ID FROM ".$obj->table." WHERE".$where_entity." ".$where;
	$result = $DB->query($sql);

	if ($DB->numrows($result) > 0 )
		return $DB->result($result,0,"ID");
	else		
		return -1;
}

//TODO : this function should be in GLPI's core (maybe in commonitem??)
function getInstance($device_type)
{
	switch ($device_type){
		case COMPUTER_TYPE :
			return new Computer;
			break;
		case NETWORKING_TYPE :
			return new Netdevice;
			break;
		case PRINTER_TYPE :
			return new Printer;
			break;
		case MONITOR_TYPE : 
			return new Monitor;	
			break;
		case PERIPHERAL_TYPE : 
			return new Peripheral;	
			break;				
		case SOFTWARE_TYPE : 
			return new Software;	
		break;				
		case CONTACT_TYPE : 
			return new Contact;	
			break;	
		case ENTERPRISE_TYPE : 
			return new Enterprise;	
			break;	
			case CONTRACT_TYPE : 
		return new Contract;	
			break;				
		case CARTRIDGE_TYPE : 
			return new CartridgeType;	
			break;					
		case TYPEDOC_TYPE : 
			return new TypeDoc;	
			break;		
		case DOCUMENT_TYPE : 
			return new Document;	
			break;					
		case KNOWBASE_TYPE : 
			return new kbitem;	
			break;					
		case USER_TYPE : 
			return new User;	
			break;					
		case TRACKING_TYPE : 
			return new Job;	
			break;
		case CONSUMABLE_TYPE : 
			return new ConsumableType;	
			break;					
		case CARTRIDGE_ITEM_TYPE : 
			return new Cartridge;	
			break;					
		case CONSUMABLE_ITEM_TYPE : 
			return new Consumable;	
			break;					
		case LICENSE_TYPE : 
			return new License;	
			break;					
		case LINK_TYPE : 
			return new Link;	
			break;	
		case PHONE_TYPE : 
			return new Phone;	
			break;		
		case REMINDER_TYPE : 
			return new Reminder;	
			break;			
		case GROUP_TYPE : 
			return new Group;	
			break;			
		case ENTITY_TYPE : 
			return new Entity;	
			break;			
		case AUTH_MAIL_TYPE:
			return new AuthMail;
			break;
		case AUTH_LDAP_TYPE:
			return new AuthLDAP;
			break;
		case OCSNG_TYPE:
			return new Ocsng;
			break;					
		case REGISTRY_TYPE:
			return new Registry;
			break;					
		case PROFILE_TYPE:
			return new Profile;
			break;					
		case MAILGATE_TYPE:
			return new Mailgate;
			break;		
		case INFOCOM_TYPE:
			return new InfoCom;
			break;				
		default :
			if ($device_type>1000){
				if (isset($PLUGIN_HOOKS['plugin_classes'][$device_type])){
				$class=$PLUGIN_HOOKS['plugin_classes'][$device_type];
					if (class_exists($class)){
						return new $class();
					} 
				} 
			}
			break;
		}
	
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
			$common_fields["device_id"] = $id;
			$common_fields["device_type"] = $type;
			$common_fields["FK_entities"] = $entity;
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
		case COMPUTER_TYPE:
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
			if (!isset($fields["FK_device"]))
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

function getFieldValue($mapping, $mapping_definition,$field_value,$entity,$obj)
{
	global $DB;

	if (isset($mapping_definition["table_type"]))
	{
		switch ($mapping_definition["table_type"])
		{
			//Read and add in a dropdown table
			case "dropdown":
				$obj[$mapping_definition["linkfield"]] = insertDropdownValue($mapping,$mapping_definition,$field_value,$entity);
				break;
				
			//Read in a single table	
			case "single":
				switch ($mapping_definition["table"].".".$mapping_definition["field"])
				{
					//Case of the profiles : get the index of a profile by his name
					case "glpi_profiles.name" :
						$sql = "SELECT ID FROM glpi_profiles WHERE name=".$field_value;
						$result = $DB->query($sql);
						if ($DB->numrows($result))
							$obj[$mapping_definition["field"]] = $DB->result($result,0, "ID");
						break;
					default:
						break;		
				}
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

?>