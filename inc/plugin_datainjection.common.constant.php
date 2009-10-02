<?php


/*
 * @version $Id: rules.constant.php 5351 2007-08-07 11:57:46Z walid $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------

global $IMPORT_TYPES,$IMPORT_PRIMARY_TYPES,$CONNECT_TO_COMPUTER_TYPES,
   $CONNECT_TO_ALL_TYPES,$CONNECT_TO_SOFTWARE_TYPES;

// pseudo_type
define("COMPUTER_CONNECTION_TYPE", 998);
define("NETPORT_TYPE", 999);
define("SOFTWARE_CONNECTION_TYPE", 997);
define("CONNECTION_ALL_TYPES", 996);

//Store all the type of items that could be imported
$IMPORT_TYPES = array (
	COMPUTER_TYPE,
	MONITOR_TYPE,
	PRINTER_TYPE,
	PHONE_TYPE,
	USER_TYPE,
	INFOCOM_TYPE,
	NETWORKING_TYPE,
	GROUP_TYPE,
	CONTRACT_TYPE,
	PERIPHERAL_TYPE,
	ENTERPRISE_TYPE,
	CONTACT_TYPE,
	CARTRIDGE_TYPE,
	CONSUMABLE_TYPE,
	DOCUMENT_TYPE, 
   SOFTWARE_TYPE,
   SOFTWAREVERSION_TYPE,
   SOFTWARELICENSE_TYPE,
   KNOWBASE_TYPE,
   PROFILE_TYPE
);

//Primary types to import
$IMPORT_PRIMARY_TYPES = array (
	COMPUTER_TYPE,
	MONITOR_TYPE,
	PRINTER_TYPE,
	PHONE_TYPE,
	USER_TYPE,
	NETWORKING_TYPE,
	GROUP_TYPE,
	CONTRACT_TYPE,
	PERIPHERAL_TYPE,
	ENTERPRISE_TYPE,
	CONTACT_TYPE,
	CARTRIDGE_TYPE,
	CONSUMABLE_TYPE,
	ENTITY_TYPE,
	DOCUMENT_TYPE,
   SOFTWARE_TYPE,
   SOFTWAREVERSION_TYPE,
   SOFTWARELICENSE_TYPE,
   KNOWBASE_TYPE,
   PROFILE_TYPE
);

//Types to connect to a computer
$CONNECT_TO_COMPUTER_TYPES = array (
	PERIPHERAL_TYPE,
	MONITOR_TYPE,
	PRINTER_TYPE,
	PHONE_TYPE,
   SOFTWARELICENSE_TYPE
);

//Types to connect to a sofware
$CONNECT_TO_SOFTWARE_TYPES = array (
	SOFTWARELICENSE_TYPE,
   SOFTWAREVERSION_TYPE
);

//Connect a type to all the others (by giving type + ID)
$CONNECT_TO_ALL_TYPES = array (
	DOCUMENT_TYPE
);


?>