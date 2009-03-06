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
	define('GLPI_ROOT', '../../..');
}

$NEEDED_ITEMS=array("data_injection");
include (GLPI_ROOT."/inc/includes.php");

commonHeader($LANG["datainjection"]["config"][1], $_SERVER["PHP_SELF"],"plugins","data_injection");

if(!isset($_SESSION["glpi_plugin_data_injection_installed"]) || $_SESSION["glpi_plugin_data_injection_installed"]!=1) {
	if(!TableExists("glpi_plugin_data_injection_config")) {
			echo "<div align='center'>";
			echo "<table class='tab_cadre' cellpadding='5'>";
			echo "<tr><th>".$LANG["datainjection"]["setup"][1];
			echo "</th></tr>";
			echo "<tr class='tab_bg_1'><td>";
			echo "<a href='plugin_data_injection.install.php'>".$LANG["datainjection"]["setup"][3]."</a></td></tr>";
			echo "</table></div>";
	}
}

echo "<form action=\"\">";
echo "enctype=\"multipart/form-data\" method=\"post\">";
echo "<input type=\"file\" name=\"datafile\" size=\"40\">";
echo "<div>";
echo "<input type=\"submit\" value=\"Send\">";
echo "</div>";
echo "</form>";

commonFooter();
?>
