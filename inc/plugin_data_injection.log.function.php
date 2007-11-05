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

// Original Author of file: Walid Nouh (walid.nouh@atosorigin.com)
// Purpose of file:
// ----------------------------------------------------------------------
function getLogItemType($device_type)
{
    switch ($device_type)
    {
    	case COMPUTER_TYPE:
    		return "computers";
		case MONITOR_TYPE:
			return "monitors"; 
		case NETWORKING_TYPE:
			return "networking";
		case PHONE_TYPE:
			return "phones";
		case PERIPHERAL_TYPE:
			return "peripherals";
		case USER_TYPE:
			return "users";
		case GROUP_TYPE:
			return "groups";
		case CONTRACT_TYPE:
			return "contracts";
		case PRINTER_TYPE:
			return "printers";	
		case CONTACT_TYPE:
			return "contacts";
		case CARTRIDGE_TYPE:
			return "cartridges";
		default:
			return "";							   		
    } 
}
?>