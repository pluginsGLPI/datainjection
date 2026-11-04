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

use function Safe\ob_start;
use function Safe\ob_get_clean;

class PluginDatainjectionInfo extends CommonDBTM
{
    public static $rightname = "plugin_datainjection_model";

    public function getEmpty()
    {
        $this->fields['itemtype']     = PluginDatainjectionInjectionType::NO_VALUE;
        $this->fields['value']        = PluginDatainjectionInjectionType::NO_VALUE;
        $this->fields['is_mandatory'] = 0;

        return true;
    }


    public function isMandatory()
    {
        return $this->fields["is_mandatory"];
    }

    public function getValue()
    {

        return $this->fields["value"];
    }


    public function getID()
    {

        return $this->fields["id"];
    }


    public function getInfosType()
    {

        return $this->fields["itemtype"];
    }

    /**
    * @param PluginDatainjectionModel $model     PluginDatainjectionModel object
    * @param boolean $canedit   (false by default)
   **/
    public static function showAddInfo(PluginDatainjectionModel $model, $canedit = false)
    {

        if ($canedit) {
            $info = new PluginDatainjectionInfo();
            $info->getEmpty();
            $info->fields['id']        = -1;
            $info->fields['models_id'] = $model->fields['id'];

            TemplateRenderer::getInstance()->display('@datainjection/info_add_form.html.twig', [
                'form_action' => Toolbox::getItemTypeFormURL(self::class),
                'fields_url'  => plugin_datainjection_geturl() . "ajax/fieldsChoices.php",
                'info'        => PluginDatainjectionInjectionType::getRow($info, $model->fields['itemtype']),
                'model_id'    => $model->fields['id'],
            ]);
        }
    }


    /**
     * Display additional information form from Model form
     */
    public static function showFormInfos(PluginDatainjectionModel $model)
    {

        $canedit = $model->can($model->fields['id'], UPDATE);
        self::showAddInfo($model, $canedit);

        $model->loadInfos();
        if (count($model->getInfos()) === 0) {
            return;
        }

        $infos = [];
        foreach ($model->getInfos() as $info) {
            $infos[] = PluginDatainjectionInjectionType::getRow($info, $model->fields['itemtype']);
        }

        TemplateRenderer::getInstance()->display('@datainjection/infos_form.html.twig', [
            'form_action' => Toolbox::getItemTypeFormURL(self::class),
            'fields_url'  => plugin_datainjection_geturl() . "ajax/fieldsChoices.php",
            'infos'       => $infos,
            'nb_mandatory' => count(array_filter(array_column($infos, 'is_mandatory'))),
            'canedit'     => $canedit,
            'select_all'  => ($_GET['select'] ?? '') === 'all',
            'model_id'    => $model->fields['id'],
        ]);
    }


    /**
    * @param int $models_id
    * @param array $infos        array
   **/
    public static function manageInfos($models_id, $infos = [])
    {
        /** @var DBmysql $DB */
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
                    $info_infos['is_mandatory'] = (isset($info_infos['is_mandatory']) ? 1 : 0);
                }

                if ($id > 0) {
                    $existing = new self();
                    if ($existing->getFromDB($id) && $existing->fields['models_id'] == $models_id) {
                        $info->update($info_infos);
                    }
                } else {
                    $info_infos['models_id'] = $models_id;
                    unset($info_infos['id']);
                    $info->add($info_infos);
                }
            }
        }

        $info->deleteByCriteria(
            ['models_id' => $models_id,
                'value'     => PluginDatainjectionInjectionType::NO_VALUE,
            ],
        );
    }


    /**
    * @param PluginDatainjectionModel $model     PluginDatainjectionModel object
   **/
    public static function showAdditionalInformationsForm(PluginDatainjectionModel $model)
    {
        $infos = getAllDataFromTable(
            'glpi_plugin_datainjection_infos',
            ['models_id' => $model->getField('id')],
        );

        $modeltype = PluginDatainjectionModel::getInstance($model->getField('filetype'));
        $modeltype->getFromDBByModelID($model->getField('id'));

        $rendered_infos = [];
        foreach ($infos as $info_data) {
            $info = new self();
            $info->fields = $info_data;

            ob_start();
            self::displayAdditionalInformation($info, $_SESSION['datainjection']['infos'] ?? []);
            $rendered_infos[] = ob_get_clean();
        }

        $data = [
            'infos' => $infos,
            'rendered_infos' => $rendered_infos,
            'model' => $model,
            'modeltype' => $modeltype,
            'has_sample' => $modeltype->haveSample(),
            'comment' => $model->fields['comment'],
            'session_infos' => $_SESSION['datainjection']['infos'] ?? [],
        ];

        // Store models_id in session for future usage
        $_SESSION['datainjection']['models_id'] = $model->getField('id');

        // Render the Twig template
        TemplateRenderer::getInstance()->display('@datainjection/infoadditionnalinfo.html.twig', $data);

        // Show the upload file form
        $options['models_id'] = $model->getField('id');
        $options['confirm'] = 'process';
        PluginDatainjectionClientInjection::showUploadFileForm($options);
    }


    /**
    * @param PluginDatainjectionInfo $info               PluginDatainjectionInfo object
    * @param array $values    array
    */
    public static function displayAdditionalInformation(PluginDatainjectionInfo $info, $values = [])
    {

        $injectionClass
        = PluginDatainjectionCommonInjectionLib::getInjectionClassInstance($info->fields['itemtype']);
        $option
        = PluginDatainjectionCommonInjectionLib::findSearchOption(
            $injectionClass->getOptions($info->fields['itemtype']),
            $info->fields['value'],
        );
        if ($option) {
            ob_start();
            self::showAdditionalInformation($info, $option, $injectionClass, $values);
            $field_html = ob_get_clean();

            TemplateRenderer::getInstance()->display('@datainjection/info_additional_information.html.twig', [
                'name'         => 'info[' . $option['linkfield'] . ']',
                'label'        => $option['name'],
                'field_html'   => $field_html,
                'is_mandatory' => $info->isMandatory(),
            ]);
        }
    }


    /**
     * Display command additional informations
     *
     * @param array $option
     * @param PluginDatainjectionInjectionInterface $injectionClass
     * @return void
     */
    public static function showAdditionalInformation(
        PluginDatainjectionInfo $info,
        $option,
        $injectionClass,
        $values = []
    ) {

        $name = "info[" . $option['linkfield'] . "]";

        if (isset($_SESSION['datainjection']['infos'][$option['linkfield']])) {
            $value = $_SESSION['datainjection']['infos'][$option['linkfield']];
        } else {
            $value = '';
        }

        switch ($option['displaytype']) {
            case 'text':
            case 'decimal':
                if (empty($value)) {
                    $value = ($option['default'] ?? '');
                }

                TemplateRenderer::getInstance()->display('@datainjection/info_additional_input.html.twig', [
                    'type'  => 'text',
                    'name'  => $name,
                    'value' => $value,
                ]);
                break;

            case 'dropdown':
                if ($value == '') {
                    $value = 0;
                }

                Dropdown::show(
                    getItemTypeForTable($option['table']),
                    ['name'  => $name,
                        'value' => $value,
                    ],
                );
                break;

            case 'bool':
                if ($value == '') {
                    $value = 0;
                }

                Dropdown::showYesNo($name, $value);
                break;

            case 'user':
                if ($value == '') {
                    $value = 0;
                }

                User::dropdown(
                    ['name'  => $name,
                        'value' => $value,
                    ],
                );
                break;

            case 'date':
                Html::showDateField($name, ['value' => $value]);
                break;

            case 'multiline_text':
                TemplateRenderer::getInstance()->display('@datainjection/info_additional_input.html.twig', [
                    'type'  => 'textarea',
                    'name'  => $name,
                    'value' => $value,
                ]);
                break;

            case 'dropdown_integer':
                $minvalue = ($option['minvalue'] ?? 0);
                $maxvalue = ($option['maxvalue'] ?? 0);
                $step     = ($option['step'] ?? 1);
                $default  = (isset($option['-1']) ? [-1 => $option['-1']] : []);

                Dropdown::showNumber(
                    $name,
                    ['value' => $value,
                        'min'   => $minvalue,
                        'max'   => $maxvalue,
                        'step'  => $step,
                        'toadd' => $default,
                    ],
                );
                break;

            case 'template':
                self::dropdownTemplates($name, $option['table']);
                break;

            case 'password':
                TemplateRenderer::getInstance()->display('@datainjection/info_additional_input.html.twig', [
                    'type' => 'password',
                    'name' => $name,
                ]);
                break;

            default:
                if (method_exists($injectionClass, 'showAdditionalInformation')) {
                    //If type is not a standard type, must be treated by specific injection class
                    $injectionClass->showAdditionalInformation($info, $option);
                }
        }
    }


    /**
    * @param PluginDatainjectionInfo $info      PluginDatainjectionInfo object
    * @param string $value
   **/
    public static function keepInfo(PluginDatainjectionInfo $info, $value)
    {

        $itemtype       = $info->getInfosType();
        $injectionClass = PluginDatainjectionCommonInjectionLib::getInjectionClassInstance($itemtype);
        $options        = $injectionClass->getOptions($itemtype);
        $option         = PluginDatainjectionCommonInjectionLib::findSearchOption(
            $options,
            $info->getValue(),
        );

        if ($option) {
            switch ($option['displaytype']) {
                default:
                case 'text':
                case 'multiline_text':
                    return $value != PluginDatainjectionCommonInjectionLib::EMPTY_VALUE;

                case 'dropdown':
                case 'user':
                case 'contact':
                    return $value != PluginDatainjectionCommonInjectionLib::DROPDOWN_EMPTY_VALUE;
            }
        }

        return null;
    }


    /**
    * @param string $name
    * @param string $table
   **/
    public static function dropdownTemplates($name, $table)
    {
        /** @var DBmysql $DB */
        global $DB;

        $values    = [0 => Dropdown::EMPTY_VALUE];

        $sql = "SELECT `id`, `template_name`
              FROM `" . $table . "`
              WHERE `is_template`=1 " .
                  getEntitiesRestrictRequest(' AND ', $table) .
           "ORDER BY `template_name`";

        foreach ($DB->doQuery($sql) as $data) {
            $values[$data['id']] = $data['template_name'];
        }

        Dropdown::showFromArray($name, $values);
    }
}
