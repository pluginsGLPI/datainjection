<?php
/*
 * @version $Id: HEADER 14683 2011-06-11 06:26:34Z remi $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2011 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

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
 along with GLPI; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Dévi Balpe
// Purpose of file:
// ----------------------------------------------------------------------

class PluginDatainjectionProfile extends CommonDBTM {

   //if profile deleted
   function cleanProfiles($ID) {
      global $DB;

      $query = "DELETE
                FROM `glpi_plugin_datainjection_profiles`
                WHERE `id` = '$ID' ";
      $DB->query($query);
   }


   function showForm($ID){
      global $LANG;

      if (!haveRight("profile","r"))  {
         return false;
      }

      $canedit = haveRight("profile", "w");

      if ($ID) {
         $this->getFromDB($ID);
      }

      $profile = new Profile;
      $profile->getFromDB($ID);

      echo "<form action='".getItemTypeFormURL(get_class($this))."' method='post'>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr><th colspan='2'>".$LANG['datainjection']['name'][1]." ".$profile->fields["name"];
      echo "</th></tr>";

      echo "<tr class='tab_bg_2'>";
      echo "<td>".$LANG['datainjection']['profiles'][1]."&nbsp;:</td><td>";
      Profile::dropdownNoneReadWrite("model", $this->fields["model"], 1, 1, 1);
      echo "</td></tr>";

      if ($canedit){
         echo "<tr class='tab_bg_1'>";
         echo "<td class='center' colspan='2'>";
         echo "<input type='hidden' name='id' value=$ID>";
         echo "<input type='submit' name='update_user_profile' value='".$LANG['buttons'][7]."'
                class='submit'>";
         echo "</td></tr>";
      }

      echo "</table></form>";
   }

}

?>
