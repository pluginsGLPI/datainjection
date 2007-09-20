<?php
/*
 * @version $Id: rules.constant.php 5351 2007-08-07 11:57:46Z walid $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2007 by the INDEPNET Development Team.

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

function getAllModels($entity=0)
{
	global $DB;
	
	$models = array();
	$sql = "SELECT * FROM glpi_plugin_data_injection_models ORDER BY name";
	$result = $DB->query($sql);
	while ($data = $DB->fetch_array($result))
	{
		$model = new DataInjectionModel($data["ID"]);
		$model->fields = $data;
		$models[] = $model;
	}
	
	return $models;
}

function getAllModelsForChoiceStep($user_id,$entity=0)
{
	global $DB;
	
	$models = array();
	$sql = "SELECT * FROM glpi_plugin_data_injection_models ORDER BY name";
	$result = $DB->query($sql);
	while ($data = $DB->fetch_array($result))
	{	
		if($entity == 0 || $entity == $data["FK_entities"] || ($data["public"] == 0 && $data["user_id"] == $user_id))
			{
			if($data["public"] == 1 || ($data["public"] == 0 && $data["user_id"] == $user_id))
				{
				$model = new DataInjectionModel($data["ID"]);
				$model->fields = $data;
				$models[] = $model;
				}
			}
	}
	
	return $models;
}

function getModelInstanceByType($type)
{
	global $DB;
	$sql="SELECT model_class_name FROM glpi_plugin_data_injection_filetype WHERE value=".$type;
	$res = $DB->query($sql);
	if ($DB->numrows($res) > 0)
	{
		$backend_infos = $DB->fetch_array($res);
		return new $backend_infos["model_class_name"];
	}
	else
		return null;
}

function getModelInstanceByID($model_id)
{
	$model = new DataInjectionModel;
	$model->getFromDB($model_id);
	$model = getModelInstanceByType($model->getModelType());
	$model->getFromDB($model_id);
	return $model;
}
?>
