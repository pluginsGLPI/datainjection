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
	
	echo "<div class='wizard_titre'>".$DATAINJECTIONLANG["step1"][1]."</div>";
	
	echo "<form action='".$target."' method='post' name='step1'>";
	echo "<div class='wizard_cadre'>";
	
	echo "<table class='step1_table'><tr>";
	echo "<td class='step1_create'><input type='radio' name='modele' value='1' onClick='showSelect(); deleteOnglet(5);' checked /></td>";
	echo "<td>".$DATAINJECTIONLANG["step1"][2]."</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td><input type='radio' name='modele' value='2' onClick='showSelect(); deleteOnglet(4);' /></td>";
	echo "<td>".$DATAINJECTIONLANG["step1"][3]."</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td><input type='radio' name='modele' value='3' onClick='showSelect(); deleteOnglet(2);' /></td>";
	echo "<td>".$DATAINJECTIONLANG["step1"][4]."</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td><input type='radio' name='modele' value='4' onClick='showSelect(); deleteOnglet(3);' /></td>";
	echo "<td>".$DATAINJECTIONLANG["step1"][5]."</td>";
	echo "</tr></table>";
	
	echo "<div>";
	echo "<select disabled name='dropdown'>";
	
	$models = getAllModels();

	foreach($models as $model)
		echo "<option value='".$model->getModelID()."'>".$model->getModelName()." / ".getDropdownName('glpi_plugin_data_injection_filetype',$model->getModelType())."</option>";
	
	echo "</select>";
	echo "</div>";
	
	echo "</div>";
	echo "<div class='nextprev'><input type='submit' name='next1' value='Suivant >' class='submit' /></div>";
	echo "</form>";
}

function step2($target)
{
	global $DATAINJECTIONLANG,$LANG;
	
	echo "<div class='wizard_titre'>".$DATAINJECTIONLANG["step2"][1]."</div>";
	
	echo "<form action='".$target."' method='post' name='step2'>";
	echo "<div class='wizard_cadre'>";
	echo "<table class='step2_table'>";
	
	echo "<tr><td>".$DATAINJECTIONLANG["step2"][2]." :</td>";
	echo "<td><select name='dropdown_type'>";
	
	$types = getAllTypes();

	foreach($types as $type)
		echo "<option value='".$type->getBackendID()."'>".$type->getBackendName()."</option>";
	
	echo "</select></td></tr>";
	
	echo "<tr><td>".$DATAINJECTIONLANG["step2"][3]." :</td>";
	echo "<td><input type='text' size='1' maxlength='1' name='delimiteur' /></td></tr>";
	
	echo "<tr><td class='step2_table_width'>".$DATAINJECTIONLANG["step2"][4]." :</td>";
	echo "<td>";
	echo "<select name='dropdown_create'>";
	echo "<option value='1'>".$LANG["choice"][1]."</option>";
	echo "<option value='0'>".$LANG["choice"][0]."</option>";
	echo "</select>";
	echo "</td></tr>";
	
	echo "<tr><td class='step2_table_width'>".$DATAINJECTIONLANG["step2"][5]." :</td>";
	echo "<td>";
	echo "<select name='dropdown_update'>";
	echo "<option value='0'>".$LANG["choice"][0]."</option>";
	echo "<option value='1'>".$LANG["choice"][1]."</option>";
	echo "</select>";
	echo "</td></tr>";
	
	echo "<tr><td class='step2_table_width'>".$DATAINJECTIONLANG["step2"][6]." :</td>";
	echo "<td>";
	echo "<select name='dropdown_header'>";
	echo "<option value='1'>".$LANG["choice"][1]."</option>";
	echo "<option value='0'>".$LANG["choice"][0]."</option>";
	echo "</select>";
	echo "</td></tr>";
	
	echo "</table>";
	echo "</div>";
	
	echo "<div class='nextprev2'>";
	echo "<input type='submit' name='preview2' value='< Precedent' class='submit' />";
	echo "</div>";
	
	echo "<div class='nextprev'>";
	echo "<input type='submit' name='next2' value='Suivant >' class='submit' />";
	echo "</div>";
	
	echo "</form>";
}

function step3($target)
{
	echo "<form action='".$target."' method='post' name='step3'>";
	echo "<input type='submit' name='preview3' value='< Precedent' class='submit' />";
	echo "</form>";
}

function step4($target,$suppr)
{
	global $DATAINJECTIONLANG;
	
	echo "<div class='wizard_titre'>".$DATAINJECTIONLANG["step4"][1]."</div>";
	
	echo "<form action='".$target."' method='post' name='step4'>";
	echo "<div class='wizard_cadre'>";
	
	$model = new DataInjectionModel();
		
	$model->getFromDB($_SESSION["wizard_idmodel"]);
	
	$name = $model->getModelName();
	
	if($suppr)
		{
		if($model->deleteFromDB($_SESSION["wizard_idmodel"]))
			echo $DATAINJECTIONLANG["step4"][4]." \" ".$name." \" ".$DATAINJECTIONLANG["step4"][5];
		else
			echo $DATAINJECTIONLANG["step4"][6];
		}
	else
		{
		echo "<table class='step4_table'><tr><td colspan='2' class='question'>";
		echo $DATAINJECTIONLANG["step4"][2]."<br />\" ".$name." \"<br />".$DATAINJECTIONLANG["step4"][3];
		echo "</td><tr>";
		echo "<tr><td><input type='submit' name='next4_1' value='Oui' class='submit' /></td>";
		echo "<td><input type='submit' name='preview4' value='Non' class='submit' /></td></tr></table>";
		}
	
	echo "</div>";
	
	if($suppr)
		{
		echo "<div class='nextprev'>";
		echo "<input type='submit' name='next4_2' value='Suivant >' class='submit' />";
		echo "</div>";
		}
	
	echo "</form>";
}

function step5($target,$error)
{
	global $DATAINJECTIONLANG;
	
	if(!$_SESSION["verif_file"])
		echo "<div class='wizard_titre'>".$DATAINJECTIONLANG["step5"][1]."</div>";
	else
		echo "<div class='wizard_titre'>".$DATAINJECTIONLANG["step6"][1]."</div>";
	
	echo "<form enctype='multipart/form-data' action='".$target."' method='post' name='step5'>";
	echo "<div class='wizard_cadre'>";
	
	echo "<table class='step5_table'><tr><td>".$DATAINJECTIONLANG["step5"][2]."</td></tr>";
	echo "<tr><td><input type='file' name='modelfile' /></td></tr></table>";
	
	if($error!="")
		echo "<div class='rouge'>".$error."</div>";
	
	echo "</div>";
	
	echo "<div class='nextprev2'>";
	echo "<input type='submit' name='preview5' value='< Precedent' class='submit' />";
	echo "</div>";
	
	echo "<div class='nextprev'>";
	echo "<input type='submit' name='next5' value='Suivant >' class='submit' />";
	echo "</div>";
	
	echo "</form>";
}


?>
