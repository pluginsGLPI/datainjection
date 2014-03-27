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

class PluginDatainjectionMapping extends CommonDBTM {

   static $rightname = "plugin_datainjection_model";

   /**
    * @param $field
    * @param $value
   **/
   function equal($field,$value) {

      if (!isset($this->fields[$field])) {
         return false;
      }

      if ($this->fields[$field] == $value) {
         return true;
      }

      return false;
   }
   
   
   function getMappingName() {
      return $this->fields["name"];
   }


   function getRank() {
      return $this->fields["rank"];
   }


   function isMandatory() {
      return $this->fields["is_mandatory"];
   }


   function getValue() {
      return $this->fields["value"];
   }


   function getID() {
      return $this->fields["id"];
   }


   function getItemtype() {
      return $this->fields["itemtype"];
   }
   

   /**
    * @param $model  PluginDatainjectionModel object
   **/
   static function showFormMappings(PluginDatainjectionModel $model) {
      global $CFG_GLPI;

      $canedit = $model->can($model->fields['id'], UPDATE);

      if (isset($_SESSION['datainjection']['lines'])) {
         $lines = unserialize($_SESSION['datainjection']['lines']);
      } else {
         $lines = array();
      }

      echo "<form method='post' name=form action='".Toolbox::getItemTypeFormURL(__CLASS__)."'>";

      //Display link to the preview popup
      if (isset($_SESSION['datainjection']['lines']) && !empty($lines)) {
         $nblines = $_SESSION['datainjection']['nblines'];
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr class='tab_bg_1'><td class='center'>";
         $url = $CFG_GLPI["root_doc"].
                "/plugins/datainjection/front/popup.php?popup=preview&amp;models_id=".
                $model->getID();
         echo "<a href=#  onClick=\"var w = window.open('$url' , 'glpipopup', ".
                "'height=400, width=600, top=100, left=100, scrollbars=yes' );w.focus();\"/>";
         echo __('See the file', 'datainjection')."</a>";
         echo "</td></tr>";
      }

      echo "<table class='tab_cadre_fixe'>";
      echo "<tr>";
      echo "<th>" . __('Header of the file', 'datainjection') . "</th>";
      echo "<th>" . __('Tables', 'datainjection') . "</th>";
      echo "<th>" . _n('Field', 'Fields', 2) . "</th>";
      echo "<th>" . __('Link field', 'datainjection') . "</th>";
      echo "</tr>";

      $model->loadMappings();

      foreach ($model->getMappings() as $mapping) {
         $mapping->fields = Toolbox::stripslashes_deep($mapping->fields);
         $mappings_id     = $mapping->getID();
         echo "<tr class='tab_bg_1'>";
         echo "<td class='center'>".$mapping->fields['name']."</td>";
         echo "<td class='center'>";
         $options = array('primary_type' => $model->fields['itemtype']);
         PluginDatainjectionInjectionType::dropdownLinkedTypes($mapping, $options);
         echo "</td>";
         echo "<td class='center'><span id='span_field_$mappings_id'>";
         echo "</span></td>";
         echo "<td class='center'><span id='span_mandatory_$mappings_id'></span></td>";
      }

      if ($canedit) {
         echo "<tr> <td class='tab_bg_2 center' colspan='4'>";
         echo "<input type='hidden' name='models_id' value='".$model->fields['id']."'>";
         echo "<input type='submit' name='update' value='"._sx('button', 'Save')."' class='submit'>";
         echo "</td></tr>";
      }
      echo "</table>";
      Html::closeForm();
   }


   /**
    * For multitext only ! Check it there's more than one value to inject in a field
    *
    * @param $models_id the model ID
    *
    * @return true if more than one value to inject, false if not
   **/
   static function getSeveralMappedField($models_id) {
      global $DB;

      $several = array();
      $query  = "SELECT `value`,
                        COUNT(*) AS counter
                 FROM `glpi_plugin_datainjection_mappings`
                 WHERE `models_id` = '".$models_id."'
                       AND `value` NOT IN ('none')
                 GROUP BY `value`
                 HAVING `counter` > 1";

      foreach ($DB->request($query) as $mapping) {
         $several[] = $mapping['value'];
      }
      return $several;
   }


   /**
    * @param $models_id
   **/
   static function getMappingsSortedByRank($models_id) {
     global $DB;

     $mappings = array();
     $query    = "SELECT `name`
                  FROM `glpi_plugin_datainjection_mappings`
                  WHERE `models_id` = '".$models_id."'
                  ORDER BY `rank` ASC";
     foreach($DB->request($query) as $data) {
        $mappings[] = $data['name'];
     }
     return $mappings;
   }

}
?>