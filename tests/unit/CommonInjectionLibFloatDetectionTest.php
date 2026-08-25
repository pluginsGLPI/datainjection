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
use PluginDatainjectionCommonInjectionLib;
use PluginDatainjectionComputerInjection;

require_once dirname(__DIR__, 2) . '/inc/injectioninterface.class.php';
require_once dirname(__DIR__, 2) . '/inc/commoninjectionlib.class.php';
require_once dirname(__DIR__, 2) . '/inc/computerinjection.class.php';

/**
 * Covers the float-detection regex used by reformatThirdPass() to derive
 * $option['checktype'] when a search option (e.g. a custom field coming from
 * a third-party plugin) only has a 'datatype' and no explicit 'checktype'.
 *
 * The regex under test is not hardcoded here: it is retrieved from
 * PluginDatainjectionCommonInjectionLib::getFloatDetectionRegex(), the same
 * shared source reformatThirdPass() itself calls, so this test can never
 * drift out of sync with the actual pattern shipped in the code.
 */
final class CommonInjectionLibFloatDetectionTest extends DbTestCase
{
    private static function getFloatDetectionRegex(): string
    {
        $lib = new PluginDatainjectionCommonInjectionLib(new PluginDatainjectionComputerInjection());

        return $lib->getFloatDetectionRegex();
    }

    public static function floatDetectionProvider(): array
    {
        return [
            // Accepted formats
            'plain dot decimal'                      => ['1234.56', true],
            'plain comma decimal'                    => ['1234,56', true],
            'leading zero decimal'                   => ['0.5', true],
            'space-grouped thousands, dot decimal'    => ['1 234.56', true],
            'space-grouped thousands, comma decimal'  => ['1 234,56', true],
            'multiple space groups, comma decimal'    => ['12 345 678,90', true],
            'comma-grouped thousands, dot decimal'    => ['1,234.56', true],
            'multiple comma groups, dot decimal'      => ['12,345,678.90', true],
            'basic comma decimal, no grouping'        => ['12,34', true],

            // Rejected formats (should fall back to $option['datatype'])
            'plain integer, no separator'             => ['1234', false],
            'empty string'                            => ['', false],
            'non numeric text'                        => ['abc', false],
            'multiple dots (malformed)'               => ['1234.56.78', false],
            'comma-grouped integer, no decimal part'  => ['1,234,567', false],
            'space-grouped integer, no decimal part'  => ['1 234', false],
            'leading dot, no integer part'            => ['.56', false],
            'negative float (unsupported by regex)'   => ['-1234.56', false],
        ];
    }

    /**
     * @dataProvider floatDetectionProvider
     */
    public function testFloatDetectionRegex(string $value, bool $expected_match): void
    {
        $regex = self::getFloatDetectionRegex();

        self::assertSame($expected_match, preg_match($regex, $value) !== 0);
    }
}
