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

/**
 * Add a Network Ports (for network device only)
 * 
 * @param common_fields the common_fields
 */
function addNetworkPorts($common_fields) {

	if (isset ($common_fields["nb_ports"])) {
		for ($i = 1; $i <= $common_fields["nb_ports"]; $i++) {
			$input = array ();
			$netport = new Netport;

			$add = "";
			if ($i < 10)
				$add = "0";
			$input["logical_number"] = $i;
			$input["name"] = $add . $i;
			$input["on_device"] = $common_fields["device_id"];
			$input["device_type"] = $common_fields["device_type"];
			$netport->add($input);
		}
	}
}

/**
 * Assign a VLAN to a port
 */
function addVlan($result, $common_fields, $canadd) {

	if (isset ($common_fields["network_port_id"]) && isset ($common_fields["vlan"])) {
		//Get the VLAN ID by his name
		$vlan_id = getDropdownValue(array (), array (
			"table" => "glpi_dropdown_vlan"
		), $common_fields["vlan"], $common_fields["FK_entities"], $canadd);

		//Check if vlan is not already assign to the port
		if ($vlan_id > 0 && !checkVlanAlreadyAssignToPort($common_fields["network_port_id"], $vlan_id))
			assignVlan($common_fields["network_port_id"], $vlan_id);
		elseif ($vlan_id > 0) $result->addInjectionMessage(WARNING_ALREADY_LINKED);
	}
}

/**
 * Check if a vlan is already assign to a network port
 * @param port_id the port ID
 * @param vlan_id the vlan ID
 *
 * @return true if the vlan is already assign to the port, false otherwise
 */
function checkVlanAlreadyAssignToPort($port_id, $vlan_id) {
	global $DB;
	$query = "SELECT ID FROM glpi_networking_vlan WHERE FK_port='$port_id' AND FK_vlan='$vlan_id'";
	$result = $DB->query($query);
	if ($DB->numrows($result) > 0)
		return true;
	else
		return false;
}

/**
 * Add a network plug
 * @param result the array to log injection informations
 * @param fields the fields to use to check if plug exists or not
 * @param canadd indicates if a network plug can be created or not
 * 
 * @return nothing
 */
function addNetPoint($result, $common_fields, $canadd) {
	//Do not look for a network plug, if not update on this field is needed
	if (isset ($common_fields["location"]) && isset ($common_fields["netpoint"])) {
		$id = checkNetpoint($result, $common_fields, $canadd);

		if ($id > 0) {
			$port = new Netport();
			$port->update(array (
				"ID" => $common_fields["network_port_id"],
				"netpoint" => $id
			));
		}
	}
}

/**
 * Connect a network port to another one
 * @param result the array to log injection informations
 * @param fields the fields to use to check if plug exists or not
 * @param canadd indicates if a network plug can be created or not
 * 
 * @return nothing
 */
function addNetworkingWire($result, $common_fields, $canupdate) {
	global $DB;

	$use_name = (isset ($common_fields["netname"]) || !empty ($common_fields["netname"]));
	$use_logical_number = (isset ($common_fields["netport"]) || !empty ($common_fields["netport"]));
	$use_mac = (isset ($common_fields["netmac"]) || !empty ($common_fields["netmac"]));

	if (!$use_name && !$use_logical_number && !$use_mac)
		return false;

	// Find port in database
	$sql = "SELECT glpi_networking_ports.ID FROM glpi_networking_ports, glpi_networking " .
	" WHERE glpi_networking_ports.device_type=" . NETWORKING_TYPE .
	" AND glpi_networking_ports.on_device=glpi_networking.ID" .
	" AND glpi_networking.is_template=0 " .
	" AND glpi_networking.FK_entities=" . $common_fields["FK_entities"];
	if ($use_name)
		$sql .= " AND glpi_networking.name='" . $common_fields["netname"] . "'";
	if ($use_logical_number)
		$sql .= " AND glpi_networking_ports.logical_number='" . $common_fields["netport"] . "'";
	if ($use_mac)
		$sql .= " AND glpi_networking_ports.ifmac='" . $common_fields["netmac"] . "'";

	$res = $DB->query($sql);

	//No port or several found
	if (!$res || $DB->numrows($res) != 1) {
		$message = array ();
		if ($use_name)
			$message[] = $common_fields["netname"];
		if ($use_mac)
			$message[] = $common_fields["netmac"];
		if ($use_logical_number)
			$message[] = $common_fields["netport"];

		//No port found	
		if (!$res || $DB->numrows($res) < 1)
			$result->addInjectionMessage(WARNING_NOTFOUND, implode(' ', $message));
		else
			//Several found
			if ($res && $DB->numrows($res) != 1)
				$result->addInjectionMessage(WARNING_SEVERAL_VALUES_FOUND, implode(' ', $message));
		return false;
	}

	$srce = $common_fields["network_port_id"];
	$dest = $DB->result($res, 0, "ID");

	$nw = new Netwire;
	// Is this port used
	$tmp = $nw->getOppositeContact($dest);
	if ($tmp) {
		if ($tmp == $srce) {
			return true;
		} else
			if ($canupdate) {
				removeConnector($dest);
			} else {
				$result->addInjectionMessage(WARNING_USED, "networking");
				return true;
			}
	}
	// Is our already connected
	$tmp = $nw->getOppositeContact($srce);
	if ($tmp) {
		if ($canupdate) {
			removeConnector($srce);
		} else {
			$result->addInjectionMessage(WARNING_NOTEMPTY, "networking");
			return true;
		}
	}
	return makeConnector($common_fields["network_port_id"], $dest, true, false);
}

/**
 * Add a contract to an object
 * @param common_fields the fields to use
 * @type the type of object
 * 
 * @return nothing
 */
function addContractToItem($common_fields, $type) {
   if (isset ($common_fields["contract"])) {
		switch ($type) {
			default :
				addDeviceContract($common_fields["contract"], $type, $common_fields['device_id']);
				break;
			case ENTERPRISE_TYPE :
				addEnterpriseContract($common_fields["contract"], $common_fields['device_id']);
				break;
		}
	}

}

function addContact($common_fields) {
	if (isset ($common_fields["contact"]))
		addContactEnterprise($common_fields['device_id'], $common_fields['contact']);
}

function getEntityParentId($parent_name) {
	global $DB;
	$sql = "SELECT ID, level FROM glpi_entities WHERE name='" . $parent_name . "' OR completename='" . $parent_name . "'";
	$result = $DB->query($sql);
	if ($DB->numrows($result) > 0)
		return array (
			"ID" => $DB->result($result, 0, "ID"),
			"level" => $DB->result($result, 0, "level")
		);
	else
		return array (
			"ID" => 0
		);
}

/**
 * Manage injection of objets with use of a template
 * @fields the object's fields
 * @type the type of the object
 */
function updateWithTemplate(& $fields, $type) {
	if (isset ($fields["template"])) {
		$fields["ID"] = $fields["template"];

		$tpl = getInstance($type);
		$tpl->getFromDB($fields["ID"]);

		//Unset fields from template
		unset ($tpl->fields["ID"]);
		unset ($tpl->fields["date_mod"]);
		unset ($tpl->fields["is_template"]);
		unset ($tpl->fields["FK_entities"]);

		foreach ($tpl->fields as $key => $value) {
			if ($value != EMPTY_VALUE && (!isset ($fields[$key]) || $fields[$key] == EMPTY_VALUE || $fields[$key] == DROPDOWN_EMPTY_VALUE))
				$fields[$key] = $value;
		}
	}
}

/**
 * Connect peripherals or devices to a computer
 * @fields the common_fields
 */
function connectPeripheral($result, $fields) {
	global $DB;
	$connect = true;
	
	if (isset ($fields["name"]) || isset ($fields["serial"]) || isset ($fields["otherserial"])) {

		if (!isset ($fields["computer_id"])) {

			//Look for a not deleted, not template computer in the entity
			$sql = "SELECT ID FROM glpi_computers WHERE deleted=0 AND is_template=0 AND FK_entities=" . $fields["FK_entities"];

			foreach (array (
					"name",
					"serial",
					"otherserial"
				) as $tmpfield) {
				if (isset ($fields[$tmpfield]))
					$sql .= " AND $tmpfield='" .
					addslashes($fields[$tmpfield]) . "'";
			}
			
			$result_sql = $DB->query($sql);
			//If only one computer was found in the entity, perform connection
			if ($DB->numrows($result_sql) != 1) {
				$result->addInjectionMessage(WARNING_SEVERAL_VALUES_FOUND);
				$connect = false;
			} else
				$fields["computer_id"] = $DB->result($result_sql, 0, "ID");
		}
	}

	if ($connect)
		switch ($fields["device_type"]) {
			//Connect a device
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
				compdevice_add($fields["computer_id"], getDeviceSubTypeByDataInjectionType($fields["device_type"]), $fields["device_id"]);
				break;
         default :
				//Connect everything but not devices
				Connect($fields["device_id"], $fields["computer_id"], $fields["device_type"]);
				break;
		}
}

/**
 * Connect the object to inject to another one
 */
function connectToObjectByType($result, $fields) {
	global $DB;
	$document = new Document;

	$type = (isset ($fields["device_type"]) ? $fields["device_type"] : 0);
	$name = (isset ($fields["name"]) ? $fields["name"] : '');

	if ($type == 0 || $name == '')
		return false;
	else {
		$commonitem = new CommonItem;
		$commonitem->setType($type, true);
		if ($commonitem->obj != null) {
			$query = "SELECT ID FROM " . $commonitem->obj->table . " WHERE name='$name'";
			$result_sql = $DB->query($query);

			if ($DB->numrows($result_sql) != 1)
				return false;
			else {
				$ID = $DB->result($result_sql, 0, "ID");
				//If document is not already linked to the object
				if (!PluginDatainjectionCheck::isDocumentAssociatedWithObject($fields["device_id"], $type, $ID))
					addDeviceDocument($fields["device_id"], $type, $ID);
				else
					$result->addInjectionMessage(WARNING_ALREADY_LINKED);
			}
		} else
			$result->addInjectionMessage(WARNING_SEVERAL_VALUES_FOUND);
	}
}

function affectLicenceToComputer($result,$fields) {
   global $DB;
   
   $found = true;
   
   if (isset ($fields["name"]) || isset ($fields["serial"]) || isset ($fields["otherserial"])) {
      if (!isset ($fields["computer_id"])) {

         //Look for a not deleted, not template computer in the entity
         $sql = "SELECT ID FROM glpi_computers WHERE deleted=0 AND is_template=0 AND FK_entities=" . $fields["FK_entities"];

         foreach (array (
               "name",
               "serial",
               "otherserial"
            ) as $tmpfield) {
            if (isset ($fields[$tmpfield]))
               $sql .= " AND $tmpfield='" .
               addslashes($fields[$tmpfield]) . "'";
         }
         
         $result_sql = $DB->query($sql);
         //If only one computer was found in the entity, perform connection
         if ($DB->numrows($result_sql) != 1) {
            $result->addInjectionMessage(WARNING_SEVERAL_VALUES_FOUND);
            $found = false;
         } else
            $fields["computer_id"] = $DB->result($result_sql, 0, "ID");
      }
   }
   if ($found) {
   	$licence = new SoftwareLicense;
      $input['ID'] = $fields['device_id'];
      $input['FK_computers'] = $fields['computer_id'];
      $licence->update($input); 
   }
}

function addDocumentToObject($fields) {
	global $DB;
   $query = "SELECT ID FROM glpi_docs WHERE name='".$fields['name']."'".
      getEntitiesRestrictRequest(" AND","glpi_docs","FK_entities",$fields['FK_entities'],true);
   $result = $DB->query($query);
   if ($DB->numrows($result) == 1) {
      $ID = $DB->result($result,0,"ID");

      $query = "SELECT glpi_doc_device.ID FROM glpi_doc_device
            WHERE FK_device = '".$fields['device_id']."'
            AND device_type = '".$fields['device_type']."'
            AND FK_doc='".$ID."'";
      $result = $DB->query($query);
      if (!$DB->numrows($result)) {
         addDeviceDocument($ID,$fields['device_type'],$fields['device_id']);  
      }     
   }      
}
?>