<?php
/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

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
 ------------------------------------------------------------------------
*/

// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------

$LANG["datainjection"]["name"][1] = "File injection";
$title = $LANG["datainjection"]["name"][1] ;

$LANG["datainjection"]["config"][1]=$title." plugin configuration";

$LANG["datainjection"]["setup"][1] = $title." plugin configuration";
$LANG["datainjection"]["setup"][3] = "Install the plugin $title";
$LANG["datainjection"]["setup"][4] = "Update plugin $title";
$LANG["datainjection"]["setup"][5] = "Uninstall plugin $title";
$LANG["datainjection"]["setup"][9] = "Rights management";
$LANG["datainjection"]["setup"][10] = "PHP 5 or higher is required to use this plugin";
$LANG["datainjection"]["setup"][11] = "Instructions";

$LANG["datainjection"]["presentation"][1] = "Welcome to the file injection wizard";
$LANG["datainjection"]["presentation"][2] = "This wizard allows you to import CSV files into GLPI";
$LANG["datainjection"]["presentation"][3] = "To start, click the Next button.";

$LANG["datainjection"]["step"][1] = "Step 1 : ";
$LANG["datainjection"]["step"][2] = "Step 2 : ";
$LANG["datainjection"]["step"][3] = "Step 3 : ";
$LANG["datainjection"]["step"][4] = "Step 4 : ";
$LANG["datainjection"]["step"][5] = "Step 5 : ";
$LANG["datainjection"]["step"][6] = "Step 6 : ";

$LANG["datainjection"]["choiceStep"][1] = "Model use or management";
$LANG["datainjection"]["choiceStep"][2] = "This first step allows you to create, modify, delete or use a model.";
$LANG["datainjection"]["choiceStep"][3] = "Create a new model";
$LANG["datainjection"]["choiceStep"][4] = "Modify an existing model";
$LANG["datainjection"]["choiceStep"][5] = "Delete an existing model";
$LANG["datainjection"]["choiceStep"][6] = "Use an existing model";
$LANG["datainjection"]["choiceStep"][7] = "Model's comments";
$LANG["datainjection"]["choiceStep"][8] = "No comments";
$LANG["datainjection"]["choiceStep"][9] = "Make your choice";
$LANG["datainjection"]["choiceStep"][10] = "Depending on your rights, you may not access all the choices.";
$LANG["datainjection"]["choiceStep"][11] = "You must select a model for use, update and deletion.";

$LANG["datainjection"]["modelStep"][1] = "Collecting informations about the file";
$LANG["datainjection"]["modelStep"][2] = "Modification of the model";
$LANG["datainjection"]["modelStep"][3] = "Select the type of file to import.";
$LANG["datainjection"]["modelStep"][4] = "Type of datas to import :";
$LANG["datainjection"]["modelStep"][5] = "Type of file :";
$LANG["datainjection"]["modelStep"][6] = "Allow lines creation :";
$LANG["datainjection"]["modelStep"][7] = "Allow lines update :";
$LANG["datainjection"]["modelStep"][8] = "Allow creation of dropdowns :";
$LANG["datainjection"]["modelStep"][9] = "Header's presence :";
$LANG["datainjection"]["modelStep"][10] = "File delimitor :";
$LANG["datainjection"]["modelStep"][11] = "No delimiter defined";
$LANG["datainjection"]["modelStep"][12] = "Allow update of existing fields :";
$LANG["datainjection"]["modelStep"][13] = "Main informations";
$LANG["datainjection"]["modelStep"][14] = "CSV Options";
$LANG["datainjection"]["modelStep"][15] = "Advanced options";
$LANG["datainjection"]["modelStep"][16] = "Diffusion :";
$LANG["datainjection"]["modelStep"][17] = "Public";
$LANG["datainjection"]["modelStep"][18] = "Private";
$LANG["datainjection"]["modelStep"][19] = "Advanced options gives you a better control of the import process. Only advance users should modify it.";
$LANG["datainjection"]["modelStep"][20] = "Try to establish network connection is possible";
$LANG["datainjection"]["modelStep"][21] = "Dates format";
$LANG["datainjection"]["modelStep"][22] = "dd-mm-yyyy";
$LANG["datainjection"]["modelStep"][23] = "mm-dd-yyyy";
$LANG["datainjection"]["modelStep"][24] = "yyyy-mm-dd";
$LANG["datainjection"]["modelStep"][25] = "1 234.56";
$LANG["datainjection"]["modelStep"][26] = "1 234,56";
$LANG["datainjection"]["modelStep"][27] = "1,234.56";
$LANG["datainjection"]["modelStep"][28] = "Float format";

$LANG["datainjection"]["deleteStep"][1] = "Confirm deletion";
$LANG["datainjection"]["deleteStep"][2] = "Watch out. If you delete the model, the mappings and informations will be deleting too.";
$LANG["datainjection"]["deleteStep"][3] = "Do you want to delete";
$LANG["datainjection"]["deleteStep"][4] = "permanently ?";
$LANG["datainjection"]["deleteStep"][5] = "The model";
$LANG["datainjection"]["deleteStep"][6] = "has been deleted.";
$LANG["datainjection"]["deleteStep"][7] = "A problem occured while deleting the model.";

$LANG["datainjection"]["fileStep"][1] = "Select file to upload";
$LANG["datainjection"]["fileStep"][2] = "Select a file in your harddrive to be uploaded on the server.";
$LANG["datainjection"]["fileStep"][3] = "Choose a file :";
$LANG["datainjection"]["fileStep"][4] = "The file could not be found";
$LANG["datainjection"]["fileStep"][5] = "File format is wrong";
$LANG["datainjection"]["fileStep"][6] = "Extension";
$LANG["datainjection"]["fileStep"][7] = "required";
$LANG["datainjection"]["fileStep"][8] = "Impossible to copy the file in";
$LANG["datainjection"]["fileStep"][9] = "File encoding :";
$LANG["datainjection"]["fileStep"][10] = "Automatic detection";
$LANG["datainjection"]["fileStep"][11] = "UTF-8";
$LANG["datainjection"]["fileStep"][12] = "ISO8859-1";

$LANG["datainjection"]["mappingStep"][1] = "columns to map have been found";
$LANG["datainjection"]["mappingStep"][2] = "Header of the file";
$LANG["datainjection"]["mappingStep"][3] = "Tables";
$LANG["datainjection"]["mappingStep"][4] = "Fields";
$LANG["datainjection"]["mappingStep"][5] = "Mandatory";
$LANG["datainjection"]["mappingStep"][6] = "-------Choose a table-------";
$LANG["datainjection"]["mappingStep"][7] = "-------Choose a field-------";
$LANG["datainjection"]["mappingStep"][8] = "You must select at least one mandatory mapping";
$LANG["datainjection"]["mappingStep"][9] = "This step allows you to map field's fields with ones in database.";
$LANG["datainjection"]["mappingStep"][10] = "The column headers corresponds to the header of your file";
$LANG["datainjection"]["mappingStep"][11] = "La notion obligatoire correspond à un champ qui devra absolument être connue dans le fichier, vous devez avoir au moins un mapping obligatoire avant de continuer l'assistant";
$LANG["datainjection"]["mappingStep"][12] = "Number of lines";

$LANG["datainjection"]["infoStep"][1] = "Complementary informations";
$LANG["datainjection"]["infoStep"][2] = "Modification of complementary informations";
$LANG["datainjection"]["infoStep"][3] = "This step allows you to add informations not present in the file. You'll be asked for theses informations while using the model.";
$LANG["datainjection"]["infoStep"][4] = "The are common to all the imported objects.";
$LANG["datainjection"]["infoStep"][5] = "Mandatory information";

$LANG["datainjection"]["saveStep"][1] = "Save the model";
$LANG["datainjection"]["saveStep"][2] = "Do you want to save the model ?";
$LANG["datainjection"]["saveStep"][3] = "Do you want to update the model ?";
$LANG["datainjection"]["saveStep"][4] = "Enter the name of the model :";
$LANG["datainjection"]["saveStep"][5] = "Add a comment :";
$LANG["datainjection"]["saveStep"][6] = "Your model has not been saved, but is still ready to use.";
$LANG["datainjection"]["saveStep"][7] = "Your model has not been updated, but is still ready to use.";
$LANG["datainjection"]["saveStep"][8] = "Your model has been saved, but is still ready to use.";
$LANG["datainjection"]["saveStep"][9] = "Your model has been updated, but is still ready to use.";
$LANG["datainjection"]["saveStep"][10] = "Do you want to use the model ?";
$LANG["datainjection"]["saveStep"][11] = "The number of columns of the file is incorrect.";
$LANG["datainjection"]["saveStep"][12] = "At least one column is incorrect";
$LANG["datainjection"]["saveStep"][13] = "Save model in order to use to later";
$LANG["datainjection"]["saveStep"][14] = "You'll only have to select it into the list in the first step.";
$LANG["datainjection"]["saveStep"][15] = "You can add a comment to add informations about the model.";
$LANG["datainjection"]["saveStep"][16] = " awaited column(s)";
$LANG["datainjection"]["saveStep"][17] = " found column(s)";
$LANG["datainjection"]["saveStep"][18] = " Into the file : ";
$LANG["datainjection"]["saveStep"][19] = " From the model : ";

$LANG["datainjection"]["fillInfoStep"][1] = "Watch out, you're about to inject datas into GLPI. Are you sure you want to do it ?";
$LANG["datainjection"]["fillInfoStep"][2] = "Fill the informations that must be inserted during injection.";
$LANG["datainjection"]["fillInfoStep"][3] = "* Mandatory field";
$LANG["datainjection"]["fillInfoStep"][4] = "One mandatory field is not filled";

$LANG["datainjection"]["importStep"][1] = "Injection fo the file";
$LANG["datainjection"]["importStep"][2] = "File injection could take several minutes depending of your configuration. Please wait.";
$LANG["datainjection"]["importStep"][3] = "Injection finished";

$LANG["datainjection"]["logStep"][1] = "Injection's results";
$LANG["datainjection"]["logStep"][2] = "You can watch or export injection's report by clicking on the watch or export buttons";
$LANG["datainjection"]["logStep"][3] = "Injection's successful";
$LANG["datainjection"]["logStep"][4] = "Array of successful injections";
$LANG["datainjection"]["logStep"][5] = "Array of unsuccessful injections";
$LANG["datainjection"]["logStep"][6] = "The 'Export in PDF' button allows you to save a report of the injection onto your harddrive.";
$LANG["datainjection"]["logStep"][7] = "The 'Generation the imported file' allows you to generate a CSV file with only the failed line during the process.";
$LANG["datainjection"]["logStep"][8] = "Injection encounters errors";

$LANG["datainjection"]["button"][1] = "< Previous";
$LANG["datainjection"]["button"][2] = "Next >";
$LANG["datainjection"]["button"][3] = "See the file";
$LANG["datainjection"]["button"][4] = "See the log";
$LANG["datainjection"]["button"][5] = "Export the log";
$LANG["datainjection"]["button"][6] = "Finish";
$LANG["datainjection"]["button"][7] = "Export rapport in PDF";
$LANG["datainjection"]["button"][8] = "Close";

$LANG["datainjection"]["result"][1] = "One data is not the good type";
$LANG["datainjection"]["result"][2] = "Datas to insert are incorrect";
$LANG["datainjection"]["result"][3] = "Datas are still in the database";
$LANG["datainjection"]["result"][4] = "At least one mandatory field is not present";
$LANG["datainjection"]["result"][5] = "You don't have rights to insert datas";
$LANG["datainjection"]["result"][6] = "You don't have rights to update datas";
$LANG["datainjection"]["result"][7] = "Import is successful";
$LANG["datainjection"]["result"][8] = "Add";
$LANG["datainjection"]["result"][9] = "Update";
$LANG["datainjection"]["result"][10] = "Checking datas";
$LANG["datainjection"]["result"][11] = "Datas injection";
$LANG["datainjection"]["result"][12] = "Type of injection";
$LANG["datainjection"]["result"][13] = "Object identifier";
$LANG["datainjection"]["result"][14] = "Line";
$LANG["datainjection"]["result"][15] = "Data not found";
$LANG["datainjection"]["result"][16] = "Data already used";
$LANG["datainjection"]["result"][17] = "No data to insert";
$LANG["datainjection"]["result"][18] = "Injection summary for file";
$LANG["datainjection"]["result"][19] = "More than one value found";
$LANG["datainjection"]["result"][20] = "Object is already linked";
$LANG["datainjection"]["result"][21] = "Import is impossible";

$LANG["datainjection"]["profiles"][1] = "Create a model";
$LANG["datainjection"]["profiles"][3] = "Use a model";
$LANG["datainjection"]["profiles"][4] = "List of profiles already configured";

$LANG["datainjection"]["mappings"][1] = "Number of ports";
$LANG["datainjection"]["mappings"][2] = "Network port";
$LANG["datainjection"]["mappings"][3] = "Connected to : device name";
$LANG["datainjection"]["mappings"][4] = "Connected to : port number";
$LANG["datainjection"]["mappings"][5] = "Computer";
$LANG["datainjection"]["mappings"][6] = "Connected to : port MAC address";
$LANG["datainjection"]["mappings"][7] = "Port unicity criteria";

$LANG["datainjection"]["history"][1] = "from CSV file";
$LANG["datainjection"]["logevent"][1] = "injection of a CSV file.";

$LANG["datainjection"]["entity"][0] = "Parent entity";
?>
