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
	
	echo "<tr>";
	echo "<td><input type='radio' name='modele' value='2' onClick='showSelect(this.form); deleteOnglet(4);' /></td>";
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
	
	$models = getAllModels($_SESSION["glpiactive_entity"]);

	foreach($models as $model)
		echo "<option value='".$model->getModelID()."'>".$model->getModelName()." / ".getDropdownName('glpi_plugin_data_injection_filetype',$model->getModelType())."</option>";
	
	echo "</select>";
	echo "</div>";
	echo "</td></tr>";
	
	echo "<tr><td class='wizard_button'>";
	echo "<div class='next'>";
	echo "<input type='submit' name='next1' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' />";
	echo "</div>";
	echo "</td></tr></table>";
	echo "</form>";
}

function step2($target,$error)
{
	global $DATAINJECTIONLANG,$LANG;
	
	echo "<form action='".$target."' method='post' name='step2'>";
	echo "<table class='wizard'>";
	echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step2"][1]."</td></tr>";
	
	echo "<tr><td class='wizard_body'>";
	echo "<div class='wizard_cadre'>";
	
	echo "<table class='step2_table'><tr>";
	echo "<td class='step2_table_width'>".$DATAINJECTIONLANG["step2"][7]." :</td>";
	echo "<td><select name='dropdown_device_type'>";
	
	$types = getAllPrimaryTypes();
		
	foreach($types as $type)
		echo "<option value='".$type[0]."'>".$type[1]."</option>";
	
	echo "</select></td></tr>";
	
	echo "<tr><td>".$DATAINJECTIONLANG["step2"][2]." :</td>";
	echo "<td><select name='dropdown_type'>";
	
	$types = getAllTypes();

	foreach($types as $type)
		echo "<option value='".$type->getBackendID()."'>".$type->getBackendName()."</option>";
	
	echo "</select></td></tr>";
	
	echo "<tr><td>".$DATAINJECTIONLANG["step2"][4]." :</td>";
	echo "<td>";
	echo "<select name='dropdown_create'>";
	echo "<option value='1'>".$LANG["choice"][1]."</option>";
	echo "<option value='0'>".$LANG["choice"][0]."</option>";
	echo "</select>";
	echo "</td></tr>";
	
	echo "<tr><td>".$DATAINJECTIONLANG["step2"][5]." :</td>";
	echo "<td>";
	echo "<select name='dropdown_update'>";
	echo "<option value='0'>".$LANG["choice"][0]."</option>";
	echo "<option value='1'>".$LANG["choice"][1]."</option>";
	echo "</select>";
	echo "</td></tr>";
	
	echo "<tr><td>".$DATAINJECTIONLANG["step2"][6]." :</td>";
	echo "<td>";
	echo "<select name='dropdown_header'>";
	echo "<option value='1'>".$LANG["choice"][1]."</option>";
	echo "<option value='0'>".$LANG["choice"][0]."</option>";
	echo "</select>";
	echo "</td></tr>";
	
	echo "<tr><td>".$DATAINJECTIONLANG["step2"][3]." :</td>";
	echo "<td><input type='text' value=';' size='1' maxlength='1' name='delimiteur' onfocus=\"this.value=''\" /></td></tr>";
	
	echo "</table></div>";
	
	if($error!="")
		echo "<div class='rouge'>".$error."</div>";
	
	echo "</td></tr>";
	
	echo "<tr><td class='wizard_button'>";
	echo "<div class='preview'>";
	echo "<input type='submit' name='preview2' value='".$DATAINJECTIONLANG["button"][1]."' class='submit' />";
	echo "</div>";
	
	echo "<div class='next'>";
	echo "<input type='submit' name='next2' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' />";
	echo "</div>";
	echo "</td></tr></table>";
	echo "</form>";
}

function step3($target)
{
	global $DATAINJECTIONLANG;
	
	echo "<form action='".$target."' method='post' name='step3'>";
	echo "<table class='wizard'>";
	echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step3"][1]."</td></tr>";
	
	echo "<tr><td class='wizard_body'>";
	echo "<div class='wizard_cadre'>";
	
	
	
	
	echo "<div>";
	echo "</td></tr>";
	
	echo "<tr><td class='wizard_button'>";
	echo "<div class='preview'>";
	echo "<input type='submit' name='preview3' value='".$DATAINJECTIONLANG["button"][1]."' class='submit' />";
	echo "</div>";
	
	echo "<div class='next'>";
	echo "<input type='submit' name='next3' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' />";
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
	global $DATAINJECTIONLANG,$LANG,$CFG_GLPI;
	
	$model = unserialize($_SESSION["plugin_data_injection_model"]);
	$file=getBackend($model->getModelType());
	$file->initBackend(PLUGIN_DATA_INJECTION_UPLOAD_DIR.$_SESSION["plugin_data_injection_file_name"],$model->getDelimiter());
	$file->read();
	
	$nbline = $file->getNumberOfLine();
	
	if($model->isHeaderPresent())
		$nbline--;
		
	$header = $file->getHeader($model->isHeaderPresent());
	$num = count($header);
	
	echo "<form action='".$target."' method='post' name='step9' onSubmit='return verif_mandatory($num)'>";
	echo "<table class='wizard'>";
	echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step9"][1]." ".$num." ".$DATAINJECTIONLANG["step9"][2]."</td></tr>";
	
	echo "<tr><td class='wizard_body'>";
	
	echo "<table>";	
	
	echo "<tr style='text-align:center'><th>".$DATAINJECTIONLANG["step9"][6]."</th><th></th><th>".$DATAINJECTIONLANG["step9"][7]."</th><th>".$DATAINJECTIONLANG["step9"][8]."</th><th>".$DATAINJECTIONLANG["step9"][9]."</th></tr>";
	
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
	
	echo "<tr><td class='rouge' style='text-align:center;visibility:hidden' id='mandatory' colspan='5'>".$DATAINJECTIONLANG["step9"][5]."</td></tr>";
	
	echo "<tr><td colspan='5'>";
	echo "<form action='plugin_data_injection.popup.php' method='post' id='popup'>";
	echo "<table style='margin-top: 10px'>";
	echo "<tr><td style='text-align:center'>";
	echo "Nbr de ligne : <input type='text' id='nbline' name='nbline' size='2' maxlength='3' value='1' onfocus=\"this.value=''\" />";
	echo "</td></tr>";
	echo "<tr><td style='text-align:center'>";
	echo "<input type='submit' name='valid_popup' value='".$DATAINJECTIONLANG["button"][3]."' onclick='popup($nbline)' class='submit' />";
	echo "</td></tr>";
	echo "</table>";
	echo "</form>";
	echo "</td></tr>";
	
	echo "</table>";
	
	echo "</td></tr>";
	
	
	echo "<tr><td class='wizard_button'>";
	echo "<div class='preview'>";
	echo "<form action='".$target."' method='post'>";
	echo "<input type='submit' name='preview9' value='".$DATAINJECTIONLANG["button"][1]."' class='submit' />";
	echo "</form>";
	echo "</div>";
	
	echo "<div class='next'>";
	echo "<input type='submit' name='next9' value='".$DATAINJECTIONLANG["button"][2]."' class='submit' />";
	echo "</div>";
	echo "</td></tr></table>";
	echo "</form>";
}	

function step12($target)
{
	global $DATAINJECTIONLANG;
	
	echo "<form action='".$target."' method='post' name='step12'>";
	echo "<table class='wizard'>";
	echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step12"][1]."</td></tr>";
	
	echo "<tr><td class='wizard_body'>";

	$key = 1;
	
	echo "<table>";
	echo "<tr style='text-align:center'><th style='width:150px;'>".$DATAINJECTIONLANG["step9"][7]."</th><th style='width:150px;'>".$DATAINJECTIONLANG["step9"][8]."</th><th>".$DATAINJECTIONLANG["step9"][9]."</th></tr>";
	echo "</table>";
	
	echo "<div id='select$key'>";
	echo "<table id='tab$key'>";
	echo "<tr>";
	echo "<td><input type='hidden' id='add$key' value='0'></td></td><td>$key</td><td><select name='field[$key][0]' id='table$key' onchange='go_info($key);addelete_info($key)' style='width: 150px'>";
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
	echo "<tr><td class='wizard_title' valign='bottom'>".$DATAINJECTIONLANG["step15"][1]."</td></tr>";
	
	echo "<tr><td class='wizard_body'>";
	echo "<div class='wizard_cadre'>";
	
	switch($save)
	{
		case 0:
			echo "<table class='step15_table'>";
			echo "<tr><td colspan='2'>".$DATAINJECTIONLANG["step15"][2]."</td><tr>";
			echo "<tr><td><input type='submit' name='next15_1' value='".$LANG["choice"][1]."' class='submit' /></td>";
			echo "<td><input type='submit' name='next15_2' value='".$LANG["choice"][0]."' class='submit' /></td></tr>";
			echo "</table>";
		break;
		case 1:
			echo "<table class='step15_1_table'>";
			echo "<tr><td>".$DATAINJECTIONLANG["step15"][3]."</td></tr>";
			echo "<tr><td><input type='text' name='model_name' size='35' /></td></tr>";
			echo "<tr><td>".$DATAINJECTIONLANG["step15"][4]."</td></tr>";
			echo "<tr><td><textarea name='comments' rows='4' cols='25'></textarea></td></tr>";
			echo "</table>";
		break;
		case 2:
			echo "<div class='save_delete'>".$DATAINJECTIONLANG["step15"][5]."</div>";
		break;
		case 3:
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
}

?>
