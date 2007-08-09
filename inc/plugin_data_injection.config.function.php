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
// Original Author of file: CAILLAUD Xavier
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
	}
	
function plugin_data_injection_Install() {
	$DB = new DB;
			
	$query="CREATE TABLE `glpi_plugin_data_injection_config` (
  		 `ID` int(11) NOT NULL,
  		 PRIMARY KEY  (`ID`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1;";
			
	$DB->query($query) or die($DB->error());
	
	$query="CREATE TABLE `glpi_plugin_data_injection_models` (
		`ID` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`name` VARCHAR( 255 ) NOT NULL ,
		`comments` TEXT NULL ,
		`date_mod` DATETIME NOT NULL ,
		`FK_entities` INT( 11 ) NOT NULL
		) ENGINE = MYISAM ;";
	
	$DB->query($query) or die($DB->error());
	
	$query="CREATE TABLE `glpi_plugin_data_injection_models_datas` (
		`ID` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`model_id` INT( 11 ) NOT NULL ,
		`param` VARCHAR( 255 ) NOT NULL ,
		`value` VARCHAR( 255 ) NOT NULL
		) ENGINE = MYISAM ;";
	$DB->query($query) or die($DB->error());
	
}


function plugin_data_injection_uninstall() {
	$DB = new DB;
		
	$query = "DROP TABLE `glpi_plugin_data_injection_config`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_data_injection_models`;";
	$DB->query($query) or die($DB->error());

	$query = "DROP TABLE `glpi_plugin_data_injection_models_datas`;";
	$DB->query($query) or die($DB->error());
	
}

function plugin_data_injection_initSession()
{
	if (TableExists("glpi_plugin_data_injection_config") && TableExists("glpi_plugin_data_injection_models") && TableExists("glpi_plugin_data_injection_models_datas"))
			$_SESSION["glpi_plugin_data_injection_installed"]=1;
}
?>
