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

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");

include("../plugin_data_injection.includes.php");

header("Content-Type: text/html; charset=UTF-8");

echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>";

echo "<html>";

echo "<head>";

echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8 \" >";

echo "<link rel='stylesheet'  href='".$CFG_GLPI["root_doc"]."/css/styles.css' type='text/css' media='screen' >\n";
echo "<!--[if lte IE 6]>" ;
echo "<link rel='stylesheet' href='".$CFG_GLPI["root_doc"]."/css/styles_ie.css' type='text/css' media='screen' >\n";
echo "<![endif]-->";

echo "<title>Popup</title>";

echo "</head>";

echo "<body>";

if(isset($_GET["nbline"]))
	$nbline = $_GET["nbline"];

$file = unserialize($_SESSION["plugin_data_injection"]["backend"]);
$model = unserialize($_SESSION["plugin_data_injection"]["model"]);

$header = $file->getHeader($model->isHeaderPresent());

if($model->isHeaderPresent())
	$data = $file->getDatasFromLineToLine(1,$nbline);
else
	$data = $file->getDatasFromLineToLine(0,$nbline-1);	
	
echo "<table class='tab_cadre_fixe'><tr>";

	foreach($header as $key => $value)
		echo"<th style='height:40px'>".stripslashes($value)."</th>";
	echo "</tr>";
	
	
	foreach($data as $key => $value)
		{
		if($key%2==0)
			echo "<tr class='tab_bg_1'>";
		else
			echo "<tr class='tab_bg_2'>";	
			
		foreach($value as $key2 => $value2)
			echo "<td style='height:40px;text-align:center'>".$value2."</td>";
		
		echo "</tr>";
		}
	
	echo "</table>";


echo "<div style='margin-top:15px;text-align:center'>";
echo "<a href='javascript:window.close()'>" . $LANG["data_injection"]["button"][8] .  "</a>";
echo "</div>";

echo "</body>";

echo "</html>";

?>
