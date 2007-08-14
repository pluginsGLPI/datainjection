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

// Original Author of file: Walid Nouh (walid.nouh@atosorigin.com)
// Purpose of file:
// ----------------------------------------------------------------------
if (!defined('GLPI_ROOT')) {
	define('GLPI_ROOT', '../../..');
}

$NEEDED_ITEMS=array("data_injection");
include (GLPI_ROOT."/inc/includes.php");

commonHeader($DATAINJECTIONLANG["config"][1], $_SERVER["PHP_SELF"],"plugins","data_injection");

if(isset($_SESSION["wizard_step"]))
	unset($_SESSION["wizard_step"]);
	
if(isset($_SESSION["wizard_nbonglet"]))
	unset($_SESSION["wizard_nbonglet"]);

if(isset($_SESSION["file_name"]))
	unset($_SESSION["file_name"]);
	
if(isset($_SESSION["verif_file"]))
	unset($_SESSION["verif_file"]);

if(isset($_SESSION["dropdown_type"]))
	unset($_SESSION["dropdown_type"]);

if(isset($_SESSION["delimiteur"]))
	unset($_SESSION["delimiteur"]);

if(isset($_SESSION["dropdown_create"]))
	unset($_SESSION["dropdown_create"]);

if(isset($_SESSION["dropdown_update"]))
	unset($_SESSION["dropdown_update"]);

if(isset($_SESSION["dropdown_header"]))
	unset($_SESSION["dropdown_header"]);


echo "<div class='global'>";

echo "<table class='wizard' style='margin-top:56px;'><tr><td valign='top'>";

echo "<form action='plugin_data_injection.wizard.form.php' method='post' >";

echo "<div class='wizard_titre'>".$DATAINJECTIONLANG["presentation"][1]."</div>";

echo "<div class='nextprev'>";
echo "<input type='submit' value='Suivant >' class='submit' />";
echo "</div>";

echo "</form>";

echo "</td></tr></table>";
echo "</div>";

commonFooter();
?>
