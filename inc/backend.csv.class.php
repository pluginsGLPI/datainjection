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
// Purpose of file:
// ----------------------------------------------------------------------
class PluginDatainjectionBackendcsv extends PluginDatainjectionBackend{

   static function parseLine($fic,$data,$encoding=1)
   {
         $csv = array();
         $num = count($data);
         for ($c=0; $c < $num; $c++)
         {
            //If field is not the last, or if field is the last of the line and is not empty

            if ($c < ($num -1) || ($c == ($num -1) && $data[$num-1] != EMPTY_VALUE))
            {
               switch ($encoding)
               {
                  //If file is ISO8859-1 : encode the datas in utf8
                  case ENCODING_ISO8859_1:
                     $csv[0][]=utf8_encode(addslashes($data[$c]));
                     break;
                  case ENCODING_UFT8:
                     $csv[0][]=addslashes($data[$c]);
                     break;
                  case ENCODING_AUTO:
                     $csv[0][]=PluginDatainjectionBackend::toUTF8(addslashes($data[$c]));
                     break;
               }
            }
         }
       return $csv;
   }

    function PluginDatainjectionBackendcsv() {
      $this->injectionDatas = new PluginDatainjectionInjectionDatas;
      $this->errmsg = "";
    }

   function initPluginDatainjectionBackend($newfile,$delimiter,$encoding)
   {
      $this->file = $newfile;
      $this->delimiter = $delimiter;
      $this->encoding = $encoding;
   }
   function read()
   {
      $fic = fopen($this->file, 'r');

      while (($data = fgetcsv($fic, 3000, $this->delimiter)) !== FALSE)
      {
         //If line is not empty
         if (count($data) > 1 || $data[0] != EMPTY_VALUE)
         {
            $line = PluginDatainjectionBackendcsv::parseLine($fic,$data,$this->encoding);
            if (count($line[0]) > 0)
               $this->injectionDatas->addToDatas($line);
         }
      }
      fclose($fic);
   }

   function deleteFile()
   {
      unlink($this->file);
   }

   function export($file, $model, $tab_result)
   {
      $tmpfile = fopen($file, "w");

      $header = $this->getHeader($model->isHeaderPresent());

      fputcsv($tmpfile, $header, $model->getDelimiter());

      foreach($tab_result[0] as $value)
         {
         $list = $this->getDataAtLine($value->getLineID());

         fputcsv($tmpfile, $list, $model->getDelimiter());
         }

      fclose($tmpfile);
   }

   function readLinesFromTo($start_line, $end_line)
   {
      $row = 0;
      $fic = fopen($this->file, 'r');

      while ((($data = fgetcsv($fic, 3000, $this->delimiter)) !== FALSE) && $row <= $end_line) {
         if ($row >= $start_line && $row <= $end_line)
            $this->injectionDatas->addToDatas(PluginDatainjectionBackendcsv::parseLine($fic,$data));
         $row++;
      }


      fclose($fic);

   }

   /*
    * Try to parse an input file
    * @return true if the file is a CSV file
    */
   function isFileCorrect($model)
   {
      global $LANG;
      $this->clearError();
      $header = $this->getHeader($model->isHeaderPresent());

      if (count($model->getMappings()) != count($header)) {
         $this->setError(count($model->getMappings()) ." ".$LANG["datainjection"]["saveStep"][16]."\n" . count($header)." ".$LANG["datainjection"]["saveStep"][17]);
         return 1;
      }

      if (!$model->isHeaderPresent())
         return 0;

      $check = 0;

      foreach ($model->getMappings() as $key => $mapping)
      {
         if (!isset($header[$key])) {
            $this->setError($key);
            $check = 2;
         }
         else if (trim(strtoupper(stripslashes($header[$mapping->getRank()]))) != trim(strtoupper(stripslashes($mapping->getName())))) {
            $this->setError($LANG["datainjection"]["saveStep"][18].stripslashes($header[$mapping->getRank()])."\n". $LANG["datainjection"]["saveStep"][19].stripslashes($mapping->getName()));
            $check = 2;
         }
      }
      return $check;
   }
}

?>