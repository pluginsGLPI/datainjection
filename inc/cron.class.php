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
 * @copyright Copyright (C) 2007-2022 by DataInjection plugin team.
 * @copyright Copyright (C) 2023 Mirko Morandini, Wuerth Phoenix.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link    https://github.com/pluginsGLPI/datainjection
 * -------------------------------------------------------------------------
 */
 
 /*
  * To add to DB (in hook):
    ALTER TABLE glpi_plugin_datainjection_models ADD csvfilename VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '';
    ALTER TABLE glpi_plugin_datainjection_models ADD enable_scheduled_injection  tinyint(1) NOT NULL DEFAULT 0;
  */

if (! defined ( 'GLPI_ROOT' )) {
	die ( "Sorry. You can't access directly to this file" );
}

class PluginDatainjectionCron extends CronTask {
	
	const GLPI_FILEINJECT_DIR  = GLPI_VAR_DIR."/_fileinject"; // Path for storage of files to inject  

	static public function rawSearchOptionsToAdd($model) {
		$tab [] = [ 
				'id' => '90',
				'table' => $model->getTable(),
				'field' => 'csvfilename',
				'name'  => 'CSV Filename',
				'datatype' => 'text'
		];
		$tab [] = [ 
				'id' => '91',
				'table' => $model->getTable(),
				'field' => 'enable_scheduled_injection',
				'name'  => 'Enable scheduled injection',
				'datatype' => 'bool'
		];
		return $tab;
	}

	function showAdditionnalForm(PluginDatainjectionModel $model) {

	  // $id      = $this->getFromDBByModelID($model->fields['id']);
	  // $canedit = $this->can($id, UPDATE);

	  echo "<tr><th colspan='4'>".__('Scheduled import (Automatic Action) options', 'datainjection')."</th></tr>";

	  echo "<tr class='tab_bg_1'>";
	  echo "<td>".__('Enable scheduled injection', 'datainjection')."</td>";
	  echo "<td>";
	  Dropdown::showYesNo("enable_scheduled_injection", $model->getEnableScheduledInjection());
	  echo "<td>"."CSV Filename (in ".self::GLPI_FILEINJECT_DIR.")"."</td>";
	  echo "<td>";
	  echo "<input type='text' size='50' name='csvfilename' value='".$model->getCSVFilename()."'";
	  echo "</td>";
	  echo "</td></tr>";
	}

   
	/**
	 * Give AutomaticAction cron information
	 *
	 * @param $name :
	 *      	automatic action's name
	 *      	
	 * @return array
	 */
    static function cronInfo($name) {
		switch ($name) {
		   case 'cronDataInjection' :
			return ['description' => __('Scheduled Data Injection', 'datainjection')];
		   #case 'cronDataInjectionAdditional' :
			 #	return ['description' => __('Scheduled Data Injection Additional', 'datainjection')];
		}
		return [ ];
	}
    /**
     * The Cron Job
     * @param CronTask $item
     * @return number
     */
    // public static function cronDataInjectionAdditional(CronTask $item) {
    //   return self::cronDataInjection($item);
    // }

	// New job: searches in all models for an enabled scheduling and the filename saved there.
	// Models need to be public or by root user!
	static function cronDataInjection ($task = NULL) {
		global $DB;
		
		// To prevent problem of execution time during injection
		ini_set("max_execution_time", "0");
		
		#$models = PluginDatainjectionModel::getModels(1, "name", 1, false); // 1 = user root
		$query = "SELECT `id`, `name`, `is_private`, `entities_id`, `is_recursive`, `itemtype`,`step`, `comment`
                FROM `glpi_plugin_datainjection_models` WHERE `step` = '".PluginDatainjectionModel::READY_TO_USE_STEP."' AND (";
		$query .= "(`is_private` = '" . PluginDatainjectionModel::MODEL_PUBLIC."') OR (`users_id` = 1)) ORDER BY `name` DESC";
		$models = $DB->request($query);
		
		if (count($models) == 0) {
			$task->log("No valid public or root models found!");
			error_log('[DEBUG] no valid models found.');
			return 0;
		}
		
		$returndata = 0; //0: nothing_to_do, >0: success, <0: partial
		// a loop on all models to get the params and execute each one in sequence:
		foreach ($models as $singlemodel){
			$model = new PluginDatainjectionModel();
			$filename = '';
			##$model->can($modelid, READ);
			$model->getFromDB($singlemodel['id']);
			if (!$model->getEnableScheduledInjection() || empty($model->getCSVFilename())){
				# $task->log("Not to schedule....!");
				continue;
			} 
			$filename  = self::GLPI_FILEINJECT_DIR ."/". $model->getCSVFilename();
			$modelname = $singlemodel['name'];		
		    
			$task->log("Executing scheduled model $modelname on file $filename.");
			if (!file_exists($filename)) {
			   $task->log("[WARNING] File $filename, defined in $modelname, does not exist. Disable scheduled injection!");
			   error_log ("[WARNING] File $filename, defined in $modelname, does not exist. Disable scheduled injection!");
			   #$returndata = -1;
			   continue;
			}

			//Read file using automatic encoding detection, and do not delete file once read
			$options = array('file_encoding'   => PluginDatainjectionBackend::ENCODING_AUTO,
						 'mode'        => PluginDatainjectionModel::PROCESS,
						 'unique_filename' => $filename,
						 'original_filename' => $filename,
						 'delete_file'     => false);
			$response = $model->processUploadedFile($options);

			if (! $response) {
				$task->log ("[ERROR] import errors for file $filename in model $modelname.");
				error_log  ("[ERROR] import errors for file $filename in model $modelname.");
				$returndata = -1;
				continue;
			}
			
			$additional_infos = [];
			$engine  = new PluginDatainjectionEngine(
				  $model,
				  $additional_infos,
				  $singlemodel['entities_id']
			);  
			//Remove first line if header is present
			$first = true;
			$nb = 0;
			foreach ($model->injectionData->getData() as $id => $data) {
				if ($first && $model->getSpecificModel()->isHeaderPresent()) {
				   $first = false;
				} else {
				   $results[] = $engine->injectLine($data[0], $id);
				   $nb++;
				}
			}
			$model->cleanData();
			
			if (! is_null ( $task )) {
				// If the argument $task is a CronTask object, the method must increment the quantity of actions done.
				$task->addVolume ( 1 );
				$task->log ( "Import/update of $nb rows for model $modelname successful." );
				error_log  ( "Import/update of $nb rows for model $modelname successful." );
				if ($returndata == 0) 
					$returndata = 1;
			}
		}
		
		return $returndata;  
	
	}

}

// END