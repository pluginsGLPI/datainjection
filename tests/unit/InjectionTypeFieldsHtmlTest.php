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

use Computer;
use PluginDatainjectionInjectionType;
use PluginDatainjectionMapping;
use PluginDatainjectionModel;
use Session;
use Glpi\Tests\DbTestCase;
use Symfony\Component\DomCrawler\Crawler;

final class InjectionTypeFieldsHtmlTest extends DbTestCase
{
    public function testEachRowKeepsItsFieldAndHidesTheOtherRowsOne(): void
    {
        $this->login();

        $model = $this->createItem(PluginDatainjectionModel::class, [
            'name'         => 'Test_Model_FieldsHtml_' . random_int(1, PHP_INT_MAX),
            'itemtype'     => Computer::class,
            'filetype'     => 'csv',
            'behavior_add' => 1,
            'entities_id'  => 0,
            'is_recursive' => 1,
            'is_private'   => 0,
            'users_id'     => Session::getLoginUserID(),
        ]);

        $fields = ['name', 'serial'];
        $mappings = [];
        foreach ($fields as $rank => $field) {
            $mappings[$field] = $this->createItem(PluginDatainjectionMapping::class, [
                'models_id'    => $model->getID(),
                'itemtype'     => Computer::class,
                'rank'         => $rank,
                'name'         => $field,
                'value'        => $field,
                'is_mandatory' => 0,
            ]);
        }

        //Rendered in the same request, as the Mappings tab does: the static caches are shared by the rows
        $selects = [];
        foreach ($mappings as $field => $mapping) {
            $row = PluginDatainjectionInjectionType::getRow($mapping, Computer::class);
            $selects[$field] = (new Crawler($row['field_html']))->filter('select');
        }

        foreach (['name' => 'serial', 'serial' => 'name'] as $field => $other) {
            $this->assertCount(1, $selects[$field]->filter(sprintf('option[value="%s"][selected]', $field)));
            $this->assertCount(0, $selects[$field]->filter(sprintf('option[value="%s"]', $other)));
        }
    }
}
