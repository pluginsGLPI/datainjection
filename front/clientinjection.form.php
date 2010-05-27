<?php
/*
   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

   http://indepnet.net/   http://glpi-project.org/
   ----------------------------------------------------------------------

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
   ------------------------------------------------------------------------
 */

// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------
if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
}
include (GLPI_ROOT."/inc/includes.php");

commonHeader($LANG["datainjection"]["name"][1], $_SERVER["PHP_SELF"],"plugins","datainjection");

if (isset($_POST['upload'])) {
   $model = new PluginDatainjectionModel();
   $model->getFromDB($_POST['id']);
   $_SESSION['glpi_plugin_datainjection_infos'] = (isset($_POST['info'])?$_POST['info']:array());

   //If additional informations provided : check if mandatory infos are present
   if (!$model->checkMandatoryFields($_SESSION['glpi_plugin_datainjection_infos'])) {
      addMessageAfterRedirect($LANG["datainjection"]["fillInfoStep"][4],true,ERROR,true);
   }
   elseif (!empty($_FILES) && !isset($_FILES['name'])) {
      //Read file using automatic encoding detection, and do not delete file once readed
      $options = array('file_encoding'=> $_POST['file_encoding'],
                       'mode'         => PluginDatainjectionModel::PROCESS,
                       'delete_file'  => false);
      $response = $model->processUploadedFile($options);
      if ($response) {
         //File uploaded successfully and matches the given model : switch to the import tab
         $_SESSION['glpi_plugin_datainjection_step'] =
                                          PluginDatainjectionClientInjection::STEP_PROCESS;
         //Store model in session for injection
         $_SESSION['glpi_plugin_datainjection_current_model'] = serialize($model);
      }
      else {
         //Got back to the file upload page
         $_SESSION['glpi_plugin_datainjection_step'] =
                                          PluginDatainjectionClientInjection::STEP_UPLOAD;
      }
   }
   else {
      addMessageAfterRedirect($LANG["datainjection"]["fileStep"][4],true,ERROR,true);
   }

   glpi_header($_SERVER['HTTP_REFERER']);
}

$clientInjection = new PluginDatainjectionClientInjection;
$clientInjection->title();
$clientInjection->showForm(1);

commonFooter();
?>
