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

if (!isset ($_GET["id"])) {
   $_GET["id"] = "";
}

if (!isset ($_GET["withtemplate"])) {
   $_GET["withtemplate"] = "";
}

$model = new PluginDatainjectionModel();
$model->checkGlobal(READ);

/* add */
if (isset ($_POST["add"])) {
   $model->check(-1, UPDATE ,$_POST);
   $newID = $model->add($_POST);

   //Set display to the advanced options tab
   Session::setActiveTab('PluginDatainjectionModel', 'PluginDatainjectionModel$3');
   Html::redirect(Toolbox::getItemTypeFormURL('PluginDatainjectionModel')."?id=$newID");

/* delete */
} else if (isset ($_POST["delete"])) {
   $model->check($_POST['id'], DELETE);
   $model->delete($_POST);
   $model->redirectToList();

/* update */
} else if (isset ($_POST["update"])) {
   //Update model
   $model->check($_POST['id'], UPDATE);
   $model->update($_POST);

   $specific_model = PluginDatainjectionModel::getInstance('csv');
   $specific_model->saveFields($_POST);
   Html::back();

/* update order */
} elseif (isset ($_POST["validate"])) {
   $model->check($_POST['id'], UPDATE);
   $model->switchReadyToUse();
   Html::back();

} elseif (isset($_POST['upload'])) {
   if (!empty($_FILES)) {
      $model->check($_POST['id'], UPDATE);

      if ($model->processUploadedFile(array('file_encoding' => 'csv',
                                            'mode'          => PluginDatainjectionModel::CREATION))) {
         Session::setActiveTab('PluginDatainjectionModel', 'PluginDatainjectionModel$4');
      } else {
         Session::addMessageAfterRedirect(__('The file could not be found', 'datainjection'),
                                          true, ERROR, true);
      }
   }
   Html::back();

} elseif (isset($_GET['sample'])) {
   $model->check($_GET['sample'], READ);
   $modeltype = PluginDatainjectionModel::getInstance($model->getField('filetype'));
   $modeltype->getFromDBByModelID($model->getField('id'));
   $modeltype->showSample($model);
   exit (0);
}

Html::header(PluginDatainjectionModel::getTypeName(), '', 
             "tools", "plugindatainjectionmenu", "model");

$model->display(array('id' =>$_GET["id"]));

Html::footer();
?>