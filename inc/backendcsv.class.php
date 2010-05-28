<?php

/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

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
 ------------------------------------------------------------------------
*/

// Original Author of file: Walid Nouh
// Purpose of file: read a CSV file
// ----------------------------------------------------------------------
class PluginDatainjectionBackendcsv extends PluginDatainjectionBackend
                                    implements PluginDatainjectionBackendInterface {

   private $delemiter = '';
   private $isHeaderPresent = true;
   private $file_handler = null;

   function __construct() {
      $this->errmsg= "";
   }

   //Getters & setters
   function getDelimiter() {
      return $this->delimiter;
   }

   function isHeaderPresent() {
      return $this->isHeaderPresent;
   }

   function setDelimiter($delimiter) {
      $this->delimiter = $delimiter;
   }

   function setHeaderPresent($present = true) {
      $this->isHeaderPresent = $present;
   }


   //CSV File parsing methods
   static function parseLine($fic, $data, $encoding= 1) {
      $csv= array();
      $num= count($data);
      for($c= 0; $c < $num; $c++) {
         //If field is not the last, or if field is the last of the line and is not empty

         if($c <($num -1)
               || ($c ==($num -1)
                  && $data[$num -1] != PluginDatainjectionCommonInjectionLib::EMPTY_VALUE)) {
            switch($encoding) {
               //If file is ISO8859-1 : encode the datas in utf8
               case PluginDatainjectionBackend :: ENCODING_ISO8859_1 :
                  $csv[0][]= utf8_encode(addslashes($data[$c]));
                  break;
               case PluginDatainjectionBackend :: ENCODING_UFT8 :
                  $csv[0][]= addslashes($data[$c]);
                  break;
               case PluginDatainjectionBackend :: ENCODING_AUTO :
                  $csv[0][]= PluginDatainjectionBackend :: toUTF8(addslashes($data[$c]));
                  break;
            }
         }
      }
      return $csv;
   }

   function init($newfile,$encoding) {
      $this->file= $newfile;
      $this->encoding= $encoding;
   }

   /**
    * Read a CSV file and store data in an array
    * @param numberOfLines inumber of lines to be read (-1 means all file)
    *
    */
   function read($numberOfLines = 1) {
      $injectionData = new PluginDatainjectionData;
      $this->openFile();
      $continue = true;
      $data = false;

      for ($index = 0; $index < $numberOfLines && $continue; $index++) {
         $data = $this->getNextLine();
         if ($data) {
            $injectionData->addToDatas($data);
         }
         else {
            $continue = false;
         }
      }
      $this->closeFile();
      return $injectionData;
   }


   /**
    * Read a CSV file and store data in an array
    * @param numberOfLines inumber of lines to be read (-1 means all file)
    *
    */
   function storeNumberOfLines() {
      $fic= fopen($this->file, 'r');

      $index = 0;
      while(($data= fgetcsv($fic,0,$this->getDelimiter())) !== FALSE) {
         //If line is not empty
         if(count($data) > 1 || $data[0] != PluginDatainjectionCommonInjectionLib::EMPTY_VALUE) {
            $line= self::parseLine($fic, $data, $this->encoding);
            if(count($line[0]) > 0) {
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
    */
   function openFile() {
      $this->file_handler = fopen($this->file, 'r');
   }

   /**
    * Close the csv file
    */
   function closeFile() {
      fclose($this->file_handler);
   }

   /**
    * Read next line of the csv file
    */
   function getNextLine() {
      $data= fgetcsv($this->file_handler,0,$this->getDelimiter());
      if ($data === FALSE) {
         return false;
      }
      else {
         $line = array();
         if(count($data) > 1 || $data[0] != PluginDatainjectionCommonInjectionLib::EMPTY_VALUE) {
            $line= self::parseLine($this->file_handler, $data, $this->encoding);
         }
         return $line;
      }
   }

   /**
    * Delete csv file from disk
    */
   function deleteFile() {
      unlink($this->file);
   }

   function export($file, PluginDatainjectionModel $model, $tab_result) {
      $tmpfile= fopen($file, "w");

      $header= $this->getHeader($model->isHeaderPresent());

      fputcsv($tmpfile, $header, $this->getDelimiter());

      foreach($tab_result[0] as $value) {
         $list= $this->getDataAtLine($value->getLineID());

         fputcsv($tmpfile, $list, $this->getDelimiter());
      }

      fclose($tmpfile);
   }

   function readLinesFromTo($start_line, $end_line) {
      $row= 0;
      $fic= fopen($this->file, 'r');
      $injectionData = new PluginDatainjectionData;

      while((($data= fgetcsv($fic, 3000, $this->delimiter)) !== FALSE) && $row <= $end_line) {
         if($row >= $start_line && $row <= $end_line)
            $injectionData->addToDatas(self :: parseLine($fic,$data));
         $row++;
      }

      fclose($fic);
      return $injectionData;
   }
}
?>