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
         //if ($_SESSION['glpi_plugin_datainjection_step'] == self::STEP_UPLOAD) {
            PluginDatainjectionModel::dropdown(array('value'=>$ID));
         //}
         //else {
        //    echo Dropdown::getDropdownName('glpi_plugin_datainjection_models',$ID);
         //}
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

      if (isset($_SESSION['glpi_plugin_datainjection_models_id'])) {
         $p['models_id'] = $_SESSION['glpi_plugin_datainjection_models_id'];

         switch ($_SESSION['glpi_plugin_datainjection_step']) {
            case self::STEP_UPLOAD:
               $url = $CFG_GLPI["root_doc"]."/plugins/datainjection/ajax/dropdownSelectModel.php";
               ajaxUpdateItem("span_injection",$url,$p);
               break;
             case self::STEP_PROCESS:
               $url = $CFG_GLPI["root_doc"]."/plugins/datainjection/ajax/injection.php";
               ajaxUpdateItem("span_injection",$url,$p);
                break;
             default:
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
      echo "<th colspan='2'>" . $LANG["datainjection"]["import"][1]."</th>";
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

      self::processInjection($model,$entities_id);
   }

   static function processInjection(PluginDatainjectionModel $model, $entities_id) {
      global $LANG,$CFG_GLPI;
      $nblines = $model->getBackend()->getNumberOfLines();
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
         changeProgressBarPosition(
            $index,
            $nblines,
            $LANG["datainjection"]["importStep"][1]."... ".
            number_format($index*100/$nblines,1).'%');
         $line = $backend->getNextLine();
         $index++;
         logDebug($index.' sur '.$nblines);
      }

      //EOF : change progressbar to 100% !
      changeProgressBarPosition(100,100,
            $LANG["datainjection"]["importStep"][3]);

      //Close CSV file
      $backend->closeFile();

      //Delete CSV file
      $backend->deleteFile();

      //Change step
      $_SESSION['glpi_plugin_datainjection_step'] = self::STEP_UPLOAD;

      //Display results form
      $p['models_id'] = $model->fields['id'];
      $p['results']   = json_encode($clientinjection->results);
      $p['nblines']   = $nblines;
      $url = $CFG_GLPI["root_doc"]."/plugins/datainjection/ajax/results.php";
      ajaxUpdateItem("span_injection",$url,$p);
   }

   static function showResultsForm(PluginDatainjectionModel $model, $options) {
      global $LANG;
      $results = json_decode(stripslashes_deep($options['results']),true);
      printCleanArray($results);
      echo "<table class='tab_cadre_fixe'>";
      echo "<th>" . $LANG["datainjection"]["log"][1]."</th>";
      echo "<tr class='tab_bg_1'>";

      echo "<td align='center'>";
      echo "<input type='button' name='popup' value='" . addslashes($LANG["datainjection"]["button"][4]) . "'
                                  class='submit' onclick='log_popup(".$options['nblines'].")' />";
      echo "&nbsp;";
      echo "<input type='button' name='pdf' value='" . addslashes($LANG["datainjection"]["button"][7]) . "'
                                 class='submit'
                                 onclick=\"location.href='plugin_datainjection.export.pdf.php'\" />";
      echo "&nbsp;";
      echo "<input type='button' name='export' value='" . addslashes($LANG["datainjection"]["button"][5]) . "'
                                 class='submit'
                                 onclick=\"location.href='plugin_datainjection.download.php'\" />";
      echo "</td>";
      echo "</tr>";
      echo "</table>";
   }
}
?>