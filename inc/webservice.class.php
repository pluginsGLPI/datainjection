<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2011 by the INDEPNET Development Team.

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
 along with GLPI; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 --------------------------------------------------------------------------
*/

// Original Author of file: Walid Nouh
// Purpose of file: Webservice methods
// ----------------------------------------------------------------------

class PluginDatainjectionWebservice {


   static function methodInject($params, $protocol) {

      if (isset ($params['help'])) {
         return array('uri'    => 'string,mandatory',
                      'base64' => 'string,optional',
                      'help'   => 'bool,optional');
      }

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

      if (!isset ($params['entities_id'])) {
         return PluginWebservicesMethodCommon::Error($protocol,
                                                     WEBSERVICES_ERROR_MISSINGPARAMETER,
                                                     'entities_id');
      }

      $model         = new PluginDatainjectionModel;
      $document_name = basename($params['uri']);
      $filename      = tempnam(PLUGIN_DATAINJECTION_UPLOAD_DIR, 'PWS');

      if (!$model->getFromDB($params['models_id'])) {
         return PluginWebservicesMethodCommon::Error($protocol,
                                                     WEBSERVICES_ERROR_NOTFOUND, 'models_id');
      }

      $response = PluginWebservicesMethodCommon::uploadDocument($params, $protocol, $filename,
                                                                $document_name);

      if (PluginWebservicesMethodCommon::isError($protocol, $response)) {
         return $response;
      }

      $options = array('file_encoding'     => PluginDatainjectionBackend::ENCODING_AUTO,
                       'webservice'        => true,
                       'original_filename' => $params['uri'],
                       'unique_filename'   => $filename);

      return $model->readUploadedFile($options);
   }


   static function methodGetModel($params,$protocol) {

      if (isset ($params['help'])) {
         return array('help' => 'bool,optional');
      }

      if (!isset ($_SESSION['glpiID'])) {
         return self::Error($protocol, WEBSERVICES_ERROR_NOTAUTHENTICATED);
      }

      $model = new PluginPluginDatainjectionModel;
      if ($model->getFromDB($params['id'])) {
         return $model->fields;
      }
      return array();
   }


  static function methodListModels($params, $protocol) {

      if (isset ($params['help'])) {
         return array('help' => 'bool,optional');
      }

      if (!isset ($_SESSION['glpiID'])) {
         return self::Error($protocol, WEBSERVICES_ERROR_NOTAUTHENTICATED);
      }

      return getAllDatasFromTable('glpi_plugin_datainjection_models');
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