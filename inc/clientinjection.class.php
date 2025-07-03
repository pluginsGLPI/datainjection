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

use Glpi\Application\View\TemplateRenderer;

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

class PluginDatainjectionClientInjection
{
    public static $rightname = "plugin_datainjection_use";

    const STEP_UPLOAD  = 0;
    const STEP_PROCESS = 1;
    const STEP_RESULT  = 2;

    //Injection results
    private $results = [];

    /**
    * Print a good title for group pages
    *
    *@return void nothing (display)
   **/
    public function title(): void
    {
        /** @var array $CFG_GLPI */
        global $CFG_GLPI;

        $buttons =  [];
        $title   = "";

        if (Session::haveRight(static::$rightname, UPDATE)) {
            $url           = Toolbox::getItemTypeSearchURL('PluginDatainjectionModel');
            $buttons[$url] = PluginDatainjectionModel::getTypeName();
            $title         = "";
            Html::displayTitle(
                plugin_datainjection_geturl() . "pics/datainjection.png",
                PluginDatainjectionModel::getTypeName(),
                $title,
                $buttons
            );
        }
    }


    public function showForm($ID, $options = [])
    {
        /** @var array $CFG_GLPI */
        global $CFG_GLPI;

        TemplateRenderer::getInstance()->display('@datainjection/clientinjection.html.twig', [
            'form_action' => Toolbox::getItemTypeFormURL(__CLASS__),
            'models' => PluginDatainjectionModel::getModels(Session::getLoginUserID(), 'name', $_SESSION['glpiactive_entity'], false),
            'can_create_model' => Session::haveRight('plugin_datainjection_model', CREATE),
            'model_type_name' => PluginDatainjectionModel::getTypeName(),
            'models_id' => PluginDatainjectionSession::getParam('models_id'),
            'step' => PluginDatainjectionSession::getParam('step'),
            'upload_url' => $CFG_GLPI['root_doc'] . "/plugins/datainjection/ajax/dropdownSelectModel.php",
            'result_url' => $CFG_GLPI['root_doc'] . "/plugins/datainjection/ajax/results.php",
            'params' => ['models_id' => PluginDatainjectionSession::getParam('models_id')],
            'upload_step' => self::STEP_UPLOAD,
            'result_step' => self::STEP_RESULT,
        ]);
    }


    /**
    * @param array $options
   **/
    public static function showUploadFileForm($options = [])
    {
        $add_form = (isset($options['add_form']) && $options['add_form']);
        $confirm  = (isset($options['confirm']) ? $options['confirm'] : false);
        $url      = (($confirm == 'creation') ? Toolbox::getItemTypeFormURL('PluginDatainjectionModel')
                                                : Toolbox::getItemTypeFormURL(__CLASS__));

        $data = [
            'add_form' => $add_form,
            'url' => $url,
            'models_id' => $options['models_id'] ?? null,
            'confirm' => $confirm,
            'submit_label' => $options['submit'] ?? __('Launch the import', 'datainjection'),
            'file_encoding_values' => PluginDatainjectionDropdown::getFileEncodingValue(),
        ];

        TemplateRenderer::getInstance()->display('@datainjection/clientinjection_upload_file.html.twig', $data);
    }


    /**
    * @param PluginDatainjectionModel $model PluginDatainjectionModel object
    * @param integer $entities_id
   **/
    public static function showInjectionForm(PluginDatainjectionModel $model, $entities_id)
    {
        if (!PluginDatainjectionSession::getParam('infos')) {
            PluginDatainjectionSession::setParam('infos', []);
        }

        TemplateRenderer::getInstance()->display('@datainjection/clientinjection_injection.html.twig', [
            'model_name' => $model->fields['name'],
        ]);

        // L'injection réelle reste côté PHP, mais tu peux déclencher l'appel Ajax ici si besoin
        echo "<span id='span_injection' name='span_injection'></span>";
        self::processInjection($model, $entities_id);
    }


    /**
    * @param PluginDatainjectionModel $model
    * @param integer $entities_id
   **/
    public static function processInjection(PluginDatainjectionModel $model, $entities_id)
    {
        /** @var array $CFG_GLPI */
        global $CFG_GLPI;

       // To prevent problem of execution time during injection
        ini_set("max_execution_time", "0");

        // Disable recording each SQL request in $_SESSION
        \Glpi\Debug\Profile::getCurrent()->disable();

        $nblines         = PluginDatainjectionSession::getParam('nblines');
        $clientinjection = new PluginDatainjectionClientInjection();

       //New injection engine
        $engine = new PluginDatainjectionEngine(
            $model,
            PluginDatainjectionSession::getParam('infos'),
            $entities_id
        );
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
        $prev = '';
        $deb  = time();
        while ($line != null) {
            //Inject line
            $injectionline              = $index + ($model->getSpecificModel()->isHeaderPresent() ? 2 : 1);
            $clientinjection->results[] = $engine->injectLine($line[0], $injectionline);

            $pos = number_format($index * 100 / $nblines, 1);
            if ($pos != $prev) {
                $prev = $pos;
                $fin  = time() - $deb;
            }
            $line = $backend->getNextLine();
            $index++;
        }

        $js = <<<JAVASCRIPT
            $(function() {
                const progress = document.querySelector('.progress');
                const progressBar = document.querySelector('.progress-bar');
                if (progressBar && progress) {
                    progressBar.style.width = '100%';
                    progress.setAttribute('aria-valuenow', '100');
                }
            });
        JAVASCRIPT;

        //EOF : change progressbar to 100% !
        echo Html::scriptBlock($js);

        // Restore
        \Glpi\Debug\Profile::getCurrent()->enable();

        //Close CSV file
        $backend->closeFile();

        //Delete CSV file
        $backend->deleteFile();

        //Change step
        $_SESSION['datainjection']['step'] = self::STEP_RESULT;

        //Display results form
        PluginDatainjectionSession::setParam('results', json_encode($clientinjection->results));
        PluginDatainjectionSession::setParam('error_lines', json_encode($engine->getLinesInError()));
        $p['models_id'] = $model->fields['id'];
        $p['nblines']   = $nblines;

        unset($_SESSION['datainjection']['go']);

        $url = $CFG_GLPI['root_doc'] . "/plugins/datainjection/ajax/results.php";
        Ajax::updateItem("span_injection", $url, $p);
    }


    /**
    * to be used instead of Toolbox::stripslashes_deep to reduce memory usage
    * execute stripslashes in place (no copy)
    *
    * @param array|string|null $value array of value
    */
    public static function stripslashes_array(&$value) // phpcs:ignore
    {

        if (is_array($value)) {
            foreach ($value as $key => $val) {
                self::stripslashes_array($value[$key]);
            }
        } else if (!is_null($value)) {
            $value = stripslashes($value);
        }
    }


    /**
    * @param PluginDatainjectionModel $model  PluginDatainjectionModel object
   **/
    public static function showResultsForm(PluginDatainjectionModel $model)
    {
        /** @var array $CFG_GLPI */
        global $CFG_GLPI;

        $results     = json_decode(PluginDatainjectionSession::getParam('results'), true);
        self::stripslashes_array($results);
        $error_lines = json_decode(PluginDatainjectionSession::getParam('error_lines'), true);
        self::stripslashes_array($error_lines);

        $ok = true;
        foreach ($results as $result) {
            if ($result['status'] != PluginDatainjectionCommonInjectionLib::SUCCESS) {
                $ok = false;
                break;
            }
        }

        $from_url = plugin_datainjection_geturl() . "front/clientinjection.form.php";
        $plugin      = new Plugin();

        $data = [
            'ok'            => $ok,
            'from_url'      => $from_url,
            'popup_url'     => plugin_datainjection_geturl() . "front/popup.php?popup=log&amp;models_id=" . $model->fields['id'],
            'model_id'      => $model->fields['id'],
            'has_pdf'       => $plugin->isActivated('pdf'),
            'has_errors'    => !empty($error_lines),
        ];

        TemplateRenderer::getInstance()->display('@datainjection/clientinjection_result.html.twig', $data);
    }

    public static function exportErrorsInCSV()
    {

        $error_lines = json_decode(PluginDatainjectionSession::getParam('error_lines'), true);
        self::stripslashes_array($error_lines);

        if (!empty($error_lines)) {
            $model = unserialize(PluginDatainjectionSession::getParam('currentmodel'));
            $file  = PLUGIN_DATAINJECTION_UPLOAD_DIR . PluginDatainjectionSession::getParam('file_name');

            $mappings = $model->getMappings();
            $tmpfile  = fopen($file, 'w');

           //If headers present
            if ($model->getBackend()->isHeaderPresent()) {
                $headers = PluginDatainjectionMapping::getMappingsSortedByRank($model->fields['id']);
                fputcsv($tmpfile, $headers, $model->getBackend()->getDelimiter());
            }

           //Write lines
            foreach ($error_lines as $line) {
                fputcsv($tmpfile, $line, $model->getBackend()->getDelimiter());
            }
            fclose($tmpfile);

            $name = "Error-" . PluginDatainjectionSession::getParam('file_name');
            $name = str_replace(' ', '', $name);
            header('Content-disposition: attachment; filename=' . $name);
            header('Content-Type: application/octet-stream');
            header('Content-Transfer-Encoding: fichier');
            header('Content-Length: ' . filesize($file));
            header('Pragma: no-cache');
            header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            header('Expires: 0');
            readfile($file);
            unlink($file);
        }
    }
}
