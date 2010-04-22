<?php
/*
 * @version $Id$
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
class PluginDatainjectionInjectionType {

   static function searchTypes() {
      global $DB,$CFG_GLPI;

      $tab = array ();
      foreach ($DB->request('glpi_plugins', array('state'=>Plugin::ACTIVATED)) as $plug) {
         $dir = GLPI_ROOT.'/plugins/'.$plug['directory'].'/datainjection/*';
         foreach (glob($dir) as $path) {
            $name = PluginDatainjectionInjectionType::getTypeNameByFileName($plug['directory'],
                                                                            $path);
            $tab[basename($name)] = $plug['directory'];
         }
      }

      return $tab;
   }

   static function getTypeNameByFileName($plugin,$filename) {
      $results = array();
      $dir = str_replace('/','\/',GLPI_ROOT.'/plugins/'.$plugin.'/datainjection/');
      if (preg_match("/$dir(.*)injection.class.php/i",$filename,$results)) {
         $type = new $results[1];
         return ucfirst($type->getTypeName());
      }
   }

   static function dropdown($value='') {
      $values = array();
      $types = PluginDatainjectionInjectionType::searchTypes();
      foreach ($types as $type => $plugin) {
         $values[] = $type;
      }
      Dropdown::showFromArray('itemtype',$values,$value);
   }
}
?>