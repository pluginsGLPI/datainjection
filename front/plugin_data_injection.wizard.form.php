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
if (!defined('GLPI_ROOT')) {
	define('GLPI_ROOT', '../../..');
}

$NEEDED_ITEMS=array("data_injection");
include (GLPI_ROOT."/inc/includes.php");

commonHeader($DATAINJECTIONLANG["config"][1], $_SERVER["PHP_SELF"],"plugins","data_injection");

$error="";
$suppr=0;
$save=0;

if(isset($_POST["next1"]))
	{
	switch($_POST["modele"])
		{
		case 1:
			$_SESSION["plugin_data_injection_wizard_step"] = 2;
			$_SESSION["plugin_data_injection_wizard_nbonglet"] = 6;
		break;
		case 2:
			$_SESSION["plugin_data_injection_wizard_step"] = 3;
			$_SESSION["plugin_data_injection_wizard_idmodel"] = $_POST["dropdown"];
			$_SESSION["plugin_data_injection_wizard_nbonglet"] = 4;
		break;
		case 3:
			$_SESSION["plugin_data_injection_wizard_step"] = 4;
			$_SESSION["plugin_data_injection_wizard_idmodel"] = $_POST["dropdown"];
			$_SESSION["plugin_data_injection_wizard_nbonglet"] = 2;
		break;
		case 4:
			$_SESSION["plugin_data_injection_wizard_step"] = 5;
			$_SESSION["plugin_data_injection_wizard_idmodel"] = $_POST["dropdown"];
			$_SESSION["plugin_data_injection_wizard_nbonglet"] = 3;
			$_SESSION["plugin_data_injection_verif_file"]=0;
		break;
		}
	}
	
else if(isset($_POST["preview2"]))
	$_SESSION["plugin_data_injection_wizard_step"] = 1;
	
else if(isset($_POST["next2"]))
	{
	if($_POST["delimiteur"]!='')
		{
		$_SESSION["plugin_data_injection_wizard_step"] = 6;
		$_SESSION["plugin_data_injection_verif_file"]=1;
		
		$model = new DataInjectionModel();
		$model->setEntity($_SESSION["glpiactive_entity"]);		
		
		if(isset($_POST["dropdown_device_type"]))
			$model->setDeviceType($_POST["dropdown_device_type"]);
		if(isset($_POST["dropdown_type"]))
			$model->setModelType($_POST["dropdown_type"]);
		if(isset($_POST["delimiteur"]))
			$model->setDelimiter($_POST["delimiteur"]);
		if(isset($_POST["dropdown_header"]))
			$model->setHeaderPresent($_POST["dropdown_header"]);
		if(isset($_POST["dropdown_create"]))
			$model->setBehaviorAdd($_POST["dropdown_create"]);
		if(isset($_POST["dropdown_update"]))
			$model->setBehaviorUpdate($_POST["dropdown_update"]);
			
		$_SESSION["plugin_data_injection_model"] = serialize($model);
		}
	else
		{
		$_SESSION["plugin_data_injection_wizard_step"] = 2;
		$error = $DATAINJECTIONLANG["step2"][8];
		}
	}
	
else if(isset($_POST["preview3"]))
	$_SESSION["plugin_data_injection_wizard_step"] = 1;
	
else if(isset($_POST["next3"]))
	$_SESSION["plugin_data_injection_wizard_step"] = 7;
	
else if(isset($_POST["preview4"]))
	$_SESSION["plugin_data_injection_wizard_step"] = 1;
	
else if(isset($_POST["next4_1"]))
	{
	$_SESSION["plugin_data_injection_wizard_step"] = 4;
	$suppr=1;
	}
else if(isset($_POST["next4_2"]))
	$_SESSION["plugin_data_injection_wizard_step"] = 1;

else if(isset($_POST["preview5"]))
	{
	if(!$_SESSION["plugin_data_injection_verif_file"])
		$_SESSION["plugin_data_injection_wizard_step"] = 1;
	else
		$_SESSION["plugin_data_injection_wizard_step"] = 2;
	}
	
else if(isset($_POST["next5"]))
	{
	$directory = PLUGIN_DATA_INJECTION_UPLOAD_DIR;

    $tmp_file = $_FILES["modelfile"]["tmp_name"];

    if( !is_uploaded_file($tmp_file) )
    	{
        $error = $DATAINJECTIONLANG["step5"][3];
        if(!$_SESSION["plugin_data_injection_verif_file"])
        	$_SESSION["plugin_data_injection_wizard_step"] = 5;
        else
        	$_SESSION["plugin_data_injection_wizard_step"] = 6;
    	}
    else
    	{
		if($_SESSION["plugin_data_injection_verif_file"])
			{
			$model = unserialize($_SESSION["plugin_data_injection_model"]);
			$type = new BackendType();
			$type->getFromDB($model->getModelType());
			$extension = $type->getBackendName();
			}
		else
			{
			$model = new DataInjectionModel();
			$model->getFromDB($_SESSION["plugin_data_injection_wizard_idmodel"]);
			$extension = getDropdownName('glpi_plugin_data_injection_filetype',$model->getModelType());
			}
		
		$name_file = $_FILES["modelfile"]["name"];
		
    	if( !strstr(substr($name_file,strlen($name_file)-4), strtolower($extension)) )
    		{
        	$error = $DATAINJECTIONLANG["step5"][4]."<br />".$DATAINJECTIONLANG["step5"][5]." ".$extension." ".$DATAINJECTIONLANG["step5"][6];
        	if(!$_SESSION["plugin_data_injection_verif_file"])
        		$_SESSION["plugin_data_injection_wizard_step"] = 5;
        	else
        		$_SESSION["plugin_data_injection_wizard_step"] = 6;
    		}
    	else
    		{
    		if( !move_uploaded_file($tmp_file, $directory . $name_file) )
    			{
        		$error = $DATAINJECTIONLANG["step5"][7]." ".$directory;
        		if(!$_SESSION["plugin_data_injection_verif_file"])
        			$_SESSION["plugin_data_injection_wizard_step"] = 5;
        		else
        			$_SESSION["plugin_data_injection_wizard_step"] = 6;
    			}
    		else
    			{
    			if(!$_SESSION["plugin_data_injection_verif_file"])
    				$_SESSION["plugin_data_injection_wizard_step"] = 8;
    			else
    				$_SESSION["plugin_data_injection_wizard_step"] = 9;
    			
    			$_SESSION["plugin_data_injection_file_name"] = $name_file;
    			}
    		}
    	}
	}

else if(isset($_POST["preview9"]))
	$_SESSION["plugin_data_injection_wizard_step"] = 6;
	
else if(isset($_POST["next9"]))
	{
	$model = unserialize($_SESSION["plugin_data_injection_model"]);
		
	$index= 0;
	$mappingcollection = new MappingCollection;
		
	foreach($_POST["field"] as $field)
		{
		$mapping = new DataInjectionMapping;
		$mapping->setName($field[0]);
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
			
	$_SESSION["plugin_data_injection_model"] = serialize($model);
	$_SESSION["plugin_data_injection_wizard_step"] = 12;
	}

else if(isset($_POST["preview12"]))
	$_SESSION["plugin_data_injection_wizard_step"] = 9;
	
else if(isset($_POST["next12"]))
	{
	$model = unserialize($_SESSION["plugin_data_injection_model"]);
	
	$infoscollection = new InfosCollection;
	
	foreach($_POST["field"] as $field)
		{
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
		}
		
	$model->setInfos($infoscollection);
		
	$_SESSION["plugin_data_injection_model"] = serialize($model);
	$_SESSION["plugin_data_injection_wizard_step"] = 15;
	}

else if(isset($_POST["preview15"]))
	$_SESSION["plugin_data_injection_wizard_step"] = 12;
else if(isset($_POST["next15"]))
	$_SESSION["plugin_data_injection_wizard_step"] = 18;
else if(isset($_POST["next15_1"]))
	{
	$_SESSION["plugin_data_injection_wizard_step"] = 15;
	$save=1;
	}
else if(isset($_POST["next15_2"]))
	{
	$_SESSION["plugin_data_injection_wizard_step"] = 15;
	$save=2;		
	}
else if(isset($_POST["next15_3"]))
	{
	$model = unserialize($_SESSION["plugin_data_injection_model"]);
	
	if(isset($_POST["model_name"]))
		$model->setName($_POST["model_name"]);
	if(isset($_POST["comments"]))
		$model->setComments($_POST["comments"]);
	
	$model->saveModel();
		
	$_SESSION["plugin_data_injection_model"] = serialize($model);
	
	$_SESSION["plugin_data_injection_wizard_step"] = 15;
	$save=3;		
	}



if(!isset($_SESSION["plugin_data_injection_wizard_step"]))
	$_SESSION["plugin_data_injection_wizard_step"] = 1;

if(!isset($_SESSION["plugin_data_injection_wizard_nbonglet"]))
	$_SESSION["plugin_data_injection_wizard_nbonglet"] = 0;
	
else if($_SESSION["plugin_data_injection_wizard_step"]==1)
	$_SESSION["plugin_data_injection_wizard_nbonglet"] = 5;


echo "<div class='global'>";

echo "<table class='step'><tr><td id='step1'>1</td><td id='step2'>2</td><td id='step3'>3</td><td id='step4'>4</td><td id='step5'>5</td><td id='step6'>6</td></tr></table>";
echo "<table class='wizard'><tr><td valign='top'>";

if($_SESSION["plugin_data_injection_wizard_nbonglet"]!=0)
	echo "<script type='text/javascript'>deleteOnglet(".$_SESSION["plugin_data_injection_wizard_nbonglet"].")</script>";

switch($_SESSION["plugin_data_injection_wizard_step"]){
	case 1:
		echo "<script type='text/javascript'>change_color_step('step1')</script>";
		step1($_SERVER["PHP_SELF"]);
	break;
	case 2:
		echo "<script type='text/javascript'>change_color_step('step2')</script>";
		step2($_SERVER["PHP_SELF"],$error);
	break;
	case 3:
		echo "<script type='text/javascript'>change_color_step('step2')</script>";
		step3($_SERVER["PHP_SELF"]);
	break;
	case 4:
		echo "<script type='text/javascript'>change_color_step('step2')</script>";
		step4($_SERVER["PHP_SELF"],$suppr);
	break;
	case 5:
		echo "<script type='text/javascript'>change_color_step('step2')</script>";
		step5($_SERVER["PHP_SELF"],$error);
	break;
	case 6:
		echo "<script type='text/javascript'>change_color_step('step3')</script>";
		step5($_SERVER["PHP_SELF"],$error);
	break;
	case 9:
		echo "<script type='text/javascript'>change_color_step('step4')</script>";
		step9($_SERVER["PHP_SELF"]);
	break;
	case 12:
		echo "<script type='text/javascript'>change_color_step('step5')</script>";
		step12($_SERVER["PHP_SELF"]);
	break;
	case 15:
		echo "<script type='text/javascript'>change_color_step('step6')</script>";
		step15($_SERVER["PHP_SELF"],$save);
	break;
}

echo "</td></tr></table>";
echo "</div>";

commonFooter();
?>
