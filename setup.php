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

		if (plugin_data_injection_haveRight("model", "r") && isset ($_SESSION["glpi_plugin_data_injection_profile"])) {
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

	pluginNewType("data_injection", "PLUGIN_DATA_INJECTION_MODEL", 1450, "DataInjectionModel", "glpi_plugin_data_injection_models", "", "");
	loadDeviceSpecificTypes();
	addDeviceSpecificMappings();
	addDeviceSpecificInfos();
}

function plugin_version_data_injection() {
	global $DATAINJECTIONLANG;

	return array (
		'name' => $DATAINJECTIONLANG["name"][1],
		'minGlpiVersion' => '0.71',
		'maxGlpiVersion' => '0.71.9',
		'version' => '1.4.0'
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

?>
