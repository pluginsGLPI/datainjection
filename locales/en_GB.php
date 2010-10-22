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

$LANG['datainjection']['name'][1] = "File injection";

$LANG['datainjection']['choiceStep'][1] = "Model use or management";
$LANG['datainjection']['choiceStep'][2] = "This first step allows you to create, modify, delete or use a model.";
$LANG['datainjection']['choiceStep'][3] = "Create a new model";
$LANG['datainjection']['choiceStep'][4] = "Modify an existing model";
$LANG['datainjection']['choiceStep'][5] = "Delete an existing model";
$LANG['datainjection']['choiceStep'][6] = "Use an existing model";
$LANG['datainjection']['choiceStep'][7] = "Model's comments";
$LANG['datainjection']['choiceStep'][8] = "No comments";
$LANG['datainjection']['choiceStep'][9] = "Make your choice";
$LANG['datainjection']['choiceStep'][10] = "Depending on your rights, you may not access all the choices.";
$LANG['datainjection']['choiceStep'][11] = "You must select a model for use, update and deletion.";

$LANG['datainjection']['model'][1] = "Collecting informations about the file";
$LANG['datainjection']['model'][2] = "Modification of the model";
$LANG['datainjection']['model'][3] = "Les options dépendent du type de fichier sélectionné.";
$LANG['datainjection']['model'][4] = "Type of datas to import";
$LANG['datainjection']['model'][5] = "Type of file";
$LANG['datainjection']['model'][6] = "Allow lines creation";
$LANG['datainjection']['model'][7] = "Allow lines update";
$LANG['datainjection']['model'][8] = "Allow creation of dropdowns";
$LANG['datainjection']['model'][9] = "Header's presence";
$LANG['datainjection']['model'][10] = "File delimitor";
$LANG['datainjection']['model'][11] = "No delimiter defined";
$LANG['datainjection']['model'][12] = "Allow update of existing fields";
$LANG['datainjection']['model'][13] = "Main informations";
$LANG['datainjection']['model'][14] = "File Options";
$LANG['datainjection']['model'][15] = "Advanced options";
$LANG['datainjection']['model'][16] = "Diffusion";
$LANG['datainjection']['model'][17] = "Public";
$LANG['datainjection']['model'][18] = "Private";
$LANG['datainjection']['model'][19] = "Advanced options gives you a better control of the import process. Only advance users should modify it.";
$LANG['datainjection']['model'][20] = "Try to establish network connection is possible";
$LANG['datainjection']['model'][21] = "Dates format";
$LANG['datainjection']['model'][22] = "dd-mm-yyyy";
$LANG['datainjection']['model'][23] = "mm-dd-yyyy";
$LANG['datainjection']['model'][24] = "yyyy-mm-dd";
$LANG['datainjection']['model'][25] = "1 234.56";
$LANG['datainjection']['model'][26] = "1 234,56";
$LANG['datainjection']['model'][27] = "1,234.56";
$LANG['datainjection']['model'][28] = "Float format";
$LANG['datainjection']['model'][29] = "Specific file format options";

$LANG['datainjection']['model'][30] = "Vous devez spécifier un nom de modèle";
$LANG['datainjection']['model'][31] = "Votre modèle doit permettre l'import et/ou la mise à jour de données";
$LANG['datainjection']['model'][32] = "Le fichier est correct.".
                     "<br>Vous pouvez à présent faire les correspondances avec les champs de GLPI";
$LANG['datainjection']['model'][33] = "Aucun modèle n'est disponible actuellement";
$LANG['datainjection']['model'][34] = "Vous pouvez en créer un en cliquant sur le bouton";
$LANG['datainjection']['model'][35] = "Modèle en cours de création";
$LANG['datainjection']['model'][36] = "Modèle utilisable";
$LANG['datainjection']['model'][37] = "Validation";
$LANG['datainjection']['model'][38] = "Valider le modèle";
$LANG['datainjection']['model'][39] = "Liste des modèles";

$LANG['datainjection']['deleteStep'][1] = "Confirm deletion";
$LANG['datainjection']['deleteStep'][2] = "Watch out. If you delete the model, the mappings and informations will be deleting too.";
$LANG['datainjection']['deleteStep'][3] = "Do you want to delete";
$LANG['datainjection']['deleteStep'][4] = "permanently ?";
$LANG['datainjection']['deleteStep'][5] = "The model";
$LANG['datainjection']['deleteStep'][6] = "has been deleted.";
$LANG['datainjection']['deleteStep'][7] = "A problem occured while deleting the model.";

$LANG['datainjection']['fileStep'][1] = "Select file to upload";
$LANG['datainjection']['fileStep'][2] = "Select a file in your harddrive to be uploaded on the server.";
$LANG['datainjection']['fileStep'][3] = "Choose a file";
$LANG['datainjection']['fileStep'][4] = "The file could not be found";
$LANG['datainjection']['fileStep'][5] = "File format is wrong";
$LANG['datainjection']['fileStep'][6] = "Extension";
$LANG['datainjection']['fileStep'][7] = "required";
$LANG['datainjection']['fileStep'][8] = "Impossible to copy the file in";
$LANG['datainjection']['fileStep'][9] = "File encoding";
$LANG['datainjection']['fileStep'][10] = "Automatic detection";
$LANG['datainjection']['fileStep'][11] = "UTF-8";
$LANG['datainjection']['fileStep'][12] = "ISO8859-1";
$LANG['datainjection']['fileStep'][13] = "Load this file";

$LANG['datainjection']['mapping'][1] = "columns to map have been found";
$LANG['datainjection']['mapping'][2] = "Header of the file";
$LANG['datainjection']['mapping'][3] = "Tables";
$LANG['datainjection']['mapping'][4] = "Fields";
$LANG['datainjection']['mapping'][5] = "Link field";
$LANG['datainjection']['mapping'][6] = "-------Choose a table-------";
$LANG['datainjection']['mapping'][7] = "-------Choose a field-------";
$LANG['datainjection']['mapping'][8] = "You must select at least one link field";
$LANG['datainjection']['mapping'][9] = "This step allows you to map field's fields with ones in database.";
$LANG['datainjection']['mapping'][10] = "The column headers corresponds to the header of your file";
$LANG['datainjection']['mapping'][11] = "One link field must be selected :<br> it will be used to check if data already exists";
$LANG['datainjection']['mapping'][12] = "Number of lines";
$LANG['datainjection']['mapping'][13] = "Warning : existing data will be overridden";

$LANG['datainjection']['info'][1] = "Complementary informations";
$LANG['datainjection']['info'][2] = "Modification of complementary informations";
$LANG['datainjection']['info'][3] = "This step allows you to add informations not present in the file. You'll be asked for theses informations while using the model.";
$LANG['datainjection']['info'][4] = "The are common to all the imported objects.";
$LANG['datainjection']['info'][5] = "Mandatory information";

$LANG['datainjection']['saveStep'][1] = "Save the model";
$LANG['datainjection']['saveStep'][2] = "Do you want to save the model ?";
$LANG['datainjection']['saveStep'][3] = "Do you want to update the model ?";
$LANG['datainjection']['saveStep'][4] = "Enter the name of the model :";
$LANG['datainjection']['saveStep'][5] = "Add a comment :";
$LANG['datainjection']['saveStep'][6] = "Your model has not been saved, but is still ready to use.";
$LANG['datainjection']['saveStep'][7] = "Your model has not been updated, but is still ready to use.";
$LANG['datainjection']['saveStep'][8] = "Your model has been saved, but is still ready to use.";
$LANG['datainjection']['saveStep'][9] = "Your model has been updated, but is still ready to use.";
$LANG['datainjection']['saveStep'][10] = "Do you want to use the model ?";
$LANG['datainjection']['saveStep'][11] = "The number of columns of the file is incorrect.";
$LANG['datainjection']['saveStep'][12] = "At least one column is incorrect";
$LANG['datainjection']['saveStep'][13] = "Save model in order to use to later";
$LANG['datainjection']['saveStep'][14] = "You'll only have to select it into the list in the first step.";
$LANG['datainjection']['saveStep'][15] = "You can add a comment to add informations about the model.";
$LANG['datainjection']['saveStep'][16] = " awaited column(s)";
$LANG['datainjection']['saveStep'][17] = " found column(s)";
$LANG['datainjection']['saveStep'][18] = " Into the file : ";
$LANG['datainjection']['saveStep'][19] = " From the model : ";

$LANG['datainjection']['fillInfoStep'][1] = "Watch out, you're about to inject datas into GLPI. Are you sure you want to do it ?";
$LANG['datainjection']['fillInfoStep'][2] = "Fill the informations that must be inserted during injection.";
$LANG['datainjection']['fillInfoStep'][3] = "* Mandatory field";
$LANG['datainjection']['fillInfoStep'][4] = "One mandatory field is not filled";

$LANG['datainjection']['importStep'][1] = "Injection fo the file";
$LANG['datainjection']['importStep'][2] = "File injection could take several minutes depending of your configuration. Please wait.";
$LANG['datainjection']['importStep'][3] = "Injection finished";

$LANG['datainjection']['log'][1] = "Injection's results";
$LANG['datainjection']['log'][2] = "You can watch or export injection's report by clicking on the watch or export buttons";
$LANG['datainjection']['log'][3] = "Injection's successful";
$LANG['datainjection']['log'][4] = "Array of successful injections";
$LANG['datainjection']['log'][5] = "Array of unsuccessful injections";
$LANG['datainjection']['log'][6] = "The 'Export in PDF' button allows you to save a report of the injection onto your harddrive.";
$LANG['datainjection']['log'][7] = "The 'Generation the imported file' allows you to generate a CSV file with only the failed line during the process.";
$LANG['datainjection']['log'][8] = "Injection encounters errors";
$LANG['datainjection']['log'][9]  = "Data check";
$LANG['datainjection']['log'][10] = "Data Import";
$LANG['datainjection']['log'][11] = "Injection type";
$LANG['datainjection']['log'][12] = "Object Identifier";
$LANG['datainjection']['log'][13] = "Line";

$LANG['datainjection']['button'][1] = "< Previous";
$LANG['datainjection']['button'][2] = "Next >";
$LANG['datainjection']['button'][3] = "See the file";
$LANG['datainjection']['button'][4] = "See the log";
$LANG['datainjection']['button'][5] = "Export the log";
$LANG['datainjection']['button'][6] = "Finish";
$LANG['datainjection']['button'][7] = "Export rapport in PDF";
$LANG['datainjection']['button'][8] = "Close";

$LANG['datainjection']['result'][2] = "Datas to insert are correct";
$LANG['datainjection']['result'][4] = "At least one mandatory field is not present";
$LANG['datainjection']['result'][7] = "Import is successful";
$LANG['datainjection']['result'][8] = "Add";
$LANG['datainjection']['result'][9] = "Update";
$LANG['datainjection']['result'][10] = "Success";
$LANG['datainjection']['result'][11] = "Failure";
$LANG['datainjection']['result'][12] = "Warning";
$LANG['datainjection']['result'][17] = "No data to insert";
$LANG['datainjection']['result'][18] = "File injection report";
$LANG['datainjection']['result'][21] = "Import is impossible";
$LANG['datainjection']['result'][22] = "Bad type";
$LANG['datainjection']['result'][23] = "A mandatory field is mising";

$LANG['datainjection']['result'][30] = "Data already exists";
$LANG['datainjection']['result'][31] = "No right to import data";
$LANG['datainjection']['result'][32] = "No right to update data";
$LANG['datainjection']['result'][33] = "Data not found";
$LANG['datainjection']['result'][34] = "Data already used";
$LANG['datainjection']['result'][35] = "More than one value fouond";
$LANG['datainjection']['result'][36] = "Object is already linked";
$LANG['datainjection']['result'][37] = "Maximum field size exceeded";

$LANG['datainjection']['profiles'][1] = "Model management";
$LANG['datainjection']['profiles'][4] = "List of profiles already configured";

$LANG['datainjection']['mappings'][1] = "Number of ports";
$LANG['datainjection']['mappings'][2] = "Network port";
$LANG['datainjection']['mappings'][3] = "Connected to : device name";
$LANG['datainjection']['mappings'][4] = "Connected to : port number";
$LANG['datainjection']['mappings'][5] = "Computer";
$LANG['datainjection']['mappings'][6] = "Connected to : port MAC address";
$LANG['datainjection']['mappings'][7] = "Port unicity criteria";

$LANG['datainjection']['history'][1] = "from CSV file";

$LANG['datainjection']['logevent'][1] = "injection of a CSV file.";

$LANG['datainjection']['entity'][0] = "Parent entity";

$LANG['datainjection']['associate'][0] = "Object association";

$LANG['datainjection']['model'][0] = "Model";

$LANG['datainjection']['tabs'][0] = "Mappings";
$LANG['datainjection']['tabs'][1] = "Additional Infoormation";
$LANG['datainjection']['tabs'][2] = "Fixed values";
$LANG['datainjection']['tabs'][3] = "File to inject";

$LANG['datainjection']['import'][0] = "Launch the import";
$LANG['datainjection']['import'][1] = "Import progress";
?>
