<?php
/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2005 by the INDEPNET Development Team.

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

$DATAINJECTIONLANG["headings"][1] = "File injection";
$DATAINJECTIONLANG["name"][1] = "File injection";
$title = $DATAINJECTIONLANG["name"][1] ;

$DATAINJECTIONLANG["config"][1]=$title." plugin configuration";

$DATAINJECTIONLANG["setup"][0] = "";
$DATAINJECTIONLANG["setup"][1] = $title." plugin configuration";
$DATAINJECTIONLANG["setup"][2] = "";
$DATAINJECTIONLANG["setup"][3] = "Install the plugin $title";
$DATAINJECTIONLANG["setup"][4] = "Update $title plugin to version";
$DATAINJECTIONLANG["setup"][5] = "Uninstall plugin $title";
$DATAINJECTIONLANG["setup"][6] = "Warning, the update is irreversible.";
$DATAINJECTIONLANG["setup"][7] = "Warning, the uninstallation of the plugin is irreversible.<br> You will loose all the data.";
$DATAINJECTIONLANG["setup"][8] = "Column";
$DATAINJECTIONLANG["setup"][9] = "Rights management";

$DATAINJECTIONLANG["presentation"][1] = "Welcome to the file injection wizard";
$DATAINJECTIONLANG["presentation"][2] = "This wizard allows you to import CSV files into GLPI";
$DATAINJECTIONLANG["presentation"][3] = "To start, click the Next button.";

$DATAINJECTIONLANG["step"][1] = "Step 1 : ";
$DATAINJECTIONLANG["step"][2] = "Step 2 : ";
$DATAINJECTIONLANG["step"][3] = "Step 3 : ";
$DATAINJECTIONLANG["step"][4] = "Step 4 : ";
$DATAINJECTIONLANG["step"][5] = "Step 5 : ";
$DATAINJECTIONLANG["step"][6] = "Step 6 : ";
$DATAINJECTIONLANG["step"][7] = "Step 7 : ";

$DATAINJECTIONLANG["choiceStep"][1] = "Model use or management";
$DATAINJECTIONLANG["choiceStep"][2] = "This first step allows you to create, modify, delete or use a model.";
$DATAINJECTIONLANG["choiceStep"][3] = "Create a new model";
$DATAINJECTIONLANG["choiceStep"][4] = "Modify an existing model";
$DATAINJECTIONLANG["choiceStep"][5] = "Delete an existing model";
$DATAINJECTIONLANG["choiceStep"][6] = "Use an existing model";
$DATAINJECTIONLANG["choiceStep"][7] = "Model's comments";
$DATAINJECTIONLANG["choiceStep"][8] = "No comments";
$DATAINJECTIONLANG["choiceStep"][9] = "Make your choice";


$DATAINJECTIONLANG["modelStep"][1] = "Collecting informations about the file";
$DATAINJECTIONLANG["modelStep"][2] = "Modification of the model";
$DATAINJECTIONLANG["modelStep"][3] = "Select the type of file to import.";
$DATAINJECTIONLANG["modelStep"][4] = "Type of datas to import :";
$DATAINJECTIONLANG["modelStep"][5] = "Type of file :";
$DATAINJECTIONLANG["modelStep"][6] = "Allow lines creation :";
$DATAINJECTIONLANG["modelStep"][7] = "Allow lines update :";
$DATAINJECTIONLANG["modelStep"][8] = "Allow creation of dropdowns :";
$DATAINJECTIONLANG["modelStep"][9] = "Header's presence :";
$DATAINJECTIONLANG["modelStep"][10] = "File delimitor :";
$DATAINJECTIONLANG["modelStep"][11] = "No delimiter defined";
$DATAINJECTIONLANG["modelStep"][12] = "Allow update of existing fields :";
$DATAINJECTIONLANG["modelStep"][13] = "Main informations";
$DATAINJECTIONLANG["modelStep"][14] = "CSV Options";
$DATAINJECTIONLANG["modelStep"][15] = "Advanced options";



$DATAINJECTIONLANG["deleteStep"][1] = "Confirm deletion";
$DATAINJECTIONLANG["deleteStep"][2] = "Watch out. If you delete the model, the mappings and informations will be deleting too.";
$DATAINJECTIONLANG["deleteStep"][3] = "Do you want to delete";
$DATAINJECTIONLANG["deleteStep"][4] = "permanently ?";
$DATAINJECTIONLANG["deleteStep"][5] = "The model";
$DATAINJECTIONLANG["deleteStep"][6] = "has been deleted.";
$DATAINJECTIONLANG["deleteStep"][7] = "A problem occured while deleting the model.";

$DATAINJECTIONLANG["fileStep"][1] = "Select file to upload";
$DATAINJECTIONLANG["fileStep"][2] = "Select a file in your harddrive to be uploaded on the server.";
$DATAINJECTIONLANG["fileStep"][3] = "Choose a file :";
$DATAINJECTIONLANG["fileStep"][4] = "The file could not be found";
$DATAINJECTIONLANG["fileStep"][5] = "File format is wrong";
$DATAINJECTIONLANG["fileStep"][6] = "Extension";
$DATAINJECTIONLANG["fileStep"][7] = "required";
$DATAINJECTIONLANG["fileStep"][8] = "Impossible to copy the file in";

$DATAINJECTIONLANG["mappingStep"][1] = "columns to map have been found";
$DATAINJECTIONLANG["mappingStep"][2] = "Header of the file";
$DATAINJECTIONLANG["mappingStep"][3] = "Tables";
$DATAINJECTIONLANG["mappingStep"][4] = "Fields";
$DATAINJECTIONLANG["mappingStep"][5] = "Mandatory";
$DATAINJECTIONLANG["mappingStep"][6] = "-------Choose a table-------";
$DATAINJECTIONLANG["mappingStep"][7] = "-------Choose a field-------";
$DATAINJECTIONLANG["mappingStep"][8] = "You must select at least one mandatory mapping";
$DATAINJECTIONLANG["mappingStep"][9] = "This step allows you to map field's fields with ones in database.";
$DATAINJECTIONLANG["mappingStep"][10] = "The column headers corresponds to the header of your file";
$DATAINJECTIONLANG["mappingStep"][11] = "La notion obligatoire correspond à un champ qui devra absolument être connue dans le fichier, vous devez avoir au moins un mapping obligatoire avant de continuer l'assistant";

$DATAINJECTIONLANG["infoStep"][1] = "Complementary informations";
$DATAINJECTIONLANG["infoStep"][2] = "Modification of complementary informations";
$DATAINJECTIONLANG["infoStep"][3] = "This step allows you to add informations not present in the file. You'll be asked for theses informations while using the model.";

$DATAINJECTIONLANG["saveStep"][1] = "Save the model";
$DATAINJECTIONLANG["saveStep"][2] = "Do you want to save the model ?";
$DATAINJECTIONLANG["saveStep"][3] = "Do you want to update the model ?";
$DATAINJECTIONLANG["saveStep"][4] = "Enter the name of the model :";
$DATAINJECTIONLANG["saveStep"][5] = "Add a comment :";
$DATAINJECTIONLANG["saveStep"][6] = "Your model has not been saved, but is still ready to use.";
$DATAINJECTIONLANG["saveStep"][7] = "Your model has not been updated, but is still ready to use.";
$DATAINJECTIONLANG["saveStep"][8] = "Your model has been saved, but is still ready to use.";
$DATAINJECTIONLANG["saveStep"][9] = "Your model has been updated, but is still ready to use.";
$DATAINJECTIONLANG["saveStep"][10] = "Do you want to use the model ?";
$DATAINJECTIONLANG["saveStep"][11] = "The number of columns of the file is incorrect.";
$DATAINJECTIONLANG["saveStep"][12] = "At least one column is incorrect";



$DATAINJECTIONLANG["fillInfoStep"][1] = "Watch out, you're about to inject datas into GLPI. Are you sure you want to do it ?";
$DATAINJECTIONLANG["fillInfoStep"][2] = "Fill the informations that must be inserted during injection.";
$DATAINJECTIONLANG["fillInfoStep"][3] = "* Mandatory field";
$DATAINJECTIONLANG["fillInfoStep"][4] = "One mandatory field is not filled";

$DATAINJECTIONLANG["importStep"][1] = "Injection fo the file";
$DATAINJECTIONLANG["importStep"][2] = "File injection could take several minutes depending of your configuration. Please wait.";
$DATAINJECTIONLANG["importStep"][3] = "Injection finished";

$DATAINJECTIONLANG["logStep"][1] = "Injection's results";
$DATAINJECTIONLANG["logStep"][2] = "You can watch or export injection's report by clicking on the watch or export buttons";
$DATAINJECTIONLANG["logStep"][3] = "Injection's successful";
$DATAINJECTIONLANG["logStep"][4] = "Array of successful injections";
$DATAINJECTIONLANG["logStep"][5] = "Array of unsuccessful injections";

$DATAINJECTIONLANG["button"][1] = "< Next";
$DATAINJECTIONLANG["button"][2] = "Previous >";
$DATAINJECTIONLANG["button"][3] = "See the file";
$DATAINJECTIONLANG["button"][4] = "See the log";
$DATAINJECTIONLANG["button"][5] = "Export the log";
$DATAINJECTIONLANG["button"][6] = "Finish";

$DATAINJECTIONLANG["result"][1] = "One data is not the good type";
$DATAINJECTIONLANG["result"][2] = "Datas to insert are incorrect";
$DATAINJECTIONLANG["result"][3] = "Datas are still in the database";
$DATAINJECTIONLANG["result"][4] = "At least one mandatory field is not present";
$DATAINJECTIONLANG["result"][5] = "You don't have rights to insert datas";
$DATAINJECTIONLANG["result"][6] = "You don't have rights to update datas";
$DATAINJECTIONLANG["result"][7] = "Import is successful";
$DATAINJECTIONLANG["result"][8] = "Add";
$DATAINJECTIONLANG["result"][9] = "Update";
$DATAINJECTIONLANG["result"][10] = "Checking datas";
$DATAINJECTIONLANG["result"][11] = "Datas injection";
$DATAINJECTIONLANG["result"][12] = "Type of injection";
$DATAINJECTIONLANG["result"][13] = "Object identifier";
$DATAINJECTIONLANG["result"][14] = "Line";

$DATAINJECTIONLANG["profiles"][1] = "Create a model";
$DATAINJECTIONLANG["profiles"][2] = "Delete model";
$DATAINJECTIONLANG["profiles"][3] = "Use a model";

$DATAINJECTIONLANG["mappings"][1] = "Number of ports";

$DATAINJECTIONLANG["history"][1] = "from CSV file";
$DATAINJECTIONLANG["logevent"][1] = "injection of a CSV file.";

?>