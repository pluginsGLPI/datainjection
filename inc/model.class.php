<?php

/**
 * -------------------------------------------------------------------------
 * DataInjection plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of DataInjection.
 *
 * DataInjection is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * DataInjection is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DataInjection. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2007-2023 by DataInjection plugin team.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/pluginsGLPI/datainjection
 * -------------------------------------------------------------------------
 */

use Glpi\Application\View\TemplateRenderer;

use function Safe\json_decode;
use function Safe\realpath;
use function Safe\tempnam;

/**
 * -------------------------------------------------------------------------
 * DataInjection plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of DataInjection.
 *
 * DataInjection is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * DataInjection is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DataInjection. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2007-2023 by DataInjection plugin team.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/pluginsGLPI/datainjection
 * -------------------------------------------------------------------------
 */

class PluginDatainjectionModel extends CommonDBTM
{
    public static $rightname = "plugin_datainjection_model";

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
    public $severaltimes_mapped = [];

    //Private or public model
    public const MODEL_PRIVATE  = 1;
    public const MODEL_PUBLIC   = 0;

    //Step constants
    public const INITIAL_STEP      = 1;
    public const FILE_STEP         = 2;
    public const MAPPING_STEP      = 3;
    public const OTHERS_STEP       = 4;
    public const READY_TO_USE_STEP = 5;

    public const PROCESS  = 0;
    public const CREATION = 1;



    public static function getTypeName($nb = 0)
    {

        return _sn('Model', 'Models', $nb);
    }


    public function __construct()
    {

        $this->mappings = new PluginDatainjectionMappingCollection();
        $this->infos    = new PluginDatainjectionInfoCollection();
    }

    public function canViewItem(): bool
    {

        if ($this->isPrivate() && $this->fields['users_id'] != Session::getLoginUserID()) {
            return false;
        }

        if (
            !$this->isPrivate()
            && !Session::haveAccessToEntity($this->getEntityID(), $this->isRecursive())
        ) {
            return false;
        }

        return self::checkRightOnModel($this->fields['id']);
    }


    public function canCreateItem(): bool
    {

        if (
            $this->isPrivate()
            && ($this->fields['users_id'] != Session::getLoginUserID())
        ) {
            return false;
        }

        if (
            !$this->isPrivate()
            && !Session::haveAccessToEntity($this->getEntityID())
        ) {
            return false;
        }

        return self::checkRightOnModel($this->fields['id']);
    }


    //Loading methods
    public function loadMappings()
    {

        $this->mappings->load($this->fields['id']);
    }


    //Loading methods
    public function loadInfos()
    {

        $this->infos->load($this->fields['id']);
    }


    //---- Getters -----//
    public function getMandatoryMappings()
    {

        return $this->mappings->getMandatoryMappings();
    }


    //---- Getters -----//
    public function getMappings()
    {

        return $this->mappings->getAllMappings();
    }


    public function getInfos()
    {

        return $this->infos->getAllInfos();
    }


    public function getBackend()
    {

        return $this->backend;
    }


    public function getMappingByName($name)
    {

        return $this->mappings->getMappingByName($name);
    }


    public function getMappingByRank($rank)
    {

        return $this->mappings->getMappingByRank($rank);
    }


    public function getFiletype()
    {

        return $this->fields["filetype"];
    }


    public function getBehaviorAdd()
    {

        return $this->fields["behavior_add"];
    }


    public function getBehaviorUpdate()
    {

        return $this->fields["behavior_update"];
    }


    public function getItemtype()
    {

        return $this->fields["itemtype"];
    }


    public function getEntity()
    {

        return $this->fields["entities_id"];
    }


    public function getCanAddDropdown()
    {

        return $this->fields["can_add_dropdown"];
    }


    public function getCanOverwriteIfNotEmpty()
    {

        return $this->fields["can_overwrite_if_not_empty"];
    }


    public function getDateFormat()
    {

        return $this->fields["date_format"];
    }


    public function getFloatFormat()
    {

        return $this->fields["float_format"];
    }


    public function getPortUnicity()
    {

        return $this->fields["port_unicity"];
    }


    public function getReplaceMultilineValue()
    {

        return $this->fields["replace_multiline_value"];
    }


    public function getNumberOfMappings()
    {

        if ($this->mappings) {
            return count($this->mappings);
        }
        return false;
    }


    public function getSpecificModel()
    {

        return  $this->specific_model;
    }


    /**
    * @param array $options   array
    *
    * @return void
   **/
    public static function dropdown($options = [])
    {
        /** @var array $CFG_GLPI */
        global $CFG_GLPI;

        $models = self::getModels(
            Session::getLoginUserID(),
            'name',
            $_SESSION['glpiactive_entity'],
            false,
        );
        $p      = ['models_id' => '__VALUE__'];

        $value = $_SESSION['datainjection']['models_id'] ?? 0;

        $rand = mt_rand();
        echo "\n<select name='dropdown_models' id='dropdown_models$rand'>";
        $prev = -2;
        echo "\n<option value='0'>" . Dropdown::EMPTY_VALUE . "</option>";

        foreach ($models as $model) {
            if ($model['entities_id'] != $prev) {
                if ($prev >= -1) {
                    echo "</optgroup>\n";
                }

                if ($model['entities_id'] == -1) {
                    echo "\n<optgroup label='" . __s('Private') . "'>";
                } else {
                    echo "\n<optgroup label=\"" . Dropdown::getDropdownName(
                        "glpi_entities",
                        $model['entities_id'],
                    ) . "\">";
                }
                $prev = $model['entities_id'];
            }

            $selected = $model['id'] == $value ? "selected" : "";

            $comment = $model['comment'] ? "title='" . htmlentities($model['comment'], ENT_QUOTES, 'UTF-8') . "'" : "";
            echo "\n<option value='" . $model['id'] . "' $selected $comment>" . $model['name'] . "</option>";
        }

        if ($prev >= -1) {
            echo "</optgroup>";
        }
        echo "</select>";

        $url = $CFG_GLPI['root_doc'] . "/plugins/datainjection/ajax/dropdownSelectModel.php";
        Ajax::updateItemOnSelectEvent("dropdown_models$rand", "span_injection", $url, $p);

        return;
    }


    /**
    * @param int $user_id
    * @param string $order        (default 'name')
    * @param int|string $entity       (default -1)
    * @param boolean $all          (false by default)
   **/
    public static function getModels($user_id, $order = "name", $entity = -1, $all = false)
    {
        /** @var DBmysql $DB */
        global $DB;

        $models =  [];
        $query = "SELECT `id`, `name`, `is_private`, `entities_id`, `is_recursive`, `itemtype`,
                       `step`, `comment`
                FROM `glpi_plugin_datainjection_models` ";

        if (!$all) {
            $query .= " WHERE `step` = '" . self::READY_TO_USE_STEP . "' AND (";
        } else {
            $query .= " WHERE (";
        }

        $query .= "(`is_private` = '" . self::MODEL_PUBLIC . "'" .
                getEntitiesRestrictRequest(
                    " AND",
                    "glpi_plugin_datainjection_models",
                    "entities_id",
                    $entity,
                    false,
                ) . ")
                  OR (`is_private` = '" . self::MODEL_PRIVATE . "' AND `users_id` = '$user_id'))
                 ORDER BY `is_private` DESC,
                          `entities_id`, " . ($order == "`name`" ? "`name`" : $order);

        foreach ($DB->doQuery($query) as $data) {
            if (
                self::checkRightOnModel($data['id'])
                && class_exists($data['itemtype'])
            ) {
                $models[] = $data;
            }
        }

        return $models;
    }


    //Standard functions
    public function rawSearchOptions()
    {

        $tab = [];

        $tab[] = [
            'id'   => 'common',
            'name' => self::getTypeName(),
        ];

        $tab[] = [
            'id'            => 1,
            'table'         => $this->getTable(),
            'field'         => 'name',
            'name'          => __s('Name'),
            'datatype'      => 'itemlink',
            'itemlink_type' => $this->getType(),
            'autocomplete'  => true,
        ];

        $tab[] = [
            'id'            => 2,
            'table'         => $this->getTable(),
            'field'         => 'id',
            'name'          => __s('ID'),
        ];

        $tab[] = [
            'id'            => 3,
            'table'         => $this->getTable(),
            'field'         => 'behavior_add',
            'name'          => __s('Allow lines creation', 'datainjection'),
            'datatype'      => 'bool',
            'massiveaction' => false,
        ];

        $tab[] = [
            'id'            => 4,
            'table'         => $this->getTable(),
            'field'         => 'behavior_update',
            'name'          => __s('Allow lines update', 'datainjection'),
            'datatype'      => 'bool',
            'massiveaction' => false,
        ];

        $tab[] = [
            'id'            => 5,
            'table'         => $this->getTable(),
            'field'         => 'itemtype',
            'name'          => __s('Type of data to import', 'datainjection'),
            'datatype'      => 'itemtypename',
            'nosearch'      => true,
            'massiveaction' => false,
        ];

        $tab[] = [
            'id'            => 6,
            'table'         => $this->getTable(),
            'field'         => 'can_add_dropdown',
            'name'          => __s('Allow creation of dropdowns (Except Entity)', 'datainjection'),
            'datatype'      => 'bool',
        ];

        $tab[] = [
            'id'            => 7,
            'table'         => $this->getTable(),
            'field'         => 'date_format',
            'name'          => __s('Dates format', 'datainjection'),
            'datatype'      => 'specific',
            'searchtype'    => 'equals',
        ];

        $tab[] = [
            'id'            => 8,
            'table'         => $this->getTable(),
            'field'         => 'float_format',
            'name'          => __s('Float format', 'datainjection'),
            'datatype'      => 'specific',
            'searchtype'    => 'equals',
        ];

        $tab[] = [
            'id'            => 10,
            'table'         => $this->getTable(),
            'field'         => 'port_unicity',
            'name'          => __s('Port unicity criteria', 'datainjection'),
            'datatype'      => 'specific',
            'searchtype'    => 'equals',
        ];

        $tab[] = [
            'id'            => 11,
            'table'         => $this->getTable(),
            'field'         => 'is_private',
            'name'          => __s('Private'),
            'datatype'      => 'bool',
            'massiveaction' => false,
        ];

        $tab[] = [
            'id'            => 12,
            'table'         => $this->getTable(),
            'field'         => 'step',
            'name'          => __s('Status'),
            'datatype'      => 'specific',
            'searchtype'    => 'equals',
            'massiveaction' => false,
        ];

        $tab[] = [
            'id'            => 16,
            'table'         => $this->getTable(),
            'field'         => 'comment',
            'name'          => __s('Comments'),
            'datatype'      => 'text',
        ];

        $tab[] = [
            'id'            => 80,
            'table'         => 'glpi_entities',
            'field'         => 'completename',
            'name'          => __s('Entity'),
            'datatype'      => 'dropdown',
        ];

        $tab[] = [
            'id'            => 86,
            'table'         => $this->getTable(),
            'field'         => 'is_recursive',
            'name'          => __s('Child entities'),
            'datatype'      => 'bool',
        ];

        $tab[] = [
            'id'            => 87,
            'table'         => $this->getTable(),
            'field'         => 'replace_multiline_value',
            'name'          => __s('Replacing the value of multiline text fields', 'datainjection'),
            'datatype'      => 'bool',
        ];

        return $tab;
    }


    /**
    * @since version 2.3.0
    *
    * @param string $field
    * @param array|string $values
    * @param array $options   array
   **/
    public static function getSpecificValueToDisplay($field, $values, array $options = [])
    {

        if (!is_array($values)) {
            $values = [$field => $values];
        }
        switch ($field) {
            case "port_unicity":
                return PluginDatainjectionDropdown::getPortUnicityValues($values['port_unicity']);

            case "float_format":
                return PluginDatainjectionDropdown::getFloatFormat($values['float_format']);

            case "date_format":
                return PluginDatainjectionDropdown::getDateFormat($values['date_format']);

            case "step":
                return PluginDatainjectionDropdown::getStatusLabel($values['step']);
        }
        return parent::getSpecificValueToDisplay($field, $values, $options);
    }


    /**
    * @since version 2.3.0
    *
    * @param string $field
    * @param string $name               (default '')
    * @param string|array $values             (defaut '')
    * @param array $options   array
   **/
    public static function getSpecificValueToSelect($field, $name = '', $values = '', array $options = [])
    {

        if (!is_array($values)) {
            $values = [$field => $values];
        }
        $options['display'] = false;
        switch ($field) {
            case 'step':
                $options['value'] = $values[$field];
                return Dropdown::showFromArray(
                    $name,
                    PluginDatainjectionDropdown::statusLabels(),
                    $options,
                );

            case 'port_unicity':
                $options['value'] = $values[$field];
                return Dropdown::showFromArray(
                    $name,
                    PluginDatainjectionDropdown::portUnicityValues(),
                    $options,
                );

            case 'float_format':
                $options['value'] = $values[$field];
                return Dropdown::showFromArray(
                    $name,
                    PluginDatainjectionDropdown::floatFormats(),
                    $options,
                );

            case 'date_format':
                $options['value'] = $values[$field];
                return Dropdown::showFromArray(
                    $name,
                    PluginDatainjectionDropdown::dateFormats(),
                    $options,
                );
        }
        return parent::getSpecificValueToSelect($field, $name, $values, $options);
    }


    public function showForm($ID, $options = [])
    {

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

    public function showAdvancedForm($ID, $options = [])
    {
        if ($ID > 0) {
            $this->check($ID, READ);
        } else {
            // Create item
            $this->check(-1, UPDATE);
            $this->getEmpty();
            $options['step'] = 1;
        }

        $rand = mt_rand();

        $status = PluginDatainjectionDropdown::getStatusLabel($this->fields['step']);
        if (!empty($status)) {
            $status_color = PluginDatainjectionDropdown::getStatusColor($this->fields['step']);
            $status_label = '<span class="badge" style="background-color: ' . $status_color . '; color:white;">' . $status . '</span>';
        }

        $data = [
            'id' => $ID,
            'url' => Toolbox::getItemTypeFormURL(self::class),
            'type_name' => self::getTypeName(),
            'status_label' => $status_label ?? $status,
            'values' => $this->fields,
            'replace_multiline_value' => $this->fields['replace_multiline_value'],
            'rand' => $rand,
            'date_formats' => PluginDatainjectionDropdown::dateFormats(),
            'float_formats' => PluginDatainjectionDropdown::floatFormats(),
            'port_unicity_values' => PluginDatainjectionDropdown::portUnicityValues(),
            'can_overwrite_if_not_empty' => $this->fields['can_overwrite_if_not_empty'] ?? 0,
            'visibility_options' => [1 => __s('Private'), 0 => __s('Public')],
            'options' => $options,
            'initial_step' => self::INITIAL_STEP,
            'session_user_id' => Session::getLoginUserID(),
            'injection_types' => PluginDatainjectionInjectionType::getItemtypes(),
            'item' => $this,
        ];

        TemplateRenderer::getInstance()->display('@datainjection/model_advanced_form.html.twig', $data);

        if ($ID > 0) {
            $tmp = self::getInstance('csv');
            $tmp->showAdditionnalForm($this);
        }

        $this->showFormButtons($options);
        echo "</form>";
        return true;
    }


    public function showValidationForm()
    {
        $data = [
            'url' => Toolbox::getItemTypeFormURL(self::class),
            'id' => $this->fields['id'],
        ];

        TemplateRenderer::getInstance()->display('@datainjection/model_validation_form.html.twig', $data);

        return true;
    }


    //Tabs management
    public function defineTabs($options = [])
    {

        $tabs = [];
        $this->addStandardTab(self::class, $tabs, $options);
        $this->addStandardTab('Log', $tabs, $options);
        return $tabs;
    }


    /**
    * Tabs management
    *
    * @return array|string
   **/
    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {

        $canedit = Session::haveRight('plugin_datainjection_model', UPDATE);

        if (!$withtemplate && $item instanceof self) {
            $tabs[1] = self::createTabEntry(__s('Model'), 0, $item::getType(), self::getIcon());
            if (!$this->isNewID($item->fields['id'])) {
                if ($canedit) {
                    $tabs[3] = self::createTabEntry(__s('File to inject', 'datainjection'), 0, $item::getType(), 'ti ti-file-download');
                }
                $tabs[4] = self::createTabEntry(__s('Mappings', 'datainjection'), 0, $item::getType(), 'ti ti-columns');
                if ($item->fields['step'] > self::MAPPING_STEP) {
                    $tabs[5] = self::createTabEntry(__s('Additional Information', 'datainjection'), 0, $item::getType(), 'ti ti-code-variable-plus');
                    if ($canedit && $item->fields['step'] != self::READY_TO_USE_STEP) {
                        $tabs[6] = self::createTabEntry(__s('Validation'), 0, $item::getType(), 'ti ti-checklist');
                    }
                }
            }
            return $tabs;
        }

        return '';
    }


    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {

        if ($item instanceof self) {
            switch ($tabnum) {
                case 1:
                    $item->showAdvancedForm($item->getID());
                    break;

                case 3:
                    $options['confirm']   = 'creation';
                    $options['models_id'] = $item->fields['id'];
                    $options['add_form']  = true;
                    $options['submit']    = __s('Load this file', 'datainjection');
                    PluginDatainjectionClientInjection::showUploadFileForm($options);
                    break;

                case 4:
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


    public function cleanDBonPurge()
    {

        $itemtypes = ["PluginDatainjectionModelcsv", "PluginDatainjectionMapping",
            "PluginDatainjectionInfo",
        ];

        foreach ($itemtypes as $itemtype) {
            $item = new $itemtype();
            $item->deleteByCriteria(["models_id" => $this->getID()]);
        }
    }


    /**
    * Clean all model which match some criteria
    *
    * @param array $crit array of criteria (ex array('itemtype'=>'PluginAppliancesAppliance'))
   **/
    public static function clean($crit = [])
    {
        /** @var DBmysql $DB */
        global $DB;

        $model = new self();

        if ((count($crit) > 0)) {
            $model->deleteByCriteria($crit);
        }
    }


    /**
    * @param int $models_id
    * @param int $step
   **/
    public static function changeStep($models_id, $step)
    {

        $model = new self();
        if ($model->getFromDB($models_id)) {
            $model->dohistory = false;
            $tmp['id']        = $models_id;
            $tmp['step']      = $step;
            $model->update($tmp);
            $model->dohistory = false;
        }
    }


    public function prepareInputForAdd($input)
    {

        //If no behavior selected
        if (!isset($input['name']) || ($input['name'] == '')) {
            Session::addMessageAfterRedirect(
                __s('Please enter a name for the model', 'datainjection'),
                true,
                ERROR,
                true,
            );
            return false;
        }

        if (!$input['behavior_add'] && !$input['behavior_update']) {
            Session::addMessageAfterRedirect(
                __s(
                    'Your model should allow import and/or update of data',
                    'datainjection',
                ),
                true,
                ERROR,
                true,
            );
            return false;
        }

        $input['step'] = self::FILE_STEP;

        return $input;
    }


    public function prepareInputForUpdate($input)
    {

        if (isset($input['is_private']) && $input['is_private'] == 1 && (isset($input['users_id']) && $input['users_id'] != $this->fields['users_id'])) {
            Session::addMessageAfterRedirect(
                __s(
                    'You are not the initial creator of this model',
                    'datainjection',
                ),
                true,
                ERROR,
                true,
            );
            return false;
        }
        return $input;
    }


    /**
    * Get the backend implementation by type
    *
    * @param string $type
   **/
    public static function getInstance($type)
    {

        $class = 'PluginDatainjectionModel' . $type;
        if (is_a($class, CommonDBTM::class, true)) {
            return new $class();
        }
        return false;
    }


    public static function getInstanceByModelID($models_id)
    {

        $model    = new self();
        $model->getFromDB($models_id);
        $specific = self::getInstance($model->getFiletype());
        $specific->getFromDBByModelID($models_id);
        return $model;
    }


    /**
    * @param array $options   array
   **/
    public function readUploadedFile($options = [])
    {

        $file_encoding     = ($options['file_encoding'] ?? PluginDatainjectionBackend::ENCODING_AUTO);
        $webservice        = ($options['webservice'] ?? false);
        $original_filename = ($options['original_filename'] ?? false);
        $unique_filename   = ($options['unique_filename'] ?? false);
        $injectionData     = false;
        $delete_file       = ($options['delete_file'] ?? true);

        //Get model & model specific fields
        $this->loadSpecificModel();

        if (!$webservice) {
            //Get and store uploaded file
            $original_filename           = $_FILES['filename']['name'];
            $temporary_uploaded_filename = $_FILES["filename"]["tmp_name"];
            $unique_filename             = tempnam(realpath(PLUGIN_DATAINJECTION_UPLOAD_DIR), "PWS");

            if (!move_uploaded_file($temporary_uploaded_filename, $unique_filename)) {
                return ['status'  => PluginDatainjectionCommonInjectionLib::FAILED,
                    'message' => sprintf(
                        __s('Impossible to copy the file in %s', 'datainjection'),
                        realpath(PLUGIN_DATAINJECTION_UPLOAD_DIR),
                    ),
                ];
            }
        }

        //If file has not the right extension, reject it and delete if
        if ($this->specific_model->checkFileName($original_filename)) {
            $message  = __s('File format is wrong', 'datainjection');
            $message .= "<br>" . __s('Extension csv required', 'datainjection');
            if (!$webservice) {
                Session::addMessageAfterRedirect($message, true, ERROR, false);
            }
            //unlink($temporary_uniquefilename);
            return ['status'  => ERROR,
                'message' => $message,
            ];
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
    public function loadSpecificModel()
    {

        $specific_model = self::getInstance($this->getFiletype());
        $specific_model->getFromDBByModelID($this->fields['id']);
        $this->specific_model = $specific_model;
    }


    /**
    * Once file is uploaded, process it
    *
    * @param array $options   array of possible options:
    *   - file_encoding
    *   - mode
    *
    * @return boolean
   **/
    public function processUploadedFile($options = [])
    {

        $mode          = ($options['mode'] ?? self::PROCESS);

        //Get model & model specific fields
        $this->loadSpecificModel();
        $this->readUploadedFile($options);
        if (!$this->injectionData) {
            return false;
        }

        if ($mode == self::PROCESS) {
            $this->loadMappings();
            $check = $this->isFileCorrect();
        } else {
            $check['status'] = PluginDatainjectionCommonInjectionLib::SUCCESS;
        }
        //There's an error
        if ($check['status'] != PluginDatainjectionCommonInjectionLib::SUCCESS && $mode == self::PROCESS) {
            Session::addMessageAfterRedirect($check['error_message'], true, ERROR);
            return false;
        }

        $mappingCollection = new PluginDatainjectionMappingCollection();

        //Delete existing mappings only in model creation mode !!
        if ($mode == self::CREATION) {
            //If mapping still exists in DB, delete all of them !
            $mappingCollection->deleteMappingsFromDB($this->fields['id']);
        }

        $rank = 0;
        //Build the mappings list
        foreach (
            PluginDatainjectionBackend::getHeader(
                $this->injectionData,
                $this->specific_model->isHeaderPresent(),
            ) as $data
        ) {
            $mapping = new PluginDatainjectionMapping();
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
            Session::addMessageAfterRedirect(__s('The file is ok.', 'datainjection'), true, INFO);
        }

        return true;
    }


    /**
    * Try to parse an input file
    *
    * @return array true if the file is a CSV file
   **/
    public function isFileCorrect()
    {

        //Get CSV file first line
        $header = PluginDatainjectionBackend::getHeader(
            $this->injectionData,
            $this->specific_model->isHeaderPresent(),
        );

        //If file columns don't match number of mappings in DB
        $nb = count($this->getMappings());
        if ($nb != count($header)) {
            $error_message  = __s('The number of columns of the file is incorrect.', 'datainjection') . "\n";
            $error_message .= sprintf(
                _sn('%d awaited column', '%d awaited columns', $nb, 'datainjection'),
                $nb,
            ) . "\n";
            $error_message .= sprintf(
                _sn(
                    '%d found column',
                    '%d found columns',
                    count($header),
                    'datainjection',
                ),
                count($header),
            );

            return ['status'         => PluginDatainjectionCommonInjectionLib::FAILED,
                'field_in_error' => false,
                'error_message'  => $error_message,
            ];
        }

        //If no header in the CSV file, exit method
        if (!$this->specific_model->isHeaderPresent()) {
            return ['status'         => PluginDatainjectionCommonInjectionLib::SUCCESS,
                'field_in_error' => false,
                'error_message'  => '',
            ];
        }

        $error = ['status'         => PluginDatainjectionCommonInjectionLib::SUCCESS,
            'field_in_error' => false,
            'error_message'  => '',
        ];

        //Check each mapping to be sure it has exactly the same name
        foreach ($this->getMappings() as $key => $mapping) {
            if (!isset($header[$key])) {
                $error['status']         = PluginDatainjectionCommonInjectionLib::FAILED;
                $error['field_in_error'] = $key;
            } else {
                //If name of the mapping is not equal in the csv file header and in the DB
                $name_from_file = trim(
                    mb_strtoupper(
                        stripslashes($header[$mapping->getRank()]),
                        'UTF-8',
                    ),
                );
                $name_from_db   = trim(mb_strtoupper(stripslashes($mapping->getName()), 'UTF-8'));

                if ($name_from_db != $name_from_file) {
                    if ($error['error_message'] == '') {
                        $error['error_message'] = __s('At least one column is incorrect', 'datainjection');
                    }

                    $error['status']         = PluginDatainjectionCommonInjectionLib::FAILED;
                    $error['field_in_error'] = false;

                    $error['error_message'] .= "<br>" . sprintf(
                        __s('%1$s: %2$s'),
                        __s('Into the file', 'datainjection'),
                        $name_from_file,
                    ) . "\n";
                    $error['error_message'] .= sprintf(
                        __s('%1$s: %2$s'),
                        __s('From the model', 'datainjection'),
                        $name_from_db,
                    );
                }
            }
        }
        return $error;
    }


    /**
    * @param array $fields
   **/
    public function checkMandatoryFields($fields)
    {

        //Load infos associated with the model
        $this->loadInfos();
        $check = true;

        foreach ($this->infos->getAllInfos() as $info) {
            if ($info->isMandatory()) {
                //Get search option (need to check dropdown default value)
                $itemtype = $info->getInfosType();
                if (is_a($itemtype, CommonDBTM::class, true)) {
                    $item     = new $itemtype();
                    $option   = $item->getSearchOptionByField('field', $info->getValue());
                    $tocheck  = (!isset($option['datatype']) || ($option['datatype'] != 'bool'));
                    if (
                        !isset($fields[$info->getValue()])
                        //Check if no value defined only when it's not a yes/no
                        || ($tocheck && !$fields[$info->getValue()])
                        || ($fields[$info->getValue()] == 'NULL')
                    ) {
                        $check = false;
                        break;
                    }
                }
            }
        }
        return $check;
    }


    /**
    * Model is now ready to be used
   **/
    public function switchReadyToUse()
    {

        $tmp         = $this->fields;
        $tmp['step'] = self::READY_TO_USE_STEP;
        $this->update($tmp);
    }


    public function populateSeveraltimesMappedFields()
    {

        $this->severaltimes_mapped
             = PluginDatainjectionMapping::getSeveralMappedField($this->fields['id']);
    }


    /**
    * @param int $models_id
   **/
    public static function checkRightOnModel($models_id)
    {
        /** @var DBmysql $DB */
        global $DB;

        $continue = true;

        $model = new self();
        if ($model->getFromDB($models_id)) {
            $query = "(SELECT `itemtype`
                    FROM `glpi_plugin_datainjection_models`
                    WHERE `id` = '" . $models_id . "')
                    UNION (SELECT DISTINCT `itemtype`
                        FROM `glpi_plugin_datainjection_mappings`
                        WHERE `models_id` = '" . $models_id . "')
                    UNION (SELECT DISTINCT `itemtype`
                        FROM `glpi_plugin_datainjection_infos`
                        WHERE `models_id` = '" . $models_id . "')";
            foreach ($DB->doQuery($query) as $data) {
                if ($data['itemtype'] != PluginDatainjectionInjectionType::NO_VALUE && is_a($data['itemtype'], CommonDBTM::class, true)) {
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


    public static function cleanSessionVariables()
    {

        //Reset parameters stored in session
        PluginDatainjectionSession::removeParams();
        PluginDatainjectionSession::setParam('infos', []);
    }


    /**
    * @param int $models_id
   **/
    public static function showPreviewMappings($models_id)
    {

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

                foreach ($mappings as $mapping) {
                    echo"<th style='height:40px'>" . stripslashes($mapping->getMappingName()) . "</th>";
                }
                echo "</tr>";
                unset($lines[0]);
            }

            foreach ($lines as $line) {
                echo "<tr class='tab_bg_2'>";
                foreach ($line[0] as $value) {
                    echo "<td>" . $value . "</td>";
                }
                echo "</tr>";
            }
        }
        Html::closeForm();
        echo "<div style='margin-top:15px;text-align:center'>";
        echo "<a href='javascript:window.close()'>" . __s('Close') . "</a>";
        echo "</div>";
    }


    /**
    * @param int $models_id
   **/
    public static function prepareLogResults($models_id)
    {
        /** @var array $CFG_GLPI */
        global $CFG_GLPI;

        $results   = json_decode(
            PluginDatainjectionSession::getParam('results'),
            true,
        );
        $todisplay = [];
        $model     = new self();
        $model->getFromDB($models_id);

        if (!empty($results)) {
            foreach ($results as $result) {
                $tmp = ['line'           => $result['line'],
                    'status'         => $result['status'],
                    'check_sumnary'  => PluginDatainjectionCommonInjectionLib::getLogLabel(PluginDatainjectionCommonInjectionLib::SUCCESS),
                    'check_message'  => PluginDatainjectionCommonInjectionLib::getLogLabel(PluginDatainjectionCommonInjectionLib::SUCCESS),
                    'type'           => __s('Undetermined', 'datainjection'),
                    'status_message' => PluginDatainjectionCommonInjectionLib::getLogLabel($result['status']),
                    'itemtype'       => $model->fields['itemtype'],
                    'url'            => '',
                    'item'           => '',
                ];

                if (isset($result[PluginDatainjectionCommonInjectionLib::ACTION_CHECK])) {
                    $check_infos          = $result[PluginDatainjectionCommonInjectionLib::ACTION_CHECK];
                    $tmp['check_status']  = $check_infos['status'];
                    $tmp['check_sumnary'] = PluginDatainjectionCommonInjectionLib::getLogLabel($check_infos['status']);
                    $tmp['check_message'] = '';
                    $first                = true;

                    foreach ($check_infos as $key => $val) {
                        if (
                            ($key !== 'status')
                            && ($val[0] != PluginDatainjectionCommonInjectionLib::SUCCESS)
                        ) {
                            $tmp['check_message'] .= ($first ? '' : "\n") .
                                      sprintf(
                                          __s('%1$s (%2$s)'),
                                          PluginDatainjectionCommonInjectionLib::getLogLabel($val[0]),
                                          $val[1],
                                      );
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
                    $url         = Toolbox::getItemTypeFormURL($model->fields['itemtype']) . "?id=" .
                                                  $result[$model->fields['itemtype']];
                    //redefine genericobject url of needed
                    $plugin = new Plugin();
                    if (
                        class_exists('PluginGenericobjectType')
                        && $plugin->isActivated('genericobject')
                        && array_key_exists($model->fields['itemtype'], PluginGenericobjectType::getTypes())
                    ) {
                        $url = $CFG_GLPI['root_doc'] . "/plugins/datainjection/front/object.form.php" .
                        "?itemtype=" . $model->fields['itemtype'] . "&id=" . $result[$model->fields['itemtype']];
                    }

                    $tmp['url']  = "<a href='" . $url . "'>" . $result[$model->fields['itemtype']] . "</a>";
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
    * @param int $models_id
   **/
    public static function showLogResults($models_id)
    {
        $logresults = self::prepareLogResults($models_id);
        $resume = [];
        $nblines = 0;

        // Prépare le résumé
        foreach ($logresults as $status => $results) {
            foreach ($results as $result) {
                if (!isset($resume[$result['status']][$result['type']])) {
                    $resume[$result['status']][$result['type']] = 0;
                }
                $resume[$result['status']][$result['type']]++;
                $nblines++;
            }
        }

        $data = [
            'logresults' => $logresults,
            'resume'     => $resume,
            'nblines'    => $nblines,
            'SUCCESS'    => PluginDatainjectionCommonInjectionLib::SUCCESS,
            'FAILED'     => PluginDatainjectionCommonInjectionLib::FAILED,
        ];

        TemplateRenderer::getInstance()->display('@datainjection/log_results.html.twig', $data);
    }

    public static function exportAsPDF($models_id)
    {

        $logresults = self::prepareLogResults($models_id);
        $model      = new self();
        $model->getFromDB($models_id);

        if (!empty($logresults) && class_exists('PluginPdfSimplePDF')) {
            $pdf = new PluginPdfSimplePDF('a4', 'landscape');
            $pdf->setHeader(
                sprintf(
                    __s('%1$s (%2$s)'),
                    __s('Data injection report', 'datainjection') . ' - <b>' .
                                 PluginDatainjectionSession::getParam('file_name') . '</b>',
                    $model->getName(),
                ),
            );
            $pdf->newPage();

            if (isset($logresults[PluginDatainjectionCommonInjectionLib::SUCCESS])) {
                $pdf->setColumnsSize(100);
                $pdf->displayTitle('<b>' . __s('Array of successful injections', 'datainjection') . '</b>');
                $pdf->setColumnsSize(6, 54, 20, 20);
                $pdf->setColumnsAlign('center', 'center', 'center', 'center');
                $col0 = '<b>' . __s('Line', 'datainjection') . '</b>';
                $col1 = '<b>' . __s('Data Import', 'datainjection') . '</b>';
                $col2 = '<b>' . __s('Injection type', 'datainjection') . '</b>';
                $col3 = '<b>' . __s('Object Identifier', 'datainjection') . '</b>';
                $pdf->displayTitle($col0, $col1, $col2, $col3);

                $index = 0;
                foreach ($logresults[PluginDatainjectionCommonInjectionLib::SUCCESS] as $result) {
                    $pdf->displayLine(
                        $result['line'],
                        $result['status_message'],
                        $result['type'],
                        $result['item'],
                    );
                }
            }

            if (isset($logresults[PluginDatainjectionCommonInjectionLib::FAILED])) {
                $pdf->setColumnsSize(100);
                $pdf->displayTitle('<b>' . __s('Array of unsuccessful injections', 'datainjection') . '</b>');
                $pdf->setColumnsSize(6, 16, 38, 20, 20);
                $pdf->setColumnsAlign('center', 'center', 'center', 'center', 'center');
                $col0 = '<b>' . __s('Line', 'datainjection') . '</b>';
                $col1 = '<b>' . __s('Data check', 'datainjection') . '</b>';
                $col2 = '<b>' . __s('Data Import', 'datainjection') . '</b>';
                $col3 = '<b>' . __s('Injection type', 'datainjection') . '</b>';
                $col4 = '<b>' . __s('Object Identifier', 'datainjection') . '</b>';
                $pdf->displayTitle($col0, $col1, $col2, $col3, $col4);

                $index = 0;
                foreach ($logresults[PluginDatainjectionCommonInjectionLib::FAILED] as $result) {
                    $pdf->setColumnsSize(6, 16, 38, 20, 20);
                    $pdf->setColumnsAlign('center', 'center', 'center', 'center', 'center');
                    $pdf->displayLine(
                        $result['line'],
                        $result['check_sumnary'],
                        $result['status_message'],
                        $result['type'],
                        $result['item'],
                    );

                    if ($result['check_message']) {
                        $pdf->displayText(
                            '<b>' . __s('Data check', 'datainjection') . '</b> :',
                            $result['check_message'],
                            1,
                        );
                    }
                }
            }
            $pdf->render();
        }
    }


    public function cleanData()
    {

        $this->injectionData = [];
    }

    public static function getIcon()
    {
        return "ti ti-stack-2";
    }
}
