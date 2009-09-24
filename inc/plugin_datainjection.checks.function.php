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
/**
 * Check if the data to import is the good type
 * 
 * @param the type of data waited
 * @data the data to import
 * 
 * @return true if the data is the correct type
 */
function checkType($type, $name, $data,$mandatory)
{
	global $DATA_INJECTION_MAPPING;

	if (isset($DATA_INJECTION_MAPPING[$type][$name]))
	{
		$field_type = $DATA_INJECTION_MAPPING[$type][$name]['type'];

		//If no data provided AND this mapping is not mandatory
		if (!$mandatory && ($data == null || $data == "NULL" || $data == EMPTY_VALUE))
			return TYPE_CHECK_OK;
			
		switch($field_type)
		{
			case 'text' :
				return TYPE_CHECK_OK;
			break;
			case 'integer' :
				if (is_numeric($data))
					return TYPE_CHECK_OK;
				else
					return ERROR_IMPORT_WRONG_TYPE;
			break;
			case 'float':
				if (is_true_float($data))
					return TYPE_CHECK_OK;
				else
					return ERROR_IMPORT_WRONG_TYPE;
			break;
			case 'date' :
				preg_match("/([0-9]{4})[\-]([0-9]{2})[\-]([0-9]{2})/",$data,$regs);
				if (count($regs) > 0)
					return TYPE_CHECK_OK;
				else
					return ERROR_IMPORT_WRONG_TYPE;
			break;	
			case 'ip':
				preg_match("/([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/",$data,$regs);
				if (count($regs) > 0)
					return TYPE_CHECK_OK;
				else
					return ERROR_IMPORT_WRONG_TYPE;
			break;
			case 'mac':
				preg_match("/([0-9a-fA-F]{2}([:-]|$)){6}$/",$data,$regs);
				if (count($regs) > 0)
					return TYPE_CHECK_OK;
				else
					return ERROR_IMPORT_WRONG_TYPE;
			break;
			case 'glpi_type':
				$commonitem = new Commonitem;
				$commonitem->setType($data);
				if($commonitem->obj != null)
					return TYPE_CHECK_OK;
				else
					return ERROR_IMPORT_WRONG_TYPE;
			break;
			default :
				return ERROR_IMPORT_WRONG_TYPE;
		}
	}
	else
		return ERROR_IMPORT_WRONG_TYPE;

}

/**
 * check one line of data to import
 * 
 * @param model the model to use
 * @param line the line of datas
 * @param result of the check (input/ouput)
 * 
 * @return status of the check
 */
function checkLine($model,$line,&$res)
{
		//Get all mappings for a model
		for ($i=0, $mappings = $model->getMappings(); $i < count($mappings); $i++)
		{
			$mapping = $mappings[$i];
			$rank = $mapping->getRank();

			//If field is mandatory AND not mapped -> error
			if ($mapping->isMandatory() && (!isset($line[$rank]) || $line[$rank] == NULL || $line[$rank] == EMPTY_VALUE || $line[$rank] == -1))
			{
				$res->addCheckMessage(ERROR_IMPORT_FIELD_MANDATORY,$mapping->getName());
					break;				
			}
			else
			{
				//If field exists and if field is mapped
				if (isset($line[$rank]) && $line[$rank] != "" && $mapping->getValue() != NOT_MAPPED)
				{
					//Check type
					$field = $line[$rank];
					$res_check_type = checkType($mapping->getMappingType(), $mapping->getValue(), $field,$mapping->isMandatory());

					//If field is not the good type -> error
					if (!$res->addCheckMessage($res_check_type,$mapping->getName())) {
						break;
					}
				}	
			}
		}

		return $res->getStatus(true);
	}


/**
 * check if a network plug exists, if not then create it
 * @param result the array to log injection informations
 * @param fields the fields to use to check if plug exists or not
 * @param canadd indicates if a network plug can be created or not
 * 
 * @return the network port's ID
 */
function checkNetpoint($result,$fields,$canadd) {
	global $DB, $LANG;

	$primary_type = $fields["device_type"];
	$entity = $fields["FK_entities"];
	$location = $fields["location"];
	$value = $fields["netpoint"];
	$port_id = $fields["network_port_id"];
	
	// networking device can use netpoint in all the entity
	$sql="SELECT ID FROM glpi_dropdown_netpoint WHERE FK_entities=$entity AND name='$value'";
	
	if ($primary_type != NETWORKING_TYPE) {
		// other device can only use netpoint in the location
		$sql .= " AND location=$location";
	}
	
	$res = $DB->query($sql);
	if ($DB->numrows($res)>0) {
		// found
		$ID = $DB->result($res,0,"ID");
		
		// Check if not used
		$sql2="SELECT ID FROM glpi_networking_ports WHERE netpoint=$ID AND device_type".($primary_type == NETWORKING_TYPE ?"=":"!=").NETWORKING_TYPE;
		$res2 = $DB->query($sql2);
		if ($DB->numrows($res2)>0) {
			$usedby = $DB->result($res2,0,"ID");
			if ($usedby != $port_id) {
				$result->addInjectionMessage(WARNING_USED, "netpoint");	
			}
			return EMPTY_VALUE;					
		} else {
			return $ID;
		}
					
	} else if ($canadd) {
		$input["tablename"] = "glpi_dropdown_netpoint";
		$input["value"] = $value;
		$input["value2"] = $location;
		$input["FK_entities"] = $entity;
		$input["type"] = EMPTY_VALUE;
		$input["comments"] = EMPTY_VALUE;
		
		return addDropdown($input);
				
	} else {
		$result->addInjectionMessage(WARNING_NOTFOUND, $LANG["networking"][51] . " '$value'");
		return EMPTY_VALUE;			
	}
}

/**
 * Find a user. Look for login OR firstname + lastname OR lastname + firstname
 * @param value the user to look for
 * @param entity the entity where the user should have right
 * @return the user ID if found or ''
 */
function findUser($value,$entity)
{
	global $DB;
	
	$sql = "SELECT ID FROM glpi_users WHERE LOWER(name)=\"".strtolower($value)."\" OR (CONCAT(LOWER(realname),' ',LOWER(firstname))=\"".strtolower($value)."\" OR CONCAT(LOWER(firstname),' ',LOWER(realname))=\"".strtolower($value)."\")";
	$result = $DB->query($sql);
	if ($DB->numrows($result)>0)
	{
		//check if user has right on the current entity
		$ID = $DB->result($result,0,"ID");
		$entities = getUserEntities($ID,true);
		if (in_array($entity,$entities))
			return $ID;
		else
			return 0;	
	}
	else
		return 0;		
}


/**
 * Find a user. Look for login OR firstname + lastname OR lastname + firstname
 * @param value the user to look for
 * @param entity the entity where the user should have right
 * @return the user ID if found or ''
 */
function findContact($value,$entity)
{
	global $DB;
	$sql = "SELECT ID FROM glpi_contacts WHERE FK_entities=".$entity." AND (LOWER(name)=\"".strtolower($value)."\" OR (CONCAT(LOWER(name),' ',LOWER(firstname))=\"".strtolower($value)."\" OR CONCAT(LOWER(firstname),' ',LOWER(name))=\"".strtolower($value)."\"))";
	$result = $DB->query($sql);
	if ($DB->numrows($result)>0)
	{
		//check if user has right on the current entity
		return $DB->result($result,0,"ID");
	}
	else
		return EMPTY_VALUE;		
}

/**
 * Create a tree of locations
 * @param location the full tree of locations
 * @param entity the current entity
 * @param canadd indicates if the user has the right to add locations
 * @return the location ID
 */
function checkLocation ($location, $entity, $canadd,$comments = EMPTY_VALUE)
{
	$location_id = 0;
	$locations = explode('>',$location);

	if ($comments != EMPTY_VALUE)
	{
		$final_location = count($locations);
		$i = 0;
	}
	
	foreach ($locations as $location)
	{

		if ($location_id !== EMPTY_VALUE)
			$location_id = addLocation(trim($location),
				$entity,
				$location_id,
				$canadd,
				//Only add comments on the final location
				($comments!= EMPTY_VALUE && $i==($final_location-1)?$comments:EMPTY_VALUE)
			);
			
		if ($comments != EMPTY_VALUE)
			$i++;		
	}
		
	return $location_id;	
}

/**
 * Add a location at a specified level
 * @param location the full tree of locations
 * @param entity the current entity
 * @param the parentid ID of the parent location
 * @param canadd indicates if the user has the right to add locations
 * @param comments the dropdown comments
 * @return the location ID
 */
function addLocation($location,$entity,$parentid,$canadd,$comments=EMPTY_VALUE)
{
	$input["tablename"] = "glpi_dropdown_locations";
	$input["value"] = $location;
	$input["value2"] = $parentid;
	$input["type"] = "under";
	$input["comments"] = $comments;
	$input["FK_entities"] = $entity;
	
	$ID = getDropdownID($input);
	
	if ($ID != -1) {
		return $ID;
	}
	
	if ($canadd) {
		return addDropdown($input);
	} else {
		return EMPTY_VALUE;
	}	
}

/**
 * Locate an entity
 * @param location the full tree of locations
 * @param entity the current entity
 * @return the location ID
 */
function checkEntity ($completename, $entity)
{
	global $DB;
	$sql = "SELECT ID FROM glpi_entities WHERE completename='".addslashes($completename)."' ".getEntitiesRestrictRequest("AND","glpi_entities","parentID");
	$result = $DB->query($sql);
	if ($DB->numrows($result)==1)
		return $DB->result($result,0,"ID");
	else
		return $entity;	
	
}

function addEntityPostProcess($common_fields)
{
	global $DB;
	
	$fields = array();
	$fields_list = array("FK_entities","address","postcode","town","state","country","website","phonenumber",
                        "fax","email","notes","admin_email","admin_reply","tag","ldap_dn");
	foreach ($fields_list as $field)
		if (isset($common_fields[$field]))
			$fields[$field]=$common_fields[$field];
	
   if (!empty($fields))
   {
      //Check is entity data still exists
      $query = "SELECT ID FROM `glpi_entities_data` WHERE `FK_entities`=".$common_fields["FK_entities"];
      $result = $DB->query($query);
      $data = new EntityData;
      
      //Data do not exist : create new ones
      if (!$DB->numrows($result)) {
         $data->add($fields);         
      }
      //Data exist : update
      else {
      	$fields["ID"] = $DB->numrows($result,0,"ID");
         $data->update($fields);
      }   	
   }  
   	
	regenerateTreeCompleteNameUnderID("glpi_entities", $common_fields["FK_entities"]);	
}

function findTemplate($entity,$table,$value)
{
	global $DB;
	$result = $DB->query("SELECT ID FROM ".$table." WHERE FK_entities=".$entity." AND tplname='".addslashes($value)."' AND is_template=1");
	if ($DB->numrows($result)==1)
		return $DB->result($result,0,"ID");
	else	
		return 0;
}

/**
 * Function found on php.net page about is_float, because is_float doesn't behave correctly
 */
function is_true_float($val){
    //if( is_float($val) || ( (float) $val > (int) $val || strlen($val) != strlen( (int) $val) ) && (int) $val != 0  ) {
    if( is_float($val) || ( (float) $val > (int) $val || strlen($val) != strlen( (int) $val) ) ) {
       return true;
    }
    else {
       return false;
    }
}

function isDocumentAssociatedWithObject($document_id,$device_type,$device_id)
{
	global $DB;
	$query = "SELECT ID FROM glpi_doc_device WHERE FK_doc=$document_id AND FK_device=$device_id AND device_type=$device_type";
	$result = $DB->query($query);
	if ($DB->numrows($result) > 0)
		return true;
	else
		return false;		
}
?>
