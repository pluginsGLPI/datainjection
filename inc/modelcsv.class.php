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
   public $itemtype = 'PluginDatainjectionModel';
   public $items_id = 'models_id';
   public $dohistory = true;
   public $model = null;

   function getEmpty() {
      $this->fields['delimiter'] = ';';
      $this->fields['is_header_present'] = 1;
   }

   function canCreate() {
      return plugin_datainjection_haveRight('model','w');
   }

   function canView() {
      return plugin_datainjection_haveRight('model','r');
   }

   function init() {
   }

   //---- Getters -----//

   function getDelimiter()
   {
      return $this->fields["delimiter"];
   }

   function isHeaderPresent()
   {
      return $this->fields["is_header_present"];
   }

   //---- Save -----//
   function setDelimiter($delimiter)
   {
      $this->fields["delimiter"] = $delimiter;
   }

   function setHeaderPresent($present)
   {
      $this->fields["is_header_present"] = $present;
   }

   /**
    * Check if filename ends with .csv
    * @param the filename
    * @return boolean true if name is correct, false is not
    */
   function checkFileName($filename) {
      return ( !strstr(strtolower(substr($filename,strlen($filename)-4)), '.csv'));
   }

   /**
    * Get CSV's specific ID for a model
    * If row doesn't exists, it creates it
    * @param models_id the model ID
    * @return the ID of the row in glpi_plugin_datainjection_modelcsv
    */
   function getFromDBByModelID($models_id,$store_model=false) {
      global $DB;
      $query = "SELECT `id` FROM `".$this->getTable()."` WHERE `models_id`='$models_id'";
      $results = $DB->query($query);
      $id = 0;
      if ($DB->numrows($results) > 0) {
         $id = $DB->result($results,0,'id');
         $this->getFromDB($id);
      }
      else {
         $this->getEmpty();
         $tmp = $this->fields;
         $tmp['models_id'] = $models_id;
         $id = $this->add($tmp);
         $this->getFromDB($id);
      }

      if ($store_model) {
         $model = new PluginDatainjectionModel;
         $model->getFromDB($models_id);
         $this->model = $model;
      }
      return $id;
   }

   function showForm($models_id,$options=array()) {
      global $LANG;

      $id = $this->getFromDBByModelID($models_id);
      $canedit=$this->can($id,'w');

      echo "<form method='post' name=form action='".getItemTypeFormURL(__CLASS__)."'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr><th colspan='4'>".$LANG["datainjection"]["model"][29]."</th></tr>";

      echo "<tr class='tab_bg_1'>";

      echo "<td>".$LANG["datainjection"]["model"][9].": </td>";
      echo "<td>";
      Dropdown::showYesNo('is_header_present',$this->isHeaderPresent());
      echo "</td>";
      echo "<td>".$LANG["datainjection"]["model"][10].": </td>";
      echo "<td>";
      echo "<input type='text' size='1' name='delimiter' value='".$this->getDelimiter()."'";
      echo "</td>";
      echo "</tr>";

      if ($canedit) {
         echo "<tr>";
         echo "<td class='tab_bg_2 center' colspan='4'>";
         echo "<input type='hidden' name='id' value='$id'>";
         echo "<input type='hidden' name='models_id' value='$models_id'>";
         echo "<input type='submit' name='update' value=\"".$LANG['buttons'][7]."\" class='submit' >";
         echo "</td></tr>";
         echo "</table></form>";
      } else {
         echo "</table>";
      }
   }


}
?>
