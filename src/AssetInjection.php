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

namespace GlpiPlugin\Datainjection;

use CommonDBTM;
use Glpi\Asset\Asset;
use Glpi\Asset\CustomFieldDefinition;
use Glpi\DBAL\QueryExpression;
use Glpi\DBAL\QueryFunction;
use Glpi\DBAL\QueryIdentifier;
use PluginDatainjectionCommonInjectionLib;
use PluginDatainjectionInjectionInterface;
use Search;

/**
 * Injection of the generic assets (GLPI custom assets).
 *
 * Their classes are final and share the same table: the injected itemtype is given
 * to the instance instead of being the parent class.
 */
final class AssetInjection extends CommonDBTM implements PluginDatainjectionInjectionInterface
{
    /** @var class-string<Asset> */
    public string $itemtype;

    /**
     * @param class-string<Asset> $itemtype
     */
    public static function forItemtype(string $itemtype): self
    {
        $injection = new self();
        $injection->itemtype = $itemtype;

        return $injection;
    }

    public static function getTable($classname = null)
    {
        return Asset::getTable();
    }

    public function isPrimaryType()
    {
        return true;
    }

    public function connectedTo()
    {
        return [];
    }

    public function isNullable($field)
    {
        return true; // By default, all fields can be null
    }

    /**
     * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
     **/
    public function getOptions($primary_type = '')
    {
        $tab = Search::getOptions($this->itemtype);

        //Specific to groups (simple group, not technical)
        $tab[71]['linkfield'] = 'groups_id_normal';

        //Remove some options because some fields cannot be imported
        $blacklist     = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions($this->itemtype);
        $notimportable = [
            91, 92, 93, 101, 102, 103, 104, 105, 106, 107, 998, 999, // location fields
            250, // asset definition, set by the asset itself
        ];

        $options['ignore_fields'] = array_merge($blacklist, $notimportable);
        $options['displaytype']   = [
            'user'           => [24, 70],
            'multiline_text' => [16],
        ];

        $tab = PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);

        // Capacities add the fields of linked items (operating system, volumes...), which are injected with their own type
        $tab = array_filter(
            $tab,
            fn(array $option) => $this->isField($option['linkfield'])
                || in_array($option['linkfield'], ['groups_id', 'groups_id_normal', 'templates_id'], true),
        );

        // Added after the generic filter, which keeps only the core options (id < 1000)
        foreach ($this->itemtype::getDefinition()->getCustomFieldDefinitions() as $custom_field) {
            $option = $this->getCustomFieldOption($custom_field);
            if ($option !== null) {
                $tab[$option['id']] = $option;
            }
        }

        return $tab;
    }

    /**
     * The core search option of a custom field cannot be injected as is: its linkfield is the
     * JSON column shared by all custom fields. Values are injected as "custom_<system_name>",
     * which the asset stores into this column.
     *
     * @return array|null null if the field cannot be injected
     */
    private function getCustomFieldOption(CustomFieldDefinition $custom_field): ?array
    {
        $core_option = $custom_field->getFieldType()->getSearchOption();
        $types       = [
            // datatype => [displaytype, checktype]
            'string'   => ['text', 'text'],
            'text'     => ['multiline_text', 'text'],
            'number'   => ['decimal', 'float'],
            'bool'     => ['bool', 'bool'],
            'date'     => ['text', 'date'],
            'datetime' => ['text', 'datetime'],
            'itemlink' => ['dropdown', 'text'],
        ];
        if (
            $core_option === null
            || !isset($types[$core_option['datatype']])
            // Values of a multiple dropdown cannot be told apart in a single CSV cell
            || ($custom_field->fields['field_options']['multiple'] ?? false)
            || $custom_field->getFieldType()->getOptionValues()['hidden']
        ) {
            return null;
        }

        $linkfield = 'custom_' . $custom_field->fields['system_name'];
        [$displaytype, $checktype] = $types[$core_option['datatype']];

        return [
            'id'          => $core_option['id'],
            'name'        => $custom_field->computeFriendlyName(),
            'table'       => $core_option['table'],
            // A dropdown is searched by its name, other values are stored as is
            'field'       => $displaytype === 'dropdown' ? $core_option['field'] : $linkfield,
            'linkfield'   => $linkfield,
            // Needed for dropdowns: custom dropdowns share a table, which cannot give back their itemtype
            'itemtype'    => $core_option['itemtype'] ?? $this->itemtype,
            'datatype'    => $core_option['datatype'],
            'displaytype' => $displaytype,
            'checktype'   => $checktype,
            'injectable'  => PluginDatainjectionCommonInjectionLib::FIELD_INJECTABLE,
        ];
    }

    /**
     * Criterion to find an asset by a custom field, whose value is stored in a JSON column
     *
     * @return QueryExpression|null null if the field is not a custom field
     */
    public function getCustomFieldCriterion(string $field, mixed $value): ?QueryExpression
    {
        global $DB;

        foreach ($this->itemtype::getDefinition()->getCustomFieldDefinitions() as $custom_field) {
            if ('custom_' . $custom_field->fields['system_name'] === $field) {
                return new QueryExpression(
                    QueryFunction::jsonUnquote(QueryFunction::jsonExtract([
                        new QueryIdentifier('custom_fields'),
                        new QueryExpression($DB::quoteValue('$."' . $custom_field->getID() . '"')),
                    ])) . ' = ' . $DB::quoteValue((string) $value),
                );
            }
        }

        return null;
    }

    /**
     * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::addOrUpdateObject()
     **/
    public function addOrUpdateObject($values = [], $options = [])
    {
        $lib = new PluginDatainjectionCommonInjectionLib($this, $values, $options);
        $lib->processAddOrUpdate();
        return $lib->getInjectionResults();
    }
}
