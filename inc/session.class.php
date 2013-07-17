<?php
/*
 * @version $Id$
 LICENSE

 This file is part of the datainjection plugin.

 Datainjection plugin is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Datainjection plugin is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with datainjection. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 @package   datainjection
 @author    the datainjection plugin team
 @copyright Copyright (c) 2010-2013 Datainjection plugin team
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://forge.indepnet.net/projects/datainjection
 @link      http://www.glpi-project.org/
 @since     2009
 ---------------------------------------------------------------------- */

 class PluginDatainjectionSession {

   /**
    * Get a parameter from the HTTP session
    *
    * @param $param  the parameter to get
    *
    * @return the param's value
   **/
   static function getParam($param) {

      if (!isset($_SESSION['datainjection'][$param])) {
         return false;
      }
      if (in_array($param, array('results', 'error_lines'))) {
         $fic = $_SESSION['datainjection'][$param];
         return file_get_contents(GLPI_DOC_DIR.'/_tmp/'.$fic);
      }
      return $_SESSION['datainjection'][$param];
   }


   /**
    * Set a parameter in the HTTP session
    *
    * @param $param     the parameter
    * @param $results   the value to store
    *
    * @return nothing
   **/
   static function setParam($param,$results) {

      if (in_array($param, array('results', 'error_lines'))) {
         $fic = Session::getLoginUserID().'_'.$param.'_'.microtime(true);
         file_put_contents(GLPI_DOC_DIR.'/_tmp/'.$fic, $results);
         $_SESSION['datainjection'][$param] = $fic;
      } else {
         $_SESSION['datainjection'][$param] = $results;
      }
   }


   /**
    * Remove all parameters from the HTTP session
    *
    * @return nothing
    */
   static function removeParams() {

      if (isset($_SESSION['datainjection']['results'])) {
         unlink(GLPI_DOC_DIR.'/_tmp/'.$_SESSION['datainjection']['results']);
      }
      if (isset($_SESSION['datainjection']['error_lines'])) {
         unlink(GLPI_DOC_DIR.'/_tmp/'.$_SESSION['datainjection']['error_lines']);
      }
      unset($_SESSION['datainjection']);
   }

}
?>