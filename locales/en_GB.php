<?php
/*
 * @version $Id: HEADER 14684 2011-06-11 06:32:40Z remi $
 LICENSE

 This file is part of the datainjection plugin.

 Datainjection plugin is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Datainjection plugin is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with datainjection. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 @package   datainjection
 @author    the datainjection plugin team
 @copyright Copyright (c) 2010-2013 Datainjection plugin team
 @license   GPLv2+
            http://www.gnu.org/licenses/gpl.txt
 @link      https://forge.indepnet.net/projects/datainjection
 @link      http://www.glpi-project.org/
 @since     2009
 ---------------------------------------------------------------------- */
 
$LANG['datainjection']['name'][1] = "File injection";

$LANG['datainjection']['choiceStep'][6] = "Use an existing model";

$LANG['datainjection']['model'][4]  = "Type of datas to import";
$LANG['datainjection']['model'][5]  = "Type of file";
$LANG['datainjection']['model'][6]  = "Allow lines creation";
$LANG['datainjection']['model'][7]  = "Allow lines update";
$LANG['datainjection']['model'][8]  = "Allow creation of dropdowns";
$LANG['datainjection']['model'][9]  = "Header's presence";
$LANG['datainjection']['model'][10] = "File delimitor";
$LANG['datainjection']['model'][12] = "Allow update of existing fields";
$LANG['datainjection']['model'][15] = "Advanced options";
$LANG['datainjection']['model'][18] = "Private";
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
$LANG['datainjection']['model'][30] = "Please enter a name for the model";
$LANG['datainjection']['model'][31] = "Your model should allow import and/or update of data”";
$LANG['datainjection']['model'][32] = "The file is ok.".
                     "<br>You can start doing the mapping with GLPI fields”";
$LANG['datainjection']['model'][33] = "No model currently available";
$LANG['datainjection']['model'][34] = "You can start the model creation by hitting the button";
$LANG['datainjection']['model'][35] = "Creation of the model on going";
$LANG['datainjection']['model'][36] = "Model available for use";
$LANG['datainjection']['model'][37] = "Validation";
$LANG['datainjection']['model'][38] = "Validate the model";
$LANG['datainjection']['model'][39] = "List of the models";
$LANG['datainjection']['model'][40] = "Download file sample";

$LANG['datainjection']['fileStep'][3]  = "Choose a file";
$LANG['datainjection']['fileStep'][4]  = "The file could not be found";
$LANG['datainjection']['fileStep'][5]  = "File format is wrong";
$LANG['datainjection']['fileStep'][6]  = "Extension";
$LANG['datainjection']['fileStep'][7]  = "required";
$LANG['datainjection']['fileStep'][8]  = "Impossible to copy the file in";
$LANG['datainjection']['fileStep'][9]  = "File encoding";
$LANG['datainjection']['fileStep'][10] = "Automatic detection";
$LANG['datainjection']['fileStep'][11] = "UTF-8";
$LANG['datainjection']['fileStep'][12] = "ISO8859-1";
$LANG['datainjection']['fileStep'][13] = "Load this file";

$LANG['datainjection']['mapping'][2]  = "Header of the file";
$LANG['datainjection']['mapping'][3]  = "Tables";
$LANG['datainjection']['mapping'][4]  = "Fields";
$LANG['datainjection']['mapping'][5]  = "Link field";
$LANG['datainjection']['mapping'][6]  = "-------Choose a table-------";
$LANG['datainjection']['mapping'][7]  = "-------Choose a field-------";
$LANG["datainjection"]["mapping"][8]  = "Connected to : device name";
$LANG["datainjection"]["mapping"][9]  = "Connected to : port number";
$LANG["datainjection"]["mapping"][10] = "Connected to : port MAC address";
$LANG['datainjection']['mapping'][11] = "One link field must be selected :<br> it will be used to check if data already exists";
$LANG['datainjection']['mapping'][13] = "Warning : existing data will be overridden";

$LANG['datainjection']['info'][1] = "Complementary informations";
$LANG['datainjection']['info'][3] = "This step allows you to add informations not present in the file. You'll be asked for theses informations while using the model.";
$LANG['datainjection']['info'][5] = "Mandatory information";

$LANG['datainjection']['saveStep'][11] = "The number of columns of the file is incorrect.";
$LANG['datainjection']['saveStep'][12] = "At least one column is incorrect";
$LANG['datainjection']['saveStep'][17] = " found column(s)";
$LANG['datainjection']['saveStep'][18] = " Into the file : ";
$LANG['datainjection']['saveStep'][19] = " From the model : ";

$LANG['datainjection']['fillInfoStep'][1] = "Watch out, you're about to inject datas into GLPI. Are you sure you want to do it ?";
$LANG['datainjection']['fillInfoStep'][3] = "* Mandatory field";
$LANG['datainjection']['fillInfoStep'][4] = "One mandatory field is not filled";

$LANG['datainjection']['importStep'][1] = "Injection of the file";
$LANG['datainjection']['importStep'][3] = "Injection finished";

$LANG['datainjection']['log'][1]  = "Injection's results";
$LANG['datainjection']['log'][3]  = "Injection successful";
$LANG['datainjection']['log'][4]  = "Array of successful injections";
$LANG['datainjection']['log'][5]  = "Array of unsuccessful injections";
$LANG['datainjection']['log'][8]  = "Injection encounters errors";
$LANG['datainjection']['log'][9]  = "Data check";
$LANG['datainjection']['log'][10] = "Data Import";
$LANG['datainjection']['log'][11] = "Injection type";
$LANG['datainjection']['log'][12] = "Object Identifier";
$LANG['datainjection']['log'][13] = "Line";

$LANG['datainjection']['button'][3] = "See the file";
$LANG['datainjection']['button'][4] = "See the log";
$LANG['datainjection']['button'][5] = "Export the log";
$LANG['datainjection']['button'][6] = "Finish";
$LANG['datainjection']['button'][7] = "Export rapport in PDF";
$LANG['datainjection']['button'][8] = "Close";

$LANG['datainjection']['result'][2]  = "Datas to insert are correct";
$LANG['datainjection']['result'][4]  = "At least one mandatory field is not present";
$LANG['datainjection']['result'][6]  = "Undetermined";
$LANG['datainjection']['result'][7]  = "Import is successful";
$LANG['datainjection']['result'][8]  = "Add";
$LANG['datainjection']['result'][9]  = "Update";
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
$LANG['datainjection']['result'][39] = "Import refused by the dictionnary";

$LANG['datainjection']['profiles'][1] = "Model management";

$LANG['datainjection']['mappings'][1] = "Number of ports";
$LANG['datainjection']['mappings'][7] = "Port unicity criteria";

$LANG['datainjection']['history'][1] = "from CSV file";

$LANG['datainjection']['model'][0] = "Model";

$LANG['datainjection']['tabs'][0] = "Mappings";
$LANG['datainjection']['tabs'][1] = "Additional Information";
$LANG['datainjection']['tabs'][2] = "Fixed values";
$LANG['datainjection']['tabs'][3] = "File to inject";

$LANG['datainjection']['import'][0] = "Launch the import";
$LANG['datainjection']['import'][1] = "Import progress";

$LANG['datainjection']['port'][1] = "Network link";
$LANG['datainjection']['entity'][1] = "Entity informations";

$LANG['datainjection']['install'][1] = "must exists and be writable for web server user";
?>