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

use function Safe\ob_get_clean;
use function Safe\ob_start;

class PluginDatainjectionMapping extends CommonDBTM
{
    public static $rightname = "plugin_datainjection_model";

    /**
    * @param string $field
    * @param array $value
   **/
    public function equal($field, $value)
    {

        if (!isset($this->fields[$field])) {
            return false;
        }
        return $this->fields[$field] == $value;
    }


    public function getMappingName()
    {

        return $this->fields["name"];
    }


    public function getRank()
    {

        return $this->fields["rank"];
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


    public function getItemtype()
    {

        return $this->fields["itemtype"];
    }


    /**
    * @param PluginDatainjectionModel $model  PluginDatainjectionModel object
   **/
    public static function showFormMappings(PluginDatainjectionModel $model)
    {
        $canedit = $model->can($model->fields['id'], UPDATE);
        $lines = isset($_SESSION['datainjection']['lines']) ? unserialize($_SESSION['datainjection']['lines']) : [];

        $show_preview = isset($_SESSION['datainjection']['lines']) && !empty($lines);
        $preview_url = '';
        if ($show_preview) {
            $preview_url = plugin_datainjection_geturl() . "front/popup.php?popup=preview&models_id=" . $model->getID();
        }

        $model->loadMappings();
        $mappings = [];

        foreach ($model->getMappings() as $mapping) {
            ob_start();
            $options = ['primary_type' => $model->fields['itemtype']];
            PluginDatainjectionInjectionType::dropdownLinkedTypes($mapping, $options);
            $dropdown_html = ob_get_clean();

            $mappings[] = [
                'id'            => $mapping->getID(),
                'name'          => $mapping->fields['name'],
                'dropdown_html' => $dropdown_html,
            ];
        }

        TemplateRenderer::getInstance()->display('@datainjection/mappings_form.html.twig', [
            'form_action'  => Toolbox::getItemTypeFormURL(self::class),
            'show_preview' => $show_preview,
            'preview_url'  => $preview_url,
            'mappings'     => $mappings,
            'canedit'      => $canedit,
            'model_id'     => $model->fields['id'],
        ]);
    }


    /**
    * For multitext only ! Check it there's more than one value to inject in a field
    *
    * @param int $models_id the model ID
    *
    * @return array true if more than one value to inject, false if not
   **/
    public static function getSeveralMappedField($models_id)
    {
        /** @var DBmysql $DB */
        global $DB;

        $several = [];
        $query  = "SELECT `value`,
                        COUNT(*) AS counter
                 FROM `glpi_plugin_datainjection_mappings`
                 WHERE `models_id` = '" . $models_id . "'
                       AND `value` NOT IN ('none')
                 GROUP BY `value`
                 HAVING `counter` > 1";

        foreach ($DB->doQuery($query) as $mapping) {
            $several[] = $mapping['value'];
        }
        return $several;
    }


    /**
    * @param int $models_id
   **/
    public static function getMappingsSortedByRank($models_id)
    {
        /** @var DBmysql $DB */
        global $DB;

        $mappings = [];
        $query    = "SELECT `name`
                   FROM `glpi_plugin_datainjection_mappings`
                   WHERE `models_id` = '" . $models_id . "'
                   ORDER BY `rank` ASC";
        foreach ($DB->doQuery($query) as $data) {
            $mappings[] = $data['name'];
        }
        return $mappings;
    }
}
