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

// ----------------------------------------------------------------------
// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

function plugin_data_injection_update131_14() {
	global $DB;
	$sql = "ALTER TABLE `glpi_plugin_data_injection_models` ADD `float_format` INT( 1 ) NOT NULL DEFAULT '0';";
	$DB->query($sql);

	//Template recursivity : need standardize names in order to use privatePublicSwitch
	$sql = " ALTER TABLE `glpi_plugin_data_injection_models` CHANGE `user_id` `FK_users` INT( 11 ) NOT NULL";
	$DB->query($sql);
	
	$sql = " ALTER TABLE `glpi_plugin_data_injection_models` CHANGE `public` `private` INT( 1 ) NOT NULL  DEFAULT '0'";
	$DB->query($sql);
	
	
	
	$sql="UPDATE `glpi_plugin_data_injection_models` SET FK_entities=-1 AND private=1 WHERE private=0";
	$DB->query($sql);
	
	$sql="UPDATE `glpi_plugin_data_injection_models` SET private=0 WHERE private=1 AND FK_entities>0";
	$DB->query($sql);
	
	$sql = "ALTER TABLE `glpi_plugin_data_injection_models` ADD `recursive` INT( 1 ) NOT NULL DEFAULT '0';";
	$DB->query($sql);

	$sql="UPDATE `glpi_plugin_data_injection_profiles` SET create_model=use_model WHERE create_model IS NULL";
	$DB->query($sql);

	$sql="ALTER TABLE `glpi_plugin_data_injection_profiles` DROP `use_model`";
	$DB->query($sql);
	
	$sql=" ALTER TABLE `glpi_plugin_data_injection_profiles` CHANGE `create_model` `model` CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL";
	$DB->query($sql);
}


function plugin_data_injection_createfirstaccess($ID) {
	global $DB;

	$DataInjectionProfile = new DataInjectionProfile();
	if (!$DataInjectionProfile->GetfromDB($ID)) {

		$Profile = new Profile();
		$Profile->GetfromDB($ID);
		$name = $Profile->fields["name"];

		$query = "INSERT INTO `glpi_plugin_data_injection_profiles` ( `ID`, `name` , `is_default`, `model`) VALUES ('$ID', '$name','0','w');";
		$DB->query($query);
	}
}

function plugin_data_injection_createaccess($ID) {
	global $DB;

	$Profile = new Profile();
	$Profile->GetfromDB($ID);
	$name = $Profile->fields["name"];

	$query = "INSERT INTO `glpi_plugin_data_injection_profiles` ( `ID`, `name` , `is_default`, `model`) VALUES ('$ID', '$name','0',NULL);";

	$DB->query($query);
}

function plugin_data_injection_loadHook($hook_name, $params = array ()) {
	global $PLUGIN_HOOKS;

	if (!empty ($params)) {
		$type = $params["type"];
		//If a plugin type is defined
		$function = 'plugin_' . $PLUGIN_HOOKS['plugin_types'][$type] . '_data_injection_' . $hook_name;
		if (function_exists($function)) {
			$function ($type, $params);
		}
	} else
		if (isset ($PLUGIN_HOOKS['plugin_types'])) {
			//Browse all plugins
			foreach ($PLUGIN_HOOKS['plugin_types'] as $type => $name) {
				$function = 'plugin_' . $name . '_data_injection_' . $hook_name;
				if (function_exists($function)) {
					$function ($type, $params);
				}
			}
		}
}

function plugin_data_injection_haveRight($module, $right) {
	$matches = array (
		"" => array (
			"",
			"r",
			"w"
		), // ne doit pas arriver normalement
	"r" => array (
			"r",
			"w"
		),
		"w" => array (
			"w"
		),
		"1" => array (
			"1"
		),
		"0" => array (
			"0",
			"1"
		), // ne doit pas arriver non plus

	
	);
	if (isset ($_SESSION["glpi_plugin_data_injection_profile"][$module]) && in_array($_SESSION["glpi_plugin_data_injection_profile"][$module], $matches[$right]))
		return true;
	else
		return false;
}

function plugin_data_injection_haveTypeRight($module,$right)
{
	return plugin_data_injection_haveRight("model", $right);	
}

function plugin_data_injection_checkRight($module, $right) {
	global $CFG_GLPI;

	if (!plugin_data_injection_haveRight($module, $right)) {
		// Gestion timeout session
		if (!isset ($_SESSION["glpiID"])) {
			glpi_header($CFG_GLPI["root_doc"] . "/index.php");
			exit ();
		}

		displayRightError();
	}
}

?>
