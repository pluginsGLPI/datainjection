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

	private $mappings;
	private $backend;
	protected $infos;
		
	function DataInjectionModel()
	{
		$this->table="glpi_plugin_data_injection_models";
    	$this->type=-1;
    	$this->mappings = new MappingCollection;
    	$this->infos = new InfosCollection;
    	$this->init();
	}

	function init()
	{
		
	}
	
	//To be implemented
	function saveSpecificFields()
	{

	}
	
	function deleteSpecificFields()
	{
	}
	
	function updateSpecificFields()
	{

	}

	function loadSpecificfields()
	{
		
	}

	//---- Load -----//	


	function loadAll($model_id)
	{
		if ($this->getFromDB($model_id))
		{
			$this->loadSpecificfields();
			$this->loadMappings($model_id);
			$this->loadInfos($model_id);
			return true;
		}
		else
			return false;	
	}
	function loadMappings($model_id)
	{
		$this->mappings->getAllMappingsByModelID($model_id);
	}
	
	function loadInfos($model_id)
	{
		$this->infos->getAllInfosByModelID($model_id);
	}

	//---- Add -----//	
	function addMappingToModel($mapping)
	{
		$this->mappings->addNewMapping($mapping);
	}
	
	function addInfosToModel($infos)
	{
		$this->infos->addNewInfos($infos);
	}

	//---- Save -----//	
	function saveModel()
	{
		//Save or add model
		if (!isset($this->fields["ID"]))
			$this->fields["ID"] = $this->add($this->fields);
		else
			$this->update($this->fields);
		
		$this->saveSpecificFields();
		
		//Save or add mappings
		$this->mappings->saveAllMappings($this->fields["ID"]);
		$this->infos->saveAllInfos($this->fields["ID"]);		
	}
	
	function deleteModel()
	{
		if($this->mappings->deleteMappingsFromDB($this->fields["ID"]) && $this->infos->deleteInfosFromDB($this->fields["ID"]))
			{
			$this->deleteSpecificFields();
			if($this->deleteFromDB($this->fields["ID"]))
				return true;
			else
				return false;
			}
		else
			return false;
	}
	
	function updateModel()
	{
		$this->update($this->fields);
		$this->updateSpecificFields();
		$this->mappings->deleteMappingsFromDB($this->fields["ID"]);
		$this->mappings->saveAllMappings($this->fields["ID"]);
		$this->infos->deleteInfosFromDB($this->fields["ID"]);
		$this->infos->saveAllInfos($this->fields["ID"]);
	}

	//---- Getters -----//
	function getMappings()
	{
		return $this->mappings->getAllMappings();
	}
	
	function getInfos()
	{
		return $this->infos->getAllInfos();
	}
	
	function getBackend()
	{
		return $this->backend;
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

	function getModelID()
	{
		return $this->fields["ID"];
	}
	
	function getDeviceType()
	{
		return $this->fields["device_type"];
	}
	
	function getEntity()
	{
		return $this->fields["FK_entities"];
	}

	function getCanAddDropdown()
	{
		return $this->fields["can_add_dropdown"];
	}
	
	function getCanOverwriteIfNotEmpty()
	{
		return $this->fields["can_overwrite_if_not_empty"];
	}
	
	function getPublic()
	{
		return $this->fields["public"];
	}
	
	function getUserID()
	{
		return $this->fields["user_id"];
	}

	function getPerformNetworkConnection()
	{
		return $this->fields["perform_network_connection"];
	}

	//---- Save -----//
	function setModelType($type)
	{
		$this->fields["type"] = $type;
	}
	
	function setName($name)
	{
		$this->fields["name"] = $name;
	}	
	
	function setComments($comments)
	{
		$this->fields["comments"] = $comments;
	}	
	
	function setBehaviorAdd($add)
	{
		$this->fields["behavior_add"] = $add;
	}
	
	function setBehaviorUpdate($update)
	{
		$this->fields["behavior_update"] = $update;
	}
	
	function setModelID($ID)
	{
		$this->fields["ID"] = $ID;
	}	
	
	function setMappings($mappings)
	{
		$this->mappings = $mappings;
	}
	
	function setInfos($infos)
	{
		$this->infos = $infos;
	}		
	
	function setBackend($backend)
	{
		$this->backend = $backend;
	}
	
	function setDeviceType($device_type)
	{
		$this->fields["device_type"] = $device_type; 
	}

	function setEntity($entity)
	{
		$this->fields["FK_entities"] = $entity; 
	}

	function setCanAddDropdown($canadd)
	{
		$this->fields["can_add_dropdown"] = $canadd; 
	}

	function setCanOverwriteIfNotEmpty($canoverwrite)
	{
		$this->fields["can_overwrite_if_not_empty"] = $canoverwrite; 
	}
	
	function setPublic($public)
	{
		$this->fields["public"] = $public;
	}
		
	function setUserID($user)
	{
		$this->fields["user_id"] = $user;
	}

	function setPerformNetworkConnection($perform)
	{
		$this->fields["perform_network_connection"] = $perform;
	}
	
	function setFields($fields,$entity,$user_id)
	{
		$this->setEntity($entity);
		
		$this->setUserID($user_id);
		
		if(isset($fields["dropdown_device_type"]))
			$this->setDeviceType($fields["dropdown_device_type"]);
		
		if(isset($fields["dropdown_type"]))
			$this->setModelType($fields["dropdown_type"]);
		
		if(isset($fields["dropdown_create"]))
			$this->setBehaviorAdd($fields["dropdown_create"]);
		
		if(isset($fields["dropdown_update"]))
			$this->setBehaviorUpdate($fields["dropdown_update"]);
			
		if(isset($fields["dropdown_canadd"]))
			$this->setCanAddDropdown($fields["dropdown_canadd"]);
			
		if(isset($fields["can_overwrite_if_not_empty"]))
			$this->setCanOverwriteIfNotEmpty($fields["can_overwrite_if_not_empty"]);
		
		if(isset($fields["dropdown_public"]))
			$this->setPublic($fields["dropdown_public"]);

		if(isset($fields["perform_network_connection"]))
			$this->setPerformNetworkConnection($fields["perform_network_connection"]);
	}
	
}

?>
