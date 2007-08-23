<?php
/*
 * @version $Id: rules.constant.php 5351 2007-08-07 11:57:46Z walid $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2007 by the INDEPNET Development Team.

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
 */

// ----------------------------------------------------------------------
// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------



// ----------------------------------------------------------------------
// ----------------------------------------------------------------------
//MAPPING
// ----------------------------------------------------------------------
// ----------------------------------------------------------------------

//Type of fields :
// - text : the data will be imported as a string
// - int : the data must be an int
// - date : the data must be a date


// ----------------------------------------------------------------------
//COMPUTER MAPPING
// ----------------------------------------------------------------------
global $LANG,$DATA_INJECTION_MAPPING,$IMPORT_TYPES,$IMPORT_PRIMARY_TYPES;
//Store all the type of items that could be imported
$IMPORT_TYPES = array(COMPUTER_TYPE, MONITOR_TYPE, PRINTER_TYPE, USER_TYPE,INFOCOM_TYPE);

$IMPORT_PRIMARY_TYPES = array(COMPUTER_TYPE, MONITOR_TYPE, PRINTER_TYPE, USER_TYPE);

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['name']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['name']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['name']['name']=$LANG["common"][16];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['name']['type']='text';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['serial']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['serial']['field']='serial';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['serial']['name']=$LANG["common"][19];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['serial']['type']='text';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['otherserial']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['otherserial']['field']='otherserial';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['otherserial']['name']=$LANG["common"][20];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['otherserial']['type']='text';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_number']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_number']['field']='os_license_number';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_number']['name']=$LANG["computers"][10];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_number']['type']='text';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_id']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_id']['field']='os_license_number';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_id']['name']=$LANG["computers"][11];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_id']['type']='text';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os']['table']='glpi_dropdown_os';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os']['name']=$LANG["computers"][9];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os']['linkfield']='os';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os']['type']='text';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_version']['table']='glpi_dropdown_os_version';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_version']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_version']['name']=$LANG["computers"][52];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_version']['linkfield']='os_version';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_version']['type']='text';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_version']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_sp']['table']='glpi_dropdown_os';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_sp']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_sp']['name']=$LANG["computers"][53];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_sp']['linkfield']='os_sp';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_sp']['type']='text';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_sp']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['location']['table']='glpi_dropdown_locations';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['location']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['location']['name']=$LANG["common"][15];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['location']['linkfield']='location';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['location']['type']='text';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['location']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['domain']['table']='glpi_dropdown_domain';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['domain']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['domain']['name']=$LANG["setup"][89];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['domain']['linkfield']='domain';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['domain']['type']='text';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['domain']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['contact']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['contact']['field']='contact';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['contact']['name']=$LANG["common"][18];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['contact']['type']='text';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['contactnum']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['contactnum']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['contactnum']['name']=$LANG["common"][21];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['contactnum']['type']='text';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['manufacturer']['table']='glpi_dropdown_manufacturer';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['manufacturer']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['manufacturer']['name']=$LANG["common"][5];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['manufacturer']['type']='text';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['manufacturer']['linkfield']='FK_glpi_enterprise';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['manufacturer']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['state']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['state']['table_type']='dropdown';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['state']['type']='text';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['type']['table']='glpi_type_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['type']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['type']['name']=$LANG["common"][17];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['type']['type']='text';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['type']['linkfield']='type';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['type']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['model']['table']='glpi_dropdown_model';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['model']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['model']['name']=$LANG["common"][22];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['model']['type']='text';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['model']['linkfield']='model';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['model']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['auto_update']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['auto_update']['field']='auto_update';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['auto_update']['name']=$LANG["ocsng"][6];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['auto_update']['type']='integer';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['network']['table']='glpi_dropdown_network';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['network']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['network']['name']=$LANG["setup"][88];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['network']['type']='text';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['network']['linkfield']='network';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['network']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['is_template']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['is_template']['field']='is_template';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['is_template']['name']=$LANG["common"][13];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['is_template']['type']='integer';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['notes']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['notes']['field']='notes';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['notes']['name']=$LANG["reminder"][2];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['notes']['type']='text';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['comments']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['comments']['field']='comments';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['comments']['name']=$LANG["common"][25];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['comments']['type']='text';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_number']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_number']['field']='os_license_number';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_number']['name']=$LANG["computers"][10];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_number']['type']='text';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_id']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_id']['field']='os_license_id';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_id']['name']=$LANG["computers"][11];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_id']['type']='text';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['ticket_tco']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['ticket_tco']['field']='ticket_tco';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['ticket_tco']['name']=$LANG["financial"][90];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['ticket_tco']['type']='text';


// ----------------------------------------------------------------------
//MONITOR MAPPING
// ----------------------------------------------------------------------
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['name']['table']='glpi_monitors';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['name']['field']='name';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['name']['name']=$LANG["common"][16];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['name']['type']='text';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['serial']['table']='glpi_monitors';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['serial']['field']='serial';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['serial']['name']=$LANG["common"][19];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['serial']['type']='text';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['otherserial']['table']='glpi_monitors';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['otherserial']['field']='otherserial';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['otherserial']['name']=$LANG["common"][20];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['otherserial']['type']='text';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['location']['table']='glpi_dropdown_locations';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['location']['field']='name';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['location']['name']=$LANG["common"][15];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['location']['linkfield']='location';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['location']['type']='text';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['location']['table_type']='dropdown';


$DATA_INJECTION_MAPPING[MONITOR_TYPE]['contact']['table']='glpi_monitors';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['contact']['field']='contact';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['contact']['name']=$LANG["common"][18];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['contact']['type']='text';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['contactnum']['table']='glpi_monitors';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['contactnum']['field']='name';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['contactnum']['name']=$LANG["common"][21];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['contactnum']['type']='text';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['comments']['table']='glpi_monitors';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['comments']['field']='comments';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['comments']['name']=$LANG["common"][25];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['comments']['type']='text';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['size']['table']='glpi_monitors';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['size']['field']='size';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['size']['name']=$LANG["monitors"][21];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['size']['type']='text';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_micro']['table']='glpi_monitors';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_micro']['field']='flags_micro';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_micro']['name']=$LANG["monitors"][14];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_micro']['type']='integer';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_speaker']['table']='glpi_monitors';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_speaker']['field']='flags_speaker';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_speaker']['name']=$LANG["monitors"][15];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_speaker']['type']='integer';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_subd']['table']='glpi_monitors';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_subd']['field']='flags_subd';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_subd']['name']=$LANG["monitors"][19];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_subd']['type']='integer';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_bnc']['table']='glpi_monitors';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_bnc']['field']='flags_bnc';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_bnc']['name']=$LANG["monitors"][20];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_bnc']['type']='integer';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_dvi']['table']='glpi_monitors';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_dvi']['field']='flags_dvi';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_dvi']['name']=$LANG["monitors"][32];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['flags_dvi']['type']='integer';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['type']['table']='glpi_type_monitors';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['type']['field']='name';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['type']['name']=$LANG["common"][17];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['type']['type']='text';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['type']['linkfield']='type';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['type']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['model']['table']='glpi_dropdown_model_monitors';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['model']['field']='name';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['model']['name']=$LANG["common"][22];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['model']['type']='text';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['model']['linkfield']='model';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['model']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['manufacturer']['table']='glpi_dropdown_manufacturer';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['manufacturer']['field']='name';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['manufacturer']['name']=$LANG["common"][5];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['manufacturer']['type']='text';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['manufacturer']['linkfield']='FK_glpi_enterprise';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['manufacturer']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['is_template']['table']='glpi_monitors';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['is_template']['field']='is_template';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['is_template']['name']=$LANG["common"][13];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['is_template']['type']='integer';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['tplname']['table']='glpi_monitors';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['tplname']['field']='tplname';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['tplname']['name']=$LANG["common"][6];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['tplname']['type']='text';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['notes']['table']='glpi_monitors';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['notes']['field']='notes';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['notes']['name']=$LANG["reminder"][2];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['notes']['type']='text';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['state']['field']='name';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['state']['table_type']='dropdown';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['state']['type']='text';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['ticket_tco']['table']='glpi_monitors';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['ticket_tco']['field']='ticket_tco';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['ticket_tco']['name']=$LANG["financial"][90];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['ticket_tco']['type']='text';

// ----------------------------------------------------------------------
//PRINTER MAPPING
// ----------------------------------------------------------------------

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['name']['table']='glpi_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['name']['field']='name';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['name']['name']=$LANG["common"][16];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['name']['type']='text';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['serial']['table']='glpi_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['serial']['field']='serial';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['serial']['name']=$LANG["common"][19];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['serial']['type']='text';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['otherserial']['table']='glpi_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['otherserial']['field']='otherserial';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['otherserial']['name']=$LANG["common"][20];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['otherserial']['type']='text';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['location']['table']='glpi_dropdown_locations';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['location']['field']='name';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['location']['name']=$LANG["common"][15];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['location']['linkfield']='location';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['location']['type']='text';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['location']['table_type']='dropdown';


$DATA_INJECTION_MAPPING[PRINTER_TYPE]['contact']['table']='glpi_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['contact']['field']='contact';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['contact']['name']=$LANG["common"][18];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['contact']['type']='text';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['contactnum']['table']='glpi_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['contactnum']['field']='name';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['contactnum']['name']=$LANG["common"][21];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['contactnum']['type']='text';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['comments']['table']='glpi_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['comments']['field']='comments';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['comments']['name']=$LANG["common"][25];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['comments']['type']='text';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['notes']['table']='glpi_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['notes']['field']='notes';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['notes']['name']=$LANG["reminder"][2];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['notes']['type']='text';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['state']['field']='name';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['state']['table_type']='dropdown';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['state']['type']='text';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['ramSize']['table']='glpi_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['ramSize']['field']='ramSize';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['ramSize']['name']=$LANG["devices"][6];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['ramSize']['type']='text';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['flags_serial']['table']='glpi_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['flags_serial']['field']='flags_serial';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['flags_serial']['name']=$LANG["setup"][175]." ".$LANG["printers"][14];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['flags_serial']['type']='integer';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['flags_par']['table']='glpi_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['flags_par']['field']='flags_par';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['flags_par']['name']=$LANG["setup"][175]." ".$LANG["printers"][15];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['flags_par']['type']='integer';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['flags_usb']['table']='glpi_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['flags_usb']['field']='flags_usb';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['flags_usb']['name']=$LANG["setup"][175]." ".$LANG["printers"][27];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['flags_usb']['type']='integer';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['domain']['table']='glpi_dropdown_domain';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['domain']['field']='name';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['domain']['name']=$LANG["setup"][89];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['domain']['linkfield']='domain';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['domain']['type']='text';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['domain']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['network']['table']='glpi_dropdown_network';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['network']['field']='name';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['network']['name']=$LANG["setup"][88];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['network']['type']='text';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['network']['linkfield']='network';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['network']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['type']['table']='glpi_type_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['type']['field']='name';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['type']['name']=$LANG["common"][17];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['type']['type']='text';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['type']['linkfield']='type';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['type']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['model']['table']='glpi_dropdown_model_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['model']['field']='name';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['model']['name']=$LANG["common"][22];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['model']['type']='text';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['model']['linkfield']='model';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['model']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['manufacturer']['table']='glpi_dropdown_manufacturer';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['manufacturer']['field']='name';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['manufacturer']['name']=$LANG["common"][5];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['manufacturer']['type']='text';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['manufacturer']['linkfield']='FK_glpi_enterprise';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['manufacturer']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['is_template']['table']='glpi_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['is_template']['field']='is_template';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['is_template']['name']=$LANG["common"][13];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['is_template']['type']='integer';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['tplname']['table']='glpi_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['tplname']['field']='tplname';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['tplname']['name']=$LANG["common"][6];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['tplname']['type']='text';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['ticket_tco']['table']='glpi_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['ticket_tco']['field']='ticket_tco';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['ticket_tco']['name']=$LANG["financial"][90];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['ticket_tco']['type']='text';
// ----------------------------------------------------------------------
//INFOCOM MAPPING
// ----------------------------------------------------------------------
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['buy_date']['table']='glpi_infocoms';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['buy_date']['field']='buy_date';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['buy_date']['name']=$LANG["financial"][14];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['buy_date']['type']='date';

$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['use_date']['table']='glpi_infocoms';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['use_date']['field']='use_date';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['use_date']['name']=$LANG["financial"][76];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['use_date']['type']='date';

$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['warranty_duration']['table']='glpi_infocoms';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['warranty_duration']['field']='warranty_duration';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['warranty_duration']['name']=$LANG["financial"][15];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['warranty_duration']['type']='integer';

$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['warranty_info']['table']='glpi_infocoms';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['warranty_info']['field']='warranty_info';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['warranty_info']['name']=$LANG["financial"][16];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['warranty_info']['type']='text';

$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['warranty_value']['table']='glpi_infocoms';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['warranty_value']['field']='warranty_value';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['warranty_value']['name']=$LANG["financial"][78];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['warranty_value']['type']='text';

$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['state']['field']='name';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['state']['table_type']='dropdown';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['state']['type']='text';

$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['manufacturer']['table']='glpi_dropdown_manufacturer';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['manufacturer']['field']='name';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['manufacturer']['name']=$LANG["common"][5];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['manufacturer']['type']='text';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['manufacturer']['linkfield']='FK_enterprise';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['manufacturer']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['num_commande']['table']='glpi_infocoms';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['num_commande']['field']='num_commande';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['num_commande']['name']=$LANG["financial"][18];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['num_commande']['type']='text';

$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['bon_livraison']['table']='glpi_infocoms';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['bon_livraison']['field']='bon_livraison';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['bon_livraison']['name']=$LANG["financial"][19];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['bon_livraison']['type']='text';

$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['num_immo']['table']='glpi_infocoms';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['num_immo']['field']='num_immo';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['num_immo']['name']=$LANG["financial"][20];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['num_immo']['type']='text';

$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['value']['table']='glpi_infocoms';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['value']['field']='value';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['value']['name']=$LANG["financial"][21];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['value']['type']='text';

$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['amort_time']['table']='glpi_infocoms';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['amort_time']['field']='amort_time';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['amort_time']['name']=$LANG["financial"][23];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['amort_time']['type']='integer';

$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['amort_type']['table']='glpi_infocoms';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['amort_type']['field']='amort_type';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['amort_type']['name']=$LANG["financial"][22];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['amort_type']['type']='integer';

$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['amort_coeff']['table']='glpi_infocoms';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['amort_coeff']['field']='amort_coeff';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['amort_coeff']['name']=$LANG["financial"][77];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['amort_coeff']['type']='float';

$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['comments']['table']='glpi_infocoms';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['comments']['field']='comments';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['comments']['name']=$LANG["common"][25];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['comments']['type']='text';

$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['facture']['table']='glpi_infocoms';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['facture']['field']='facture';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['facture']['name']=$LANG["financial"][82];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['facture']['type']='text';

$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['budget']['table']='glpi_dropdown_budget';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['budget']['field']='budget';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['budget']['name']=$LANG["financial"][87];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['budget']['type']='text';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['budget']['linkfield']='FK_enterprise';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['budget']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['alert']['table']='glpi_infocoms';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['alert']['field']='alert';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['alert']['name']=$LANG["setup"][247];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['alert']['type']='integer';

// ----------------------------------------------------------------------
//USER MAPPING
// ----------------------------------------------------------------------


$DATA_INJECTION_MAPPING[USER_TYPE]['name']['table']='glpi_users';
$DATA_INJECTION_MAPPING[USER_TYPE]['name']['field']='name';
$DATA_INJECTION_MAPPING[USER_TYPE]['name']['name']=$LANG["common"][21];
$DATA_INJECTION_MAPPING[USER_TYPE]['name']['type']='text';

$DATA_INJECTION_MAPPING[USER_TYPE]['password']['table']='glpi_users';
$DATA_INJECTION_MAPPING[USER_TYPE]['password']['field']='password';
$DATA_INJECTION_MAPPING[USER_TYPE]['password']['name']=$LANG["login"][7];
$DATA_INJECTION_MAPPING[USER_TYPE]['password']['type']='text';

$DATA_INJECTION_MAPPING[USER_TYPE]['email']['table']='glpi_users';
$DATA_INJECTION_MAPPING[USER_TYPE]['email']['field']='email';
$DATA_INJECTION_MAPPING[USER_TYPE]['email']['name']=$LANG["setup"][14];
$DATA_INJECTION_MAPPING[USER_TYPE]['email']['type']='text';

$DATA_INJECTION_MAPPING[USER_TYPE]['phone']['table']='glpi_users';
$DATA_INJECTION_MAPPING[USER_TYPE]['phone']['field']='phone';
$DATA_INJECTION_MAPPING[USER_TYPE]['phone']['name']=$LANG["phones"][4];
$DATA_INJECTION_MAPPING[USER_TYPE]['phone']['type']='text';

$DATA_INJECTION_MAPPING[USER_TYPE]['phone2']['table']='glpi_users';
$DATA_INJECTION_MAPPING[USER_TYPE]['phone2']['field']='phone2';
$DATA_INJECTION_MAPPING[USER_TYPE]['phone2']['name']=$LANG["phones"][4]." 2";
$DATA_INJECTION_MAPPING[USER_TYPE]['phone2']['type']='text';

$DATA_INJECTION_MAPPING[USER_TYPE]['mobile']['table']='glpi_users';
$DATA_INJECTION_MAPPING[USER_TYPE]['mobile']['field']='mobile';
$DATA_INJECTION_MAPPING[USER_TYPE]['mobile']['name']=$LANG["common"][42];
$DATA_INJECTION_MAPPING[USER_TYPE]['mobile']['type']='text';

$DATA_INJECTION_MAPPING[USER_TYPE]['realname']['table']='glpi_users';
$DATA_INJECTION_MAPPING[USER_TYPE]['realname']['field']='realname';
$DATA_INJECTION_MAPPING[USER_TYPE]['realname']['name']=$LANG["common"][48];
$DATA_INJECTION_MAPPING[USER_TYPE]['realname']['type']='text';

$DATA_INJECTION_MAPPING[USER_TYPE]['firstname']['table']='glpi_users';
$DATA_INJECTION_MAPPING[USER_TYPE]['firstname']['field']='firstname';
$DATA_INJECTION_MAPPING[USER_TYPE]['firstname']['name']=$LANG["common"][43];
$DATA_INJECTION_MAPPING[USER_TYPE]['firstname']['type']='text';

$DATA_INJECTION_MAPPING[USER_TYPE]['comments']['table']='glpi_users';
$DATA_INJECTION_MAPPING[USER_TYPE]['comments']['field']='comments';
$DATA_INJECTION_MAPPING[USER_TYPE]['comments']['name']=$LANG["common"][25];
$DATA_INJECTION_MAPPING[USER_TYPE]['comments']['type']='text';

$DATA_INJECTION_MAPPING[USER_TYPE]['location']['table']='glpi_dropdown_locations';
$DATA_INJECTION_MAPPING[USER_TYPE]['location']['field']='name';
$DATA_INJECTION_MAPPING[USER_TYPE]['location']['name']=$LANG["common"][15];
$DATA_INJECTION_MAPPING[USER_TYPE]['location']['linkfield']='location';
$DATA_INJECTION_MAPPING[USER_TYPE]['location']['type']='text';
$DATA_INJECTION_MAPPING[USER_TYPE]['location']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[USER_TYPE]['FK_profile']['table']='glpi_profiles';
$DATA_INJECTION_MAPPING[USER_TYPE]['FK_profile']['field']='name';
$DATA_INJECTION_MAPPING[USER_TYPE]['FK_profile']['name']=$LANG["profiles"][22];
$DATA_INJECTION_MAPPING[USER_TYPE]['FK_profile']['type']='text';
$DATA_INJECTION_MAPPING[USER_TYPE]['FK_profile']['table_type']='single';

?>
