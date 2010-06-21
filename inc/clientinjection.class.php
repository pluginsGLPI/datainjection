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

// ----------------------------------------------------------------------
// Original Author of file: Walid Nouh
// Purpose of file: injection GUI
// ----------------------------------------------------------------------
*/
class PluginDatainjectionClientInjection {

   const STEP_UPLOAD  = 0;
   const STEP_PROCESS = 1;
   const STEP_RESULT  = 2;

   //Injection results
   private $results = array();

   //Model used for injection
   private $model;

   //Overall injection results
   private $global_results;

   /**
    * Print a good title for group pages
    *
    *@return nothing (display)
    **/
   function title() {
      global $LANG, $CFG_GLPI;

      $buttons = array ();
      $title = "";
      if (plugin_datainjection_haveRight("model", "w") ) {
         $url = getItemTypeSearchURL('PluginDatainjectionModel');
         $buttons[$url] = $LANG["datainjection"]["profiles"][1];
         $title="";
         displayTitle($CFG_GLPI["root_doc"] . "/plugins/datainjection/pics/datainjection.png",
                      $LANG['Menu'][36], $title, $buttons);
      }
   }

   function showForm($ID, $options=array()) {
      global $LANG, $CFG_GLPI;

      echo "<form method='post' name=form action='".getItemTypeFormURL(__CLASS__)."'".
            "enctype='multipart/form-data'>";
      echo "<div class='center'>";
      echo "<table class='tab_cadre_fixe'>";

      $models = PluginDatainjectionModel::getModels(getLoginUserID(),
                                                    'name',
                                                    $_SESSION['glpiactive_entity'],
                                                    false);
      echo "<tr>";
      echo "<th>" . $LANG["datainjection"]["choiceStep"][6]."</th>";
      echo "</tr>";

      if (count($models) > 0) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>".$LANG['common'][22]."&nbsp;:";
         PluginDatainjectionModel::dropdown();
         echo "</td></tr></table>";
      }
      else {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center' colspan='2'>".$LANG["datainjection"]["model"][33];
         if (plugin_datainjection_haveRight('model','w')) {
            echo ". ".$LANG["datainjection"]["model"][34].":".$LANG["datainjection"]["profiles"][1];
         }
         echo "</td></tr></table>";
      }

      echo "<span id='span_injection' name='span_injection'></span>";
      echo "</div></form>";

      if (isset($_SESSION['datainjection']['models_id'])) {
         $p['models_id'] = $_SESSION['datainjection']['models_id'];

         switch ($_SESSION['datainjection']['step']) {
            case self::STEP_UPLOAD:
               $url = $CFG_GLPI["root_doc"]."/plugins/datainjection/ajax/dropdownSelectModel.php";
               ajaxUpdateItem("span_injection",$url,$p);
               break;
            case self::STEP_RESULT:
               $url = $CFG_GLPI["root_doc"]."/plugins/datainjection/ajax/results.php";
               ajaxUpdateItem("span_injection",$url,$p);
               break;
         }
      }
   }

   static function showUploadFileForm($options = array()) {
      global $LANG;
      $add_form = (isset($options['add_form']) && $options['add_form']);
      $confirm = (isset($options['confirm']) && $options['confirm']);
      $url = ($confirm == 'creation'?getItemTypeFormURL('PluginDatainjectionModel')
                                                                  :getItemTypeFormURL(__CLASS__));
      if ($add_form) {
         echo "<form method='post' name='form' action='$url' enctype='multipart/form-data'>";
      }
      echo "<table class='tab_cadre_fixe'>";
      //Show file selection
      echo "<th colspan='2'>" . $LANG["datainjection"]["tabs"][3]."</th>";
      echo "<tr class='tab_bg_1'>";
      echo "<td>" . $LANG["datainjection"]["fileStep"][3] . "</td>";
      echo "<td><input type='file' name='filename' /></td>";
      echo "<input type='hidden' name='id' value='".$options['models_id']."'>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>" . $LANG["datainjection"]["fileStep"][9] . "</td>";
      echo "<td>";
      PluginDatainjectionDropdown::dropdownFileEncoding();
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";

      echo "<td colspan='2' align='center'>";
      if ($confirm) {
         if ($confirm == 'creation') {
            $message = $LANG["datainjection"]["mapping"][13];
         }
         else {
            $message = $LANG["datainjection"]["fillInfoStep"][1];
         }
         $alert = "OnClick='return window.confirm(\"$message\");'";
      }
      echo "<input type='submit' class='submit' name='upload' value=\"".
                                            $LANG["datainjection"]["import"][0]."\" $alert/>";
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      if ($add_form) {
         echo "</form>";
      }
   }

   static function showInjectionForm(PluginDatainjectionModel $model, $entities_id) {
      global $LANG,$CFG_GLPI;

      if (!isset($_SESSION['glpi_plugin_datainjection_infos'])) {
         $_SESSION['glpi_plugin_datainjection_infos'] = array();
      }
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2'>";
      echo $LANG["datainjection"]["model"][0]." : ".$model->fields['name']."</th>";
      echo "</tr>";
      echo "</table><br/>";

      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2'>" . $LANG["datainjection"]["import"][1]."</th>";
      echo "</tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td>" ;
      createProgressBar($LANG["datainjection"]["importStep"][1]);
      echo "</td>" ;
      echo "</tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td>" ;
      echo "</td>" ;
      echo "</tr>";
      echo "</table>";
      echo "<span id='span_injection' name='span_injection'></span>";
      self::processInjection($model,$entities_id);
   }

   static function processInjection(PluginDatainjectionModel $model, $entities_id) {
      global $LANG,$CFG_GLPI;
      $nblines = $_SESSION['datainjection']['nblines'];
      $clientinjection = new PluginDatainjectionClientInjection;

            //New injection engine
      $engine = new PluginDatainjectionEngine($model,
                                              $_SESSION['glpi_plugin_datainjection_infos'],
                                              $entities_id);
      $backend = $model->getBackend();
      $model->loadSpecificModel();

      //Open CSV file
      $backend->openFile();

      $index = 0;

      //Read CSV file
      $line = $backend->getNextLine();

      //If header is present, then get the second line
      if ($model->getSpecificModel()->isHeaderPresent()) {
         $line = $backend->getNextLine();
      }

      //While CSV file is not EOF
      while ($line != null) {
         //Inject line
         $clientinjection->results[] = $engine->injectLine($line[0]);
         $engine->injectLine($line[0]);
         changeProgressBarPosition(
             $index,
              $nblines,
              $LANG["datainjection"]["importStep"][1]."... ".
              number_format($index*100/$nblines,1).'%');
         $line = $backend->getNextLine();
         $index++;
         //logDebug($index.' sur '.$nblines);
      }

      //EOF : change progressbar to 100% !
      changeProgressBarPosition(100,100,
            $LANG["datainjection"]["importStep"][3]);

      //Close CSV file
      $backend->closeFile();

      //Delete CSV file
      $backend->deleteFile();

      //Change step
      $_SESSION['datainjection']['step'] = self::STEP_RESULT;

      //Display results form
      $_SESSION['datainjection']['results'] = json_encode($clientinjection->results);
      $_SESSION['datainjection']['error_lines'] = json_encode($engine->getLinesInError());
      $p['models_id'] = $model->fields['id'];
      $p['nblines']   = $nblines;

      unset($_SESSION['datainjection']['go']);

      $url = $CFG_GLPI["root_doc"]."/plugins/datainjection/ajax/results.php";
      ajaxUpdateItem("span_injection",$url,$p);
   }

   static function showResultsForm(PluginDatainjectionModel $model) {
      global $LANG,$CFG_GLPI;

      $results = json_decode(stripslashes_deep($_SESSION['datainjection']['results']),true);
      $error_lines = json_decode(stripslashes_deep($_SESSION['datainjection']['error_lines']),true);
      $ok = true;
      foreach ($results as $result) {
         if ($result['status'] != PluginDatainjectionCommonInjectionLib::SUCCESS) {
            $ok = false;
            break;
         }
      }

      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th>" . $LANG["datainjection"]["log"][1]."</th></tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      if ($ok) {
         echo "<img src='".$CFG_GLPI['root_doc']."/plugins/datainjection/pics/ok.png'>";
         echo $LANG["datainjection"]["log"][3];
      }
      else {
         echo "<img src='".$CFG_GLPI['root_doc']."/plugins/datainjection/pics/danger.png'>";
         echo $LANG["datainjection"]["log"][8];
      }
      echo "</td></tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";

      $url = $CFG_GLPI["root_doc"].
              "/plugins/datainjection/front/popup.php?popup=log&amp;models_id=".$model->fields['id'];
      echo "<a href='#' onClick=\"var w = window.open('$url' ,";
      echo "'glpipopup', 'height=400, width=600, top=100, left=100, scrollbars=yes' );w.focus();\"/' ";
      echo "title='".addslashes($LANG["datainjection"]["button"][4])."'>";
      echo "<img src='".$CFG_GLPI['root_doc']."/plugins/datainjection/pics/seereport.png'>";
      echo "</a>";
      $plugin = new Plugin;
      if ($plugin->isInstalled('pdf') && $plugin->isActivated('pdf')) {
         echo "&nbsp;";
         echo "<a href='#' onclick=\"location.href='export.pdf.php?models_id=".$model->fields['id']."'\"";
         echo "title='".addslashes($LANG["datainjection"]["button"][7])."'>";
         echo "<img src='".$CFG_GLPI['root_doc']."/plugins/datainjection/pics/reportpdf.png'>";
         echo "</a>";
      }

      if (!empty($error_lines)) {
         echo "&nbsp;";
         echo "<a href='#' onclick=\"location.href='export.csv.php'\"";
         echo "title='".addslashes($LANG["datainjection"]["button"][5])."'>";
         echo "<img src='".$CFG_GLPI['root_doc']."/plugins/datainjection/pics/failedcsv.png'>";
         echo "</a>";
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
   }

   static function exportErrorsInCSV() {

      $error_lines = json_decode(stripslashes_deep($_SESSION['datainjection']['error_lines']),true);
      if (!empty($error_lines)) {
         $model = unserialize($_SESSION["datainjection"]["currentmodel"]);
         $file = PLUGIN_DATAINJECTION_UPLOAD_DIR . $_SESSION['datainjection']['file_name'];

         $mappings = $model->getMappings();
         $tmpfile= fopen($file, "w");

         //If headers present
         if ($model->getBackend()->isHeaderPresent()) {
            $headers = PluginDatainjectionMapping::getMappingsSortedByRank($model->fields['id']);
            fputcsv($tmpfile, $headers, $model->getBackend()->getDelimiter());
         }

         //Write lines
         foreach($error_lines as $line) {
            fputcsv($tmpfile, $line, $model->getBackend()->getDelimiter());
         }
         fclose($tmpfile);

         header('Content-disposition: attachment; filename=Error-'.$_SESSION['datainjection']['file_name']);
         header('Content-Type: application/force-download');
         header('Content-Transfer-Encoding: fichier');
         header('Content-Length: '.filesize($file));
         header('Pragma: no-cache');
         header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
         header('Expires: 0');
         readfile($file);
         unlink($file);
      }
   }
}
?>