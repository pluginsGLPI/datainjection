<?php
/*
 * @version $Id: SoftwareVersioninjection.class.php 614 2011-11-06 17:55:33Z walid $
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
 along with datainjection; along with Behaviors. If not, see <http://www.gnu.org/licenses/>.
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
 
if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/// SoftwareVersion class
class PluginDatainjectionSoftwareVersionInjection extends SoftwareVersion
                                                  implements PluginDatainjectionInjectionInterface {


   function __construct() {
      $this->table = getTableForItemType(get_parent_class($this));
   }


   function isPrimaryType() {
      return true;
   }


   function connectedTo() {
      return array('Software');
   }


   function getOptions($primary_type = '') {
      global $LANG;


      $tab = Search::getOptions(get_parent_class($this));
      $options['ignore_fields'] = array();
      $options['displaytype']   = array("dropdown"       => array(4,31),
                                        "multiline_text" => array(16));

      if ($primary_type == 'SoftwareVersion') {
         $tab[100]['name']        = $LANG['help'][31];
         $tab[100]['field']       = 'name';
         $tab[100]['table']       = getTableForItemType('Software');
         $tab[100]['linkfield']   = 'softwares_id';
         $tab[100]['displaytype'] = 'text';
         $tab[100]['injectable']  = true;
      }
      
      return PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
   }


   function showAdditionalInformation($info=array(), $option=array()) {

      $name = "info[".$option['linkfield']."]";
      switch ($option['displaytype']) {
         case 'computer' :
            Dropdown::show('Computer', array('name'    => $name,
                                             'comment' => true,
                                             'entity'  => $_SESSION['glpiactive_entity']));
            break;

         default :
            break;
      }
   }


   /**
    * Standard method to add an object into glpi
    * WILL BE INTEGRATED INTO THE CORE IN 0.80
    *
    * @param values fields to add into glpi
    * @param options options used during creation
    *
    * @return an array of IDs of newly created objects : for example array(Computer=>1, Networkport=>10)
   **/
   function addOrUpdateObject($values=array(), $options=array()) {

      $lib = new PluginDatainjectionCommonInjectionLib($this, $values, $options);
      $lib->processAddOrUpdate();
      return $lib->getInjectionResults();
   }


   function addSpecificMandatoryFields() {
      return array('softwares_id'=>1);
   }


   function getValueForAdditionalMandatoryFields($fields_toinject=array()) {
      global $DB;
 
      if (!isset($fields_toinject['SoftwareVersion']['softwares_id'])) {
         return $fields_toinject;
      }

      $query = "SELECT `id`
                FROM `glpi_softwares`
                WHERE `name` = '".$fields_toinject['SoftwareLicense']['softwares_id']."'";
      $query .= getEntitiesRestrictRequest(" AND", "glpi_softwares", "entities_id", 
                                           $_SESSION['glpiactive_entity'], true);
      $result = $DB->query($query);

      if ($DB->numrows($result) > 0) {
         $id = $DB->result($result,0,'id');
         //Add softwares_id to the array
         $fields_toinject['SoftwareVersion']['softwares_id'] = $id;

      } else {
         //Remove software name
         unset($fields_toinject['SoftwareVersion']['softwares_id']);
      }

      return $fields_toinject;
   }
   
   function addSpecificNeededFields($primary_type,$values) {
      $fields['softwares_id'] = $values[$primary_type]['id'];
      return $fields;
   }
}

?>