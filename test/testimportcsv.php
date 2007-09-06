<?php
/*
 * @version $Id: ocsng_fullsync.php 4980 2007-05-15 13:32:29Z walid $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2007 by the INDEPNET Development Team.

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
ini_set("memory_limit","-1");
ini_set("max_execution_time", "0");

# Converts cli parameter to web parameter for compatibility
if ($argv) {
	for ($i=1;$i<count($argv);$i++)
	{
		$it = split("=",$argv[$i]);
		$it[0] = eregi_replace('^--','',$it[0]);
		$_GET[$it[0]] = $it[1];
	}
}


define('GLPI_ROOT', '../../..');

$NEEDED_ITEMS=array("user","contract","networking","group","monitor","phone","infocom","printer","profile","entity","computer","software","setup","peripheral");
include (GLPI_ROOT."/inc/includes.php");
include ("../plugin_data_injection.includes.php");

//The csv file, got from the command line
//$file = $argv[1];
//$model_id = $argv[2];
//$entity_id = $argv[3];

//---------------------- //
//------CSV File-------- //
//---------------------- //

//Get the backend to read CSV file
//$backend = getBackend(CSV_TYPE);

//Initialize the backend
//$backend->initBackend($file,";");

//Read file from the CSV file
//$backend->read();

//print_r($backend->getHeader(0));

//Display datas
//print_r($backend->getDatas());


//---------------------- //
//--Models & Mappings--- //
//---------------------- //

//$model = new DataInjectionModel;
//if ($model->loadAll(1))
//{
//	print_r($model->getModelInfos());
//	print_r($model->getMappings());
//}

//print_r(getAllMappingsDefinitionsTypes());
//print_r(getAllMappingsDefinitionsByType(1));

//---------------------- //
//-------Types---------- //
//---------------------- //
//print_r(getAllTypes());

//---------------------- //
//-------Engine--------- //
//---------------------- //
//$engine = new DataInjectionEngine($model_id,$file,$entity_id);
//$results = $engine->injectDatas();
//print_r($results);

$fields["name"] = "test";
$fields["ID"] = "12";
$infos[] = new InjectionInfos(INFOCOM_TYPE,"warranty_duration",3);
$infos[] = new InjectionInfos(INFOCOM_TYPE,"num_commande",1234322);
$infos[] = new InjectionInfos(COMPUTER_TYPE,"state",1);

print_r(addInfosFields(1,$fields,$infos));
?>
