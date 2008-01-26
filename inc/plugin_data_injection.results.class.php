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
class DataInjectionResults {
	
	//Overall status of the process
	//private $status;
	
	//Status of the data check
	private $check_status;
	
	//Status of the data injection
	private $injection_status;
	
	//Messages of the data check
	private $check_message;
	
	//Message of the data injection
	private $injection_message;

	//Type of injection (add or update)
	private $injection_type;
	
	//ID of the item added or updated
	private $injected_id;
	
	//ID of the line processed
	private $line_id;
	
	function DataInjectionResults()
	{
		$this->status = -1;
		$this->check_message = array();
		$this->injection_message = array();
		$this->injection_type = array();
		$this->injected_id = -1;
		$this->line_id=-1;
	}

	//Getters
	function getLineID()
	{
		return $this->line_id;
	}
	
	function getStatus()
	{
		return ($this->check_status == CHECK_OK && 
			$this->injection_status == IMPORT_OK);
	}
	
	function getCheckStatus()
	{
		return $this->check_status;
	}
	
	function getInjectionStatus()
	{
		return $this->injection_status;
	}

	function getCheckMessage()
	{
		if ($this->check_status == CHECK_OK)
			return $this->getLabel(TYPE_CHECK_OK);
			
		$output = "";
		foreach ($this->check_message as $field => $res) {
			$output .= ($output?"\n":" ").$this->getLabel($res)." ($field)";			
		}
		
		return $output;
	}	
	
	function getInjectionMessage()
	{
		if ($this->injection_status == IMPORT_OK)
			return $this->getLabel(IMPORT_OK);
			
		$output = "";
		foreach ($this->injection_message as $field => $res) {
			$output .= ($output?"\n":" ").$this->getLabel($res);
			if ($field && !intval($field)) {
				$output .= " ($field)";
			}			
		}
		
		return $output;
	}

	function getInjectionType()
	{
		return $this->injection_type;
	}

	function getInjectedId()
	{
		return $this->injected_id;
	}

	//Setters
	
/*	function setStatus($status)
	{
		$this->status = $status;
	}

	function setCheckStatus($message)
	{
		$this->check_status = $message;
	}
	
	function setInjectionStatus($status)
	{
		$this->injection_status = $status;
	}
*/
	function setInjectionType($type)
	{
		$this->injection_type = $type;
	}
	
	function setInjectedId($ID)
	{
		$this->injected_id = $ID;
	}
	
	function setLineId($ID)
	{
		$this->line_id = $ID;
	}
	
	/** 
	 * Add a message for Check pass
	 * 
	 * @param $message : number
	 * @param $field : name (only if error)
	 * 
	 * @return boolean : OK
	 */
	function addCheckMessage($message, $field)
	{
		switch ($message) {
			case TYPE_CHECK_OK:
				$this->check_status = CHECK_OK;
				$this->injection_status = IMPORT_OK;
				break;
			case ERROR_IMPORT_WRONG_TYPE:
			case ERROR_IMPORT_FIELD_MANDATORY:
				$this->check_status = CHECK_NOTOK;
				$this->injection_status = NOT_IMPORTED;

				$this->check_message[$field] = $message;
				break;
		}
		return ($this->check_status == CHECK_OK);
	}

	/** 
	 * Add a message for Injection pass
	 * 
	 * @param $message : number
	 * @param $field : name (optional)
	 * 
	 * @return boolean : OK
	 */
	function addInjectionMessage($message, $field=false)
	{
		switch ($message) {
			case ERROR_IMPORT_ALREADY_IMPORTED:
			case ERROR_CANNOT_IMPORT:
			case ERROR_CANNOT_UPDATE:
				$this->injection_status = NOT_IMPORTED;
				break;
			case WARNING_NOTEMPTY:
			case WARNING_NOTFOUND:
			case WARNING_USED:
				$this->injection_status = PARTIALY_IMPORTED;
				break;
		}
		if ($field) {
			$this->injection_message[$field] = $message;
		} else {
			$this->injection_message[] = $message;
		}
		
		return ($this->injection_status == IMPORT_OK);
	}
			
	private function getLabel($type)
	{
		global $DATAINJECTIONLANG;
		
		$message = "";
		switch ($type)
		{
			case ERROR_CANNOT_IMPORT:
				$message = $DATAINJECTIONLANG["result"][5];
			break;
			case WARNING_NOTEMPTY:
			case ERROR_CANNOT_UPDATE:
				$message = $DATAINJECTIONLANG["result"][6];
			break;
			case ERROR_IMPORT_ALREADY_IMPORTED:
				$message = $DATAINJECTIONLANG["result"][3];
			break;
			case ERROR_IMPORT_WRONG_TYPE:
				$message = $DATAINJECTIONLANG["result"][1];
			break;
			case ERROR_IMPORT_FIELD_MANDATORY:
				$message = $DATAINJECTIONLANG["result"][4];
			break;
			case TYPE_CHECK_OK:
				$message = $DATAINJECTIONLANG["result"][2];
			break;
			case IMPORT_OK:
				$message = $DATAINJECTIONLANG["result"][7];
			break;
			case WARNING_NOTFOUND:
				$message = $DATAINJECTIONLANG["result"][15];
			break;
			case WARNING_USED:
				$message = $DATAINJECTIONLANG["result"][16];
			break;
		}
		return $message;
	}
}
?>
