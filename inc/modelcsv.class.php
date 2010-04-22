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
class PluginDatainjectionModelcsv extends PluginDatainjectionModel {
   var $specific_fields;

   function getEmpty() {
      $this->fields['delimiter'] = ';';
      $this->fields['header_present'] = 1;
   }

   function canCreate() {
      return plugin_datainjection_haveRight('model','w');
   }

   function canView() {
      return plugin_datainjection_haveRight('model','r');
   }

   function init()
   {
      $this->specific_fields = array();
   }
/*
   //---- Load -----//
   function loadSpecificfields()
   {
      global $DB;
      $sql = "SELECT * FROM glpi_plugin_datainjection_modelcsvs WHERE model_id = ".$this->fields["ID"];
      $result = $DB->query($sql);
      if ($DB->numrows($result) > 0)
         $this->specific_fields = $DB->fetch_array($result);
      else
         $this->specific_fields = array();
   }

   //---- Save -----//
   function updateSpecificFields()
   {
      global $DB;

      $sql = "UPDATE glpi_plugin_datainjection_modelcsvs SET delimiter='".$this->specific_fields["delimiter"]
      ."' , header_present=".$this->specific_fields["header_present"]." WHERE model_id=".$this->fields["ID"];
      $DB->query($sql);
   }


   //---- Save -----//
   function saveSpecificFields()
   {
      global $DB;
      $sql = "INSERT INTO glpi_plugin_datainjection_modelcsvs (`delimiter`,`header_present`,`model_id`) VALUES ('".$this->specific_fields["delimiter"]
      ."',".$this->specific_fields["header_present"].",".$this->fields["ID"].")";

      $DB->query($sql);

      //Reload specific_fields with the ID set
      $this->loadSpecificfields();
   }


   function deleteSpecificFields()
   {
      global $DB;
      $sql = "DELETE FROM glpi_plugin_datainjection_modelcsvs WHERE model_id=".$this->fields["ID"];
      $DB->query($sql);
   }
*/
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
/*
   function setFields($fields,$entity,$user_id)
   {
      parent::setFields($fields,$entity,$user_id);
      if(isset($fields["dropdown_header"]))
         $this->setHeaderPresent($fields["dropdown_header"]);

      if(isset($_POST["delimiter"]))
         $this->setDelimiter(stripslashes($fields["delimiter"]));
   }
*/

   /*
    * Get CSV's specific ID for a model
    * If row doesn't exists, it creates it
    * @param models_id the model ID
    * @return the ID of the row in glpi_plugin_datainjection_modelcsv
    */
   function getFromDBByModelID($models_id) {
      global $DB;
      $query = "SELECT `id` FROM `".$this->getTable()."` WHERE `models_id`='$models_id'";
      $results = $DB->query($query);
      if ($DB->numrows($results) > 0) {
         return $DB->result($results,0,'id');
      }
      else {
         $csv = new PluginDatainjectionModelcsv;
         $tmp['models_id'] = $models_id;
         return $csv->add($tmp);
      }
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
         echo "<input type='submit' name='update' value=\"".$LANG['buttons'][7]."\" class='submit' >";
         echo "</td></tr>";
         echo "</table></form>";
      } else {
         echo "</table>";
      }
   }

   function checkUploadedFile($file_encoding) {
      global $LANG;
      $name_file = $_FILES["file"]["name"];
      $tmpfname = tempnam (realpath(PLUGIN_DATAINJECTION_UPLOAD_DIR), "Tmp");
      $tmp_file = $_FILES["file"]["tmp_name"];

      if( !strstr(strtolower(substr($name_file,strlen($name_file)-4)), '.csv') ) {
         $message = $LANG["datainjection"]["fileStep"][5];
         $message.="<br />".$LANG["datainjection"]["fileStep"][6]." csv ";
         $message.=$LANG["datainjection"]["fileStep"][7];
         addMessageAfterRedirect($message,true,ERROR,false);
         unlink($tmpfname);
      }
      else {
         if( !move_uploaded_file($tmp_file, $tmpfname) ) {
            addMessageAfterRedirect($LANG["datainjection"]["fileStep"][8],true,ERROR,false);
            unlink($tmpfname);
         }
         else {
               $backend = new PluginDatainjectionBackendcsv;
               $backend->init($tmpfname,$this->getDelimiter(),$file_encoding);
               $backend->read();
               $backend->deleteFile();
               logDebug($backend);
               //$ok = $backend->isFileCorrect($this);
         }
      }
   }
}
?>
