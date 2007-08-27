<?php
/*
 * @version $Id: rules.constant.php 5351 2007-08-07 11:57:46Z walid $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2007 by the INDEPNET Development Team.

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
class DataInjectionResults {
	
	var $status;
	var $check_message;
	var $injection_message;
	var $injection_type;
	
	function DataInjectionResults()
	{
		$this->status = -1;
		$this->check_message = "";
		$this->injection_message = "";
		$this->injection_type = "";
	}

	
	function getStatus()
	{
		return $this->status;
	}
	
	function getCheckMessage()
	{
		return $this->check_message;
	}	
	
	function getInjectionMessage()
	{
		return $this->injection_message;
	}

	function getInjectionType()
	{
		return $this->injection_type;
	}


	function setStatus($status)
	{
		$this->status = $status;
	}

	function setCheckMessage($message)
	{
		$this->check_message = $message;
	}
	
	function setInjectionMessage($message)
	{
		$this->injection_message = $message;
	}

	function setInjectionType($type)
	{
		$this->injection_message = $type;
	}

}
?>
