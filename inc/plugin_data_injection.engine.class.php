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
	private $model;
	
	//Backend to read file to import
	private $backend;

	//Current entity
	private $entity;
		
	function DataInjectionEngine($model_id,$filename,$entity=0)
	{
		//Instanciate model
		$this->model = getModelInstanceByID($model_id);
		
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
	 * Return the number of lines of the file
	 * @return the number of line of the file
	 */
	function getNumberOfLines()
	{
		return $this->backend->getNumberOfLine();
	}
	
	/*
	 * Inject one line of datas
	 * @param line one line of data to import
	 */
	function injectLine($line,$infos = array())
	{
		$result = new DataInjectionResults;

		$result = checkLine($this->model,$line,$result);
		if (!$result->getStatus())
			return $result;
			
		//Array to store the fields to write to db
		$db_fields = array();
		$db_fields["common"] = array();
		$db_fields[$this->model->getDeviceType()] = array();
		
		for ($i=0; $i < count($line);$i++)
		{
			$mapping = $this->model->getMappingByRank($i);
			if ($mapping != null && $mapping->getValue() != NOT_MAPPED)
			{
				$mapping_definition = getMappingDefinitionByTypeAndName($mapping->getMappingType(),$mapping->getValue());
				if (!isset($db_fields[$mapping->getMappingType()]))
					$db_fields[$mapping->getMappingType()] = array();
				
				$db_fields[$mapping->getMappingType()] = getFieldValue(
						$mapping, 
						$mapping_definition,$line[$i],
						$this->getEntity(),
						$db_fields[$mapping->getMappingType()],
						$this->model->getCanAddDropdown()
				);
			}
		}

		//Add informations filleds by the user
		$db_fields = addInfosFields($db_fields,$infos);

		$process = true;
		//----------------------------------------------------//
		//-------------Process primary type------------------//
		//--------------------------------------------------//
		
		//First, try to insert or update primary object
		$fields = $db_fields[$this->model->getDeviceType()];

		$obj = getInstance($this->model->getDeviceType());
		//If necessary, add default fields which are mandatory to create the object
		$fields = addNecessaryFields($this->model,$mapping,$mapping_definition,$this->getEntity(),$this->model->getDeviceType(),$fields,$db_fields["common"]);

		//Check if the line already exists in database
		$fields_from_db = dataAlreadyInDB($this->model->getDeviceType(),$fields,$mapping_definition,$this->model);

		$ID = $fields_from_db["ID"];
		if ($ID == -1)
		{
			if ($this->model->getBehaviorAdd())
			{
				$ID = $obj->add($fields);
				//Add the ID to the fields, so it can be reused after
				$db_fields["common"] = addCommonFields($db_fields["common"],$this->model->getDeviceType(),$fields,$this->getEntity(),$ID);
				logAddOrUpdate($this->model->getDeviceType(),$ID,INJECTION_ADD);

				$result->setStatus(true);

				$result->setInjectedId($ID);
				$result->setInjectionType(INJECTION_ADD);

				$result->setInjectionMessage(IMPORT_OK);
			}
			else
			{
				//Object doesn't exists, but add in not allowed by the model
				$process = false;
				
				$result->setStatus(false);

				$result->setInjectedId(NOT_IMPORTED);
				$result->setInjectionType(INJECTION_ADD);
				
				$result->setInjectionMessage(ERROR_CANNOT_IMPORT);
			}
		}	
		elseif ($this->model->getBehaviorUpdate())
		{
			$fields = filterFields($fields,$fields_from_db,$this->model->getCanOverwriteIfNotEmpty());
			$fields["ID"] = $ID;

			$db_fields["common"] = addCommonFields($db_fields["common"],$this->model->getDeviceType(),$fields,$this->getEntity(),$ID);

			if (count($fields) > 1)
			{
				logAddOrUpdate($this->model->getDeviceType(),$ID,INJECTION_UPDATE);
				$obj->update($fields);
			}
			$result->setStatus(true);

			$result->setInjectedId($ID);
			$result->setInjectionType(INJECTION_UPDATE);

			$result->setInjectionMessage(IMPORT_OK);
		}
		else
		{	
			//Object exists but update is not allowed by the model
			$process = false;

			$result->setStatus(false);

			$result->setInjectedId($ID);
			$result->setInjectionType(INJECTION_UPDATE);
			
			$result->setInjectionMessage(ERROR_CANNOT_UPDATE);
		}
		if ($process)
		{
			//Post processing, if some actions need to be done
			processBeforeEnd($this->model,$this->model->getDeviceType(),$fields,$db_fields["common"]);

			//----------------------------------------------------//
			//-------------Process other types-------------------//
			//--------------------------------------------------//

			//Insert others objects in database
			foreach ($db_fields as $type => $fields)
			{
				if ($type != "common" && $type != $this->model->getDeviceType())
				{
					$obj = getInstance($type);
					//If necessary, add default fields which are mandatory to create the object
					$fields = addNecessaryFields($this->model,$mapping,$mapping_definition,$this->getEntity(),$type,$fields,$db_fields["common"]);
					
					//Check if the line already exists in database
					$ID = dataAlreadyInDB($type,$fields,$mapping_definition,$this->model);
					if ($ID == -1)
					{
							$ID = $obj->add($fields);
							//Add the ID to the fields, so it can be reused after
							$db_fields["common"] = addCommonFields($db_fields["common"],$this->model->getDeviceType(),$fields,$this->getEntity(),$ID);
					}	
					else
					{
						$db_fields["common"] = addCommonFields($db_fields["common"],$type,$fields,$this->entity,$ID);
						$obj->update($fields);
					}

					//Post processing, if some actions need to be done
					processBeforeEnd($this->model,$type,$fields,$db_fields["common"]);
				}
			}
		}
		return $result;			
	}

	function getModel()
	{
		return $this->model;
	}

	function getBackend()
	{
		return $this->backend;
	}

	function getEntity()
	{
		return $this->entity;
	}
}

?>