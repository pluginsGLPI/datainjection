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
function addNetworkCard($common_fields,$canadd)
{
	if (isset($common_fields["ifmac"]) || isset($common_fields["ifaddr"]))
	{
		if (!isset($common_fields["nb_ports"]))
			$common_fields["nb_ports"]=1;
			
		$input=array();
		if (isset($common_fields["ifaddr"]))
			$input[0]["ifaddr"]=$common_fields["ifaddr"];
		if (isset($common_fields["ifmac"]))
			$input[0]["ifmac"]=$common_fields["ifmac"];
		
		addNetworPorts($common_fields,$input,$canadd);
	}
	return $common_fields;
}

function addNetworPorts($common_fields,$network_cards_infos=array(),$canadd)
{
	global $DB;
	$network_ports_ids=array();

	if (isset($common_fields["nb_ports"]))
	{
		$netport = new Netport;
		for ($i=0;$i<$common_fields["nb_ports"];$i++)
		{
			$add="";
			if ($i<10)	$add="0";
			$input["logical_number"]=$i;
			$input["name"]=$add.$i;
			$input["on_device"]=$common_fields["device_id"];
			$input["device_type"]=$common_fields["device_type"];
			
			if (count($network_cards_infos)>0 && isset($network_cards_infos[$i]))
			{
				if (isset($network_cards_infos[$i]["ifaddr"]))
					$input["ifaddr"]=$network_cards_infos[$i]["ifaddr"];
				if (isset($network_cards_infos[$i]["ifmac"]))
					$input["ifmac"]=$network_cards_infos[$i]["ifmac"];
			}	

			unset($netport->fields["ID"]);
			//If the network card still exists, don't add it
			$result = $DB->query("SELECT ID FROM glpi_networking_ports WHERE 1 ".(isset($input["ifaddr"])?" AND '".$input["ifaddr"]."'":'')." ".(isset($input["ifmac"])?" AND '".$input["ifmac"]."'":''));
			if ($DB->numrows($result) > 0)
				$common_fields["network_port_id"] = $DB->result($result,0,"ID");
			else	
				$common_fields["network_port_id"] = $netport->add($input);

			$common_fields["netpoint"]=addNetworkPlug($common_fields,$canadd);
			$input["ID"]=$common_fields["network_port_id"];
			$input["netpoint"]=$common_fields["netpoint"];
			$netport->update($input);
			
			connectWire($common_fields);	
		}
	}
}

function addNetworkPlug($common_fields,$canadd)
{
	if (isset($common_fields["network_port_id"]) && isset($common_fields["plug"]))
		return getDropdownValue(array(), array("table"=>"glpi_dropdown_netpoint"),$common_fields["plug"],$common_fields["FK_entities"],$canadd,$common_fields["location"]);	  
	else
		return 0;
}

function connectWire($common_fields)
{
	global $DB;
	
	if (isset($common_fields["netpoint"]) && isset($common_fields["network_port_id"]))
	{
		$sql = "SELECT ID FROM glpi_networking_ports WHERE netpoint=".$common_fields["netpoint"]." AND device_type!=".$common_fields["device_type"];
		$result=$DB->query($sql);
		if($DB->numrows($result)>0)
			$DB->query("INSERT INTO glpi_networking_wire (end1,end2) VALUES (".$common_fields["network_port_id"].",".$DB->result($result,0,"ID").")");
	}
}
?>