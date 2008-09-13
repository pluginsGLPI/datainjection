<?php
/*
 * @version $Id: rules.constant.php 5351 2007-08-07 11:57:46Z walid $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
 */


function loadDeviceSpecificTypes()
{
	global $LANG, $DEVICES_TYPES_STRING,$IMPORT_PRIMARY_TYPES,$CONNECT_TO_COMPUTER_TYPES,$IMPORT_TYPES,$DEVICES_TYPES;

		$DEVICES_TYPES_STRING = array (
		"PLUGIN_DATA_INJECTION_MOBOARD_DEVICE_TYPE" => array (
			"label" => $LANG["title"][30] . " : " . $LANG["devices"][5],
			"table" => "glpi_device_moboard",
			"class" => "plugin_dataInjectionDeviceMoboard",
			"ID" => 1451
		),
		"PLUGIN_DATA_INJECTION_PROCESSOR_DEVICE_TYPE" => array (
			"label" => $LANG["title"][30] . " : " . $LANG["devices"][4],
			"table" => "glpi_device_processor",
			"class" => "plugin_dataInjectionDeviceProcessor",
			"ID" => 1452
		),
		"PLUGIN_DATA_INJECTION_RAM_DEVICE_TYPE" => array (
			"label" => $LANG["title"][30] . " : " . $LANG["devices"][6],
			"table" => "glpi_device_ram",
			"class" => "plugin_dataInjectionDeviceRam",
			"ID" => 1453
		),
		"PLUGIN_DATA_INJECTION_HDD_DEVICE_TYPE" => array (
			"label" => $LANG["title"][30] . " : " . $LANG["devices"][1],
			"table" => "glpi_device_hdd",
			"class" => "plugin_dataInjectionHdd",
			"ID" => 1454
		),
		"PLUGIN_DATA_INJECTION_NETWORK_DEVICE_TYPE" => array (
			"label" => $LANG["title"][30] . " : " . $LANG["devices"][3],
			"table" => "glpi_device_iface",
			"class" => "plugin_dataInjectionDeviceNetwork",
			"ID" => 1455
		),
		"PLUGIN_DATA_INJECTION_DRIVE_DEVICE_TYPE" => array (
			"label" => $LANG["title"][30] . " : " . $LANG["devices"][19],
			"table" => "glpi_device_drive",
			"class" => "plugin_dataInjectionDeviceDrive",
			"ID" => 1456
		),
		"PLUGIN_DATA_INJECTION_CONTROL_DEVICE_TYPE" => array (
			"label" => $LANG["title"][30] . " : " . $LANG["devices"][20],
			"table" => "glpi_device_control",
			"class" => "plugin_dataInjectionDeviceControl",
			"ID" => 1457
		),
		"PLUGIN_DATA_INJECTION_GFX_DEVICE_TYPE" => array (
			"label" => $LANG["title"][30] . " : " . $LANG["devices"][2],
			"table" => "glpi_device_gfxcard",
			"class" => "plugin_dataInjectionDeviceGfxCard",
			"ID" => 1458
		),
		"PLUGIN_DATA_INJECTION_SND_DEVICE_TYPE" => array (
			"label" => $LANG["title"][30] . " : " . $LANG["devices"][7],
			"table" => "glpi_device_sndcard",
			"class" => "plugin_dataInjectionDeviceSndCard",
			"ID" => 1459
		),
		"PLUGIN_DATA_INJECTION_PCI_DEVICE_TYPE" => array (
			"label" => $LANG["title"][30] . " : " . $LANG["devices"][21],
			"table" => "glpi_device_pci",
			"class" => "plugin_dataInjectionDevicePci",
			"ID" => 1460
		),
		"PLUGIN_DATA_INJECTION_CASE_DEVICE_TYPE" => array (
			"label" => $LANG["title"][30] . " : " . $LANG["devices"][22],
			"table" => "glpi_device_case",
			"class" => "plugin_dataInjectionDeviceCase",
			"ID" => 1461
		),
		"PLUGIN_DATA_INJECTION_POWER_DEVICE_TYPE" => array (
			"label" => $LANG["title"][30] . " : " . $LANG["devices"][23],
			"table" => "glpi_device_power",
			"class" => "plugin_dataInjectionDevicePower",
			"ID" => 1462
		)
	);

	
	//Devices have only one type in GLPI, so I need to define a list of different types in order to fit into the plugin's framework...
	foreach ($DEVICES_TYPES_STRING as $name => $infos)
		pluginNewType("data_injection", $name, $infos["ID"], $infos["class"], $infos["table"], "", $infos["label"]);		

	$DEVICES_TYPES = array (
		PLUGIN_DATA_INJECTION_MOBOARD_DEVICE_TYPE,
		PLUGIN_DATA_INJECTION_PROCESSOR_DEVICE_TYPE,
		PLUGIN_DATA_INJECTION_RAM_DEVICE_TYPE,
		PLUGIN_DATA_INJECTION_HDD_DEVICE_TYPE,
		PLUGIN_DATA_INJECTION_NETWORK_DEVICE_TYPE,
		PLUGIN_DATA_INJECTION_DRIVE_DEVICE_TYPE,
		PLUGIN_DATA_INJECTION_CONTROL_DEVICE_TYPE,
		PLUGIN_DATA_INJECTION_GFX_DEVICE_TYPE,
		PLUGIN_DATA_INJECTION_SND_DEVICE_TYPE,
		PLUGIN_DATA_INJECTION_PCI_DEVICE_TYPE,
		PLUGIN_DATA_INJECTION_CASE_DEVICE_TYPE,
		PLUGIN_DATA_INJECTION_POWER_DEVICE_TYPE
	);

	$IMPORT_PRIMARY_TYPES = array_merge($IMPORT_PRIMARY_TYPES, $DEVICES_TYPES);
}
?>
