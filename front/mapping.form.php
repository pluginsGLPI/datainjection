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
 @copyright Copyright (c) 2010-2011 Order plugin team
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://forge.indepnet.net/projects/datainjection
 @link      http://www.glpi-project.org/
 @since     2009
 ---------------------------------------------------------------------- */
 
define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");

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
      Session::addMessageAfterRedirect($LANG['datainjection']['mapping'][11], true, ERROR, true);
   } else {
      $model = new PluginDatainjectionModel();
      $model->getFromDB($_POST['models_id']);

      if ($model->fields['step'] != PluginDatainjectionModel::READY_TO_USE_STEP) {
         PluginDatainjectionModel::changeStep($_POST['models_id'],
                                              PluginDatainjectionModel::OTHERS_STEP);
         Session::setActiveTab('PluginDatainjectionModel', 4);
         Session::addMessageAfterRedirect($LANG['datainjection']['info'][3]);
      }
      unset($_SESSION['datainjection']['lines']);
   }
}
Html::redirect($_SERVER['HTTP_REFERER']);
?>