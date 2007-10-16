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

/*
 * Add a port
 * @param common_fields the common_fields
 * @param canadd indicates if user has right to add values
 */
function addNetworkCard(&$common_fields,$canadd)
{
	$just_add_port=false;
	$add_card_informations = false;
	$input=array();

		
	if (!isset($common_fields["nb_ports"]))
		$common_fields["nb_ports"]=1;
	else
	{	
		if (isset($common_fields["ifmac"]))
			unset($common_fields["ifmac"]);
		if (isset($common_fields["ifaddr"]))	
			unset($common_fields["ifaddr"]);
			
		$just_add_port=true;	
	}		

	if (isset($common_fields["ifmac"]) || isset($common_fields["ifaddr"]))
		$add_card_informations = true;

	if ($add_card_informations)
	{
		if (isset($common_fields["ifaddr"]))
			$input[0]["ifaddr"]=$common_fields["ifaddr"];
		if (isset($common_fields["ifmac"]))
			$input[0]["ifmac"]=$common_fields["ifmac"];
		if (isset($common_fields["port"]))
			$input[0]["port"]=$common_fields["port"];
	}			
	
	addNetworPorts($common_fields,$input,$canadd,$just_add_port,$add_card_informations);
	//return $common_fields;
}

function addNetworPorts($common_fields,$network_cards_infos=array(),$canadd,$just_add_port=false,$add_card_informations=false)
{
	global $DB;
	$network_ports_ids=array();
	if ($just_add_port)
			addPorts($common_fields["device_id"],$common_fields["device_type"],$common_fields["nb_ports"]);
	else
	
		for ($i=0;$i<$common_fields["nb_ports"];$i++)
		{
			addPort($common_fields,$i,$network_cards_infos,$just_add_port,$add_card_informations);
			updatePort($common_fields,$canadd);
		}
}

function addNetworkPlug($common_fields,$canadd)
{
	if (isset($common_fields["network_port_id"]) && isset($common_fields["plug"]))
		return getDropdownValue(array(), array("table"=>"glpi_dropdown_netpoint"),$common_fields["plug"],$common_fields["FK_entities"],$canadd,(isset($common_fields["location"])?$common_fields["location"]:0));	  
	else
		return 0;
}

function updatePort($common_fields,$canadd)
{
	global $DB;
		
	$netport = new Netport;
	$input=array();
	
	//Try to find a port with this mac and/or ip already connected to this item
	//$result = $DB->query("SELECT ID FROM glpi_networking_ports WHERE on_device=".$common_fields["device_id"].(isset($input["ifaddr"])?" AND ifaddr='".$input["ifaddr"]."'":'')." ".(isset($input["ifmac"])?" AND ifmac='".$input["ifmac"]."'":''));
	//if ($DB->numrows($result) > 0)
	//	$common_fields["network_port_id"] = $DB->result($result,0,"ID");
	//else
		//	If the network card still exists, don't add it
	//	$common_fields["network_port_id"] = $netport->add($input);
	
				
	if ($common_fields["network_port_id"] != EMPTY_VALUE)
	{
		$common_fields["netpoint"]=addNetworkPlug($common_fields,$canadd);
		$input["netpoint"]=$common_fields["netpoint"];
		$input["ID"]=$common_fields["network_port_id"];
		$netport->update($input);
		connectWire($common_fields);
	}
}

function connectWire($common_fields)
{
	global $DB;
	
	if (isset($common_fields["netpoint"]) && isset($common_fields["network_port_id"]))
	{
		$sql = "SELECT ID FROM glpi_networking_ports WHERE ID!=".$common_fields["network_port_id"]." AND netpoint=".$common_fields["netpoint"]." AND device_type=".NETWORKING_TYPE;
		$result=$DB->query($sql);
		if($DB->numrows($result)>0)
			$DB->query("INSERT INTO glpi_networking_wire (end1,end2) VALUES (".$common_fields["network_port_id"].",".$DB->result($result,0,"ID").")");
	}
}

function addContract($common_fields)
{
	if (isset($common_fields["contract"]))
		addDeviceContract($common_fields["contract"],$common_fields['device_type'],$common_fields['device_id']);	
}

/*
 * Add port without checking if it exists or not
 */
function addPorts($on_device,$device_type,$nb_ports)
{
	for ($i=0; $i < $nb_ports;$i++)
	{
		$input = array();
		$netport = new Netport;

		$add="";
		if ($i<9) $add="0";
		$input["logical_number"]=($i+1);
		$input["name"]=$add.($i+1);
		$input["on_device"]=$on_device;
		$input["device_type"]=$device_type;
		$netport->add($input);
	}	
}

/*
 * Add port, check if it exists and try to connect it to a plug, and to a network device
 */
function addPort(&$common_fields,$i,$network_cards_infos,$just_add_port,$add_card_informations=false)
{
	global $DB;
	$ID=0;
	$input = array();
	$netport = new Netport;

	//If a port number is provided
	if (isset($common_fields["port"]))
	{
		//First, detect if port is already present
		$sql = "SELECT ID FROM glpi_networking_ports WHERE on_device=".$common_fields["device_id"]." AND name='".$common_fields["port"]."'";
		$result = $DB->query($sql);
		if ($DB->numrows($result) > 0)
			$ID = $DB->result($result,0,"ID");
	}

	if (!$ID)
	{
		$add="";
		if ($i<9) $add="0";
		$input["logical_number"]=($i+1);
		$input["name"]=$add.($i+1);
		$input["on_device"]=$common_fields["device_id"];
		$input["device_type"]=$common_fields["device_type"];
	}
	else
	{
		$input["ID"]=$ID;
		$common_fields["network_port_id"]=$ID;
	}	
					
	if ($add_card_informations && isset($network_cards_infos[$i]))
	{
		if (isset($network_cards_infos[$i]["ifaddr"]))
			$input["ifaddr"]=$network_cards_infos[$i]["ifaddr"];
		if (isset($network_cards_infos[$i]["ifmac"]))
			$input["ifmac"]=$network_cards_infos[$i]["ifmac"];
	}	

	if (!$ID)
		$common_fields["network_port_id"] = $netport->add($input);
	else
		$netport->update($input);		
}
?>