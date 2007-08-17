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

if(isset($_POST["next1"]))
	{
	switch($_POST["modele"])
		{
		case 1:
			$_SESSION["wizard_step"] = 2;
			$_SESSION["wizard_nbonglet"] = 5;
		break;
		case 2:
			$_SESSION["wizard_step"] = 3;
			$_SESSION["wizard_idmodel"] = $_POST["dropdown"];
			$_SESSION["wizard_nbonglet"] = 4;
		break;
		case 3:
			$_SESSION["wizard_step"] = 4;
			$_SESSION["wizard_idmodel"] = $_POST["dropdown"];
			$_SESSION["wizard_nbonglet"] = 2;
		break;
		case 4:
			$_SESSION["wizard_step"] = 5;
			$_SESSION["wizard_idmodel"] = $_POST["dropdown"];
			$_SESSION["wizard_nbonglet"] = 3;
			$_SESSION["verif_file"]=0;
		break;
		}
	}
	
else if(isset($_POST["preview2"]))
	$_SESSION["wizard_step"] = 1;
	
else if(isset($_POST["next2"]))
	{
	$_SESSION["wizard_step"] = 6;
	$_SESSION["verif_file"]=1;
	$_SESSION["dropdown_type"] = $_POST["dropdown_type"];
	$_SESSION["delimiteur"] = $_POST["delimiteur"];
	$_SESSION["dropdown_create"] = $_POST["dropdown_create"];
	$_SESSION["dropdown_update"] = $_POST["dropdown_update"];
	$_SESSION["dropdown_header"] = $_POST["dropdown_header"];
	}
	
else if(isset($_POST["preview3"]))
	$_SESSION["wizard_step"] = 1;
	
else if(isset($_POST["next3"]))
	$_SESSION["wizard_step"] = 7;
	
else if(isset($_POST["preview4"]))
	$_SESSION["wizard_step"] = 1;
	
else if(isset($_POST["next4_1"]))
	{
	$_SESSION["wizard_step"] = 4;
	$suppr=1;
	}
else if(isset($_POST["next4_2"]))
	$_SESSION["wizard_step"] = 1;

else if(isset($_POST["preview5"]))
	{
	if(!$_SESSION["verif_file"])
		$_SESSION["wizard_step"] = 1;
	else
		$_SESSION["wizard_step"] = 2;
	}
	
else if(isset($_POST["next5"]))
	{
	$directory = PLUGIN_DATA_INJECTION_UPLOAD_DIR;

    $tmp_file = $_FILES["modelfile"]["tmp_name"];

    if( !is_uploaded_file($tmp_file) )
    	{
        $error="Le fichier est introuvable";
        if(!$_SESSION["verif_file"])
        	$_SESSION["wizard_step"] = 5;
        else
        	$_SESSION["wizard_step"] = 6;
    	}
    else
    	{
		if($_SESSION["verif_file"])
			{
			$type = new BackendType();
			$type->getFromDB($_SESSION["dropdown_type"]);
			$extension = $type->getBackendName();
			}
		else
			{
			$model = new DataInjectionModel();
			$model->getFromDB($_SESSION["wizard_idmodel"]);
			$extension = getDropdownName('glpi_plugin_data_injection_filetype',$model->getModelType());
			}
		
		$name_file = $_FILES["modelfile"]["name"];
		
    	if( !strstr(substr($name_file,strlen($name_file)-4), strtolower($extension)) )
    		{
        	$error="Le fichier n'a pas le bon format <br /> Extension ".$extension." requise";
        	if(!$_SESSION["verif_file"])
        		$_SESSION["wizard_step"] = 5;
        	else
        		$_SESSION["wizard_step"] = 6;
    		}
    	else
    		{
    		if( !move_uploaded_file($tmp_file, $directory . $name_file) )
    			{
        		$error="Impossible de copier le fichier dans ".$directory;
        		if(!$_SESSION["verif_file"])
        			$_SESSION["wizard_step"] = 5;
        		else
        			$_SESSION["wizard_step"] = 6;
    			}
    		else
    			{
    			if(!$_SESSION["verif_file"])
    				$_SESSION["wizard_step"] = 8;
    			else
    				$_SESSION["wizard_step"] = 9;
    			
    			$_SESSION["file_name"] = $name_file;
    			}
    		}
    	}
	}

else if(isset($_POST["preview9"]))
	$_SESSION["wizard_step"] = 6;
	
else if(isset($_POST["next9"]))
	$_SESSION["wizard_step"] = 12;
	
	


if(!isset($_SESSION["wizard_step"]))
	$_SESSION["wizard_step"] = 1;

if(!isset($_SESSION["wizard_nbonglet"]))
	$_SESSION["wizard_nbonglet"] = 0;
	
else if($_SESSION["wizard_step"]==1)
	$_SESSION["wizard_nbonglet"] = 5;


echo "<div class='global'>";

echo "<table class='step'><tr><td id='step1'>1</td><td id='step2'>2</td><td id='step3'>3</td><td id='step4'>4</td><td id='step5'>5</td></tr></table>";
echo "<table class='wizard'><tr><td valign='top'>";

switch($_SESSION["wizard_step"]){
	case 1:
		echo "<script type='text/javascript'>change_color_step('step1')</script>";
		step1($_SERVER["PHP_SELF"]);
	break;
	case 2:
		echo "<script type='text/javascript'>change_color_step('step2')</script>";
		step2($_SERVER["PHP_SELF"]);
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
}

echo "</td></tr></table>";
echo "</div>";

if($_SESSION["wizard_nbonglet"]!=0)
	echo "<script type='text/javascript'>deleteOnglet(".$_SESSION["wizard_nbonglet"].")</script>";

commonFooter();
?>
