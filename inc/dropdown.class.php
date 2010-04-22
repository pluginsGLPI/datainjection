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

class PluginDatainjectionDropdown {
   static function getDropdownMinimalName ($table, $id)
   {
      global $DB,$LANG;

      if ($table == "glpi_entities")
      {
         if ($id==0)
            return $LANG["entity"][2];
         elseif ($id==-1)
            return $LANG["common"][77];
      }

      $name = EMPTY_VALUE;
      if ($id>0){
         $query = "SELECT name FROM ". $table ." WHERE ID=$id";
         if ($result = $DB->query($query)){
            if($DB->numrows($result) > 0) {
               $data=$DB->fetch_assoc($result);
               $name = $data["name"];
            }
         }
      }
      return addslashes($name);
   }

   /*
    * Get the ID of an element in a dropdown table, create it if the value doesn't exists and if user has the right
    * @param mapping the mapping informations
    * @param mapping_definition the definition of the mapping
    * @param value the value to add
    * @param entity the active entity
    * @return the ID of the insert value in the dropdown table
    */
   static function getDropdownValue($mapping, $mapping_definition,$value,$entity,$canadd=0,$dropdown_comments=EMPTY_VALUE)
   {
      global $DB, $CFG_GLPI;

      if (empty ($value))
         return 0;

         $rightToAdd = haveRightDropdown($mapping_definition["table"],$canadd);

         //Value doesn't exists -> add the value in the dropdown table
         switch ($mapping_definition["table"])
         {
            case "glpi_dropdown_locations":
               return checkLocation($value,$entity,$rightToAdd,$dropdown_comments);
            case "glpi_dropdown_netpoint":
               // not handle here !
               return EMPTY_VALUE;
            break;
            default:
               $input["value2"] = EMPTY_VALUE;
               break;
         }

         $input["tablename"] = $mapping_definition["table"];
         $input["value"] = $value;
         $input["FK_entities"] = $entity;
         $input["type"] = EMPTY_VALUE;
         $input["comments"] = $dropdown_comments;

         $ID = getDropdownID($input);
         if ($ID != -1)
            return $ID;
         else if ($rightToAdd)
            return addDropdown($input);
         else
            return EMPTY_VALUE;
   }

   static function dropdownTemplate($name,$entity,$table,$value='')
   {
      global $DB;
      $result = $DB->query("SELECT tplname, ID FROM ".$table." WHERE FK_entities=".$entity." AND tplname <> '' GROUP BY tplname ORDER BY tplname");

      $rand=mt_rand();
      echo "<select name='$name' id='dropdown_".$name.$rand."'>";

      echo "<option value='0'".($value==0?" selected ":"").">-------------</option>";

      while ($data = $DB->fetch_array($result))
         echo "<option value='".$data["ID"]."'".($value==$data["tplname"]?" selected ":"").">".$data["tplname"]."</option>";

      echo "</select>";
      return $rand;
   }

   static function dropdownDateFormat($format)
   {
      global $LANG;
      $date_format[PluginDatainjectionModel::DATE_TYPE_DDMMYYYY]=$LANG["datainjection"]["model"][22];
      $date_format[PluginDatainjectionModel::DATE_TYPE_MMDDYYYY]=$LANG["datainjection"]["model"][23];
      $date_format[PluginDatainjectionModel::DATE_TYPE_YYYYMMDD]=$LANG["datainjection"]["model"][24];

      Dropdown::showFromArray('date_format',$date_format,array('value'=>$format));
   }

   static function floatFormats() {
      global $LANG;
      $float_format[PluginDatainjectionModel::FLOAT_TYPE_DOT]=$LANG["datainjection"]["model"][25];
      $float_format[PluginDatainjectionModel::FLOAT_TYPE_COMMA]=$LANG["datainjection"]["model"][26];
      $float_format[PluginDatainjectionModel::FLOAT_TYPE_DOT_AND_COM]=$LANG["datainjection"]["model"][27];
      return $float_format;
   }

   static function getFloatFormat($format) {
      $formats = PluginDatainjectionDropdown::floatFormats();
      if (isset($formats[$format])) {
         return $formats[$format];
      }
      else {
         return "";
      }
   }
   static function dropdownFloatFormat($format)
   {

      Dropdown::showFromArray('float_format',
                              PluginDatainjectionDropdown::floatFormats(),
                              array('value'=>$format));
   }

/*
   static function dropdownPrimaryTypeSelection($name,$model=null,$disable=false)
   {
      echo "<select name='$name' ".($disable?"style='background-color:#e6e6e6' disabled":"").">";

      $default_value=($model==null?0:$model->getDeviceType());

      foreach(getAllPrimaryTypes() as $type)
         echo "<option value='".$type[1]."' ".(($default_value == $type[1])?"selected":"").">".$type[0]."</option>";

      echo "</select>";
   }
*/
   static function dropdownFileTypes($value)
   {
      global $LANG;
      $values['csv'] = "CSV";
      Dropdown::showFromArray('filetype',$values,array('value'=>$value));
   }

/*
   static function dropdownModels($disable=false,$models,$with_select=true)
   {
         $nbmodel = count($models);
         if ($with_select)
         {
            if ($disable)
               echo "\n<select style='background-color:#e6e6e6' disabled name='dropdown' id='dropdown' onchange='show_comments($nbmodel)'>";
            else
               echo "\n<select name='dropdown' id='dropdown' onchange='show_comments($nbmodel)'>";
         }

         $prev = -2;

         foreach($models as $model)
         {
            if ($model->getEntity() != $prev) {
               if ($prev >= -1) {
                  echo "</optgroup>\n";
               }
               $prev = $model->getEntity();
               echo "\n<optgroup label=\"" . getDropdownMinimalName("glpi_entities", $prev) . "\">";
            }
            echo "\n<option value='".$model->getModelID()."'>".$model->getModelName()." , ".getDropdownName('glpi_plugin_datainjection_filetype',$model->getModelType())."</option>";
         }

         if ($prev >= -1) {
            echo "</optgroup>";
         }


         if ($with_select)
            echo "</select>\n";

   }
*/
   static function dropdownFileEncoding()
   {
      global $LANG;
      $values[PluginDatainjectionBackend::ENCODING_AUTO]=$LANG["datainjection"]["fileStep"][10];
      $values[PluginDatainjectionBackend::ENCODING_UFT8]=$LANG["datainjection"]["fileStep"][11];
      $values[PluginDatainjectionBackend::ENCODING_ISO8859_1]=$LANG["datainjection"]["fileStep"][12];
      Dropdown::showFromArray('file_encoding',$values,array('value'=>PluginDatainjectionBackend::ENCODING_AUTO));
   }

   static function portUnicityValues() {
      global $LANG;
      $values[PluginDatainjectionModel::UNICITY_NETPORT_LOGICAL_NUMBER] = $LANG["networking"][21];
      $values[PluginDatainjectionModel::UNICITY_NETPORT_NAME] = $LANG["common"][16];
      $values[PluginDatainjectionModel::UNICITY_NETPORT_MACADDRESS] = $LANG["device_iface"][2];
      $values[PluginDatainjectionModel::UNICITY_NETPORT_LOGICAL_NUMBER_NAME] =
                                                   $LANG["networking"][21]."+".$LANG["common"][16];
      $values[PluginDatainjectionModel::UNICITY_NETPORT_LOGICAL_NUMBER_MAC]=
                                              $LANG["networking"][21]."+".$LANG["device_iface"][2];
      $values[PluginDatainjectionModel::UNICITY_NETPORT_LOGICAL_NUMBER_NAME_MAC]=
                      $LANG["networking"][21]."+".$LANG["common"][16]."+".$LANG["device_iface"][2];
      return $values;
   }
   static function dropdownPortUnicity($value)
   {
      global $LANG;
      $values = PluginDatainjectionDropdown::portUnicityValues();
      Dropdown::showFromArray('port_unicity',$values,array('value'=>$value));
   }

   static function getPortUnitictyValues($value) {
      $values = PluginDatainjectionDropdown::portUnicityValues();
      if (isset($values[$value])) {
         return $values[$value];
      }
      else {
         return "";
      }
   }
}
?>
