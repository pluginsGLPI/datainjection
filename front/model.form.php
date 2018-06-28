<?php
/*
 * @version $Id: HEADER 14684 2011-06-11 06:32:40Z remi $
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
 @copyright Copyright (c) 2010-2017 Datainjection plugin team
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://github.com/pluginsGLPI/datainjection
 @link      http://www.glpi-project.org/
 @since     2009
 ---------------------------------------------------------------------- */

require '../../../inc/includes.php';

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$model = new PluginDatainjectionModel();
$model->checkGlobal(READ);

if (isset($_POST["add"])) {
    /* add */
    $model->check(-1, UPDATE, $_POST);
    $newID = $model->add($_POST);

    //Set display to the advanced options tab
    Session::setActiveTab('PluginDatainjectionModel', 'PluginDatainjectionModel$3');
    Html::redirect(Toolbox::getItemTypeFormURL('PluginDatainjectionModel')."?id=$newID");

} else if (isset($_POST["delete"])) {
    /* delete */
    $model->check($_POST['id'], DELETE);
    $model->delete($_POST);
    $model->redirectToList();

} else if (isset($_POST["update"])) {
    /* update */
    //Update model
    $model->check($_POST['id'], UPDATE);
    $model->update($_POST);

    $specific_model = PluginDatainjectionModel::getInstance('csv');
    $specific_model->saveFields($_POST);
    Html::back();

} else if (isset($_POST["validate"])) {
    /* update order */
    $model->check($_POST['id'], UPDATE);
    $model->switchReadyToUse();
    Html::back();

} else if (isset($_POST['upload'])) {
   if (!empty($_FILES)) {
      $model->check($_POST['id'], UPDATE);

      if ($model->processUploadedFile(
          [
             'file_encoding' => 'csv',
            'mode'          => PluginDatainjectionModel::CREATION
          ]
      )) {
         Session::setActiveTab('PluginDatainjectionModel', 'PluginDatainjectionModel$4');
      } else {
         Session::addMessageAfterRedirect(
            __('The file could not be found', 'datainjection'),
            true, ERROR, true
         );
      }
   }
    Html::back();

} else if (isset($_GET['sample'])) {
    $model->check($_GET['sample'], READ);
    $modeltype = PluginDatainjectionModel::getInstance($model->getField('filetype'));
    $modeltype->getFromDBByModelID($model->getField('id'));
    $modeltype->showSample($model);
    exit(0);
}

Html::header(
    PluginDatainjectionModel::getTypeName(), '',
    "tools", "plugindatainjectionmenu", "model"
);

$model->display(['id' =>$_GET["id"]]);

Html::footer();
