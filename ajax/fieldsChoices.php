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
use Glpi\Exception\Http\BadRequestHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;

Html::header_nocache();

Session::checkRight('plugin_datainjection_model', READ);

$itemtype     = (string) ($_POST['itemtype'] ?? PluginDatainjectionInjectionType::NO_VALUE);
$primary_type = (string) ($_POST['primary_type'] ?? '');
$called_by    = (string) ($_POST['called_by'] ?? '');

if (
    !is_a($primary_type, CommonDBTM::class, true)
    || ($itemtype !== PluginDatainjectionInjectionType::NO_VALUE && !is_a($itemtype, CommonDBTM::class, true))
    || !in_array($called_by, [PluginDatainjectionMapping::class, PluginDatainjectionInfo::class], true)
) {
    throw new BadRequestHttpException();
}

$choices = PluginDatainjectionInjectionType::getFieldsChoices([
    'itemtype'        => $itemtype,
    'primary_type'    => $primary_type,
    'called_by'       => $called_by,
    'mapping_or_info' => (string) ($_POST['mapping_or_info'] ?? '{}'),
]);

// Use a list of pairs to keep the options order on client side
$fields = [];
foreach ($choices['fields'] as $key => $label) {
    $fields[] = [$key, $label];
}

return new JsonResponse([
    'fields' => $fields,
    'value'  => $choices['value'],
]);
