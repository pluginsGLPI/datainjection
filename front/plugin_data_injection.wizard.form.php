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

// Original Author of file: Walid Nouh (walid.nouh@atosorigin.com)
// Purpose of file:
// ----------------------------------------------------------------------
ini_set("max_execution_time", "0");

if (!defined('GLPI_ROOT')) {
	define('GLPI_ROOT', '../../..');
}
//Mysql Replication configuration : only work with 0.71
//Plugin must never use replicate!
$USEDBREPLICATE=0;
$DBCONNECTION_REQUIRED=1;

$NEEDED_ITEMS=array("ocsng","user","contact","enterprise","contract","networking","group","monitor","phone","infocom","printer","profile","entity",
	"computer","software","setup","peripheral","cartridge","consumable","rulesengine","rule.dictionnary.dropdown","rule.softwarecategories",
	"rule.dictionnary.software","device");
include (GLPI_ROOT."/inc/includes.php");

commonHeader($DATAINJECTIONLANG["config"][1],$_SERVER["PHP_SELF"],"plugins","data_injection");

$load=1;
$error="";
$save=0;
$suppr=0;

/********************(re)Load or Not***************************/
foreach($_POST as $key => $val)
	if($_SESSION["plugin_data_injection"]["load"]==$key)
		$load=0;
/**************************************************************/


if($load)
	{		
	/***********************Global Step****************************/
	if(isset($_POST["next_choiceStep"]))
		{
		switch($_POST["choice"])
			{
			case 1:
				$_SESSION["plugin_data_injection"]["nbonglet"] = 6;
				$_SESSION["plugin_data_injection"]["choice"] = 1;
			break;
			case 2:
				$model = getModelInstanceByID($_POST["dropdown"]);
				$model->loadAll($_POST["dropdown"]);
				
				$_SESSION["plugin_data_injection"]["nbonglet"] = 5;
				$_SESSION["plugin_data_injection"]["choice"] = 2;
				$_SESSION["plugin_data_injection"]["model"] = serialize($model);
			break;
			case 3:
				$_SESSION["plugin_data_injection"]["nbonglet"] = 2;
				$_SESSION["plugin_data_injection"]["choice"] = 3;
				$_SESSION["plugin_data_injection"]["idmodel"] = $_POST["dropdown"];
			break;
			case 4:
				$model = getModelInstanceByID($_POST["dropdown"]);
				$model->loadAll($_POST["dropdown"]);
				
				$_SESSION["plugin_data_injection"]["nbonglet"] = 5;
				$_SESSION["plugin_data_injection"]["choice"] = 4;
				$_SESSION["plugin_data_injection"]["model"] = serialize($model);
			break;
			}
		$_SESSION["plugin_data_injection"]["step"]++;
		$_SESSION["plugin_data_injection"]["load"] = "next_choiceStep";
		}
	/**************************************************************/
	
	
	/************************Model Step****************************/
	else if(isset($_POST["preview_modelStep"]))
		{
		$_SESSION["plugin_data_injection"]["step"]--;
		$_SESSION["plugin_data_injection"]["load"] = "preview_modelStep";
		}
		
	else if(isset($_POST["next_modelStep"]))
		{	
		if(isset($_SESSION["plugin_data_injection"]["model"]))
			{
			$model = unserialize($_SESSION["plugin_data_injection"]["model"]);
			
			if($_SESSION["plugin_data_injection"]["choice"] == 1)
				{
				if($model->getModelType()!=$_POST["dropdown_type"])
					$model = getModelInstanceByType($_POST["dropdown_type"]);
				}
			}
		else		
			$model = getModelInstanceByType($_POST["dropdown_type"]);
			
		$model->setFields($_POST,$_SESSION["glpiactive_entity"],$_SESSION["glpiID"]);
				
		$_SESSION["plugin_data_injection"]["model"] = serialize($model);
		
		$_SESSION["plugin_data_injection"]["step"]++;
		$_SESSION["plugin_data_injection"]["load"] = "next_modelStep";
		}
	/**************************************************************/	
	
	
	/************************Delete Step***************************/
	else if(isset($_POST["yes_deleteStep"]))
		$suppr=1;
	
	else if(isset($_POST["no_deleteStep"]))
		{
		$_SESSION["plugin_data_injection"]["step"]--;
		$_SESSION["plugin_data_injection"]["load"] = "no_deleteStep";
		}
		
	else if(isset($_POST["next_deleteStep"]))
		{
		$_SESSION["plugin_data_injection"]["step"]--;
		$_SESSION["plugin_data_injection"]["load"] = "next_deleteStep";
		}
	/**************************************************************/
	
	
	/**************************File Step***************************/
	else if(isset($_POST["preview_fileStep"]))
		{
		$_SESSION["plugin_data_injection"]["step"]--;
		$_SESSION["plugin_data_injection"]["load"] = "preview_fileStep";
		}
		
	else if(isset($_POST["next_fileStep"]))
		{
	    $tmp_file = $_FILES["file"]["tmp_name"];
	
	    if( !is_uploaded_file($tmp_file) )
	        $error = $DATAINJECTIONLANG["fileStep"][4];
	    else
	    	{
			$model = unserialize($_SESSION["plugin_data_injection"]["model"]);
			$type = new BackendType();
			$type->getFromDB($model->getModelType());
			$extension = $type->getBackendName();
			
			$name_file = $_FILES["file"]["name"];
			
			$tmpfname = tempnam (realpath(PLUGIN_DATA_INJECTION_UPLOAD_DIR), "Tmp");
			
	    	if( !strstr(substr($name_file,strlen($name_file)-4), strtolower($extension)) )
	        	$error = $DATAINJECTIONLANG["fileStep"][5]."<br />".$DATAINJECTIONLANG["fileStep"][6]." ".$extension." ".$DATAINJECTIONLANG["fileStep"][7];
	    	else
	    		{
	    		if( !move_uploaded_file($tmp_file, $tmpfname) )
	        		$error = $DATAINJECTIONLANG["fileStep"][8]." ".$directory;
	    		else
	    			{
	    			$_SESSION["plugin_data_injection"]["file"] = basename($tmpfname);
	    			
	    			$file=getBackend($model->getModelType());
					$file->initBackend(PLUGIN_DATA_INJECTION_UPLOAD_DIR.$_SESSION["plugin_data_injection"]["file"],$model->getDelimiter(),$_POST["dropdown_encoding"]);
					$file->read();
					$file->deleteFile();
					
					if(($_SESSION["plugin_data_injection"]["choice"]!=4))
						$ok = 0;
					else
					 	$ok = $file->isFileCorrect($model);
					 
					if (!$ok)
						{
						$_SESSION["plugin_data_injection"]["step"]++;
		    			$_SESSION["plugin_data_injection"]["load"] = "next_fileStep";
						$_SESSION["plugin_data_injection"]["backend"] = serialize($file);
	    				$_SESSION["plugin_data_injection"]["file_name"] = $name_file;
	    				$_SESSION["plugin_data_injection"]["encoding"] = $_POST["dropdown_encoding"];
	    				
	    				if($_SESSION["plugin_data_injection"]["choice"]==1)
	    					$_SESSION["plugin_data_injection"]["remember"] = 0;
						}
					else
						{
						if ($ok==1)
							$error=$DATAINJECTIONLANG["saveStep"][11];
						else
							$error=$DATAINJECTIONLANG["saveStep"][12];	
						
						$error .= "<br><br><center>" . $file->getError(true) . "<center>";
						unset($_SESSION["plugin_data_injection"]["file"]);
						}
	    			}
	    		}
	    	}
		}
	/**************************************************************/
		
		
	/*************************Mapping Step*************************/	
	else if(isset($_POST["preview_mappingStep"]))
		{
		$_SESSION["plugin_data_injection"]["step"]--;
		$_SESSION["plugin_data_injection"]["load"] = "preview_mappingStep";
		}
		
	else if(isset($_POST["next_mappingStep"]))
		{
		$model = unserialize($_SESSION["plugin_data_injection"]["model"]);
			
		$index= 0;
		$mappingcollection = new MappingCollection;
			
		foreach($_POST["field"] as $field)
			{
			$mapping = new DataInjectionMapping;
			$mapping->setName(stripslashes($field[0]));
			$mapping->setMappingType($field[1]);
			$mapping->setValue($field[2]);
			$mapping->setRank($index);
			if(isset($field[3]))
				$mapping->setMandatory(1);
			else
				$mapping->setMandatory(0);
				
			$mappingcollection->addNewMapping($mapping);
			$index++;
			}
				
			$model->setMappings($mappingcollection);
				
		$_SESSION["plugin_data_injection"]["model"] = serialize($model);
		
		$_SESSION["plugin_data_injection"]["step"]++;
		$_SESSION["plugin_data_injection"]["load"] = "next_mappingStep";
		
		if($_SESSION["plugin_data_injection"]["choice"]==1)
	    				$_SESSION["plugin_data_injection"]["remember"] = 1;
		}	
	/**************************************************************/
	
	
	/***************************Info Step**************************/
	else if(isset($_POST["preview_infoStep"]))
		{
		$_SESSION["plugin_data_injection"]["step"]--;	
		$_SESSION["plugin_data_injection"]["load"] = "preview_infoStep";
		}
		
	else if(isset($_POST["next_infoStep"]))
		{
		$model = unserialize($_SESSION["plugin_data_injection"]["model"]);
		
		$infoscollection = new InfosCollection;
		
		foreach($_POST["field"] as $field)
			if($field[0]!=-1)
				{
				$infos = new DataInjectionInfos;
				$infos->setInfosType($field[0]);
				$infos->setValue($field[1]);
				if(isset($field[2]))
					$infos->setMandatory(1);
				else
					$infos->setMandatory(0);
			
				$infoscollection->addNewInfos($infos);
				}
			
		$model->setInfos($infoscollection);
			
		$_SESSION["plugin_data_injection"]["model"] = serialize($model);
		
		$_SESSION["plugin_data_injection"]["step"]++;
		$_SESSION["plugin_data_injection"]["load"] = "next_infoStep";
		
		if($_SESSION["plugin_data_injection"]["choice"]==1)
	    				$_SESSION["plugin_data_injection"]["remember"] = 2;
		}
	/**************************************************************/
	
	
	/***************************Save Step**************************/
	else if(isset($_POST["preview_saveStep"]))
		{
		$_SESSION["plugin_data_injection"]["step"]--;
		$_SESSION["plugin_data_injection"]["load"] = "preview_saveStep";
		}
		
	else if(isset($_POST["next_saveStep"]))
		{
		$model = unserialize($_SESSION["plugin_data_injection"]["model"]);
		
		if(isset($_POST["model_name"]))
			$model->setName($_POST["model_name"]);
		if(isset($_POST["comments"]))
			$model->setComments($_POST["comments"]);
		
		if($_SESSION["plugin_data_injection"]["choice"]==1)
			$model->saveModel();
		else
			$model->updateModel();
			
		$_SESSION["plugin_data_injection"]["model"] = serialize($model);
		
		$save=3;		
		}
	
	else if(isset($_POST["yes1_saveStep"]))
		$save=1;
		
	else if(isset($_POST["no1_saveStep"]))
		$save=2;		
	
	else if(isset($_POST["yes2_saveStep"]))
		{	
		if($_SESSION["plugin_data_injection"]["choice"]==1)
			{
			$_SESSION["plugin_data_injection"]["step"] = 3;
			$_SESSION["plugin_data_injection"]["load"] = "yes2_saveStep";
			}
		else
			$_SESSION["plugin_data_injection"]["step"] = 2;
		$_SESSION["plugin_data_injection"]["choice"] = 4;
		$_SESSION["plugin_data_injection"]["nbonglet"] = 5;
		}
		
	else if(isset($_POST["no2_saveStep"]))
		$_SESSION["plugin_data_injection"]["step"] = 1;
	/**************************************************************/
	
	
	/***********************Fill Infos Step************************/
	else if(isset($_POST["preview1_fillInfoStep"]))
		{
		$_SESSION["plugin_data_injection"]["step"]--;
		$_SESSION["plugin_data_injection"]["load"] = "preview1_fillInfoStep";
		}
		
	else if(isset($_POST["preview2_fillInfoStep"]))
		$_SESSION["plugin_data_injection"]["load"] = "preview2_fillInfoStep";
	
	else if(isset($_POST["next_fillInfoStep"]))
		{
		$model = unserialize($_SESSION["plugin_data_injection"]["model"]);
		
		foreach($_POST["field"] as $field)
			foreach($model->getInfos() as $info)
				if($info->getID() == $field[0])
					{
					$info->setInfosText($field[1]);
					
					if($info->isMandatory() && (empty($field[1]) || empty($field[1])))
						{
						$error = $DATAINJECTIONLANG["fillInfoStep"][4];
						$_SESSION["plugin_data_injection"]["load"] = "next_fileStep";
						}
					}
		
		$_SESSION["plugin_data_injection"]["model"] = serialize($model);
		
		if(empty($error))
			$_SESSION["plugin_data_injection"]["load"] = "next_fillInfoStep";
		}
		
	else if(isset($_POST["yes_fillInfoStep"]))
		{
		$_SESSION["plugin_data_injection"]["step"]++;
		$_SESSION["plugin_data_injection"]["load"] = "yes_fillInfoStep";
		initImport();
		}
		
	else if(isset($_POST["no_fillInfoStep"]))
		$_SESSION["plugin_data_injection"]["step"] = 1;
	/**************************************************************/
	
	
	/**************************Import Step*************************/
	else if(isset($_POST["next_importStep"]))
		{
		$_SESSION["plugin_data_injection"]["step"]++;
		$_SESSION["plugin_data_injection"]["load"] = "next_importStep";
		}
	/**************************************************************/
	
	
	/****************************Log Step**************************/
	else if(isset($_POST["next_logStep"]))
		$_SESSION["plugin_data_injection"]["step"] = 1;
	/**************************************************************/
	}


/********************Initialization****************************/
if($_SESSION["plugin_data_injection"]["step"]==1)
	initSession();
/**************************************************************/


echo "<table class='global'><tr><td>";

/**********************Show onglet*****************************/
echo "<table class='step'><tr>";
for($i=1;$i<=6;$i++)
	echo "<td id='step".$i."'>".$i."</td>";
echo "</tr></table>";
/**************************************************************/


/**********************Hide onglet*****************************/
echo "<script type='text/javascript'>deleteOnglet(".$_SESSION["plugin_data_injection"]["nbonglet"].")</script>";
/**************************************************************/


/*********************Select onglet****************************/
echo "<script type='text/javascript'>change_color_step('step".$_SESSION["plugin_data_injection"]["step"]."')</script>";
/**************************************************************/


/*********************Select form******************************/
if($_SESSION["plugin_data_injection"]["step"]==1)
	choiceStep($_SERVER["PHP_SELF"]);
else
	switch($_SESSION["plugin_data_injection"]["choice"])
		{
		case 1:
			switch($_SESSION["plugin_data_injection"]["step"])
				{
				case 2:
					modelStep($_SERVER["PHP_SELF"]);
				break;
				case 3:
					fileStep($_SERVER["PHP_SELF"],$error);
				break;
				case 4:
					mappingStep($_SERVER["PHP_SELF"]);
				break;
				case 5:
					infoStep($_SERVER["PHP_SELF"]);
				break;
				case 6:
					saveStep($_SERVER["PHP_SELF"],$save);
				break;
				}
		break;
		case 2:
			switch($_SESSION["plugin_data_injection"]["step"])
				{
				case 2:
					modelStep($_SERVER["PHP_SELF"]);
				break;
				case 3:
					mappingStep($_SERVER["PHP_SELF"]);
				break;
				case 4:
					infoStep($_SERVER["PHP_SELF"]);
				break;
				case 5:
					saveStep($_SERVER["PHP_SELF"],$save);
				break;
				}
		break;
		case 3:
			switch($_SESSION["plugin_data_injection"]["step"])
				{
				case 2:
					deleteStep($_SERVER["PHP_SELF"],$suppr);
				break;
				}
		break;
		case 4:
			switch($_SESSION["plugin_data_injection"]["step"])
				{
				case 2:
					fileStep($_SERVER["PHP_SELF"],$error);
				break;
				case 3:
					fillInfoStep($_SERVER["PHP_SELF"],$error);
				break;
				case 4:
					importStep($_SERVER["PHP_SELF"]);
				break;
				case 5:
					logStep($_SERVER["PHP_SELF"]);
				break;
				}
		break;
		}
/**************************************************************/

echo "</td></tr></table>";

commonFooter();
?>
