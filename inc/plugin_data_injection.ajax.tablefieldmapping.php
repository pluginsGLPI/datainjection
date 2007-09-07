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
// Original Author of file: Dévi Balpe
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");

include("../plugin_data_injection.includes.php");

global $DATAINJECTIONLANG;

if(isset($_POST['id']))
	{
	
	echo "<select name='field[".$_POST['id']."][2]' style='width: 150px'>";
		
	if(isset($_POST['idMapping']))
		{
			if($_POST['idMapping'] == NOT_MAPPED)
				echo "<option value='-1'>".$DATAINJECTIONLANG["mappingStep"][7]."</option>";
			else
				{
					$values = getAllMappingsDefinitionsByType($_POST['idMapping']);

					foreach($values as $key => $value)
						echo "<option value='".$key."'>".$value."</option>";
				}
		}
			
	echo "</select>";
	}
?>
