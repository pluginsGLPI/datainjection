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

if(isset($_POST["next1"]))
	$_SESSION["step"] = 2;
else if(isset($_POST["preview2"]))
	$_SESSION["step"] = 1;
else if(isset($_POST["next2"]))
	{
	$directory = "upload/";

    $tmp_file = $_FILES["modelfile"]["tmp_name"];

    if( !is_uploaded_file($tmp_file) )
    	{
        $error="Le fichier est introuvable";
        $_SESSION["step"] = 2;
    	}
    else
    	{
    	$type_file = $_FILES["modelfile"]["type"];

    	if( !strstr($type_file, 'gif') && !strstr($type_file, 'xls') && !strstr($type_file, 'xml') )
    		{
        	$error="Le fichier n'a pas le bon format";
        	$_SESSION["step"] = 2;
    		}
    	else
    		{
    		$name_file = $_FILES["modelfile"]["name"];

    		if( !move_uploaded_file($tmp_file, $directory . $name_file) )
    			{
        		$error="Impossible de copier le fichier dans $directory";
        		$_SESSION["step"] = 2;
    			}
    		else
    			$_SESSION["step"] = 3;
    		}
    	}
	}
else if(isset($_POST["preview3"]))
	$_SESSION["step"] = 2;



if(isset($_SESSION["step"]))
	$step = $_SESSION["step"];
else
	$step = $_SESSION["step"] = 1;


echo "<div class='global'>";

echo "<table class='step'><tr><td id='step1'>1</td><td id='step2'>2</td><td id='step3'>3</td></tr></table>";
echo "<table class='wizard'><tr><td valign='top'>";

switch($step){
	case 1:
		echo "<script type='text/javascript'>change_color_step('step1')</script>";
		step1($_SERVER["PHP_SELF"]);
	break;
	case 2:
		echo "<script type='text/javascript'>change_color_step('step2')</script>";
		step2($_SERVER["PHP_SELF"],$error);
	break;
	case 3:
		echo "<script type='text/javascript'>change_color_step('step3')</script>";
		step3($_SERVER["PHP_SELF"]);
	break;
}

echo "</td></tr></table>";
echo "</div>";

commonFooter();
?>
