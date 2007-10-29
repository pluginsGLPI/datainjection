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
function parseLine($fic,$data,$encoding=1)
{
   	$csv = array();
   	$num = count($data);
   	for ($c=0; $c < $num; $c++)
   	{
       	//If field is not the last, or if field is the last of the line and is not empty
       	if ($c < ($num -1) || ($c == ($num -1) && $c[$num] != EMPTY_VALUE))
       	{
	       	switch ($encoding)
	       	{
		       	//If file is ISO8859-1 : encode the datas in utf8
	       		case ENCODING_ISO8859_1:
	       			$csv[0][]=utf8_encode(addslashes($data[$c]));
	       			break;
	       		case ENCODING_UFT8:
	       			$csv[0][]=addslashes($data[$c]);
	       			break;
	       		case ENCODING_AUTO:
	       			$csv[0][]=toUTF8(addslashes($data[$c]));
	       			break;				
	       	}
       	}
   	}
    return $csv;	
}
?>