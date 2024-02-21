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

require '../../../inc/includes.php';

/* Update mappings */
if (isset($_POST["update"])) {
    PluginDatainjectionInfo::manageInfos($_POST['models_id'], $_POST);
} else if (isset($_POST["delete"])) {
    $info = new PluginDatainjectionInfo();
    foreach ($_POST["item"] as $key => $val) {
        $input = ['id' => $key];
        if ($val == 1) {
            $info->check($key, UPDATE);
            $info->delete($input);
        }
    }
    Html::back();
}

Session::setActiveTab('PluginDatainjectionModel', 'PluginDatainjectionModel$5');
Html::back();
