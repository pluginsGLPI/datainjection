<?php
/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2005 by the INDEPNET Development Team.

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

// Original Author of file: Walid Nouh (walid.nouh@atosorigin.com)
// Purpose of file:
// ----------------------------------------------------------------------

class DataInjectionEngine
{
	//Model informations
	var $model;
	
	//Backend to read file to import
	var $backend;
	
	function DataInjectionEngine($model_id,$filename)
	{
		//Instanciate model
		$model = new DataInjectionModel($model_id);
		
		//Load model and mappings informations
		$model->loadAll();
		
		$datas = new InjectionDatas;
		
		//TODO : do someting generic !!
		$this->backend = new BackendCSV($filename,$model->getDelimiter());
		$this->backend->read();
	}
	
	/*
	 * Check all the datas and inject them
	 */
	function injectDatas()
	{
		$tab_result = array();
		
		foreach ($this->model->getDatas() as $line)
		{
			$check_result = checkLine($this->model,$line);
			if ($check_result["result"])
				injectLine($line);
			else
				$tab_result[] = $check_result;	
		}
	}
	
	/*
	 * Inject one line of datas
	 * @param line one line of data to import
	 */
	function injectLine($line)
	{
		
	}
	

}
?>