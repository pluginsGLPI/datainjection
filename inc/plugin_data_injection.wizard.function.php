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
	
	echo "<form action='".$target."' method='post' name='step1'";
	echo "<div class='wizard_cadre'>";
	
	echo "<table class='step1_table'><tr>";
	echo "<td><input type='radio' name='modele' value='1' onClick='showSelect()' checked /></td>";
	echo "<td>".$DATAINJECTIONLANG["step1"][2]."</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td><input type='radio' name='modele' value='2' onClick='showSelect()' /></td>";
	echo "<td>".$DATAINJECTIONLANG["step1"][3]."</td>";
	echo "</tr></table>";
	
	echo "<div>";
	echo "<select disabled name='load'>";
	
	$model = getAllModels();
	
	for($i=0;$i<count($model);$i++)
		echo "<option value='".$model[$i]->getModelID()."'>".$model[$i]->getModelName()." / ".getDropdownName('glpi_plugin_data_injection_filetype',$model[$i]->getModelType())."</option>";
	
	echo "</select>";
	echo "</div>";
	
	echo "</div>";
	echo "<div class='nextprev'><input type='submit' name='next1' value='Suivant >' class='submit' /></div>";
	echo "</form>";
}

function step2($target,$error)
{
	global $DATAINJECTIONLANG;
	
	echo "<div class='wizard_titre'>".$DATAINJECTIONLANG["step2"][1]."</div>";
	
	echo "<form enctype='multipart/form-data' action='".$target."' method='post' name='step2'";
	echo "<div class='wizard_cadre'>";
	
	echo "<table class='step2_table'><tr><td>".$DATAINJECTIONLANG["step2"][2]."</td></tr>";
	echo "<tr><td><input type='file' name='modelfile' /></td></tr></table>";
	
	echo "<div class='nextprev2'>";
	echo "<input type='submit' name='preview2' value='< Precedent' class='submit' />";
	echo "</div>";
	
	echo "<div class='nextprev'>";
	echo "<input type='submit' name='next2' value='Suivant >' class='submit' />";
	echo "</div>";
	
	echo "</form>";
	
	if($error!="")
		echo "<div class='rouge'>".$error."</div>";
}

function step3($target)
{
	echo "<form action='".$target."' method='post'";
	echo "<input type='submit' name='preview3' value='< Precedent' class='submit' />";
	echo "</form>";
}

?>
