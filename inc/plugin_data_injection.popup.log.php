<?php

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");

include("../plugin_data_injection.includes.php");

echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>";

echo "<html>";

echo "<head>";

echo "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>";

echo "<link rel='stylesheet'  href='".$CFG_GLPI["root_doc"]."/css/styles.css' type='text/css' media='screen' >\n";
echo "<!--[if lte IE 6]>" ;
echo "<link rel='stylesheet' href='".$CFG_GLPI["root_doc"]."/css/styles_ie.css' type='text/css' media='screen' >\n";
echo "<![endif]-->";

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


echo "<table class='tab_cadre_fixe'>";

foreach($tab_result as $value)
	{
	echo "<tr>";
	echo "<td>".$value->getStatus()."</td>";
	echo "<td>".$value->getCheckMessage()."</td>";
	echo "<td>".$value->getInjectionMessage()."</td>";
	echo "<td>".$value->getInjectionType()."</td>";
	echo "</tr>";
	}


	
echo "</table>";

echo "<div style='margin-top:15px;text-align:center'>";
echo "<a href='javascript:window.close()'>fermer</a>";
echo "</div>";

echo "</body>";

echo "</html>";

?>