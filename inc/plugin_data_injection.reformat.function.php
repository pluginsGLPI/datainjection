<?php
/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

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

// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------

/*
 * Reformat datas if needed
 * @param model the model
 * @param line the line of data to inject
 * @param result of the check (input/ouput)
 * 
 * @return the line modified
 */
function reformatDatasBeforeCheck ($model,$line,$entity,&$result)
{
	global $DATA_INJECTION_MAPPING,$CFG_GLPI;

	$manu="";
	$rank_manu = -1;
	$mappings = $model->getMappings();
	
	// First pass : empty check + find the manufacturer
	foreach ($mappings as $mapping)
	{
		$rank = $mapping->getRank();
		
		if ($mapping->getValue() != NOT_MAPPED)
		{
			$mapping_definition = $DATA_INJECTION_MAPPING[$mapping->getMappingType()][$mapping->getValue()];
				
			//If a value is set to NULL -> ignore the value during injection
			if (!isset($line[$rank]) || $line[$rank] == "NULL") {
				$line[$rank]=EMPTY_VALUE;
			}	
			// Apply manufacturer dictionnary
			else if (isset($mapping_definition["table_type"]) 
				&& $mapping_definition["table_type"]=="dropdown"
				&& $mapping->getValue() == "manufacturer") {
	
				$rank_manu = $rank;
				
				// TODO : probably a bad idea to add new value in check, but...
				$id = externalImportDropdown('glpi_dropdown_manufacturer', $line[$rank], -1, array(), '', $model->getCanAddDropdown());
				$manu = getDropdownMinimalName('glpi_dropdown_manufacturer', $id);
	
				if ($id<=0) {
					$result->addInjectionMessage(WARNING_NOTFOUND, $line[$rank]);
				}
				$line[$rank] = $manu;
			}
			//If value is not in a hierarchical dropdown -> check if there's no < or > in it
			//Must be careful, because hierarchical dropdown tables uses > as separator !
			elseif (!in_array($mapping_definition["table"],$CFG_GLPI["dropdowntree_tables"])) {
				 $line[$rank] = reformatSpecialChars($line[$rank]);
				}
		}
	}

	// Second pass : if software -> process dictionnary. It must be done before third pass
	//because it can modify the manufacturer (which is used in third pass)
	if($model->getDeviceType() == SOFTWARE_TYPE)
	{		
		foreach ($mappings as $mapping)
		{
			$rank = $mapping->getRank();

			if ($mapping->getValue() == "name") {
				$rulecollection = new DictionnarySoftwareCollection;
				$res_rule = $rulecollection->processAllRules(array("name"=>$line[$rank],"manufacturer"=>$manu),array(),array());
				
				if(isset($res_rule["name"]))
					$line[$rank]=$res_rule["name"]; 
					
				if(isset($res_rule["manufacturer"]))
				{
					$manu = $res_rule["manufacturer"];
					if ($rank_manu > -1)
						$line[$rank_manu]=getDropdownName("glpi_dropdown_manufacturer",$res_rule["manufacturer"]);
				}
					
			}
		}
	}

	// Third pass : type check + apply dictionary
	foreach ($mappings as $mapping)
	{
		$rank = $mapping->getRank();

		if (isset($line[$rank]) && $line[$rank] != EMPTY_VALUE && $mapping->getValue() != NOT_MAPPED)
		{
			$mapping_definition = $DATA_INJECTION_MAPPING[$mapping->getMappingType()][$mapping->getValue()];

			// Check some types 
			switch ($mapping_definition["type"])
			{
				case "date":
					//If the value is a date, try to reformat it if it's not the good type (dd-mm-yyyy instead of yyyy-mm-dd)
					if (isset($mapping_definition["type"]) && $mapping_definition["type"]=="date")
						$line[$rank] = reformatDate($model->getDateFormat(),$line[$rank]);
					break;
				case "mac":
					$line[$rank]=reformatMacAddress($line[$rank]);
					break;
				default:
				break;
			}
			// Apply dropdown dictionnary
			if (isset($mapping_definition["table_type"]) 
					&& $mapping_definition["table_type"]=="dropdown" 
					&& $mapping->getValue()!="location"
					&& $mapping->getValue()!="manufacturer")  {
				(in_array($mapping_definition["table"],$CFG_GLPI["specif_entities_tables"])?$per_entity=$entity:$per_entity=-1);
						
				$id = externalImportDropdown($mapping_definition["table"], $line[$rank], $per_entity, 
					array("manufacturer" => $manu), '', $model->getCanAddDropdown());
				$val = getDropdownMinimalName($mapping_definition["table"], $id);
				if ($id<=0) {
					$result->addInjectionMessage(WARNING_NOTFOUND, $line[$rank]);
				}
				$line[$rank] = $val;					
			}
		}
	}
	return $line;
}

/**
 * Reformat date from dd-mm-yyyy to yyyy-mm-dd
 * @param original_date the original date
 * @return the date reformated, if needed
 */
function reformatDate($date_format,$original_date)
{
	switch ($date_format)
	{
		case DATE_TYPE_YYYYMMDD:
			$new_date=preg_replace('/(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})/','\1-\2-\3',$original_date);
		break;	
		case DATE_TYPE_DDMMYYYY:
			$new_date=preg_replace('/(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})/','\3-\2-\1',$original_date);
		break;
		case DATE_TYPE_MMDDYYYY:
			$new_date=preg_replace('/(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})/','\3-\1-\2',$original_date);
		break;
	}
	
	if (ereg('[0-9]{2,4}-[0-9]{1,2}-[0-9]{1,2}',$new_date))
		return $new_date;
	else
		return $original_date;	
}

/**
 * Reformat mac adress if mac doesn't contains : or - as seperator
 * @param mac the original mac address
 * @return the mac address modified, if needed
 */
function reformatMacAddress($mac)
{
	preg_match("/^([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/",$mac,$results);
	if (count($results) > 0)
	{
		$mac="";
		$first=true;
		unset($results[0]);
		foreach($results as $result)
		{
			$mac.=(!$first?":":"").$result;
			$first=false;
		}
	}
	return $mac;
}

/**
 * Replace < and > by their html code
 * @value to inject
 * @value modified
 */
function reformatSpecialChars($value)
{
	return str_replace(array('<','>'),array("&lt;","&gt;"),$value);
}
?>
