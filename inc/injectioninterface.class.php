<?php
/*
 * @version $Id$
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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Interface to implement for each type to inject
 */
interface PluginDatainjectionInjectionInterface {

   /**
    * Tells datainjection is the type is a primary type or not
    * @param none
    * @return a boolean
    */
   function isPrimaryType();

   /**
    * Indicates to with other types it can be connected
    * @param none
    * @return an array of GLPI types
    */
   function connectedTo();

   /**
    * Function which calls getSearchOptions and add more parameters specific to display
    * @param none
    * @return an array of search options, as defined in each commondbtm object
    */
   function getOptions();

   /**
    * Manage display of additional informations
    * @param info an array which contains the additional information values
    */
   function showAdditionalInformation($info = array());
}
?>