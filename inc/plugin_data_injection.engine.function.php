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
function checkType($type, $data)
{
	switch($type)
	{
		case 'text' :
			return TYPE_CHECK_OK;
		break;
		default :
			return ERROR_IMPORT_WRONG_TYPE;
	}
}

function checkLine($model,$line)
	{
		global $DATA_INJECTION_MAPPING;
		
		$res["result"] = true;
		
		//---- First, check types ----//
		for ($i = 0; $i < count($line); $i++)
		{
			//Get data to check
			$data = $line[$i];
			
			//Get mapping associated to the data
			$mapping_infos = $this->model->getMappingByRank($i);
			
			//If field is mandatory, check if it's present in the line
			if ($mapping_infos->isMandatory() && !isset($data[$mapping_infos->getName()]))
			 {
			 	$res = array ("result" => false, "message" => ERROR_IMPORT_FIELD_MANDATORY);
			 	break;
			 }	
				
			//Get mapping informations
			if (isset($DATA_INJECTION_MAPPING[$mapping_infos->getType()][$mapping_infos->getName()]))
			{
				$type = $DATA_INJECTION_MAPPING[$mapping_infos->getType()][$mapping_infos->getName()];
				
				$res_check_type = checkType($type, $data);
				if ($res_check_type != TYPE_CHECK_OK)
				{
					$res = array ("result" => false, "message" => $res_check_type);
					break;
				}
			}
		}
		
		//---- Second, check if line was not previously imported ----//
		//TODO : implement checks
		
		return $res;
	}
	

?>