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

/// SoftwareLicense class
class PluginDatainjectionSoftwareLicenseInjection extends SoftwareLicense
                                                  implements PluginDatainjectionInjectionInterface {


   static function getTable() {

      $parenttype = get_parent_class();
      return $parenttype::getTable();
   }


   function isPrimaryType() {
      return true;
   }


   function connectedTo() {
      return array('Software');
   }


   /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
   **/
   function getOptions($primary_type='') {

      $tab                 = Search::getOptions(get_parent_class($this));

      $tab[8]['checktype'] = 'date';

      if ($primary_type == 'SoftwareLicense') {
         $tab[100]['name']          = _n('Software', 'Software', 1);
         $tab[100]['field']         = 'name';
         $tab[100]['table']         = getTableForItemType('Software');
         $tab[100]['linkfield']     = 'softwares_id';
         $tab[100]['displaytype']   = 'dropdown';
         $tab[100]['checktype']     = 'text';
         $tab[100]['injectable']    = true;

      }

      //Remove some options because some fields cannot be imported
      $blacklist     = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
      $notimportable = array();

      $options['ignore_fields'] = array_merge($blacklist, $notimportable);

      $key = array_search(2, $options['ignore_fields']);
      unset($options['ignore_fields'][$key]);

      $options['displaytype']   = array("dropdown"       => array(5, 6, 7, 110),
                                        "date"           => array(8),
                                        "multiline_text" => array(16),
                                        "software" => array(100));

      return PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
   }


   /**
    * @param $info      array
    * @param $option    array
   **/
   function showAdditionalInformation($info=array(), $option=array()) {

      $name = "info[".$option['linkfield']."]";

      switch ($option['displaytype']) {
         case 'computer' :
            Computer::dropdown(array('name'        => $name,
                                     'entity'      => $_SESSION['glpiactive_entity'],
                                     'entity_sons' => false));
            break;

         case 'software' :
            Software::dropdown(array('name'        => $name,
                                     'entity'      => $_SESSION['glpiactive_entity'],
                                     'entity_sons' => false));
            break;

         default :
            break;
      }
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
    * @param $fields_toinject    array
   **/
   function getValueForAdditionalMandatoryFields($fields_toinject=array()) {
      global $DB;

      if (!isset($fields_toinject['SoftwareLicense']['softwares_id'])) {
         return $fields_toinject;
      }

      $query = "SELECT `id`
                FROM `glpi_softwares`
                WHERE `name` = '".$fields_toinject['SoftwareLicense']['softwares_id']."'".
                      getEntitiesRestrictRequest(" AND", "glpi_softwares", "entities_id",
                                                 $fields_toinject['SoftwareLicense']['entities_id'],
                                                 true);
      $result = $DB->query($query);

      if ($DB->numrows($result) > 0) {
         $id = $DB->result($result,0,'id');
         //Add softwares_id to the array
         $fields_toinject['SoftwareLicense']['softwares_id'] = $id;

      } else {
         //Remove software id
         unset($fields_toinject['SoftwareLicense']['softwares_id']);
      }

      return $fields_toinject;
   }


   /**
    * @param $primary_type
    * @param $values
   **/
   function addSpecificNeededFields($primary_type, $values) {

      $fields = array();
      if ($primary_type == 'Software') {
         $fields['softwares_id'] = $values[$primary_type]['id'];
      }
      return $fields;
   }


   /**
    * @param $fields_toinject    array
    * @param $options            array
   **/
   function checkPresent($fields_toinject=array(), $options=array()) {

      if ($options['itemtype'] != 'SoftwareLicense') {
         return (" AND `softwares_id` = '".$fields_toinject['Software']['id']."'
                   AND `name` = '".$fields_toinject['SoftwareLicense']['name']."'");
      }
      return "";
   }

}
?>