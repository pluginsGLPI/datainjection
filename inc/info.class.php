<?php
/*
 * @version $Id: HEADER 14684 2011-06-11 06:32:40Z remi $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2011 by the INDEPNET Development Team.

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
 along with GLPI; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 --------------------------------------------------------------------------
 */
class PluginDatainjectionInfo extends CommonDBTM {

   function getEmpty() {

      $this->fields['itemtype']     = PluginDatainjectionInjectionType::NO_VALUE;
      $this->fields['value']        = PluginDatainjectionInjectionType::NO_VALUE;
      $this->fields['is_mandatory'] = 0;
   }


   function isMandatory() {
      return $this->fields["is_mandatory"];
   }


   function getInfosText() {
      return $this->text;
   }


   function getValue() {
      return $this->fields["value"];
   }


   function getID() {
      return $this->fields["id"];
   }


   function getModelID() {
      return $this->fields["models_id"];
   }


   function getInfosType() {
      return $this->fields["itemtype"];
   }


   static function showAddInfo(PluginDatainjectionModel $model, $canedit=false) {
      global $LANG;

      if ($canedit) {
         echo "<form method='post' name=form action='".getItemTypeFormURL(__CLASS__)."'>";
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr>";
         echo "<th>" . $LANG['datainjection']['mapping'][3] . "</th>";
         echo "<th>" . $LANG['datainjection']['mapping'][4] . "</th>";
         echo "<th>" . $LANG['datainjection']['info'][5] . "</th>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td class='center'>";
         $infos_id = -1;
         $info     = new PluginDatainjectionInfo;
         $info->fields['id']        = -1;
         $info->fields['models_id'] = $model->fields['id'];
         $info->getEmpty();

         $rand = PluginDatainjectionInjectionType::dropdownLinkedTypes($info,
                                                                       array('primary_type'
                                                                           => $model->fields['itemtype'])
                                                                      );
         echo "</td>";
         echo "<td class='center'><span id='span_field_$infos_id'></span></td>";
         echo "<td class='center'><span id='span_mandatory_$infos_id'></span></td>";
         echo "</tr>";

         echo "<tr>";
         echo "<td class='tab_bg_2 center' colspan='4'>";
         echo "<input type='hidden' name='models_id' value='".$model->fields['id']."'>";
         echo "<input type='submit' name='update' value='".$LANG['buttons'][8]."' class='submit' >";
         echo "</td></tr>";


         echo "</table></form><br/>";
     }

   }


   static function showFormInfos(PluginDatainjectionModel $model) {
      global $LANG;

      $canedit = $model->can($model->fields['id'], 'w');

      PluginDatainjectionInfo::showAddInfo($model, $canedit);

      echo "<form method='post' name=form action='".getItemTypeFormURL(__CLASS__)."'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr>";
      echo "<th>" . $LANG['datainjection']['mapping'][3] . "</th>";
      echo "<th>" . $LANG['datainjection']['mapping'][4] . "</th>";
      echo "<th>" . $LANG['datainjection']['info'][5] . "</th>";
      echo "</tr>";

      $model->loadInfos();

      foreach ($model->getInfos() as $info) {
         $info->fields = stripslashes_deep($info->fields);
         $infos_id     = $info->fields['id'];
         echo "<tr class='tab_bg_1'>";
         echo "<td class='center'>";
         $rand = PluginDatainjectionInjectionType::dropdownLinkedTypes($info,
                                                                       array('primary_type'
                                                                           => $model->fields['itemtype'])
                                                                      );
         echo "</td>";
         echo "<td class='center'><span id='span_field_$infos_id'></span></td>";
         echo "<td class='center'><span id='span_mandatory_$infos_id'></span></td></tr>";
      }

      if ($canedit) {
         echo "<tr>";
         echo "<td class='tab_bg_2 center' colspan='4'>";
         echo "<input type='hidden' name='models_id' value='".$model->fields['id']."'>";
         echo "<input type='submit' name='update' value='".$LANG['buttons'][7]."' class='submit'>";
         echo "</td></tr>";
      }
      echo "</table></form>";
   }


   static function manageInfos($models_id, $infos = array()) {
      global $DB;

      $info = new self();

      if (isset($_POST['data']) && is_array($_POST['data']) && count($_POST['data'])) {
         foreach ($_POST['data'] as $id => $info_infos) {
            $info_infos['id'] = $id;
            //If no field selected, reset other values

            if ($info_infos['value'] == PluginDatainjectionInjectionType::NO_VALUE) {
               $info_infos['itemtype']     = PluginDatainjectionInjectionType::NO_VALUE;
               $info_infos['is_mandatory'] = 0;
            } else {
               $info_infos['is_mandatory'] = (isset($info_infos['is_mandatory'])?1:0);
            }

            if ($id > 0) {
               $info->update($info_infos);
            } else {
               $info_infos['models_id'] = $models_id;
               unset($info_infos['id']);
               $info->add($info_infos);
            }
         }
      }

      $info->deleteByCriteria(array('models_id' => $models_id,
                                    'value'     => PluginDatainjectionInjectionType::NO_VALUE));
   }


   static function showAdditionalInformationsForm(PluginDatainjectionModel $model) {
      global $LANG;

      $infos = getAllDatasFromTable('glpi_plugin_datainjection_infos',
                                    "`models_id` = '". $model->getField('id')."'");

      $table = false;
      $modeltype = PluginDatainjectionModel::getInstance($model->getField('filetype'));
      $modeltype->getFromDBByModelID($model->getField('id'));

      if (count($infos) || $modeltype->haveSample() || $model->fields['comment']) {
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr><th colspan='2'>" . $LANG['datainjection']['info'][1];
         echo "&nbsp;(".$LANG['datainjection']['fillInfoStep'][3].")</th></tr>\n";
         $table = true;
      }
      if ($modeltype->haveSample()) {
         echo "<tr class='tab_bg_1'>";
         echo "<td colspan='2' class='center'><a href='".$model->getFormURL()."?sample=";
         echo $model->getField('id')."'>".$LANG['datainjection']['model'][40]."</a></td></tr>\n";
      }
      if ($model->fields['comment']) {
         echo "<tr class='tab_bg_2'>";
         echo "<td colspan='2' class='center'>".nl2br($model->fields['comment'])."</td></tr>\n";
      }
      if (count($infos)) {
         $info = new PluginDatainjectionInfo;

         foreach ($infos as $tmp) {
            $info->fields = $tmp;
            $item = new $tmp['itemtype'];
            if ($item->can(-1,'w')) {
               echo "<tr class='tab_bg_1'>";
               self::displayAdditionalInformation(
                  $info,
                  (isset($_SESSION['datainjection']['infos'])
                     ? $_SESSION['datainjection']['infos']
                     : array())
               );
               echo "</tr>";
            }
         }
      }
      if ($table) {
         echo "</table><br>";
      }

      $options['models_id'] = $model->getField('id');
      $options['confirm']   = 'process';
      PluginDatainjectionClientInjection::showUploadFileForm($options);

      //Store models_id in session for future usage
      $_SESSION['datainjection']['models_id'] = $model->getField('id');
   }


   static function displayAdditionalInformation(PluginDatainjectionInfo $info, $values = array()) {

      $injectionClass = PluginDatainjectionCommonInjectionLib::getInjectionClassInstance($info->fields['itemtype']);
      $option = PluginDatainjectionCommonInjectionLib::findSearchOption($injectionClass->getOptions($info->fields['itemtype']),
                                                                        $info->fields['value']);
      if ($option) {
         echo "<td>".$option['name']."</td><td>";
         self::showAdditionalInformation($info, $option, $injectionClass, $values);
         echo "</td>";
      }
   }


   /**
    * Display command additional informations
    *
    * @param info
    * @param option
    * @param injectionClass
    *
    * @return nothing
   **/
   static function showAdditionalInformation(PluginDatainjectionInfo $info, $option = array(),
                                             $injectionClass, $values = array()) {
      global $LANG;

      $name = "info[".$option['linkfield']."]";

      if (isset($_SESSION['datainjection']['infos'][$option['linkfield']])) {
         $value = $_SESSION['datainjection']['infos'][$option['linkfield']];
      } else {
         $value = '';
      }

      switch ($option['displaytype']) {
         case 'text' :
         case 'decimal' :
            if (empty($value)) {
               $value = (isset($option['default'])?$option['default']:'');
            }
            echo "<input type='text' name='$name' value='$value'";
            if (isset($option['size'])) {
               echo " size='".$option['size']."'";
            }
            echo ">";
            break;

         case 'dropdown' :
            if ($value == '') {
               $value = 0;
            }
            Dropdown::show(getItemTypeForTable($option['table']), array('name'  => $name,
                                                                        'value' => $value));
            break;

         case 'bool' :
            if ($value == '') {
               $value = 0;
            }
            Dropdown::showYesNo($name, $value);
            break;

         case 'user' :
            if ($value == '') {
               $value = 0;
            }
            User::dropdown(array('name'  => $name,
                                 'value' => $value));
            break;

         case 'date' :
            showDateFormItem($name, $value, true, true);
            break;

         case 'multiline_text' :
            echo "<textarea cols='45' rows='5' name='$name'>$value</textarea>";
            break;

         case 'dropdown_integer' :
            $minvalue = (isset($option['minvalue'])?$option['minvalue']:0);
            $maxvalue = (isset($option['maxvalue'])?$option['maxvalue']:0);
            $step     = (isset($option['step'])?$option['step']:1);
            $default  = (isset($option['-1'])?array(-1 => $option['-1']):array());

            Dropdown::showInteger($name, $value, $minvalue, $maxvalue, $step, $default);
            break;

         case 'template' :
            self::dropdownTemplates($name, $option['table']);
            break;

         default :
            if (method_exists($injectionClass,'showAdditionalInformation')) {
               //If type is not a standard type, must be treated by specific injection class
               $injectionClass->showAdditionalInformation($info, $option);
            }
      }

      if ($info->isMandatory()) {
         echo "&nbsp;*";
      }
   }


   static function keepInfo(PluginDatainjectionInfo $info, $value) {

      $itemtype       = $info->getInfosType();
      $injectionClass = PluginDatainjectionCommonInjectionLib::getInjectionClassInstance($itemtype);
      $options        = $injectionClass->getOptions($itemtype);
      $option         = PluginDatainjectionCommonInjectionLib::findSearchOption($options,
                                                                                $info->getValue());

      if ($option) {
         switch ($option['displaytype']) {
            default :
            case 'text' :
            case 'multiline_text' :
               if ($value != PluginDatainjectionCommonInjectionLib::EMPTY_VALUE) {
                  return true;
               }
               return false;

            case 'dropdown' :
            case 'user' :
            case 'contact' :
               if ($value != PluginDatainjectionCommonInjectionLib::DROPDOWN_EMPTY_VALUE) {
                  return true;
               }
               return false;
         }
      }
   }


   static function dropdownTemplates($name, $table) {
      global $DB;

      $values    = array(0 => DROPDOWN_EMPTY_VALUE);

      $sql = "SELECT `id`, `template_name`
              FROM `".$table."`
              WHERE `is_template`=1 ".
                  getEntitiesRestrictRequest(' AND ', $table).
             "ORDER BY `template_name`";

      foreach ($DB->request($sql) as $data) {
         $values[$data['id']] = $data['template_name'];
      }
      Dropdown::showFromArray($name, $values);
   }

}
?>