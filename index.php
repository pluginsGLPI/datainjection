<?php
/*
   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

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

// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------
if (!defined('GLPI_ROOT')) {
	define('GLPI_ROOT', '../..');
}

$NEEDED_ITEMS=array("datainjection");
include (GLPI_ROOT."/inc/includes.php");

commonHeader($LANG["datainjection"]["name"][1], $_SERVER["PHP_SELF"],"plugins","datainjection");

initSession();
$_SESSION["plugin_datainjection"]["load"]="presentation";

echo "<div align='center'>";

echo "<form action='./front/plugin_datainjection.wizard.form.php' method='post'>";
echo "<table class='wizard' style='margin-top:1px;'>";

echo "<tr>";
echo "<td class='wizard_left_area' valign='top'>";
echo "<div class='presentation_logo'><img src='./pics/logo.png' alt='logo' /></div>";
echo "</td>";

echo "<td class='wizard_right_area' style='width: 400px' valign='top'>";

echo "<div class='presentation_title'>".$LANG["datainjection"]["presentation"][1]."</div>";

echo "<div class='presentation_text'>".$LANG["datainjection"]["presentation"][2]."<br /><br /><br />".$LANG["datainjection"]["presentation"][3]."</div>";

echo "</td>";
echo "</tr>";	
	
echo "<tr><td class='wizard_button' colspan='2'>";
echo "<div class='next'>";
echo "<input type='submit' name='presentation' value='".$LANG["datainjection"]["button"][2]."' class='submit' />";
echo "</div>";
echo "</td></tr></table>";
echo "</form>";

echo "</div>";

commonFooter();
?>
