<?php
/*
 * @version $Id: rules.constant.php 5351 2007-08-07 11:57:46Z walid $
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
class DataInjectionModel extends CommonDBTM {

	var $mappings;
		
	function DataInjectionModel()
	{
		$this->table="glpi_plugin_data_injection_models";
    	$this->type=-1;
    	$this->mappings = new MappingCollection;
	}

	//---- Load -----//	
	function loadAll($model_id)
	{
		if ($this->getFromDB($model_id))
		{
			$this->loadMappings($model_id);
			return true;
		}
		else
			return false;	
	}
	function loadMappings($model_id)
	{
		$this->mappings->getAllMappingsByModelID($model_id);
	}

	//---- Add -----//	
	function addMappingToModel($mapping)
	{
		$this->mappings->addNewMapping($mapping);
	}

	//---- Save -----//	
	function saveModel()
	{
		//Save or add model
		if (!isset($this->fields["ID"]))
			$this->fields["ID"] = $this->add($this->fields);
		else
			$this->update($this->fields);
		
		//Save or add mappings
		$this->mappings->saveAllMappings();		
	}

	//---- Getters -----//
	function getMappings()
	{
		return $this->mappings;
	}
	
	function getMappingByName($name)
	{
		return $this->mappings->getMappingByName($name);
	}

	function getMappingByRank($rank)
	{
		return $this->mappings->getMappingByRank($rank);
	}
	
	function getModelInfos()
	{
		return $this->fields;
	}
	
	function getModelName()
	{
		return $this->fields["name"];
	}
	
	function getModelType()
	{
		return $this->fields["type"];
	}
	
	function getModelComments()
	{
		return $this->fields["comments"];
	}

	function getBehaviorAdd()
	{
		return $this->fields["behavior_add"];
	}
	
	function getBehaviorUpdate()
	{
		return $this->fields["behavior_update"];
	}

	function getDelimiter()
	{
		return $this->fields["delimiter"];
	}
	
	function getModelID()
	{
		return $this->fields["ID"];
	}
	
	function isHeaderPresent()
	{
		return ($this->fields["header_present"]?true:false);
	}
	
}

?>
