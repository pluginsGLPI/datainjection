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

// Original Author of file: Walid Nouh (walid.nouh@atosorigin.com)
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
	return $name; 
}
/*
 * Reformat datas if needed
 * @param model the model
 * @param line the line of data to inject
 * @return the line modified
 */
function reformatDatasBeforeCheck ($model,$line)
{
	global $DATA_INJECTION_MAPPING;

	$manu="";
	$mappings = $model->getMappings();
	
	// First pass : empty check + find the manufacturer
	foreach ($mappings as $mapping)
	{
		$rank = $mapping->getRank();

		//If a value is set to NULL -> ignore the value during injection
		if (isset($line[$rank]) && $line[$rank] == "NULL") {
			$line[$rank]=EMPTY_VALUE;
		}	
		// Apply manufacturer dictionnary
		else if ($mapping->getValue() == "manufacturer") {
			// TODO : probably a bad idea to add new value in check, but...
			$id = externalImportDropdown('glpi_dropdown_manufacturer', $line[$rank], -1, array(), '', $model->getCanAddDropdown());
			$manu = getDropdownMinimalName('glpi_dropdown_manufacturer', $id);
			$line[$rank] = $manu;
		} 
	}
	
	// Second pass : type check + apply dictionary
	foreach ($mappings as $mapping)
	{
		$rank = $mapping->getRank();
	
		if ($line[$rank] != EMPTY_VALUE && $mapping->getValue() != NOT_MAPPED)
		{
			$mapping_definition = $DATA_INJECTION_MAPPING[$mapping->getMappingType()][$mapping->getValue()];
			
			// Check some types 
			switch ($mapping_definition["type"])
			{
				case "date":
					//If the value is a date, try to reformat it if it's not the good type (dd-mm-yyyy instead of yyyy-mm-dd)
					if (isset($mapping_definition["type"]) && $mapping_definition["type"]=="date")
						$line[$rank] = reformatDate($model->getDateFormat(),$line[$rank]);
					break;
				case "mac":
					$line[$rank]=reformatMacAddress($line[$rank]);
					break;
				default:
				break;
			}
			// Apply dropdown dictionnary
			if (isset($mapping_definition["table_type"]) && $mapping_definition["table_type"]=="dropdown" && $mapping->getValue()!="manufacturer")  {
				$id = externalImportDropdown($mapping_definition["table"], $line[$rank], -1, 
					array("manufacturer" => $manu), '', $model->getCanAddDropdown());
				$val = getDropdownMinimalName($mapping_definition["table"], $id);
				$line[$rank] = $val;					
			}
		}
	}
	return $line;
}

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
		if (!$mandatory && ($data == null || $data == "NULL" || $data == EMPTY_VALUE))
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
				ereg("([0-9]{4})[\-]([0-9]{2})[\-]([0-9]{2})",$data,$regs);
				if (count($regs) > 0)
					return TYPE_CHECK_OK;
				else
					return ERROR_IMPORT_WRONG_TYPE;
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
			if ($mapping->isMandatory() && (!isset($line[$rank]) || $line[$rank] == NULL || $line[$rank] == EMPTY_VALUE || $line[$rank] == -1))
			{
				$res->addCheckMessage(ERROR_IMPORT_FIELD_MANDATORY,$mapping->getName());
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
					if (!$res->addCheckMessage($res_check_type,$mapping->getName())) {
						break;
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

function checkNetpoint($result,$primary_type,$entity,$location,$value,$port_id,$canadd) {
	global $DB;

	// networking device can use netpoint in all the entity
	$sql="SELECT ID FROM glpi_dropdown_netpoint WHERE FK_entities=$entity AND name='$value'";
	
	if ($primary_type != NETWORKING_TYPE) {
		// other device can only use netpoint in the location
		$sql .= " AND location=$location";
	}
	$res = $DB->query($sql);
	if ($DB->numrows($res)>0) {
		// found
		$ID = $DB->result($res,0,"ID");
		
		// Check if not used
		$sql2="SELECT ID FROM glpi_networking_ports WHERE netpoint=$ID AND device_type".($primary_type == NETWORKING_TYPE ?"=":"!=").NETWORKING_TYPE;
		$res2 = $DB->query($sql2);
		if ($DB->numrows($res2)>0) {
			$usedby = $DB->result($res2,0,"ID");
			if ($usedby != $port_id) {
				$result->addInjectionMessage(WARNING_USED, "netpoint");	
			}
			return EMPTY_VALUE;					
		} else {
			return $ID;
		}
					
	} else if ($canadd) {
		$input["tablename"] = "glpi_dropdown_netpoint";
		$input["value"] = $value;
		$input["value2"] = $location;
		$input["FK_entities"] = $entity;
		$input["type"] = EMPTY_VALUE;
		$input["comments"] = EMPTY_VALUE;
		
		return addDropdown($input);
				
	} else {
		$result->addInjectionMessage(WARNING_NOTFOUND, "netpoint");
		return EMPTY_VALUE;			
	}
}

/*
 * Find a user. Look for login OR firstname + lastname OR lastname + firstname
 * @param value the user to look for
 * @param entity the entity where the user should have right
 * @return the user ID if found or ''
 */
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
			return EMPTY_VALUE;	
	}
	else
		return EMPTY_VALUE;		
}


/*
 * Find a user. Look for login OR firstname + lastname OR lastname + firstname
 * @param value the user to look for
 * @param entity the entity where the user should have right
 * @return the user ID if found or ''
 */
function findContact($value,$entity)
{
	global $DB;
	$sql = "SELECT ID FROM glpi_contacts WHERE FK_entities=".$entity." AND (LOWER(name)=\"".strtolower($value)."\" OR (CONCAT(LOWER(name),' ',LOWER(firstname))=\"".strtolower($value)."\" OR CONCAT(LOWER(firstname),' ',LOWER(name))=\"".strtolower($value)."\"))";
	$result = $DB->query($sql);
	if ($DB->numrows($result)>0)
	{
		//check if user has right on the current entity
		return $DB->result($result,0,"ID");
	}
	else
		return EMPTY_VALUE;		
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
		
	if (FieldExists($obj->table, "deleted")) 
		$where .= " AND deleted=0 "; 
    if (FieldExists($obj->table, "is_template")) 
		$where .= " AND is_template=0 "; 
                	
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
			//nobreak
			case "glpi_groups":
			//nobreak
			case "glpi_cartridges":
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
				$where .= " AND device_type=".$model->getDeviceType()." AND FK_device=".$fields["FK_device"];
			break;

			case NETPORT_TYPE :
				$where .= " AND device_type=".$model->getDeviceType()." AND on_device=".$fields["on_device"];
				$where .= " AND logical_number=".$fields["logical_number"];
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
		return array("ID"=>ITEM_NOT_FOUND);
}

/*
 * Get an instance of the primary type
 * @param device_type the type of the primary item
 * @return an instance of the primary item
 */
function getInstance($device_type)
{
		if ($device_type == NETPORT_TYPE)
			return new Netport;
			
		$commonitem = new CommonItem;
		$commonitem->setType($device_type,1);
		return $commonitem->obj;
}

/*
 * Set fields in the common_fields array
 * @param fields the fields to write in DB
 * @param common_fields the array of all the common_fields
 * @param fields_to_set the list of fields to add to the common_fields
 */
function setFields($fields,&$common_fields,$fields_to_set)
{
	foreach ($fields_to_set as $field)
		if (isset($fields[$field]))
			$common_fields[$field]=$fields[$field];
}

/*
 * Set unfields in an array
 * @param fields the fields to write in DB
 * @param fields_to_unset the list of fields to unset from the fields arrary
 */
function unsetFields(&$fields,$fields_to_unset)
{
	foreach ($fields_to_unset as $field)
		if (isset($fields[$field]))
			unset($fields[$field]);
}

function addField(&$array,$field,$value,$check_exists=true)
{
	if ($check_exists && !isset($array[$field]))
		$array[$field]=$value;
	elseif (!$check_exists)
		$array[$field]=$value;	
}
/*
 * Add fields to the common_fields array BEFORE add/update of the primary type
 */
function preAddCommonFields($common_fields,$type,$fields,$entity)
{
	global $IMPORT_NETFIELDS;
	
	$setFields = array();
	switch ($type)
	{
		case ENTITY_TYPE:
			$setFields = array("address","postcode","town","state","country","website","phonenumber","fax","email","notes");
		break;
		case PHONE_TYPE:
			$setFields = array("contract","template");
		break;	
		case MONITOR_TYPE:
			$setFields = array("contract","template");
		break;		
		case ENTERPRISE_TYPE:
			$setFields = array("contract","contact");
		break;		
		case NETWORKING_TYPE:
			$setFields = array("nb_ports","contract","template");		
		break;
		case PRINTER_TYPE:
			$setFields = array("contract","template");
		break;	
		case COMPUTER_TYPE:
			$setFields = array("contract","template");
		break;	
		case NETPORT_TYPE:
			$setFields = array("netpoint","vlan","netname","netport");
		break;	
		default:
		break;
	}
	setFields($fields,$common_fields,$setFields);
	return $common_fields;
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
function addCommonFields(&$common_fields,$type,$fields,$entity,$ID)
{
	$setFields=array();
	switch ($type)
	{
		//Copy/paste is voluntary in order to know exactly which fields are included or not
		case NETPORT_TYPE:
			addField($common_fields,"network_port_id",$ID,true);
			break;
		case ENTITY_TYPE:
			break;
		case CONTACT_TYPE:
			addField($common_fields,"FK_entities",$entity,false);
			break;
		case CARTRIDGE_TYPE:
			addField($common_fields,"device_id",$ID,true);
			addField($common_fields,"device_type",$type,false);
			addField($common_fields,"FK_entities",$entity,false);
			break;
		case CONSUMABLE_TYPE:
			addField($common_fields,"device_id",$ID,true);
			addField($common_fields,"device_type",$type,false);
			addField($common_fields,"FK_entities",$entity,false);
			break;
		case ENTERPRISE_TYPE:
			addField($common_fields,"device_id",$ID,true);
			addField($common_fields,"device_type",$type,false);
			addField($common_fields,"FK_entities",$entity,false);
			break;
		case COMPUTER_TYPE:
			$setFields = array("location");
			addField($common_fields,"device_id",$ID,true);
			addField($common_fields,"device_type",$type,false);
			addField($common_fields,"FK_entities",$entity,false);
			break;
		case MONITOR_TYPE:
			$setFields = array("location");
			addField($common_fields,"device_id",$ID,true);
			addField($common_fields,"device_type",$type,false);
			addField($common_fields,"FK_entities",$entity,false);
			break;
		case PRINTER_TYPE:
			$setFields = array("location");
			addField($common_fields,"device_id",$ID,true);
			addField($common_fields,"device_type",$type,false);
			addField($common_fields,"FK_entities",$entity,false);
			break;
		case NETWORKING_TYPE:
			$setFields = array("location");
			addField($common_fields,"device_id",$ID,true);
			addField($common_fields,"device_type",$type,false);
			addField($common_fields,"FK_entities",$entity,false);
			break;
		case PHONE_TYPE:
			$setFields = array("location");
			addField($common_fields,"device_id",$ID,true);
			addField($common_fields,"device_type",$type,false);
			addField($common_fields,"FK_entities",$entity,false);
			break;
		case PERIPHERAL_TYPE:
			$setFields = array("location");
			addField($common_fields,"device_id",$ID,true);
			addField($common_fields,"device_type",$type,false);
			addField($common_fields,"FK_entities",$entity,false);
			break;
		case GROUP_TYPE:
			break;
		case CONTRACT_TYPE:
			addField($common_fields,"FK_entities",$entity,false);
			break;
		case USER_TYPE:
			addField($common_fields,"FK_user",$ID,false);
			$setFields = array("FK_group");	
			break;		
		default:
			break;	
	}	

	setFields($fields,$common_fields,$setFields);
}

/*
 * Add necessary fields
 * @param model the current model
 * @param mapping the current mapping
 * @param mapping_definition the mapping definition associated to the mapping
 * @param entity the current entity
 * @param type the device type
 * @param fields the fields to insert into DB
 * @param common_fields the array of common fields
 * @return the fields modified
 */
function addNecessaryFields($model,$mapping,$mapping_definition,$entity,$type,&$fields,$common_fields)
{
	global $DB;

	$unsetFields = array();
	switch ($type)
	{
		case ENTITY_TYPE:
			$unsetFields = array("address","postcode","town","state","country","website","phonenumber","fax","email","notes");
			break;
		case CONTACT_TYPE:
			addField($fields,"FK_entities",$entity);
			break;
		case CONSUMABLE_TYPE:
			addField($fields,"FK_entities",$entity);
			break;
		case CARTRIDGE_TYPE:
			addField($fields,"FK_entities",$entity);
			break;
		case CARTRIDGE_ITEM_TYPE:
			break;
		case COMPUTER_TYPE:
			$unsetFields = array("contract","template");
			addField($fields,"FK_entities",$entity);
			break;
		case MONITOR_TYPE:
			$unsetFields = array("contract");
			addField($fields,"FK_entities",$entity);
			break;
		case PRINTER_TYPE:
			$unsetFields = array("contract","template");
			addField($fields,"FK_entities",$entity);
			break;
		case PHONE_TYPE:
			$unsetFields = array("contract","template");
			addField($fields,"FK_entities",$entity);
			break;
		case NETWORKING_TYPE:
			$unsetFields = array("contract","nb_ports","template");
			addField($fields,"FK_entities",$entity);
			break;
		case PERIPHERAL_TYPE:
			$unsetFields = array("contract");
			addField($fields,"FK_entities",$entity);
			break;

		case GROUP_TYPE:
			break;
		case ENTERPRISE_TYPE:
			addField($fields,"FK_entities",$entity);
			break;
		case CONTRACT_TYPE:
			addField($fields,"FK_entities",$entity);
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
			addField($fields,"auth_method",AUTH_DB_GLPI);	

			break;
		case INFOCOM_TYPE:
			//Set the device_id
			addField($fields,"FK_device",$common_fields["device_id"]);
					
			//Set the device type
			addField($fields,"device_type",$model->getDeviceType());
			break;
		case NETPORT_TYPE:
			if (!isset($fields["logical_number"]) || empty($fields["logical_number"])) {
				$fields["logical_number"] = ($model->getDeviceType()==NETWORKING_TYPE ? 1 : 0);				
			} 
			$unsetFields = array("netpoint","vlan","netport","netname");

			//Set the device_id
			addField($fields,"on_device",$common_fields["device_id"]);
					
			//Set the device type
			addField($fields,"device_type",$model->getDeviceType());
			
			
			break;
		default:
			break;	
	}
	unsetFields($fields,$unsetFields);
}

function getFieldValue($result, $mapping, $mapping_definition,$field_value,$entity,$obj,$canadd)
{
	global $DB, $CFG_GLPI;
	
	if (isset($mapping_definition["table_type"]))
	{
		switch ($mapping_definition["table_type"])
		{
			case "template":
				$obj[$mapping_definition["linkfield"]] = findTemplate($entity,$mapping_definition["table"],$field_value);
				break;
			//Read and add in a dropdown table
			case "dropdown":
				$val = getDropdownValue($mapping,$mapping_definition,$field_value,$entity,$canadd);
				if ($val == EMPTY_VALUE) {
					$result->addInjectionMessage(WARNING_NOTFOUND,$mapping_definition["linkfield"]);
				} else {
					$obj[$mapping_definition["linkfield"]] = $val;
				}
				break;
			case "contact":
				//find a contact by looking into name field OR firstname + name OR name + firstname
				$obj[$mapping_definition["linkfield"]] = findContact($field_value,$entity);
				break;		
			case "user":
				//find a user by looking into login field OR firstname + lastname OR lastname + firstname
				$obj[$mapping_definition["linkfield"]] = findUser($field_value,$entity);
				break;
				
			//Read in a single table	
			case "single":
				$sql = "SELECT ID FROM ".$mapping_definition["table"]." WHERE ".$mapping_definition["field"]."='".$field_value."'";
				
				if (in_array($mapping_definition["table"], $CFG_GLPI["specif_entities_tables"])) {
					$sql .= getEntitiesRestrictRequest($separator = " AND ", $mapping_definition["table"], '', $entity, 
						in_array($mapping_definition["table"], $CFG_GLPI["recursive_type"]));
				}
				$res = $DB->query($sql);
				if ($DB->numrows($res)) {
					$obj[$mapping_definition["linkfield"]] = $DB->result($res,0, "ID");					
				} else {
					$result->addInjectionMessage(WARNING_NOTFOUND,$mapping_definition["linkfield"]);
				}
				break;
			case "multitext":
				//Multitext means that the several input fields can be mapped into one field in DB. all the informations
				//are appended at the end of the field
				if (!isset($obj[$mapping_definition["field"]]))
					$obj[$mapping_definition["field"]]="";
					
				if (!empty($field_value))
					$obj[$mapping_definition["field"]] .= $mapping->getName()."=".$field_value."\n";		
				break;	
			case "virtual":
			//nobreak
			default :
				$obj[$mapping_definition["field"]] = $field_value;
				break;
		}
	}
	else
		$obj[$mapping_definition["field"]] = $field_value;
	
	return $obj;
}

/*
 * Process actions after item was imported in DB (mainly create connections)
 * 
 * @param result the result set
 * @param model the model
 * @param type the type of the item inserted
 * @param fields the fields of the item inserted
 * @param common_fields the array of common fields
 * @return the common_fields
 */
function processBeforeEnd($result,$model,$type,$fields,&$common_fields)
{
	switch ($type)
	{
		case ENTERPRISE_TYPE:
			addContract($common_fields);
			addContact($common_fields);					
		break;		
		case USER_TYPE:
			//If user ID is given, add the user in this group
			if (isset($common_fields["FK_user"]) && isset($common_fields["FK_group"]))
				addUserGroup($common_fields["FK_user"],$common_fields["FK_group"]);
		break;
		case NETWORKING_TYPE:
			updateWithTemplate($common_fields);
			addNetworkPorts($common_fields);
			addContract($common_fields);
		break;	
		case PRINTER_TYPE:
			updateWithTemplate($common_fields);
			addContract($common_fields);					
		break;
		case MONITOR_TYPE:
			updateWithTemplate($common_fields);
		break;
		case COMPUTER_TYPE:
			updateWithTemplate($common_fields);
			addContract($common_fields);					
		break;
		case PHONE_TYPE:
			updateWithTemplate($common_fields);
			addContract($common_fields);					
		break;	
		case NETPORT_TYPE:
			addVlan($common_fields,$model->getCanAddDropdown());
			addNetpoint($result,$common_fields,$model->getCanAddDropdown());
			addNetworkingWire($result,$common_fields,$model->getCanOverwriteIfNotEmpty());
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

/*
 * Add the complementary informations into the list of fields to insert in DB
 * @param fields the fields to insert into DB
 * @param infos the informations filled by the user when injecting his file
 * @return the fields modified
 */	
function addInfosFields($fields,$infos)
{
	global $DATA_INJECTION_INFOS;
	
	foreach ($infos as $info)
		if (keepInfo($info))
		{	
			if (isset($fields[$info->getInfosType()][$info->getValue()]) && isset($DATA_INJECTION_INFOS[$info->getInfosType()][$info->getValue()]['table_type']) && $DATA_INJECTION_INFOS[$info->getInfosType()][$info->getValue()]['table_type'] == "multitext")
				$fields[$info->getInfosType()][$info->getValue()] .= "\n".$info->getInfosText();
			else
				$fields[$info->getInfosType()][$info->getValue()] = $info->getInfosText();
		}
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
			if ($info->getInfosText() != NULL && $info->getInfosText() != EMPTY_VALUE)
				return true;
		break;
		case "dropdown":
			if ($info->getInfosText() != 0)
				return true;
		break;		
	}	
	return false;
}

/*
 * Log event into the history
 * @param device_type the type of the item to inject
 * @param device_id the id of the inserted item
 * @param the action_type the type of action(add or update)
 */
function logAddOrUpdate($device_type,$device_id,$action_type)
{
	global $DATAINJECTIONLANG;
	
	//Do not add history for users and groups
	if($device_type != USER_TYPE && $device_type != GROUP_TYPE)
		{
		$changes[0]=0;
		
		if ($action_type == INJECTION_ADD)
			$changes[2] = $DATAINJECTIONLANG["result"][8]." ".$DATAINJECTIONLANG["history"][1];
		else
			$changes[2] = $DATAINJECTIONLANG["result"][9]." ".$DATAINJECTIONLANG["history"][1];
		
		$changes[1] = "";		
		historyLog ($device_id,$device_type,$changes,0,HISTORY_LOG_SIMPLE_MESSAGE);
	}
}

/*
 * Unset the fields when user have no rights to add or modify
 * @param fields the fields to insert into DB
 * @param fields_from_db fields already in DB
 * @param can_overwrite indicates if the model allows datas already in DB to be overwrited
 */
function filterFields(&$fields,$fields_from_db,$can_overwrite)
{
	//If no right to overwrite existing fields in DB -> unset the field
	foreach ($fields as $field=>$value)
		if ($field != "ID" && !$can_overwrite && (isset($fields_from_db[$field])))
			unset ($fields[$field]);

}

/*
 * Create a tree of locations
 * @param location the full tree of locations
 * @param entity the current entity
 * @param canadd indicates if the user has the right to add locations
 * @return the location ID
 */
function checkLocation ($location, $entity, $canadd)
{
	$location_id = 0;
	$locations = explode('>',$location);
	
	foreach ($locations as $location)
		if ($location_id !== EMPTY_VALUE)
			$location_id = addLocation(trim($location),$entity,$location_id,$canadd);
		
	return $location_id;	
}

/*
 * Add a location at a specified level
 * @param location the full tree of locations
 * @param entity the current entity
 * @param the parentid ID of the parent location
 * @param canadd indicates if the user has the right to add locations
 * @return the location ID
 */
function addLocation($location,$entity,$parentid,$canadd)
{
	$input["tablename"] = "glpi_dropdown_locations";
	$input["value"] = $location;
	$input["value2"] = $parentid;
	$input["type"] = "under";
	$input["comments"] = EMPTY_VALUE;
	$input["FK_entities"] = $entity;
	
	$ID = getDropdownID($input);
	
	if ($ID != -1)
		return $ID;

	if ($canadd)	
		return addDropdown($input);
	else
		return EMPTY_VALUE;	
}

/*
 * Reformat date from dd-mm-yyyy to yyyy-mm-dd
 * @param original_date the original date
 * @return the date reformated, if needed
 */
function reformatDate($date_format,$original_date)
{
	switch ($date_format)
	{
		case DATE_TYPE_YYYYMMDD:
			$new_date=preg_replace('/(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})/','\1-\2-\3',$original_date);
		break;	
		case DATE_TYPE_DDMMYYYY:
			$new_date=preg_replace('/(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})/','\3-\2-\1',$original_date);
		break;
		case DATE_TYPE_MMDDYYYY:
			$new_date=preg_replace('/(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})/','\3-\1-\2',$original_date);
		break;
	}
	
	if (ereg('[0-9]{2,4}-[0-9]{1,2}-[0-9]{1,2}',$new_date))
		return $new_date;
	else
		return $original_date;	
}

/*
 * Reformat mac adress if mac doesn't contains : or - as seperator
 * @param mac the original mac address
 * @return the mac address modified, if needed
 */
function reformatMacAddress($mac)
{
	preg_match("/^([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/",$mac,$results);
	if (count($results) > 0)
	{
		$mac="";
		$first=true;
		unset($results[0]);
		foreach($results as $result)
		{
			$mac.=(!$first?":":"").$result;
			$first=false;
		}
	}
	return $mac;
}

function findTemplate($entity,$table,$value)
{
	global $DB;
	$result = $DB->query("SELECT ID FROM ".$table." WHERE FK_entities=".$entity." AND tplname='".addslashes($value)."'");
	if ($DB->numrows($result)==1)
		return $DB->result($result,0,"ID");
	else	
		return 0;
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