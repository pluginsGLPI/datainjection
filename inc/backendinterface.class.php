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

/**
 * Interface to be implemented for each injection backend
**/
interface PluginDatainjectionBackendInterface {


   /**
    * Read from file
    *
    * @param $numberOfLines (default 1)
   **/
   function read($numberOfLines=1);


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