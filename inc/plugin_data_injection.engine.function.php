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

function lookForNecessaryMappings($model)
{
	global $MANDATORY_MAPPINGS,$DATA_INJECTION_MAPPING;
	$mandatory_fields = $MANDATORY_MAPPINGS[$model->getDeviceType()];
	
	$not_found = array();
	
	if (count($mandatory_fields) > 0)
	{
		$mappings = $model->getMappings();
		foreach ($mandatory_fields as $mandatory_field)
		{
			$found = false;
			foreach ($mappings as $mapping)
				if ($mapping->getValue() == $mandatory_fields)
					$found = true;
			if (!$found)
				$not_found[] = $DATA_INJECTION_MAPPING[$model->getModelType()][$mandatory_fields];
		}
	}
	return $not_found;
}

/**
 * For multitext only ! Check it there's more than one value to inject in a field
 * @model the model
 * @mapping_definition the mapping_definition corresponding to the field
 * @value the value of the mapping
 * @line the complete line to inject
 * @return true if more than one value to inject, false if not
 */
function lookIfSeveralMappingsForField($model,$mapping_definition,$value)
{
	global $MANDATORY_MAPPINGS,$DATA_INJECTION_MAPPING;

	$mapping_count = 0;
	
	if (isset($mapping_definition["table_type"]) && $mapping_definition["table_type"] == "multitext")
	{
		$mappings = $model->getMappings();
		foreach ($model->getMappings() as $mapping)
		{
			if ($mapping->getValue() == $value)
				$mapping_count++;
		}
	}
	
	return ($mapping_count>1?true:false);
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
			case "glpi_entities":
			//nobreak
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
 * 
 * @param device_type the type of the primary item
 * @return an instance of the primary item
 */
function getInstance($device_type)
{
		switch ($device_type)
		{
			case NETPORT_TYPE:
				return new Netport;
			case COMPUTER_CONNECTION_TYPE:
				return null;
			default:
				$commonitem = new CommonItem;
				$commonitem->setType($device_type,1);
				return $commonitem->obj;
		}
}

/*
 * Get the name of a type
 * 
 * @param device_type the type of the item
 * 
 * @return a string
 */
function getInstanceName($device_type)
{
	global $LANG;
	
	if ($device_type == NETPORT_TYPE)
		return $LANG["networking"][6];
		
	$commonitem = new CommonItem;
	$commonitem->setType($device_type);
	return $commonitem->getType();
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
	$setFields = array();
	switch ($type)
	{
		case ENTITY_TYPE:
			$setFields = array("address","postcode","town","state","country","website","phonenumber","fax","email","notes","admin_email","admin_reply");
		break;
		case PHONE_TYPE:
			$setFields = array("contract");
		break;	
		case MONITOR_TYPE:
			$setFields = array("contract");
		break;		
		case ENTERPRISE_TYPE:
			$setFields = array("contract","contact");
		break;		
		case NETWORKING_TYPE:
			$setFields = array("nb_ports","contract");		
		break;
		case PRINTER_TYPE:
			$setFields = array("contract");
		break;	
		case COMPUTER_TYPE:
			$setFields = array("contract");
		break;	
		case SOFTWARE_TYPE:
			$setFields = array("version","serial");
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
			addField($common_fields,"FK_entities",$ID,true);
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
		case SOFTWARE_TYPE:
			$setFields = array("location");
			addField($common_fields,"device_id",$ID,true);
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
	
	//Internal field to identify object injected with data_injection
	addField($fields,"_from_data_injection",1);
	
	$unsetFields = array();
	switch ($type)
	{
		case ENTITY_TYPE:
			$unsetFields = array("address","postcode","town","state","country","website","phonenumber","fax","email","notes","admin_email","admin_reply");
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
			$unsetFields = array("contract");
			addField($fields,"FK_entities",$entity);
			break;
		case MONITOR_TYPE:
			$unsetFields = array("contract");
			addField($fields,"FK_entities",$entity);
			break;
		case PRINTER_TYPE:
			$unsetFields = array("contract");
			addField($fields,"FK_entities",$entity);
			break;
		case PHONE_TYPE:
			$unsetFields = array("contract");
			addField($fields,"FK_entities",$entity);
			break;
		case NETWORKING_TYPE:
			$unsetFields = array("contract","nb_ports");
			addField($fields,"FK_entities",$entity);
			break;
		case PERIPHERAL_TYPE:
			$unsetFields = array("contract","computer_name","computer_serial","computer_otherserial");
			addField($fields,"FK_entities",$entity);
			break;
		case GROUP_TYPE:
			break;
		case ENTERPRISE_TYPE:
			addField($fields,"FK_entities",$entity);
			break;
		case SOFTWARE_TYPE:
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
		case COMPUTER_CONNECTION_TYPE:
			//Set the device_id
			addField($fields,"FK_entities",$entity);
			addField($fields,"device_id",$common_fields["device_id"]);
			addField($fields,"device_type",$model->getDeviceType());
		 	break;
		default:
			//Add entity field for plugins
			if ($type > 1000)
				addField($fields,"FK_entities",$entity);
			break;	
	}
	unsetFields($fields,$unsetFields);
}

/*
 * Check if all value of an array are empty
 * 
 * @param fields : array to check
 * 
 * @return boolean
 * 
 */
function isAllEmpty ($fields) {
	foreach ($fields as $key => $val) {
		if (!empty($val)) {
			return false;
		}
	}
	return true;
}

function getFieldValue($result, $mapping, $mapping_definition,$field_value,$entity,$obj,$canadd,$several)
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
				if ($val > 0) {
					$obj[$mapping_definition["linkfield"]] = $val;
				} else if (!empty($field_value)) {
					$result->addInjectionMessage(WARNING_NOTFOUND,$mapping_definition["linkfield"]."=$field_value");
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
					$result->addInjectionMessage(WARNING_NOTFOUND,$mapping_definition["linkfield"]."='$field_value'");
				}
				break;
			case "multitext":
				//Multitext means that the several input fields can be mapped into one field in DB. all the informations
				//are appended at the end of the field
				if (!isset($obj[$mapping_definition["field"]]))
					$obj[$mapping_definition["field"]]="";
				
				if (!empty($field_value))
				{
					if ($several)
						$obj[$mapping_definition["field"]] .= $mapping->getName()."=".$field_value."\n";
					else
						$obj[$mapping_definition["field"]] .= $field_value."\n";			
				}
				break;	
			case "entity":
				$obj[$mapping_definition["field"]] = checkEntity($field_value,$entity);
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
			addNetworkPorts($common_fields);
			addContract($common_fields);
		break;	
		case PRINTER_TYPE:
			addContract($common_fields);					
		break;
		case LICENSE_TYPE:
			addSoftwareLicensesInfos($common_fields);
		break;
		case COMPUTER_TYPE:
			addContract($common_fields);					
		break;
		case PHONE_TYPE:
			addContract($common_fields);					
		break;	
		case NETPORT_TYPE:
			addVlan($common_fields,$model->getCanAddDropdown());
			addNetpoint($result,$common_fields,$model->getCanAddDropdown());
			addNetworkingWire($result,$common_fields,$model->getCanOverwriteIfNotEmpty());
		break;
		case COMPUTER_CONNECTION_TYPE:
			connectPeripheral($fields);
		break;	
		case ENTITY_TYPE:
			addEntityPostProcess($common_fields);
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

/**
 * Unset the fields when user have no rights to add or modify
 * @param fields the fields to insert into DB
 * @param fields_from_db fields already in DB
 * @param can_overwrite indicates if the model allows datas already in DB to be overwrited
 */
function filterFields(&$fields,$fields_from_db,$can_overwrite,$type)
{
	//If no right to overwrite existing fields in DB -> unset the field
	foreach ($fields as $field=>$value)
	{
		if ($field[0] != '_' && $fields_from_db[$field] && !$can_overwrite)
		{
			$name = getMappingNameByTypeAndValue($type,$field);
			if ( $field == "ID" || ((isset($DATA_INJECTION_MAPPING[$type][$name]['table_type']) &&
			 	$DATA_INJECTION_MAPPING[$type][$name]['table_type'] == "dropdown")
			 	&& $fields_from_db[$field] > 0 ) || $fields_from_db[$field] !=EMPTY_VALUE)
			 		unset ($fields[$field]);
		}	
		
	}
}
?>
