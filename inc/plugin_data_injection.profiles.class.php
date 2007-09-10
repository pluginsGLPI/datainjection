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

class DataInjectionProfile extends CommonDBTM {
	
	function DataInjectionProfile()
	{
		$this->table="glpi_plugin_data_injection_profiles";
    	$this->type=-1;
    	$this->text="";
	}

	function getFromDBForUser($ID){

		// Make new database object and fill variables
		global $DB;
		$ID_profile=0;
		// Get user profile
		$query = "SELECT FK_profiles FROM glpi_users_profiles WHERE (FK_users = '$ID')";

		if ($result = $DB->query($query)) {
			if ($DB->numrows($result)){
				$ID_profile = $DB->result($result,0,0);
			}
		}
		if ($ID_profile){
			return $this->getFromDB($ID_profile);
		} else return false;
	}

	function showDataInjectionForm($target,$ID){
		global $LANG,$DATAINJECTIONLANG;

		if (!haveRight("profile","r")) return false;
		$canedit=haveRight("profile","w");

		if ($ID){
			$this->getFromDB($ID);
		} else {
			$this->getEmpty();
		}

		echo "<form name='form' method='post' action=\"$target\">";
		echo "<table class='tab_cadre'><tr>";

		echo "<tr><th colspan='2' align='center'><strong>".$DATAINJECTIONLANG["setup"][9]."</strong></td></tr>";

		echo "<tr class='tab_bg_2'>";
		echo "<td>".$DATAINJECTIONLANG["profiles"][1].":</td><td>";
		dropdownNoneReadWrite("create_model",$this->fields["create_model"],1,0,1);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_2'>";
		echo "<td>".$DATAINJECTIONLANG["profiles"][3].":</td><td>";
		dropdownNoneReadWrite("use_model",$this->fields["use_model"],1,1,0);
		echo "</td>";
		echo "</tr>";

		echo "</tr>";

		if ($canedit){
			echo "<tr class='tab_bg_1'>";
			if ($ID){
				echo "<td  align='center'>";
				echo "<input type='hidden' name='ID' value=$ID>";
				echo "<input type='submit' name='update' value=\"".$LANG["buttons"][7]."\" class='submit'>";
				echo "</td><td  align='center'>";
				echo "<input type='submit' name='delete' value=\"".$LANG["buttons"][6]."\" class='submit'>";
			} else {
				echo "<td colspan='2' align='center'>";
				echo "<input type='submit' name='add' value=\"".$LANG["buttons"][8]."\" class='submit'>";
			}
			echo "</td></tr>";
		}
		echo "</table>";
	echo "</form>";

	}
}
	
?>
