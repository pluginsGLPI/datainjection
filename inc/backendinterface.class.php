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

/**
 * Interface to be implemented for each injection backend
**/
interface PluginDatainjectionBackendInterface {


   /**
    * Read from file
  **/
   function read($numberOfLines = 1);


   /**
    * Read n lines from the input files
   */
   function readLinesFromTo($start_line, $end_line);

   /**
    * Delete file
   **/
   function deleteFile();


   /**
    * Export results to a file
   **/
   //function export($file, PluginDatainjectionModel $model, $tab_result);


   /**
    * Store the number of lines red from the file
   **/
   function storeNumberOfLines();


   /**
    * Get the number of lines in the file
   **/
   function getNumberOfLines();

}

?>