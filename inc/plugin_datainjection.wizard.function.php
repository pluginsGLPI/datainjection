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

// ----------------------------------------------------------------------
// Original Author of file: Dévi Balpe
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

function choiceStep($target) {
	global $LANG, $LANG;

	$models = PluginDatainjectionModel::getModels($_SESSION["glpiID"], "name", $_SESSION["glpiactive_entity"]);

	$nbmodel = count($models);

	echo "<form action='" . $target . "' method='post'>";
	echo "<table class='wizard'>";

	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";

	/************************Title Step****************************/
	echo "<div class='wizard_title' valign='bottom'>" . $LANG["datainjection"]["step"][1] . $LANG["datainjection"]["choiceStep"][1] . "</div>";
	/**************************************************************/

	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>" . $LANG["datainjection"]["choiceStep"][2] . "</div>";
	echo "<div class='wizard_explain'>" . $LANG["datainjection"]["choiceStep"][10] . "</div>";
	echo "<div class='wizard_explain'>" . $LANG["datainjection"]["choiceStep"][11] . "</div>";

	/**************************************************************/

	echo "</td>";

	echo "<td class='wizard_right_area' style='width:400px' valign='top'>";
	echo "<fieldset class='choiceStep_selection'>";
	echo "<legend>" . $LANG["datainjection"]["choiceStep"][9] . "</legend>";
	echo "<table class='choiceStep_table'>";

	/***************************Create*****************************/
	if (plugin_datainjection_haveRight("model", "w")) {

		echo "<tr>";
		echo "<td style='height: 40px;'><input type='radio' id='create' name='choice' value='1' onClick='show_Select($nbmodel);deleteOnglet(6)' checked /></td>";
		echo "<td>" . $LANG["datainjection"]["choiceStep"][3] . "</td>";
		echo "</tr>";
	}
	/**************************************************************/

	if ($nbmodel > 0) {
		/**************************Update******************************/
		if (plugin_datainjection_haveRight("model", "w")) {
			echo "<tr>";
			echo "<td><input type='radio' name='choice' id='choice2' value='2' onClick='show_Select($nbmodel);deleteOnglet(5)' /></td>";
			echo "<td>" . $LANG["datainjection"]["choiceStep"][4] . "</td>";
			echo "</tr>";

			/**************************************************************/

			/**************************Delete******************************/
			echo "<tr>";
			echo "<td><input type='radio' name='choice' id='choice3' value='3' onClick='show_Select($nbmodel);deleteOnglet(2)' /></td>";
			echo "<td>" . $LANG["datainjection"]["choiceStep"][5] . "</td>";
			echo "</tr>";
		}

		/**************************************************************/

		/**************************Using*******************************/
		echo "<tr>";
		echo "<div ='choice4_div'><td>";
		if (plugin_datainjection_haveRight("model", "r"))
			if ($_SESSION["plugin_datainjection"]["choice"] == 4)
				echo "<input type='radio' name='choice' id='choice4' value='4' onClick='show_Select($nbmodel);deleteOnglet(5)' checked />";
			else
				echo "<input type='radio' name='choice' id='choice4' value='4' onClick='show_Select($nbmodel);deleteOnglet(5)' />";
		else
			echo "<input type='radio' name='choice' id='choice4' value='4' onClick='show_Select($nbmodel);deleteOnglet(5)' disabled />";
		echo "</td></div>";
		echo "<td>" . $LANG["datainjection"]["choiceStep"][6] . "</td>";
		echo "</tr>";
		echo "</table>";

		/**************************************************************/

		/************************Select Model**************************/
		echo "<div class='choiceStep_dropdown'>";
		dropdownModels(plugin_datainjection_haveRight("model", "w"), $models);
		echo "</div>";
		echo "</fieldset>";
		/**************************************************************/

		/***********************Comments Model*************************/
		echo "<div id='comment_select'>";

		foreach ($models as $key => $model) {
			$comment = $model->getModelComments();

			echo "<fieldset class='choiceStep_comments' id='comments" . $key . "'>";
			echo "<legend>" . $LANG["datainjection"]["choiceStep"][7] . "</legend>";
			if (!empty ($comment))
				echo $comment;
			else
				echo $LANG["datainjection"]["choiceStep"][8];
			echo "</fieldset>";
		}

		echo "</div>";
		/**************************************************************/
	} else {
		echo "</table>";
		echo "</fieldset>";
	}

	if (!plugin_datainjection_haveRight("model", "w"))
		echo "<script type='text/javascript'>show_comments($nbmodel)</script>";

	echo "</td></tr>";

	/**************************Button******************************/
	echo "<tr><td class='wizard_button' colspan='2'>";
	echo "<div class='next'>";
	echo "<input type='submit' name='next_choiceStep' value='" . $LANG["datainjection"]["button"][2] . "' class='submit' />";
	echo "</div>";
	echo "</td></tr>";
	/**************************************************************/

	echo "</table>";
	echo "</form>";
}

function model($target) {
	global $LANG, $LANG;

	if (isset ($_SESSION["plugin_datainjection"]["model"]))
		$model = unserialize($_SESSION["plugin_datainjection"]["model"]);

	echo "<form action='" . $target . "' method='post'>";
	echo "<table class='wizard'>";

	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";

	/************************Title Step****************************/
	if ($_SESSION["plugin_datainjection"]["choice"] == 1)
		echo "<div class='wizard_title' valign='bottom'>" . $LANG["datainjection"]["step"][2] . $LANG["datainjection"]["model"][1] . "</div>";
	else
		echo "<div class='wizard_title' valign='bottom'>" . $LANG["datainjection"]["step"][2] . $LANG["datainjection"]["model"][2] . "</div>";
	/**************************************************************/

	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>" . $LANG["datainjection"]["model"][3] . "</div>";
	echo "<div class='wizard_explain'>" . $LANG["datainjection"]["model"][19] . "</div>";
	/**************************************************************/

	echo "</td>";

	echo "<td class='wizard_right_area' style='width:400px' valign='top'>";
	echo "<fieldset class='model_selection'>";
	echo "<legend>" . $LANG["datainjection"]["model"][13] . "</legend>";
	echo "<table class='model_table'>";

	echo "<tr><td colspan=2>";
	privatePublicSwitch((isset ($model->fields["private"]) ? $model->fields["private"] : 1), (isset ($model->fields["FK_entities"]) ? $model->fields["FK_entities"] : 0), (isset ($model->fields["recursive"]) ? $model->fields["recursive"] : 0));
	echo "</tr></td>";

	/***********************Device Type****************************/
	echo "<tr><td style='width:250px'>" . $LANG["datainjection"]["model"][4] . "</td>";
	echo "<td style='width:150px'>";
	dropdownPrimaryTypeSelection("dropdown_device_type", (isset ($model) ? $model : null), (($_SESSION["plugin_datainjection"]["choice"] == 1) ? false : true));
	echo "</td></tr>";

	/**************************************************************/

	/**************************Type********************************/
	echo "<tr><td>" . $LANG["datainjection"]["model"][5] . "</td>";
	if ($_SESSION["plugin_datainjection"]["choice"] == 1) {
		$id = 0;
		echo "<td><select id='dropdown_type' name='dropdown_type' onchange='show_backend($id)'>";
	} else {
		echo "<td><select name='dropdown_type' style='background-color:#e6e6e6' disabled>";
		$id = $model->getModelType();
	}

	$types = getAllTypes();

	foreach ($types as $key => $type) {
		if (isset ($model)) {
			if ($model->getModelType() == $type->getBackendID())
				echo "<option value='" . $type->getBackendID() . "' selected>" . $type->getBackendName() . "</option>";
			else
				echo "<option value='" . $type->getBackendID() . "'>" . $type->getBackendName() . "</option>";
		} else
			echo "<option value='" . $type->getBackendID() . "'>" . $type->getBackendName() . "</option>";
	}

	echo "</select></td></tr>";

	/**************************************************************/

	/***********************Behavior add***************************/
	echo "<tr><td>" . $LANG["datainjection"]["model"][6] . "</td>";
	echo "<td>";
	dropdownYesNo("dropdown_create", (isset ($model)?$model->getBehaviorAdd():1));
	echo "</td></tr>";

	/**************************************************************/

	/**********************Behavior update*************************/
	echo "<tr><td>" . $LANG["datainjection"]["model"][7] . "</td>";

	echo "<td>";
	dropdownYesNo("dropdown_update", (isset ($model)?$model->getBehaviorUpdate():0));
	echo "</td></tr>";

	/**************************************************************/

	echo "</table>";
	echo "</fieldset>";

	echo "<fieldset id='option_backend' class='model_selection'>";

	echo "</fieldset>";

	echo "<script type='text/javascript'>show_backend($id)</script>";

	echo "<fieldset class='model_selection'>";
	echo "<legend><a href='javascript:show_option()'><img src='../pics/plus.png' alt='plus' id='option_img' style='width:20px;float:left' /></a>" . $LANG["datainjection"]["model"][15] . "</legend>";
	echo "<table class='model_table' id='option' style='display:none'>";

	/**********************Can add dropdown************************/
	echo "<tr><td style='width:250px'>" . $LANG["datainjection"]["model"][8] . "</td>";
	echo "<td style='width:150px'>";
	dropdownYesNo("dropdown_canadd", (isset ($model)?$model->getCanAddDropdown():0));
	echo "</td></tr>";
	/**************************************************************/

	/***************Can overwrite if not empty*********************/
	echo "<tr><td>" . $LANG["datainjection"]["model"][12] . "</td><td>";
	dropdownYesNo("can_overwrite_if_not_empty", (isset ($model)?$model->getCanOverwriteIfNotEmpty():1));
	echo "</td></tr>";
	/**************************************************************/

	/***************Date format*********************/
	echo "<tr><td>" . $LANG["datainjection"]["model"][21] . "</td><td>";
	dropdownDateFormat("date_format", (isset ($model) ? $model->getDateFormat() : DATE_TYPE_YYYYMMDD));
	echo "</td></tr>";
	/**************************************************************/

	/***************Float format*********************/
	echo "<tr><td>" . $LANG["setup"][150] . "</td><td>";
	dropdownFloatFormat("float_format", (isset ($model) ? $model->getFloatFormat() : FLOAT_TYPE_DOT));
	echo "</td></tr>";
	/**************************************************************/

	/***************Can add network connections*********************/
	echo "<tr><td>" . $LANG["datainjection"]["model"][20] . "</td><td>";
	dropdownYesNo("perform_network_connection", (isset ($model)?$model->getPerformNetworkConnection():0));
	echo "</td></tr>";

	/**************************************************************/

	/***************Port existance*********************/
	echo "<tr><td>" . $LANG["datainjection"]["mappings"][7] . "</td><td>";
	dropdownPortUnicity("port_unicity", (isset ($model) ? $model->getPortUnicity() : PluginDatainjectionModel::UNICITY_NETPORT_LOGICAL_NUMBER));
	echo "</td></tr>";
	/**************************************************************/


	echo "</table>";
	echo "</fieldset>";

	/*********************Delimiter Error**************************/
	echo "<div id='delimiter_error' class='delimiter' >" . $LANG["datainjection"]["model"][11] . "</div>";
	/**************************************************************/

	echo "</td></tr>";

	/**************************************************************/

	/**************************Button******************************/
	echo "<tr><td class='wizard_button' colspan='2'>";
	echo "<div class='preview'>";
	echo "<input type='submit' name='preview_model' value='" . $LANG["datainjection"]["button"][1] . "' class='submit' />";
	echo "</div>";

	echo "<div class='next'>";
	echo "<input type='submit' name='next_model' value='" . $LANG["datainjection"]["button"][2] . "' class='submit' onclick='return verif_delimiter()' />";
	echo "</div>";
	echo "</td></tr>";
	/**************************************************************/

	echo "</table>";
	echo "</form>";
}

function deleteStep($target, $suppr) {
	global $LANG, $LANG;

	$model = PluginDatainjectionModel::getInstanceByID($_SESSION["plugin_datainjection"]["idmodel"]);
	$name = $model->getModelName();

	echo "<form action='" . $target . "' method='post'>";
	echo "<table class='wizard'>";

	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";

	/************************Title Step****************************/
	echo "<div class='wizard_title' valign='bottom'>" . $LANG["datainjection"]["step"][2] . $LANG["datainjection"]["deleteStep"][1] . "</div>";
	/**************************************************************/

	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>" . $LANG["datainjection"]["deleteStep"][2] . "</div>";
	/**************************************************************/

	echo "</td>";

	echo "<td class='wizard_right_area' style='width:400px' valign='top'>";

	/**********************Confirm delete**************************/
	if ($suppr) {
		if ($model->deleteModel())
			echo "<div class='save_delete'>" . $LANG["datainjection"]["deleteStep"][5] . " \" " . $name . " \" " . $LANG["datainjection"]["deleteStep"][6] . "</div>";
		else
			echo "<div class='save_delete'>" . $LANG["datainjection"]["deleteStep"][7] . "</div>";
	} else {
		echo "<table class='deleteStep_table'>";
		echo "<tr>";
		echo "<td colspan='2' class='question'>" . $LANG["datainjection"]["deleteStep"][3] . "<br />\" " . $name . " \"<br />" . $LANG["datainjection"]["deleteStep"][4] . "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td><input type='submit' name='yes_deleteStep' value='" . $LANG["choice"][1] . "' class='submit' /></td>";
		echo "<td><input type='submit' name='no_deleteStep' value='" . $LANG["choice"][0] . "' class='submit' /></td>";
		echo "</tr>";
		echo "</table>";
	}
	/**************************************************************/

	echo "</td></tr>";

	/**************************Button******************************/
	echo "<tr><td class='wizard_button' colspan='2'>";
	if ($suppr) {
		echo "<div class='next'>";
		echo "<input type='submit' name='next_deleteStep' value='" . $LANG["datainjection"]["button"][6] . "' class='submit' />";
		echo "</div>";
	}
	echo "</td></tr>";
	/**************************************************************/

	echo "</table>";
	echo "</form>";
}

function fileStep($target, $error) {
	global $LANG;

	echo "<form action='" . $target . "' method='post' enctype='multipart/form-data'>";
	echo "<table class='wizard'>";

	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";

	/************************Title Step****************************/
	if ($_SESSION["plugin_datainjection"]["choice"] == 1)
		echo "<div class='wizard_title' valign='bottom'>" . $LANG["datainjection"]["step"][3] . $LANG["datainjection"]["fileStep"][1] . "</div>";
	else
		echo "<div class='wizard_title' valign='bottom'>" . $LANG["datainjection"]["step"][2] . $LANG["datainjection"]["fileStep"][1] . "</div>";
	/**************************************************************/

	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>" . $LANG["datainjection"]["fileStep"][2] . "</div>";
	/**************************************************************/

	echo "</td>";

	echo "<td class='wizard_right_area' style='width:400px' valign='top'>";

	/************************Select File***************************/
	echo "<table class='fileStep_table'>";
	echo "<tr>";
	echo "<td style='text-align: left'>" . $LANG["datainjection"]["fileStep"][3] . "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><input type='file' name='file' /></td>";
	echo "</tr>";
	/**************************************************************/

	/***********************File encoding**************************/
	echo "<tr style='height:60px' valign='bottom'>";
	echo "<td>" . $LANG["datainjection"]["fileStep"][9] . "</td>";
	echo "</tr>";
	echo "<td>";
	dropdownFileEncoding("dropdown_encoding");
	echo "</td></tr>";
	echo "</table>";
	/**************************************************************/

	/*************************File Error***************************/
	if (!empty ($error))
		echo "<div class='fileStep_error'>" . $error . "</div>";
	/**************************************************************/

	echo "</td></tr>";

	/**************************Button******************************/
	echo "<tr><td class='wizard_button' colspan='2'>";
	echo "<div class='preview'>";
	echo "<input type='submit' name='preview_fileStep' value='" . $LANG["datainjection"]["button"][1] . "' class='submit' />";
	echo "</div>";

	echo "<div class='next'>";
	echo "<input type='submit' name='next_fileStep' value='" . $LANG["datainjection"]["button"][2] . "' class='submit' />";
	echo "</div>";
	echo "</td></tr>";
	/**************************************************************/

	echo "</table>";
	echo "</form>";
}

function mappingStep($target) {
	global $LANG;

	$model = unserialize($_SESSION["plugin_datainjection"]["model"]);

	/***********************Read File******************************/
	if ($_SESSION["plugin_datainjection"]["choice"] == 1) {
		$file = unserialize($_SESSION["plugin_datainjection"]["backend"]);

		$nbline = $file->getNumberOfLine();

		if ($model->isHeaderPresent())
			$nbline--;

		$header = $file->getHeader($model->isHeaderPresent());

		$num = count($header);
	} else
		$num = count($model->getMappings());
	/**************************************************************/

	echo "<form action='" . $target . "' method='post'>";
	echo "<table class='wizard'>";

	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";

	/************************Title Step****************************/
	if ($_SESSION["plugin_datainjection"]["choice"] == 1)
		echo "<div class='wizard_title' valign='bottom'>" . $LANG["datainjection"]["step"][4] . " " . $num . " " . $LANG["datainjection"]["mapping"][1] . "</div>";
	else
		echo "<div class='wizard_title' valign='bottom'>" . $LANG["datainjection"]["step"][3] . " " . $num . " " . $LANG["datainjection"]["mapping"][1] . "</div>";
	/**************************************************************/

	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>" . $LANG["datainjection"]["mappingStep"][9] . "</div>";
	echo "<div class='wizard_explain'>" . $LANG["datainjection"]["mappingStep"][10] . "</div>";
	echo "<div class='wizard_explain'>" . $LANG["datainjection"]["mappingStep"][11] . "</div>";
	/**************************************************************/

	echo "</td>";

	echo "<td class='wizard_right_area' valign='top'>";
	echo "<table style='margin-top:30px'>";

	/***********************Header Table***************************/
	echo "<tr style='text-align:center'>";
	echo "<th>" . $LANG["datainjection"]["mappingStep"][2] . "</th>";
	echo "<th></th>";
	echo "<th>" . $LANG["datainjection"]["mappingStep"][3] . "</th>";
	echo "<th>" . $LANG["datainjection"]["mappingStep"][4] . "</th>";
	echo "<th>" . $LANG["datainjection"]["mappingStep"][5] . "</th>";
	echo "</tr>";
	/**************************************************************/

	if ($_SESSION["plugin_datainjection"]["choice"] == 2 || $_SESSION["plugin_datainjection"]["remember"] >= 1)
		foreach ($model->getMappings() as $key => $value) {
			echo "<tr>";

			/************************Header File***************************/
			echo "<td><input id='name$key' type='hidden' name='field[$key][0]' value=\"" . $value->getName() . "\" />" . stripslashes($value->getName()) . " : </td>";
			/**************************************************************/

			/***********************Arrow Picture**************************/
			echo "<td style='text-align:center;width:75px'><img src='../pics/fleche.png' alt='fleche' /></td>";
			/**************************************************************/

			/************************Select table**************************/
			echo "<td>";
			echo "<select name='field[$key][1]' id='table$key' onchange='go_mapping($key," . $model->getDeviceType() . ")' style='width: 150px'>";

			$types = getAllMappingsDefinitionsTypes($model->getDeviceType());

			echo "<option value='-1'>" . $LANG["datainjection"]["mappingStep"][6] . "</option>";

			foreach ($types as $type) {
				if ($value->getMappingType() == $type[0])
					echo "<option value='" . $type[0] . "' selected>" . $type[1] . "</option>";
				else
					echo "<option value='" . $type[0] . "'>" . $type[1] . "</option>";
			}

			echo "</select>";
			echo "</td>";
			/**************************************************************/

			/************************Select field**************************/
			echo "<td id='field$key'>";
			echo "<select name='field[$key][2]' style='width: 150px'>";

			if ($value->getMappingType() == -1)
				echo "<option value='-1'>" . $LANG["datainjection"]["mappingStep"][7] . "</option>";
			else {
				$values = getAllMappingsDefinitionsByType($value->getMappingType());

				foreach ($values as $key2 => $value2) {
					if ($value->getValue() == $key2)
						echo "<option value='" . $key2 . "' selected>" . $value2 . "</option>";
					else
						echo "<option value='" . $key2 . "'>" . $value2 . "</option>";
				}
			}

			echo "</select>";
			echo "</td>";
			/**************************************************************/

			/*********************Checkbox Mandatory***********************/
			if ($value->getMappingType() != $model->getDeviceType())
				echo "<td style='text-align:center'><input type='checkbox' name='field[$key][3]' id='check$key' disabled /></td>";
			else {
				if ($value->isMandatory())
					echo "<td style='text-align:center'><input type='checkbox' name='field[$key][3]' id='check$key' checked /></td>";
				else
					echo "<td style='text-align:center'><input type='checkbox' name='field[$key][3]' id='check$key' /></td>";
			}
			/**************************************************************/

			echo "</tr>";
		} else
		foreach ($header as $key => $value) {
			echo "<tr>";

			/************************Header File***************************/
			echo "<td><input id='name$key' type='hidden' name='field[$key][0]' value=\"$value\" />" . stripslashes($value) . " : </td>";
			/**************************************************************/

			/***********************Arrow Picture**************************/
			echo "<td style='text-align:center;width:75px'><img src='../pics/fleche.png' alt='fleche' /></td>";
			/**************************************************************/

			/************************Select table**************************/
			echo "<td>";
			echo "<select name='field[$key][1]' id='table$key' onchange='go_mapping($key," . $model->getDeviceType() . ")' style='width: 150px'>";

			$types = getAllMappingsDefinitionsTypes($model->getDeviceType());

			echo "<option value='-1'>" . $LANG["datainjection"]["mappingStep"][6] . "</option>";

			foreach ($types as $type)
				echo "<option value='" . $type[0] . "'>" . $type[1] . "</option>";

			echo "</select>";
			echo "</td>";
			/**************************************************************/

			/************************Select field**************************/
			echo "<td id='field$key'>";
			echo "<select name='field[$key][2]' style='width: 150px'>";
			echo "<option value='-1'>" . $LANG["datainjection"]["mappingStep"][7] . "</option>";
			echo "</select>";
			echo "</td>";
			/**************************************************************/

			/*********************Checkbox Mandatory***********************/
			echo "<td style='text-align:center'><input type='checkbox' name='field[$key][3]' id='check$key' disabled /></td>";
			/**************************************************************/

			echo "</tr>";
		}

	/***********************Mandatory Error************************/
	echo "<tr>";
	echo "<td class='mandatory' id='mandatory_error' colspan='5'>" . $LANG["datainjection"]["mappingStep"][8] . "</td>";
	echo "</tr>";
	/**************************************************************/

	/*************************File View****************************/
	if ($_SESSION["plugin_datainjection"]["choice"] == 1) {
		echo "<tr><td colspan='5'>";
		echo "<table style='margin-top: 10px'>";
		echo "<tr><td style='text-align:center'>";
		echo $LANG["datainjection"]["mappingStep"][12] . " : <input type='text' id='nbline' name='nbline' size='2' maxlength='3' value='1' onfocus=\"this.value=''\" /> / " . $nbline;
		echo "</td></tr>";
		echo "<tr><td style='text-align:center'>";
		echo "<input type='button' name='popup' value='" . $LANG["datainjection"]["button"][3] . "' class='submit' onclick='file_popup($nbline)' />";
		echo "</td></tr>";
		echo "</table>";
		echo "</td></tr>";
	}
	/**************************************************************/

	echo "</table>";
	echo "</td></tr>";

	/**************************Button******************************/
	echo "<tr><td class='wizard_button' colspan='2'>";
	echo "<div class='preview'>";
	echo "<input type='submit' name='preview_mappingStep' value='" . $LANG["datainjection"]["button"][1] . "' class='submit' />";
	echo "</div>";

	echo "<div class='next'>";
	echo "<input type='submit' name='next_mappingStep' value='" . $LANG["datainjection"]["button"][2] . "' class='submit' onclick='return verif_mandatory($num)' />";
	echo "</div>";
	echo "</td></tr>";
	/**************************************************************/

	echo "</table>";
	echo "</form>";
}

function infoStep($target) {
	global $LANG;

	$model = unserialize($_SESSION["plugin_datainjection"]["model"]);

	echo "<form action='" . $target . "' method='post'>";
	echo "<table class='wizard'>";

	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";

	/************************Title Step****************************/
	if ($_SESSION["plugin_datainjection"]["choice"] == 1)
		echo "<div class='wizard_title' valign='bottom'>" . $LANG["datainjection"]["step"][5] . $LANG["datainjection"]["info"][1] . "</div>";
	else
		echo "<div class='wizard_title' valign='bottom'>" . $LANG["datainjection"]["step"][4] . $LANG["datainjection"]["info"][2] . "</div>";
	/**************************************************************/

	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>" . $LANG["datainjection"]["info"][3] . "</div>";
	echo "<div class='wizard_explain'>" . $LANG["datainjection"]["info"][4] . "</div>";
	/**************************************************************/

	echo "</td>";

	echo "<td class='wizard_right_area' style='width: 400px' valign='top'>";
	echo "<table style='margin-top:30px'>";

	/***********************Header Table***************************/
	echo "<tr style='text-align:center'>";
	echo "<th style='width:150px;'>" . $LANG["datainjection"]["mappingStep"][3] . "</th>";
	echo "<th style='width:150px;'>" . $LANG["datainjection"]["mappingStep"][4] . "</th>";
	echo "<th >" . $LANG["datainjection"]["info"][5] . "</th>";
	echo "</tr>";
	/**************************************************************/

	echo "</table>";

	$nbline = 0;

	if ($_SESSION["plugin_datainjection"]["choice"] == 2 || $_SESSION["plugin_datainjection"]["remember"] == 2) {
		$nbline = count($model->getInfos());
		if ($nbline > 0) {
			foreach ($model->getInfos() as $indice => $value) {
				$key = $indice +1;

				echo "<div id='select$key'>";
				echo "<table id='tab$key'>";

				/***********************Already Change*************************/
				echo "<tr>";
				if ($value->getInfosType() != -1)
					echo "<td><input type='hidden' id='add$key' value='1'></td></td>";
				else
					echo "<td><input type='hidden' id='add$key' value='0'></td></td>";
				/**************************************************************/

				/*************************Select table*************************/
				echo "<td>";

				$types = getAllInfosDefinitionsTypes($model->getDeviceType());

				echo "<select name='field[$key][0]' id='table$key' onchange='go_info($key);addelete_info($key)' style='width: 150px'>";

				echo "<option value='-1'>" . $LANG["datainjection"]["mappingStep"][6] . "</option>";

				foreach ($types as $type)
					if ($value->getInfosType() == $type[0])
						echo "<option value='" . $type[0] . "' selected>" . $type[1] . "</option>";
					else
						echo "<option value='" . $type[0] . "'>" . $type[1] . "</option>";

				echo "</select>";
				echo "</td>";
				/**************************************************************/

				/************************Select field**************************/
				echo "<td id='field$key'>";
				echo "<select name='field[$key][1]' style='width: 150px'>";

				if ($value->getInfosType() == -1)
					echo "<option value='-1'>" . $LANG["datainjection"]["mappingStep"][7] . "</option>";
				else {
					$values = getAllInfosDefinitionsByType($value->getInfosType());

					foreach ($values as $key2 => $value2)
						if ($value->getValue() == $key2)
							echo "<option value='" . $key2 . "' selected>" . $value2 . "</option>";
						else
							echo "<option value='" . $key2 . "'>" . $value2 . "</option>";
				}

				echo "</select>";
				echo "</td>";
				/**************************************************************/

				/*********************Checkbox Mandatory***********************/
				if ($value->getInfosType() == -1)
					echo "<td style='text-align:center'><input type='checkbox' name='field[$key][2]' id='check$key' disabled /></td>";
				else {
					if ($value->isMandatory())
						echo "<td style='text-align:center'><input type='checkbox' name='field[$key][2]' id='check$key' checked /></td>";
					else
						echo "<td style='text-align:center'><input type='checkbox' name='field[$key][2]' id='check$key' /></td>";
				}
				/**************************************************************/

				echo "</tr>";
				echo "</table>";
				echo "</div>";
			}

			/*********************************Add a blank line*********************************/
			$key++;

			echo "<div id='select$key'>";
			echo "<table id='tab$key'>";

			echo "<tr>";

			/***********************Already Change*************************/
			echo "<td><input type='hidden' id='add$key' value='0'></td>";
			/**************************************************************/

			/*************************Select table*************************/
			echo "<td>";

			$types = getAllInfosDefinitionsTypes($model->getDeviceType());

			echo "<select name='field[$key][0]' id='table$key' onchange='go_info($key);addelete_info($key)' style='width: 150px'>";

			echo "<option value='-1'>" . $LANG["datainjection"]["mappingStep"][6] . "</option>";

			foreach ($types as $type)
				echo "<option value='" . $type[0] . "'>" . $type[1] . "</option>";

			echo "</select>";
			echo "</td>";
			/**************************************************************/

			/************************Select field**************************/
			echo "<td id='field$key'>";
			echo "<select name='field[$key][1]' style='width: 150px'>";
			echo "<option value='-1'>" . $LANG["datainjection"]["mappingStep"][7] . "</option>";
			echo "</select>";
			echo "</td>";
			/**************************************************************/

			/*********************Checkbox Mandatory***********************/
			echo "<td style='text-align:center'><input type='checkbox' name='field[$key][2]' id='check$key' disabled /></td>";
			/**************************************************************/

			echo "</tr>";
			echo "</table>";
			echo "</div>";

			$key++;

			echo "<div id='select$key'>";
			echo "</div>";
			/**********************************************************************************/
		}
	}

	if ((isset ($_SESSION["plugin_datainjection"]["remember"]) && $_SESSION["plugin_datainjection"]["remember"] == 1) || $nbline == 0) {
		$key = 1;

		echo "<div id='select$key'>";
		echo "<table id='tab$key'>";

		echo "<tr>";

		/***********************Already Change*************************/
		echo "<td><input type='hidden' id='add$key' value='0'></td>";
		/**************************************************************/

		/*************************Select table*************************/
		echo "<td>";

		$types = getAllInfosDefinitionsTypes($model->getDeviceType());

		echo "<select name='field[$key][0]' id='table$key' onchange='go_info($key);addelete_info($key)' style='width: 150px'>";

		echo "<option value='-1'>" . $LANG["datainjection"]["mappingStep"][6] . "</option>";

		foreach ($types as $type)
			echo "<option value='" . $type[0] . "'>" . $type[1] . "</option>";

		echo "</select>";

		echo "</td>";
		/**************************************************************/

		/************************Select field**************************/
		echo "<td id='field$key'>";
		echo "<select name='field[$key][1]' style='width: 150px'>";
		echo "<option value='-1'>" . $LANG["datainjection"]["mappingStep"][7] . "</option>";
		echo "</select>";
		echo "</td>";
		/**************************************************************/

		/*********************Checkbox Mandatory***********************/
		echo "<td style='text-align:center'><input type='checkbox' name='field[$key][2]' id='check$key' style='width:60px;' disabled /></td>";
		/**************************************************************/

		echo "</tr>";
		echo "</table>";
		echo "</div>";

		$key++;

		echo "<div id='select$key'>";
		echo "</div>";
	}

	echo "</td></tr>";

	/**************************Button******************************/
	echo "<tr><td class='wizard_button' colspan='2'>";
	echo "<div class='preview'>";
	echo "<input type='submit' name='preview_infoStep' value='" . $LANG["datainjection"]["button"][1] . "' class='submit' />";
	echo "</div>";

	echo "<div class='next'>";
	echo "<input type='submit' name='next_infoStep' value='" . $LANG["datainjection"]["button"][2] . "' class='submit' />";
	echo "</div>";
	echo "</td></tr>";
	/**************************************************************/

	echo "</table>";
	echo "</form>";
}

function saveStep($target, $save) {
	global $LANG, $LANG;

	echo "<form action='" . $target . "' method='post'>";
	echo "<table class='wizard'>";

	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";

	/************************Title Step****************************/
	if ($_SESSION["plugin_datainjection"]["choice"] == 1)
		echo "<div class='wizard_title' valign='bottom'>" . $LANG["datainjection"]["step"][6] . $LANG["datainjection"]["saveStep"][1] . "</div>";
	else
		echo "<div class='wizard_title' valign='bottom'>" . $LANG["datainjection"]["step"][5] . $LANG["datainjection"]["saveStep"][1] . "</div>";
	/**************************************************************/

	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>" . $LANG["datainjection"]["saveStep"][13] . "</div>";
	echo "<div class='wizard_explain'>" . $LANG["datainjection"]["saveStep"][14] . "</div>";
	echo "<div class='wizard_explain'>" . $LANG["datainjection"]["saveStep"][15] . "</div>";
	/**************************************************************/

	echo "</td>";

	echo "<td class='wizard_right_area' style='width: 400px' valign='top'>";

	switch ($save) {
		case 0 :
			/***********************Asking View****************************/
			echo "<table class='saveStep_table'>";

			if ($_SESSION["plugin_datainjection"]["choice"] == 1)
				echo "<tr><td colspan='2'>" . $LANG["datainjection"]["saveStep"][2] . "</td><tr>";
			else
				echo "<tr><td colspan='2'>" . $LANG["datainjection"]["saveStep"][3] . "</td><tr>";

			echo "<tr><td style='text-align: center'><input type='submit' name='yes1_saveStep' value='" . $LANG["choice"][1] . "' class='submit' /></td>";
			echo "<td style='text-align: center'><input type='submit' name='no1_saveStep' value='" . $LANG["choice"][0] . "' class='submit' /></td></tr>";
			echo "</table>";
			/**************************************************************/
			break;
		case 1 :
			/************************Fill View*****************************/
			echo "<table class='saveStep2_table'>";
			echo "<tr><td>" . $LANG["datainjection"]["saveStep"][4] . "</td></tr>";

			if ($_SESSION["plugin_datainjection"]["choice"] == 1) {
				echo "<tr><td><input type='text' name='model_name' id='model_name' size='35' /></td></tr>";
				echo "<tr><td>" . $LANG["datainjection"]["saveStep"][5] . "</td></tr>";
				echo "<tr><td><textarea name='comments' rows='4' cols='25'></textarea></td></tr>";
			} else {
				$model = unserialize($_SESSION["plugin_datainjection"]["model"]);
				echo "<tr><td><input type='text' name='model_name' size='35' value='" . $model->getModelName() . "' /></td></tr>";
				echo "<tr><td>" . $LANG["datainjection"]["saveStep"][5] . "</td></tr>";
				echo "<tr><td><textarea name='comments' rows='4' cols='25'>" . $model->getModelComments() . "</textarea></td></tr>";
			}
			echo "</table>";
			/**************************************************************/
			break;
		case 2 :
			/**********************No Save View****************************/
			echo "<table class='saveStep_table'>";

			if ($_SESSION["plugin_datainjection"]["choice"] == 1)
				echo "<tr><td style='text-align: center' colspan='2'>" . $LANG["datainjection"]["saveStep"][6] . "</td><tr>";
			else
				echo "<tr><td style='text-align: center' colspan='2'>" . $LANG["datainjection"]["saveStep"][7] . "</td><tr>";

			echo "<tr><td style='text-align: center' colspan='2'>" . $LANG["datainjection"]["saveStep"][10] . "</td><tr>";

			echo "<tr><td style='text-align: center'><input type='submit' name='yes2_saveStep' value='" . $LANG["choice"][1] . "' class='submit' /></td>";
			echo "<td style='text-align: center'><input type='submit' name='no2_saveStep' value='" . $LANG["choice"][0] . "' class='submit' /></td></tr>";
			echo "</table>";
			/**************************************************************/
			break;
		case 3 :
			/************************Save View*****************************/
			echo "<table class='saveStep_table'>";

			if ($_SESSION["plugin_datainjection"]["choice"] == 1)
				echo "<tr><td style='text-align: center' colspan='2'>" . $LANG["datainjection"]["saveStep"][8] . "</td><tr>";
			else
				echo "<tr><td style='text-align: center' colspan='2'>" . $LANG["datainjection"]["saveStep"][9] . "</td><tr>";

			echo "<tr><td style='text-align: center' colspan='2'>" . $LANG["datainjection"]["saveStep"][10] . "</td><tr>";

			echo "<tr><td style='text-align: center'><input type='submit' name='yes2_saveStep' value='" . $LANG["choice"][1] . "' class='submit' /></td>";
			echo "<td style='text-align: center'><input type='submit' name='no2_saveStep' value='" . $LANG["choice"][0] . "' class='submit' /></td></tr>";
			echo "</table>";
			/**************************************************************/
			break;
	}

	echo "</td></tr>";

	/**************************Button******************************/
	echo "<tr><td class='wizard_button' colspan='2'>";

	switch ($save) {
		case 0 :
			echo "<div class='preview'>";
			echo "<input type='submit' name='preview_saveStep' value='" . $LANG["datainjection"]["button"][1] . "' class='submit' />";
			echo "</div>";
			break;
		case 1 :
			echo "<div class='next'>";
			echo "<input type='submit' name='next_saveStep' value='" . $LANG["datainjection"]["button"][6] . "' class='submit' />";
			echo "</div>";
			break;
	}

	echo "</td></tr>";
	/**************************************************************/

	echo "</table>";
	echo "</form>";

	echo "<script type='text/javascript'>document.getElementById('model_name').focus()</script>";
}

function fillInfoStep($target, $error) {
	global $LANG, $LANG, $DATA_INJECTION_INFOS;

	$model = unserialize($_SESSION["plugin_datainjection"]["model"]);

	if (($_SESSION["plugin_datainjection"]["load"] == "next_fileStep" || $_SESSION["plugin_datainjection"]["load"] == "preview2_fillInfoStep" || $_SESSION["plugin_datainjection"]["load"] == "yes2_saveStep") && count($model->getInfos()) > 0)
		$info = 1;
	else
		$info = 0;

	echo "<form action='" . $target . "' method='post'>";
	echo "<table class='wizard'>";

	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";

	/************************Title Step****************************/
	echo "<div class='wizard_title' valign='bottom'>" . $LANG["datainjection"]["step"][3] . $LANG["datainjection"]["info"][1] . "</div>";
	/**************************************************************/

	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>" . $LANG["datainjection"]["fillInfoStep"][2] . "</div>";
	/**************************************************************/

	echo "</td>";

	echo "<td class='wizard_right_area' style='width: 400px' valign='top'>";

	/************************Infos view****************************/
	if ($info) {
		$temp_label = -2;

		foreach ($model->getInfos() as $key => $value) {
			$label = $value->getInfosType();

			if ($label != $temp_label) {
				if ($temp_label != -2) {
					echo "</table>";
					echo "</fieldset>";
				}
				$temp_label = $label;
				$commonitem = new CommonItem;
				$commonitem->setType($value->getInfosType());
				echo "<fieldset class='fillInfoStep_fieldset'>";
				echo "<legend>" . $commonitem->getType() . "</legend>";
				echo "<table class='fillInfoStep_tab'>";
			}

			if (isset ($DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["type"]))
				$data = $DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["type"];
			else
				$data = "text";

			if (isset ($DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["input_type"]))
				$input = $DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["input_type"];
			else
				$input = "text";

			if (isset ($DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["input_type"]))
				$input = $DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["input_type"];

			switch ($input) {
				case "text" :
					switch ($data) {
						case "text" :
						case "integer" :
							echo "<tr><td colspan='3'><input type='hidden' name='field[$key][0]' value='" . $value->getID() . "' /></td></tr>";
							echo "<tr><td style='width: 200px'>" . $DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["name"] . " : </td><td style='width: 130px'>";
							autocompletionTextField("field[$key][1]", $DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["table"], $DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["field"], $value->getInfosText(), 20, $_SESSION["glpiactive_entity"]);
							if ($value->isMandatory())
								echo "</td><td class='fillInfoStep_mandatory'>*</td></tr>";
							else
								echo "</td><td style='width:10px'></td></tr>";
							break;
						case "date" :
							echo "<tr><td colspan='3'><input type='hidden' name='field[$key][0]' value='" . $value->getID() . "' /></td></tr>";
							echo "<tr><td style='width: 200px'>" . $DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["name"] . " : </td><td style='width: 130px'>";
							showDateFormItem("field[$key][1]", $value->getInfosText(),false);
							//showCalendarForm("form_ic", "field[$key][1]", $value->getInfosText());
							if ($value->isMandatory())
								echo "</td><td class='fillInfoStep_mandatory'>*</td></tr>";
							else
								echo "</td><td style='width:10px'></td></tr>";
							break;
					}
					break;
				case "yesno":
					echo "<tr><td colspan='3'><input type='hidden' name='field[$key][0]' value='" . $value->getID() . "' /></td></tr>";
					echo "<tr><td style='width: 200px'>" . $DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["name"] . " : </td><td style='width: 130px'>";
					dropdownYesNo("field[$key][1]");
					if ($value->isMandatory())
						echo "</td><td class='fillInfoStep_mandatory'>*</td></tr>";
					else
						echo "</td><td style='width:10px'></td></tr>";
					break;

            case "dropdown_users" :
               echo "<tr><td colspan='3'><input type='hidden' name='field[$key][0]' value='" . $value->getID() . "' /></td></tr>";
               echo "<tr><td style='width: 200px'>" . $DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["name"] . " : </td><td style='width: 130px'>";
               dropdownUsers("field[$key][1]","","all",0,1,$_SESSION["glpiactive_entity"]);
               if ($value->isMandatory())
                  echo "</td><td class='fillInfoStep_mandatory'>*</td></tr>";
               else
                  echo "</td><td style='width:10px'></td></tr>";
               break;
				case "dropdown" :
 					echo "<tr><td colspan='3'><input type='hidden' name='field[$key][0]' value='" . $value->getID() . "' /></td></tr>";
  					echo "<tr><td style='width: 200px'>" . $DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["name"] . " : </td><td style='width: 130px'>";

					switch ($DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["field"]) {
						case "alarm" :
							dropdownInteger("field[$key][1]", $value->getInfosText(), -1, 100);
							break;
						case "amort_time" :
							dropdownInteger("field[$key][1]", "", 0, 15);
							break;
						case "warranty_duration" :
							dropdownInteger("field[$key][1]", "", 0, 120);
							break;
						case "amort_type" :
							dropdownAmortType("field[$key][1]");
							break;
						case "contract" :
							dropdownContracts("field[$key][1]", $_SESSION["glpiactive_entity"]);
							break;
						case "tplname" :
							dropdownTemplate("field[$key][1]", $_SESSION["glpiactive_entity"], $DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["table"]);
							break;
						case "is_global" :
							dropdownSimpleManagement("field[$key][1]");
							break;
                  case "FK_entities":
                     dropdownValue($DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["table"], "field[$key][1]", $value->getInfosText(), 1, getEntitySons($_SESSION["glpiactive_entity"]));
                     break;
                  default :
							dropdownValue($DATA_INJECTION_INFOS[$value->getInfosType()][$value->getValue()]["table"], "field[$key][1]", $value->getInfosText(), 1, $_SESSION["glpiactive_entity"]);
							break;
					}
					if ($value->isMandatory())
						echo "</td><td class='fillInfoStep_mandatory'>*</td></tr>";
					else
						echo "</td><td style='width:10px'></td></tr>";
					break;
			}
		}
		echo "</table>";
		echo "</fieldset>";

		echo "<div class='fillInfoStep_red'>" . $LANG["datainjection"]["fillInfoStep"][3] . "</div>";

		/**********************Fill Info Error*************************/
		if (!empty ($error))
			echo "<div class='fillInfoStep_error'>" . $error . "</div>";
		/**************************************************************/
	}
	/**************************************************************/

	/***********************Question view**************************/
	else {
		echo "<table class='saveStep_table'>";
		echo "<tr><td style='text-align: center' colspan='2'>" . $LANG["datainjection"]["fillInfoStep"][1] . "</td><tr>";
		echo "<tr><td style='text-align: center'><input type='submit' name='yes_fillInfoStep' value='" . $LANG["choice"][1] . "' class='submit' /></td>";
		echo "<td style='text-align: center'><input type='submit' name='no_fillInfoStep' value='" . $LANG["choice"][0] . "' class='submit' /></td></tr>";
		echo "</table>";
	}
	/**************************************************************/

	echo "</td></tr>";

	/**************************Button******************************/
	echo "<tr><td class='wizard_button' colspan='2'>";

	if ($info) {
		echo "<div class='preview'>";
		echo "<input type='submit' name='preview1_fillInfoStep' value='" . $LANG["datainjection"]["button"][1] . "' class='submit' />";
		echo "</div>";

		echo "<div class='next'>";
		echo "<input type='submit' name='next_fillInfoStep' value='" . $LANG["datainjection"]["button"][2] . "' class='submit' />";
		echo "</div>";
	} else {
		if (count($model->getInfos()) > 0) {
			echo "<div class='preview'>";
			echo "<input type='submit' name='preview2_fillInfoStep' value='" . $LANG["datainjection"]["button"][1] . "' class='submit' />";
			echo "</div>";
		} else {
			echo "<div class='preview'>";
			echo "<input type='submit' name='preview1_fillInfoStep' value='" . $LANG["datainjection"]["button"][1] . "' class='submit' />";
			echo "</div>";
		}
	}

	echo "</td></tr>";
	/**************************************************************/

	echo "</table>";
	echo "</form>";
}

function importStep($target) {
	global $LANG, $LANG;

	echo "<form action='" . $target . "' method='post'>";
	echo "<table class='wizard'>";

	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";

	/************************Title Step****************************/
	echo "<div class='wizard_title' valign='bottom'>" . $LANG["datainjection"]["step"][4] . $LANG["datainjection"]["importStep"][1] . "</div>";
	/**************************************************************/

	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>" . $LANG["datainjection"]["importStep"][2] . "</div>";
	/**************************************************************/

	echo "</td>";

	echo "<td class='wizard_right_area' style='width: 400px' valign='top'>";

	echo "<div id='new_import'>";

	echo "<div class='importStep_cadre'>";
	createProgressBar($LANG["datainjection"]["importStep"][1]);
	echo "</div>";

	while ($_SESSION["plugin_datainjection"]["import"]["i"] < $_SESSION["plugin_datainjection"]["import"]["i_stop"]) {
		traitement();
		changeProgressBarPosition(
			$_SESSION["plugin_datainjection"]["import"]["i"],
			$_SESSION["plugin_datainjection"]["import"]["nbline"],
			$LANG["datainjection"]["importStep"][1]."... ".
			number_format($_SESSION["plugin_datainjection"]["import"]["i"]*100/$_SESSION["plugin_datainjection"]["import"]["nbline"],1).'%');

	}
	changeProgressBarMessage($LANG["datainjection"]["importStep"][3]);

	$model = unserialize($_SESSION["plugin_datainjection"]["model"]);
	logEvent(0, getLogItemType($model->getDeviceType()), 4, "plugin", $_SESSION["glpiname"] . " " . $LANG["datainjection"]["logevent"][1]);

	echo "</div>";

	echo "</td></tr>";

	/**************************Button******************************/
	echo "<tr><td class='wizard_button' colspan='2'>";
	echo "<div class='next'>";
	echo "<input type='submit' name='next_importStep' value='" . $LANG["datainjection"]["button"][2] . "' class='submit' />";
	echo "</div>";
	echo "</td></tr>";
	/**************************************************************/

	echo "</table>";
	echo "</form>";
}

function logStep($target) {
	global $LANG;

	$nbline = $_SESSION["plugin_datainjection"]["import"]["nbline"];
	$backend = unserialize($_SESSION["plugin_datainjection"]["backend"]);
	$tab_result = unserialize($_SESSION["plugin_datainjection"]["import"]["tab_result"]);

	$tab_result = PluginDatainjectionResult::sort($tab_result);

	echo "<form action='" . $target . "' method='post'>";
	echo "<table class='wizard'>";

	echo "<tr>";
	echo "<td class='wizard_left_area' valign='top'>";

	/************************Title Step****************************/
	echo "<div class='wizard_title' valign='bottom'>" . $LANG["datainjection"]["step"][5] . $LANG["datainjection"]["logStep"][1] . "</div>";
	/**************************************************************/

	/***********************Explain Step***************************/
	echo "<div class='wizard_explain'>" . $LANG["datainjection"]["logStep"][2] . "</div>";
	echo "<div class='wizard_explain'>" . $LANG["datainjection"]["logStep"][6] . "</div>";
	if (count($tab_result[0]) > 0)
		echo "<div class='wizard_explain'>" . $LANG["datainjection"]["logStep"][7] . "</div>";
	/**************************************************************/

	echo "</td>";

	echo "<td class='wizard_right_area' style='width: 400px' valign='top'>";

	if (count($tab_result[0]) > 0)
		echo "<div class='logStep_success' colspan='2' valign='top'>" . $LANG["datainjection"]["logStep"][8] . "</div>";
	else
		echo "<div class='logStep_success' colspan='2' valign='top'>" . $LANG["datainjection"]["logStep"][3] . "</div>";

	echo "<table class='logStep_tab'>";
	echo "<tr>";

	echo "<td style='width:200px'>";
	echo "<input type='button' name='popup' value='" . $LANG["datainjection"]["button"][4] . "' class='submit , logStep_button' onclick='log_popup($nbline)' />";
	echo "</td>";

	echo "<td style='width:200px'>";
	echo "<input type='button' name='pdf' value='" . $LANG["datainjection"]["button"][7] . "' class='submit , logStep_button' onclick=\"location.href='plugin_datainjection.export.pdf.php'\" />";
	echo "</td>";

	echo "</tr>";

	echo "<tr>";

	if (count($tab_result[0]) > 0) {
		$model = unserialize($_SESSION["plugin_datainjection"]["model"]);
		$file = PLUGIN_DATAINJECTION_UPLOAD_DIR . $_SESSION["plugin_datainjection"]["file_name"];

		$backend->export($file, $model, $tab_result);
		echo "<td colspan='2'>";
		echo "<input type='button' name='export' value='" . $LANG["datainjection"]["button"][5] . "' class='submit , logStep_button' onclick=\"location.href='plugin_datainjection.download.php'\" />";
		echo "</td>";
	}

	echo "</tr>";
	echo "</table>";

	echo "</td></tr>";

	/**************************Button******************************/
	echo "<tr><td class='wizard_button' colspan='2'>";
	echo "<div class='next'>";
	echo "<input type='submit' name='next_logStep' value='" . $LANG["datainjection"]["button"][6] . "' class='submit' />";
	echo "</div>";
	echo "</td></tr>";
	/**************************************************************/

	echo "</table>";
	echo "</form>";
}

function traitement() {
	$model = unserialize($_SESSION["plugin_datainjection"]["model"]);
	$tab_result = unserialize($_SESSION["plugin_datainjection"]["import"]["tab_result"]);
	$global_result = unserialize($_SESSION["plugin_datainjection"]["import"]["global_result"]);
	$engine = unserialize($_SESSION["plugin_datainjection"]["import"]["engine"]);
	$nbline = $_SESSION["plugin_datainjection"]["import"]["nbline"];
	$i = $_SESSION["plugin_datainjection"]["import"]["i"];
	$datas = $_SESSION["plugin_datainjection"]["import"]["datas"];

	$global_result = $engine->injectLine($datas[$i][0], $model->getInfos());
	$global_result->setLineId($i);
	$tab_result[] = $global_result;
	$i++;
	$datas = $engine->getDatas();

	$_SESSION["plugin_datainjection"]["import"]["tab_result"] = serialize($tab_result);
	$_SESSION["plugin_datainjection"]["import"]["global_result"] = serialize($global_result);
	$_SESSION["plugin_datainjection"]["import"]["i"] = $i;
	$_SESSION["plugin_datainjection"]["import"]["datas"] = $datas;

	if (isset($_SESSION["MESSAGE_AFTER_REDIRECT"]))
		$_SESSION["MESSAGE_AFTER_REDIRECT"] = '';
}

function initImport() {
	$model = unserialize($_SESSION["plugin_datainjection"]["model"]);
	$file = unserialize($_SESSION["plugin_datainjection"]["backend"]);

	$tab_result = array ();

	$global_result = new PluginDatainjectionResult;

	$engine = new PluginDatainjectionEngine($model, PLUGIN_DATAINJECTION_UPLOAD_DIR .
	$_SESSION["plugin_datainjection"]["file"], $file, $_SESSION["glpiactive_entity"]);

	if ($engine->getModel()->isHeaderPresent()) {
		$nbline = $engine->getNumberOfLines() - 1;
		$i = 1;
	} else {
		$nbline = $engine->getNumberOfLines();
		$i = 0;
	}

	$i_stop = $engine->getNumberOfLines();
	$progress = 0;

	$datas = $engine->getDatas();

	$_SESSION["plugin_datainjection"]["import"]["tab_result"] = serialize($tab_result);
	$_SESSION["plugin_datainjection"]["import"]["global_result"] = serialize($global_result);
	$_SESSION["plugin_datainjection"]["import"]["engine"] = serialize($engine);
	$_SESSION["plugin_datainjection"]["import"]["nbline"] = $nbline;
	$_SESSION["plugin_datainjection"]["import"]["i"] = $i;
	$_SESSION["plugin_datainjection"]["import"]["i_stop"] = $i_stop;
	$_SESSION["plugin_datainjection"]["import"]["progress"] = $progress;
	$_SESSION["plugin_datainjection"]["import"]["datas"] = $datas;
}

function initSession() {
	if (isset ($_SESSION["plugin_datainjection"]["nbonglet"]))
		unset ($_SESSION["plugin_datainjection"]["nbonglet"]);

	if (isset ($_SESSION["plugin_datainjection"]["step"]))
		unset ($_SESSION["plugin_datainjection"]["step"]);

	if (isset ($_SESSION["plugin_datainjection"]["choice"]))
		unset ($_SESSION["plugin_datainjection"]["choice"]);

	if (isset ($_SESSION["plugin_datainjection"]["idmodel"]))
		unset ($_SESSION["plugin_datainjection"]["idmodel"]);

	if (isset ($_SESSION["plugin_datainjection"]["model"]))
		unset ($_SESSION["plugin_datainjection"]["model"]);

	if (isset ($_SESSION["plugin_datainjection"]["file"]))
		unset ($_SESSION["plugin_datainjection"]["file"]);

	if (isset ($_SESSION["plugin_datainjection"]["file_name"])) {
		if (file_exists(PLUGIN_DATAINJECTION_UPLOAD_DIR . $_SESSION["plugin_datainjection"]["file_name"]))
			unlink(PLUGIN_DATAINJECTION_UPLOAD_DIR .
			$_SESSION["plugin_datainjection"]["file_name"]);
		unset ($_SESSION["plugin_datainjection"]["file_name"]);
	}

	if (isset ($_SESSION["plugin_datainjection"]["remember"]))
		unset ($_SESSION["plugin_datainjection"]["remember"]);

	if (isset ($_SESSION["plugin_datainjection"]["import"]))
		unset ($_SESSION["plugin_datainjection"]["import"]);

	if (isset ($_SESSION["plugin_datainjection"]["backend"]))
		unset ($_SESSION["plugin_datainjection"]["backend"]);

	$_SESSION["plugin_datainjection"]["step"] = 1;

	if (plugin_datainjection_haveRight("model", "w")) {
		$_SESSION["plugin_datainjection"]["choice"] = 1;
		$_SESSION["plugin_datainjection"]["nbonglet"] = 6;
	} else
		if (plugin_datainjection_haveRight("model", "r")) {
			$_SESSION["plugin_datainjection"]["choice"] = 4;
			$_SESSION["plugin_datainjection"]["nbonglet"] = 5;
		}
}
?>
