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

use Glpi\Asset\AssetDefinitionManager;
use Glpi\Asset\Capacity;
use Glpi\Tests\DbTestCase;
use GlpiPlugin\Datainjection\Glpi\Asset\Capacity\IsInjectableCapacity;
use PluginDatainjectionCommonInjectionLib;

final class AssetInjectionTest extends DbTestCase
{
    /**
     * End-to-end injection into a custom GlpiAsset definition:
     * enable IsInjectableCapacity on a definition, let the plugin generate the
     * dedicated AssetInjection subclass, inject a row through it and assert the
     * resulting custom asset item is persisted and tied to the definition.
     */
    public function testRowIsInjectedIntoInjectableCustomAssetDefinition(): void
    {
        $this->login();

        // The AssetDefinitionManager singleton is rebuilt between tests and only
        // carries core capacities; the plugin capacity is registered from
        // plugin_init_datainjection() at runtime, so re-register it explicitly here.
        $manager = AssetDefinitionManager::getInstance();
        $manager->registerCapacity(new IsInjectableCapacity());

        // Create a custom asset definition with the "Injectable" capacity enabled.
        $system_name = 'Injectableasset';
        $definition  = $this->initAssetDefinition(
            $system_name,
            [new Capacity(name: IsInjectableCapacity::class)],
        );

        $asset_itemtype = $definition->getAssetClassName();

        // Reproduce the runtime registration pipeline that turns an injectable
        // definition into a concrete injection class:
        //  - bootDefinitions() re-reads the new definition and triggers
        //    IsInjectableCapacity::onClassBootstrap(), which populates injectable_types;
        //  - the two registration helpers then eval() the dedicated AssetInjection subclass.
        /** @var array $CFG_GLPI */
        global $CFG_GLPI;
        $CFG_GLPI['injectable_types'] = [];
        $manager->bootDefinitions();

        self::assertArrayHasKey(
            $definition->getID(),
            $CFG_GLPI['injectable_types'],
            'IsInjectableCapacity should register the definition as an injectable type',
        );
        self::assertSame($asset_itemtype, $CFG_GLPI['injectable_types'][$definition->getID()]);

        plugin_datainjection_registerInjectableAssets();
        plugin_datainjection_creationInjectableAssets();

        // The subclass name is built exactly the way setup.php builds it.
        $injection_classname = 'PluginDatainjection' . ucfirst($system_name) . 'AssetInjection';
        self::assertTrue(
            class_exists($injection_classname),
            "Injection class {$injection_classname} should have been generated",
        );

        $injection = new $injection_classname();

        // The lib keys both the injected values and the results by the itemtype it
        // derives from the injection class; reuse that exact value to avoid any
        // table round-trip mismatch.
        $primary_type = PluginDatainjectionCommonInjectionLib::getItemtypeByInjectionClass($injection);
        self::assertSame($asset_itemtype, $primary_type);

        $asset_name = 'Injected custom asset';
        $results = $injection->addOrUpdateObject(
            [
                $primary_type => [
                    'name' => $asset_name,
                ],
            ],
            [
                'rights' => [
                    'can_add'      => true,
                    'can_update'   => true,
                    'add_dropdown' => true,
                ],
                'mandatory_fields' => [
                    $primary_type => ['name' => true],
                ],
                'entities_id' => 0,
            ],
        );

        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);
        self::assertSame(PluginDatainjectionCommonInjectionLib::IMPORT_ADD, $results['type']);

        $asset_id = $results[$primary_type] ?? null;
        self::assertNotNull($asset_id, 'The injection result should expose the created asset id');
        self::assertGreaterThan(0, (int) $asset_id);

        // The row must exist as a real custom asset, tied to our definition.
        $asset = new $asset_itemtype();
        self::assertTrue($asset->getFromDB((int) $asset_id));
        self::assertSame($asset_name, $asset->fields['name']);
        self::assertSame(
            $definition->getID(),
            (int) $asset->fields['assets_assetdefinitions_id'],
        );
    }
}
