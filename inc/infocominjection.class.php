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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginDatainjectionInfocomInjection extends Infocom
                                          implements PluginDatainjectionInjectionInterface {


   static function getTable() {

      $parenttype = get_parent_class();
      return $parenttype::getTable();
   }


   function isPrimaryType() {
      return false;
   }


   function connectedTo() {
      global $CFG_GLPI;

      return $CFG_GLPI["infocom_types"];
   }


   /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
   **/
   function getOptions($primary_type='') {

      $tab                    = Search::getOptions(get_parent_class($this));

      $tab[4]['checktype']    = 'date';
      $tab[5]['checktype']    = 'date';

      //Warranty_duration
      $tab[6]['minvalue']     = 0;
      $tab[6]['maxvalue']     = 120;
      $tab[6]['step']         = 1;
      $tab[6]['-1']           = __('Lifelong');
      $tab[6]['checktype']    = 'integer';

      $tab[8]['checktype']    = 'float';

      $tab[13]['checktype']   = 'float';

      $tab[14]['minvalue']    = 0;
      $tab[14]['maxvalue']    = 15;
      $tab[14]['step']        = 1;
      $tab[14]['checktype']   = 'integer';

      $tab[17]['size']        = 14;
      $tab[17]['default']     = 0;
      $tab[17]['checktype']   = 'integer';

      $tab[15]['minvalue']    = 0;
      $tab[15]['maxvalue']    = 2;
      $tab[15]['step']        = 1;
      $tab[15]['checktype']   = 'integer';

      $tab[23]['checktype']   = 'date';
      $tab[24]['checktype']   = 'date';
      $tab[25]['checktype']   = 'date';
      $tab[26]['checktype']   = 'date';

      //Remove some options because some fields cannot be imported
      $blacklist     = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
      $notimportable = array(20, 21, 86);

      $options['ignore_fields'] = array_merge($blacklist, $notimportable);

      $key                      = array_search(19, $options['ignore_fields']);
      unset($options['ignore_fields'][$key]);

      $options['displaytype']   = array("date"             => array(4, 5, 23, 24, 25, 26),
                                        "dropdown"         => array(6, 9, 19),
                                        "dropdown_integer" => array(6, 14),
                                        "decimal"          => array(8, 13, 17),
                                        "sink_type"        => array(15),
                                        "alert"            => array(22),
                                        "multiline_text"   => array(16));

      return PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
   }


   /**
    * @param $info      array
    * @param $option    array
   **/
   function showAdditionalInformation($info=array(), $option=array()) {

      $name = "info[".$option['linkfield']."]";

      switch ($option['displaytype']) {
         case 'sink_type' :
            Infocom::dropdownAmortType($name);
            break;

         case 'alert' :
            Infocom::dropdownAlert(array('name' => $name));
            break;

         default:
            break;
      }
   }


   /**
    * @param $values    array
   **/
   function reformat(&$values=array()) {

      foreach (array('order_date', 'use_date', 'buy_date', 'warranty_date', 'delivery_date',
                     'inventory_date') as $date) {

         if (isset($values['Infocom'][$date])
             && ($values['Infocom'][$date] == PluginDatainjectionCommonInjectionLib::EMPTY_VALUE)) {
            $values['Infocom'][$date] = "NULL";
         }
      }
   }


   /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::addOrUpdateObject()
   **/
   function addOrUpdateObject($values=array(), $options=array()) {

      $lib = new PluginDatainjectionCommonInjectionLib($this, $values, $options);
      $lib->processAddOrUpdate();
      return $lib->getInjectionResults();
   }

}
?>
