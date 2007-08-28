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

function step1($target)
{
	global $DATAINJECTIONLANG;
	
	deleteSession();
	
	echo "<form action='".$target."' method='post' name='step1'>";
	echo "<table class='wizard'>";
	echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step1"][1]."</td></tr>";
	
	echo "<tr><td class='wizard_body'>";
	echo "<div class='wizard_cadre'>";
	
	echo "<table class='step1_table'><tr>";
	echo "<td class='step1_create'><input type='radio' id='checkbox' name='modele' value='1' onClick='showSelect(this.form); deleteOnglet(6);' checked /></td>";
	echo "<td>".$DATAINJECTIONLANG["step1"][2]."</td>";
	echo "</tr>";
	
	$models = getAllModels($_SESSION["glpiactive_entity"]);
	if (count($models)>0) {
	echo "<tr>";
	echo "<td><input type='radio' name='modele' value='2' onClick='showSelect(this.form); deleteOnglet(5);' /></td>";
	echo "<td>".$DATAINJECTIONLANG["step1"][3]."</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td><input type='radio' name='modele' value='3' onClick='showSelect(this.form); deleteOnglet(2);' /></td>";
	echo "<td>".$DATAINJECTIONLANG["step1"][4]."</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td><input type='radio' name='modele' value='4' onClick='showSelect(this.form); deleteOnglet(3);' /></td>";
	echo "<td>".$DATAINJECTIONLANG["step1"][5]."</td>";
	echo "</tr></table>";
	
	echo "<div>";
	echo "<select disabled name='dropdown'>";
	

	foreach($models as $model)
		echo "<option value='".$model->getModelID()."'>".$model->getModelName()." / ".getDropdownName('glpi_plugin_data_injection_filetype',$model->getModelType())."</option>";
	
	echo "</select>";
	echo "</div>";
	echo "</td></tr>";
	}
	else echo"</table></td></tr>";

	echo "<tr><td class='wizard_button'>";
	echo "<div class='next'>";
	echo "<input type='submit' name='next1' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' />";
	echo "</div>";
	echo "</td></tr></table>";
	echo "</form>";
}

function step2($target)
{
	global $DATAINJECTIONLANG,$LANG;
	
	if(isset($_SESSION["plugin_data_injection_model"]))
		$model = unserialize($_SESSION["plugin_data_injection_model"]);
		
	echo "<form action='".$target."' method='post' name='step2'>";
	echo "<table class='wizard'>";
	if($_SESSION["plugin_data_injection_modif_model"])
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step3"][1]."</td></tr>";
	else
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step2"][1]."</td></tr>";
		
	echo "<tr><td class='wizard_body'>";
	echo "<div class='wizard_cadre'>";
	
	echo "<table class='step2_table'><tr>";
	
	//Device Type
	echo "<td class='step2_table_width'>".$DATAINJECTIONLANG["step2"][7]." :</td>";
	
	if($_SESSION["plugin_data_injection_modif_model"])
		echo "<td><select name='dropdown_device_type' disabled>";
	else
		echo "<td><select name='dropdown_device_type'>";
	
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
	
	//Type
	echo "<tr><td>".$DATAINJECTIONLANG["step2"][2]." :</td>";
	
	if($_SESSION["plugin_data_injection_modif_model"])
		echo "<td><select name='dropdown_type' disabled>";
	else
		echo "<td><select name='dropdown_type'>";
		
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
	
	//Behavior add
	echo "<tr><td>".$DATAINJECTIONLANG["step2"][4]." :</td>";
	echo "<td>";
	echo "<select name='dropdown_create'>";
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
	echo "</select>";
	echo "</td></tr>";
	
	//Behavior update
	echo "<tr><td>".$DATAINJECTIONLANG["step2"][5]." :</td>";
	echo "<td>";
	echo "<select name='dropdown_update'>";
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
	echo "</select>";
	echo "</td></tr>";
	
	//Header
	echo "<tr><td>".$DATAINJECTIONLANG["step2"][6]." :</td>";
	
	if($_SESSION["plugin_data_injection_modif_model"])
		echo "<td><select name='dropdown_header' disabled>";
	else
		echo "<td><select name='dropdown_header'>";
	
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
	echo "</select>";
	echo "</td></tr>";
	
	//Delimiter
	echo "<tr><td>".$DATAINJECTIONLANG["step2"][3]." :</td>";
	
	if($_SESSION["plugin_data_injection_modif_model"] && isset($model))
		echo "<td><input type='text' value='".$model->getDelimiter()."' size='1' maxlength='1' name='delimiter' disabled style='font-weight:bold;background-color:#d4d0c8' onfocus=\"this.value=''\" /></td></tr>";
	else if(isset($model))
		echo "<td><input type='text' value='".$model->getDelimiter()."' size='1' maxlength='1' name='delimiter' onfocus=\"this.value=''\" /></td></tr>";
	else
		echo "<td><input type='text' value=';' size='1' maxlength='1' name='delimiter' onfocus=\"this.value=''\" /></td></tr>";
	
	echo "</table></div>";
	
	echo "<div id='verif_delimiter' class='rouge' style='text-align:center;margin-top:10px;font-size:14px;visibility:hidden'>".$DATAINJECTIONLANG["step2"][8]."</div>";
	
	echo "</td></tr>";
	
	echo "<tr><td class='wizard_button'>";
	echo "<div class='preview'>";
	echo "<input type='submit' name='preview2' value='".$DATAINJECTIONLANG["button"][1]."' class='submit' />";
	echo "</div>";
	
	echo "<div class='next'>";
	if($_SESSION["plugin_data_injection_modif_model"])
		echo "<input type='submit' name='next3' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' />";
	else
		echo "<input type='submit' name='next2' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' onclick='return verif_step2()' />";
	echo "</div>";
	echo "</td></tr></table>";
	echo "</form>";
}

function step4($target,$suppr)
{
	global $DATAINJECTIONLANG, $LANG;
	
	echo "<form action='".$target."' method='post' name='step4'>";
	echo "<table class='wizard'>";
	echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step4"][1]."</td></tr>";
	
	echo "<tr><td class='wizard_body'>";
	echo "<div class='wizard_cadre'>";
	
	$model = new DataInjectionModel();
		
	$model->getFromDB($_SESSION["plugin_data_injection_wizard_idmodel"]);
	
	$name = $model->getModelName();
	
	if($suppr)
		{
		if($model->deleteModel())
			{
			echo "<div class='save_delete'>".$DATAINJECTIONLANG["step4"][4]." \" ".$name." \" ".$DATAINJECTIONLANG["step4"][5]."</div>";
			}
		else
			echo "<div class='save_delete'>".$DATAINJECTIONLANG["step4"][6]."</div>";
		}
	else
		{
		echo "<table class='step4_table'><tr><td colspan='2' class='question'>";
		echo $DATAINJECTIONLANG["step4"][2]."<br />\" ".$name." \"<br />".$DATAINJECTIONLANG["step4"][3];
		echo "</td><tr>";
		echo "<tr><td><input type='submit' name='next4_1' value='".$LANG["choice"][1]."' class='submit' /></td>";
		echo "<td><input type='submit' name='preview4' value='".$LANG["choice"][0]."' class='submit' /></td></tr></table>";
		}
	
	echo "<div>";
	echo "</td></tr>";
	
	echo "<tr><td class='wizard_button'>";
	if($suppr)
		{
		echo "<div class='next'>";
		echo "<input type='submit' name='next4_2' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' />";
		echo "</div>";
		}
	
	echo "</td></tr></table>";
	echo "</form>";
}

function step5($target,$error)
{
	global $DATAINJECTIONLANG;
	
	echo "<form action='".$target."' method='post' name='step5' enctype='multipart/form-data'>";
	echo "<table class='wizard'>";
	
	if(!$_SESSION["plugin_data_injection_verif_file"])
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step5"][1]."</td></tr>";
	else
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step6"][1]."</td></tr>";
		
	echo "<tr><td class='wizard_body'>";
	echo "<div class='wizard_cadre'>";
	
	echo "<table class='step5_table'><tr><td>".$DATAINJECTIONLANG["step5"][2]."</td></tr>";
	echo "<tr><td><input type='file' name='modelfile' /></td></tr></table>";
	
	if($error!="")
		echo "<div class='rouge'>".$error."</div>";
	
	echo "<div>";
	echo "</td></tr>";
	
	echo "<tr><td class='wizard_button'>";
	echo "<div class='preview'>";
	echo "<input type='submit' name='preview5' value='".$DATAINJECTIONLANG["button"][1]."' class='submit' />";
	echo "</div>";
	
	echo "<div class='next'>";
	echo "<input type='submit' name='next5' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' />";
	echo "</div>";
	echo "</td></tr></table>";
	echo "</form>";
}

function step9($target)
{
	global $DATAINJECTIONLANG;
	
	$model = unserialize($_SESSION["plugin_data_injection_model"]);
	
	if($_SESSION["plugin_data_injection_modif_model"] == 1)
		$num = count($model->getMappings()->getAllMappings());
	else
		{
		$file=getBackend($model->getModelType());
		$file->initBackend(PLUGIN_DATA_INJECTION_UPLOAD_DIR.$_SESSION["plugin_data_injection_file_name"],$model->getDelimiter());
		$file->read();
	
		$nbline = $file->getNumberOfLine();
	
		if($model->isHeaderPresent())
			$nbline--;
		
		$header = $file->getHeader($model->isHeaderPresent());
		$num = count($header);
		}
	
	echo "<form action='".$target."' method='post' name='step9'>";
	echo "<table class='wizard'>";
	
	if($_SESSION["plugin_data_injection_modif_model"] == 1)
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step7"][1]."</td></tr>";
	else
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step9"][1]." ".$num." ".$DATAINJECTIONLANG["step9"][2]."</td></tr>";
	
	echo "<tr><td class='wizard_body' valign='top'>";
	
	echo "<table style='margin-top:30px'>";	
	
	echo "<tr style='text-align:center'><th>".$DATAINJECTIONLANG["step9"][6]."</th><th></th><th>".$DATAINJECTIONLANG["step9"][7]."</th><th>".$DATAINJECTIONLANG["step9"][8]."</th><th>".$DATAINJECTIONLANG["step9"][9]."</th></tr>";
	
	if($_SESSION["plugin_data_injection_modif_model"])
		{
		foreach($model->mappings as $mapping)
			{
			foreach($mapping as $key => $value)
				{
				echo "<tr>";
				echo "<td><input type='hidden' name='field[$key][0]' value='".$value->getName()."' />".$value->getName()." : </td>";
				echo "<td style='text-align:center;width:75px'><img src='../pics/fleche.png' alt='fleche' /></td>";
				echo "<td><select name='field[$key][1]' id='table$key' onchange='go_mapping($key)' style='width: 150px'>";
				echo "<option value='-1'>".$DATAINJECTIONLANG['step9'][3]."</option>";
		
				$types = getAllMappingsDefinitionsTypes();
		
				foreach($types as $type)
					if($value->getMappingType() == $type[0])
						echo "<option value='".$type[0]."' selected>".$type[1]."</option>";
					else
						echo "<option value='".$type[0]."'>".$type[1]."</option>";
			
				echo "</select></td>";
		
				echo "<td id='field$key'><select name='field[$key][2]' style='width: 150px'>";
		
				if($value->getMappingType()!=-1)
					{
					$values = getAllMappingsDefinitionsByType($value->getMappingType());

					foreach($values as $key2 => $value2)
						if($value->getValue()==$key2)
							echo "<option value='".$key2."' selected>".$value2."</option>";
						else
							echo "<option value='".$key2."'>".$value2."</option>";
					}
				else
					echo "<option value='-1'>".$DATAINJECTIONLANG["step9"][4]."</option>";
		
				echo "</select></td>";
		
				if($value->getMappingType()!=-1)
					{
					if($value->isMandatory())	
						echo "<td><input type='checkbox' name='field[$key][3]' id='check$key' style='text-align:center' checked /></td>";
					else
						echo "<td><input type='checkbox' name='field[$key][3]' id='check$key' style='text-align:center' /></td>";
					}
				else
					echo "<td><input type='checkbox' name='field[$key][3]' id='check$key' style='text-align:center;visibility:hidden' /></td>";
				echo "</tr>";
				}
			}
		}
	else
		{
		foreach($header as $key => $value)
			{
			echo "<tr>";
			echo "<td><input type='hidden' name='field[$key][0]' value='$value' />".$value." : </td>";
			echo "<td style='text-align:center;width:75px'><img src='../pics/fleche.png' alt='fleche' /></td>";
			echo "<td><select name='field[$key][1]' id='table$key' onchange='go_mapping($key)' style='width: 150px'>";
			echo "<option value='-1'>".$DATAINJECTIONLANG['step9'][3]."</option>";
			
			$types = getAllMappingsDefinitionsTypes();
			
			foreach($types as $type)
				echo "<option value='".$type[0]."'>".$type[1]."</option>";
				
			echo "</select></td>";
			echo "<td id='field$key'><select name='field[$key][2]' style='width: 150px'><option value='-1'>".$DATAINJECTIONLANG["step9"][4]."</option></select></td>";
			echo "<td style='text-align:center'><input type='checkbox' name='field[$key][3]' id='check$key' style='text-align:center;visibility:hidden' /></td>";
			echo "</tr>";
			}
		}
	echo "<tr><td class='rouge' style='text-align:center;visibility:hidden' id='mandatory' colspan='5'>".$DATAINJECTIONLANG["step9"][5]."</td></tr>";
	
	if($_SESSION["plugin_data_injection_modif_model"]!=1)
		{
		echo "<tr><td colspan='5'>";
		echo "<form action='plugin_data_injection.popup.php' method='post' id='popup'>";
		echo "<table style='margin-top: 10px'>";
		echo "<tr><td style='text-align:center'>";
		echo "Nbr de ligne : <input type='text' id='nbline' name='nbline' size='2' maxlength='3' value='1' onfocus=\"this.value=''\" />";
		echo "</td></tr>";
		echo "<tr><td style='text-align:center'>";
		echo "<input type='button' name='valid_popup' value='".$DATAINJECTIONLANG["button"][3]."' onclick='popup($nbline)' class='submit' />";
		echo "</td></tr>";
		echo "</table>";
		echo "</form>";
		echo "</td></tr>";
		}
	
	echo "</table>";
	
	echo "</td></tr>";
	
	echo "<tr><td class='wizard_button'>";
	echo "<div class='preview'>";
	echo "<input type='submit' name='preview9' value='".$DATAINJECTIONLANG["button"][1]."' class='submit' />";
	echo "</div>";
	
	echo "<div class='next'>";
	echo "<input type='submit' name='next9' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' onclick='return verif_step9($num)' />";
	echo "</div>";
	echo "</td></tr></table>";
	echo "</form>";
}	

function step12($target)
{
	global $DATAINJECTIONLANG;
	
	$model = unserialize($_SESSION["plugin_data_injection_model"]);
	
	echo "<form action='".$target."' method='post' name='step12'>";
	echo "<table class='wizard'>";
	
	if($_SESSION["plugin_data_injection_modif_model"]==1)
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step10"][1]."</td></tr>";
	else
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step12"][1]."</td></tr>";
	
	echo "<tr><td class='wizard_body' valign='top'>";
	
	echo "<table style='margin-top:30px'>";
	echo "<tr style='text-align:center'><th style='width:150px;'>".$DATAINJECTIONLANG["step9"][7]."</th><th style='width:150px;'>".$DATAINJECTIONLANG["step9"][8]."</th><th>".$DATAINJECTIONLANG["step9"][9]."</th></tr>";
	echo "</table>";
	
	if($_SESSION["plugin_data_injection_modif_model"]!=2)
		{
		foreach($model->infos as $info)
			{
			if(count($info)>0)
				{
				foreach($info as $indice => $value)
					{
					$key=$indice+1;
					
					echo "<div id='select$key'>";
					echo "<table id='tab$key'>";
					echo "<tr>";
					if($value->getInfosType()!=-1)
						echo "<td><input type='hidden' id='add$key' value='1'></td></td>";
					else
						echo "<td><input type='hidden' id='add$key' value='0'></td></td>";
					
					echo "<td><select name='field[$key][0]' id='table$key' onchange='go_info($key);addelete_info($key)' style='width: 150px'>";
					echo "<option value='-1'>".$DATAINJECTIONLANG['step9'][3]."</option>";
						
					$types = getAllMappingsDefinitionsTypes();
						
					foreach($types as $type)
						if($value->getInfosType() == $type[0])
							echo "<option value='".$type[0]."' selected>".$type[1]."</option>";
						else
							echo "<option value='".$type[0]."'>".$type[1]."</option>";
						
					echo "</select></td>";
					
					echo "<td id='field$key'><select name='field[$key][1]' style='width: 150px'>";
			
					if($value->getInfosType()!=-1)
						{
						$values = getAllMappingsDefinitionsByType($value->getInfosType());
	
						foreach($values as $key2 => $value2)
							if($value->getValue()==$key2)
								echo "<option value='".$key2."' selected>".$value2."</option>";
							else
								echo "<option value='".$key2."'>".$value2."</option>";
						}
					else
						echo "<option value='-1'>".$DATAINJECTIONLANG["step9"][4]."</option>";
			
					echo "</select></td>";
					
					if($value->getInfosType()!=-1)
						{
						if($value->isMandatory())	
							echo "<td style='text-align:center'><input type='checkbox' name='field[$key][2]' id='check$key' style='text-align:center;width:60px' checked /></td>";
						else
							echo "<td style='text-align:center'><input type='checkbox' name='field[$key][2]' id='check$key' style='text-align:center;width:60px' /></td>";
						}
					else
						echo "<td style='text-align:center'><input type='checkbox' name='field[$key][2]' id='check$key' style='text-align:center;width:60px;visibility:hidden' /></td>";
					
					echo "</tr>";
					echo "</table>";
					echo "</div>";
					}
				
				$key++;
				
				echo "<div id='select$key'>";
				echo "<table id='tab$key'>";
				echo "<tr>";
				echo "<td><input type='hidden' id='add$key' value='0'></td></td><td><select name='field[$key][0]' id='table$key' onchange='go_info($key);addelete_info($key)' style='width: 150px'>";
				echo "<option value='-1'>".$DATAINJECTIONLANG['step9'][3]."</option>";
					
				$types = getAllMappingsDefinitionsTypes();
					
				foreach($types as $type)
					echo "<option value='".$type[0]."'>".$type[1]."</option>";
					
				echo "</select></td>";
				echo "<td id='field$key'><select name='field[$key][1]' style='width: 150px'><option value='-1'>".$DATAINJECTIONLANG["step9"][4]."</option></select></td>";
				echo "<td><input type='checkbox' name='field[$key][2]' id='check$key' style='text-align:center;width:60px;visibility:hidden' /></td>";
				echo "</tr>";
				echo "</table>";
				echo "</div>";
				
				$key++;
				
				echo "<div id='select$key'>";
				echo "</div>";
				}
			else
				{
				$key = 1;
				
				echo "<div id='select$key'>";
				echo "<table id='tab$key'>";
				echo "<tr>";
				echo "<td><input type='hidden' id='add$key' value='0'></td></td><td><select name='field[$key][0]' id='table$key' onchange='go_info($key);addelete_info($key)' style='width: 150px'>";
				echo "<option value='-1'>".$DATAINJECTIONLANG['step9'][3]."</option>";
					
				$types = getAllMappingsDefinitionsTypes();
					
				foreach($types as $type)
					echo "<option value='".$type[0]."'>".$type[1]."</option>";
					
				echo "</select></td>";
				echo "<td id='field$key'><select name='field[$key][1]' style='width: 150px'><option value='-1'>".$DATAINJECTIONLANG["step9"][4]."</option></select></td>";
				echo "<td><input type='checkbox' name='field[$key][2]' id='check$key' style='text-align:center;width:60px;visibility:hidden' /></td>";
				echo "</tr>";
				echo "</table>";
				echo "</div>";
				
				$key++;
				
				echo "<div id='select$key'>";
				echo "</div>";
				}
			}
		}
	else
		{
		$key = 1;
	
		echo "<div id='select$key'>";
		echo "<table id='tab$key'>";
		echo "<tr>";
		echo "<td><input type='hidden' id='add$key' value='0'></td></td><td><select name='field[$key][0]' id='table$key' onchange='go_info($key);addelete_info($key)' style='width: 150px'>";
		echo "<option value='-1'>".$DATAINJECTIONLANG['step9'][3]."</option>";
			
		$types = getAllMappingsDefinitionsTypes();
			
		foreach($types as $type)
			echo "<option value='".$type[0]."'>".$type[1]."</option>";
			
		echo "</select></td>";
		echo "<td id='field$key'><select name='field[$key][1]' style='width: 150px'><option value='-1'>".$DATAINJECTIONLANG["step9"][4]."</option></select></td>";
		echo "<td><input type='checkbox' name='field[$key][2]' id='check$key' style='text-align:center;width:60px;visibility:hidden' /></td>";
		echo "</tr>";
		echo "</table>";
		echo "</div>";
		
		$key++;
		
		echo "<div id='select$key'>";
		echo "</div>";
		}
			
	echo "</td></tr>";
	
	echo "<tr><td class='wizard_button'>";
	echo "<div class='preview'>";
	echo "<input type='submit' name='preview12' value='".$DATAINJECTIONLANG["button"][1]."' class='submit' />";
	echo "</div>";
	
	echo "<div class='next'>";
	echo "<input type='submit' name='next12' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' />";
	echo "</div>";
	echo "</td></tr></table>";
	echo "</form>";
}

function step15($target,$save)
{
	global $DATAINJECTIONLANG,$LANG;
	
	echo "<form action='".$target."' method='post' name='step15'>";
	echo "<table class='wizard'>";
	if($_SESSION["plugin_data_injection_modif_model"] == 1)
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step13"][1]."</td></tr>";
	else
		echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step15"][1]."</td></tr>";
	
	echo "<tr><td class='wizard_body'>";
	echo "<div class='wizard_cadre'>";
	
	switch($save)
	{
		case 0:
			echo "<table class='step15_table'>";
			if($_SESSION["plugin_data_injection_modif_model"]==1)
				echo "<tr><td colspan='2'>".$DATAINJECTIONLANG["step13"][2]."</td><tr>";
			else
				echo "<tr><td colspan='2'>".$DATAINJECTIONLANG["step15"][2]."</td><tr>";
			echo "<tr><td><input type='submit' name='next15_1' value='".$LANG["choice"][1]."' class='submit' /></td>";
			echo "<td><input type='submit' name='next15_2' value='".$LANG["choice"][0]."' class='submit' /></td></tr>";
			echo "</table>";
		break;
		case 1:
			echo "<table class='step15_1_table'>";
			echo "<tr><td>".$DATAINJECTIONLANG["step15"][3]."</td></tr>";
			if($_SESSION["plugin_data_injection_modif_model"]==1)
				{
				$model = unserialize($_SESSION["plugin_data_injection_model"]);
				echo "<tr><td><input type='text' name='model_name' size='35' value='".$model->getModelName()."' /></td></tr>";
				echo "<tr><td>".$DATAINJECTIONLANG["step15"][4]."</td></tr>";
				echo "<tr><td><textarea name='comments' rows='4' cols='25'>".$model->getModelComments()."</textarea></td></tr>";
				}
			else
				{
				echo "<tr><td><input type='text' name='model_name' size='35' /></td></tr>";
				echo "<tr><td>".$DATAINJECTIONLANG["step15"][4]."</td></tr>";
				echo "<tr><td><textarea name='comments' rows='4' cols='25'></textarea></td></tr>";
				}
			echo "</table>";
		break;
		case 2:
			if($_SESSION["plugin_data_injection_modif_model"]==1)
				echo "<div class='save_delete'>".$DATAINJECTIONLANG["step13"][3]."</div>";
			else
				echo "<div class='save_delete'>".$DATAINJECTIONLANG["step15"][5]."</div>";
		break;
		case 3:
			if($_SESSION["plugin_data_injection_modif_model"]==1)
				echo "<div class='save_delete'>".$DATAINJECTIONLANG["step13"][4]."</div>";
			else
				echo "<div class='save_delete'>".$DATAINJECTIONLANG["step15"][6]."</div>";
		break;
	}
	
	echo "<div>";
	echo "</td></tr>";
	
	echo "<tr><td class='wizard_button'>";
	
	switch($save)
	{
		case 0:
			echo "<div class='preview'>";
			echo "<input type='submit' name='preview15' value='".$DATAINJECTIONLANG["button"][1]."' class='submit' />";
			echo "</div>";
		break;
		case 1:
			echo "<div class='next'>";
			echo "<input type='submit' name='next15_3' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' />";
			echo "</div>";
		break;
		case 2:
		case 3:
			echo "<div class='next'>";
			echo "<input type='submit' name='next15' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' />";
			echo "</div>";
		break;
	}

	echo "</td></tr></table>";
	echo "</form>";
	
	echo "<script type='text/javascript'>document.forms['step15'].model_name.focus()</script>";
}

function deleteSession()
{
if(isset($_SESSION["plugin_data_injection_wizard_step"]))
	unset($_SESSION["plugin_data_injection_wizard_step"]);
	
if(isset($_SESSION["plugin_data_injection_wizard_nbonglet"]))
	unset($_SESSION["plugin_data_injection_wizard_nbonglet"]);
	
if(isset($_SESSION["plugin_data_injection_wizard_idmodel"]))
	unset($_SESSION["plugin_data_injection_wizard_idmodel"]);

if(isset($_SESSION["plugin_data_injection_file_name"]))
	unset($_SESSION["plugin_data_injection_file_name"]);
	
if(isset($_SESSION["plugin_data_injection_verif_file"]))
	unset($_SESSION["plugin_data_injection_verif_file"]);
	
if(isset($_SESSION["plugin_data_injection_model"]))
	unset($_SESSION["plugin_data_injection_model"]);	

if(isset($_SESSION["plugin_data_injection_modif_model"]))
	unset($_SESSION["plugin_data_injection_modif_model"]);	
}
?>
