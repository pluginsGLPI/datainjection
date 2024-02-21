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
 * @copyright Copyright (C) 2007-2023 by DataInjection plugin team.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/pluginsGLPI/datainjection
 * -------------------------------------------------------------------------
 */

class PluginDatainjectionBackendcsv extends PluginDatainjectionBackend implements PluginDatainjectionBackendInterface
{
    private $isHeaderPresent = true;
    private $file_handler    = null;


    public function __construct()
    {

        $this->errmsg = "";
    }


    //Getters & setters
    public function getDelimiter()
    {

        return $this->delimiter;
    }


    public function isHeaderPresent()
    {

        return $this->isHeaderPresent;
    }


    /**
    * @param $delimiter
   **/
    public function setDelimiter($delimiter)
    {

        $this->delimiter = $delimiter;
    }


    /**
    * @param $present (true by default)
   **/
    public function setHeaderPresent($present = true)
    {

        $this->isHeaderPresent = $present;
    }


    /**
    * CSV File parsing methods
    *
    * @param $fic
    * @param $data
    * @param $encoding  (default 1)
   **/
    public static function parseLine($fic, $data, $encoding = 1)
    {
        /** @var DBmysql $DB */
        global $DB;

        $csv = [];
        $num = count($data);

        for ($c = 0; $c < $num; $c++) {
           //If field is not the last, or if field is the last of the line and is not empty

            if (
                ($c < ($num - 1))
                || (($c == ($num - 1))
                && ($data[$num - 1] != PluginDatainjectionCommonInjectionLib::EMPTY_VALUE))
            ) {
                $tmp = trim($DB->escape($data[$c]));
                switch ($encoding) {
                  //If file is ISO8859-1 : encode the data in utf8
                    case PluginDatainjectionBackend::ENCODING_ISO8859_1:
                        if (!Toolbox::seems_utf8($tmp)) {
                            $csv[0][] = utf8_encode($tmp);
                        } else {
                            $csv[0][] = $tmp;
                        }
                        break;

                    case PluginDatainjectionBackend::ENCODING_UFT8:
                         $csv[0][] = $tmp;
                        break;

                    default: //PluginDatainjectionBackend :: ENCODING_AUTO :
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
    public function init($newfile, $encoding)
    {

        $this->file     = $newfile;
        $this->encoding = $encoding;
    }


    /**
    * Read a CSV file and store data in an array
    *
    * @param $numberOfLines inumber of lines to be read (-1 means all file) (default 1)
   **/
    public function read($numberOfLines = 1)
    {

        $injectionData = new PluginDatainjectionData();
        $this->openFile();
        $continue = true;
        $data     = false;

        for ($index = 0; (($numberOfLines == -1) || ($index < $numberOfLines)) && $continue; $index++) {
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
    public function storeNumberOfLines()
    {

        $fic = fopen($this->file, 'r');

        $index = 0;
        while (($data = fgetcsv($fic, 0, $this->getDelimiter())) !== false) {
           //If line is not empty
            if (
                (count($data) > 1)
                || ($data[0] != PluginDatainjectionCommonInjectionLib::EMPTY_VALUE)
            ) {
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


    public function getNumberOfLines()
    {

        return $this->numberOfLines;
    }

    /**
    * Open the csv file
   **/
    public function openFile()
    {

        $this->file_handler = fopen($this->file, 'r');

       // Check if file starts with BOM.
       // 1. If BOM found, keep the handler moved to 4th char to not include it in data.
       // 2. If no BOM found, rewind to start of file.
        $hasBOM = fread($this->file_handler, 3) === pack('CCC', 0xEF, 0xBB, 0xBF);
        if (!$hasBOM) {
            fseek($this->file_handler, 0);
        }
    }


    /**
    * Close the csv file
   **/
    public function closeFile()
    {

        fclose($this->file_handler);
    }


    /**
    * Read next line of the csv file
   **/
    public function getNextLine()
    {

        $data = fgetcsv($this->file_handler, 0, $this->getDelimiter());
        if ($data === false) {
            return false;
        }
        $line = [];
        if (
            (count($data) > 1)
            || ($data[0] != PluginDatainjectionCommonInjectionLib::EMPTY_VALUE)
        ) {
            $line = self::parseLine($this->file_handler, $data, $this->encoding);
        }
        return $line;
    }


    /**
    * Delete csv file from disk
   **/
    public function deleteFile()
    {

        unlink($this->file);
    }
}
