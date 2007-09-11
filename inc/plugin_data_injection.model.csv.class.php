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
class DataInjectionModelCSV extends DataInjectionModel {
	var $specific_fields;
	
	function init()
	{
		$this->specific_fields = array();
	}
		
	//---- Load -----//	
	function loadSpecificfields()
	{
		global $DB;
		$sql = "SELECT * FROM glpi_plugin_data_injection_models_csv WHERE model_id = ".$this->fields["ID"];
		$result = $DB->query($sql);
		if ($DB->numrows($result) > 0)
			$this->specific_fields = $DB->fetch_array($result); 
		else
			$this->specific_fields = array();
	}

	//---- Save -----//	
	function updateSpecificFields()
	{
		global $DB;
		
		$sql = "UPDATE glpi_plugin_data_injection_models_csv SET delimiter='".$this->specific_fields["delimiter"]
		."' , header_present=".$this->specific_fields["header_present"]." WHERE model_id=".$this->fields["ID"];
		$DB->query($sql);
	}
	
	
	//---- Save -----//	
	function saveSpecificFields()
	{
		global $DB;
		$sql = "INSERT INTO glpi_plugin_data_injection_models_csv (`delimiter`,`header_present`,`model_id`) VALUES ('".$this->specific_fields["delimiter"]
		."',".$this->specific_fields["header_present"].",".$this->fields["ID"].")";

		$DB->query($sql);
		
		//Reload specific_fields with the ID set
		$this->loadSpecificfields();
	}
	
	
	function deleteSpecificFields()
	{
		global $DB;
		$sql = "DELETE FROM glpi_plugin_data_injection_models_csv WHERE model_id=".$this->fields["ID"];
		$DB->query($sql);
	}
	
	//---- Getters -----//

	function getDelimiter()
	{
		return $this->specific_fields["delimiter"];
	}
		
	function isHeaderPresent()
	{
		return ($this->specific_fields["header_present"]?true:false);
	}
	
	//---- Save -----//
	function setDelimiter($delimiter)
	{
		$this->specific_fields["delimiter"] = $delimiter;
	}	
	
	function setHeaderPresent($present)
	{
		$this->specific_fields["header_present"] = $present;
	}	
	
	function setFields($fields,$entity)
	{
		parent::setFields($fields,$entity);
		if(isset($fields["dropdown_header"]))
			$this->setHeaderPresent($fields["dropdown_header"]);
		
		if(isset($_POST["delimiter"]))
			$this->setDelimiter(stripslashes($fields["delimiter"]));
	}
	
	function showForm()
	{
		global $DATAINJECTIONLANG,$LANG;
		
		echo "<legend>Options CSV</legend>";
		echo "<table class='modelStep_table'>";
		
		/**************************Header******************************/
		echo "<tr><td style='width:160px'>".$DATAINJECTIONLANG["modelStep"][9]."</td>";
		
		if($_SESSION["plugin_data_injection"]["choice"]==1)
			echo "<td style='width:105px'><select name='dropdown_header'>";
		else
			echo "<td style='width:105px'><select name='dropdown_header' style='background-color:#e6e6e6' disabled>";
		
		if(isset($model))
			{
			if($model->isHeaderPresent())
				{
				echo "<option value='1' selected>".$LANG["choice"][1]."</option>";
				echo "<option value='0'>".$LANG["choice"][0]."</option>";
				}
			else
				{
				echo "<option value='1'>".$LANG["choice"][1]."</option>";
				echo "<option value='0' selected>".$LANG["choice"][0]."</option>";	
				}
			}
		else
			{
			echo "<option value='1'>".$LANG["choice"][1]."</option>";
			echo "<option value='0'>".$LANG["choice"][0]."</option>";
			}
		
		echo "</select></td></tr>";
		/**************************************************************/
		
		/************************Delimiter*****************************/
		echo "<tr><td>".$DATAINJECTIONLANG["modelStep"][9]."</td>";
		
		if(isset($model))
			{
			if($_SESSION["plugin_data_injection"]["choice"]==1)
				echo "<td><input type='text' value='".$model->getDelimiter()."' size='1' maxlength='1' name='delimiter' id='delimiter' onfocus=\"this.value=''\" /></td></tr>";
			else
				echo "<td><input type='text' value='".$model->getDelimiter()."' size='1' maxlength='1' name='delimiter' id='delimiter' onfocus=\"this.value=''\" disabled style='font-weight:bold;background-color:#e6e6e6'  /></td></tr>";
			}
		else
			echo "<td><input type='text' value=';' size='1' maxlength='1' name='delimiter' id='delimiter' onfocus=\"this.value=''\" /></td></tr>";
		/**************************************************************/
		
		echo "</table>";
	}
}
?>
