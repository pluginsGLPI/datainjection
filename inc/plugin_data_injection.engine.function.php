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

/*
 * Check if the data to import is the good type
 * @param the type of data waited
 * @data the data to import
 * @return true if the data is the correct type
 */
function checkType($type, $name, $data)
{
	global $DATA_INJECTION_MAPPING;
	
	if (isset($DATA_INJECTION_MAPPING[$type][$name]))
	{
		$field_type = $DATA_INJECTION_MAPPING[$type][$name]['type'];
		switch($field_type)
		{
			case 'text' :
				return TYPE_CHECK_OK;
			break;
			default :
				return ERROR_IMPORT_WRONG_TYPE;
		}
	}
	else
		return ERROR_IMPORT_WRONG_TYPE;
}

/*
 * check one line of data to import
 * @param model the model to use
 * @param line the line of datas
 * @return an array to give the result of the check ("result"=>value,"message"=>error message if any)
 */
function checkLine($model,$line)
	{
		$res = array ("result"=>true, "message"=>TYPE_CHECK_OK);
		
		//Get all mappings for a model
		for ($i=0, $mappings = $model->getMappings()->getAllMappings(); $i < count($mappings); $i++)
		{
			$mapping = $mappings[$i];
			$rank = $mapping->getRank();

			//If field is mandatory AND not present in the datas to inject -> error
			if ($mapping->isMandatory() && (!isset($line[$rank]) || $line[$rank] == ""))
			{
				 	$res = array ("result" => false, "message" => ERROR_IMPORT_FIELD_MANDATORY);
					break;				
			}
			else
			{
				//If field exists
				if (isset($line[$rank]))
				{
					//Check type
					$field = $line[$rank];
					$res_check_type = checkType($model->getModelType(), $mapping->getValue(), $field);
					
					//If field is not the good type -> error
					if ($res_check_type != TYPE_CHECK_OK)
					{
						$res = array ("result" => false, "message" => $res_check_type);
						break;
					}
				}	
			}
		}

		//---- Second, check if line was not previously imported ----//
		//TODO : implement checks
		
		return $res;
	}
	

?>