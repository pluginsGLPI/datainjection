<?php
/*
 * @version $Id$
 LICENSE

 This file is part of the order plugin.

 Datainjection plugin is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Datainjection plugin is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with datainjection; along with Behaviors. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 @package   datainjection
 @author    the datainjection plugin team
 @copyright Copyright (c) 2010-2011 Order plugin team
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://forge.indepnet.net/projects/datainjection
 @link      http://www.glpi-project.org/
 @since     2009
 ---------------------------------------------------------------------- */

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");

header("Content-Type: text/html; charset=UTF-8");
header_nocache();

if (!isset($_POST["id"])) {
   exit();
}

if (!isset($_POST["sort"])) {
   $_POST["sort"] = "";
}

if (!isset($_POST["order"])) {
   $_POST["order"] = "";
}

$model = new PluginDatainjectionModel;
if ($_POST['glpi_tab'] == 1) {
   $model->showAdvancedForm($_POST["id"]);
}

if ($_POST["id"] >0 && $model->can($_POST["id"],'r')) {
   switch($_POST['glpi_tab']) {
      case -1 :
         Plugin::displayAction($model,$_POST['glpi_tab']);

         if ($model->fields['step'] > PluginDatainjectionModel::INITIAL_STEP) {
            $options['confirm']   = 'creation';
            $options['models_id'] = $model->fields['id'];
            $options['add_form']  = true;
            $options['submit']    = $LANG['datainjection']['fileStep'][13];
            PluginDatainjectionClientInjection::showUploadFileForm($options);

         } else {
            if ($model->fields['step'] > PluginDatainjectionModel::FILE_STEP) {
               PluginDatainjectionMapping::showFormMappings($model);
            }
            if ($model->fields['step'] > PluginDatainjectionModel::MAPPING_STEP) {
               PluginDatainjectionInfo::showFormInfos($model);
               $model->showValidationForm();
            }
         }
         break;

      case 3 :
         $options['confirm']   = 'creation';
         $options['models_id'] = $model->fields['id'];
         $options['add_form']  = true;
         $options['submit']    = $LANG['datainjection']['fileStep'][13];
         PluginDatainjectionClientInjection::showUploadFileForm($options);
         break;

      case 4 :
         PluginDatainjectionMapping::showFormMappings($model);
         break;

      case 5 :
         if ($model->fields['step'] > PluginDatainjectionModel::MAPPING_STEP) {
            PluginDatainjectionInfo::showFormInfos($model);
         }
         break;

      case 6 :
         break;

      case 7:
         if ($model->fields['step'] > PluginDatainjectionModel::MAPPING_STEP) {
            $model->showValidationForm();
         }
         break;

      case 12 :
         Log::showForItem($model);
         break;

      default :
         if (!Plugin::displayAction($model,$_POST['glpi_tab'])) {
         }
   }

   ajaxFooter();
}

?>