<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

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
// ----------------------------------------------------------------------
// Original Author of file: NOUH Walid & FONTAN Benjamin & CAILLAUD Xavier
// Purpose of file: plugin order v1.2.0 - GLPI 0.78
// ----------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");

if (!isset ($_GET["id"]))
   $_GET["id"] = "";
if (!isset ($_GET["withtemplate"]))
   $_GET["withtemplate"] = "";

$model = new PluginDatainjectionModel();

/* add order */
if (isset ($_POST["add"])) {
   $model->check(-1,'w',$_POST);
   $newID = $model->add($_POST);
   glpi_header($_SERVER['HTTP_REFERER']."?id=$newID");
}
/* delete order */
elseif (isset ($_POST["delete"])) {
   $model->check($_POST['id'],'w');
   $model->delete($_POST);
   glpi_header(getItemTypeSearchURL('PluginDatainjectionModel'));
}
/* restore order */
elseif (isset ($_POST["restore"])) {
   $model->check($_POST['id'],'w');
   $model->restore($_POST);
   glpi_header(getItemTypeSearchURL('PluginDatainjectionModel'));
}
/* purge order */
elseif (isset ($_POST["purge"])) {
   $model->check($_POST['id'],'w');
   $model->delete($_POST, 1);
   glpi_header(getItemTypeSearchURL('PluginDatainjectionModel'));
}
/* update order */
else if (isset ($_POST["update"])) {
   $model->check($_POST['id'],'w');
   $model->update($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);
}
elseif (isset($_POST['upload'])) {
   if (!empty($_FILES)) {
      $model->check($_POST['id'],'w');
      if ($model->processUploadedFile(array('file_encoding'=>$_POST['file_encoding'],
                                            'mode'=>PluginDatainjectionModel::CREATION))) {
         setActiveTab('PluginDatainjectionModel', 4);
      }
      else {
         addMessageAfterRedirect($LANG["datainjection"]["fileStep"][4],true,ERROR,true);
      }
   }
   glpi_header($_SERVER['HTTP_REFERER']);
}


commonHeader($LANG["datainjection"]["profiles"][1], '', "plugins", "datainjection", "model");

$model->showForm($_GET["id"]);

commonFooter();

?>