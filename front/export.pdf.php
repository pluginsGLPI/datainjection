<?php

if (!defined('GLPI_ROOT')) {
	define('GLPI_ROOT', '../../..');
}

include (GLPI_ROOT."/lib/ezpdf/class.ezpdf.php");
include (GLPI_ROOT."/inc/includes.php");

PluginDatainjectionModel::exportAsPDF($_GET['models_id']);
?>
