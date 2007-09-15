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

if(!isset($_SESSION["glpi_plugin_data_injection_installed"]) || $_SESSION["glpi_plugin_data_injection_installed"]!=1) {
	
	commonHeader($LANG["title"][2],$_SERVER['PHP_SELF'],"config","plugins");
	
			echo "<div align='center'>";
			echo "<table class='tab_cadre' cellpadding='5'>";
			echo "<tr><th>".$DATAINJECTIONLANG["setup"][1];
			echo "</th></tr>";
			echo "<tr class='tab_bg_1'><td>";
			echo "<a href='plugin_data_injection.install.php'>".$DATAINJECTIONLANG["setup"][3]."</a></td></tr>";
			echo "</table></div>";
}
else
{
	commonHeader($DATAINJECTIONLANG["config"][1], $_SERVER["PHP_SELF"],"plugins","data_injection");
		echo "<div align='center'>";
		echo "<form name=cas action=\"".$_SERVER["PHP_SELF"]."\" method=\"post\">";

		echo "<table class='tab_cadre' cellpadding='6'>";
		echo "<tr class='tab_bg_2'><th colspan='6'>" . $DATAINJECTIONLANG["setup"][1]."</th></tr></table>";
		echo "<br>";
		echo "<table class='tab_cadre' cellpadding='6'>";
		if (haveRight("profile","w")){
			echo "<tr class='tab_bg_2'><td colspan='5' align='center'><a href=\"../front/plugin_data_injection.profile.php\">".$DATAINJECTIONLANG["setup"][9]."</a></td/></tr>";
		}
		if (haveRight("config","w")){
			echo "<tr class='tab_bg_2'><td colspan='5' align='center'><a href=\"../front/plugin_data_injection.uninstall.php\">".$DATAINJECTIONLANG["setup"][5]."</a></td/></tr>";
		}
		echo "</table></form></div>";
}

commonFooter();
?>
