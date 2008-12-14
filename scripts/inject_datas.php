<?php


/*
 * @version $Id: ocsng_fullsync.php 4980 2007-05-15 13:32:29Z walid $
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

// ----------------------------------------------------------------------
// Original Author of file: Julien Dombre
// Contributor: Goneri Le Bouder <goneri@rulezlan.org>
// Contributor: Walid Nouh <walid.nouh@gmail.com>
// Purpose of file:
// ----------------------------------------------------------------------
ini_set("memory_limit", "-1");
ini_set("max_execution_time", "0");

# Converts cli parameter to web parameter for compatibility
if ($argv) {
	for ($i = 1; $i < count($argv); $i++) {
		$it = split("=", $argv[$i]);
		$it[0] = eregi_replace('^--', '', $it[0]);
		$_GET[$it[0]] = $it[1];
	}
}

define('GLPI_ROOT', '../../..');

$NEEDED_ITEMS = array (
	"ocsng",
	"user",
	"contact",
	"enterprise",
	"contract",
	"networking",
	"group",
	"monitor",
	"phone",
	"infocom",
	"printer",
	"profile",
	"entity",
	"computer",
	"software",
	"setup",
	"peripheral",
	"cartridge",
	"consumable",
	"rulesengine",
	"rule.dictionnary.dropdown",
	"rule.softwarecategories",
	"rule.dictionnary.software",
	"device"
);

include (GLPI_ROOT . "/config/based_config.php");
include (GLPI_ROOT . "/inc/includes.php");
include ("../plugin_data_injection.includes.php");

print_r($_SESSION);
$CFG_GLPI["debug"]=0; 

$start = true;

$entity = (isset ($_GET["entity"]) ? $_GET["entity"] : 0);
$action = (isset ($_GET["action"]) ? $_GET["action"] : "list");

switch ($action) {
	case "list" :
		listModels($entity);
		break;
	case "inject" :
		inject($_GET,$entity);
		break;
	default;
		break;
}

function print_help() {
	print ("Syntax : inject_datas.php -- [options]\n");
	print ("Options :\n");
	print ("-- action=list : list all the available models\n");
	print ("-- action=inject model_id=xx file=yyy : inject datas from file yyy using model xx\n");

}
function listModels($entity) {
	$models = getAllModels(1, "ID",$entity);
	
	foreach ($models as $model)
		echo $model->getModelID() . " : " . $model->getModelName() . " / " . getDropdownName('glpi_plugin_data_injection_filetype', $model->getModelType()) . "\n";
}

function inject($_GET, $entity) {
	if (isset ($_GET["file"])) {

		if (!file_exists($_GET["file"]))
			print ("File " . $_GET["file"] . " doesn't exists\n");
		else {
			//Get the backend to read CSV file
			$backend = getBackend(CSV_TYPE);

			//Initialize the backend
			$backend->initBackend($_GET["file"], ";","utf-8");

			//Read file from the CSV file
			$backend->read();
		}

		$start = false;

		if (isset ($_GET["model_id"])) {
			$model = new DataInjectionModel;
			$model->loadAll($_GET["model_id"]);
			$engine = new DataInjectionEngine($_GET["model_id"], $_GET["file"], $backend, $entity);
		} else
			$start = false;

		if (!$start)
			print_help();

	}

}
?>
