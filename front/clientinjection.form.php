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

Session::checkRight("plugin_datainjection_use", READ);

Html::header(
    __('Data injection', 'datainjection'), $_SERVER["PHP_SELF"],
    "tools", "plugindatainjectionmenu", "client"
);

if (isset($_SESSION['datainjection']['go'])) {
    $model = unserialize($_SESSION['datainjection']['currentmodel']);
    PluginDatainjectionClientInjection::showInjectionForm($model, $_SESSION['glpiactive_entity']);

} else if (isset($_POST['upload'])) {
   $model = new PluginDatainjectionModel();
   $model->can($_POST['id'], READ);
   $_SESSION['datainjection']['infos'] = (isset($_POST['info'])?$_POST['info']:[]);

   //If additional informations provided : check if mandatory infos are present
   if (!$model->checkMandatoryFields($_SESSION['datainjection']['infos'])) {
      Session::addMessageAfterRedirect(
         __('One mandatory field is not filled', 'datainjection'),
         true, ERROR, true
      );

   } else if (isset($_FILES['filename']['name'])
      && $_FILES['filename']['name']
         && $_FILES['filename']['tmp_name']
            && !$_FILES['filename']['error']
               && $_FILES['filename']['size']) {

      //Read file using automatic encoding detection, and do not delete file once readed
      $options = [
         'file_encoding' => $_POST['file_encoding'],
         'mode'          => PluginDatainjectionModel::PROCESS,
         'delete_file'   => false
      ];
      $response = $model->processUploadedFile($options);
      $model->cleanData();

      if ($response) {
         //File uploaded successfully and matches the given model : switch to the import tab
         $_SESSION['datainjection']['file_name']    = $_FILES['filename']['name'];
         $_SESSION['datainjection']['step']         = PluginDatainjectionClientInjection::STEP_PROCESS;
         //Store model in session for injection
         $_SESSION['datainjection']['currentmodel'] = serialize($model);
         $_SESSION['datainjection']['go']           = true;
      } else {
         //Got back to the file upload page
         $_SESSION['datainjection']['step'] = PluginDatainjectionClientInjection::STEP_UPLOAD;
      }

   } else {
      Session::addMessageAfterRedirect(
          __('The file could not be found', 'datainjection'),
          true, ERROR, true
      );
   }

    Html::back();
} else if (isset($_POST['finish']) || isset($_POST['cancel'])) {

    PluginDatainjectionSession::removeParams();
    Html::redirect(Toolbox::getItemTypeFormURL('PluginDatainjectionClientInjection'));

} else {
   if (isset($_GET['id'])) { // Allow link to a model
      PluginDatainjectionSession::setParam('models_id', $_GET['id']);
   }
    $clientInjection = new PluginDatainjectionClientInjection();
    $clientInjection->title();
    $clientInjection->showForm(0);
}

Html::footer();
