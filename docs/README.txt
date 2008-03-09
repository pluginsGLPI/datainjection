/*
 * @version $Id: setup.php,v 1.2 2006/04/02 14:45:27 moyo Exp $
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
// Original Author of file: Walid NOUH
// Purpose of file:
// ----------------------------------------------------------------------

Author :Walid NOUH

1 - Presentation

This plugin enables you inject datas from CSV files into GLPI. 

Compatibility 0.71
PHP5 or higher is required !

2 - Installation

RPM are available in Fedora/EPEL repository
	yum install glpi-data-injection

Else
	Download the tarball of this plugin
	Uncompress it in the plugins directory of your glpi installation

Once copied in the repertory, go the the "Configuration"->"Plugins"->"File injection" menu, and install the plugin

