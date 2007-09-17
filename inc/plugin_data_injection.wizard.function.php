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
// Original Author of file: Dévi Balpe
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
	}

function choiceStep($target)
{
	global $DATAINJECTIONLANG;
	
	$models = getAllModels($_SESSION["glpiactive_entity"]);
	
	$nbmodel = count($models);
	
	echo "<form action='".$target."' method='post'>";
	echo "<table class='wizard'>";
	
	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";
	
	/************************Title Step****************************/
	echo "<div class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][1].$DATAINJECTIONLANG["choiceStep"][1]."</div>";
	/**************************************************************/
	
	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>".$DATAINJECTIONLANG["choiceStep"][2]."</div>";
	/**************************************************************/
	
	echo "</td>";
	
	echo "<td class='wizard_right_area' style='width:400px' valign='top'>";
	echo "<fieldset class='choiceStep_selection'>";
	echo "<legend>".$DATAINJECTIONLANG["choiceStep"][9]."</legend>";
	echo "<table class='choiceStep_table'>";

	/***************************Create*****************************/
	echo "<tr>";
	if (plugin_data_injection_haveRight("create_model","w"))
		echo "<td style='height: 40px;'><input type='radio' id='create' name='choice' value='1' onClick='show_Select($nbmodel);deleteOnglet(6)' checked /></td>";
	else
		echo "<td style='height: 40px;'><input type='radio' id='create' name='choice' value='1' onClick='show_Select($nbmodel);deleteOnglet(6)' disabled /></td>";
	echo "<td>".$DATAINJECTIONLANG["choiceStep"][3]."</td>";
	echo "</tr>";
	/**************************************************************/
			
	if ($nbmodel>0) 
		{
		/**************************Update******************************/
		echo "<tr>";
		if (plugin_data_injection_haveRight("create_model","w"))
			echo "<td><input type='radio' name='choice' value='2' onClick='show_Select($nbmodel);deleteOnglet(5)' /></td>";
		else
			echo "<td><input type='radio' name='choice' value='2' onClick='show_Select($nbmodel);deleteOnglet(5)' disabled /></td>";
		echo "<td>".$DATAINJECTIONLANG["choiceStep"][4]."</td>";
		echo "</tr>";
		/**************************************************************/		

		/**************************Delete******************************/
		echo "<tr>";
		if (plugin_data_injection_haveRight("create_model","w"))
			echo "<td><input type='radio' name='choice' value='3' onClick='show_Select($nbmodel);deleteOnglet(2)' /></td>";
		else
			echo "<td><input type='radio' name='choice' value='3' onClick='show_Select($nbmodel);deleteOnglet(2)' disabled /></td>";
		echo "<td>".$DATAINJECTIONLANG["choiceStep"][5]."</td>";
		echo "</tr>";
		/**************************************************************/

		/**************************Using*******************************/
		echo "<tr>";
		if (plugin_data_injection_haveRight("use_model","r"))
			if($_SESSION["plugin_data_injection"]["choice"] == 4)
				echo "<td><input type='radio' name='choice' value='4' onClick='show_Select($nbmodel);deleteOnglet(5)' checked /></td>";
			else
				echo "<td><input type='radio' name='choice' value='4' onClick='show_Select($nbmodel);deleteOnglet(5)' /></td>";
		else
			echo "<td><input type='radio' name='choice' value='4' onClick='show_Select($nbmodel);deleteOnglet(5)' disabled /></td>";
		echo "<td>".$DATAINJECTIONLANG["choiceStep"][6]."</td>";
		echo "</tr>";
		echo "</table>";
		/**************************************************************/

		/************************Select Model**************************/
		echo "<div class='choiceStep_dropdown'>";
		
		if (plugin_data_injection_haveRight("create_model","w"))	
			echo "<select style='background-color:#e6e6e6' disabled name='dropdown' id='dropdown' onchange='show_comments($nbmodel)'>";
		else			
			echo "<select name='dropdown' id='dropdown' onchange='show_comments($nbmodel)'>";
			
		foreach($models as $model)
			echo "<option value='".$model->getModelID()."'>".$model->getModelName()." / ".getDropdownName('glpi_plugin_data_injection_filetype',$model->getModelType())."</option>";
	
		echo "</select>";
		echo "</div>";
		echo "</fieldset>";
		/**************************************************************/
		
		/***********************Comments Model*************************/
		echo "<div id='test'>";
		
		foreach($models as $key => $model)
			{
			$comment = $model->getModelComments();
			
			echo "<fieldset class='choiceStep_comments' id='comments".$key."'>";
			echo "<legend>".$DATAINJECTIONLANG["choiceStep"][7]."</legend>";
			if(!empty($comment))
				echo $comment;
			else
				echo $DATAINJECTIONLANG["choiceStep"][8];
			echo "</fieldset>";
			}
			
		echo "</div>";
		/**************************************************************/
		}
		
	else 
		{
		echo"</table>";
		echo "</fieldset>";
		}
	
	if (!plugin_data_injection_haveRight("create_model","w"))
		echo "<script type='text/javascript'>show_comments($nbmodel)</script>";
	
	echo "</td></tr>";
	
	/**************************Button******************************/
	echo "<tr><td class='wizard_button' colspan='2'>";
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
	
	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";
	
	/************************Title Step****************************/
	if($_SESSION["plugin_data_injection"]["choice"]==1)
		echo "<div class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][2].$DATAINJECTIONLANG["modelStep"][1]."</div>";
	else
		echo "<div class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][2].$DATAINJECTIONLANG["modelStep"][2]."</div>";
	/**************************************************************/
	
	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>".$DATAINJECTIONLANG["modelStep"][3]."</div>";
	/**************************************************************/
	
	echo "</td>";
	
	echo "<td class='wizard_right_area' style='width:400px' valign='top'>";
	echo "<fieldset class='modelStep_selection'>";
	echo "<legend>".$DATAINJECTIONLANG["modelStep"][13]."</legend>";
	echo "<table class='modelStep_table'>";
	
	/***********************Device Type****************************/
	echo "<tr><td style='width:160px'>".$DATAINJECTIONLANG["modelStep"][4]."</td>";
	
	if($_SESSION["plugin_data_injection"]["choice"]==1)
		echo "<td style='width:105px'><select name='dropdown_device_type'>";
	else
		echo "<td style='width:105px'><select name='dropdown_device_type' style='background-color:#e6e6e6' disabled>";
	
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
	echo "<tr><td>".$DATAINJECTIONLANG["modelStep"][5]."</td>";
	
	if($_SESSION["plugin_data_injection"]["choice"]==1)
		{
		$id=0;
		echo "<td><select id='dropdown_type' name='dropdown_type' onchange='show_backend($id)'>";
		}
	else
		{
		echo "<td><select name='dropdown_type' style='background-color:#e6e6e6' disabled>";
		$id=$model->getModelType();
		}
		
	$types = getAllTypes();
	
	foreach($types as $key => $type)
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
	echo "<tr><td>".$DATAINJECTIONLANG["modelStep"][6]."</td>";
	echo "<td>";
	if(isset($model))
		$can_add = $model->getBehaviorAdd();
	else
		$can_add = 1;
	dropdownYesNo("dropdown_create",$can_add);
	echo "</td></tr>";	

	/**************************************************************/
	
	/**********************Behavior update*************************/
	echo "<tr><td>".$DATAINJECTIONLANG["modelStep"][7]."</td>";
	
	echo "<td>";
	if(isset($model))
		$can_update = $model->getBehaviorUpdate();
	else
		$can_update = 0;
	dropdownYesNo("dropdown_update",$can_update);
	echo "</td></tr>";

	/**************************************************************/
	
	echo "</table>";
	echo "</fieldset>";
	
	echo "<fieldset id='option_backend' class='modelStep_option'>";
	
	echo "</fieldset>";
	
	echo "<script type='text/javascript'>show_backend($id)</script>";
	
	
	echo "<fieldset class='modelStep_selection'>";
	echo "<legend><a href='javascript:show_option()'><img src='../pics/plus.png' alt='plus' id='option_img' style='width:20px;float:left' /></a>".$DATAINJECTIONLANG["modelStep"][15]."</legend>";
	echo "<table class='modelStep_table' id='option' style='display:none'>";
	
	/**********************Can add dropdown************************/
	echo "<tr><td>".$DATAINJECTIONLANG["modelStep"][8]."</td>";
	echo "<td>";
	if(isset($model))
		$can_adddropdown = $model->getCanAddDropdown();
	else
		$can_adddropdown = 0;
	dropdownYesNo("dropdown_canadd",$can_adddropdown);
	echo "</td></tr>";
	/**************************************************************/
	
	/***************Can overwrite if not empty*********************/
	echo "<tr><td>".$DATAINJECTIONLANG["modelStep"][12]."</td><td>";
	if(isset($model))
		$can_overwrite = $model->getCanOverwriteIfNotEmpty();
	else
		$can_overwrite = 1;
	dropdownYesNo("can_overwrite_if_not_empty",$can_overwrite);
	echo "</td></tr>";
	/**************************************************************/
	
	echo "</table>";
	echo "</fieldset>";
	
	/*********************Delimiter Error**************************/
	echo "<div id='delimiter_error' class='delimiter' >".$DATAINJECTIONLANG["modelStep"][11]."</div>";
	/**************************************************************/
	
	echo "</td></tr>";
	
	/**************************Button******************************/
	echo "<tr><td class='wizard_button' colspan='2'>";
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
	
	$model = getModelInstanceByID($_SESSION["plugin_data_injection"]["idmodel"]);
	$name = $model->getModelName();
	
	echo "<form action='".$target."' method='post'>";
	echo "<table class='wizard'>";
	
	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";
	
	/************************Title Step****************************/
	echo "<div class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][2].$DATAINJECTIONLANG["deleteStep"][1]."</div>";
	/**************************************************************/
	
	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>".$DATAINJECTIONLANG["deleteStep"][2]."</div>";
	/**************************************************************/
	
	echo "</td>";
	
	echo "<td class='wizard_right_area' style='width:400px' valign='top'>";
	
	/**********************Confirm delete**************************/
	if($suppr)
		{
		if($model->deleteModel())
			echo "<div class='save_delete'>".$DATAINJECTIONLANG["deleteStep"][5]." \" ".$name." \" ".$DATAINJECTIONLANG["deleteStep"][6]."</div>";
		else
			echo "<div class='save_delete'>".$DATAINJECTIONLANG["deleteStep"][7]."</div>";
		}
	else
		{
		echo "<table class='deleteStep_table'>";
		echo "<tr>";
		echo "<td colspan='2' class='question'>".$DATAINJECTIONLANG["deleteStep"][3]."<br />\" ".$name." \"<br />".$DATAINJECTIONLANG["deleteStep"][4]."</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td><input type='submit' name='yes_deleteStep' value='".$LANG["choice"][1]."' class='submit' /></td>";
		echo "<td><input type='submit' name='no_deleteStep' value='".$LANG["choice"][0]."' class='submit' /></td>";
		echo "</tr>";
		echo "</table>";
		}
	/**************************************************************/
	
	echo "</td></tr>";
	
	/**************************Button******************************/
	echo "<tr><td class='wizard_button' colspan='2'>";
	if($suppr)
		{
		echo "<div class='next'>";
		echo "<input type='submit' name='next_deleteStep' value='".$DATAINJECTIONLANG["button"][6]."' class='submit' />";
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
	
	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";
	
	/************************Title Step****************************/
	if($_SESSION["plugin_data_injection"]["choice"]==1)
		echo "<div class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][3].$DATAINJECTIONLANG["fileStep"][1]."</div>";
	else
		echo "<div class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][2].$DATAINJECTIONLANG["fileStep"][1]."</div>";
	/**************************************************************/
	
	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>".$DATAINJECTIONLANG["fileStep"][2]."</div>";
	/**************************************************************/
	
	echo "</td>";
	
	echo "<td class='wizard_right_area' style='width:400px' valign='top'>";
	
	/************************Select File***************************/
	echo "<table class='fileStep_table'>";
	echo "<tr>";
	echo "<td>".$DATAINJECTIONLANG["fileStep"][3]."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><input type='file' name='file' /></td>";
	echo "</tr>";
	echo "</table>";
	/**************************************************************/
	
	/*************************File Error***************************/
	if(!empty($error))
		echo "<div class='fileStep_error'>".$error."</div>";
	/**************************************************************/
	
	echo "</td></tr>";
	
	/**************************Button******************************/
	echo "<tr><td class='wizard_button' colspan='2'>";
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
		$file = unserialize($_SESSION["plugin_data_injection"]["backend"]);

		$nbline = $file->getNumberOfLine();
	
		if($model->isHeaderPresent())
			$nbline--;
		
		$header = $file->getHeader($model->isHeaderPresent());
		$num = count($header);
		}
	else
		$num = count($model->getMappings());
	/**************************************************************/
	
	echo "<form action='".$target."' method='post'>";
	echo "<table class='wizard'>";
	
	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";
	
	/************************Title Step****************************/
	if($_SESSION["plugin_data_injection"]["choice"]==1)
		echo "<div class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][4]." ".$num." ".$DATAINJECTIONLANG["mappingStep"][1]."</div>";
	else
		echo "<div class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][3]." ".$num." ".$DATAINJECTIONLANG["mappingStep"][1]."</div>";
	/**************************************************************/
	
	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>".$DATAINJECTIONLANG["mappingStep"][9]."</div>";
	echo "<div class='wizard_explain'>".$DATAINJECTIONLANG["mappingStep"][10]."</div>";
	echo "<div class='wizard_explain'>".$DATAINJECTIONLANG["mappingStep"][11]."</div>";
	/**************************************************************/
	
	echo "</td>";
	
	echo "<td class='wizard_right_area' valign='top'>";
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
			foreach($model->getMappings() as $key => $value)
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
		
				$types = getAllMappingsDefinitionsTypes($model->getDeviceType());

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
					echo "<td style='text-align:center'><input type='checkbox' name='field[$key][3]' id='check$key' disabled /></td>";
				else
					{
					if($value->isMandatory())	
						echo "<td style='text-align:center'><input type='checkbox' name='field[$key][3]' id='check$key' checked /></td>";
					else
						echo "<td style='text-align:center'><input type='checkbox' name='field[$key][3]' id='check$key' /></td>";
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
			
			$types = getAllMappingsDefinitionsTypes($model->getDeviceType());
			
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
			echo "<td style='text-align:center'><input type='checkbox' name='field[$key][3]' id='check$key' disabled /></td>";
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
		echo "<form action='plugin_data_injection.popup.file.php' method='post' id='popup'>";
		echo "<table style='margin-top: 10px'>";
		echo "<tr><td style='text-align:center'>";
		echo "Nbr de ligne : <input type='text' id='nbline' name='nbline' size='2' maxlength='3' value='1' onfocus=\"this.value=''\" /> / ".$nbline;
		echo "</td></tr>";
		echo "<tr><td style='text-align:center'>";
		echo "<input type='button' name='valid_popup' value='".$DATAINJECTIONLANG["button"][3]."' class='submit' onclick='file_popup($nbline)' />";
		echo "</td></tr>";
		echo "</table>";
		echo "</form>";
		echo "</td></tr>";
		}
	/**************************************************************/
	
	echo "</table>";
	echo "</td></tr>";
	
	/**************************Button******************************/
	echo "<tr><td class='wizard_button' colspan='2'>";
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
	
	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";
	
	/************************Title Step****************************/
	if($_SESSION["plugin_data_injection"]["choice"]==1)
		echo "<div class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][5].$DATAINJECTIONLANG["infoStep"][1]."</div>";
	else
		echo "<div class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][4].$DATAINJECTIONLANG["infoStep"][2]."</div>";
	/**************************************************************/
	
	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>".$DATAINJECTIONLANG["infoStep"][3]."</div>";
	/**************************************************************/
	
	echo "</td>";
	
	echo "<td class='wizard_right_area' style='width: 400px' valign='top'>";
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
			{
			$nbline = count($model->getInfos());
			if($nbline>0)
				{
				foreach($model->getInfos() as $indice => $value)
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
					
					$types = getAllInfosDefinitionsTypes($model->getDeviceType());
					
					echo "<select name='field[$key][0]' id='table$key' onchange='go_info($key);addelete_info($key)' style='width: 150px'>";
					
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
						$values = getAllInfosDefinitionsByType($value->getInfosType());
	
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
						echo "<td style='text-align:center'><input type='checkbox' name='field[$key][2]' id='check$key' disabled /></td>";
					else
						{
						if($value->isMandatory())	
							echo "<td style='text-align:center'><input type='checkbox' name='field[$key][2]' id='check$key' checked /></td>";
						else
							echo "<td style='text-align:center'><input type='checkbox' name='field[$key][2]' id='check$key' /></td>";
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
				
				$types = getAllInfosDefinitionsTypes($model->getDeviceType());
				
				echo "<select name='field[$key][0]' id='table$key' onchange='go_info($key);addelete_info($key)' style='width: 150px'>";
				
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
				echo "<td style='text-align:center'><input type='checkbox' name='field[$key][2]' id='check$key' disabled /></td>";
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
				
	if( (isset($_SESSION["plugin_data_injection"]["remember"]) && $_SESSION["plugin_data_injection"]["remember"] ==1) || $nbline==0)
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
		
		$types = getAllInfosDefinitionsTypes($model->getDeviceType());
		
		echo "<select name='field[$key][0]' id='table$key' onchange='go_info($key);addelete_info($key)' style='width: 150px'>";
			
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
		echo "<td style='text-align:center'><input type='checkbox' name='field[$key][2]' id='check$key' style='width:60px;' disabled /></td>";
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
	echo "<tr><td class='wizard_button' colspan='2'>";
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
	
	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";
	
	/************************Title Step****************************/
	if($_SESSION["plugin_data_injection"]["choice"]==1)
		echo "<div class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][6].$DATAINJECTIONLANG["saveStep"][1]."</div>";
	else
		echo "<div class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][5].$DATAINJECTIONLANG["saveStep"][1]."</div>";
	/**************************************************************/
	
	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>".$DATAINJECTIONLANG["infoStep"][3]."</div>";
	/**************************************************************/
	
	echo "</td>";
	
	echo "<td class='wizard_right_area' style='width: 400px' valign='top'>";
	
	switch($save)
	{
		case 0:
			/***********************Asking View****************************/
			echo "<table class='saveStep_table'>";
			
			if($_SESSION["plugin_data_injection"]["choice"]==1)
				echo "<tr><td colspan='2'>".$DATAINJECTIONLANG["saveStep"][2]."</td><tr>";
			else
				echo "<tr><td colspan='2'>".$DATAINJECTIONLANG["saveStep"][3]."</td><tr>";
				
			echo "<tr><td style='text-align: center'><input type='submit' name='yes1_saveStep' value='".$LANG["choice"][1]."' class='submit' /></td>";
			echo "<td style='text-align: center'><input type='submit' name='no1_saveStep' value='".$LANG["choice"][0]."' class='submit' /></td></tr>";
			echo "</table>";
			/**************************************************************/
		break;
		case 1:
			/************************Fill View*****************************/
			echo "<table class='saveStep2_table'>";
			echo "<tr><td>".$DATAINJECTIONLANG["saveStep"][4]."</td></tr>";
			
			if($_SESSION["plugin_data_injection"]["choice"]==1)
				{
				echo "<tr><td><input type='text' name='model_name' id='model_name' size='35' /></td></tr>";
				echo "<tr><td>".$DATAINJECTIONLANG["saveStep"][5]."</td></tr>";
				echo "<tr><td><textarea name='comments' rows='4' cols='25'></textarea></td></tr>";
				}
			else
				{
				$model = unserialize($_SESSION["plugin_data_injection"]["model"]);
				echo "<tr><td><input type='text' name='model_name' size='35' value='".$model->getModelName()."' /></td></tr>";
				echo "<tr><td>".$DATAINJECTIONLANG["saveStep"][5]."</td></tr>";
				echo "<tr><td><textarea name='comments' rows='4' cols='25'>".$model->getModelComments()."</textarea></td></tr>";
				}
			echo "</table>";
			/**************************************************************/
		break;
		case 2:	
			/**********************No Save View****************************/
			echo "<table class='saveStep_table'>";
			
			if($_SESSION["plugin_data_injection"]["choice"]==1)
				echo "<tr><td style='text-align: center' colspan='2'>".$DATAINJECTIONLANG["saveStep"][6]."</td><tr>";
			else
				echo "<tr><td style='text-align: center' colspan='2'>".$DATAINJECTIONLANG["saveStep"][7]."</td><tr>";
				
			echo "<tr><td style='text-align: center' colspan='2'>".$DATAINJECTIONLANG["saveStep"][10]."</td><tr>";
			
			echo "<tr><td style='text-align: center'><input type='submit' name='yes2_saveStep' value='".$LANG["choice"][1]."' class='submit' /></td>";
			echo "<td style='text-align: center'><input type='submit' name='no2_saveStep' value='".$LANG["choice"][0]."' class='submit' /></td></tr>";
			echo "</table>";
			/**************************************************************/	
		break;
		case 3:		
			/************************Save View*****************************/
			echo "<table class='saveStep_table'>";
			
			if($_SESSION["plugin_data_injection"]["choice"]==1)
				echo "<tr><td style='text-align: center' colspan='2'>".$DATAINJECTIONLANG["saveStep"][8]."</td><tr>";
			else
				echo "<tr><td style='text-align: center' colspan='2'>".$DATAINJECTIONLANG["saveStep"][9]."</td><tr>";
				
			echo "<tr><td style='text-align: center' colspan='2'>".$DATAINJECTIONLANG["saveStep"][10]."</td><tr>";
			
			echo "<tr><td style='text-align: center'><input type='submit' name='yes2_saveStep' value='".$LANG["choice"][1]."' class='submit' /></td>";
			echo "<td style='text-align: center'><input type='submit' name='no2_saveStep' value='".$LANG["choice"][0]."' class='submit' /></td></tr>";
			echo "</table>";
			/**************************************************************/	
		break;
	}
	
	echo "</td></tr>";
	
	/**************************Button******************************/
	echo "<tr><td class='wizard_button' colspan='2'>";
	
	switch($save)
	{
		case 0:
			echo "<div class='preview'>";
			echo "<input type='submit' name='preview_saveStep' value='".$DATAINJECTIONLANG["button"][1]."' class='submit' />";
			echo "</div>";
		break;
		case 1:
			echo "<div class='next'>";
			echo "<input type='submit' name='next_saveStep' value='".$DATAINJECTIONLANG["button"][6]."' class='submit' />";
			echo "</div>";
		break;
	}

	echo "</td></tr>";
	/**************************************************************/
	
	echo "</table>";
	echo "</form>";
	
	echo "<script type='text/javascript'>document.getElementById('model_name').focus()</script>";
}

function fillInfoStep($target,$error)
{
	global $DATAINJECTIONLANG,$LANG,$DATA_INJECTION_INFOS;
	
	$model = unserialize($_SESSION["plugin_data_injection"]["model"]);
	
	if(($_SESSION["plugin_data_injection"]["load"] == "next_fileStep" || $_SESSION["plugin_data_injection"]["load"] == "preview2_fillInfoStep" || $_SESSION["plugin_data_injection"]["load"] == "yes2_saveStep") && count($model->getInfos())>0)
		$info = 1;
	else
		$info = 0;
	
	echo "<form action='".$target."' method='post'>";
	echo "<table class='wizard'>";
	
	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";
	
	/************************Title Step****************************/
	echo "<div class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][3].$DATAINJECTIONLANG["infoStep"][1]."</div>";
	/**************************************************************/
	
	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>".$DATAINJECTIONLANG["fillInfoStep"][2]."</div>";
	/**************************************************************/
	
	echo "</td>";
	
	echo "<td class='wizard_right_area' style='width: 400px' valign='top'>";	
	
	/************************Infos view****************************/
	if($info)
		{	
			$temp_label = -2;
			
			foreach($model->getInfos() as $key => $value)
				{
				$label = $value->getInfosType();
					
				if($label != $temp_label)
					{
					if($temp_label != -2)
						{
						echo "</table>";
						echo "</fieldset>";
						}
					$temp_label = $label;	
					$commonitem = new CommonItem;
					$commonitem->setType($value->getInfosType());
					echo "<fieldset class='fillInfoStep_fieldset'>";
					echo "<legend>".$commonitem->getType()."</legend>";
					echo "<table class='fillInfoStep_tab'>";
					}
				
				if(isset($DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["type"]))
					$data = $DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["type"];
				else
					$data = "text";
				
				if(isset($DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["input_type"]))
					$input =  $DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["input_type"];
				else
					$input = "text";
				
				if(isset($DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["input_type"]))
					$input =  $DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["input_type"];
				
				switch ($input)
					{
					case "text":
						switch($data)
							{
							case "text":
							case "integer":							
								echo "<tr><td colspan='3'><input type='hidden' name='field[$key][0]' value='".$value->getID()."' /></td></tr>";
								echo "<tr><td style='width: 200px'>".$DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["name"]." : </td><td style='width: 130px'>";
								autocompletionTextField("field[$key][1]",$DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["table"], $DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["field"],$value->getInfosText(),20,$_SESSION["glpiactive_entity"]);
								if($value->isMandatory())
									echo "</td><td class='fillInfoStep_mandatory'>*</td></tr>";
								else
									echo "</td><td style='width:10px'></td></tr>";
							break;
							case "date":
								echo "<tr><td colspan='3'><input type='hidden' name='field[$key][0]' value='".$value->getID()."' /></td></tr>";
								echo "<tr><td style='width: 200px'>".$DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["name"]." : </td><td style='width: 130px'>";
								showCalendarForm("form_ic","field[$key][1]",$value->getInfosText());
								if($value->isMandatory())
									echo "</td><td class='fillInfoStep_mandatory'>*</td></tr>";
								else
									echo "</td><td style='width:10px'></td></tr>";
							break;
							}
					break;
					case "dropdown":
						echo "<tr><td colspan='3'><input type='hidden' name='field[$key][0]' value='".$value->getID()."' /></td></tr>";
						echo "<tr><td style='width: 200px'>".$DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["name"]." : </td><td style='width: 130px'>";

						switch ($DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["field"])
						{
							case "amort_time":
								dropdownInteger("amort_time","",0,15);
								break;
							case "warranty_duration":
								dropdownInteger("warranty_duration","",0,120);
								break;
							case "amort_type":
								dropdownAmortType("field[$key][1]");
								break;
							default:
								dropdownValue($DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["table"], "field[$key][1]", $value->getInfosText(), 0, $_SESSION["glpiactive_entity"]);
								break;						 
						}
						if($value->isMandatory())
							echo "</td><td class='fillInfoStep_mandatory'>*</td></tr>";
						else
							echo "</td><td style='width:10px'></td></tr>";
					break;
					}
				}
				echo "</table>";
				echo "</fieldset>";
				
				echo "<div class='fillInfoStep_red'>".$DATAINJECTIONLANG["fillInfoStep"][3]."</div>";	
				
				/**********************Fill Info Error*************************/
				if(!empty($error))
					echo "<div class='fillInfoStep_error'>".$error."</div>";
				/**************************************************************/
		}
	/**************************************************************/
	
	/***********************Question view**************************/
	else
		{
		echo "<table class='saveStep_table'>";
		echo "<tr><td style='text-align: center' colspan='2'>".$DATAINJECTIONLANG["fillInfoStep"][1]."</td><tr>";
		echo "<tr><td style='text-align: center'><input type='submit' name='yes_fillInfoStep' value='".$LANG["choice"][1]."' class='submit' /></td>";
		echo "<td style='text-align: center'><input type='submit' name='no_fillInfoStep' value='".$LANG["choice"][0]."' class='submit' /></td></tr>";
		echo "</table>";
		}
	/**************************************************************/

	echo "</td></tr>";
	
	/**************************Button******************************/
	echo "<tr><td class='wizard_button' colspan='2'>";
	
	if($info)
		{
		echo "<div class='preview'>";
		echo "<input type='submit' name='preview1_fillInfoStep' value='".$DATAINJECTIONLANG["button"][1]."' class='submit' />";
		echo "</div>";
		
		echo "<div class='next'>";
		echo "<input type='submit' name='next_fillInfoStep' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' />";
		echo "</div>";
		}
	else
		{
		if(count($model->getInfos())>0)
			{
			echo "<div class='preview'>";
			echo "<input type='submit' name='preview2_fillInfoStep' value='".$DATAINJECTIONLANG["button"][1]."' class='submit' />";
			echo "</div>";
			}
		else
			{
			echo "<div class='preview'>";
			echo "<input type='submit' name='preview1_fillInfoStep' value='".$DATAINJECTIONLANG["button"][1]."' class='submit' />";
			echo "</div>";	
			}
		}
		
	echo "</td></tr>";
	/**************************************************************/
	
	echo "</table>";
	echo "</form>";
}

function importStep($target)
{
	global $DATAINJECTIONLANG,$LANG;
	
	echo "<form action='".$target."' method='post'>";
	echo "<table class='wizard'>";
	
	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";
	
	/************************Title Step****************************/
	echo "<div class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][4].$DATAINJECTIONLANG["importStep"][1]."</div>";
	/**************************************************************/
	
	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>".$DATAINJECTIONLANG["importStep"][2]."</div>";
	/**************************************************************/
	
	echo "</td>";
	
	echo "<td class='wizard_right_area' style='width: 400px' valign='top'>";
	
	
	echo "<div id='new_import'>";
	echo "<div class='importStep_cadre'><div class='importStep_progress' id='importStep_progress'><div class='importStep_pourcentage' id='importStep_pourcentage'>".$_SESSION["plugin_data_injection"]["import"]["progress"]." %</div></div></div>";
		
	while($_SESSION["plugin_data_injection"]["import"]["i"]<$_SESSION["plugin_data_injection"]["import"]["i_stop"])
		traitement();
	
	$model = unserialize($_SESSION["plugin_data_injection"]["model"]);
	logEvent(0, getLogItemType($model->getDeviceType()), 4, "plugin" , $_SESSION["glpiname"]." ".$DATAINJECTIONLANG["logevent"][1]);
		
	echo "</div>";

	echo "<div class='importStep_end'>".$DATAINJECTIONLANG["importStep"][3]."</div>";
	
	echo "</td></tr>";
	
	/**************************Button******************************/
	echo "<tr><td class='wizard_button' colspan='2'>";
	echo "<div class='next'>";
	echo "<input type='submit' name='next_importStep' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' />";
	echo "</div>";
	echo "</td></tr>";
	/**************************************************************/
	
	echo "</table>";
	echo "</form>";	
}

function logStep($target)
{
	global $DATAINJECTIONLANG,$LANG;
	
	$nbline = $_SESSION["plugin_data_injection"]["import"]["nbline"];
	$backend = unserialize($_SESSION["plugin_data_injection"]["backend"]);
	
	echo "<form action='".$target."' method='post'>";
	echo "<table class='wizard'>";
	
	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";
	
	/************************Title Step****************************/
	echo "<div class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step"][5].$DATAINJECTIONLANG["logStep"][1]."</div>";
	/**************************************************************/
	
	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>".$DATAINJECTIONLANG["logStep"][2]."</div>";
	/**************************************************************/
	
	echo "</td>";
	
	echo "<td class='wizard_right_area' style='width: 400px' valign='top'>";

	echo "<div class='logStep_success' colspan='2' valign='top'>".$DATAINJECTIONLANG["logStep"][3]."</div>";

	echo "<table class='logStep_tab'>";
	echo "<tr>";
	
	echo "<td style='width:200px'>";
	echo "<input type='button' name='popup' value='".$DATAINJECTIONLANG["button"][4]."' class='submit , logStep_button' onclick='log_popup($nbline)' />";
	echo "</td>";
	
	echo "<td style='width:200px'>";
	echo "<input type='button' name='pdf' value='".$DATAINJECTIONLANG["button"][7]."' class='submit , logStep_button' onclick=\"location.href='plugin_data_injection.export.pdf.php'\" />";
	echo "</td>";
	
	echo "</tr>";
	
	echo "<tr>";
	$tab_result = unserialize($_SESSION["plugin_data_injection"]["import"]["tab_result"]);

	$tab_result = sortAllResults($tab_result);

	if(count($tab_result[0])>0)
		{
		$model = unserialize($_SESSION["plugin_data_injection"]["model"]);
		$file = PLUGIN_DATA_INJECTION_UPLOAD_DIR.$_SESSION["plugin_data_injection"]["file_name"];
		
		$backend->export($file, $model, $tab_result);
		echo "<td colspan='2'>";
		echo "<input type='button' name='export' value='".$DATAINJECTIONLANG["button"][5]."' class='submit , logStep_button' onclick=\"location.href='plugin_data_injection.telecharger.php'\" />";
		echo "</td>";
		}
	
	echo "</tr>";
	echo "</table>";
	
	echo "</td></tr>";
	
	/**************************Button******************************/
	echo "<tr><td class='wizard_button' colspan='2'>";
	echo "<div class='next'>";
	echo "<input type='submit' name='next_logStep' value='".$DATAINJECTIONLANG["button"][6]."' class='submit' />";
	echo "</div>";
	echo "</td></tr>";
	/**************************************************************/
	
	echo "</table>";
	echo "</form>";
}

function traitement()
{
	$model = unserialize($_SESSION["plugin_data_injection"]["model"]);
	$tab_result = unserialize($_SESSION["plugin_data_injection"]["import"]["tab_result"]);
	$global_result = unserialize($_SESSION["plugin_data_injection"]["import"]["global_result"]);
	$engine = unserialize($_SESSION["plugin_data_injection"]["import"]["engine"]);
	$nbline = $_SESSION["plugin_data_injection"]["import"]["nbline"];
	$i = $_SESSION["plugin_data_injection"]["import"]["i"];
	$progress = $_SESSION["plugin_data_injection"]["import"]["progress"];
	$datas = $_SESSION["plugin_data_injection"]["import"]["datas"];
	
	$global_result = $engine->injectLine($datas[$i][0],$model->getInfos());
	$global_result->setLineId($i);
	$tab_result[] = $global_result;
	$progress = round(($i*100)/$nbline,2);
	$i++;
	$datas = $engine->getDatas();
	echo "<script type='text/javascript'>change_progress('".$progress."%')</script>";
	flush();	
		
	$_SESSION["plugin_data_injection"]["import"]["tab_result"] = serialize($tab_result);
	$_SESSION["plugin_data_injection"]["import"]["global_result"] = serialize($global_result);
	$_SESSION["plugin_data_injection"]["import"]["i"] = $i;
	$_SESSION["plugin_data_injection"]["import"]["progress"] = $progress;
	$_SESSION["plugin_data_injection"]["import"]["datas"] = $datas;
}

function initImport()
{
$model = unserialize($_SESSION["plugin_data_injection"]["model"]);
$file = unserialize($_SESSION["plugin_data_injection"]["backend"]);

$tab_result = array();

$global_result = new DataInjectionResults;

$engine = new DataInjectionEngine($model,
  PLUGIN_DATA_INJECTION_UPLOAD_DIR.$_SESSION["plugin_data_injection"]["file"],
  $file,
  $_SESSION["glpiactive_entity"]);
	
if($engine->getModel()->isHeaderPresent())
	{
	$nbline = $engine->getNumberOfLines() - 1;
	$i=1;
	}
else
	{
	$nbline = $engine->getNumberOfLines();
	$i=0;
	}

$i_stop = $engine->getNumberOfLines();
$progress = 0;
	
$datas = $engine->getDatas();

$_SESSION["plugin_data_injection"]["import"]["tab_result"] = serialize($tab_result);
$_SESSION["plugin_data_injection"]["import"]["global_result"] = serialize($global_result);
$_SESSION["plugin_data_injection"]["import"]["engine"] = serialize($engine);
$_SESSION["plugin_data_injection"]["import"]["nbline"] = $nbline;
$_SESSION["plugin_data_injection"]["import"]["i"] = $i;
$_SESSION["plugin_data_injection"]["import"]["i_stop"] = $i_stop;
$_SESSION["plugin_data_injection"]["import"]["progress"] = $progress;
$_SESSION["plugin_data_injection"]["import"]["datas"] = $datas;
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

if(isset($_SESSION["plugin_data_injection"]["file_name"]))
	{
	if(file_exists(PLUGIN_DATA_INJECTION_UPLOAD_DIR.$_SESSION["plugin_data_injection"]["file_name"]))
		unlink(PLUGIN_DATA_INJECTION_UPLOAD_DIR.$_SESSION["plugin_data_injection"]["file_name"]);
	unset($_SESSION["plugin_data_injection"]["file_name"]);
	}

if(isset($_SESSION["plugin_data_injection"]["remember"]))
	unset($_SESSION["plugin_data_injection"]["remember"]);		
	
if(isset($_SESSION["plugin_data_injection"]["import"]))
	unset($_SESSION["plugin_data_injection"]["import"]);

if(isset($_SESSION["plugin_data_injection"]["backend"]))
	unset($_SESSION["plugin_data_injection"]["backend"]);
	
$_SESSION["plugin_data_injection"]["step"] = 1;

if (plugin_data_injection_haveRight("create_model","w"))
	{
	$_SESSION["plugin_data_injection"]["choice"] = 1;
	$_SESSION["plugin_data_injection"]["nbonglet"] = 6;
	}
else if (plugin_data_injection_haveRight("use_model","r"))
	{
	$_SESSION["plugin_data_injection"]["choice"] = 4;
	$_SESSION["plugin_data_injection"]["nbonglet"] = 5;	
	}
}
?>
