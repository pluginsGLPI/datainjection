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

//Minimal stand-ins for the Fields plugin classes the rights checks rely on, loaded only when Fields is absent

//Mirrors the injection class Fields generates for each container
trait PluginFieldsStubInjectionTrait
{
    public static function getTable($classname = null): string
    {
        return getTableForItemType(get_parent_class(self::class));
    }

    public function isPrimaryType(): bool
    {
        return false;
    }

    public function getOptions($primary_type = ''): array
    {
        return [
            76665 => [
                'table'       => self::getTable(),
                'field'       => self::VALUE_FIELD,
                'name'        => 'Stub - Value',
                'linkfield'   => self::VALUE_FIELD,
                'datatype'    => 'string',
                'displaytype' => 'text',
                'injectable'  => PluginDatainjectionCommonInjectionLib::FIELD_INJECTABLE,
            ],
        ];
    }

    public function addOrUpdateObject($values = [], $options = []): array
    {
        $lib = new PluginDatainjectionCommonInjectionLib($this, $values, $options);
        $lib->processAddOrUpdate();

        return $lib->getInjectionResults();
    }
}

abstract class PluginFieldsAbstractContainerInstance extends CommonDBChild
{
    public static $itemtype = 'itemtype';

    public static $items_id = 'items_id';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canUpdate(): bool
    {
        return false;
    }

    //Like Fields, the stored entity always follows the parent item, overriding any given one
    public function addNeededInfoToInput($input)
    {
        $parent = static::getItemFromArray(static::$itemtype, static::$items_id, array_merge($this->fields, $input));
        if ($parent) {
            $input['entities_id'] = $parent->getEntityID();
            if ($parent->maybeRecursive()) {
                $input['is_recursive'] = (int) $parent->isRecursive();
            }
        }

        return $input;
    }
}

class PluginFieldsProfile
{
    public static int $right = 0;

    public static function getRightOnContainer(int $profile_id, int $container_id): int
    {
        return in_array($container_id, [PluginFieldsComputerstub::CONTAINER_ID, PluginFieldsEntitystub::CONTAINER_ID], true)
            ? self::$right
            : 0;
    }
}

class PluginFieldsComputerstub extends PluginFieldsAbstractContainerInstance
{
    public const CONTAINER_ID = 42;

    public const VALUE_FIELD = 'stubvaluefield';

    public static $rightname = 'computer';
}

class PluginFieldsComputerstubInjection extends PluginFieldsComputerstub implements PluginDatainjectionInjectionInterface
{
    use PluginFieldsStubInjectionTrait;

    public static $rightname = 'plugin_datainjection_model';

    public function connectedTo(): array
    {
        return [Computer::class];
    }
}

class PluginFieldsEntitystub extends PluginFieldsAbstractContainerInstance
{
    public const CONTAINER_ID = 43;

    public const VALUE_FIELD = 'stubvaluefield';

    public static $rightname = 'entity';
}

class PluginFieldsEntitystubInjection extends PluginFieldsEntitystub implements PluginDatainjectionInjectionInterface
{
    use PluginFieldsStubInjectionTrait;

    public static $rightname = 'plugin_datainjection_model';

    public function connectedTo(): array
    {
        return [Entity::class];
    }
}
