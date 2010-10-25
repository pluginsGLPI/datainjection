<?php
if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
}

include (GLPI_ROOT."/inc/includes.php");

PluginDatainjectionClientInjection::exportErrorsInCSV();
?>