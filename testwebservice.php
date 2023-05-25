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

if (!extension_loaded("xmlrpc")) {
   die("Extension xmlrpc not loaded\n");
}

chdir(dirname($_SERVER["SCRIPT_FILENAME"]));
chdir("../../..");
$url = "/".basename(getcwd()).Plugin::getWebDir('webservices', false)."/xmlrpc.php";

$args = [];
if ($_SERVER['argc'] > 1) {
   for ($i=1; $i<count($_SERVER['argv']); $i++) {
      $it           = explode("=", $argv[$i], 2);
      $it[0]        = preg_replace('/^--/', '', $it[0]);
      $args[$it[0]] = (isset($it[1]) ? $it[1] : true);
   }
}

if (isset($args['help']) && !isset($args['method'])) {
   echo "\nusage : ".$_SERVER["SCRIPT_FILENAME"]." [ options] \n\n";

   echo "\thelp        : display this screen\n";
   echo "\thost        : server name or IP, default : localhost\n";
   echo "\turl         : XML-RPC plugin URL, default : $url\n";
   echo "\tusername    : User name for security check (optionnal, default : glpi)\n";
   echo "\tpassword    : User password (optionnal, default : glpi)\n";
   echo "\turi         : Absolute path to the csv file to inject\n";
   echo "\additional[xx]=yy : Additional info to import (xx = field name, yy=value)\n";
   echo "\tmodels_id   : ID of the model to use\n";
   echo "\tentities_id : ID of the entity in whic data should be injected\n";

   die( "\nOther options are used for XML-RPC call.\n\n");
}
print_r($args);
//First of all, user login
$attrs['login_name']       = (isset($args['username'])?$args['username']:'glpi');
$attrs['login_password']   = (isset($args['password'])?$args['password']:'glpi');
$attrs['host']             = (isset($args['host'])?$args['host']:'localhost');
$attrs['url']              = (isset($args['url'])?$args['url']
                                                 :'glpi080/plugins/webservices/xmlrpc.php');
$attrs['additional']       = (isset($args['additional'])?$args['additional']:[]);

$response = call('glpi.doLogin', $attrs);

if ($response) {
   $attrs['session'] = $response['session'];
   echo "User logged in with session=".$response['session']."\n";
} else {
   exit(0);
}

//Set parameters for csv injection
$attrs['models_id']   = $args['models_id'];
$attrs['entities_id'] = $args['entities_id'];
$attrs['uri']         = $args['uri'];

//Inject file
$response = call('datainjection.inject', $attrs);
print_r($response);

print_r(call('glpi.doLogout', $attrs));


function call($method, $params) {

   $header  = "Content-Type: text/xml";
   echo "+ Calling '$method' on http://".$params['host']."/".$params['url']."\n";

   $request = xmlrpc_encode_request($method, $params);
   $context = stream_context_create(
      [
         'http' => [
            'method'  => "POST",
            'header'  => $header,
            'content' => $request
         ]
      ]
   );

   $file = file_get_contents("http://".$params['host']."/".$params['url'], false, $context);
   if (!$file) {
      die("+ No response\n");
   }

   $response = xmlrpc_decode($file);
   if (!is_array($response)) {
      echo $file;
      die ("+ Bad response\n");
   }

   if (xmlrpc_is_fault($response)) {
       echo("xmlrpc error(".$response['faultCode']."): ".$response['faultString']."\n");
       return false;
   }
   return $response;
}
