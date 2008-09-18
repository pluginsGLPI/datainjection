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

function addVlan($common_fields, $canadd) {

	if (isset ($common_fields["network_port_id"]) && isset ($common_fields["vlan"])) {
		$vlan_id = getDropdownValue(array (), array (
			"table" => "glpi_dropdown_vlan"
		), $common_fields["vlan"], $common_fields["FK_entities"], $canadd);
		if ($vlan_id > 0)
			assignVlan($common_fields["network_port_id"], $vlan_id);
	} else
		return 0;
}

function addNetPoint($result, $common_fields, $canadd) {
	if (isset ($common_fields["location"]) && isset ($common_fields["netpoint"])) {
		$id = checkNetpoint($result, $common_fields["device_type"], $common_fields["FK_entities"], $common_fields["location"], $common_fields["netpoint"], $common_fields["network_port_id"], $canadd);

		if ($id > 0) {
			$port = new Netport();
			$port->update(array (
				"ID" => $common_fields["network_port_id"],
				"netpoint" => $id
			));
		}
	}
}

function addNetworkingWire($result, $common_fields, $canupdate) {
	global $DB;

	if (!isset ($common_fields["netname"]) || empty ($common_fields["netname"]) || !isset ($common_fields["netport"]) || empty ($common_fields["netport"])) {
		return false;
	}

	// Find port in database
	$sql = "SELECT glpi_networking_ports.ID FROM glpi_networking_ports, glpi_networking " .
	" WHERE glpi_networking_ports.device_type=" . NETWORKING_TYPE .
	" AND glpi_networking_ports.on_device=glpi_networking.ID" .
	" AND glpi_networking.is_template=0 " .
	" AND glpi_networking.FK_entities=" . $common_fields["FK_entities"] .
	" AND glpi_networking.name='" . $common_fields["netname"] . "'" .
	" AND glpi_networking_ports.logical_number=" . $common_fields["netport"];
	$res = $DB->query($sql);
	if (!$res || $DB->numrows($res) < 1) {
		$result->addInjectionMessage(WARNING_NOTFOUND, $common_fields["netname"] . " #" . $common_fields["netport"]);
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

function addContract($common_fields) {
	if (isset ($common_fields["contract"]))
		addEnterpriseContract($common_fields["contract"], $common_fields['device_id']);
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
			if ($value != EMPTY_VALUE && (!isset ($fields[$key]) || $fields[$key] == EMPTY_VALUE || $fields[$key] == DROPDOWN_DEFAULT_VALUE))
				$fields[$key] = $value;
		}
	}
}

/**
 * Connect peripherals or devices to a computer
 * @fields the common_fields
 */
function connectPeripheral($fields) {
	global $DB;

	$connect = true;

	if (isset ($fields["name"]) || isset ($fields["serial"]) || isset ($fields["otherserial"])) {

		if (!isset($fields["computer_id"])) {

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

			$result = $DB->query($sql);

			//If only one computer was found in the entity, perform connection
			if ($DB->numrows($result) != 1)
				$connect = false;
			else
				$fields["computer_id"] = $DB->result($result, 0, "ID");
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
?>