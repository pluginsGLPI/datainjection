<?php

/**
 * -------------------------------------------------------------------------
 * Escalade plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of Escalade.
 *
 * Escalade is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Escalade is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Escalade. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2015-2023 by Escalade plugin team.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/pluginsGLPI/escalade
 * -------------------------------------------------------------------------
 */


namespace GlpiPlugin\Datainjection\Tests\Unit;

use Glpi\Tests\DbTestCase;
use Manufacturer;
use PluginDatainjectionCommonInjectionLib;

require_once dirname(__DIR__, 2) . '/inc/injectioninterface.class.php';
require_once dirname(__DIR__, 2) . '/inc/commoninjectionlib.class.php';
require_once dirname(__DIR__, 2) . '/inc/manufacturerinjection.class.php';

final class CommonInjectionLibDataAlreadyInDbTest extends DbTestCase
{
    public function testDataAlreadyInDbHandlesApostropheInMandatoryFieldValue(): void
    {
        $manufacturer_name = "O'Brien Manufacturer";
        $manufacturer = $this->createItem(Manufacturer::class, [
            'name' => $manufacturer_name,
        ]);

        $injection_class = new \PluginDatainjectionManufacturerInjection();
        $common_injection_lib = new PluginDatainjectionCommonInjectionLib(
            $injection_class,
            [
                'Manufacturer' => [
                    'name' => $manufacturer_name,
                ],
            ],
            [
                'mandatory_fields' => [
                    'Manufacturer' => [
                        'name' => true,
                    ],
                ],
            ],
        );

        $invoke_data_already_in_db = \Closure::bind(
            function ($injection_class, string $itemtype): void {
                $this->dataAlreadyInDB($injection_class, $itemtype);
            },
            $common_injection_lib,
            PluginDatainjectionCommonInjectionLib::class,
        );

        $invoke_data_already_in_db($injection_class, 'Manufacturer');

        $values = $common_injection_lib->getValuesForItemtype('Manufacturer');
        self::assertIsArray($values);
        self::assertSame($manufacturer->getID(), $values['id']);
    }
}
