<?php
/*
 * @version $Id$
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

class PluginDatainjectionInfosCollection {

	var $infosCollection;

	function __construct()
	{
		$this->infosCollection = array();
	}

	//---- Getter ----//

	/*
	 * Load all the mappings for a specified model
	 * @param model_id the model ID
	 */
	function getAllInfosByModelID($model_id)
	{
		global $DB;

		$sql = "SELECT * FROM glpi_plugin_datainjection_infos WHERE model_id=".$model_id." ORDER BY type ASC";
		$result = $DB->query($sql);
		while  ($data = $DB->fetch_array($result))
		{
			$infos = new PluginDatainjectionInfos;
			$infos->fields = $data;
			$this->infosCollection[] = $infos;
		}
	}

	/*
	 * Return all the mappings for this model
	 * @return the list of all the mappings for this model
	 */
	function getAllInfos()
	{
		return $this->infosCollection;
	}

	//---- Save ----//

	/*
	 * Save in database the model and all his associated mappings
	 */
	function saveAllInfos($model_id)
	{
		foreach ($this->infosCollection as $infos)
		{
			$infos->setModelID($model_id);

			if (isset($infos->fields["ID"]))
				$infos->update($infos->fields);
			else
				$infos->fields["ID"] = $infos->add($infos->fields);
		}
	}

	//---- Delete ----//

	function deleteInfosFromDB($model_id)
	{
		global $DB;

		$sql = "DELETE from glpi_plugin_datainjection_infos WHERE model_id =".$model_id;
		if ($result = $DB->query($sql))
			return true;
		else
			return false;
	}

	//---- Add ----//

	/*
	 * Add a new mapping to this model (don't write in to DB)
	 * @param mapping the new PluginDatainjectionMapping to add
	 */
	function addNewInfos($infos)
	{
		$this->infosCollection[] = $infos;
	}

	/*
	 * Replace all the infos for a model
	 * @mappins the array of PluginDatainjectionInfos objects
	 */
	function replaceInfos($infos)
	{
		$this->infosCollection = $infos;
	}
}
?>
