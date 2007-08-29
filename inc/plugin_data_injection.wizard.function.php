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

// ----------------------------------------------------------------------
// Original Author of file: CAILLAUD Xavier
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
	}

function choiceStep($target)
{
	global $DATAINJECTIONLANG;
	
	$models = getAllModels($_SESSION["glpiactive_entity"]);
	
	echo "<form action='".$target."' method='post'>";
	echo "<table class='wizard'>";
	
	/************************Title Step****************************/
	echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][1].$DATAINJECTIONLANG["choiceStep"][1]."</td></tr>";
	/**************************************************************/
	
	echo "<tr><td class='wizard_body'>";
	echo "<div class='wizard_cadre'>";
	
	echo "<table class='choiceStep_table'>";
	
	/***************************Create*****************************/
	echo "<tr>";
	echo "<td style='height: 40px;'><input type='radio' id='checkbox' name='choice' value='1' onClick='showSelect(this.form); deleteOnglet(6);' checked /></td>";
	echo "<td>".$DATAINJECTIONLANG["choiceStep"][2]."</td>";
	echo "</tr>";
	/**************************************************************/
	
	if (count($models)>0) 
		{
		/**************************Update******************************/
		echo "<tr>";
		echo "<td><input type='radio' name='choice' value='2' onClick='showSelect(this.form); deleteOnglet(5);' /></td>";
		echo "<td>".$DATAINJECTIONLANG["choiceStep"][3]."</td>";
		echo "</tr>";
		/**************************************************************/
		
		/**************************Delete******************************/
		echo "<tr>";
		echo "<td><input type='radio' name='choice' value='3' onClick='showSelect(this.form); deleteOnglet(2);' /></td>";
		echo "<td>".$DATAINJECTIONLANG["choiceStep"][4]."</td>";
		echo "</tr>";
		/**************************************************************/
		
		/***************************Using******************************/
		echo "<tr>";
		echo "<td><input type='radio' name='choice' value='4' onClick='showSelect(this.form); deleteOnglet(4);' /></td>";
		echo "<td>".$DATAINJECTIONLANG["choiceStep"][5]."</td>";
		echo "</tr>";
		echo "</table>";
		/**************************************************************/
		
		/************************Select Model**************************/
		echo "<div>";
		echo "<select disabled name='dropdown'>";
	
		foreach($models as $model)
			echo "<option value='".$model->getModelID()."'>".$model->getModelName()." / ".getDropdownName('glpi_plugin_data_injection_filetype',$model->getModelType())."</option>";
	
		echo "</select>";
		echo "</div>";
		/**************************************************************/
		}
		
	else 
		echo"</table>";

	echo "</div>";
	echo "</td></tr>";
	
	/**************************Button******************************/
	echo "<tr><td class='wizard_button'>";
	echo "<div class='next'>";
	echo "<input type='submit' name='next_choiceStep' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' />";
	echo "</div>";
	echo "</td></tr>";
	/**************************************************************/
	
	echo "</table>";
	echo "</form>";
}

function modelStep($target)
{
	global $DATAINJECTIONLANG,$LANG;
	
	if(isset($_SESSION["plugin_data_injection"]["model"]))
		$model = unserialize($_SESSION["plugin_data_injection"]["model"]);
	
	echo "<form action='".$target."' method='post'>";
	echo "<table class='wizard'>";
	
	/************************Title Step****************************/
	if($_SESSION["plugin_data_injection"]["choice"]==1)
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][2].$DATAINJECTIONLANG["modelStep"][1]."</td></tr>";
	else
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][2].$DATAINJECTIONLANG["modelStep"][2]."</td></tr>";
	/**************************************************************/
	
	echo "<tr><td class='wizard_body'>";
	echo "<div class='wizard_cadre'>";
	
	echo "<table class='modelStep_table'>";
	
	/***********************Device Type****************************/
	echo "<tr><td>".$DATAINJECTIONLANG["modelStep"][3]."</td>";
	
	if($_SESSION["plugin_data_injection"]["choice"]==1)
		echo "<td><select name='dropdown_device_type'>";
	else
		echo "<td><select name='dropdown_device_type' disabled>";
	
	$types = getAllPrimaryTypes();
		
	foreach($types as $type)
		{
		if(isset($model))
			{
			if($model->getDeviceType() == $type[0])
				echo "<option value='".$type[0]."' selected>".$type[1]."</option>";
			else
				echo "<option value='".$type[0]."'>".$type[1]."</option>";
			}
		else
			echo "<option value='".$type[0]."'>".$type[1]."</option>";
		}
	
	echo "</select></td></tr>";
	/**************************************************************/
	
	/**************************Type********************************/
	echo "<tr><td>".$DATAINJECTIONLANG["modelStep"][4]."</td>";
	
	if($_SESSION["plugin_data_injection"]["choice"]==1)
		echo "<td><select name='dropdown_type'>";
	else
		echo "<td><select name='dropdown_type' disabled>";
		
	$types = getAllTypes();

	foreach($types as $type)
		{
		if(isset($model))
			{
			if($model->getModelType() == $type->getBackendID())
				echo "<option value='".$type->getBackendID()."' selected>".$type->getBackendName()."</option>";
			else
				echo "<option value='".$type->getBackendID()."'>".$type->getBackendName()."</option>";
			}
		else
			echo "<option value='".$type->getBackendID()."'>".$type->getBackendName()."</option>";
		}
		
	echo "</select></td></tr>";
	/**************************************************************/
	
	/***********************Behavior add***************************/
	echo "<tr><td>".$DATAINJECTIONLANG["modelStep"][5]."</td>";
	
	echo "<td><select name='dropdown_create'>";
	
	if(isset($model))
		{
		if($model->getBehaviorAdd())
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
	
	/**********************Behavior update*************************/
	echo "<tr><td>".$DATAINJECTIONLANG["modelStep"][6]."</td>";
	
	echo "<td><select name='dropdown_update'>";
	
	if(isset($model))
		{
		if($model->getBehaviorUpdate())
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
		echo "<option value='0'>".$LANG["choice"][0]."</option>";
		echo "<option value='1'>".$LANG["choice"][1]."</option>";
		}
	
	echo "</select></td></tr>";
	/**************************************************************/
	
	/**************************Header******************************/
	echo "<tr><td>".$DATAINJECTIONLANG["modelStep"][7]."</td>";
	
	if($_SESSION["plugin_data_injection"]["choice"]==1)
		echo "<td><select name='dropdown_header'>";
	else
		echo "<td><select name='dropdown_header' disabled>";
	
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
	echo "<tr><td>".$DATAINJECTIONLANG["modelStep"][8]."</td>";
	
	if(isset($model))
		{
		if($_SESSION["plugin_data_injection"]["choice"]==1)
			echo "<td><input type='text' value='".$model->getDelimiter()."' size='1' maxlength='1' name='delimiter' id='delimiter' onfocus=\"this.value=''\" /></td></tr>";
		else
			echo "<td><input type='text' value='".$model->getDelimiter()."' size='1' maxlength='1' name='delimiter' id='delimiter' onfocus=\"this.value=''\" disabled style='font-weight:bold;background-color:#d4d0c8'  /></td></tr>";
		}
	else
		echo "<td><input type='text' value=';' size='1' maxlength='1' name='delimiter' id='delimiter' onfocus=\"this.value=''\" /></td></tr>";
	/**************************************************************/
	
	echo "</table>";
	echo "</div>";
	
	/*********************Delimiter Error**************************/
	echo "<div id='delimiter_error' class='delimiter' >".$DATAINJECTIONLANG["modelStep"][9]."</div>";
	/**************************************************************/
	
	echo "</td></tr>";
	
	/**************************Button******************************/
	echo "<tr><td class='wizard_button'>";
	echo "<div class='preview'>";
	echo "<input type='submit' name='preview_modelStep' value='".$DATAINJECTIONLANG["button"][1]."' class='submit' />";
	echo "</div>";
	
	echo "<div class='next'>";
	echo "<input type='submit' name='next_modelStep' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' onclick='return verif_delimiter()' />";
	echo "</div>";
	echo "</td></tr>";
	/**************************************************************/
	
	echo "</table>";
	echo "</form>";
}

function deleteStep($target,$suppr)
{
	global $DATAINJECTIONLANG,$LANG;
	
	$model = new DataInjectionModel();
		
	$model->getFromDB($_SESSION["plugin_data_injection"]["idmodel"]);
	
	$name = $model->getModelName();
	
	echo "<form action='".$target."' method='post'>";
	echo "<table class='wizard'>";
	
	/************************Title Step****************************/
	echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][2].$DATAINJECTIONLANG["deleteStep"][1]."</td></tr>";
	/**************************************************************/
	
	echo "<tr><td class='wizard_body'>";
	echo "<div class='wizard_cadre'>";
	
	/**********************Confirm delete**************************/
	if($suppr)
		{
		if($model->deleteModel())
			echo "<div class='save_delete'>".$DATAINJECTIONLANG["deleteStep"][4]." \" ".$name." \" ".$DATAINJECTIONLANG["deleteStep"][5]."</div>";
		else
			echo "<div class='save_delete'>".$DATAINJECTIONLANG["deleteStep"][6]."</div>";
		}
	else
		{
		echo "<table class='deleteStep_table'>";
		echo "<tr>";
		echo "<td colspan='2' class='question'>".$DATAINJECTIONLANG["deleteStep"][2]."<br />\" ".$name." \"<br />".$DATAINJECTIONLANG["deleteStep"][3]."</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td><input type='submit' name='yes_deleteStep' value='".$LANG["choice"][1]."' class='submit' /></td>";
		echo "<td><input type='submit' name='no_deleteStep' value='".$LANG["choice"][0]."' class='submit' /></td>";
		echo "</tr>";
		echo "</table>";
		}
	/**************************************************************/
	
	echo "<div>";
	echo "</td></tr>";
	
	/**************************Button******************************/
	echo "<tr><td class='wizard_button'>";
	if($suppr)
		{
		echo "<div class='next'>";
		echo "<input type='submit' name='next_deleteStep' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' />";
		echo "</div>";
		}
	echo "</td></tr>";
	/**************************************************************/
	
	echo "</table>";
	echo "</form>";
}

function fileStep($target,$error)
{
	global $DATAINJECTIONLANG;
	
	echo "<form action='".$target."' method='post' enctype='multipart/form-data'>";
	echo "<table class='wizard'>";
	
	/************************Title Step****************************/
	if($_SESSION["plugin_data_injection"]["choice"]==1)
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][3].$DATAINJECTIONLANG["fileStep"][1]."</td></tr>";
	else
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][2].$DATAINJECTIONLANG["fileStep"][1]."</td></tr>";
	/**************************************************************/
		
	echo "<tr><td class='wizard_body'>";
	echo "<div class='wizard_cadre'>";
	
	/************************Select File***************************/
	echo "<table class='fileStep_table'>";
	echo "<tr>";
	echo "<td>".$DATAINJECTIONLANG["fileStep"][2]."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><input type='file' name='file' /></td>";
	echo "</tr>";
	echo "</table>";
	/**************************************************************/
	
	/*************************File Error***************************/
	if($error!="")
		echo "<div class='red'>".$error."</div>";
	/**************************************************************/
	
	echo "</div>";
	echo "</td></tr>";
	
	/**************************Button******************************/
	echo "<tr><td class='wizard_button'>";
	echo "<div class='preview'>";
	echo "<input type='submit' name='preview_fileStep' value='".$DATAINJECTIONLANG["button"][1]."' class='submit' />";
	echo "</div>";
	
	echo "<div class='next'>";
	echo "<input type='submit' name='next_fileStep' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' />";
	echo "</div>";
	echo "</td></tr>";
	/**************************************************************/
	
	echo "</table>";
	echo "</form>";
}

function mappingStep($target)
{
	global $DATAINJECTIONLANG;
	
	$model = unserialize($_SESSION["plugin_data_injection"]["model"]);
	
	/***********************Read File******************************/
	if($_SESSION["plugin_data_injection"]["choice"]==1)
		{
		$file=getBackend($model->getModelType());
		$file->initBackend(PLUGIN_DATA_INJECTION_UPLOAD_DIR.$_SESSION["plugin_data_injection"]["file"],$model->getDelimiter());
		$file->read();
	
		$nbline = $file->getNumberOfLine();
	
		if($model->isHeaderPresent())
			$nbline--;
		
		$header = $file->getHeader($model->isHeaderPresent());
		$num = count($header);
		}
	else
		$num = count($model->getMappings()->getAllMappings());
	/**************************************************************/
	
	echo "<form action='".$target."' method='post'>";
	echo "<table class='wizard'>";
	
	/************************Title Step****************************/
	if($_SESSION["plugin_data_injection"]["choice"]==1)
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][4]." ".$num." ".$DATAINJECTIONLANG["mappingStep"][1]."</td></tr>";
	else
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][3]." ".$num." ".$DATAINJECTIONLANG["mappingStep"][1]."</td></tr>";
	/**************************************************************/
	
	echo "<tr><td class='wizard_body' valign='top'>";
	echo "<table style='margin-top:30px'>";
	
	/***********************Header Table***************************/
	echo "<tr style='text-align:center'>";
	echo "<th>".$DATAINJECTIONLANG["mappingStep"][2]."</th>";
	echo "<th></th>";
	echo "<th>".$DATAINJECTIONLANG["mappingStep"][3]."</th>";
	echo "<th>".$DATAINJECTIONLANG["mappingStep"][4]."</th>";
	echo "<th>".$DATAINJECTIONLANG["mappingStep"][5]."</th>";
	echo "</tr>";
	/**************************************************************/
	
	if($_SESSION["plugin_data_injection"]["choice"]==2 || $_SESSION["plugin_data_injection"]["remember"]>=1)
		foreach($model->mappings as $mapping)
			foreach($mapping as $key => $value)
				{
				echo "<tr>";
				
				/************************Header File***************************/
				echo "<td><input type='hidden' name='field[$key][0]' value='".$value->getName()."' />".$value->getName()." : </td>";
				/**************************************************************/
				
				/***********************Arrow Picture**************************/
				echo "<td style='text-align:center;width:75px'><img src='../pics/fleche.png' alt='fleche' /></td>";
				/**************************************************************/
				
				/************************Select table**************************/
				echo "<td>";
				echo "<select name='field[$key][1]' id='table$key' onchange='go_mapping($key)' style='width: 150px'>";
		
				$types = getAllMappingsDefinitionsTypes();
		
				echo "<option value='-1'>".$DATAINJECTIONLANG["mappingStep"][6]."</option>";
		
				foreach($types as $type)
					{
					if($value->getMappingType() == $type[0])
						echo "<option value='".$type[0]."' selected>".$type[1]."</option>";
					else
						echo "<option value='".$type[0]."'>".$type[1]."</option>";
					}
			
				echo "</select>";
				echo "</td>";
				/**************************************************************/
				
				/************************Select field**************************/
				echo "<td id='field$key'>";
				echo "<select name='field[$key][2]' style='width: 150px'>";
		
				if($value->getMappingType()==-1)
					echo "<option value='-1'>".$DATAINJECTIONLANG["mappingStep"][7]."</option>";
				else
					{
					$values = getAllMappingsDefinitionsByType($value->getMappingType());

					foreach($values as $key2 => $value2)
						{
						if($value->getValue()==$key2)
							echo "<option value='".$key2."' selected>".$value2."</option>";
						else
							echo "<option value='".$key2."'>".$value2."</option>";
						}
					}
		
				echo "</select>";
				echo "</td>";
				/**************************************************************/
		
				/*********************Checkbox Mandatory***********************/
				if($value->getMappingType()==-1)
					echo "<td><input type='checkbox' name='field[$key][3]' id='check$key' style='text-align:center;visibility:hidden' /></td>";
				else
					{
					if($value->isMandatory())	
						echo "<td><input type='checkbox' name='field[$key][3]' id='check$key' style='text-align:center' checked /></td>";
					else
						echo "<td><input type='checkbox' name='field[$key][3]' id='check$key' style='text-align:center' /></td>";
					}
				/**************************************************************/
				
				echo "</tr>";
				}
	else
		foreach($header as $key => $value)
			{
			echo "<tr>";
			
			/************************Header File***************************/
			echo "<td><input type='hidden' name='field[$key][0]' value='$value' />".$value." : </td>";
			/**************************************************************/
			
			/***********************Arrow Picture**************************/
			echo "<td style='text-align:center;width:75px'><img src='../pics/fleche.png' alt='fleche' /></td>";
			/**************************************************************/
			
			/************************Select table**************************/
			echo "<td>";
			echo "<select name='field[$key][1]' id='table$key' onchange='go_mapping($key)' style='width: 150px'>";
			
			$types = getAllMappingsDefinitionsTypes();
			
			echo "<option value='-1'>".$DATAINJECTIONLANG["mappingStep"][6]."</option>";
			
			foreach($types as $type)
				echo "<option value='".$type[0]."'>".$type[1]."</option>";
				
			echo "</select>";
			echo "</td>";
			/**************************************************************/
			
			/************************Select field**************************/
			echo "<td id='field$key'>";
			echo "<select name='field[$key][2]' style='width: 150px'>";
			echo "<option value='-1'>".$DATAINJECTIONLANG["mappingStep"][7]."</option>";
			echo "</select>";
			echo "</td>";
			/**************************************************************/
			
			/*********************Checkbox Mandatory***********************/
			echo "<td style='text-align:center'>";
			echo "<input type='checkbox' name='field[$key][3]' id='check$key' style='text-align:center;visibility:hidden' />";
			echo "</td>";
			/**************************************************************/
			
			echo "</tr>";
			}
	
	/***********************Mandatory Error************************/
	echo "<tr>";
	echo "<td class='mandatory' id='mandatory_error' colspan='5'>".$DATAINJECTIONLANG["mappingStep"][8]."</td>";
	echo "</tr>";
	/**************************************************************/
	
	/*************************File View****************************/
	if($_SESSION["plugin_data_injection"]["choice"]==1)
		{
		echo "<tr><td colspan='5'>";
		echo "<form action='plugin_data_injection.popup.php' method='post' id='popup'>";
		echo "<table style='margin-top: 10px'>";
		echo "<tr><td style='text-align:center'>";
		echo "Nbr de ligne : <input type='text' id='nbline' name='nbline' size='2' maxlength='3' value='1' onfocus=\"this.value=''\" /> / ".$nbline;
		echo "</td></tr>";
		echo "<tr><td style='text-align:center'>";
		echo "<input type='button' name='valid_popup' value='".$DATAINJECTIONLANG["button"][3]."' class='submit' onclick='popup($nbline)' />";
		echo "</td></tr>";
		echo "</table>";
		echo "</form>";
		echo "</td></tr>";
		}
	/**************************************************************/
	
	echo "</table>";
	echo "</td></tr>";
	
	/**************************Button******************************/
	echo "<tr><td class='wizard_button'>";
	echo "<div class='preview'>";
	echo "<input type='submit' name='preview_mappingStep' value='".$DATAINJECTIONLANG["button"][1]."' class='submit' />";
	echo "</div>";
	
	echo "<div class='next'>";
	echo "<input type='submit' name='next_mappingStep' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' onclick='return verif_mandatory($num)' />";
	echo "</div>";
	echo "</td></tr>";
	/**************************************************************/
	
	echo "</table>";
	echo "</form>";
}	

function infoStep($target)
{
	global $DATAINJECTIONLANG;
	
	$model = unserialize($_SESSION["plugin_data_injection"]["model"]);
	
	echo "<form action='".$target."' method='post'>";
	echo "<table class='wizard'>";
	
	/************************Title Step****************************/
	if($_SESSION["plugin_data_injection"]["choice"]==1)
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][5].$DATAINJECTIONLANG["infoStep"][1]."</td></tr>";
	else
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][4].$DATAINJECTIONLANG["infoStep"][2]."</td></tr>";
	/**************************************************************/
	
	echo "<tr><td class='wizard_body' valign='top'>";
	echo "<table style='margin-top:30px'>";
	
	/***********************Header Table***************************/
	echo "<tr style='text-align:center'>";
	echo "<th style='width:150px;'>".$DATAINJECTIONLANG["mappingStep"][3]."</th>";
	echo "<th style='width:150px;'>".$DATAINJECTIONLANG["mappingStep"][4]."</th>";
	echo "<th >".$DATAINJECTIONLANG["mappingStep"][5]."</th>";
	echo "</tr>";
	/**************************************************************/
	
	echo "</table>";
	
	$nbline = 0;
	
	if($_SESSION["plugin_data_injection"]["choice"]==2 || $_SESSION["plugin_data_injection"]["remember"]==2)
		foreach($model->infos as $info)
			{
			$nbline = count($info);
			
			if($nbline>0)
				{
				foreach($info as $indice => $value)
					{
					$key=$indice+1;
					
					echo "<div id='select$key'>";
					echo "<table id='tab$key'>";
					
					/***********************Already Change*************************/
					echo "<tr>";
					if($value->getInfosType()!=-1)
						echo "<td><input type='hidden' id='add$key' value='1'></td></td>";
					else
						echo "<td><input type='hidden' id='add$key' value='0'></td></td>";
					/**************************************************************/
					
					/*************************Select table*************************/
					echo "<td>";
					echo "<select name='field[$key][0]' id='table$key' onchange='go_info($key);addelete_info($key)' style='width: 150px'>";
					
					$types = getAllMappingsDefinitionsTypes();
					
					echo "<option value='-1'>".$DATAINJECTIONLANG["mappingStep"][6]."</option>";
						
					foreach($types as $type)
						if($value->getInfosType() == $type[0])
							echo "<option value='".$type[0]."' selected>".$type[1]."</option>";
						else
							echo "<option value='".$type[0]."'>".$type[1]."</option>";
						
					echo "</select>";
					echo "</td>";
					/**************************************************************/
					
					/************************Select field**************************/
					echo "<td id='field$key'>";
					echo "<select name='field[$key][1]' style='width: 150px'>";
			
					if($value->getInfosType()==-1)
						echo "<option value='-1'>".$DATAINJECTIONLANG["mappingStep"][7]."</option>";
					else
						{
						$values = getAllMappingsDefinitionsByType($value->getInfosType());
	
						foreach($values as $key2 => $value2)
							if($value->getValue()==$key2)
								echo "<option value='".$key2."' selected>".$value2."</option>";
							else
								echo "<option value='".$key2."'>".$value2."</option>";
						}
			
					echo "</select>";
					echo "</td>";
					/**************************************************************/
					
					/*********************Checkbox Mandatory***********************/
					if($value->getInfosType()==-1)
						echo "<td style='text-align:center'><input type='checkbox' name='field[$key][2]' id='check$key' style='text-align:center;width:60px;visibility:hidden' /></td>";
					else
						{
						if($value->isMandatory())	
							echo "<td style='text-align:center'><input type='checkbox' name='field[$key][2]' id='check$key' style='text-align:center;width:60px' checked /></td>";
						else
							echo "<td style='text-align:center'><input type='checkbox' name='field[$key][2]' id='check$key' style='text-align:center;width:60px' /></td>";
						}
					/**************************************************************/
					
					echo "</tr>";
					echo "</table>";
					echo "</div>";
					}
				
				/*********************************Add a blank line*********************************/
				$key++;
				
				echo "<div id='select$key'>";
				echo "<table id='tab$key'>";
				
				echo "<tr>";
				
				/***********************Already Change*************************/
				echo "<td><input type='hidden' id='add$key' value='0'></td>";
				/**************************************************************/
				
				/*************************Select table*************************/
				echo "<td>";
				echo "<select name='field[$key][0]' id='table$key' onchange='go_info($key);addelete_info($key)' style='width: 150px'>";
				
				$types = getAllMappingsDefinitionsTypes();
				
				echo "<option value='-1'>".$DATAINJECTIONLANG["mappingStep"][6]."</option>";
					
				foreach($types as $type)
					echo "<option value='".$type[0]."'>".$type[1]."</option>";
					
				echo "</select>";
				echo "</td>";
				/**************************************************************/
				
				/************************Select field**************************/
				echo "<td id='field$key'>";
				echo "<select name='field[$key][1]' style='width: 150px'>";
				echo "<option value='-1'>".$DATAINJECTIONLANG["mappingStep"][7]."</option>";
				echo "</select>";
				echo "</td>";
				/**************************************************************/
				
				/*********************Checkbox Mandatory***********************/
				echo "<td><input type='checkbox' name='field[$key][2]' id='check$key' style='text-align:center;width:60px;visibility:hidden' /></td>";
				/**************************************************************/
				
				echo "</tr>";
				echo "</table>";
				echo "</div>";
				
				$key++;
				
				echo "<div id='select$key'>";
				echo "</div>";
				/**********************************************************************************/
				}
			}
				
	if($_SESSION["plugin_data_injection"]["remember"]==1 || $nbline==0)
		{
		$key = 1;
	
		echo "<div id='select$key'>";
		echo "<table id='tab$key'>";
		
		echo "<tr>";
		
		/***********************Already Change*************************/
		echo "<td><input type='hidden' id='add$key' value='0'></td>";
		/**************************************************************/
		
		/*************************Select table*************************/
		echo "<td>";
		echo "<select name='field[$key][0]' id='table$key' onchange='go_info($key);addelete_info($key)' style='width: 150px'>";
			
		$types = getAllMappingsDefinitionsTypes();
			
		echo "<option value='-1'>".$DATAINJECTIONLANG["mappingStep"][6]."</option>";
			
		foreach($types as $type)
			echo "<option value='".$type[0]."'>".$type[1]."</option>";
			
		echo "</select>";
		echo "</td>";
		/**************************************************************/
		
		/************************Select field**************************/
		echo "<td id='field$key'>";
		echo "<select name='field[$key][1]' style='width: 150px'>";
		echo "<option value='-1'>".$DATAINJECTIONLANG["mappingStep"][7]."</option>";
		echo "</select>";
		echo "</td>";
		/**************************************************************/
		
		/*********************Checkbox Mandatory***********************/
		echo "<td><input type='checkbox' name='field[$key][2]' id='check$key' style='text-align:center;width:60px;visibility:hidden' /></td>";
		/**************************************************************/
		
		echo "</tr>";
		echo "</table>";
		echo "</div>";
		
		$key++;
		
		echo "<div id='select$key'>";
		echo "</div>";
		}
			
	echo "</td></tr>";
	
	/**************************Button******************************/
	echo "<tr><td class='wizard_button'>";
	echo "<div class='preview'>";
	echo "<input type='submit' name='preview_infoStep' value='".$DATAINJECTIONLANG["button"][1]."' class='submit' />";
	echo "</div>";
	
	echo "<div class='next'>";
	echo "<input type='submit' name='next_infoStep' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' />";
	echo "</div>";
	echo "</td></tr>";
	/**************************************************************/
	
	echo "</table>";
	echo "</form>";
}

function saveStep($target,$save)
{
	global $DATAINJECTIONLANG,$LANG;
	
	echo "<form action='".$target."' method='post'>";
	echo "<table class='wizard'>";
	
	/************************Title Step****************************/
	if($_SESSION["plugin_data_injection"]["choice"]==1)
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][6].$DATAINJECTIONLANG["saveStep"][1]."</td></tr>";
	else
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][5].$DATAINJECTIONLANG["saveStep"][1]."</td></tr>";
	/**************************************************************/
	
	echo "<tr><td class='wizard_body'>";
	echo "<div class='wizard_cadre'>";
	
	switch($save)
	{
		case 0:
			/***********************Asking View****************************/
			echo "<table class='step15_table'>";
			
			if($_SESSION["plugin_data_injection"]["choice"]==1)
				echo "<tr><td colspan='2'>".$DATAINJECTIONLANG["saveStep"][2]."</td><tr>";
			else
				echo "<tr><td colspan='2'>".$DATAINJECTIONLANG["saveStep"][3]."</td><tr>";
				
			echo "<tr><td><input type='submit' name='yes1_saveStep' value='".$LANG["choice"][1]."' class='submit' /></td>";
			echo "<td><input type='submit' name='no1_saveStep' value='".$LANG["choice"][0]."' class='submit' /></td></tr>";
			echo "</table>";
			/**************************************************************/
		break;
		case 1:
			/************************Fill View*****************************/
			echo "<table class='step15_1_table'>";
			echo "<tr><td>".$DATAINJECTIONLANG["saveStep"][4]."</td></tr>";
			
			if($_SESSION["plugin_data_injection"]["choice"]==1)
				{
				echo "<tr><td><input type='text' name='model_name' size='35' /></td></tr>";
				echo "<tr><td>".$DATAINJECTIONLANG["saveStep"][5]."</td></tr>";
				echo "<tr><td><textarea name='comments' rows='4' cols='25'></textarea></td></tr>";
				}
			else
				{
				$model = unserialize($_SESSION["plugin_data_injection_model"]);
				echo "<tr><td><input type='text' name='model_name' size='35' value='".$model->getModelName()."' /></td></tr>";
				echo "<tr><td>".$DATAINJECTIONLANG["saveStep"][5]."</td></tr>";
				echo "<tr><td><textarea name='comments' rows='4' cols='25'>".$model->getModelComments()."</textarea></td></tr>";
				}
			echo "</table>";
			/**************************************************************/
		break;
		case 2:	
			/**********************No Save View****************************/
			echo "<table class='step15_table'>";
			
			if($_SESSION["plugin_data_injection"]["choice"]==1)
				echo "<tr><td colspan='2'>".$DATAINJECTIONLANG["saveStep"][6]."</td><tr>";
			else
				echo "<tr><td colspan='2'>".$DATAINJECTIONLANG["saveStep"][7]."</td><tr>";
				
			echo "<tr><td colspan='2'>".$DATAINJECTIONLANG["saveStep"][10]."</td><tr>";
			
			echo "<tr><td><input type='submit' name='yes2_saveStep' value='".$LANG["choice"][1]."' class='submit' /></td>";
			echo "<td><input type='submit' name='no2_saveStep' value='".$LANG["choice"][0]."' class='submit' /></td></tr>";
			echo "</table>";
			/**************************************************************/	
		break;
		case 3:		
			/************************Save View*****************************/
			echo "<table class='step15_table'>";
			
			if($_SESSION["plugin_data_injection"]["choice"]==1)
				echo "<tr><td colspan='2'>".$DATAINJECTIONLANG["saveStep"][8]."</td><tr>";
			else
				echo "<tr><td colspan='2'>".$DATAINJECTIONLANG["saveStep"][9]."</td><tr>";
				
			echo "<tr><td colspan='2'>".$DATAINJECTIONLANG["saveStep"][10]."</td><tr>";
			
			echo "<tr><td><input type='submit' name='yes2_saveStep' value='".$LANG["choice"][1]."' class='submit' /></td>";
			echo "<td><input type='submit' name='no2_saveStep' value='".$LANG["choice"][0]."' class='submit' /></td></tr>";
			echo "</table>";
			/**************************************************************/	
		break;
	}
	
	echo "<div>";
	echo "</td></tr>";
	
	/**************************Button******************************/
	echo "<tr><td class='wizard_button'>";
	
	switch($save)
	{
		case 0:
			echo "<div class='preview'>";
			echo "<input type='submit' name='preview_saveStep' value='".$DATAINJECTIONLANG["button"][1]."' class='submit' />";
			echo "</div>";
		break;
		case 1:
			echo "<div class='next'>";
			echo "<input type='submit' name='next_saveStep' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' />";
			echo "</div>";
		break;
	}

	echo "</td></tr>";
	/**************************************************************/
	
	echo "</table>";
	echo "</form>";
	
	echo "<script type='text/javascript'>document.forms['step15'].model_name.focus()</script>";
}



function fillInfoStep($target)
{
	global $DATAINJECTIONLANG;
	
	echo "<form action='".$target."' method='post' name='step8' enctype='multipart/form-data'>";
	echo "<table class='wizard'>";
	
	/************************Title Step****************************/
	echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["fillInfoStep"][1]."</td></tr>";
	/**************************************************************/
	
	echo "<tr><td class='wizard_body'>";
	echo "<div class='wizard_cadre'>";

	
	
	echo "<div>";
	echo "</td></tr>";
	
	echo "<tr><td class='wizard_button'>";

	echo "<div class='preview'>";
	echo "<input type='submit' name='preview8' value='".$DATAINJECTIONLANG["button"][1]."' class='submit' />";
	echo "</div>";
	
	echo "<div class='next'>";
	echo "<input type='submit' name='next8' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' />";
	echo "</div>";
	echo "</td></tr></table>";
	echo "</form>";
}

function initSession()
{
if(isset($_SESSION["plugin_data_injection"]["nbonglet"]))
	unset($_SESSION["plugin_data_injection"]["nbonglet"]);

if(isset($_SESSION["plugin_data_injection"]["step"]))
	unset($_SESSION["plugin_data_injection"]["step"]);
	
if(isset($_SESSION["plugin_data_injection"]["choice"]))
	unset($_SESSION["plugin_data_injection"]["choice"]);
	
if(isset($_SESSION["plugin_data_injection"]["idmodel"]))
	unset($_SESSION["plugin_data_injection"]["idmodel"]);
	
if(isset($_SESSION["plugin_data_injection"]["model"]))
	unset($_SESSION["plugin_data_injection"]["model"]);

if(isset($_SESSION["plugin_data_injection"]["file"]))
	unset($_SESSION["plugin_data_injection"]["file"]);

if(isset($_SESSION["plugin_data_injection"]["remember"]))
	unset($_SESSION["plugin_data_injection"]["remember"]);		
	
$_SESSION["plugin_data_injection"]["step"] = 1;
$_SESSION["plugin_data_injection"]["choice"] = 1;
$_SESSION["plugin_data_injection"]["nbonglet"] = 6;
}

?>
