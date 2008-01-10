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
// Original Author of file: Walid Nouh (walid.nouh@atosorigin.com)
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
	}
	
function plugin_data_injection_Install() {
	$DB = new DB;
	
	$query="CREATE TABLE IF NOT EXISTS `glpi_plugin_data_injection_models` (
	  `ID` int(11) NOT NULL auto_increment,
	  `name` varchar(255) NOT NULL,
	  `comments` text,
	  `date_mod` datetime NOT NULL,
	  `type` int(11) NOT NULL default '1',
	  `device_type` int(11) NOT NULL default '1',
	  `FK_entities` int(11) NOT NULL,
	  `behavior_add` int(1) NOT NULL default '1',
	  `behavior_update` int(1) NOT NULL default '0',
	  `can_add_dropdown` int(1) NOT NULL default '0',
	  `can_overwrite_if_not_empty` int(1) NOT NULL default '1',
	  `public` int(1) NOT NULL default '1',
	  `perform_network_connection` int(1) NOT NULL default '0',
	  `user_id` int(11) NOT NULL,
	  `date_format` varchar(11) NOT NULL default 'yyyy-mm-dd',
	  PRIMARY KEY  (`ID`)
	) ENGINE=MyISAM;";
	
	$DB->query($query) or die($DB->error());

	$query="CREATE TABLE IF NOT EXISTS `glpi_plugin_data_injection_models_csv` (
	  `ID` int(11) NOT NULL auto_increment,
	  `model_id` int(11) NOT NULL,
	  `device_type` int(11) NOT NULL default '1',
	  `delimiter` varchar(1) NOT NULL default ';',
	  `header_present` int(1) NOT NULL default '1',
	  PRIMARY KEY  (`ID`)
	) ENGINE=MyISAM;";
	
	$DB->query($query) or die($DB->error());

	
	$query="CREATE TABLE IF NOT EXISTS `glpi_plugin_data_injection_mappings` (
		`ID` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`model_id` INT( 11 ) NOT NULL ,
		`type` INT( 11 ) NOT NULL DEFAULT '1',
		`rank` INT( 11 ) NOT NULL ,
		`name` VARCHAR( 255 ) NOT NULL ,
		`value` VARCHAR( 255 ) NOT NULL ,
		`mandatory` INT( 1 ) NOT NULL DEFAULT '0'		
		) ENGINE = MYISAM ;";
	$DB->query($query) or die($DB->error());

	$query="CREATE TABLE IF NOT EXISTS `glpi_plugin_data_injection_infos` (
		`ID` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		`model_id` INT( 11 ) NOT NULL ,
		`type` int(11) NOT NULL default '9',
		`value` VARCHAR( 255 ) NOT NULL ,
		`mandatory` INT( 1 ) NOT NULL DEFAULT '0'		
		) ENGINE = MYISAM ;";
	$DB->query($query) or die($DB->error());
	
	$query="CREATE TABLE IF NOT EXISTS `glpi_plugin_data_injection_filetype` (
	  `ID` int(11) NOT NULL auto_increment,
	  `name` varchar(255) NOT NULL,
	  `value` int(11) NOT NULL,
	  `backend_class_name` varchar(255) NOT NULL,
	  `model_class_name` varchar(255) NOT NULL,
	  PRIMARY KEY  (`ID`)
	) ENGINE=MyISAM AUTO_INCREMENT=2 ;
	";
	$DB->query($query) or die($DB->error());
	
	$query="REPLACE INTO `glpi_plugin_data_injection_filetype` (`ID`, `name`, `value`, `backend_class_name`, `model_class_name`) VALUES 
(1, 'CSV', 1, 'BackendCSV', 'DataInjectionModelCSV');";
	$DB->query($query) or die($DB->error());

	$query="CREATE TABLE IF NOT EXISTS `glpi_plugin_data_injection_profiles` (
	  `ID` int(11) NOT NULL auto_increment,
	  `name` varchar(255) default NULL,
	  `is_default` int(6) NOT NULL default '0',
	  `create_model` char(1) default NULL,
	  `use_model` char(1) default NULL,
	  PRIMARY KEY  (`ID`)
	) ENGINE=MyISAM;";
	$DB->query($query) or die($DB->error());
	
	if (!is_dir(PLUGIN_DATA_INJECTION_UPLOAD_DIR)) {
		@mkdir(PLUGIN_DATA_INJECTION_UPLOAD_DIR) or die("Can't create folder " . PLUGIN_DATA_INJECTION_UPLOAD_DIR);
	}
}


function plugin_data_injection_uninstall() {
	$DB = new DB;
		
	$query = "DROP TABLE `glpi_plugin_data_injection_models`;";
	$DB->query($query) or die($DB->error());

	$query = "DROP TABLE `glpi_plugin_data_injection_models_csv`;";
	$DB->query($query) or die($DB->error());

	$query = "DROP TABLE `glpi_plugin_data_injection_mappings`;";
	$DB->query($query) or die($DB->error());

	$query = "DROP TABLE `glpi_plugin_data_injection_infos`;";
	$DB->query($query) or die($DB->error());
	
	$query = "DROP TABLE `glpi_plugin_data_injection_filetype`;";
	$DB->query($query) or die($DB->error());

	$query = "DROP TABLE `glpi_plugin_data_injection_profiles`;";
	$DB->query($query) or die($DB->error());
	
	if (is_dir(PLUGIN_DATA_INJECTION_UPLOAD_DIR)) {
		deleteDir(PLUGIN_DATA_INJECTION_UPLOAD_DIR);
	}
}

function plugin_data_injection_initSession() {
	global $DB;
	
	if(TableExists("glpi_plugin_data_injection_filetype") && 
		is_dir(PLUGIN_DATA_INJECTION_UPLOAD_DIR) && is_writable(PLUGIN_DATA_INJECTION_UPLOAD_DIR)){
		$profile=new DataInjectionProfile();
	
		$query = "SELECT DISTINCT glpi_profiles.* FROM glpi_users_profiles INNER JOIN glpi_profiles ON (glpi_users_profiles.FK_profiles = glpi_profiles.ID) WHERE glpi_users_profiles.FK_users='".$_SESSION["glpiID"]."'";
		$result = $DB->query($query);
		$_SESSION['glpi_plugin_data_injection_profile'] = array ();
		if ($DB->numrows($result)) {
			while ($data = $DB->fetch_assoc($result)) {
				$profile->fields = array ();
				if(isset($_SESSION["glpiactiveprofile"]["ID"])){
					$profile->getFromDB($_SESSION["glpiactiveprofile"]["ID"]);
					$_SESSION['glpi_plugin_data_injection_profile'] = $profile->fields;
				}else{
					$profile->getFromDB($data['ID']);
					$_SESSION['glpi_plugin_data_injection_profile'] = $profile->fields;
				}
				$_SESSION["glpi_plugin_data_injection_installed"]=1;
			}
		}
	}
}

function plugin_data_injection_changeprofile()
{
	if(isset($_SESSION["glpi_plugin_data_injection_installed"]) && $_SESSION["glpi_plugin_data_injection_installed"]==1){
		$prof=new DataInjectionProfile();
		if($prof->getFromDB($_SESSION['glpiactiveprofile']['ID']))
			$_SESSION["glpi_plugin_data_injection_profile"]=$prof->fields;
		else
			unset($_SESSION["glpi_plugin_data_injection_profile"]);
	}
}

function plugin_data_injection_createfirstaccess($ID){

	GLOBAL $DB;
	
	$query0 ="SELECT * FROM glpi_plugin_data_injection_profiles where ID='".$ID."';";
	$result0=$DB->query($query0);
	if ($DB->numrows($result0)==0){
		$query="SELECT * FROM glpi_profiles where ID='$ID';";
		$result=$DB->query($query);
		$name = $DB->result($result, 0, "glpi_profiles.name");
		
		$query1 ="INSERT INTO `glpi_plugin_data_injection_profiles` ( `ID`, `name` , `is_default`, `create_model`, `use_model`) VALUES ('$ID', '$name','0','w','r');";
		$DB->query($query1);
	}
}

function plugin_data_injection_createaccess($ID){

	$DB = new DB;
	$query="SELECT * FROM glpi_profiles where ID='$ID';";
	$result=$DB->query($query);
	$i = 0;
	$name = $DB->result($result, $i, "glpi_profiles.name");

	$query1 ="INSERT INTO `glpi_plugin_data_injection_profiles` ( `ID`, `name` , `is_default`, `create_model`, `use_model`) VALUES ('$ID', '$name','0',NULL,NULL);";

	$DB->query($query1);
}

function plugin_data_injection_haveRight($module,$right){
	$matches=array(
			""  => array("","r","w"), // ne doit pas arriver normalement
			"r" => array("r","w"),
			"w" => array("w"),
			"1" => array("1"),
			"0" => array("0","1"), // ne doit pas arriver non plus
		      );
	if (isset($_SESSION["glpi_plugin_data_injection_profile"][$module])&&in_array($_SESSION["glpi_plugin_data_injection_profile"][$module],$matches[$right]))
		return true;
	else return false;
}

function plugin_data_injection_checkRight($module, $right) {
	global $CFG_GLPI;

	if (!plugin_data_injection_haveRight($module, $right)) {
		// Gestion timeout session
		if (!isset ($_SESSION["glpiID"])) {
			glpi_header($CFG_GLPI["root_doc"] . "/index.php");
			exit ();
		}

		displayRightError();
	}
}
?>
