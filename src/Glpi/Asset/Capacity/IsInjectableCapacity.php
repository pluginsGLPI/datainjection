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

namespace GlpiPlugin\Datainjection\Glpi\Asset\Capacity;

use Glpi\Asset\AssetDefinitionManager;
use Glpi\Asset\Capacity\AbstractCapacity;
use Glpi\Asset\CapacityConfig;
use Override;

final class IsInjectableCapacity extends AbstractCapacity
{
    #[Override]
    public function getLabel(): string
    {
        return __('Injectable', 'datainjection');
    }

    #[Override]
    public function getIcon(): string
    {
        return 'ti ti-download';
    }

    #[Override]
    public function getDescription(): string
    {
        return __("Inject objects list");
    }

//    public function getCloneRelations(): array
//    {
//        return [
//            Infocom::class,
//        ];
//    }
//
//    public function isUsed(string $classname): bool
//    {
//        return parent::isUsed($classname)
//            && $this->countAssetsLinkedToPeerItem($classname, Infocom::class) > 0;
//    }

    #[Override]
    public function getCapacityUsageDescription(string $classname): string
    {
//        return sprintf(
//            __('Used by %1$s of %2$s assets'),
//            $this->countAssetsLinkedToPeerItem($classname, Infocom::class),
//            $this->countAssets($classname)
//        );
        return '';
    }

    public function onClassBootstrap(string $classname, CapacityConfig $config): void
    {
        global $CFG_GLPI;

//        if (!isset($CFG_GLPI['injectable_types'])) {
//            $CFG_GLPI['injectable_types'] = [];
//        }
        $manager = AssetDefinitionManager::getInstance();
        foreach ($manager->getDefinitions() as $definition) {
            $itemtype = $definition->getAssetClassName();
            if ($itemtype == $classname) {
                if (!in_array($classname, $CFG_GLPI['injectable_types'])) {
                    $CFG_GLPI['injectable_types'][$definition->fields['id']] = $classname;
                }
            }
        }

        $this->registerToTypeConfig('injectable_types', $classname);

//        CommonGLPI::registerStandardTab($classname, Infocom::class, 50);
    }

    public function onCapacityDisabled(string $classname, CapacityConfig $config): void
    {
        // Unregister from infocom types
//        $this->unregisterFromTypeConfig('injectable_types', $classname);
//
//        // Delete related infocom data
//        $infocom = new Infocom();
//        $infocom->deleteByCriteria(['itemtype' => $classname], force: true, history: false);
//
//        $infocom_search_options = Infocom::rawSearchOptionsToAdd($classname);
//
//        // Clean history related to infocoms
//        $this->deleteFieldsLogs($classname, $infocom_search_options);
//
//        // Clean display preferences
//        $this->deleteDisplayPreferences($classname, $infocom_search_options);
    }

//    #[Override]
//    public function onObjectInstanciation(Asset $object, CapacityConfig $config): void
//    {
//        $object->fields['_added_by_hasinjectablecapacity'] = 'abc';
//    }
}
