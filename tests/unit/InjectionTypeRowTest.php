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
use Dropdown;
use Glpi\Tests\DbTestCase;
use PluginDatainjectionInfo;
use PluginDatainjectionInjectionType;
use PluginDatainjectionMapping;
use PluginDatainjectionModel;
use PluginDatainjectionModelcsv;
use Session;
use Symfony\Component\HttpFoundation\Response;

final class InjectionTypeRowTest extends DbTestCase
{
    private function createModel(string $name = 'Test_InjectionType_Row'): PluginDatainjectionModel
    {
        $model = $this->createItem(PluginDatainjectionModel::class, [
            'name'            => $name,
            'itemtype'        => Computer::class,
            'filetype'        => 'csv',
            'entities_id'     => 0,
            'is_private'      => 0,
            'behavior_add'    => 1,
            'behavior_update' => 0,
            'users_id'        => Session::getLoginUserID(),
        ]);
        $this->assertInstanceOf(PluginDatainjectionModel::class, $model);

        return $model;
    }

    private function createMapping(int $models_id, array $input): PluginDatainjectionMapping
    {
        $mapping = $this->createItem(PluginDatainjectionMapping::class, $input + [
            'models_id'    => $models_id,
            'itemtype'     => Computer::class,
            'rank'         => 0,
            'name'         => 'column',
            'value'        => PluginDatainjectionInjectionType::NO_VALUE,
            'is_mandatory' => 0,
        ]);
        $this->assertInstanceOf(PluginDatainjectionMapping::class, $mapping);

        return $mapping;
    }

    public function testFieldsChoicesWithoutTableOnlyContainsEmptyValue(): void
    {
        $choices = PluginDatainjectionInjectionType::getFieldsChoices([
            'itemtype'        => PluginDatainjectionInjectionType::NO_VALUE,
            'primary_type'    => Computer::class,
            'called_by'       => PluginDatainjectionMapping::class,
            'mapping_or_info' => '{}',
        ]);

        $this->assertSame(
            [PluginDatainjectionInjectionType::NO_VALUE => Dropdown::EMPTY_VALUE],
            $choices['fields'],
        );
        $this->assertSame(PluginDatainjectionInjectionType::NO_VALUE, $choices['value']);
    }

    public function testFieldsChoicesGuessesFieldFromMappingName(): void
    {
        $model = $this->createModel();
        $mapping = $this->createMapping($model->getID(), ['name' => 'serial']);

        $choices = PluginDatainjectionInjectionType::getFieldsChoices([
            'itemtype'        => Computer::class,
            'primary_type'    => Computer::class,
            'called_by'       => PluginDatainjectionMapping::class,
            'mapping_or_info' => $mapping->fields,
            'need_decode'     => false,
        ]);

        // Empty value on top, then the injectable fields
        $this->assertSame(PluginDatainjectionInjectionType::NO_VALUE, array_key_first($choices['fields']));
        $this->assertArrayHasKey('serial', $choices['fields']);
        $this->assertSame('serial', $choices['value']);
    }

    public function testFieldsChoicesDoesNotGuessFieldForInfo(): void
    {
        $model = $this->createModel();
        $info = $this->createItem(PluginDatainjectionInfo::class, [
            'models_id'    => $model->getID(),
            'itemtype'     => Computer::class,
            'value'        => PluginDatainjectionInjectionType::NO_VALUE,
            'is_mandatory' => 0,
        ]);

        $choices = PluginDatainjectionInjectionType::getFieldsChoices([
            'itemtype'        => Computer::class,
            'primary_type'    => Computer::class,
            'called_by'       => PluginDatainjectionInfo::class,
            'mapping_or_info' => $info->fields + ['name' => 'serial'],
            'need_decode'     => false,
        ]);

        $this->assertArrayHasKey('serial', $choices['fields']);
        $this->assertSame(PluginDatainjectionInjectionType::NO_VALUE, $choices['value']);
    }

    public function testCanBeMandatory(): void
    {
        // Infos can always be mandatory
        $this->assertTrue(PluginDatainjectionInjectionType::canBeMandatory(
            PluginDatainjectionInfo::class,
            Computer::class,
            'Group',
            'groups_id',
        ));

        // Mappings: only fields of the primary type can be link fields
        $this->assertTrue(PluginDatainjectionInjectionType::canBeMandatory(
            PluginDatainjectionMapping::class,
            Computer::class,
            Computer::class,
            'serial',
        ));
        $this->assertFalse(PluginDatainjectionInjectionType::canBeMandatory(
            PluginDatainjectionMapping::class,
            Computer::class,
            'NetworkPort',
            'mac',
        ));
        $this->assertFalse(PluginDatainjectionInjectionType::canBeMandatory(
            PluginDatainjectionMapping::class,
            Computer::class,
            Computer::class,
            'groups_id',
        ));
    }

    public function testRowFlagsForMappings(): void
    {
        $model = $this->createModel();
        $mapped = $this->createMapping($model->getID(), [
            'name'         => 'serial',
            'value'        => 'serial',
            'is_mandatory' => 1,
        ]);
        $unmapped = $this->createMapping($model->getID(), [
            'rank'     => 1,
            'name'     => 'unknown column',
            'itemtype' => PluginDatainjectionInjectionType::NO_VALUE,
        ]);

        $row = PluginDatainjectionInjectionType::getRow($mapped, Computer::class);
        $this->assertSame($mapped->getID(), $row['id']);
        $this->assertTrue($row['is_mapped']);
        $this->assertTrue($row['is_mandatory']);
        $this->assertTrue($row['can_be_mandatory']);
        $this->assertFalse($row['has_conflict']);
        $this->assertStringContainsString('data[' . $mapped->getID() . '][value]', $row['field_html']);
        $this->assertSame(Computer::class, $row['params']['primary_type']);
        $this->assertSame(PluginDatainjectionMapping::class, $row['params']['called_by']);

        $row = PluginDatainjectionInjectionType::getRow($unmapped, Computer::class);
        $this->assertFalse($row['is_mapped']);
        $this->assertFalse($row['is_mandatory']);
        $this->assertFalse($row['can_be_mandatory']);
    }

    public function testRowDetectsFieldUsedByMappingAndInfo(): void
    {
        $model = $this->createModel();
        $mapping = $this->createMapping($model->getID(), [
            'name'  => 'serial',
            'value' => 'serial',
        ]);
        $info = $this->createItem(PluginDatainjectionInfo::class, [
            'models_id'    => $model->getID(),
            'itemtype'     => Computer::class,
            'value'        => 'serial',
            'is_mandatory' => 0,
        ]);

        $this->assertContains(
            Computer::class . '|serial',
            PluginDatainjectionInjectionType::getOtherTargetedFields(PluginDatainjectionMapping::class, $model->getID()),
        );
        $this->assertTrue(PluginDatainjectionInjectionType::getRow($mapping, Computer::class)['has_conflict']);
        $this->assertTrue(PluginDatainjectionInjectionType::getRow($info, Computer::class)['has_conflict']);
    }

    public function testShowSampleReturnsCsvResponse(): void
    {
        $model = $this->createModel('Sample model');
        $this->createMapping($model->getID(), ['name' => 'serial', 'rank' => 1]);
        $this->createMapping($model->getID(), ['name' => 'name', 'rank' => 0]);

        $csv = new PluginDatainjectionModelcsv();
        $csv->getFromDBByModelID($model->getID());

        $delimiter = $csv->getDelimiter();

        $response = $csv->showSample($model);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame('"name"' . $delimiter . '"serial"' . "\n", $response->getContent());
        $this->assertSame(
            'attachment; filename="Sample_model.csv"',
            $response->headers->get('Content-Disposition'),
        );
        $this->assertSame('text/comma-separated-values', $response->headers->get('Content-Type'));
        $this->assertSame((string) strlen($response->getContent()), $response->headers->get('Content-Length'));
    }
}
