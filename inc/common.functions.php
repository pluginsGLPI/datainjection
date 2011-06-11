<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2011 by the INDEPNET Development Team.

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
 along with GLPI; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 --------------------------------------------------------------------------
*/

// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------

 function plugin_datainjection_getSessionParam($param) {

   if (!isset($_SESSION['datainjection'][$param])) {
      return false;
   }
   if (in_array($param, array('results', 'error_lines'))) {
      $fic = $_SESSION['datainjection'][$param];
      return file_get_contents(GLPI_DOC_DIR.'/_tmp/'.$fic);
   }
   return $_SESSION['datainjection'][$param];
}


function plugin_datainjection_setSessionParam($param,$results) {

   if (in_array($param, array('results', 'error_lines'))) {
      $fic = getLoginUserID().'_'.$param.'_'.microtime(true);
      file_put_contents(GLPI_DOC_DIR.'/_tmp/'.$fic, $results);
      $_SESSION['datainjection'][$param] = $fic;
   } else {
      $_SESSION['datainjection'][$param] = $results;
   }
}


function plugin_datainjection_removeSessionParams() {

   if (isset($_SESSION['datainjection']['results'])) {
      unlink(GLPI_DOC_DIR.'/_tmp/'.$_SESSION['datainjection']['results']);
   }
   if (isset($_SESSION['datainjection']['error_lines'])) {
      unlink(GLPI_DOC_DIR.'/_tmp/'.$_SESSION['datainjection']['error_lines']);
   }
   unset($_SESSION['datainjection']);
}
?>
