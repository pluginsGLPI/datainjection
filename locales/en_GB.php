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

$LANG["data_injection"]["name"][1] = "File injection";
$LANG["data_injection"]["setup"][1] = "Rights management";

$LANG["data_injection"]["presentation"][1] = "Welcome to the file injection wizard";
$LANG["data_injection"]["presentation"][2] = "This wizard allows you to import CSV files into GLPI";
$LANG["data_injection"]["presentation"][3] = "To start, click the Next button.";

$LANG["data_injection"]["step"][1] = "Step 1 : ";
$LANG["data_injection"]["step"][2] = "Step 2 : ";
$LANG["data_injection"]["step"][3] = "Step 3 : ";
$LANG["data_injection"]["step"][4] = "Step 4 : ";
$LANG["data_injection"]["step"][5] = "Step 5 : ";
$LANG["data_injection"]["step"][6] = "Step 6 : ";

$LANG["data_injection"]["choiceStep"][1] = "Model use or management";
$LANG["data_injection"]["choiceStep"][2] = "This first step allows you to create, modify, delete or use a model.";
$LANG["data_injection"]["choiceStep"][3] = "Create a new model";
$LANG["data_injection"]["choiceStep"][4] = "Modify an existing model";
$LANG["data_injection"]["choiceStep"][5] = "Delete an existing model";
$LANG["data_injection"]["choiceStep"][6] = "Use an existing model";
$LANG["data_injection"]["choiceStep"][7] = "Model's comments";
$LANG["data_injection"]["choiceStep"][8] = "No comments";
$LANG["data_injection"]["choiceStep"][9] = "Make your choice";
$LANG["data_injection"]["choiceStep"][10] = "Depending on your rights, you may not access all the choices.";
$LANG["data_injection"]["choiceStep"][11] = "You must select a model for use, update and deletion.";

$LANG["data_injection"]["modelStep"][1] = "Collecting informations about the file";
$LANG["data_injection"]["modelStep"][2] = "Modification of the model";
$LANG["data_injection"]["modelStep"][3] = "Select the type of file to import.";
$LANG["data_injection"]["modelStep"][4] = "Type of datas to import :";
$LANG["data_injection"]["modelStep"][5] = "Type of file :";
$LANG["data_injection"]["modelStep"][6] = "Allow lines creation :";
$LANG["data_injection"]["modelStep"][7] = "Allow lines update :";
$LANG["data_injection"]["modelStep"][8] = "Allow creation of dropdowns :";
$LANG["data_injection"]["modelStep"][9] = "Header's presence :";
$LANG["data_injection"]["modelStep"][10] = "File delimitor :";
$LANG["data_injection"]["modelStep"][11] = "No delimiter defined";
$LANG["data_injection"]["modelStep"][12] = "Allow update of existing fields :";
$LANG["data_injection"]["modelStep"][13] = "Main informations";
$LANG["data_injection"]["modelStep"][14] = "CSV Options";
$LANG["data_injection"]["modelStep"][15] = "Advanced options";
$LANG["data_injection"]["modelStep"][16] = "Diffusion :";
$LANG["data_injection"]["modelStep"][17] = "Public";
$LANG["data_injection"]["modelStep"][18] = "Private";
$LANG["data_injection"]["modelStep"][19] = "Advanced options gives you a better control of the import process. Only advance users should modify it.";
$LANG["data_injection"]["modelStep"][20] = "Try to establish network connection is possible";
$LANG["data_injection"]["modelStep"][21] = "Dates format";
$LANG["data_injection"]["modelStep"][22] = "dd-mm-yyyy";
$LANG["data_injection"]["modelStep"][23] = "mm-dd-yyyy";
$LANG["data_injection"]["modelStep"][24] = "yyyy-mm-dd";

$LANG["data_injection"]["deleteStep"][1] = "Confirm deletion";
$LANG["data_injection"]["deleteStep"][2] = "Watch out. If you delete the model, the mappings and informations will be deleting too.";
$LANG["data_injection"]["deleteStep"][3] = "Do you want to delete";
$LANG["data_injection"]["deleteStep"][4] = "permanently ?";
$LANG["data_injection"]["deleteStep"][5] = "The model";
$LANG["data_injection"]["deleteStep"][6] = "has been deleted.";
$LANG["data_injection"]["deleteStep"][7] = "A problem occured while deleting the model.";

$LANG["data_injection"]["fileStep"][1] = "Select file to upload";
$LANG["data_injection"]["fileStep"][2] = "Select a file in your harddrive to be uploaded on the server.";
$LANG["data_injection"]["fileStep"][3] = "Choose a file :";
$LANG["data_injection"]["fileStep"][4] = "The file could not be found";
$LANG["data_injection"]["fileStep"][5] = "File format is wrong";
$LANG["data_injection"]["fileStep"][6] = "Extension";
$LANG["data_injection"]["fileStep"][7] = "required";
$LANG["data_injection"]["fileStep"][8] = "Impossible to copy the file in";
$LANG["data_injection"]["fileStep"][9] = "File encoding :";
$LANG["data_injection"]["fileStep"][10] = "Automatic detection";
$LANG["data_injection"]["fileStep"][11] = "UTF-8";
$LANG["data_injection"]["fileStep"][12] = "ISO8859-1";

$LANG["data_injection"]["mappingStep"][1] = "columns to map have been found";
$LANG["data_injection"]["mappingStep"][2] = "Header of the file";
$LANG["data_injection"]["mappingStep"][3] = "Tables";
$LANG["data_injection"]["mappingStep"][4] = "Fields";
$LANG["data_injection"]["mappingStep"][5] = "Mandatory";
$LANG["data_injection"]["mappingStep"][6] = "-------Choose a table-------";
$LANG["data_injection"]["mappingStep"][7] = "-------Choose a field-------";
$LANG["data_injection"]["mappingStep"][8] = "You must select at least one mandatory mapping";
$LANG["data_injection"]["mappingStep"][9] = "This step allows you to map field's fields with ones in database.";
$LANG["data_injection"]["mappingStep"][10] = "The column headers corresponds to the header of your file";
$LANG["data_injection"]["mappingStep"][11] = "La notion obligatoire correspond à un champ qui devra absolument être connue dans le fichier, vous devez avoir au moins un mapping obligatoire avant de continuer l'assistant";
$LANG["data_injection"]["mappingStep"][12] = "Number of lines";

$LANG["data_injection"]["infoStep"][1] = "Complementary informations";
$LANG["data_injection"]["infoStep"][2] = "Modification of complementary informations";
$LANG["data_injection"]["infoStep"][3] = "This step allows you to add informations not present in the file. You'll be asked for theses informations while using the model.";
$LANG["data_injection"]["infoStep"][4] = "The are common to all the imported objects.";
$LANG["data_injection"]["infoStep"][5] = "Mandatory information";

$LANG["data_injection"]["saveStep"][1] = "Save the model";
$LANG["data_injection"]["saveStep"][2] = "Do you want to save the model ?";
$LANG["data_injection"]["saveStep"][3] = "Do you want to update the model ?";
$LANG["data_injection"]["saveStep"][4] = "Enter the name of the model :";
$LANG["data_injection"]["saveStep"][5] = "Add a comment :";
$LANG["data_injection"]["saveStep"][6] = "Your model has not been saved, but is still ready to use.";
$LANG["data_injection"]["saveStep"][7] = "Your model has not been updated, but is still ready to use.";
$LANG["data_injection"]["saveStep"][8] = "Your model has been saved, but is still ready to use.";
$LANG["data_injection"]["saveStep"][9] = "Your model has been updated, but is still ready to use.";
$LANG["data_injection"]["saveStep"][10] = "Do you want to use the model ?";
$LANG["data_injection"]["saveStep"][11] = "The number of columns of the file is incorrect.";
$LANG["data_injection"]["saveStep"][12] = "At least one column is incorrect";
$LANG["data_injection"]["saveStep"][13] = "Save model in order to use to later";
$LANG["data_injection"]["saveStep"][14] = "You'll only have to select it into the list in the first step.";
$LANG["data_injection"]["saveStep"][15] = "You can add a comment to add informations about the model.";
$LANG["data_injection"]["saveStep"][16] = " awaited column(s)";
$LANG["data_injection"]["saveStep"][17] = " found column(s)";
$LANG["data_injection"]["saveStep"][18] = " Into the file : ";
$LANG["data_injection"]["saveStep"][19] = " From the model : ";

$LANG["data_injection"]["fillInfoStep"][1] = "Watch out, you're about to inject datas into GLPI. Are you sure you want to do it ?";
$LANG["data_injection"]["fillInfoStep"][2] = "Fill the informations that must be inserted during injection.";
$LANG["data_injection"]["fillInfoStep"][3] = "* Mandatory field";
$LANG["data_injection"]["fillInfoStep"][4] = "One mandatory field is not filled";

$LANG["data_injection"]["importStep"][1] = "Injection fo the file";
$LANG["data_injection"]["importStep"][2] = "File injection could take several minutes depending of your configuration. Please wait.";
$LANG["data_injection"]["importStep"][3] = "Injection finished";

$LANG["data_injection"]["logStep"][1] = "Injection's results";
$LANG["data_injection"]["logStep"][2] = "You can watch or export injection's report by clicking on the watch or export buttons";
$LANG["data_injection"]["logStep"][3] = "Injection's successful";
$LANG["data_injection"]["logStep"][4] = "Array of successful injections";
$LANG["data_injection"]["logStep"][5] = "Array of unsuccessful injections";
$LANG["data_injection"]["logStep"][6] = "The 'Export in PDF' button allows you to save a report of the injection onto your harddrive.";
$LANG["data_injection"]["logStep"][7] = "The 'Generation the imported file' allows you to generate a CSV file with only the failed line during the process.";
$LANG["data_injection"]["logStep"][8] = "Injection encounters errors";

$LANG["data_injection"]["button"][1] = "< Previous";
$LANG["data_injection"]["button"][2] = "Next >";
$LANG["data_injection"]["button"][3] = "See the file";
$LANG["data_injection"]["button"][4] = "See the log";
$LANG["data_injection"]["button"][5] = "Export the log";
$LANG["data_injection"]["button"][6] = "Finish";
$LANG["data_injection"]["button"][7] = "Export rapport in PDF";
$LANG["data_injection"]["button"][8] = "Close";

$LANG["data_injection"]["result"][1] = "One data is not the good type";
$LANG["data_injection"]["result"][2] = "Datas to insert are incorrect";
$LANG["data_injection"]["result"][3] = "Datas are still in the database";
$LANG["data_injection"]["result"][4] = "At least one mandatory field is not present";
$LANG["data_injection"]["result"][5] = "You don't have rights to insert datas";
$LANG["data_injection"]["result"][6] = "You don't have rights to update datas";
$LANG["data_injection"]["result"][7] = "Import is successful";
$LANG["data_injection"]["result"][8] = "Add";
$LANG["data_injection"]["result"][9] = "Update";
$LANG["data_injection"]["result"][10] = "Checking datas";
$LANG["data_injection"]["result"][11] = "Datas injection";
$LANG["data_injection"]["result"][12] = "Type of injection";
$LANG["data_injection"]["result"][13] = "Object identifier";
$LANG["data_injection"]["result"][14] = "Line";
$LANG["data_injection"]["result"][15] = "Data not found";
$LANG["data_injection"]["result"][16] = "Data already used";
$LANG["data_injection"]["result"][17] = "No data to insert";
$LANG["data_injection"]["result"][18] = "Injection summary for file";

$LANG["data_injection"]["profiles"][1] = "Create a model";
$LANG["data_injection"]["profiles"][3] = "Use a model";
$LANG["data_injection"]["profiles"][4] = "List of profiles already configured";

$LANG["data_injection"]["mappings"][1] = "Number of ports";
$LANG["data_injection"]["mappings"][2] = "Network port";
$LANG["data_injection"]["mappings"][3] = "Connected to : device name";
$LANG["data_injection"]["mappings"][4] = "Connected to : port number";
$LANG["data_injection"]["mappings"][5] = "Computer";

$LANG["data_injection"]["history"][1] = "from CSV file";
$LANG["data_injection"]["logevent"][1] = "injection of a CSV file.";

$LANG["data_injection"]["entity"][0] = "Parent entity";
?>
