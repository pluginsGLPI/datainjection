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

class PluginDatainjectionModel extends CommonDBTM {

   static $rightname = "plugin_datainjection_model";
   
   //Store mappings informations
   private $mappings;

   //Store backend used to collect informations
   private $backend;

   //Store informations related to the model
   protected $infos;

   //Do history (CommonDBTM)
   public $dohistory = true;

   //Store specific backend parameters
   public $specific_model;

   //Data to inject
   public $injectionData = false;

   //Store field of type 'multline_text' several times mapped
   public $severaltimes_mapped = array();

   //Private or public model
   const MODEL_PRIVATE  = 1;
   const MODEL_PUBLIC   = 0;

   //Step constants
   const INITIAL_STEP      = 1;
   const FILE_STEP         = 2;
   const MAPPING_STEP      = 3;
   const OTHERS_STEP       = 4;
   const READY_TO_USE_STEP = 5;

   const PROCESS  = 0;
   const CREATION = 1;



   static function getTypeName($nb=0) {
      return __('Model management', 'datainjection');
   }


   function __construct() {

      $this->mappings = new PluginDatainjectionMappingCollection();
      $this->infos    = new PluginDatainjectionInfoCollection();
   }

   function canViewItem() {

      if ($this->isPrivate() && $this->fields['users_id'] != Session::getLoginUserID()) {
         return false;
      }

      if (!$this->isPrivate()
          && !Session::haveAccessToEntity($this->getEntityID(),$this->isRecursive())) {
         return false;
      }

      return self::checkRightOnModel($this->fields['id']);
   }


   function canCreateItem() {

      if ($this->isPrivate()
          && ($this->fields['users_id'] != Session::getLoginUserID())) {
         return false;
      }

      if (!$this->isPrivate()
          && !Session::haveAccessToEntity($this->getEntityID())) {
         return false;
      }

      return self::checkRightOnModel($this->fields['id']);
   }


   //Loading methods
   function loadMappings() {
      $this->mappings->load($this->fields['id']);
   }


   //Loading methods
   function loadInfos() {
      $this->infos->load($this->fields['id']);
   }


   //---- Getters -----//
   function getMandatoryMappings() {
      return $this->mappings->getMandatoryMappings();
   }


   //---- Getters -----//
   function getMappings() {
      return $this->mappings->getAllMappings();
   }


   function getInfos() {
      return $this->infos->getAllInfos();
   }


   function getBackend() {
      return $this->backend;
   }


   function getMappingByName($name) {
      return $this->mappings->getMappingByName($name);
   }


   function getMappingByRank($rank) {
      return $this->mappings->getMappingByRank($rank);
   }


   function getFiletype() {
      return $this->fields["filetype"];
   }


   function getBehaviorAdd() {
      return $this->fields["behavior_add"];
   }


   function getBehaviorUpdate() {
      return $this->fields["behavior_update"];
   }


   function getItemtype() {
      return $this->fields["itemtype"];
   }


   function getEntity() {
      return $this->fields["entities_id"];
   }


   function getCanAddDropdown() {
      return $this->fields["can_add_dropdown"];
   }


   function getCanOverwriteIfNotEmpty() {
      return $this->fields["can_overwrite_if_not_empty"];
   }


   function getDateFormat() {
      return $this->fields["date_format"];
   }


   function getFloatFormat() {
      return $this->fields["float_format"];
   }


   function getPortUnicity() {
      return $this->fields["port_unicity"];
   }


   function getNumberOfMappings() {

      if ($this->mappings) {
         return count($this->mappings);
      }
      return false;
   }


   function getSpecificModel() {
      return  $this->specific_model;
   }


   /**
    * @param $options   array
   **/
   static function dropdown($options=array()) {
      global $CFG_GLPI;

      $models = self::getModels(Session::getLoginUserID(), 'name', $_SESSION['glpiactive_entity'],
                                false);
      $p      = array('models_id' => '__VALUE__');

      if (isset($_SESSION['datainjection']['models_id'])) {
         $value = $_SESSION['datainjection']['models_id'];
      } else {
         $value = 0;
      }

      $rand = mt_rand();
      echo "\n<select name='dropdown_models' id='dropdown_models$rand'>";
      $prev = -2;
      echo "\n<option value='0'>".Dropdown::EMPTY_VALUE."</option>";

      foreach ($models as $model) {
         if ($model['entities_id'] != $prev) {
            if ($prev >= -1) {
               echo "</optgroup>\n";
            }

            if ($model['entities_id'] == -1) {
               echo "\n<optgroup label='" . __('Private') . "'>";
            } else {
               echo "\n<optgroup label=\"" . Dropdown::getDropdownName("glpi_entities",
                                                                      $model['entities_id']) . "\">";
            }
            $prev = $model['entities_id'];
         }

         if ($model['id'] == $value) {
            $selected = "selected";
         } else {
            $selected = "";
         }

         if ($model['comment']) {
            $comment = "title='".htmlentities($model['comment'], ENT_QUOTES, 'UTF-8')."'";
         } else {
            $comment = "";
         }
         echo "\n<option value='".$model['id']."' $selected $comment>".$model['name']."</option>";
      }

      if ($prev >= -1) {
         echo "</optgroup>";
      }
      echo "</select>";

      $url = $CFG_GLPI["root_doc"]."/plugins/datainjection/ajax/dropdownSelectModel.php";
      Ajax::updateItemOnSelectEvent("dropdown_models$rand", "span_injection", $url, $p);
   }


   /**
    * @param $user_id
    * @param $order        (default 'name')
    * @param $entity       (default -1)
    * @param $all          (false by default)
   **/
   static function getModels($user_id, $order = "name", $entity = -1, $all = false) {
      global $DB;

      $models = array ();
      $query = "SELECT `id`, `name`, `is_private`, `entities_id`, `is_recursive`, `itemtype`,
                       `step`, `comment`
                FROM `glpi_plugin_datainjection_models` ";

      if (!$all) {
         $query .= " WHERE `step` = '".self::READY_TO_USE_STEP."' AND (";
      } else {
         $query .= " WHERE (";
      }

      $query .= "(`is_private` = '" . self::MODEL_PUBLIC."'".
                  getEntitiesRestrictRequest(" AND", "glpi_plugin_datainjection_models",
                                             "entities_id", $entity, true) . ")
                  OR (`is_private` = '" . self::MODEL_PRIVATE."' AND `users_id` = '$user_id'))
                 ORDER BY `is_private` DESC,
                          `entities_id`, " . ($order == "`name`" ? "`name`" : $order);

      foreach ($DB->request($query) as $data) {
         if (self::checkRightOnModel($data['id'])
             && class_exists($data['itemtype'])) {
            $models[] = $data;
         }
      }

      return $models;
   }


   //Standard functions
   function getSearchOptions() {

      $tab                       = array();

      $tab['common']             = self::getTypeName();

      $tab[1]['table']           = $this->getTable();
      $tab[1]['field']           = 'name';
      $tab[1]['name']            = __('Name');
      $tab[1]['datatype']        = 'itemlink';
      $tab[1]['itemlink_type']   = $this->getType();

      $tab[2]['table']           = $this->getTable();
      $tab[2]['field']           = 'id';
      $tab[2]['name']            = __('ID');

      $tab[3]['table']           = $this->getTable();
      $tab[3]['field']           = 'behavior_add';
      $tab[3]['name']            = __('Allow lines creation', 'datainjection');
      $tab[3]['datatype']        = 'bool';
      $tab[3]['massiveaction']   = false;

      $tab[4]['table']           = $this->getTable();
      $tab[4]['field']           = 'behavior_update';
      $tab[4]['name']            = __('Allow lines update', 'datainjection');
      $tab[4]['datatype']        = 'bool';
      $tab[4]['massiveaction']   = false;

      $tab[5]['table']           = $this->getTable();
      $tab[5]['field']           = 'itemtype';
      $tab[5]['name']            = __('Type of data to import', 'datainjection');
      $tab[5]['datatype']        = 'itemtypename';
      $tab[5]['nosearch']        = true;
      $tab[5]['massiveaction']   = false;

      $tab[6]['table']           = $this->getTable();
      $tab[6]['field']           = 'can_add_dropdown';
      $tab[6]['name']            = __('Allow creation of dropdowns', 'datainjection');
      $tab[6]['datatype']        = 'bool';

      $tab[7]['table']           = $this->getTable();
      $tab[7]['field']           = 'date_format';
      $tab[7]['name']            = __('Dates format', 'datainjection');
      $tab[7]['datatype']        = 'specific';
      $tab[7]['searchtype']      = 'equals';

      $tab[8]['table']           = $this->getTable();
      $tab[8]['field']           = 'float_format';
      $tab[8]['name']            = __('Float format', 'datainjection');
      $tab[8]['datatype']        = 'specific';
      $tab[8]['searchtype']      = 'equals';

      $tab[9]['table']           = $this->getTable();
      $tab[9]['field']           = 'perform_network_connection';
      $tab[9]['name']            = __('Try to establish network connection is possible',
                                      'datainjection');
      $tab[9]['datatype']        = 'bool';

      $tab[10]['table']          = $this->getTable();
      $tab[10]['field']          = 'port_unicity';
      $tab[10]['name']           = __('Port unicity criteria', 'datainjection');
      $tab[10]['datatype']       = 'specific';
      $tab[10]['searchtype']     = 'equals';

      $tab[11]['table']          = $this->getTable();
      $tab[11]['field']          = 'is_private';
      $tab[11]['name']           = __('Private');
      $tab[11]['datatype']       = 'bool';
      $tab[11]['massiveaction']  = false;

      $tab[12]['table']          = $this->getTable();
      $tab[12]['field']          = 'step';
      $tab[12]['name']           = __('Status');
      $tab[12]['massiveaction']  = false;
      $tab[12]['datatype']       = 'specific';
      $tab[12]['searchtype']     = 'equals';

      $tab[16]['table']          = $this->getTable();
      $tab[16]['field']          = 'comment';
      $tab[16]['name']           = __('Comments');
      $tab[16]['datatype']       = 'text';

      $tab[80]['table']          = 'glpi_entities';
      $tab[80]['field']          = 'completename';
      $tab[80]['name']           = __('Entity');
      $tab[80]['datatype']       = 'dropdown';

      $tab[86]['table']          = $this->getTable();
      $tab[86]['field']          = 'is_recursive';
      $tab[86]['name']           = __('Child entities');
      $tab[86]['datatype']       = 'bool';

      return $tab;
   }


   /**
    * @since version 2.3.0
    *
    * @param $field
    * @param $values
    * @param $options   array
   **/
   static function getSpecificValueToDisplay($field, $values, array $options=array()) {

      if (!is_array($values)) {
         $values = array($field => $values);
      }
      switch ($field) {
         case "port_unicity" :
            return PluginDatainjectionDropdown::getPortUnicityValues($values['port_unicity']);

         case "float_format" :
            return PluginDatainjectionDropdown::getFloatFormat($values['float_format']);

         case "date_format" :
            return PluginDatainjectionDropdown::getDateFormat($values['date_format']);

         case "step" :
            return PluginDatainjectionDropdown::getStatusLabel($values['step']);
      }
      return parent::getSpecificValueToDisplay($field, $values, $options);
   }


   /**
    * @since version 2.3.0
    *
    * @param $field
    * @param $name               (default '')
    * @param $values             (defaut '')
    * @param $options   array
   **/
   static function getSpecificValueToSelect($field, $name='', $values='', array $options=array()) {

      if (!is_array($values)) {
         $values = array($field => $values);
      }
      $options['display'] = false;
      switch ($field) {
         case 'step':
            $options['value'] = $values[$field];
            return Dropdown::showFromArray($name, PluginDatainjectionDropdown::statusLabels(),
                                             $options);

         case 'port_unicity' :
            $options['value'] = $values[$field];
            return Dropdown::showFromArray($name, PluginDatainjectionDropdown::portUnicityValues(),
                                             $options);

         case 'float_format' :
            $options['value'] = $values[$field];
            return Dropdown::showFromArray($name, PluginDatainjectionDropdown::floatFormats(),
                                             $options);

         case 'date_format' :
            $options['value'] = $values[$field];
            return Dropdown::showFromArray($name, PluginDatainjectionDropdown::dateFormats(),
                                             $options);

      }
      return parent::getSpecificValueToSelect($field, $name, $values, $options);
   }


   function showForm($ID, $options = array()) {

      if ($ID > 0) {
         $this->check($ID, READ);
      } else {
         // Create item
         $this->check(-1, UPDATE);
         $this->getEmpty();
      }

      $this->initForm($ID, $options);
      
      if ($this->isNewID($ID)) {
         $this->showAdvancedForm($ID);
      }

      return true;
   }


   function showAdvancedForm($ID, $options = array()) {

      if ($ID > 0) {
         $this->check($ID, READ);
      } else {
         // Create item
         $this->check(-1, UPDATE);
         $this->getEmpty();
         echo Html::hidden('step', array('value' => 1));
      }

      echo "<form name='form' method='post' action='".Toolbox::getItemTypeFormURL(__CLASS__)."'>";
      echo "<div class='center' id='tabsbody'>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr><th colspan='2'>".self::getTypeName()."</th>";
      echo "<th colspan='2'>".PluginDatainjectionDropdown::getStatusLabel($this->fields['step']).
           "</th></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td><input type='hidden' name='users_id' value='".Session::getLoginUserID()."'>".
                 __('Name')."</td>";
      echo "<td>";
      Html::autocompletionTextField($this, "name");
      echo "</td>";
      echo "<td colspan='2'></td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='4' class='center'>";
      Dropdown::showPrivatePublicSwitch($this->fields["is_private"], $this->fields["entities_id"],
                                        $this->fields["is_recursive"]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Comments')."</td>";
      echo "<td colspan='3' class='middle'>";
      echo "<textarea cols='45' rows='5' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Type of data to import', 'datainjection')."</td>";
      echo "<td>";

      if (($this->fields['step'] == '') || ($this->fields['step'] == self::INITIAL_STEP)) {
         //Get only the primary types
         PluginDatainjectionInjectionType::dropdown($this->fields['itemtype'], true);
      } else {
         $itemtype = new $this->fields['itemtype'];
         echo $itemtype->getTypeName();
      }
      echo "</td><td colspan='2'></tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Allow lines creation', 'datainjection')."</td>";
      echo "<td>";
      Dropdown::showYesNo("behavior_add", $this->fields['behavior_add']);
      echo "</td><td>".__('Allow lines update', 'datainjection')."</td>";
      echo "<td>";
      Dropdown::showYesNo("behavior_update", $this->fields['behavior_update']);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'><th colspan='4'>".__('Advanced options', 'datainjection').
           "</th></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Allow creation of dropdowns', 'datainjection')."</td>";
      echo "<td>";
      Dropdown::showYesNo("can_add_dropdown", $this->fields['can_add_dropdown']);
      echo "</td>";
      echo "<td>".__('Dates format', 'datainjection')."</td>";
      echo "<td>";
      Dropdown::showFromArray('date_format', PluginDatainjectionDropdown::dateFormats(),
                              array('value' => $this->fields['date_format']));
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Allow update of existing fields', 'datainjection')."</td>";
      echo "<td>";
      Dropdown::showYesNo("can_overwrite_if_not_empty", $this->fields['can_overwrite_if_not_empty']);
      echo "</td>";
      echo "<td>".__('Float format', 'datainjection')."</td>";
      echo "<td>";
      Dropdown::showFromArray('float_format', PluginDatainjectionDropdown::floatFormats(),
                              array('value' => $this->fields['float_format']));
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Try to establish network connection is possible', 'datainjection')."</td>";
      echo "<td>";
      Dropdown::showYesNo("perform_network_connection", $this->fields['perform_network_connection']);
      echo "</td>";
      echo "<td>".__('Port unicity criteria', 'datainjection')."</td>";
      echo "<td>";
      Dropdown::showFromArray('port_unicity', PluginDatainjectionDropdown::portUnicityValues(),
                              array('value' => $this->fields['port_unicity']));
      echo "</td></tr>";

      if ($ID > 0) {
         $tmp = self::getInstance('csv');
         $tmp->showAdditionnalForm($this);
      }

      $this->showFormButtons($options);
      return true;
   }


   function showValidationForm() {

      echo "<form method='post' name=form action='".Toolbox::getItemTypeFormURL(__CLASS__)."'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'><th colspan='4'>".__('Validation')."</th></tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td class='center'>";
      echo "<input type='submit' class='submit' name='validate' value='".
             _sx('button', 'Validate the model', 'datainjection')."'>";
      echo "<input type='hidden' name='id' value='".$this->fields['id']."'>";
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      Html::closeForm();

      return true;
   }


   //Tabs management
   function defineTabs($options = array()) {

      $tabs = array();
      $this->addStandardTab(__CLASS__, $tabs, $options);
      $this->addStandardTab('Log', $tabs, $options);
      return $tabs;
   }


   //Tabs management
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
   
      $canedit = Session::haveRight('plugin_datainjection_model', UPDATE);
      
      if (!$withtemplate) {
         switch ($item->getType()) {
            case __CLASS__ :
               $tabs[1] = __('Model');
               if (!$this->isNewID($item->fields['id'])) {
                  if ($canedit) {
                     $tabs[3] = __('File to inject', 'datainjection');
                  }
                  $tabs[4] = __('Mappings', 'datainjection');

                  if ($item->fields['step'] > self::MAPPING_STEP) {
                     $tabs[5] = __('Additional Information', 'datainjection');

                     if ($canedit && $item->fields['step'] != self::READY_TO_USE_STEP) {
                        $tabs[6] = __('Validation');
                     }
                  }
               }
               return $tabs;

            default:
               return '';
         }
      }
   }


   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getType() == __CLASS__) {
         switch ($tabnum) {
            case 1 :
               $item->showAdvancedForm($item->getID());
               break;

            case 3:
               $options['confirm']   = 'creation';
               $options['models_id'] = $item->fields['id'];
               $options['add_form']  = true;
               $options['submit']    = __('Load this file', 'datainjection');
               PluginDatainjectionClientInjection::showUploadFileForm($options);
               break;

            case 4 :
               PluginDatainjectionMapping::showFormMappings($item);
               break;

            case 5:
               if ($item->fields['step'] > self::MAPPING_STEP) {
                  PluginDatainjectionInfo::showFormInfos($item);
               }
               break;

            case 6:
               if ($item->fields['step'] > self::MAPPING_STEP) {
                  $item->showValidationForm();
               }
               break;
         }
      }
      return true;
   }


   function cleanDBonPurge() {

      $itemtypes = array("PluginDatainjectionModelcsv", "PluginDatainjectionMapping",
                         "PluginDatainjectionInfo");

      foreach ($itemtypes as $itemtype) {
         $item = new $itemtype();
         $item->deleteByCriteria(array("models_id" => $this->getID()));
      }
   }


   /**
    * Clean all model which match some criteria
    *
    * @param $crit array of criteria (ex array('itemtype'=>'PluginAppliancesAppliance'))
    *
   **/
   static function clean($crit=array()) {
      global $DB;

      $model = new self();

      if (is_array($crit) && (count($crit) > 0)) {
         $crit['FIELDS'] = 'id';
         foreach ($DB->request($model->getTable(), $crit) as $row) {
            $model->delete($row);
         }
      }
   }


   /**
    * @param $models_id
    * @param $step
   **/
   static function changeStep($models_id, $step) {

      $model = new self();
      if ($model->getFromDB($models_id)) {
         $model->dohistory = false;
         $tmp['id']        = $models_id;
         $tmp['step']      = $step;
         $model->update($tmp);
         $model->dohistory = false;
      }
   }


   function prepareInputForAdd($input) {

      //If no behavior selected
      if (!isset($input['name']) || ($input['name'] == '')) {
         Session::addMessageAfterRedirect(__('Please enter a name for the model', 'datainjection'),
                                          true, ERROR, true);
         return false;
      }

      if (!$input['behavior_add'] && !$input['behavior_update']) {
         Session::addMessageAfterRedirect(__('Your model should allow import and/or update of data',
                                          'datainjection'), true, ERROR, true);
         return false;
      }

      if (isset($input['is_private']) && ($input['is_private'] == 1)) {
         $input['entities_id']  = $_SESSION['glpiactive_entity'];
         $input['is_recursive'] = 1;
      }
      $input['step'] = self::FILE_STEP;

      return $input;
   }


   function prepareInputForUpdate($input) {

      if (isset($input['is_private']) && ($input['is_private'] == 1)) {
         $input['entities_id']  = $_SESSION['glpiactive_entity'];
         $input['is_recursive'] = 1;

         if (isset($input['users_id']) && ($input['users_id'] != $this->fields['users_id'])) {
            Session::addMessageAfterRedirect(__('You are not the initial creator of this model',
                                             'datainjection'), true, ERROR, true);
            return false;
         }
      }
      return $input;
   }


   /**
    * Get the backend implementation by type
    *
    * @param $type
   **/
   static function getInstance($type) {

      $class = 'PluginDatainjectionModel'.$type;
      if (class_exists($class)) {
         return new $class();
      }
      return false;
   }


   static function getInstanceByModelID($models_id) {

      $model    = new self();
      $model->getFromDB($models_id);
      $specific = self::getInstance($model->getFiletype());
      $specific->getFromDBByModelID($models_id);
      $model->setSpecificModel($specific);
      return $model;
   }


   /**
    * @param $options   array
   **/
   function readUploadedFile($options=array()) {

      $file_encoding     = (isset($options['file_encoding'])
                            ?$options['file_encoding'] :PluginDatainjectionBackend::ENCODING_AUTO);
      $webservice        = (isset($options['webservice'])?$options['webservice']:false);
      $original_filename = (isset($options['original_filename'])?$options['original_filename']:false);
      $unique_filename   = (isset($options['unique_filename'])?$options['unique_filename']:false);
      $injectionData     = false;
      $only_header       = (isset($options['only_header'])?$options['only_header']:false);
      $delete_file       = (isset($options['delete_file'])?$options['delete_file']:true);

      //Get model & model specific fields
      $this->loadSpecificModel();

      if (!$webservice) {
         //Get and store uploaded file
         $original_filename           = $_FILES['filename']['name'];
         $temporary_uploaded_filename = stripslashes($_FILES["filename"]["tmp_name"]);
         $unique_filename             = tempnam (realpath(PLUGIN_DATAINJECTION_UPLOAD_DIR), "PWS");

         if (!move_uploaded_file($temporary_uploaded_filename, $unique_filename)) {
            return array('status'  => PluginDatainjectionCommonInjectionLib::FAILED,
                         'message' => sprintf(__('Impossible to copy the file in %s', 'datainjection'),
                                              realpath(PLUGIN_DATAINJECTION_UPLOAD_DIR)));
         }
      }

      //If file has not the right extension, reject it and delete if
      if ($this->specific_model->checkFileName($original_filename)) {
         $message  = __('File format is wrong', 'datainjection');
         $message .= "<br>".__('Extension csv required', 'datainjection');
         if (!$webservice) {
            Session::addMessageAfterRedirect($message, true, ERROR, false);
         }
         //unlink($temporary_uniquefilename);
         return array('status'  => ERROR,
                      'message' => $message);

      } else {
         //Initialise a new backend
          $backend = PluginDatainjectionBackend::getInstance($this->fields['filetype']);
         //Init backend with needed values
         $backend->init($unique_filename, $file_encoding);
         $backend->setHeaderPresent($this->specific_model->fields['is_header_present']);
         $backend->setDelimiter($this->specific_model->fields['delimiter']);

         if (!$webservice) {
            //Read n line from the CSV file if not webservice
            $injectionData = $backend->read(20);
         } else {
            //Read the whole file
            $injectionData = $backend->read(-1);
         }

         //Read the whole file and store the number of lines found
         $backend->storeNumberOfLines();
         $_SESSION['datainjection']['lines']   = serialize($injectionData);
         $_SESSION['datainjection']['nblines'] = $backend->getNumberOfLines();

         if ($delete_file) {
            $backend->deleteFile();
         }
         $this->backend = $backend;
      }
      $this->injectionData = $injectionData;
      return true;
   }


   /**
    * Load specific model
   **/
   function loadSpecificModel() {

      $specific_model = self::getInstance($this->getFiletype());
      $specific_model->getFromDBByModelID($this->fields['id']);
      $this->specific_model = $specific_model;
   }


   /**
    * Once file is uploaded, process it
    *
    * @param $options   array of possible options:
    *   - file_encoding
    *   - mode
    *
    * @return boolean
   **/
   function processUploadedFile($options=array()) {

      $file_encoding = (isset($options['file_encoding'])
                        ?$options['file_encoding'] :PluginDatainjectionBackend::ENCODING_AUTO);
      $mode          = (isset($options['mode'])?$options['mode']:self::PROCESS);

      //Get model & model specific fields
      $this->loadSpecificModel();
      $response = $this->readUploadedFile($options);
      if (!$this->injectionData) {
         if (!isset($options['webservice'])) {
            return false;
         }
         return PluginWebservicesMethodCommon::Error($options['protocol'],
                                                     WEBSERVICES_ERROR_FAILED,
                                                     sprintf(__('Not data to import',
                                                                'datainjection')));
      }

      if ($mode == self::PROCESS) {
         $this->loadMappings();
         $check = $this->isFileCorrect();

      } else {
         $check['status'] = PluginDatainjectionCommonInjectionLib::SUCCESS;
      }
         //There's an error
      if ($check['status']!= PluginDatainjectionCommonInjectionLib::SUCCESS) {

         if ($mode == self::PROCESS) {
            if (!isset($options['webservice'])) {
               Session::addMessageAfterRedirect($check['error_message'], true, ERROR);
               return false;
            }
            return PluginWebservicesMethodCommon::Error($options['protocol'],
                                                        WEBSERVICES_ERROR_FAILED,
                                                        $check['error_message']);
         }
      }

      $mappingCollection = new PluginDatainjectionMappingCollection();

      //Delete existing mappings only in model creation mode !!
      if ($mode == self::CREATION) {
         //If mapping still exists in DB, delete all of them !
         $mappingCollection->deleteMappingsFromDB($this->fields['id']);
      }

      $rank = 0;
      //Build the mappings list
      foreach (PluginDatainjectionBackend::getHeader($this->injectionData,
                                                     $this->specific_model->isHeaderPresent()) as $data) {
         $mapping = new PluginDatainjectionMapping;
         $mapping->fields['models_id'] = $this->fields['id'];
         $mapping->fields['rank']      = $rank;
         $mapping->fields['name']      = $data;
         $mapping->fields['value']     = PluginDatainjectionInjectionType::NO_VALUE;
         $mapping->fields['itemtype']  = PluginDatainjectionInjectionType::NO_VALUE;
         $mappingCollection->addNewMapping($mapping);
         $rank++;
      }

      if ($mode == self::CREATION) {
         //Save the mapping list in DB
         $mappingCollection->saveAllMappings();
         self::changeStep($this->fields['id'], self::MAPPING_STEP);

         //Add redirect message
         Session::addMessageAfterRedirect(__('The file is ok.', 'datainjection'), true, INFO);
      }

      return true;
   }


   /**
    * Try to parse an input file
    *
    * @return true if the file is a CSV file
   **/
   function isFileCorrect() {

      $field_in_error = false;

      //Get CSV file first line
      $header = PluginDatainjectionBackend::getHeader($this->injectionData,
                                                      $this->specific_model->isHeaderPresent());

      //If file columns don't match number of mappings in DB
      $nb = count($this->getMappings());
      if ($nb != count($header)) {
         $error_message  = __('The number of columns of the file is incorrect.', 'datainjection')."\n";
         $error_message .= sprintf(_n('%d awaited column', '%d awaited columns', $nb, 'datainjection'),
                                   $nb)."\n";
         $error_message .= sprintf(_n('%d found column', '%d found columns', count($header),
                                      'datainjection'),
                                   count($header));

         return array('status'         => PluginDatainjectionCommonInjectionLib::FAILED,
                      'field_in_error' => false,
                      'error_message'  => $error_message);
      }

      //If no header in the CSV file, exit method
      if (!$this->specific_model->isHeaderPresent()) {
         return array('status'         => PluginDatainjectionCommonInjectionLib::SUCCESS,
                      'field_in_error' => false,
                      'error_message'  => '');
      }

      $error = array('status'         => PluginDatainjectionCommonInjectionLib::SUCCESS,
                     'field_in_error' => false,
                     'error_message'  => '');

      //Check each mapping to be sure it has exactly the same name
      foreach($this->getMappings() as $key => $mapping) {
         if (!isset($header[$key])) {
            $error['status']         = PluginDatainjectionCommonInjectionLib::FAILED;
            $error['field_in_error'] = $key;

         } else {
            //If name of the mapping is not equal in the csv file header and in the DB
            $name_from_file = trim(mb_strtoupper(stripslashes($header[$mapping->getRank()])     ,
                                                 'UTF-8'));
            $name_from_db   = trim(mb_strtoupper(stripslashes($mapping->getName()), 'UTF-8'));

            if ($name_from_db != $name_from_file) {
               if ($error['error_message'] == '') {
                  $error['error_message'] = __('At least one column is incorrect', 'datainjection');
               }

               $error['status']         = PluginDatainjectionCommonInjectionLib::FAILED;
               $error['field_in_error'] = false;

               $error['error_message'] .= "<br>".sprintf(__('%1$s: %2$s'),
                                                         __('Into the file', 'datainjection'),
                                                         $name_from_file)."\n";
               $error['error_message'] .=sprintf(__('%1$s: %2$s'),
                                                 __('From the model', 'datainjection'),
                                                 $name_from_db);
            }
         }
      }
      return $error;
   }


   /**
    * @param $fields
   **/
   function checkMandatoryFields($fields) {

      //Load infos associated with the model
      $this->loadInfos();
      $check = true;

      foreach ($this->infos->getAllInfos() as $info) {
         if ($info->isMandatory()) {
            //Get search option (need to check dropdown default value)
            $itemtype = $info->getInfosType();
            $item     = new $itemtype();
            $option   = $item->getSearchOptionByField('field', $info->getValue());
            $tocheck  = (!isset($option['datatype']) || ($option['datatype'] != 'bool'));
            if (!isset($fields[$info->getValue()])
                //Check if no value defined only when it's not a yes/no
                || ($tocheck && !$fields[$info->getValue()])
                || ($fields[$info->getValue()] == 'NULL')) {
               $check = false;
               break;
            }
         }
      }
      return $check;
   }


   /**
    * Model is now ready to be used
   **/
   function switchReadyToUse() {

      $tmp         = $this->fields;
      $tmp['step'] = self::READY_TO_USE_STEP;
      $this->update($tmp);
   }


   function populateSeveraltimesMappedFields() {

      $this->severaltimes_mapped
               = PluginDatainjectionMapping::getSeveralMappedField($this->fields['id']);
   }


   /**
    * @param $models_id
   **/
   static function checkRightOnModel($models_id) {
      global $DB;

      $model = new self();
      $model->getFromDB($models_id);

      $continue = true;

      $query = "(SELECT `itemtype`
                 FROM `glpi_plugin_datainjection_models`
                 WHERE `id` = '".$models_id."')
                UNION (SELECT DISTINCT `itemtype`
                       FROM `glpi_plugin_datainjection_mappings`
                       WHERE `models_id` = '".$models_id."')
                UNION (SELECT DISTINCT `itemtype`
                       FROM `glpi_plugin_datainjection_infos`
                       WHERE `models_id` = '".$models_id."')";
      foreach ($DB->request($query) as $data) {
         if ($data['itemtype'] != PluginDatainjectionInjectionType::NO_VALUE) {
            if (class_exists($data['itemtype'])) {
               $item                     = new $data['itemtype']();
               $item->fields['itemtype'] = $model->fields['itemtype'];

               if (!($item instanceof CommonDBRelation) && !$item->canCreate()) {
                  $continue = false;
                  break;
               }
            }
         }
      }
      return $continue;
   }


   static function cleanSessionVariables() {

      //Reset parameters stored in session
      PluginDatainjectionSession::removeParams();
      PluginDatainjectionSession::setParam('infos', array());
   }


   /**
    * @param $models_id
   **/
   static function showPreviewMappings($models_id) {

      echo "<table class='tab_cadre_fixe'>";
      if (isset($_SESSION['datainjection']['lines'])) {
         $injectionData = unserialize($_SESSION['datainjection']['lines']);
         $lines         = $injectionData->getData();
         $nblines       = $_SESSION['datainjection']['nblines'];
         $model         = self::getInstanceByModelID($models_id);

         $model->loadMappings();
         $mappings = $model->getMappings();

         if ($model->getSpecificModel()->isHeaderPresent()) {
            $nbmappings = count($mappings);
            echo "<tr class='tab_bg_1'>";

            foreach($mappings as $mapping) {
               echo"<th style='height:40px'>".stripslashes($mapping->getMappingName())."</th>";
            }
            echo "</tr>";
            unset($lines[0]);
         }

         foreach ($lines as $line) {
            echo "<tr class='tab_bg_2'>";
            foreach ($line[0] as $value) {
               echo "<td>".$value."</td>";
            }
            echo "</tr>";
         }
      }
      Html::closeForm();
      echo "<div style='margin-top:15px;text-align:center'>";
      echo "<a href='javascript:window.close()'>" . __('Close') . "</a>";
      echo "</div>";
   }


   /**
    * @param $models_id
   **/
   static function prepareLogResults($models_id) {

      $results   = Toolbox::stripslashes_deep(json_decode(PluginDatainjectionSession::getParam('results'),
                                                          true));
      $todisplay = array();
      $model     = new self();
      $model->getFromDB($models_id);

      if (!empty($results)) {
         foreach ($results as $result) {
            $tmp = array('line'           => $result['line'],
                         'status'         => $result['status'],
                         'check_sumnary'  => PluginDatainjectionCommonInjectionLib::getLogLabel(PluginDatainjectionCommonInjectionLib::SUCCESS),
                         'check_message'  => PluginDatainjectionCommonInjectionLib::getLogLabel(PluginDatainjectionCommonInjectionLib::SUCCESS),
                         'type'           => __('Undetermined', 'datainjection'),
                         'status_message' => PluginDatainjectionCommonInjectionLib::getLogLabel($result['status']),
                         'itemtype'       => $model->fields['itemtype'],
                         'url'            => '',
                         'item'           => '');

            if (isset($result[PluginDatainjectionCommonInjectionLib::ACTION_CHECK])) {
               $check_infos          = $result[PluginDatainjectionCommonInjectionLib::ACTION_CHECK];
               $tmp['check_status']  = $check_infos['status'];
               $tmp['check_sumnary'] = PluginDatainjectionCommonInjectionLib::getLogLabel($check_infos['status']);
               $tmp['check_message'] = '';
               $first                = true;

               foreach($check_infos as $key => $val) {
                  if (($key !== 'status')
                      && ($val[0] != PluginDatainjectionCommonInjectionLib::SUCCESS)) {
                     $tmp['check_message'] .= ($first ? '' : "\n").
                                              sprintf(__('%1$s (%2$s)'),
                                                      PluginDatainjectionCommonInjectionLib::getLogLabel($val[0]),
                                                      $val[1]);
                     $first = false;
                  }
               }
            }

            //Store the action type (add/update)
            if (isset($result['type'])) {
               $tmp['type'] = PluginDatainjectionCommonInjectionLib::getActionLabel($result['type']);
            }


            if (isset($result[$model->fields['itemtype']])) {
               $tmp['item'] = $result[$model->fields['itemtype']];
               $url         = Toolbox::getItemTypeFormURL($model->fields['itemtype'])."?id=".
                                                      $result[$model->fields['itemtype']];
               $tmp['url']  = "<a href='".$url."'>".$result[$model->fields['itemtype']]."</a>";
            }

            if ($result['status'] == PluginDatainjectionCommonInjectionLib::SUCCESS) {
               $todisplay[PluginDatainjectionCommonInjectionLib::SUCCESS][] = $tmp;
            } else {
               $todisplay[PluginDatainjectionCommonInjectionLib::FAILED][] = $tmp;
            }
         }
      }
      return $todisplay;
   }


   /**
    * @param $models_id
   **/
   static function showLogResults($models_id) {

      $logresults = self::prepareLogResults($models_id);
      if (!empty($logresults)) {
         if (!empty($logresults[PluginDatainjectionCommonInjectionLib::SUCCESS])) {
            echo "<table>\n";
            echo "<tr>";
            echo "<td style='width:30px'>";
            echo "<a href=\"javascript:show_log('1')\"><img src='../pics/plus.png' alt='plus' id='log1'>";
            echo "</a></td>";
            echo "<td style='width: 900px;font-size: 14px;font-weight: bold;padding-left: 20px'>".
                   __('Array of successful injections', 'datainjection')."</td>";
            echo "</tr>\n";
            echo "</table>\n";

            echo "<div id='log1_table'>";
            echo "<table class='tab_cadre_fixe'>\n";
            echo "<tr><th></th>"; //Icone
            echo "<th>".__('Line', 'datainjection')."</th>"; //Ligne
            echo "<th>".__('Data Import', 'datainjection')."</th>"; //Import des données
            echo "<th>".__('Injection type', 'datainjection')."</th>"; //Type d'injection
            echo "<th>".__('Object Identifier', 'datainjection')."</th></tr>\n"; //Identifiant de l'objet

            foreach ($logresults[PluginDatainjectionCommonInjectionLib::SUCCESS] as $result) {
               echo "<tr class='tab_bg_1'>";
               echo "<td style='height:30px;width:30px'><img src='../pics/ok.png' alt='success'></td>";
               echo "<td>".$result['line']."</td>";
               echo "<td>".nl2br($result['status_message'])."</td>";
               echo "<td>".$result['type']."</td>";
               echo "<td>".$result['url']."</td><tr>\n";
            }
            echo "</table></div>\n";
         }

         if (!empty($logresults[PluginDatainjectionCommonInjectionLib::FAILED])) {
            echo "<table>\n";
            echo "<tr>";
            echo "<td style='width:30px'>";
            echo "<a href=\"javascript:show_log('2')\"><img src='../pics/minus.png' alt='minus' id='log2'>";
            echo "</a></td>";
            echo "<td style='width: 900px;font-size: 14px;font-weight: bold;padding-left: 20px'>".
                   __('Array of unsuccessful injections', 'datainjection')."</td>";
            echo "</tr>\n";
            echo "</table>\n";

            echo "<div id='log2_table'>";
            echo "<table class='tab_cadre_fixe center'>\n";
            echo "<th></th>"; //Icone
            echo "<th>".__('Line', 'datainjection')."</th>"; //Ligne
            echo "<th>".__('Data check', 'datainjection')."</th>"; //Vérification des données
            echo "<th>".__('Data Import', 'datainjection')."</th>"; //Import des données
            echo "<th>".__('Injection type', 'datainjection')."</th>"; //Type d'injection
            echo "<th>".__('Object Identifier', 'datainjection')."</th></tr>\n"; //Identifiant de l'objet

            foreach ($logresults[PluginDatainjectionCommonInjectionLib::FAILED] as $result) {
               echo "<tr class='tab_bg_1'>";
               echo "<td style='height:30px;width:30px'><img src='../pics/notok.png' alt='success'></td>";
               echo "<td>".$result['line']."</td>";
               echo "<td>".nl2br($result['check_message'])."</td>";
               echo "<td>".nl2br($result['status_message'])."</td>";
               echo "<td>".$result['type']."</td>";
               echo "<td>".$result['url']."</td><tr>\n";
            }
            echo "</table></div>\n";
            echo "<script type='text/javascript'>document.getElementById('log1_table').style.display='none'</script>";
         }
      }

      echo "<div style='margin-top:15px;text-align:center'>";
      echo "<a href='javascript:window.close()'>" . __('Close') . "</a>";
      echo "</div>";
   }


   static function exportAsPDF($models_id) {

      $logresults = self::prepareLogResults($models_id);
      $model      = new self();
      $model->getFromDB($models_id);

      if (!empty($logresults)) {
         $pdf = new PluginPdfSimplePDF('a4', 'landscape');
         $pdf->setHeader(sprintf(__('%1$s (%2$s)'),
                                 __('File injection report', 'datainjection') . ' - <b>' .
                                    PluginDatainjectionSession::getParam('file_name') . '</b>',
                                 $model->getName()));
         $pdf->newPage();

         if (isset($logresults[PluginDatainjectionCommonInjectionLib::SUCCESS])) {
            $pdf->setColumnsSize(100);
            $pdf->displayTitle('<b>'.__('Array of successful injections', 'datainjection').'</b>');
            $pdf->setColumnsSize(6,54,20,20);
            $pdf->setColumnsAlign('center','center','center','center');
            $col0 = '<b>'.__('Line', 'datainjection').'</b>';
            $col1 = '<b>'.__('Data Import', 'datainjection').'</b>';
            $col2 = '<b>'.__('Injection type', 'datainjection').'</b>';
            $col3 = '<b>'.__('Object Identifier', 'datainjection').'</b>';
            $pdf->displayTitle($col0, $col1, $col2, $col3);

            $index = 0;
            foreach ($logresults[PluginDatainjectionCommonInjectionLib::SUCCESS] as $result) {
               $pdf->displayLine($result['line'], $result['status_message'], $result['type'],
                                 $result['item']);
            }
         }

         if (isset($logresults[PluginDatainjectionCommonInjectionLib::FAILED])) {
            $pdf->setColumnsSize(100);
            $pdf->displayTitle('<b>'.__('Array of unsuccessful injections', 'datainjection').'</b>');
            $pdf->setColumnsSize(6, 16, 38, 20, 20);
            $pdf->setColumnsAlign('center','center','center','center','center');
            $col0 = '<b>'.__('Line', 'datainjection').'</b>';
            $col1 = '<b>'.__('Data check', 'datainjection').'</b>';
            $col2 = '<b>'.__('Data Import', 'datainjection').'</b>';
            $col3 = '<b>'.__('Injection type', 'datainjection').'</b>';
            $col4 = '<b>'.__('Object Identifier', 'datainjection').'</b>';
            $pdf->displayTitle($col0, $col1, $col2, $col3, $col4);

            $index = 0;
            foreach ($logresults[PluginDatainjectionCommonInjectionLib::FAILED] as $result) {
               $pdf->setColumnsSize(6, 16, 38, 20, 20);
               $pdf->setColumnsAlign('center', 'center', 'center', 'center', 'center');
               $pdf->displayLine($result['line'], $result['check_sumnary'],
                                 $result['status_message'], $result['type'], $result['item']);

               if ($result['check_message']) {
                  $pdf->displayText('<b>'.__('Data check', 'datainjection').'</b> :',
                                    $result['check_message'],1);
               }
            }
         }
         $pdf->render();
      }
   }


   function cleanData() {
      $this->injectionData = array();
   }

}
?>
