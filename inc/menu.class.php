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

class PluginDatainjectionMenu extends CommonGLPI
{

   static $rightname = 'plugin_datainjection_use';

   static function getMenuName() {

      return __('Data injection', 'datainjection');
   }

   static function getMenuContent() {

      global $CFG_GLPI;

      $injectionFormUrl = "/".Plugin::getWebDir('datainjection', false).'/front/clientinjection.form.php';

      $menu = [
         'title' => self::getMenuName(),
         'page'  => $injectionFormUrl,
         'icon'  => 'fas fa-file-import',
      ];

      if (Session::haveRight(static::$rightname, READ)) {

         $image_import  = "<i class='fas fa-upload' title='";
         $image_import .= __s('Injection of the file', 'datainjection');
         $image_import .= "' alt='".__s('Injection of the file', 'datainjection')."'></i>";

         $menu['options'] = [
            'client' => [
               'title' => __s('Injection of the file', 'datainjection'),
               'page'  => $injectionFormUrl,
               'icon'  => 'fas fa-upload',
            ],
            'model' => [
               'icon'  => 'fas fa-layer-group',
               'links' => [
                  $image_import => $injectionFormUrl
               ]
            ]
         ];

         $model_name  = PluginDatainjectionModel::getTypeName(Session::getPluralNumber());
         $image_model = "<i class='fas fa-layer-group' title='$model_name' alt='$model_name'></i>";

         if (Session::haveRight('plugin_datainjection_model', READ)) {
            $menu['options']['model']['title'] = $model_name;
            $menu['options']['model']['page'] = Toolbox::getItemTypeSearchUrl('PluginDatainjectionModel', false);
            $menu['options']['model']['links']['search'] = Toolbox::getItemTypeSearchUrl('PluginDatainjectionModel', false);
            $menu['options']['client']['links'][$image_model]  = Toolbox::getItemTypeSearchUrl('PluginDatainjectionModel', false);
         }

         if (Session::haveRight('plugin_datainjection_model', UPDATE) || Session::haveRight('plugin_datainjection_model', CREATE)) {
            $menu['options']['model']['links']['add'] = Toolbox::getItemTypeFormUrl('PluginDatainjectionModel', false);
            $menu['options']['client']['links'][$image_model]  = Toolbox::getItemTypeSearchUrl('PluginDatainjectionModel', false);
         }

      }

      return $menu;
   }


}
