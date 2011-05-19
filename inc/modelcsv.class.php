<?php
/*
 * @version $Id: rules.constant.php 5351 2007-08-07 11:57:46Z walid $
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
class PluginDatainjectionModelcsv extends CommonDBChild {
   var $specific_fields;

   // From CommonDBChild
   public $itemtype  = 'PluginDatainjectionModel';
   public $items_id  = 'models_id';
   public $dohistory = true;


   function getEmpty() {

      $this->fields['delimiter']         = ';';
      $this->fields['is_header_present'] = 1;
   }


   function canCreate() {
      return plugin_datainjection_haveRight('model', 'w');
   }


   function canView() {
      return plugin_datainjection_haveRight('model', 'r');
   }


   function init() {
   }


   //---- Getters -----//

   function getDelimiter() {
      return $this->fields["delimiter"];
   }


   function isHeaderPresent() {
      return $this->fields["is_header_present"];
   }


   /**
    * If a Sample could be generated
    */
   function haveSample() {
      return $this->fields["is_header_present"];
   }

   /**
    * Display Sample
    */
   function showSample (PluginDatainjectionModel $model) {
      $headers = PluginDatainjectionMapping::getMappingsSortedByRank($model->fields['id']);
      $sample = '"'.implode('"'.$this->getDelimiter().'"', $headers)."\"\n";

      header('Content-disposition: attachment; filename="'.str_replace(' ', '_', $model->getName()).'.csv"');
      header('Content-Type: text/comma-separated-values');
      header('Content-Transfer-Encoding: UTF-8');
      header('Content-Length: '.mb_strlen($sample, 'UTF-8'));
      header('Pragma: no-cache');
      header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
      header('Expires: 0');
      echo $sample;
   }

   //---- Save -----//
   function setDelimiter($delimiter) {
      $this->fields["delimiter"] = $delimiter;
   }


   function setHeaderPresent($present) {
      $this->fields["is_header_present"] = $present;
   }


   /**
    * Check if filename ends with .csv
    *
    * @param the filename
    *
    * @return boolean true if name is correct, false is not
   **/
   function checkFileName($filename) {
      return ( !strstr(strtolower(substr($filename, strlen($filename)-4)), '.csv'));
   }


   /**
    * Get CSV's specific ID for a model
    * If row doesn't exists, it creates it
    *
    * @param models_id the model ID
    *
    * @return the ID of the row in glpi_plugin_datainjection_modelcsv
   **/
   function getFromDBByModelID($models_id) {
      global $DB;

      $query = "SELECT `id`
                FROM `".$this->getTable()."`
                WHERE `models_id` = '$models_id'";

      $results = $DB->query($query);
      $id = 0;

      if ($DB->numrows($results) > 0) {
         $id = $DB->result($results, 0, 'id');
         $this->getFromDB($id);

      } else {
         $this->getEmpty();
         $tmp = $this->fields;
         $tmp['models_id'] = $models_id;
         $id  = $this->add($tmp);
         $this->getFromDB($id);
      }

      return $id;
   }


   function showAdditionnalForm(PluginDatainjectionModel $model, $options=array()) {
      global $LANG;

      $id      = $this->getFromDBByModelID($model->fields['id']);
      $canedit = $this->can($id, 'w');

      echo "<tr><th colspan='4'>".$LANG['datainjection']['model'][29]."</th></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['datainjection']['model'][9]."&nbsp;: </td>";
      echo "<td>";
      Dropdown::showYesNo('is_header_present', $this->isHeaderPresent());
      echo "</td>";
      echo "<td>".$LANG['datainjection']['model'][10]."&nbsp;: </td>";
      echo "<td>";
      echo "<input type='text' size='1' name='delimiter' value='".$this->getDelimiter()."'";
      echo "</td>";
      echo "</tr>";
   }


   function saveFields($fields) {

      $csv                      = clone $this;
      $tmp['models_id']         = $fields['id'];
      $tmp['delimiter']         = $fields['delimiter'];
      $tmp['is_header_present'] = $fields['is_header_present'];
      $csv->getFromDBByModelID($fields['id']);
      $tmp['id']                = $csv->fields['id'];
      $csv->update($tmp);
   }

}
?>
