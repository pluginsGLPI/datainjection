<?php
/*
 * @version $Id: HEADER 14684 2011-06-11 06:32:40Z remi $
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
/*
 * Common backend to read files to import
 */
abstract class PluginDatainjectionBackend {

   private $file = "";
   private $delimiter;
   private $encoding;
   private $errmsg;
   private $numberOfLines = 0;

   const ENCODING_ISO8859_1 = 0;
   const ENCODING_UFT8      = 1;
   const ENCODING_AUTO      = 2;


   /**
    * Get header of the file
    *
    * @param $injectionData
    * @param $header_present
    *
    * @return array with the data from the header
   **/
   static function getHeader(PluginDatainjectionData $injectionData, $header_present) {

      if ($header_present) {
         return $injectionData->getDataAtLine(0);
      }

      $nb = count($injectionData->getDataAtLine(0));
      for ($i=0 ; $i<$nb ; $i++) {
         $header[] = $i;
      }
      return $header;
   }


   /**
    * Get the backend implementation by type
    *
    * @param $type
   **/
   static function getInstance($type) {

      $class = 'PluginDatainjectionBackend'.$type;
      return new $class();
   }


   static function is_utf8($string) {

       // From http://w3.org/International/questions/qa-forms-utf-8.html
       return preg_match('%^(?:
             [\x09\x0A\x0D\x20-\x7E]            # ASCII
           | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
           |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
           | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
           |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
           |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
           | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
           |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
       )*$%xs', $string);
   }


   static function toUTF8($string) {

      if (!self::is_utf8($string)) {
         return utf8_encode($string);
      }
      return $string;
   }

}
?>
