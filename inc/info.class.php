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
class PluginDatainjectionInfo extends CommonDBTM {

   var $text;

   function getEmpty() {
      $this->fields['itemtype'] = PluginDatainjectionInjectionType::NO_VALUE;
      $this->fields['value'] = PluginDatainjectionInjectionType::NO_VALUE;
      $this->fields['is_mandatory'] = 0;
   }
   function isMandatory()
   {
      return $this->fields["is_mandatory"];
   }

   function getInfosText()
   {
      return $this->text;
   }

   function getValue()
   {
      return $this->fields["value"];
   }

   function getID()
   {
      return $this->fields["id"];
   }

   function getModelID()
   {
      return $this->fields["models_id"];
   }

   function getInfosType()
   {
      return $this->fields["itemtype"];
   }

   function setMandatory($mandatory)
   {
      $this->fields["is_mandatory"] = $mandatory;
   }

   function setInfosText($text)
   {
      $this->text = $text;
   }

   function setValue($value)
   {
      $this->fields["value"] = $value;
   }

   function setID($ID)
   {
      $this->fields["id"] = $ID;
   }

   function setModelID($model_id)
   {
      $this->fields["models_id"] = $model_id;
   }

   function setInfosType($type)
   {
      $this->fields["itemtype"] = $type;
   }

   static function showAddInfo(PluginDatainjectionModel $model,$canedit=false) {
      global $LANG, $DB,$CFG_GLPI;

      if ($canedit) {
         echo "<form method='post' name=form action='".getItemTypeFormURL(__CLASS__)."'>";
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr>";
         echo "<th>" . $LANG["datainjection"]["mapping"][3] . "</th>";
         echo "<th>" . $LANG["datainjection"]["mapping"][4] . "</th>";
         echo "<th>" . $LANG["datainjection"]["info"][5] . "</th>";
         echo "</tr>";
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         $infos_id = -1;
         $info = new PluginDatainjectionInfo;
         $info->fields['id'] = -1;
         $info->fields['models_id'] = $model->fields['id'];
         $info->getEmpty();

         $rand = PluginDatainjectionInjectionType::dropdownLinkedTypes($info,
                                                                       array('primary_type'=>
                                                                       $model->fields['itemtype'])
                                                                       );
         echo "</td>";
         echo "<td align='center'><span id='span_field_$infos_id'></span></td>";
         echo "<td align='center'><span id='span_mandatory_$infos_id'></span></td>";
         echo "</tr>";
         echo "<tr>";
         echo "<td class='tab_bg_2 center' colspan='4'>";
         echo "<input type='hidden' name='models_id' value='".$model->fields['id']."'>";
         echo "<input type='submit' name='update' value=\"".$LANG['buttons'][8]."\" class='submit' >";
         echo "</td></tr>";


         echo "</table></form><br/>";
     }

   }
   static function showFormInfos($models_id) {
      global $LANG, $DB,$CFG_GLPI;

      $model = new PluginDatainjectionModel;
      $canedit=$model->can($models_id,'w');

      PluginDatainjectionInfo::showAddInfo($model,$canedit);

      echo "<form method='post' name=form action='".getItemTypeFormURL(__CLASS__)."'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr>";
      echo "<th>" . $LANG["datainjection"]["mapping"][3] . "</th>";
      echo "<th>" . $LANG["datainjection"]["mapping"][4] . "</th>";
      echo "<th>" . $LANG["datainjection"]["info"][5] . "</th>";
      echo "</tr>";

      $model = new PluginDatainjectionModel;
      $model->getFromDB($models_id);
      $model->loadInfos();

      foreach ($model->getInfos() as $info) {
         $info->fields = stripslashes_deep($info->fields);
         $infos_id = $info->fields['id'];
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         $rand = PluginDatainjectionInjectionType::dropdownLinkedTypes($info,
                                                                       array('primary_type'=>
                                                                       $model->fields['itemtype'])
                                                                       );
         echo "</td>";
         echo "<td align='center'><span id='span_field_$infos_id'></span></td>";
         echo "<td align='center'><span id='span_mandatory_$infos_id'></span></td>";
      }

      if ($canedit) {
         echo "<tr>";
         echo "<td class='tab_bg_2 center' colspan='4'>";
         echo "<input type='hidden' name='models_id' value='$models_id'>";
         echo "<input type='submit' name='update' value=\"".$LANG['buttons'][7]."\" class='submit' >";
         echo "</td></tr>";
      }
      echo "</table></form>";
   }

   static function manageInfos($models_id, $infos = array()) {
      global $DB;
      $info = new PluginDatainjectionInfo;
      foreach ($_POST['data'] as $id => $info_infos) {
         $info_infos['id'] = $id;
         //If no field selected, reset other values
         if ($info_infos['value'] == PluginDatainjectionInjectionType::NO_VALUE) {
            $info_infos['itemtype'] = PluginDatainjectionInjectionType::NO_VALUE;
            $info_infos['is_mandatory'] = 0;
         }
         else {
            $info_infos['is_mandatory'] = (isset($info_infos['is_mandatory'])?1:0);
         }

         if ($id > 0) {
            $info->update($info_infos);
         }
         else {
            $info_infos['models_id'] = $models_id;
            unset($info_infos['id']);
            $info->add($info_infos);
         }
      }
      $query = "DELETE FROM `glpi_plugin_datainjection_infos`
                WHERE `models_id`='$models_id'
                  AND `value`='".PluginDatainjectionInjectionType::NO_VALUE."'";
      $DB->query($query);
   }

   static function showAdditionalInformationsForm($options=array()) {
      global $DB, $LANG;
      if (isset($options['models_id']) && $options['models_id']) {
         $infos = getAllDatasFromTable('glpi_plugin_datainjection_infos',
                                       "`models_id`='". $options['models_id']."'");

         echo "<table class='tab_cadre_fixe'>";

         if (count($infos)) {
            $info = new PluginDatainjectionInfo;

            echo "<tr>";
            echo "<th colspan='2'>" . $LANG["datainjection"]["info"][1]."</th>";
            echo "</tr>";

            foreach ($infos as $tmp) {
               $info->fields = $tmp;
               echo "<tr class='tab_bg_1'>";
               PluginDatainjectionInjectionCommon::displayAdditionalInformation($info);
               echo "</tr>";
            }
            echo "<tr class='tab_bg_1'>";
            echo "<td colspan='2' align='left'>".$LANG["datainjection"]["fillInfoStep"][3]."</td>";
            echo "</tr>";
         }

         //Show file selection
         echo "<th colspan='2'>" . $LANG["datainjection"]["tabs"][3]."</th>";
         echo "<tr class='tab_bg_1'>";
         echo "<td>" . $LANG["datainjection"]["fileStep"][3] . "</td>";
         echo "<td><input type='file' name='file' /></td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td colspan='2' align='center'>";
         echo "<input type='submit' class='submit' name='upload' value=\"".
                                                $LANG["datainjection"]["import"][0]."\"/></td>";
         echo "</tr>";
         echo "</table>";
      }
   }
}
?>