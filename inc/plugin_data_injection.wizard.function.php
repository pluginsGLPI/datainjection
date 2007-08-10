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
	
	echo "<div class='step1_titre'>".$DATAINJECTIONLANG["step1"][1]."</div>";
	echo "<form action='".$target."' method='post' name='step1'";
	echo "<div class='step1_cadre'>";
	echo "<div class='step1_element'><input type='radio' name='modele' checked value='1' onClick='showSelect()' id='r1' />";
	echo "<label for='r1'>".$DATAINJECTIONLANG["step1"][2]."</label></div>";
	echo "<div class='step1_element'><input type='radio' name='modele' value='2' onClick='showSelect()' id='r2' />";
	echo "<label for='r2'>".$DATAINJECTIONLANG["step1"][3]."</label></div>";
	echo "<div class='step1_element'><select disabled name='load'>";
	echo "<option value='1'>Modele1</option>";
	echo "<option value='2'>Modele2</option>";
	echo "<option value='3'>Modele3</option>";
	echo "<option value='4'>Modele4</option>";
	echo "</select></div></div>";
	echo "<div class='nextprev'><input type='submit' name='next1' value='Suivant' class='submit' /></div>";
	echo "</form>";
}

function step2($target)
{
	echo "<form action='".$target."' method='post'";
	echo "<input type='submit' name='preview2' value='< Precedent' class='submit' />";
	echo "<input type='submit' name='next2' value='Suivant >' class='submit' />";
	echo "</form>";
}

function step3($target)
{
	echo "<form action='".$target."' method='post'";
	echo "<input type='submit' name='preview3' value='< Precedent' class='submit' />";
	echo "</form>";
}

?>
