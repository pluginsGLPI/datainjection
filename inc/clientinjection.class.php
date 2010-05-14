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
         PluginDatainjectionModel::dropdown(array('value'=>$ID));
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

      echo "<span id='span_injection' name='span_injection'>";
      echo "</span>";
      echo "</div></form>";

      if (isset($_SESSION['glpi_plugin_datainjection_models_id'])) {
         $p['models_id'] = $_SESSION['glpi_plugin_datainjection_models_id'];

         if ($_SESSION['glpi_plugin_datainjection_step'] == self::STEP_UPLOAD) {
            $url = $CFG_GLPI["root_doc"]."/plugins/datainjection/ajax/dropdownSelectModel.php";
         }
         else {
            $url = $CFG_GLPI["root_doc"]."/plugins/datainjection/ajax/injection.php";
         }
         ajaxUpdateItem("span_injection",$url,$p);
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

   static function showInjectionForm($options = array(),PluginDatainjectionModel $model,$entities_id) {
      global $LANG;

      echo "<table class='tab_cadre_fixe'>";
      echo "<th colspan='2'>" . $LANG["datainjection"]["tabs"][3]."</th>";
      echo "<tr class='tab_bg_1'>";
      echo "<td>" ;

      $nblines = $model->getBackend()->getNumberOfLines();
      createProgressBar($LANG["datainjection"]["importStep"][1]);

      $clientinjection = new PluginDatainjectionClientInjection;

      //New injection engine
      $engine = new PluginDatainjectionEngine($model,$entities_id);

      $backend = $model->getBackend();
      $model->loadSpecificModel();
      $backend->openFile();

      $index = 0;
      $line = $backend->getNextLine();

      //If header is present, then get the second line
      if ($model->getSpecificModel()->isHeaderPresent()) {
         $line = $backend->getNextLine();
      }

      while ($line != null) {
         //Inject line
         $clientinjection->inject($engine,$line[0],$index);
         changeProgressBarPosition(
            $index,
            $nblines,
            $LANG["datainjection"]["importStep"][1]."... ".
            number_format($index*100/$nblines,1).'%');
         $line = $backend->getNextLine();
         $index++;
      }

      changeProgressBarPosition(100,100,
            $LANG["datainjection"]["importStep"][3]);
      $backend->closeFile();
      echo "</td>" ;
      echo "</tr>";
      echo "</table>";
   }

   function inject(PluginDatainjectionEngine $engine, $line,$line_id) {
      $res = $engine->injectLine($line);
      $this->results[] = $engine->injectLine($line);
      //$datas = $engine->getDatas();
   }
}
?>