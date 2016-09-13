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

class PluginDatainjectionWebservice {


   static function methodInject($params, $protocol) {

      if (isset ($params['help'])) {
         return array('uri'      => 'string,mandatory',
                      'base64'     => 'string,optional',
                      'additional' => 'array,optional',
                      'models_id'  => 'integer, mandatory',
                      'entities_id'=> 'integer,mandatory',
                      'mandatory'  => 'array,optional',
                      'uri'        => 'uri,mandatory',
                      'help'       => 'bool,optional');
      }

      $model = new PluginDatainjectionModel();

      //-----------------------------------------------------------------
      //-------------------------- Check parameters ---------------------
      //-----------------------------------------------------------------
      if (!isset ($_SESSION['glpiID'])) {
         return PluginWebservicesMethodCommon::Error($protocol,
                                                     WEBSERVICES_ERROR_NOTAUTHENTICATED);
      }

      if (!isset($params['uri']) && !isset($params['base64'])) {
         return PluginWebservicesMethodCommon::Error($protocol,
                                                     WEBSERVICES_ERROR_MISSINGPARAMETER,
                                                     '', 'uri or base64');
      }

      if (!isset ($params['models_id'])) {
         return PluginWebservicesMethodCommon::Error($protocol,
                                                     WEBSERVICES_ERROR_MISSINGPARAMETER,
                                                     'models_id');
      }
      if (!$model->getFromDB($params['models_id'])) {
         return PluginWebservicesMethodCommon::Error($protocol, WEBSERVICES_ERROR_NOTFOUND,
                                                     __('Model unknown', 'datainjection'));

      }
      if (!$model->can($params['models_id'], READ)) {
         return PluginWebservicesMethodCommon::Error($protocol, WEBSERVICES_ERROR_NOTALLOWED,
                                                     __('You cannot access this model',
                                                        'datainjection'));
      }
      if ($model->fields['step'] < PluginDatainjectionModel::READY_TO_USE_STEP) {
         return PluginWebservicesMethodCommon::Error($protocol, WEBSERVICES_ERROR_NOTALLOWED,
                                                     __('You cannot access this model',
                                                        'datainjection'));
      }

      //Check entity
      if (!isset ($params['entities_id'])) {
         return PluginWebservicesMethodCommon::Error($protocol,
                                                     WEBSERVICES_ERROR_MISSINGPARAMETER,
                                                     'entities_id');
      }
      $entities_id = $params['entities_id'];
      if ($entities_id > 0 ) {
         $entity = new Entity();
         if (!$entity->getFromDB($entities_id)) {
            return PluginWebservicesMethodCommon::Error($protocol, WEBSERVICES_ERROR_NOTFOUND,
                                                        __('Entity unknown', 'datainjection'));

         }
         if (!Session::haveAccessToEntity($entities_id)) {
            return PluginWebservicesMethodCommon::Error($protocol, WEBSERVICES_ERROR_NOTALLOWED,
                                                        __('You cannot access this entity',
                                                           'datainjection'));
         }
      }

      //Mandatory fields
      $additional_infos = array();
      if (isset($params['additional']) && is_array($params['additional'])) {
         $additional_infos = $params['additional'];
      }

      //Upload CSV file
      $document_name = basename($params['uri']);
      $filename      = tempnam(PLUGIN_DATAINJECTION_UPLOAD_DIR, 'PWS');
      $response      = PluginWebservicesMethodCommon::uploadDocument($params, $protocol, $filename,
                                                                     $document_name);

      if (PluginWebservicesMethodCommon::isError($protocol, $response)) {
         return $response;
      }

      //Uploade successful : now perform import !
      $options = array('file_encoding'     => PluginDatainjectionBackend::ENCODING_AUTO, //Detect automatically file encoding
                       'webservice'        => true, //Use webservice CSV file import
                       'original_filename' => $params['uri'], //URI to the CSV file
                       'unique_filename'   => $filename, //Unique filename
                       'mode'              => PluginDatainjectionModel::PROCESS,
                       'delete_file'       => false, //Do not delete file once imported
                       'protocol'          => $protocol); //The Webservice protocol used

      $results  = array();
      $response = $model->processUploadedFile($options);
      if (!PluginWebservicesMethodCommon::isError($protocol, $response)) {
         $engine  = new PluginDatainjectionEngine($model, $additional_infos, $params['entities_id']);
         //Remove first line if header is present
         $first = true;
         foreach ($model->injectionData->getData() as $id => $data) {
            if ($first
                && $model->getSpecificModel()->isHeaderPresent()) {
               $first = false;
            } else {
               $results[] = $engine->injectLine($data[0], $id);
            }
         }
         $model->cleanData();
         return $results;
      }
      return $response;
   }


   static function methodGetModel($params,$protocol) {

      $params['itemtype'] = 'PluginDatainjectionModel';
      return PluginWebservicesMethodInventaire::methodGetObject($params, $protocol);
   }


  static function methodListModels($params, $protocol) {

      $params['itemtype'] = 'PluginDatainjectionModel';
      return PluginWebservicesMethodInventaire::methodListObjects($params, $protocol);
   }


   static function methodListItemtypes($params, $protocol) {

      if (isset ($params['help'])) {
         return array('help' => 'bool,optional');
      }

      if (!isset ($_SESSION['glpiID'])) {
         return self::Error($protocol, WEBSERVICES_ERROR_NOTAUTHENTICATED);
      }

      return PluginDatainjectionInjectionType::getItemtypes();
   }

}
?>
