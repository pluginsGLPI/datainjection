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
class PluginDatainjectionClientInjection {

   /**
    * Print a good title for group pages
    *
    *@return nothing (display)
    **/
   function title() {
      global $LANG, $CFG_GLPI;

      $buttons = array ();
      $title = "";
      if (plugin_datainjection_haveRight("model", "w") ) {
         $url = getItemTypeSearchURL('PluginDatainjectionModel');
         $buttons[$url] = $LANG["datainjection"]["profiles"][1];
         $title="";
      }
      displayTitle($CFG_GLPI["root_doc"] . "/pics/groupes.png", $LANG['Menu'][36], $title, $buttons);
   }

   function showForm($ID, $options=array()) {

   }
}
?>