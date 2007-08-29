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
	
	/*
	 * Get datas imported read from the file
	 */
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
		$global_result = new DataInjectionResults;
		
		
		if ($this->model->isHeaderPresent())
			$i=1;
		else
			$i=0;

		for ($datas = $this->getDatas(); $i < count($datas);$i++)
		{
			$global_result = checkLine($this->model,$datas[$i][0],$global_result);
			if ($global_result->getStatus())
				$global_result = $this->injectLine($this->model,$datas[$i][0],$global_result);

			$tab_result[] = $global_result;
		}
			
		return $tab_result;
	}
	
	/*
	 * Inject one line of datas
	 * @param line one line of data to import
	 */
	function injectLine($model,$line,$result)
	{
		//Array to store the fields to write to db
		$db_fields = array();
		$db_fields["common"] = array();
		$db_fields[$model->getDeviceType()] = array();
		
		for ($i=0; $i < count($line);$i++)
		{
			$mapping = $model->getMappingByRank($i);
			if ($mapping != null && $mapping->getValue() != NOT_MAPPED)
			{
				$mapping_definition = getMappingDefinitionByTypeAndName($mapping->getMappingType(),$mapping->getValue());
				if (!isset($db_fields[$mapping->getMappingType()]))
					$db_fields[$mapping->getMappingType()] = array();
				
				$db_fields[$mapping->getMappingType()] = getFieldValue(
						$mapping, 
						$mapping_definition,$line[$i],
						$this->entity,
						$db_fields[$mapping->getMappingType()]
				);
				
			}
		}

		$process = true;
		//----------------------------------------------------//
		//-------------Process primary type------------------//
		//--------------------------------------------------//
		
		//First, try to insert or update primary object
		$fields = $db_fields[$model->getDeviceType()];

		$obj = getInstance($model->getDeviceType());
		//If necessary, add default fields which are mandatory to create the object
		$fields = addNecessaryFields($model,$mapping,$mapping_definition,$this->entity,$model->getDeviceType(),$fields,$db_fields["common"]);

		//Check if the line already exists in database
		$ID = dataAlreadyInDB($model->getDeviceType(),$fields,$mapping_definition,$model);
	
		if ($ID == -1)
		{
			if ($model->getBehaviorAdd())
			{
				$ID = $obj->add($fields);
				//Add the ID to the fields, so it can be reused after
				$db_fields["common"] = addCommonFields($db_fields["common"],$model->getDeviceType(),$fields,$this->entity,$ID);
				//echo "ADD=$ID\n"; 
				$result->setInjectionType("add");
				$result->setInjectionType(IMPORT_OK);
			}
			else
			{
				//Object doesn't exists, but add in not allowed by the model
				$process = false;
				$result->setStatus(false);
				$result->setInjectionMessage(ERROR_CANNOT_IMPORT);
			}
		}	
		elseif ($model->getBehaviorUpdate())
		{
			$fields["ID"] = $ID;
			$db_fields["common"] = addCommonFields($db_fields["common"],$model->getDeviceType(),$fields,$this->entity,$ID);
			$obj->update($fields);
			//echo "update ID=$ID\n";
			$result->setInjectionType("update");
			$result->setInjectionMessage(IMPORT_OK);
		}
		else
		{	
			//Object exists but update is not allowed by the model
			$process = false;
			$result->setStatus(false);
			$result->setInjectionMessage(ERROR_CANNOT_IMPORT);
		}
		if ($process)
		{
			//Post processing, if some actions need to be done
			processBeforeEnd($model,$model->getDeviceType(),$fields,$db_fields["common"]);

			//----------------------------------------------------//
			//-------------Process other types-------------------//
			//--------------------------------------------------//
	
			//Insert others objects in database
			foreach ($db_fields as $type => $fields)
			{
				if ($type != "common" && $type != $model->getDeviceType())
				{
					$obj = getInstance($type);
					//If necessary, add default fields which are mandatory to create the object
					$fields = addNecessaryFields($model,$mapping,$mapping_definition,$this->entity,$type,$fields,$db_fields["common"]);
					
					//Check if the line already exists in database
					$ID = dataAlreadyInDB($type,$fields,$mapping_definition,$model);
					if ($ID == -1)
					{
							$ID = $obj->add($fields);
							//Add the ID to the fields, so it can be reused after
							$db_fields["common"] = addCommonFields($db_fields["common"],$model->getDeviceType(),$fields,$this->entity,$ID);
					}	
					else
					{
						$db_fields["common"] = addCommonFields($db_fields["common"],$type,$fields,$this->entity,$ID);
						$obj->update($fields);
					}

					//Post processing, if some actions need to be done
					processBeforeEnd($model,$type,$fields,$db_fields["common"]);

				}
				
			}
			$result->setStatus(true);
			
		}
		return $result;			
	}
}

?>