<?php
/*
 * @version $Id: rules.constant.php 5351 2007-08-07 11:57:46Z walid $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

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
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */
class PluginDatainjectionInfos extends CommonDBTM {

   var $text;

   function __construct()
   {
      $this->table="glpi_plugin_datainjection_infos";
      $this->type=-1;
      $this->text="";
   }

   function isMandatory()
   {
      return $this->fields["mandatory"];
   }

   function getInfosText()
   {
      return $this->text;
   }

   function getValue()
   {
      return $this->fields["value"];
   }

   function getID()
   {
      return $this->fields["ID"];
   }

   function getModelID()
   {
      return $this->fields["model_id"];
   }

   function getInfosType()
   {
      return $this->fields["type"];
   }

   function setMandatory($mandatory)
   {
      $this->fields["mandatory"] = $mandatory;
   }

   function setInfosText($text)
   {
      $this->text = $text;
   }

   function setValue($value)
   {
      $this->fields["value"] = $value;
   }

   function setID($ID)
   {
      $this->fields["ID"] = $ID;
   }

   function setModelID($model_id)
   {
      $this->fields["model_id"] = $model_id;
   }

   function setInfosType($type)
   {
      $this->fields["type"] = $type;
   }
}
?>