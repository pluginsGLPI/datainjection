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

class PluginDatainjectionLockedFixture extends CommonDBTM
{
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canUpdate(): bool
    {
        return false;
    }
}

class PluginDatainjectionLockedFixtureInjection extends PluginDatainjectionLockedFixture implements PluginDatainjectionInjectionInterface
{
    public static bool $can_write = true;

    public static function canCreate(): bool
    {
        return self::$can_write;
    }

    public static function canUpdate(): bool
    {
        return self::$can_write;
    }

    public function isPrimaryType()
    {
        return false;
    }

    public function connectedTo()
    {
        return [Computer::class];
    }

    public function getOptions($primary_type = '')
    {
        return [];
    }

    public function addOrUpdateObject($values = [], $options = [])
    {
        return [];
    }
}
