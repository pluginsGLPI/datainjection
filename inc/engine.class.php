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

class PluginDatainjectionEngine {
   //Model informations
   private $model;

   //Current entity
   private $entity;

   //Additional infos to be added
   private $infos = array();

   //Lines in error
   private $error_lines = array();


   /**
    * @param $model
    * @param $infos     array
    * @param $entity             (default 0)
    */
   function __construct($model, $infos=array(), $entity=0) {

      //Instanciate model
      $this->model = $model;

      //Load model and mappings informations
      $this->getModel()->loadMappings();
      $this->getModel()->populateSeveraltimesMappedFields();
      $this->getModel()->loadInfos();
      $this->infos  = $infos;
      $this->entity = $entity;
   }


   /**
    * Inject one line of data
    *
    * @param $line   one line of data to import
    * @param $index  the line number is the file
   **/
   function injectLine($line, $index) {

      //Store all fields to injection, sorted by itemtype
      $fields_toinject  = array();
      $mandatory_fields = array();

      //Get the injectionclass associated to the itemtype
      $itemtype       = $this->getModel()->getItemtype();
      $injectionClass = PluginDatainjectionCommonInjectionLib::getInjectionClassInstance($itemtype);
      $several        = PluginDatainjectionMapping::getSeveralMappedField($this->getModel()->fields['id']);

      //First of all : transform $line which is an array of values to inject into another array
      //which looks like this :
      //array(itemtype=>array(field=>value,field2=>value2))
      //Note : ignore values which are not mapped with a glpi's field
      $searchOptions = $injectionClass->getOptions($itemtype);

      for ($i=0 ; $i<count($line) ; $i++) {
         $mapping = $this->getModel()->getMappingByRank($i);
         //If field is mapped with a value in glpi
         if (($mapping != null)
             && ($mapping->getItemtype() != PluginDatainjectionInjectionType::NO_VALUE)) {
            $this->addValueToInject($fields_toinject, $searchOptions, $mapping, $line[$i], $several);
         }
      }

      //Create an array with the mandatory mappings
      foreach ($this->getModel()->getMappings() as $mapping) {
         if ($mapping->isMandatory()) {
            $mandatory_fields[$mapping->getItemtype()][$mapping->getValue()] = $mapping->isMandatory();
         }
      }

      //Add fields needed for injection
      $this->addRequiredFields($itemtype, $fields_toinject);

      //Optional data to be added to the fields to inject (won't be checked !)
      $optional_data = $this->addAdditionalInformations($this->infos);

      //--------------- Set all needed options ------------------//
      //Check options
      $checks = array('ip'           => true,
                      'mac'          => true,
                      'integer'      => true,
                      'yes'          => true,
                      'bool'         => true,
                      'date'         => $this->getModel()->getDateFormat(),
                      'float'        => $this->getModel()->getFloatFormat(),
                      'string'       => true,
                      'right_r'      => true,
                      'right_rw'     => true,
                      'interface'    => true,
                      'auth_method'  => true,
                      'port_unicity' => $this->getModel()->getPortUnicity());

      //Rights options
      $rights = array('add_dropdown'              => $this->getModel()->getCanAddDropdown(),
                      'overwrite_notempty_fields' => $this->getModel()->getCanOverwriteIfNotEmpty(),
                      'can_add'                   => $this->model->getBehaviorAdd(),
                      'can_update'                => $this->model->getBehaviorUpdate(),
                      'can_delete'                => false);

      //Field format options
      $formats = array('date_format'  => $this->getModel()->getDateFormat(),
                       'float_format' => $this->getModel()->getFloatFormat());

      //Check options : by default check all types
      $options = array('checks'           => $checks,
                       'entities_id'      => $this->getEntity(),
                       'rights'           => $rights,
                       'formats'          => $formats,
                       'mandatory_fields' => $mandatory_fields,
                       'optional_data'    => $optional_data);

      //Will manage add or update
      $results = $injectionClass->addOrUpdateObject($fields_toinject, $options);

      //Add injected line number to the result array
      $results['line'] = $index;
      if ($results['status'] != PluginDatainjectionCommonInjectionLib::SUCCESS) {
         $this->error_lines[] = $line;
      }
      return $results;
   }


   /**
    * Add fields needed for injection
    *
    * @param $itemtype                    the itemtype to inject
    * @param $fields_toinject    array    the list of fields representing the object
    *
    * @return nothing
   **/
   function addRequiredFields($itemtype, &$fields_toinject=array()) {

      //Add entity to the primary type
      $fields_toinject[$itemtype]['entities_id'] = $this->entity;
   }


   /**
    * Add a value to the fields to inject
    *
    * @param $fields_toinject                the fields
    * @param $searchOptions                  options related to the itemtype to inject
    * @param $mapping                        the mapping which matches the field
    * @param $value                          the value for this field, as readed from the CSV file
    * @param $several            array       of all fields which can be mapping more than one time
    *                                        in the model
    * @return nothing
   **/
   function addValueToInject(&$fields_toinject, $searchOptions, $mapping, $value,
                             $several = array()) {

      // Option will be found only for "main" type.
      $option       = PluginDatainjectionCommonInjectionLib::findSearchOption($searchOptions,
                                                                              $mapping->getValue());
      $return_value = $value;

      if (($option['displaytype'] == 'multiline_text')
          && in_array($mapping->getValue(), $several)
          && ($value != PluginDatainjectionCommonInjectionLib::EMPTY_VALUE)) {
         $return_value = '';

         if (isset($fields_toinject[$mapping->getItemtype()][$mapping->getValue()])) {
            $return_value .= $fields_toinject[$mapping->getItemtype()][$mapping->getValue()];
         }
         $return_value .= $mapping->getMappingName()."=".$value."\n";
      }
      $fields_toinject[$mapping->getItemtype()][$mapping->getValue()] = $return_value;
   }


   /**
    * Add additonal informations, as selected by the user which performs the CSV file import
    *
    * @return additional informations to inject
   **/
   function addAdditionalInformations() {

      $additional_infos = array();
      foreach ($this->model->getInfos() as $info) {
         if (isset($this->infos[$info->getValue()])
             && PluginDatainjectionInfo::keepInfo($info, $this->infos[$info->getValue()])) {

            $additional_infos[$info->getInfosType()][$info->getValue()]
                              = $this->infos[$info->getValue()];
         }
      }
      return $additional_infos;
   }


   //--------- Getters -------------------------//
   function getModel() {
      return $this->model;
   }


   function getEntity() {
      return $this->entity;
   }


   function getLinesInError() {
      return $this->error_lines;
   }

}
?>
