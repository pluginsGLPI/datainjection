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

class PluginDatainjectionProfile extends Profile {

   static $rightname = "profile";

   static function getAllRights() {
      $rights = array(
          array('itemtype'  => 'PluginDatainjectionModel',
                'label'     => __('Model management', 'datainjection'),
                'field'     => 'plugin_datainjection_model'),
          array('itemtype'  => 'PluginDatainjectionModel',
                'label'     => __('Injection of the file', 'datainjection'),
                'field'     => 'plugin_datainjection_use', 
                'rights'    => array(READ => __('Read'))));
      return $rights;
   }
   
   /**
    * Clean profiles_id from plugin's profile table
    *
    * @param $ID
   **/
   function cleanProfiles($ID) {
      global $DB;
      $query = "DELETE FROM `glpi_profiles` 
                WHERE `profiles_id`='$ID' 
                   AND `name` LIKE '%plugin_datainjection%'";
      $DB->query($query);
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
         $profile = new self();
         $ID   = $item->getField('id');
         //In case there's no right datainjection for this profile, create it
         self::addDefaultProfileInfos($item->getID(), 
                                      array('plugin_datainjection_model' => 0));
         $profile->showForm($ID);
      }
      return true;
   }


   /**
    * @param $profile
   **/
   static function addDefaultProfileInfos($profiles_id, $rights) {
      $profileRight = new ProfileRight();
      foreach ($rights as $right => $value) {
         if (!countElementsInTable('glpi_profilerights',
                                   "`profiles_id`='$profiles_id' AND `name`='$right'")) {
            $myright['profiles_id'] = $profiles_id;
            $myright['name']        = $right;
            $myright['rights']      = $value;
            $profileRight->add($myright);

            //Add right to the current session
            $_SESSION['glpiactiveprofile'][$right] = $value;
         }
      }
   }

   /**
    * @param $ID  integer
    */
   static function createFirstAccess($profiles_id) {
      include_once(GLPI_ROOT."/plugins/datainjection/inc/profile.class.php");
      foreach (self::getAllRights() as $right) {
         self::addDefaultProfileInfos($profiles_id, 
                                    array('plugin_datainjection_model' => ALLSTANDARDRIGHT, 
                                          'plugin_datainjection_use' => READ));
      }
   }


   static function migrateProfiles() {
      global $DB;
      if (!TableExists('glpi_plugin_datainjection_profiles')) {
         return true;
      } 

      $profiles = getAllDatasFromTable('glpi_plugin_datainjection_profiles');
      foreach ($profiles as $id => $profile) {
         $query = "SELECT `id` FROM `glpi_profiles` WHERE `name`='".$profile['name']."'";
         $result = $DB->query($query);
         if ($DB->numrows($result) == 1) {
            $id = $DB->result($result, 0, 'id');
            switch ($profile['model']) {
               case 'r' :
                  $value = READ;
                  break;
               case 'w':
                  $value = ALLSTANDARDRIGHT;
                  break;
               case 0:
               default:
                  $value = 0;
                  break;
            }
            self::addDefaultProfileInfos($id, array('plugin_datainjection_model' => $value));
            if ($value > 0) {
               self::addDefaultProfileInfos($id, array('plugin_datainjection_use' => READ));
            } else {
               self::addDefaultProfileInfos($id, array('plugin_datainjection_model' => 0));
            }
         }
      }
   }
   
    /**
    * Show profile form
    *
    * @param $items_id integer id of the profile
    * @param $target value url of target
    *
    * @return nothing
    **/
   function showForm($profiles_id=0, $openform=TRUE, $closeform=TRUE) {

      echo "<div class='firstbloc'>";
      if (($canedit = Session::haveRightsOr(self::$rightname, array(CREATE, UPDATE, PURGE)))
          && $openform) {
         $profile = new Profile();
         echo "<form method='post' action='".$profile->getFormURL()."'>";
      }

      $profile = new Profile();
      $profile->getFromDB($profiles_id);

      $rights = self::getAllRights();
      $profile->displayRightsChoiceMatrix(self::getAllRights(), 
                                          array('canedit'       => $canedit,
                                                'default_class' => 'tab_bg_2',
                                                'title'         => __('General')));
      if ($canedit
          && $closeform) {
         echo "<div class='center'>";
         echo Html::hidden('id', array('value' => $profiles_id));
         echo Html::submit(_sx('button', 'Save'), array('name' => 'update'));
         echo "</div>\n";
         Html::closeForm();
      }
      echo "</div>";
   }
}
