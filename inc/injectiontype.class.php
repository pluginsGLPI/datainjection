<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

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
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */
class PluginDatainjectionInjectionType {

   /**
    * Display a list of all importable types using datainjection plugin
    * @param value the selected value
    * @return nothing
    */
   static function dropdown($value='',$only_primary=false) {
      global $INJECTABLE_TYPES;

      $values = array();
      foreach ($INJECTABLE_TYPES as $type => $plugin) {
         $injectionclass = new $type();
         if (!$only_primary || ($only_primary && $injectionclass->isPrimaryType())) {
            $typename = PluginDatainjectionInjectionType::getParentObjectName($type);
            $values[$typename] = call_user_func(array($type,'getTypeName'));
         }
      }
      asort($values);
      return Dropdown::showFromArray('itemtype',$values,array('value'=>$value));
   }

   /**
    * Get all types linked with a primary type
    * @param primary_type
    * @param value
    */
   static function dropdownLinkedTypes(PluginDatainjectionMapping $mapping, $primary_type,$value='') {
      global $INJECTABLE_TYPES,$LANG,$CFG_GLPI;

      $mappings_id = $mapping->fields['id'];
      $values = array();

      //Add null value
      $values['NULL'] = $LANG["datainjection"]["mapping"][6];

      //Add primary_type to the list of availables types
      $type = new $primary_type;
      $values[$primary_type] = $type->getTypeName();

      foreach ($INJECTABLE_TYPES as $type => $plugin) {
         $injectionclass = new $type();
         $connected_to = $injectionclass->connectedTo();
         if (in_array($primary_type,$connected_to)) {
            $typename = PluginDatainjectionInjectionType::getParentObjectName($type);
            $values[$typename] = call_user_func(array($type,'getTypeName'));
         }
      }
      asort($values);

      $rand = Dropdown::showFromArray("data[itemtype[$mappings_id]]",
                                      $values,
                                      array('value'=>$value));
      $params=array('itemtype'=>'__VALUE__',
                    'primary_type'=>$primary_type,
                    'value'=>$mapping->getValue(),
                    'mappings_id'=>$mappings_id,
                    'mandatory'=>$mapping->isMandatory());
      $url = $CFG_GLPI["root_doc"]."/plugins/datainjection/ajax/dropdownChooseField.php";
      ajaxUpdateItemOnSelectEvent("dropdown_data[itemtype[$mappings_id]]$rand",
                                  "span_field_$mappings_id",
                                  $url,$params);
      return $rand;
   }

   static function getParentObjectName($injectionclass='') {
      preg_match("/PluginDatainjection(.*)Injection/",$injectionclass,$results);
      return ucfirst($results[1]);
   }

   static function dropdownFields($options=array()) {
      global $LANG,$CFG_GLPI;

      $blacklisted_fields = array('id','date_mod');

      //logDebug($options);
      $p['itemtype'] = false;
      $p['value'] = '';
      $p['mappings_id'] = 0;
      $p['mandatory'] = 0;
      $p['primary_type'] = '';
      foreach ($options as $key => $value) {
         $p[$key] = $value;
      }

      if ($p['itemtype'] != 'NULL') {
         $search_options = array();
         $typename = 'PluginDatainjection'.$p['itemtype'].'Injection';
         $type = new $typename();
         $search_options = $type->getOptions();

         $fields = array();
         $fields['NULL'] = $LANG["datainjection"]["mapping"][7];

         foreach ($search_options as $option) {
            //If it's a real option (not a group label) and if field is not blacklisted
            if (is_array($option) && !in_array($option['field'],$blacklisted_fields)) {
               $fields[$option['field']] = $option['name'];
            }
         }

         asort($fields);
         $rand = Dropdown::showFromArray("data[field[".$p['mappings_id']."]]",
                                         $fields,
                                         array('value'=>$value));
         $params=array('field'=>'__VALUE__',
                       'mappings_id'=>$p['mappings_id'],
                       'mandatory'=>$p['mandatory'],
                       'itemtype'=>$p['itemtype'],
                       'primary_type'=>$p['primary_type']);
         $url = $CFG_GLPI["root_doc"]."/plugins/datainjection/ajax/dropdownMandatory.php";
         ajaxUpdateItemOnSelectEvent("dropdown_data[field[".$p['mappings_id']."]]$rand",
                                     "span_mandatory_".$p['mappings_id'],
                                     $url,$params);
      }
   }
}
?>