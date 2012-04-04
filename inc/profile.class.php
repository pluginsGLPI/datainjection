<?php
/*
 * @version $Id: HEADER 14684 2011-06-11 06:32:40Z remi $
 LICENSE

 This file is part of the order plugin.

 Datainjection plugin is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Datainjection plugin is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with datainjection. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 @package   datainjection
 @author    the datainjection plugin team
 @copyright Copyright (c) 2010-2011 Order plugin team
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://forge.indepnet.net/projects/datainjection
 @link      http://www.glpi-project.org/
 @since     2009
 ---------------------------------------------------------------------- */
 
class PluginDatainjectionProfile extends CommonDBTM {

   //if profile deleted
   function cleanProfiles($ID) {
      $profile = new self();
      $profile->deleteByCriteria(array('id' => $ID));
   }


   function showForm($ID){
      global $LANG;

      if (!Session::haveRight("profile","r"))  {
         return false;
      }

      $canedit = Session::haveRight("profile", "w");

      if ($ID) {
         $this->getFromDB($ID);
      }

      $profile = new Profile;
      $profile->getFromDB($ID);

      echo "<form action='".Toolbox::getItemTypeFormURL(get_class($this))."' method='post'>";
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
