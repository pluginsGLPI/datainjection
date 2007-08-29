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

if(isset($_GET["nbline"]))
	$nbline = $_GET["nbline"];

$file=new BackendCSV();
$model = unserialize($_SESSION["plugin_data_injection"]["model"]);
$file->initBackend(PLUGIN_DATA_INJECTION_UPLOAD_DIR.$_SESSION["plugin_data_injection"]["file"],$model->getDelimiter());
$file->read();
$header = $file->getHeader($model->isHeaderPresent());

if($model->isHeaderPresent())
	$data = $file->getDatasFromLineToLine(1,$nbline);
else
	$data = $file->getDatasFromLineToLine(0,$nbline-1);

echo "</table>";	
	
	echo "<table class='tab_cadre_fixe'><tr>";
	foreach($header as $key => $value)
		echo"<th>".utf8_decode($value)."</th>";
	echo "</tr>";
	
	
	foreach($data as $key => $value)
		{
		echo "<tr>";
		foreach($value as $key2 => $value2)
			{
			if($key2%2==0)
				echo "<td class='tab_bg_1'>";
			else
				echo "<td class='tab_bg_2'>";
			echo $value2."</td>";
			}
		echo "</tr>";
		}
	
	echo "</table>";


echo "<div style='margin-top:15px;text-align:center'>";
echo "<a href='javascript:window.close()'>fermer</a>";
echo "</div>";

echo "</body>";

echo "</html>";

?>