<?php
if (!defined('GLPI_ROOT')) {
	define('GLPI_ROOT', '../../..');
}

include (GLPI_ROOT."/inc/includes.php");

$fichier = PLUGIN_DATAINJECTION_UPLOAD_DIR.$_SESSION["plugin_datainjection"]["file_name"];
$nom_fichier = $_SESSION["plugin_datainjection"]["file_name"];

header('Content-disposition: attachment; filename=Error-'.$nom_fichier);
header('Content-Type: application/force-download');
header('Content-Transfer-Encoding: fichier');
header('Content-Length: '.filesize($fichier));
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');
readfile($fichier);
unlink($fichier);
?>
