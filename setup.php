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
	global $PLUGIN_HOOKS, $CFG_GLPI, $LANG;

	$plugin = new Plugin;

	if ($plugin->isInstalled("data_injection") && $plugin->isActivated("data_injection")) {

		$PLUGIN_HOOKS['change_profile']['data_injection'] = 'plugin_data_injection_changeprofile';

		$PLUGIN_HOOKS['headings']['data_injection'] = 'plugin_get_headings_data_injection';
		$PLUGIN_HOOKS['headings_action']['data_injection'] = 'plugin_headings_actions_data_injection';

		if (plugin_data_injection_haveRight("model", "r"))
			$PLUGIN_HOOKS['menu_entry']['data_injection'] = true;

		$PLUGIN_HOOKS['pre_item_delete']['data_injection'] = 'plugin_pre_item_delete_data_injection';

		// Css file
		$PLUGIN_HOOKS['add_css']['data_injection'] = 'css/data_injection.css';
	
		// Javascript file
		$PLUGIN_HOOKS['add_javascript']['data_injection'] = 'javascript/data_injection.js';
	
		//Need to load mappings when all the other files are loaded...
		//TODO : check with it cannot be included at the same at as the other files...
		include_once("inc/plugin_data_injection.mapping.constant.php");
		registerPluginType('data_injection', 'PLUGIN_DATA_INJECTION_MODEL', 1450, array(
				'classname'  => 'DataInjectionModel',
				'tablename'  => 'glpi_plugin_data_injection_models',
				'typename'   => $LANG['common'][22],
				'formpage'   => '',
				'searchpage' => '',
				'specif_entities_tables' => true,
				'recursive_type' => true
				));
	
		loadDeviceSpecificTypes();
		addDeviceSpecificMappings();
		addDeviceSpecificInfos();
	
		//Initialize, if active, genericobject types
		if ($plugin->isActivated("genericobject"))
			usePlugin("genericobject");
	}
}

function plugin_version_data_injection() {
	global $LANG;

	return array (
		'name' => $LANG["datainjection"]["name"][1],
		'minGlpiVersion' => '0.72',
		'author'=>'Dévi Balpe & Walid Nouh & Remi Collet',
		'homepage'=>'http://glpi-project.org/wiki/doku.php?id='.substr($_SESSION["glpilanguage"],0,2).':plugins:pluginslist',
		'version' => '1.6.0'
	);
}


function plugin_data_injection_check_prerequisites() {
	if (GLPI_VERSION >= 0.72) {
		return true;
	} else {
		echo "This plugin requires GLPI 0.72 or later";
	}
}

function plugin_data_injection_check_config() {
	return true;
}

?>
