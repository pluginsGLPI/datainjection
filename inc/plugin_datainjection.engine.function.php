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

function lookForNecessaryMappings($model) {
	global $MANDATORY_MAPPINGS, $DATA_INJECTION_MAPPING;
	$mandatory_fields = $MANDATORY_MAPPINGS[$model->getDeviceType()];

	$not_found = array ();

	if (count($mandatory_fields) > 0) {
		$mappings = $model->getMappings();
		foreach ($mandatory_fields as $mandatory_field) {
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
function lookIfSeveralMappingsForField($model, $mapping_definition, $value) {
	global $MANDATORY_MAPPINGS, $DATA_INJECTION_MAPPING;

	$mapping_count = 0;

	if (isset ($mapping_definition["table_type"]) && $mapping_definition["table_type"] == "multitext") {
		$mappings = $model->getMappings();
		foreach ($model->getMappings() as $mapping) {
			if ($mapping->getValue() == $value)
				$mapping_count++;
		}
	}

	return ($mapping_count > 1 ? true : false);
}

/**
 * Function to check if the datas to inject already exists in DB
 * @param type the type of datas to inject
 * @param fields the datas to inject
 * @param mapping_definition the definition of the mapping
 * @param model the current injection model
 * @return true if the data exists, false if it doesn't already exists
 */
function dataAlreadyInDB($type, $fields, $mapping_definition, $model) {
	global $DB,$CFG_GLPI;
	$where = "";
	$mandatories = getAllMandatoriesMappings($type, $model);

   $primary = ($model->getDeviceType() == $type?true:false);

	$obj = getInstance($type);

	$delimiter = "'";

	if (in_array($type,$CFG_GLPI["deleted_tables"])) {
		$where .= " AND deleted=0 ";
	}
   if (in_array($obj->table,$CFG_GLPI["template_tables"])) {
      $where .= " AND is_template=0 ";
   }
		
	if ($primary) {
		foreach ($mandatories as $mapping) {
			$mapping_definition = getMappingDefinitionByTypeAndName($type, $mapping->getValue());
			$where .= " AND " . $mapping->getValue() . "=" . $delimiter . $fields[$mapping->getValue()] . $delimiter;
		}

		switch ($obj->table) {
			//Devices types
         case "glpi_profiles" :
			case "glpi_device_moboard" :
			case "glpi_device_processor" :
			case "glpi_device_ram" :
			case "glpi_device_hdd" :
			case "glpi_device_iface" :
			case "glpi_device_drive" :
			case "glpi_device_control" :
			case "glpi_device_gfxcard" :
			case "glpi_device_sndcard" :
			case "glpi_device_pci" :
			case "glpi_device_case" :
			case "glpi_device_power" :

 			case "glpi_entities" :
				//nobreak
			case "glpi_users" :
				//nobreak
			case "glpi_cartridges" :
				$where_entity = " 1";
				break;
        case "glpi_softwarelicenses" :
            $where_entity = getEntitiesRestrictRequest("","glpi_softwarelicenses","FK_entities",$fields["FK_entities"],true);
            $where_entity .= " AND sID=" . $fields["sID"];
            break;
        case "glpi_softwareversions" :
            $where_entity = " sID=" . $fields["sID"];
            break;
 			default :
            if (isset($CFG_GLPI["recursive_type"][$type])) {
            	$where_entity = getEntitiesRestrictRequest("",$obj->table,"FK_entities",$fields["FK_entities"],true);
            }
            else {
               $where_entity = " FK_entities=" . $fields["FK_entities"];	
            }
				
				break;
		}
	} else {
		$where_entity = " 1";
		switch ($type) {
			case INFOCOM_TYPE :
				$where .= " AND device_type=" . $model->getDeviceType() . " AND FK_device=" . $fields["FK_device"];
				break;

			case NETPORT_TYPE :
				$where .= " AND device_type=" . $model->getDeviceType() . " AND on_device=" . $fields["on_device"];
				$where .= getPortUnicityRequest($model,$fields);
				break;
			default :
				break;
		}
	}

	$sql = "SELECT * FROM " . $obj->table . " WHERE" . $where_entity . " " . $where;
	$result = $DB->query($sql);

	if ($DB->numrows($result) > 0)
		return $DB->fetch_array($result);
	else
		return array (
			"ID" => ITEM_NOT_FOUND
		);
}

/**
 * Get an instance of the primary type
 * 
 * @param device_type the type of the primary item
 * @return an instance of the primary item
 */
function getInstance($device_type) {
	switch ($device_type) {

		//Devices
		case PLUGIN_DATA_INJECTION_MOBOARD_DEVICE_TYPE :
			return new Device(MOBOARD_DEVICE);
		case PLUGIN_DATA_INJECTION_PROCESSOR_DEVICE_TYPE :
			return new Device(PROCESSOR_DEVICE);
		case PLUGIN_DATA_INJECTION_RAM_DEVICE_TYPE :
			return new Device(RAM_DEVICE);
		case PLUGIN_DATA_INJECTION_HDD_DEVICE_TYPE :
			return new Device(HDD_DEVICE);
		case PLUGIN_DATA_INJECTION_NETWORK_DEVICE_TYPE :
			return new Device(NETWORK_DEVICE);
		case PLUGIN_DATA_INJECTION_DRIVE_DEVICE_TYPE :
			return new Device(DRIVE_DEVICE);
		case PLUGIN_DATA_INJECTION_CONTROL_DEVICE_TYPE :
			return new Device(CONTROL_DEVICE);
		case PLUGIN_DATA_INJECTION_GFX_DEVICE_TYPE :
			return new Device(GFX_DEVICE);
		case PLUGIN_DATA_INJECTION_SND_DEVICE_TYPE :
			return new Device(PCI_DEVICE);
		case PLUGIN_DATA_INJECTION_CASE_DEVICE_TYPE :
			return new Device(CASE_DEVICE);
		case PLUGIN_DATA_INJECTION_POWER_DEVICE_TYPE :
			return new Device(POWER_DEVICE);
		case PLUGIN_DATA_INJECTION_PCI_DEVICE_TYPE :
			return new Device(PCI_DEVICE);

			//Network ports	
		case NETPORT_TYPE :
			return new Netport;

			//Connection to computers
		case COMPUTER_CONNECTION_TYPE :
			return null;

			//Plugins
		default :
			$commonitem = new CommonItem;
			$commonitem->setType($device_type, 1);
			return $commonitem->obj;
	}
}

/**
 * Get the name of a type
 * 
 * @param device_type the type of the item
 * 
 * @return a string
 */
function getInstanceName($device_type) {
	global $LANG;

	if ($device_type == NETPORT_TYPE)
		return $LANG["networking"][6];

	$commonitem = new CommonItem;
	$commonitem->setType($device_type);
	return $commonitem->getType();
}

/**
 * Set fields in the common_fields array
 * @param fields the fields to write in DB
 * @param common_fields the array of all the common_fields
 * @param fields_to_set the list of fields to add to the common_fields
 */
function setFields($fields, & $common_fields, $fields_to_set) {
	foreach ($fields_to_set as $field)
		if (isset ($fields[$field]))
			$common_fields[$field] = $fields[$field];
}

/**
 * Set unfields in an array
 * @param fields the fields to write in DB
 * @param fields_to_unset the list of fields to unset from the fields arrary
 */
function unsetFields(& $fields, $fields_to_unset) {
	foreach ($fields_to_unset as $field)
		if (isset ($fields[$field]))
			unset ($fields[$field]);
}

function addField(& $array, $field, $value, $check_exists = true) {
	if ($check_exists && !isset ($array[$field]))
		$array[$field] = $value;
	elseif (!$check_exists) $array[$field] = $value;
}
/**
 * Add fields to the common_fields array BEFORE add/update of the primary type
 */
function preAddCommonFields($common_fields, $type, $fields, $entity) {
	global $PLUGIN_HOOKS;
   $setFields = array ();
	switch ($type) {
		case ENTITY_TYPE :
			$setFields = array (
				"address",
				"postcode",
				"town",
				"state",
				"country",
				"website",
				"phonenumber",
				"fax",
				"email",
				"notes",
				"admin_email",
				"admin_reply",
            "tag",
            "ldap_dn"
			);
			break;
		case PHONE_TYPE :
			$setFields = array (
				"contract"
			);
			break;
		case MONITOR_TYPE :
			$setFields = array (
				"contract"
			);
			break;
		case ENTERPRISE_TYPE :
			$setFields = array (
				"contract",
				"contact"
			);
			break;
		case NETWORKING_TYPE :
			$setFields = array (
				"nb_ports",
				"contract",
				"ifmac",
				"name"
			);
			break;
		case PRINTER_TYPE :
			$setFields = array (
				"contract"
			);
			break;
		case COMPUTER_TYPE :
			$setFields = array (
				"contract"
			);
			break;
		case SOFTWARE_TYPE :
			$setFields = array (
				"version",
				"serial"
			);
			break;
		case NETPORT_TYPE :
			$setFields = array (
				"netpoint",
				"vlan",
				"netname",
				"netport",
				"netmac",
				"name",
				"netmac",
				"ifmac"
			);
			break;
		case COMPUTER_CONNECTION_TYPE:
			$setFields = array (
				"device_id",
			);
		case CONNECTION_ALL_TYPES:
			$setFields = array (
				"device_type",
				"name",
				"ID"
			);
			break;				
		default :
            if ($type >= 1000) {
                  $params = array();
                  $setFields = doOneHook($PLUGIN_HOOKS['plugin_types'][$type],"datainjection_preAddCommonFields",$params);
            }

			break;
	}
	setFields($fields, $common_fields, $setFields);
	return $common_fields;
}

/**
 * Add new values to the array of common values
 * @param common_fields the array of common values
 * @param type the type of value
 * @param fields the fields associated with the type
 * @param entity the current entity
 * @param id the ID of the main object
 * @return the update common values array
 */
function addCommonFields(& $common_fields, $type, $fields, $entity, $ID) {
	global $PLUGIN_HOOKS;
   $setFields = array ();
	switch ($type) {
		//Copy/paste is voluntary in order to know exactly which fields are included or not
		case NETPORT_TYPE :
			addField($common_fields, "network_port_id", $ID, true);
			break;
		case ENTITY_TYPE :
			addField($common_fields, "FK_entities", $ID, true);
			break;
		case CONTACT_TYPE :
			addField($common_fields, "FK_entities", $entity, false);
			break;
		case CARTRIDGE_TYPE :
			addField($common_fields, "device_id", $ID, true);
			addField($common_fields, "device_type", $type, false);
			addField($common_fields, "FK_entities", $entity, false);
			break;
		case CONSUMABLE_TYPE :
			addField($common_fields, "device_id", $ID, true);
			addField($common_fields, "device_type", $type, false);
			addField($common_fields, "FK_entities", $entity, false);
			break;
		case ENTERPRISE_TYPE :
			addField($common_fields, "device_id", $ID, true);
			addField($common_fields, "device_type", $type, false);
			addField($common_fields, "FK_entities", $entity, false);
			break;
		case COMPUTER_TYPE :
			$setFields = array (
				"location"
			);
			addField($common_fields, "device_id", $ID, true);
			addField($common_fields, "device_type", $type, false);
			addField($common_fields, "FK_entities", $entity, false);
			break;
		case SOFTWARE_TYPE :
			$setFields = array (
				"location"
			);
			addField($common_fields, "device_id", $ID, true);
			addField($common_fields, "FK_entities", $entity, false);
			break;
        case DOCUMENT_TYPE : 
            addField($common_fields, "FK_entities", $entity, false); 
			addField($common_fields, "device_id", $ID, true);
            break; 
		case MONITOR_TYPE :
			$setFields = array (
				"location"
			);
			addField($common_fields, "device_id", $ID, true);
			addField($common_fields, "device_type", $type, false);
			addField($common_fields, "FK_entities", $entity, false);
			break;
		case PRINTER_TYPE :
			$setFields = array (
				"location"
			);
			addField($common_fields, "device_id", $ID, true);
			addField($common_fields, "device_type", $type, false);
			addField($common_fields, "FK_entities", $entity, false);
			break;
		case NETWORKING_TYPE :
			$setFields = array (
				"location"
			);
			addField($common_fields, "device_id", $ID, true);
			addField($common_fields, "device_type", $type, false);
			addField($common_fields, "FK_entities", $entity, false);
			break;
		case PHONE_TYPE :
			$setFields = array (
				"location"
			);
			addField($common_fields, "device_id", $ID, true);
			addField($common_fields, "device_type", $type, false);
			addField($common_fields, "FK_entities", $entity, false);
			break;
		case PERIPHERAL_TYPE :
			$setFields = array (
				"location"
			);
			addField($common_fields, "device_id", $ID, true);
			addField($common_fields, "device_type", $type, false);
			addField($common_fields, "FK_entities", $entity, false);
			break;
		case GROUP_TYPE :
			break;
		case CONTRACT_TYPE :
			addField($common_fields, "FK_entities", $entity, false);
			break;
		case USER_TYPE :
			addField($common_fields, "FK_user", $ID, false);
			$setFields = array (
				"FK_group"
			);
			break;

      case SOFTWARELICENSE_TYPE:
         addField($common_fields, "device_id", $ID, true);
         addField($common_fields, "device_type", $type, false);
         addField($common_fields, "FK_entities", $entity, false);
         break;	
         	
		//Device types
		case PLUGIN_DATA_INJECTION_MOBOARD_DEVICE_TYPE :
		case PLUGIN_DATA_INJECTION_PROCESSOR_DEVICE_TYPE :
		case PLUGIN_DATA_INJECTION_RAM_DEVICE_TYPE :
		case PLUGIN_DATA_INJECTION_HDD_DEVICE_TYPE :
		case PLUGIN_DATA_INJECTION_NETWORK_DEVICE_TYPE :
		case PLUGIN_DATA_INJECTION_DRIVE_DEVICE_TYPE :
		case PLUGIN_DATA_INJECTION_CONTROL_DEVICE_TYPE :
		case PLUGIN_DATA_INJECTION_GFX_DEVICE_TYPE :
		case PLUGIN_DATA_INJECTION_SND_DEVICE_TYPE :
		case PLUGIN_DATA_INJECTION_PCI_DEVICE_TYPE :
		case PLUGIN_DATA_INJECTION_CASE_DEVICE_TYPE :
		case PLUGIN_DATA_INJECTION_POWER_DEVICE_TYPE :
			addField($common_fields, "device_id", $ID, true);
			addField($common_fields, "device_type", $type, false);
		default :
            if ($type >= 1000) {
                  $params = array("fields"=>$fields,"ID"=>$ID, "entity"=>$entity,"common_fields"=>$common_fields);
                  $hook_result = doOneHook($PLUGIN_HOOKS['plugin_types'][$type],"datainjection_addCommonFields",$params);
                  $common_fields = $hook_result["common_fields"];
                  $setFields = $hook_result["setfields"];
            }

			break;
	}

	setFields($fields, $common_fields, $setFields);
}

/**
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
function addNecessaryFields($model, $mapping, $mapping_definition, $entity, $type, & $fields, $common_fields) {
	global $DB,$PLUGIN_HOOKS;

	//Internal field to identify object injected with datainjection
	addField($fields, "_from_datainjection", 1);

	$unsetFields = array ();
	switch ($type) {
		case ENTITY_TYPE :
			$unsetFields = array (
				"address",
				"postcode",
				"town",
				"state",
				"country",
				"website",
				"phonenumber",
				"fax",
				"email",
				"notes",
				"admin_email",
				"admin_reply",
            "tag",
            "ldap_dn"
			);
			break;
		case CONTACT_TYPE :
			addField($fields, "FK_entities", $entity);
			break;
		case DOCUMENT_TYPE : 
            addField($fields, "FK_entities", $entity); 
            break; 
		case CONSUMABLE_TYPE :
			addField($fields, "FK_entities", $entity);
			break;
		case CARTRIDGE_TYPE :
			addField($fields, "FK_entities", $entity);
			break;
		case CARTRIDGE_ITEM_TYPE :
			break;
		case COMPUTER_TYPE :
			$unsetFields = array (
				"contract"
			);
			addField($fields, "FK_entities", $entity);
			break;
		case MONITOR_TYPE :
			$unsetFields = array (
				"contract"
			);
			addField($fields, "FK_entities", $entity);
			break;
		case PRINTER_TYPE :
			$unsetFields = array (
				"contract"
			);
			addField($fields, "FK_entities", $entity);
			break;
		case PHONE_TYPE :
			$unsetFields = array (
				"contract"
			);
			addField($fields, "FK_entities", $entity);
			break;
		case NETWORKING_TYPE :
			$unsetFields = array (
				"contract",
				"nb_ports"
			);
			addField($fields, "FK_entities", $entity);
			break;
		case PERIPHERAL_TYPE :
			$unsetFields = array (
				"contract",
				"computer_name",
				"computer_serial",
				"computer_otherserial"
			);
			addField($fields, "FK_entities", $entity);
			break;
		case GROUP_TYPE :
         addField($fields, "FK_entities", $entity);
			break;
		case ENTERPRISE_TYPE :
			addField($fields, "FK_entities", $entity);
			break;
		case SOFTWARE_TYPE :
			addField($fields, "FK_entities", $entity);
			break;
      case KNOWBASE_TYPE :
         addField($fields, "FK_entities", $entity);
         break;
		case CONTRACT_TYPE :
			addField($fields, "FK_entities", $entity);
			break;
		case USER_TYPE :
			addField($fields, "FK_entities", $entity);
			if (isset ($fields["password"])) {
				if (empty ($fields["password"])) {
					unset ($fields["password"]);
				} else {
					$fields["password_md5"] = md5(unclean_cross_side_scripting_deep($fields["password"]));
					$fields["password"] = "";
				}
			}

			//Add auth and profiles fields	
			addField($fields, "auth_method", AUTH_DB_GLPI);

			break;
		case INFOCOM_TYPE :
			//Set the device_id
			addField($fields, "FK_device", $common_fields["device_id"]);

			//Set the device type
			addField($fields, "device_type", $model->getDeviceType());
			break;
		case NETPORT_TYPE :
			$unsetFields = array (
				"netpoint",
				"vlan",
				"netport",
				"netname",
				"netmac",
			);

			//Set the device_id
			addField($fields, "on_device", $common_fields["device_id"]);

			//Set the device type
			addField($fields, "device_type", $model->getDeviceType());
			break;

		//Object connections
		case COMPUTER_CONNECTION_TYPE :
			//Set the device_id
			addField($fields, "FK_entities", $entity);
			addField($fields, "device_id", $common_fields["device_id"]);
			addField($fields, "device_type", $common_fields["device_type"]);
			break;
		case CONNECTION_ALL_TYPES :
			//Set the device_id
			addField($fields, "FK_entities", $entity);
			addField($fields, "device_id", $common_fields["device_id"]);
			break;

      case SOFTWARELICENSE_TYPE :
         //Set the device_id
         addField($fields, "FK_entities", $entity);
         break;
			
		default :
            if ($type >= 1000) {
                  //Add entity field for plugins
                  addField($fields, "FK_entities", $entity);
                  
                  $params = array("fields"=>$fields,
                                    "common_fields"=>$common_fields);
                  $fields_to_add = doOneHook($PLUGIN_HOOKS['plugin_types'][$type],"datainjection_addNecessaryFields",$params);
                  foreach ($fields_to_add as $field_to_add => $value_to_add) {
                  	addField($fields,$field_to_add,$value_to_add);
                  }
            }
			break;
	}
	unsetFields($fields, $unsetFields);
}

/**
 * Check if all value of an array are empty
 * 
 * @param fields : array to check
 * 
 * @return boolean
 * 
 */
function isAllEmpty($fields) {
	foreach ($fields as $key => $val) {
		if (!empty ($val)) {
			return false;
		}
	}
	return true;
}

/**
 * Get the value in database for a field
 * @param result the array to store injection's results
 * @param mapping the current mapping
 * @param mapping_definition the current mapping_definition
 * @param field_value the field value
 * @param entity the current entity
 * @param obj the object
 * @param canadd can add values
 * @param several can map several values in one field
 * @param fields_comments the comments associated with a dropdown
 * @return a table with the field's values
 */
function getFieldValue($result, $type, $mapping, $mapping_definition, $field_value, $entity, $obj, $canadd, $several,$field_comments = EMPTY_VALUE) {
	global $DB, $CFG_GLPI,$PLUGIN_HOOKS;

	if (isset ($mapping_definition["table_type"])) {
		switch ($mapping_definition["table_type"]) {
 			case "template" :
				$obj[$mapping_definition["linkfield"]] = findTemplate($entity, $mapping_definition["table"], $field_value);
				break;
				//Read and add in a dropdown table
			case "dropdown" :
				$val = getDropdownValue($mapping, $mapping_definition, $field_value, $entity, $canadd,$field_comments);
				if ($val > 0) {
					$obj[$mapping_definition["linkfield"]] = $val;
				} else
					if (!empty ($field_value)) {
						$result->addInjectionMessage(WARNING_NOTFOUND, $mapping_definition["linkfield"] . "=$field_value");
					}
				break;
			case "contact" :
				//find a contact by looking into name field OR firstname + name OR name + firstname
				$obj[$mapping_definition["linkfield"]] = findContact($field_value, $entity);
				break;
			case "user" :
				//find a user by looking into login field OR firstname + lastname OR lastname + firstname
				$obj[$mapping_definition["linkfield"]] = findUser($field_value, $entity);
				break;

				//Read in a single table	
			case "single" :
				$sql = "SELECT ID FROM " . $mapping_definition["table"] . " WHERE " . $mapping_definition["field"] . "='" . $field_value . "'";

				if (in_array($mapping_definition["table"], $CFG_GLPI["specif_entities_tables"])) {
					$sql .= getEntitiesRestrictRequest($separator = " AND ", $mapping_definition["table"], '', $entity, in_array($mapping_definition["table"], $CFG_GLPI["recursive_type"]));
				}
				$res = $DB->query($sql);
				if ($DB->numrows($res)) {
					$obj[$mapping_definition["linkfield"]] = $DB->result($res, 0, "ID");
				} else {
					$result->addInjectionMessage(WARNING_NOTFOUND, $mapping_definition["linkfield"] . "='$field_value'");
				}
				break;
			case "multitext" :
				//Multitext means that the several input fields can be mapped into one field in DB. all the informations
				//are appended at the end of the field
				if (!isset ($obj[$mapping_definition["field"]]))
					$obj[$mapping_definition["field"]] = "";

				if (!empty ($field_value)) {
					if ($several)
						$obj[$mapping_definition["field"]] .= $mapping->getName() . "=" . $field_value . "\n";
					else
						$obj[$mapping_definition["field"]] .= $field_value . "\n";
				}
				break;
			case "entity" :
				$obj[$mapping_definition["field"]] = checkEntity($field_value, $entity);
				break;
			case "virtual" :
				//nobreak
			default :
				if ($type >= 1000) {
                  $params = array("mapping"=>$mapping, "mapping_definition"=>$mapping_definition,"entity"=>$entity,
                                    "field_value"=>$field_value);
                  $obj[$mapping_definition["field"]] = doOneHook($PLUGIN_HOOKS['plugin_types'][$type],"datainjection_getFieldValue",$params);
            }
            else {
               $obj[$mapping_definition["field"]] = $field_value;	
            }
				break;
		}
	} else {
		$obj[$mapping_definition["field"]] = $field_value;
	}
		
	return $obj;
}

/**
 * Process actions after item was imported in DB (mainly create connections)
 * 
 * @param result the result set
 * @param model the model
 * @param type the type of the item inserted
 * @param fields the fields of the item inserted
 * @param common_fields the array of common fields
 * @return the common_fields
 */
function processBeforeEnd($result, $model, $type, $fields, & $common_fields) {
	global $PLUGIN_HOOKS;
   switch ($type) {
		case ENTERPRISE_TYPE :
			addContractToItem($common_fields,$type);
			addContact($common_fields);
			break;
		case USER_TYPE :
			//If user ID is given, add the user in this group
			if (isset ($common_fields["FK_user"]) && isset ($common_fields["FK_group"]))
				addUserGroup($common_fields["FK_user"], $common_fields["FK_group"]);
			break;
		case NETWORKING_TYPE :
			addNetworkPorts($common_fields);
			addContractToItem($common_fields,$type);
			break;
		case PRINTER_TYPE :
			addContractToItem($common_fields,$type);
			break;
		case COMPUTER_TYPE :
			addContractToItem($common_fields,$type);
			break;
		case PHONE_TYPE :
			addContractToItem($common_fields,$type);
			break;
		case NETPORT_TYPE :
			addVlan($result,$common_fields, $model->getCanAddDropdown());
			addNetPoint($result, $common_fields, $model->getCanAddDropdown());
			addNetworkingWire($result, $common_fields, $model->getCanOverwriteIfNotEmpty());
			break;
		case COMPUTER_CONNECTION_TYPE :
			if ($model->getDeviceType() != SOFTWARELICENSE_TYPE) {
            connectPeripheral($result,$fields);
			}
         else {
         	affectLicenceToComputer($result,$fields);
         }
			break;
		case ENTITY_TYPE :
			addEntityPostProcess($common_fields);
			break;
		case CONNECTION_ALL_TYPES:
			connectToObjectByType($result,$fields);
			break;
		default :
            if ($type >= 1000) {
                  $params = array("result"=>$result, "model"=>$model,"fields"=>$fields,
                                    "common_fields"=>$common_fields);
                  doOneHook($PLUGIN_HOOKS['plugin_types'][$type],"datainjection_getFieldValue",$params);
            }

			break;
	}
}

/**
 * Check is the user has the right to add datas in a dropdown table
 * @param table the dropdown table
 * @canadd_dropdown boolean to indicate if the model allows user to add datas in a dropdown table
 * @return true if the user can add, false if he can't 
 */
function haveRightDropdown($table, $canadd_dropdown) {
	global $CFG_GLPI;
	if (!$canadd_dropdown)
		return false;
	else {
		if (in_array($table, $CFG_GLPI["specif_entities_tables"]))
			return haveRight("entity_dropdown", "w");
		else
			return haveRight("dropdown", "w");
	}
}

/**
 * Add the complementary informations into the list of fields to insert in DB
 * @param fields the fields to insert into DB
 * @param infos the informations filled by the user when injecting his file
 * @return the fields modified
 */
function addInfosFields($fields, $infos) {
	global $DATA_INJECTION_INFOS;

	foreach ($infos as $info)
		if (keepInfo($info)) {
			if (isset ($fields[$info->getInfosType()][$info->getValue()]) && isset ($DATA_INJECTION_INFOS[$info->getInfosType()][$info->getValue()]['table_type']) && $DATA_INJECTION_INFOS[$info->getInfosType()][$info->getValue()]['table_type'] == "multitext")
				$fields[$info->getInfosType()][$info->getValue()] .= "\n" . $info->getInfosText();
			else
				$fields[$info->getInfosType()][$info->getValue()] = $info->getInfosText();
		}
	return $fields;
}

/**
 * Determine if the information entered by the user must be injected into glpi or not
 * @info the info object
 */
function keepInfo($info) {
	global $DATA_INJECTION_INFOS;

	if (!isset ($DATA_INJECTION_INFOS[$info->getInfosType()][$info->getValue()]["input_type"]))
		return true;

	switch ($DATA_INJECTION_INFOS[$info->getInfosType()][$info->getValue()]["input_type"]) {
		case "text" :
			if ($info->getInfosText() != NULL && $info->getInfosText() != EMPTY_VALUE)
				return true;
			break;
		case "yesno":
			return true;
			break;
		case "dropdown" :
      case "dropdown_users" :
			if ($info->getInfosText() != 0)
				return true;
			break;
	}
	return false;
}

/**
 * Log event into the history
 * @param device_type the type of the item to inject
 * @param device_id the id of the inserted item
 * @param the action_type the type of action(add or update)
 */
function logAddOrUpdate($device_type, $device_id, $action_type) {
	global $LANG;

	//Do not add history for users and groups
	if ($device_type != USER_TYPE && $device_type != GROUP_TYPE) {
		$changes[0] = 0;

		if ($action_type == INJECTION_ADD)
			$changes[2] = $LANG["datainjection"]["result"][8] . " " . $LANG["datainjection"]["history"][1];
		else
			$changes[2] = $LANG["datainjection"]["result"][9] . " " . $LANG["datainjection"]["history"][1];

		$changes[1] = "";
		historyLog($device_id, $device_type, $changes, 0, HISTORY_LOG_SIMPLE_MESSAGE);
	}
}

/**
 * Unset the fields when user have no rights to add or modify
 * @param fields the fields to insert into DB
 * @param fields_from_db fields already in DB
 * @param can_overwrite indicates if the model allows datas already in DB to be overwrited
 */
function filterFields(& $fields, $fields_from_db, $can_overwrite, $type) {
	//If no right to overwrite existing fields in DB -> unset the field
	foreach ($fields as $field => $value) {
		if ($field[0] != '_' 
         && (isset($fields_from_db[$field]) 
            && $fields_from_db[$field]) 
               && !$can_overwrite) {
			$name = getMappingNameByTypeAndValue($type, $field);
			if ($field == "ID" 
            || (
               (isset ($DATA_INJECTION_MAPPING[$type][$name]['table_type']) 
                  && $DATA_INJECTION_MAPPING[$type][$name]['table_type'] == "dropdown") 
                     && $fields_from_db[$field] > 0) 
                        || $fields_from_db[$field] != EMPTY_VALUE)
				unset ($fields[$field]);
		}

	}
}

/**
 * Build where sql request to look for a network port
 * @param model the model
 * @param fields the fields to insert into DB
 * 
 * @return the sql where clause
 */
function getPortUnicityRequest($model,$fields)
{
	$where = "";
	switch ($model->getPortUnicity())
	{
		case MODEL_NETPORT_LOGICAL_NUMER:
			$where .= " AND logical_number='" . (isset($fields["logical_number"])?$fields["logical_number"]:'')."'";
		break;
		case MODEL_NETPORT_LOGICAL_NUMER_MAC:
			$where .= " AND logical_number='" . (isset($fields["logical_number"])?$fields["logical_number"]:'')."'";
			$where .= " AND name='" . (isset($fields["name"])?$fields["name"]:'')."'";
			$where .= " AND ifmac='" . (isset($fields["ifmac"])?$fields["ifmac"]:'')."'";
		break;
		case MODEL_NETPORT_LOGICAL_NUMER_NAME:
			$where .= " AND logical_number='" . (isset($fields["logical_number"])?$fields["logical_number"]:'')."'";
			$where .= " AND name='" . (isset($fields["name"])?$fields["name"]:'')."'";
		break;
		case MODEL_NETPORT_LOGICAL_NUMER_NAME_MAC:
			$where .= " AND logical_number='" . (isset($fields["logical_number"])?$fields["logical_number"]:'')."'";
			$where .= " AND name='" . (isset($fields["name"])?$fields["name"]:'')."'";
			$where .= " AND ifmac='" . (isset($fields["ifmac"])?$fields["ifmac"]:'')."'";
		break;
		case MODEL_NETPORT_MACADDRESS:
			$where .= " AND ifmac='" . (isset($fields["ifmac"])?$fields["ifmac"]:'')."'";
		break;
		case MODEL_NETPORT_NAME:
			$where .= " AND name='" . (isset($fields["name"])?$fields["name"]:'')."'";
		break;
	}
	return $where;
}

/**
 * Look for comments assiocated with a dropdown
 * @param model the injection model
 * @line the line of datas to inject
 * @mapping the mapping
 */
function getFieldCommentsIfExists($model,$line,$mapping)
{
	$field_name = $mapping->getValue();
	$field_comments_mapping = $model->getMappingByValue("_".$field_name."_comments");

	if ($field_comments_mapping != null)
		return $line[$field_comments_mapping->getRank()];
	else
		return EMPTY_VALUE;	
	
}
?>
