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

include_once ("plugin_data_injection.includes.php");

function plugin_init_data_injection() {
	global $PLUGIN_HOOKS, $CFG_GLPI, $DATAINJECTIONLANG;

	$PLUGIN_HOOKS['init_session']['data_injection'] = 'plugin_data_injection_initSession';
	$PLUGIN_HOOKS['change_profile']['data_injection'] = 'plugin_data_injection_changeprofile';

	if (isset ($_SESSION["glpi_plugin_data_injection_installed"]) && $_SESSION["glpi_plugin_data_injection_installed"] == 1) {

		if (plugin_data_injection_haveRight("create_model", "w") || plugin_data_injection_haveRight("use_model", "r") && isset ($_SESSION["glpi_plugin_data_injection_profile"])) {
			$PLUGIN_HOOKS['menu_entry']['data_injection'] = true;
			$PLUGIN_HOOKS['submenu_entry']['data_injection']['config'] = 'front/plugin_data_injection.config.form.php';
		}
		if (haveRight("config", "w") || haveRight("profile", "w")) {
			// Config page
			$PLUGIN_HOOKS['config_page']['data_injection'] = 'front/plugin_data_injection.config.form.php';
		}
		$PLUGIN_HOOKS['pre_item_delete']['data_injection'] = 'plugin_pre_item_delete_data_injection';
	} else
		if (haveRight("config", "w")) {
			$PLUGIN_HOOKS['config_page']['data_injection'] = 'front/plugin_data_injection.config.form.php';
		}

	// Css file
	$PLUGIN_HOOKS['add_css']['data_injection'] = 'css/data_injection.css';

	// Javascript file
	$PLUGIN_HOOKS['add_javascript']['data_injection'] = 'javascript/data_injection.js';

	loadDeviceSpecificTypes();
	addDeviceSpecificMappings();
}

function plugin_version_data_injection() {
	global $DATAINJECTIONLANG;

	return array (
		'name' => $DATAINJECTIONLANG["name"][1],
		'minGlpiVersion' => '0.71',
		'maxGlpiVersion' => '0.71.2',
		'version' => '1.3'
	);
}

// Hook done on delete item case

function plugin_pre_item_delete_data_injection($input) {
	if (isset ($input["_item_type_"]))
		switch ($input["_item_type_"]) {
			case PROFILE_TYPE :
				// Manipulate data if needed 
				$DataInjectionProfile = new DataInjectionProfile;
				$DataInjectionProfile->cleanProfiles($input["ID"]);
				break;
		}
	return $input;
}

function loadDeviceSpecificTypes()
{
	global $LANG, $DEVICES_TYPES_STRING,$IMPORT_PRIMARY_TYPES,$CONNECT_TO_COMPUTER_TYPES,$IMPORT_TYPES;

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