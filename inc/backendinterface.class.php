<?php

/**
 * -------------------------------------------------------------------------
 * DataInjection plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of DataInjection.
 *
 * DataInjection is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * DataInjection is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DataInjection. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2007-2022 by DataInjection plugin team.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/pluginsGLPI/datainjection
 * -------------------------------------------------------------------------
 */

/**
 * Interface to be implemented for each injection backend
**/
interface PluginDatainjectionBackendInterface
{


    /**
    * Read from file
    *
    * @param $numberOfLines (default 1)
   **/
    function read($numberOfLines = 1);


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
