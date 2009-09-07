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

// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------

include_once("inc/plugin_datainjection.common.constant.php");
//include_once("inc/plugin_datainjection.mapping.constant.php");
include_once("inc/plugin_datainjection.infos.constant.php");
include_once("inc/plugin_datainjection.backend.class.php");
include_once("inc/plugin_datainjection.backend.function.php");
include_once("inc/plugin_datainjection.config.function.php");
include_once("inc/plugin_datainjection.backend.csv.class.php");
include_once("inc/plugin_datainjection.backend.csv.function.php");
include_once("inc/plugin_datainjection.wizard.function.php");
include_once("inc/plugin_datainjection.engine.class.php");
include_once("inc/plugin_datainjection.engine.function.php");
include_once("inc/plugin_datainjection.engine.connections.function.php");
include_once("inc/plugin_datainjection.model.class.php");
include_once("inc/plugin_datainjection.model.csv.class.php");
include_once("inc/plugin_datainjection.model.function.php");
include_once("inc/plugin_datainjection.infos.class.php");
include_once("inc/plugin_datainjection.infos.function.php");
include_once("inc/plugin_datainjection.mapping.class.php");
include_once("inc/plugin_datainjection.mapping.function.php");
include_once("inc/plugin_datainjection.type.class.php");
include_once("inc/plugin_datainjection.type.function.php");
include_once("inc/plugin_datainjection.profiles.class.php");
include_once("config/plugin_datainjection.config.php");
include_once("inc/plugin_datainjection.results.class.php");
include_once("inc/plugin_datainjection.results.function.php");
include_once("inc/plugin_datainjection.log.function.php");
include_once("inc/plugin_datainjection.checks.function.php");
include_once("inc/plugin_datainjection.dropdown.function.php");
include_once("inc/plugin_datainjection.reformat.function.php");
include_once("inc/plugin_datainjection.device.function.php");
?>
