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

// ----------------------------------------------------------------------
// Original Author of file: Dévi Balpe
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");

include("../plugin_data_injection.includes.php");

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
$global_result = unserialize($_SESSION["plugin_data_injection"]["import"]["global_result"]);
$engine = unserialize($_SESSION["plugin_data_injection"]["import"]["engine"]);
$nbline = $_SESSION["plugin_data_injection"]["import"]["nbline"];
$i = $_SESSION["plugin_data_injection"]["import"]["i"];
$progress = $_SESSION["plugin_data_injection"]["import"]["progress"];
$datas = $_SESSION["plugin_data_injection"]["import"]["datas"];

$tab_result = sortAllResults($tab_result);

echo "<a href=\"javascript:show_log('1')\"><img src='../pics/minus.png' alt='minus' id='log1' /></a>";

echo "<table class='tab_cadre_fixe' style='text-align: center' id='log1_table'>";

echo "<tr><th>".$LANG["joblist"][0]."</th><th>".$DATAINJECTIONLANG["result"][14]."</th><th>".$DATAINJECTIONLANG["result"][10]."</th><th>".$DATAINJECTIONLANG["result"][11]."</th><th>".$DATAINJECTIONLANG["result"][12]."</th><th>".$DATAINJECTIONLANG["result"][13]."</th></tr>";

foreach($tab_result[1] as $key => $value)
	{
	if($key%2==0)
		$num = 1;
	else
		$num = 2;

	echo "<tr class='tab_bg_$num'>";
	echo "<td style='height:30px;width:30px'><img src='../pics/ok.png' alt='success' /></td>";
	echo "<td style='height:30px;width:50px'>".$value->getLineID()."</td>";
	echo "<td style='height:30px;width:300px'>".$value->getCheckMessage()."</td>";
	echo "<td style='height:30px;width:300px'>".$value->getInjectionMessage()."</td>";
	echo "<td style='height:30px;width:300px'>".($value->getInjectionType()==INJECTION_ADD?$DATAINJECTIONLANG["result"][8]:$DATAINJECTIONLANG["result"][9])."</td>";
	
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

echo "<a href=\"javascript:show_log('2')\" style='margin-top: 20px'><img src='../pics/minus.png' alt='minus' id='log2' /></a>";

echo "<table class='tab_cadre_fixe' style='text-align: center' id='log2_table'>";

echo "<tr><th>".$LANG["joblist"][0]."</th><th>".$DATAINJECTIONLANG["result"][14]."</th><th>".$DATAINJECTIONLANG["result"][10]."</th><th>".$DATAINJECTIONLANG["result"][11]."</th><th>".$DATAINJECTIONLANG["result"][12]."</th><th>".$DATAINJECTIONLANG["result"][13]."</th></tr>";

foreach($tab_result[0] as $key => $value)
	{
	if($key%2==0)
		$num = 1;
	else
		$num = 2;

	echo "<tr class='tab_bg_$num'>";
	echo "<td style='height:30px;width:30px'><img src='../pics/notok.png' alt='success' /></td>";
	echo "<td style='height:30px;width:50px'>".$value->getLineID()."</td>";
	echo "<td style='height:30px;width:300px'>".$value->getCheckMessage()."</td>";
	echo "<td style='height:30px;width:300px'>".$value->getInjectionMessage()."</td>";
	echo "<td style='height:30px;width:200px'>".($value->getInjectionType()==INJECTION_ADD?$DATAINJECTIONLANG["result"][8]:$DATAINJECTIONLANG["result"][9])."</td>";
	
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

echo "<div style='margin-top:15px;text-align:center'>";
echo "<a href='javascript:window.close()'>fermer</a>";
echo "</div>";

echo "</body>";

echo "</html>";

?>