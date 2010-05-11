<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

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

if ($_POST["id"] >0 && $model->can($_POST["id"],'r')) {
   $tmp = PluginDatainjectionModel::getInstance($model->fields['filetype']);

   switch($_POST['glpi_tab']) {
      case -1 :
         Plugin::displayAction($model,$_POST['glpi_tab']);
         $tmp->showForm($_POST['glpi_tab']);
         $model->showAdvancedForm($_POST["id"],$_POST['glpi_tab']);

         if ($model->fields['step'] > PluginDatainjectionModel::INITIAL_STEP) {
            $options['confirm'] = 'creation';
            $options['models_id'] = $model->fields['id'];
            $options['add_form'] = true;
            PluginDatainjectionClientInjection::showUploadFileForm($options);
         }
         else {
            if ($model->fields['step'] > PluginDatainjectionModel::FILE_STEP) {
               PluginDatainjectionMapping::showFormMappings($model);
            }
            if ($model->fields['step'] > PluginDatainjectionModel::MAPPING_STEP) {
               PluginDatainjectionInfo::showFormInfos($model);
               $model->showValidationForm();
            }
         }
         break;
      case 1:
         break;
      case 2:
         $tmp->showForm($_POST["id"],$_POST['glpi_tab']);
         $model->showAdvancedForm($_POST["id"],$_POST['glpi_tab']);
         break;
      case 3 :
         $options['confirm'] = 'creation';
         $options['models_id'] = $model->fields['id'];
         $options['add_form'] = true;
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