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
 class PluginDatainjectionBackendcsv extends PluginDatainjectionBackend
                                    implements PluginDatainjectionBackendInterface {

   private $delimiter       = '';
   private $isHeaderPresent = true;
   private $file_handler    = null;


   function __construct() {
      $this->errmsg = "";
   }


   //Getters & setters
   function getDelimiter() {
      return $this->delimiter;
   }


   function isHeaderPresent() {
      return $this->isHeaderPresent;
   }


   /**
    * @param $delimiter
   **/
   function setDelimiter($delimiter) {
      $this->delimiter = $delimiter;
   }


   /**
    * @param $present (true by default)
   **/
   function setHeaderPresent($present=true) {
      $this->isHeaderPresent = $present;
   }


   /**
    * CSV File parsing methods
    *
    * @param $fic
    * @param $data
    * @param $encoding  (default 1)
   **/
   static function parseLine($fic, $data, $encoding=1) {
      global $DB;

      $csv = array();
      $num = count($data);

      for($c=0 ; $c<$num ; $c++) {
         //If field is not the last, or if field is the last of the line and is not empty

         if (($c < ($num -1))
             || (($c == ($num -1))
                 && ($data[$num -1] != PluginDatainjectionCommonInjectionLib::EMPTY_VALUE))) {
            $tmp = trim($DB->escape($data[$c]));
            switch ($encoding) {
               //If file is ISO8859-1 : encode the data in utf8
               case PluginDatainjectionBackend::ENCODING_ISO8859_1 :
                  if (!Toolbox::seems_utf8($tmp)) {
                     $csv[0][] = utf8_encode($tmp);
                  } else {
                     $csv[0][] = $tmp;
                  }
                  break;

               case PluginDatainjectionBackend::ENCODING_UFT8 :
                  $csv[0][] = $tmp;
                  break;

               default : //PluginDatainjectionBackend :: ENCODING_AUTO :
                  $csv[0][] = PluginDatainjectionBackend::toUTF8($tmp);
            }
         }
      }
      return $csv;
   }


   /**
    * @param $newfile
    * @param $encoding
   **/
   function init($newfile,$encoding) {

      $this->file     = $newfile;
      $this->encoding = $encoding;
   }


   /**
    * Read a CSV file and store data in an array
    *
    * @param $numberOfLines inumber of lines to be read (-1 means all file) (default 1)
   **/
   function read($numberOfLines=1) {

      $injectionData = new PluginDatainjectionData();
      $this->openFile();
      $continue = true;
      $data     = false;

      for ($index = 0 ; (($numberOfLines == -1) || ($index < $numberOfLines)) && $continue ; $index++) {
         $data = $this->getNextLine();
         if ($data) {
            $injectionData->addToData($data);
         } else {
            $continue = false;
         }
      }

      $this->closeFile();
      return $injectionData;
   }


   /**
    * Store the number of lines red from the file
    *
    * @see plugins/datainjection/inc/PluginDatainjectionBackendInterface::storeNumberOfLines()
   **/
   function storeNumberOfLines() {

      $fic = fopen($this->file, 'r');

      $index = 0;
      while (($data = fgetcsv($fic, 0, $this->getDelimiter())) !== FALSE) {
         //If line is not empty
         if ((count($data) > 1)
             || ($data[0] != PluginDatainjectionCommonInjectionLib::EMPTY_VALUE)) {

            $line = self::parseLine($fic, $data, $this->encoding);
            if (count($line[0]) > 0) {
               $index++;
            }
         }
      }
      fclose($fic);

      if ($this->isHeaderPresent) {
         $index--;
      }

      $this->numberOfLines = $index;
   }


   function getNumberOfLines() {
      return $this->numberOfLines;
   }

   /**
    * Open the csv file
   **/
   function openFile() {
      $this->file_handler = fopen($this->file, 'r');
   }


   /**
    * Close the csv file
   **/
   function closeFile() {
      fclose($this->file_handler);
   }


   /**
    * Read next line of the csv file
   **/
   function getNextLine() {

      $data = fgetcsv($this->file_handler, 0, $this->getDelimiter());
      if ($data === FALSE) {
         return false;
      }
      $line = array();
      if ((count($data) > 1)
          || ($data[0] != PluginDatainjectionCommonInjectionLib::EMPTY_VALUE)) {
         $line = self::parseLine($this->file_handler, $data, $this->encoding);
      }
      return $line;
   }


   /**
    * Delete csv file from disk
   **/
   function deleteFile() {
      unlink($this->file);
   }

}
?>
