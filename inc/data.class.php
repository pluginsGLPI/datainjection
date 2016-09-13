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

class PluginDatainjectionData {

   private $injectionData;


   function __construct() {
      $injectionData = array();
   }


   /**
    * @param $newData
   **/
   function addToData($newData) {
      $this->injectionData[] = $newData;
   }


   function getData() {
      return $this->injectionData;
   }


   /**
    * @param $line_id
   **/
   function getDataAtLine($line_id) {

      if (count($this->injectionData) >= $line_id) {
         return $this->injectionData[$line_id][0];
      }
      return array();
   }

}
?>
