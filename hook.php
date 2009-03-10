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

function plugin_data_injection_changeprofile() {
	$plugin = new Plugin;

	if ($plugin->isInstalled("data_injection") && $plugin->isActivated("data_injection")) {
		$prof = new DataInjectionProfile();
		if ($prof->getFromDB($_SESSION['glpiactiveprofile']['ID']))
			$_SESSION["glpi_plugin_data_injection_profile"] = $prof->fields;
		else
			unset ($_SESSION["glpi_plugin_data_injection_profile"]);
	}
}

function plugin_headings_actions_data_injection($type) {
	switch ($type) {
		case PROFILE_TYPE :
			return array (
				1 => "plugin_headings_data_injection",
				
			);
			break;
	}
	return false;
}

function plugin_get_headings_data_injection($type, $withtemplate) {
	global $LANG;

	switch ($type) {
		case PROFILE_TYPE :
			if ($withtemplate)
				return array ();
			else
				return array (
					1 => $LANG["data_injection"]["name"][1]
				);
			break;
	}
	return false;
}

function plugin_headings_data_injection($type, $ID, $withtemplate = 0) {
	global $CFG_GLPI;

	switch ($type) {
		case PROFILE_TYPE :
			$profile = new DataInjectionProfile;
			if (!$profile->getFromDB($ID))
				plugin_data_injection_createaccess($ID);

			$profile->showForm($CFG_GLPI["root_doc"] . "/plugins/data_injection/front/plugin_data_injection.profile.php", $ID);
			break;
		default :
			break;
	}
}
?>
