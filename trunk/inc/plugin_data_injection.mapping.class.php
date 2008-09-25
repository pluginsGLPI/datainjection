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
class DataInjectionMapping extends CommonDBTM {
	
	function DataInjectionMapping()
	{
		$this->table="glpi_plugin_data_injection_mappings";
    	$this->type=-1;
	}
	
	/*
	 * 
	 */
	function equal($field,$value)
	{
		if (!isset($this->fields[$field]))
			return false;
			
		if ($this->fields[$field] == $value)
			return true;
		else
			return false;	
	}
	
	function isMandatory()
	{
		return $this->fields["mandatory"];
	}
	
	function getName()
	{
		return $this->fields["name"];
	}
	
	function getRank()
	{
		return $this->fields["rank"];
	}
	
	function getValue()
	{
		return $this->fields["value"];
	}
	
	function getID()
	{
		return $this->fields["ID"];
	}
	
	function getModelID()
	{
		return $this->fields["model_id"];
	}
	
	function getMappingType()
	{
		return $this->fields["type"];
	}
	
	function setMandatory($mandatory)
	{
		$this->fields["mandatory"] = $mandatory; 
	}
	
	function setName($name)
	{
		$this->fields["name"] = $name;
	}
	
	function setRank($rank)
	{
		$this->fields["rank"] = $rank;
	}
	
	function setValue($value)
	{
		$this->fields["value"] = $value;
	}
	
	function setID($ID)
	{
		$this->fields["ID"] = $ID;
	}
	
	function setModelID($model_id)
	{
		$this->fields["model_id"] = $model_id;
	}
	
	function setMappingType($type)
	{
		$this->fields["type"] = $type;
	}	
}

class MappingCollection {
	
	private $mappingCollection;
	
	function MappingCollection()
	{
		$this->mappingCollection = array();
	}
	
	//---- Getter ----//
	
	/*
	 * Load all the mappings for a specified model
	 * @param model_id the model ID
	 */
	function getAllMappingsByModelID($model_id)
	{
		global $DB;
		
		$sql = "SELECT * FROM glpi_plugin_data_injection_mappings WHERE model_id=".$model_id." ORDER BY rank ASC";
		$result = $DB->query($sql);
		$this->mappingCollection = array();
		while  ($data = $DB->fetch_array($result))
		{
			// Addslashes to conform to value return by parseLine
			$data["name"] = addslashes($data["name"]);
			
			$mapping = new DataInjectionMapping;
			$mapping->fields = $data;
			$this->mappingCollection[] = $mapping;
		}
	}
	
	/*
	 * Return all the mappings for this model
	 * @return the list of all the mappings for this model
	 */
	function getAllMappings()
	{
		return $this->mappingCollection;
	}
	
	/*
	 * Get a DataInjectionMapping by giving the mapping name
	 * @param name
	 * @return the DataInjectionMapping object associated or null
	 */
	function getMappingByName($name)
	{
		return $this->getMappingsByField("name",$name);
	}

	/*
	 * Get a DataInjectionMapping by giving the mapping rank
	 * @param rank
	 * @return the DataInjectionMapping object associated or null
	 */
	function getMappingByRank($rank)
	{
		return $this->getMappingsByField("rank",$rank);
	}
	
	/*
	 * Find a mapping by looking for a specific field
	 * @param field the field to look for
	 * @param the value of the field
	 * @return the DataInjectionMapping object associated or null
	 */
	function getMappingsByField($field,$value)
	{
		foreach ($this->mappingCollection as $mapping)
		{
			if ($mapping->equal($field,$value))
				return $mapping;
		}
		return null;
	}
	
	//---- Save ----//
	
	/*
	 * Save in database the model and all his associated mappings
	 */
	function saveAllMappings($model_id)
	{
		foreach ($this->mappingCollection as $mapping)
		{
			$mapping->setModelID($model_id);
			
			if (isset($mapping->fields["ID"]))
				$mapping->update($mapping->fields);
			else
				$mapping->fields["ID"] = $mapping->add($mapping->fields);
		}
	}
	
	//---- Delete ----//
	
	function deleteMappingsFromDB($model_id)
	{
		global $DB;
		
		$sql = "DELETE from glpi_plugin_data_injection_mappings WHERE model_id =".$model_id;
		if ($result = $DB->query($sql)) 
			return true;
		else
			return false;
	}
	
	//---- Add ----//
	
	/*
	 * Add a new mapping to this model (don't write in to DB)
	 * @param mapping the new DataInjectionMapping to add
	 */
	function addNewMapping($mapping)
	{
		$this->mappingCollection[] = $mapping;
	}
	
	/*
	 * Replace all the mappings for a model
	 * @mappins the array of DataInjectionMapping objects
	 */
	function replaceMappings($mappings)
	{
		$this->mappingCollection = $mappings;
	}
}
?>
