<?php
/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2005 by the INDEPNET Development Team.

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

// Original Author of file: Walid Nouh (walid.nouh@atosorigin.com)
// Purpose of file:
// ----------------------------------------------------------------------

class DataInjectionEngine
{
	//Model informations
	var $model;
	
	//Backend to read file to import
	var $backend;

	var $entity;
		
	function DataInjectionEngine($model_id,$filename,$entity=0)
	{
		//Instanciate model
		$this->model = new DataInjectionModel;
		
		//Load model and mappings informations
		$this->model->loadAll($model_id);
		
		$this->entity = $entity;
		
		$datas = new InjectionDatas;

		//Get the backend associated with the model type (CSV, etc...)
		$this->backend = getBackend($this->model->getModelType());		
		$this->backend->initBackend($filename,$this->model->getDelimiter());
		$this->backend->read();
	}
	
	function getDatas()
	{
		if (isset($this->backend))
			return $this->backend->getDatas()->getDatas();
		else
			return array();	
	}
	
	/*
	 * Check all the datas and inject them
	 */
	function injectDatas()
	{
		$tab_result = array();
		$check_result = array();
		
		if ($this->model->isHeaderPresent())
			$i=1;
		else
			$i=0;
					
		for ($datas = $this->getDatas(); $i < count($datas);$i++)
		{
			$check_result = checkLine($this->model,$datas[$i][0]);

			if ($check_result["result"])
				$this->injectLine($this->model,$datas[$i][0],$this->entity);

			$tab_result[] = $check_result;
		}
			
		return $tab_result;
	}
	
	/*
	 * Inject one line of datas
	 * @param line one line of data to import
	 */
	function injectLine($model,$line,$entity)
	{
		//Array to store the fields to write to db
		$db_fields = array();
		
		for ($i=0; $i < count($line);$i++)
		{
			$mapping = $model->getMappingByRank($i);
			
			if ($mapping != null && $mapping->getValue() != NOT_MAPPED)
			{
				$mapping_definition = getMappingDefinitionByTypeAndName($mapping->getMappingType(),$mapping->getValue());
				if (!isset($db_fields[$mapping->getMappingType()]))
					$db_fields[$mapping->getMappingType()] = array();
				
				$obj = $db_fields[$mapping->getMappingType()];
				
				if (isset($mapping_definition["table_type"]) && $mapping_definition["table_type"] == "dropdown")
					$obj[$mapping_definition["linkfield"]] = insertDropdownValue($mapping,$mapping_definition,$line[$i],$entity);
				else
					$obj[$mapping_definition["field"]] = $line[$i];
				
				//Add the active entity
				$obj["FK_entities"] = $entity;
				$db_fields[$mapping->getMappingType()] = $obj;
			}
		}
		
	
		//Insert datas in databse
		foreach ($db_fields as $type => $fields)
		{
			$obj = getInstance($type);

			$ID = dataAlreadyInDB($type,$fields,$mapping_definition,$model);
			if ($ID == -1)
			{
				if ($model->getBehaviorAdd())
					echo "ADD ID=".$obj->add($fields)."\n";
			}	
			elseif ($model->getBehaviorupdate())
			{
				$fields["ID"] = $ID;
				echo "update ID=".$obj->update($fields);
			}	
		}
				
	}
}
?>