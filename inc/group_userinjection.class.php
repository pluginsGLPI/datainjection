<?php
/*
 * @version $Id$
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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginDatainjectionGroup_UserInjection extends Group_User
                                             implements PluginDatainjectionInjectionInterface {


   static function getTypeName($nb=0) {
      return sprintf(__('%1$s - %2$s'), __('Group'), __('User'));
   }


   static function getTable() {

      $parenttype = get_parent_class();
      return $parenttype::getTable();

   }

   function isPrimaryType() {
      return false;
   }


   function connectedTo() {
      //return array();
      return array('Group');
   }


   /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
   **/
   function getOptions($primary_type='') {

      $tab                    = Search::getOptions(get_parent_class($this));

      $tab[3]['checktype']    = 'bool';
      $tab[3]['displaytype']  = 'bool';

      $tab[4]['checktype']    = 'text';
      $tab[4]['displaytype']  = 'dropdown';

      //Remove some options because some fields cannot be imported
      $blacklist     = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
      $notimportable = array(4);

      $options['ignore_fields'] = array_merge($blacklist, $notimportable);
      $options['displaytype']   = array("bool"    => array(3, 6, 7),
                                        "dropdown" => array(4));

      return PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
   }


   /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::addOrUpdateObject()
   **/
   function addOrUpdateObject($values=array(), $options=array()) {

      $lib = new PluginDatainjectionCommonInjectionLib($this, $values, $options);
      $lib->processAddOrUpdate();
      return $lib->getInjectionResults();
   }


   /**
    * @param $primary_type
    * @param $values
   **/
   function addSpecificNeededFields($primary_type, $values) {

      if ($primary_type == "User") {
         $fields['users_id'] = $values['User']['id'];
      } else if ($primary_type == "Group") {
         $fields['users_id']  = $values['Group_User']['users_id'];
         $fields['groups_id'] = $values['Group']['id'];
      }

      return $fields;
   }

}
?>