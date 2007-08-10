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
class DataInjectionMapping extends CommonDBTM {
	
	function DataInjectionMapping()
	{
		$this->table="glpi_plugin_data_injection_mappings";
    	$this->type=-1;
	}
}

class MappingCollection {
	
	var $mappingCollection;
	
	function MappingCollection()
	{
		$mappingCollection = array();
	}
	
	function getAllMappingsByModelID($model_id)
	{
		global $DB;
		
		$sql = "SELECT * FROM glpi_plugin_data_injection_mappings WHERE model_id=".$model_id." ORDER BY rank ASC";
		$result = $DB->query($sql);
		while  ($data = $DB->fetch_array($result))
		{
			$mapping = new DataInjectionMapping;
			$mapping->fields = $data;
			$this->mappingCollection[] = $mapping;
		}
	}
	
	function getAllMappings()
	{
		return $this->mappingCollection;
	}
	
	function saveAllMappings()
	{
		$tmp = new DataInjectionMapping;
		
		foreach ($this->mappingCollection as $mapping)
		{
			if (isset($mapping->fields["ID"]))
				$mapping->update($mapping->fields);
			else
				$mapping->fields["ID"] = $mapping->add($mapping->fields);
		}
	}
	
	function addNewMapping($mapping)
	{
		$this->mappingCollection[] = $mapping;
	}
	
	function replaceMappings($mappings)
	{
		$this->mappingCollection = $mappings;
	}
}
?>
