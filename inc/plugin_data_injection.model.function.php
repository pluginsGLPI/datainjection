<?php
/*
 * @version $Id: rules.constant.php 5351 2007-08-07 11:57:46Z walid $
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

function getAllModels($user_id)
{
	global $DB;
	
	$models = array();
	$sql = "SELECT * FROM glpi_plugin_data_injection_models WHERE public=".MODEL_PUBLIC." OR (public=".MODEL_PRIVATE." AND user_id=$user_id) ORDER BY name";
	$result = $DB->query($sql);
	while ($data = $DB->fetch_array($result))
		{	
		$model = new DataInjectionModel($data["ID"]);
		$model->fields = $data;
		$models[] = $model;
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

function exportModelAsCsv()
{
	$ficname = tempnam(PLUGIN_DATA_INJECTION_UPLOAD_DIR, "CSV");
	$fic = fopen($ficname, "wb");
	if (!$fic) return false;
	
	$sql ="SHOW COLUMNS FROM ".$this->table;
	
	if ($data=$DB->fetch_assoc($sql)){
		$str="";
		foreach ($data as $nom=>$val) {
			if (!empty($str)) $str.=";";
			$str .= '"' . $nom . '"';
		}
		
		fwrite($fic, $str . "\r\n");
	}

	$sql ="SELECT * FROM ".$this->table." WHERE ID=".$this->fields["ID"];
	$result = $DB->query($sql);	
	while ($data=$DB->fetch_assoc($result)) {
			$str="";
			foreach ($data as $nom=>$val) {
				if (!empty($str)) $str.=";";
				
				if (!empty($val))
					if (is_numeric($val))
						$str .= $val;
					else
						$str .= '"' . mysql_escape_string($val) . '"';
			}	
			fwrite($fic, $str . "\r\n");
		} 
	fclose($fic);
	return $ficname;
}
?>
