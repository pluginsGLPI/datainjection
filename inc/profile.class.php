<?php
/*
 * @version $Id: HEADER 14684 2011-06-11 06:32:40Z remi $
 LICENSE

 This file is part of the datainjection plugin.

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
 @copyright Copyright (c) 2010-2013 Datainjection plugin team
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://forge.indepnet.net/projects/datainjection
 @link      http://www.glpi-project.org/
 @since     2009
 ---------------------------------------------------------------------- */

class PluginDatainjectionProfile extends CommonDBTM {


   static function canCreate() {
      return Session::haveRight('profile', 'w');
   }


   static function canView() {
      return Session::haveRight('profile', 'r');
   }


   /**
    * Clean profiles_id from plugin's profile table
    *
    * @param $ID
   **/
   function cleanProfiles($ID) {

      $profile = new self();
      $profile->deleteByCriteria(array('id' => $ID));
   }


   static function changeProfile() {

      $prof = new self();
      if ($prof->getFromDB($_SESSION['glpiactiveprofile']['id'])) {
         $_SESSION["glpi_plugin_datainjection_profile"] = $prof->fields;
      } else {
         unset ($_SESSION["glpi_plugin_datainjection_profile"]);
      }
   }


   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->getType() == 'Profile') {
         if ($item->getField('interface') == 'central') {
            return __('File injection', 'datainjection');
         }
         return '';
      }
      return '';
   }


   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getType() == 'Profile') {
         $prof = new self();
         $ID   = $item->getField('id');
         if (!$prof->GetfromDB($ID)) {
            $prof->createUserAccess($item);
         }
         $prof->showForm($ID);
      }
      return true;
   }


   /**
    * @param $profile
   **/
   function createUserAccess($profile) {

      return $this->add(array('id'      => $profile->getField('id'),
                              'name' => addslashes($profile->getField('name'))));
   }


   /**
    * @param $ID  integer
    */
   static function createFirstAccess($ID) {

      include_once(GLPI_ROOT."/plugins/datainjection/inc/profile.class.php");
      $firstProf = new self();
      if (!$firstProf->GetfromDB($ID)) {
         $profile = new Profile();
         $profile->getFromDB($ID);
         $name    = addslashes($profile->fields["name"]);

         $firstProf->add(array('id'          => $ID,
                               'profile'     => $name,
                               'is_default'  => 0,
                               'model'       => 'w'));
      }
   }


   function showForm($ID, $options=array()){

      if (!Session::haveRight("profile","r"))  {
         return false;
      }

      $profile = new Profile();

      if ($ID){
         $this->getFromDB($ID);
         $profile->getFromDB($ID);
      } else {
         $this->getEmpty();
      }

      $options['colspan'] = 1;
      $this->showFormHeader($options);

      echo "<tr><th colspan='2'>".sprintf(__('%1$s %2$s'), __('File injection', 'datainjection'),
                                          $profile->fields["name"]);
      echo "</th></tr>";

      echo "<tr class='tab_bg_2'>";
      echo "<td>".PluginDatainjectionModel::getTypeName()."</td><td>";
      Profile::dropdownNoneReadWrite("model", $this->fields["model"], 1, 1, 1);
      echo "</td></tr>";

      echo "<input type='hidden' name='id' value=".$this->fields["id"].">";

      $options['candel'] = false;
      $this->showFormButtons($options);
   }

}
?>