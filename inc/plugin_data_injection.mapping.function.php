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

function getAllMappingsDefinitionsTypes($primary_type)
{
	global $DATAINJECTIONLANG, $CFG_GLPI;
	
	$types = array();
	$commonitem = new CommonItem;

	$commonitem->setType($primary_type);
	$types[] = array($primary_type,$commonitem->getType());
	
	if (in_array($primary_type, $CFG_GLPI["infocom_types"])) {
		$commonitem->setType(INFOCOM_TYPE);
		$types[] = array(INFOCOM_TYPE,$commonitem->getType());		
	}
	
	if ($primary_type!=NETWORKING_TYPE && in_array($primary_type, $CFG_GLPI["netport_types"])) {
		$types[] = array(NETPORT_TYPE,$DATAINJECTIONLANG["mappings"][2]);		
	}
	
	asort($types);
	return $types;
}

function getAllMappingsDefinitionsByType($type)
{
	global $DATA_INJECTION_MAPPING,$LANG;
	$mapping_parameters = array();
	
	foreach ($DATA_INJECTION_MAPPING[$type] as $name => $mapping)
		$mapping_parameters[$name] = $mapping["name"];

	asort($mapping_parameters);
	return 	$mapping_parameters;
}

function getMappingDefinitionByTypeAndName($type,$name)
{
	global $DATA_INJECTION_MAPPING;
	if (isset($DATA_INJECTION_MAPPING[$type][$name]))
		return $DATA_INJECTION_MAPPING[$type][$name];
	else
		return null;	
}

function getAllMandatoriesMappings($type,$model)
{
	global $DATA_INJECTION_MAPPING;
	$mandatories = array();
	
	foreach ($model->getMappings() as $mapping)
	{
		if ($mapping->isMandatory())
			$mandatories[] = $mapping;
	}
	
	return $mandatories;
}

function getAllPrimaryTypes()
{
	global $IMPORT_PRIMARY_TYPES,$LANG;

	$types = array();
	$commonitem = new CommonItem;
	
	foreach ($IMPORT_PRIMARY_TYPES as $type)
	{
		$commonitem->setType($type);
		$types[] = array($commonitem->getType(),$type);
	}
	asort($types);
	return $types;
}
?>
