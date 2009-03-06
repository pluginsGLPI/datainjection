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
	define('GLPI_ROOT', '../../..');
}
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
echo "<script type=\"text/javascript\" src=\"../javascript/data_injection.js\"></script>";

echo "<title>Popup</title>";

echo "</head>";

echo "<body>";

$model = unserialize($_SESSION["plugin_data_injection"]["model"]);
$tab_result = unserialize($_SESSION["plugin_data_injection"]["import"]["tab_result"]);

$tab_result = sortAllResults($tab_result);

if(count($tab_result[1])>0)
	{
	echo "<table>";
	echo "<tr>";
	echo "<td style='width:30px'>";
	echo "<a href=\"javascript:show_log('1')\"><img src='../pics/plus.png' alt='plus' id='log1' /></a>";
	echo "</td>";
	echo "<td style='width: 900px;font-size: 14px;font-weight: bold;padding-left: 20px'>";
	echo $LANG["datainjection"]["logStep"][4];
	echo "</td>";
	echo "</tr>";
	echo "</table>";
	
	echo "<table class='tab_cadre_fixe' style='text-align: center' id='log1_table'>";
	
	echo "<tr><th>".$LANG["joblist"][0]."</th><th>".$LANG["datainjection"]["result"][14]."</th><th>".$LANG["datainjection"]["result"][10]."</th><th>".$LANG["datainjection"]["result"][11]."</th><th>".$LANG["datainjection"]["result"][12]."</th><th>".$LANG["datainjection"]["result"][13]."</th></tr>";
	
	foreach($tab_result[1] as $key => $value)
		{
		$num=($key%2)+1;
	
		echo "<tr class='tab_bg_$num'>";
		// Global status OK
		echo "<td style='height:30px;width:30px'><img src='../pics/ok.png' alt='success' /></td>";
		echo "<td style='height:30px;width:50px'>".$value->getLineID()."</td>";
		
		echo "<td style='height:30px;width:300px'>";
		echo nl2br($value->getCheckMessage())."</td>";
		
		echo "<td style='height:30px;width:300px'>";
		echo nl2br($value->getInjectionMessage())."</td>";
		
		echo "<td style='height:30px;width:200px'>".($value->getInjectionType()==INJECTION_ADD?$LANG["datainjection"]["result"][8]:$LANG["datainjection"]["result"][9])."</td>";
		
		if ($value->getInjectedId() > 0)
			{
			$url = GLPI_ROOT."/".$INFOFORM_PAGES[$model->getDeviceType()]."?ID=".$value->getInjectedId();
			echo "<td style='height:30px'><a href=\"".$url."\" target=_blank>".$value->getInjectedId()."</a></td>";
			}
		else
			echo "<td style='height:30px'>".$LANG["common"][49]."</td>";
		
		echo "</tr>";
		}
	
	echo "</table>";
	}

if(count($tab_result[0])>0)
	{
	if(count($tab_result[1])>0)
		echo "<table style='margin-top: 20px;'>";
	else
		echo "<table>";
	
	echo "<tr>";
	echo "<td style='width:30px'>";
	echo "<a href=\"javascript:show_log('2')\"><img src='../pics/minus.png' alt='minus' id='log2' /></a>";
	echo "</td>";
	echo "<td style='width: 900px;font-size: 14px;font-weight: bold;padding-left: 20px'>";
	echo $LANG["datainjection"]["logStep"][5];
	echo "</td>";
	echo "</tr>";
	echo "</table>";

	echo "<table class='tab_cadre_fixe' style='text-align: center' id='log2_table'>";
	
	echo "<tr><th>".$LANG["joblist"][0]."</th><th>".$LANG["datainjection"]["result"][14]."</th><th>".$LANG["datainjection"]["result"][10]."</th><th>".$LANG["datainjection"]["result"][11]."</th><th>".$LANG["datainjection"]["result"][12]."</th><th>".$LANG["datainjection"]["result"][13]."</th></tr>";
	
	foreach($tab_result[0] as $key => $value)
		{
		$num=($key%2)+1;
		
		// Global status
		echo "<tr class='tab_bg_$num'><td style='height:30px;width:30px'>";
		if ($value->getCheckStatus() != CHECK_OK || $value->getInjectionStatus()==NOT_IMPORTED) {
			echo "<img src='../pics/notok.png' alt='error' />";
		} else {
			echo "<img src='../pics/danger.png' alt='warning' />";
		}
		echo "</td><td style='height:30px;width:50px'>".$value->getLineID()."</td>";
		
		// Check status
		echo "<td style='height:30px;width:300px'>";
		if($value->getCheckStatus() != CHECK_OK) {
			echo "<img src='../pics/notok.png' alt='error' />";
		}
		echo nl2br($value->getCheckMessage())."</td>";
		
		
		// Injection status
		echo "<td style='height:30px;width:300px'>";
		if($value->getInjectionStatus() == NOT_IMPORTED) {
			echo "<img src='../pics/notok.png' alt='error' />";			
		} else {
			echo "<img src='../pics/danger.png' alt='danger' />";			
		}
		echo nl2br($value->getInjectionMessage())."</td>";
		
		echo "<td style='height:30px;width:200px'>".($value->getInjectionType()==INJECTION_ADD?$LANG["datainjection"]["result"][8]:$LANG["datainjection"]["result"][9])."</td>";
		
		if ($value->getInjectedId() > 0)
			{
			$url = GLPI_ROOT."/".$INFOFORM_PAGES[$model->getDeviceType()]."?ID=".$value->getInjectedId();
			echo "<td style='height:30px'><a href=\"".$url."\" target=_blank>".$value->getInjectedId()."</a></td>";
			}
		else
			echo "<td style='height:30px'>".$LANG["common"][49]."</td>";
		
		echo "</tr>";
		}
	
	echo "</table>";
	}

if(count($tab_result[1])>0)
	echo "<script type='text/javascript'>document.getElementById('log1_table').style.display='none'</script>";

echo "<div style='margin-top:15px;text-align:center'>";
echo "<a href='javascript:window.close()'>" . $LANG["datainjection"]["button"][8] . "</a>";
echo "</div>";

echo "</body>";

echo "</html>";

?>
