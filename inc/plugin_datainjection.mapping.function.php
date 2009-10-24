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

// ----------------------------------------------------------------------
// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------

/**
 * Get all the available mapping types for a primary type
 * @param primary_type the primary type
 * @return an array of all the types availables for this primary type
 */
function getAllMappingsDefinitionsTypes($primary_type)
{
	global $LANG, $CFG_GLPI,$CONNECT_TO_COMPUTER_TYPES,$DEVICES_TYPES,$CONNECT_TO_SOFTWARE_TYPES,$CONNECT_TO_ALL_TYPES;
	
	$types = array();
	$commonitem = new CommonItem;

	$commonitem->setType($primary_type);
	$types[] = array($primary_type,$commonitem->getType());
	
	if (in_array($primary_type, $CFG_GLPI["infocom_types"])) {
		$commonitem->setType(INFOCOM_TYPE);
		$types[] = array(INFOCOM_TYPE,$commonitem->getType());		
	}
	
	if (in_array($primary_type, $CFG_GLPI["netport_types"])) {
		$types[] = array(NETPORT_TYPE,$LANG["datainjection"]["mappings"][2]);		
	}

	if (in_array($primary_type,$CONNECT_TO_COMPUTER_TYPES)) {
		$types[] = array(COMPUTER_CONNECTION_TYPE,$LANG["datainjection"]["mappings"][5]);		
	}

	if (in_array($primary_type,$CONNECT_TO_ALL_TYPES)) {
		$types[] = array(CONNECTION_ALL_TYPES,$LANG["datainjection"]["associate"][0]);		
	}
	
	if ($primary_type == USER_TYPE) {
		$types[] = array(PROFILE_USER_TYPE,$LANG["profiles"][22]);		
	}

   if (in_array($primary_type,$CFG_GLPI["doc_types"])
    || $primary_type == KNOWBASE_TYPE) {
      $types[] = array(DOCUMENT_CONNECTION_TYPE,$LANG['Menu'][27]);     
   }	
	asort($types);
	return $types;
}

/**
 * Get all the available mappings for a type
 * @type the type
 * @return an array with all the available mappings for this type
 */
function getAllMappingsDefinitionsByType($type)
{
	global $DATA_INJECTION_MAPPING,$LANG;
	$mapping_parameters = array();
	
	foreach ($DATA_INJECTION_MAPPING[$type] as $name => $mapping)
		$mapping_parameters[$name] = $mapping["name"];

	asort($mapping_parameters);
	return 	$mapping_parameters;
}


/**
 * Get a mapping definition by his type and name
 * @type the mapping type
 * @name the mapping name
 * @return an array with all the informations about this mapping
 */
function getMappingDefinitionByTypeAndName($type,$name)
{
	global $DATA_INJECTION_MAPPING;
	if (isset($DATA_INJECTION_MAPPING[$type][$name]))
		return $DATA_INJECTION_MAPPING[$type][$name];
	else
		return null;	
}

/**
 * Get a mapping definition by his type and value
 * @type the mapping type
 * @name the mapping value
 * @return an array with all the informations about this mapping
 */
function getMappingNameByTypeAndValue($type,$value)
{
	global $DATA_INJECTION_MAPPING;
	
	foreach ($DATA_INJECTION_MAPPING[$type] as $name => $mapping)
		if ($name == $value || (isset($mapping["linkfield"]) && $mapping["linkfield"] == $value))
			return $name;
	
	return "";			
}

/**
 * Return the list of all the mandatory mappings for a specific type
 * @type the type of objet to inject
 * @model the model to use
 * @return an array with all the mandatory mappings for the type
 */
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

/**
 * Return the list of all the mandatory types
 * @return an array with all the primary types
 */
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

/**
 * Return the type of an object by mapping type
 */
function getDeviceTypeByPluginType($type)
{
	global $DEVICES_TYPES;

	for ($index=1; $index < count($DEVICES_TYPES) +1; $index++)
		if ($type == $DEVICES_TYPES[$index])
			return $index;
	
	return 0;				
}
?>
