<?php
/*
 * @version $Id$
 LICENSE

 This file is part of the datainjection plugin.

 Datainjection plugin is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Datainjection plugin is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with datainjection. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 @package   datainjection
 @author    the datainjection plugin team
 @copyright Copyright (c) 2010-2013 Datainjection plugin team
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://forge.indepnet.net/projects/datainjection
 @link      http://www.glpi-project.org/
 @since     2009
 ---------------------------------------------------------------------- */

include ('../../../inc/includes.php');

/* Update mappings */
if (isset ($_POST["update"])) {
   $at_least_one_mandatory = false;
   $mapping                = new PluginDatainjectionMapping();

   foreach ($_POST['data'] as $id => $mapping_infos) {
      $mapping_infos['id'] = $id;

      //If no field selected, reset other values
      if ($mapping_infos['value'] == PluginDatainjectionInjectionType::NO_VALUE) {
         $mapping_infos['itemtype']     = PluginDatainjectionInjectionType::NO_VALUE;
         $mapping_infos['is_mandatory'] = 0;

      } else {
         $mapping_infos['is_mandatory'] = (isset($mapping_infos['is_mandatory'])?1:0);
      }

      if ($mapping_infos['is_mandatory']) {
         $at_least_one_mandatory = true;
      }

      $mapping->update($mapping_infos);
   }

   if (!$at_least_one_mandatory) {
      Session::addMessageAfterRedirect(__('One link field must be selected: it will be used to check if data already exists',
                                          'datainjection'), true, ERROR, true);
   } else {
      $model = new PluginDatainjectionModel();
      $model->getFromDB($_POST['models_id']);

      if ($model->fields['step'] != PluginDatainjectionModel::READY_TO_USE_STEP) {
         PluginDatainjectionModel::changeStep($_POST['models_id'],
                                              PluginDatainjectionModel::OTHERS_STEP);
         Session::setActiveTab('PluginDatainjectionModel', 'PluginDatainjectionModel$5');
         Session::addMessageAfterRedirect(__("This step allows you to add informations not present in the file. You'll be asked for theses informations while using the model.",
                                             'datainjection'));
      }
      unset($_SESSION['datainjection']['lines']);
   }
}

Html::back();
?>