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

namespace GlpiPlugin\Datainjection\Tests\Unit;

use Glpi\Tests\DbTestCase;
use Computer;
use PluginDatainjectionComputerInjection;
use PluginDatainjectionCommonInjectionLib;
use ReflectionMethod;

final class CommonInjectionLibDateTimeTest extends DbTestCase
{
    public static function reformatDateTimeProvider(): array
    {
        return [
            'iso8601 with microseconds and utc offset' => [
                'original'    => '2026-04-15T13:51:30.935291+00:00',
                'date_format' => PluginDatainjectionCommonInjectionLib::DATE_TYPE_YYYYMMDD,
                'expected'    => '2026-04-15 13:51:30',
            ],
            'iso8601 with z timezone' => [
                'original'    => '2026-04-15T13:51:30Z',
                'date_format' => PluginDatainjectionCommonInjectionLib::DATE_TYPE_YYYYMMDD,
                'expected'    => '2026-04-15 13:51:30',
            ],
            'space separated, already valid' => [
                'original'    => '2026-04-15 13:51:30',
                'date_format' => PluginDatainjectionCommonInjectionLib::DATE_TYPE_YYYYMMDD,
                'expected'    => '2026-04-15 13:51:30',
            ],
            'date only, no time part' => [
                'original'    => '2026-04-15',
                'date_format' => PluginDatainjectionCommonInjectionLib::DATE_TYPE_YYYYMMDD,
                'expected'    => '2026-04-15 00:00:00',
            ],
            'dd-mm-yyyy format with time' => [
                'original'    => '15-04-2026 13:51:30',
                'date_format' => PluginDatainjectionCommonInjectionLib::DATE_TYPE_DDMMYYYY,
                'expected'    => '2026-04-15 13:51:30',
            ],
            'mm-dd-yyyy format with time' => [
                'original'    => '04-15-2026 13:51:30',
                'date_format' => PluginDatainjectionCommonInjectionLib::DATE_TYPE_MMDDYYYY,
                'expected'    => '2026-04-15 13:51:30',
            ],
            'iso8601 with negative utc offset' => [
                'original'    => '2026-04-15T13:51:30.000000-05:00',
                'date_format' => PluginDatainjectionCommonInjectionLib::DATE_TYPE_YYYYMMDD,
                'expected'    => '2026-04-15 13:51:30',
            ],
            'empty value' => [
                'original'    => '',
                'date_format' => PluginDatainjectionCommonInjectionLib::DATE_TYPE_YYYYMMDD,
                'expected'    => 'NULL',
            ],
        ];
    }

    /**
     * @dataProvider reformatDateTimeProvider
     */
    public function testReformatDateTime(string $original, string $date_format, string $expected): void
    {
        $lib = new PluginDatainjectionCommonInjectionLib(
            new PluginDatainjectionComputerInjection(),
            [],
            [],
        );

        $reformat_datetime = new ReflectionMethod(
            PluginDatainjectionCommonInjectionLib::class,
            'reformatDateTime',
        );

        self::assertSame($expected, $reformat_datetime->invoke($lib, $original, $date_format));
    }

    /**
     * Reproduces ticket #45007: a datetime value with microseconds and a timezone offset
     * must not reach the DB as-is, and must not crash the injection.
     */
    public function testDatetimeWithMicrosecondsAndTimezoneIsInjected(): void
    {
        $computer = $this->createItem(Computer::class, [
            'name'        => 'Test_Computer_Datetime',
            'entities_id' => 0,
        ]);

        $injection_class = new PluginDatainjectionComputerInjection();

        $lib = new PluginDatainjectionCommonInjectionLib(
            $injection_class,
            [
                'Computer' => [
                    'name'                  => 'Test_Computer_Datetime',
                    'last_inventory_update' => '2026-04-15T13:51:30.935291+00:00',
                ],
            ],
            [
                'rights' => [
                    'can_add'      => true,
                    'can_update'   => true,
                    'add_dropdown' => true,
                ],
                'mandatory_fields' => [
                    'Computer' => ['name' => true],
                ],
                'entities_id' => 0,
            ],
        );

        $lib->processAddOrUpdate();

        $results = $lib->getInjectionResults();

        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);
        self::assertSame(PluginDatainjectionCommonInjectionLib::IMPORT_UPDATE, $results['type']);

        $computer->getFromDB($computer->getID());
        self::assertSame('2026-04-15 13:51:30', $computer->fields['last_inventory_update']);
    }
}
