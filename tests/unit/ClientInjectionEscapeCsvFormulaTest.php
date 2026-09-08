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

require_once dirname(__DIR__, 2) . '/inc/clientinjection.class.php';

/**
 * Covers escapeCsvFormula(), which prefixes values starting with a CSV
 * formula-injection trigger character with a single quote before they are
 * written out by exportErrorsInCSV().
 */
class ClientInjectionEscapeCsvFormulaTest extends DbTestCase
{
    public function escapeCsvFormulaProvider(): array
    {
        return [
            'empty string'           => ['', ''],
            'equals trigger'         => ['=SUM(A1:A2)', "'=SUM(A1:A2)"],
            'plus trigger'           => ['+1234', "'+1234"],
            'minus trigger'          => ['-1234', "'-1234"],
            'at trigger'             => ['@SUM(A1:A2)', "'@SUM(A1:A2)"],
            'safe value passthrough' => ['normal value', 'normal value'],
            'non-string passthrough' => [42, 42],
        ];
    }

    /**
     * @dataProvider escapeCsvFormulaProvider
     */
    public function testEscapeCsvFormula($value, $expected): void
    {
        $escape_csv_formula = new ReflectionMethod(
            PluginDatainjectionClientInjection::class,
            'escapeCsvFormula'
        );
        // Required on PHP < 8.1, where private members stay closed to reflection
        if (PHP_VERSION_ID < 80100) {
            $escape_csv_formula->setAccessible(true);
        }

        $this->assertSame($expected, $escape_csv_formula->invoke(null, $value));
    }
}
