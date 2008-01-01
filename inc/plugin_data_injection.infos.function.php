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

function getAllInfosDefinitionsTypes($primary_type)
{
	$types = array();
	$commonitem = new CommonItem;

	$commonitem->setType($primary_type);
	$types[] = array($primary_type,$commonitem->getType());
	
	switch ($primary_type)
	{
		case CONTACT_TYPE:
		//nobreak
		case CONTRACT_TYPE:
		//nobreak
		case USER_TYPE:
		//nobreak
		case GROUP_TYPE:
		//nobreak
		case ENTERPRISE_TYPE:
		//nobreak
		case ENTITY_TYPE:
		break;
		
		//Add infocom type
		default:
			$commonitem->setType(INFOCOM_TYPE);
			$types[] = array(INFOCOM_TYPE,$commonitem->getType());
		break;	
	}

	asort($types);
	return $types;
}

function getAllInfosDefinitionsByType($type)
{
	global $DATA_INJECTION_INFOS,$LANG;
	$mapping_parameters = array();
	
	foreach ($DATA_INJECTION_INFOS[$type] as $name => $mapping)
	{
		$mapping_parameters[$name] = $mapping["name"];
	}
	asort($mapping_parameters);
	return 	$mapping_parameters;
}

?>
