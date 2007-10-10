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



global $LANG,$DATA_INJECTION_MAPPING,$IMPORT_TYPES,$IMPORT_PRIMARY_TYPES,$DATAINJECTIONLANG;
//Store all the type of items that could be imported
$IMPORT_TYPES = array(COMPUTER_TYPE, MONITOR_TYPE, PRINTER_TYPE,PHONE_TYPE, USER_TYPE, INFOCOM_TYPE, NETWORKING_TYPE, GROUP_TYPE, CONTRACT_TYPE, PERIPHERAL_TYPE);
$IMPORT_PRIMARY_TYPES = array(COMPUTER_TYPE, MONITOR_TYPE, PRINTER_TYPE, PHONE_TYPE, USER_TYPE, NETWORKING_TYPE, GROUP_TYPE, CONTRACT_TYPE, PERIPHERAL_TYPE);

// ----------------------------------------------------------------------
//COMPUTER MAPPING
// ----------------------------------------------------------------------
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
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_id']['field']='os_license_id';
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

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_sp']['table']='glpi_dropdown_os_sp';
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
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['contactnum']['field']='contact_num';
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
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['notes']['table_type']='multitext';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['comments']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['comments']['field']='comments';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['comments']['name']=$LANG["common"][25];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['comments']['type']='text';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['comments']['table_type']='multitext';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_number']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_number']['field']='os_license_number';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_number']['name']=$LANG["computers"][10];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_number']['type']='text';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_id']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_id']['field']='os_license_id';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_id']['name']=$LANG["computers"][11];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_id']['type']='text';


$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['FK_groups']['table']='glpi_groups';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['FK_groups']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['FK_groups']['name']=$LANG["common"][35];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['FK_groups']['linkfield']='FK_groups';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['FK_groups']['type']='text';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['FK_groups']['table_type']='single';


$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['tech_num']['table']='glpi_users';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['tech_num']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['tech_num']['name']=$LANG["common"][10];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['tech_num']['linkfield']='tech_num';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['tech_num']['type']='text';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['tech_num']['table_type']='user';


$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['FK_users']['table']='glpi_users';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['FK_users']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['FK_users']['name']=$LANG["common"][34];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['FK_users']['linkfield']='FK_users';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['FK_users']['type']='text';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['FK_users']['table_type']='user';

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
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['contactnum']['field']='contact_num';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['contactnum']['name']=$LANG["common"][21];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['contactnum']['type']='text';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['comments']['table']='glpi_monitors';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['comments']['field']='comments';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['comments']['name']=$LANG["common"][25];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['comments']['type']='text';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['comments']['table_type']='multitext';

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
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['notes']['table_type']='multitext';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['state']['field']='name';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['state']['table_type']='dropdown';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['state']['type']='text';

$DATA_INJECTION_MAPPING[MONITOR_TYPE]['FK_groups']['table']='glpi_groups';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['FK_groups']['field']='name';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['FK_groups']['name']=$LANG["common"][35];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['FK_groups']['linkfield']='FK_groups';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['FK_groups']['type']='text';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['FK_groups']['table_type']='single';


$DATA_INJECTION_MAPPING[MONITOR_TYPE]['tech_num']['table']='glpi_users';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['tech_num']['field']='name';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['tech_num']['name']=$LANG["common"][10];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['tech_num']['linkfield']='tech_num';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['tech_num']['type']='text';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['tech_num']['table_type']='user';


$DATA_INJECTION_MAPPING[MONITOR_TYPE]['FK_users']['table']='glpi_users';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['FK_users']['field']='name';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['FK_users']['name']=$LANG["common"][34];
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['FK_users']['linkfield']='FK_users';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['FK_users']['type']='text';
$DATA_INJECTION_MAPPING[MONITOR_TYPE]['FK_users']['table_type']='user';

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
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['contactnum']['field']='contact_num';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['contactnum']['name']=$LANG["common"][21];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['contactnum']['type']='text';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['comments']['table']='glpi_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['comments']['field']='comments';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['comments']['name']=$LANG["common"][25];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['comments']['type']='text';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['comments']['table_type']='multitext';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['notes']['table']='glpi_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['notes']['field']='notes';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['notes']['name']=$LANG["reminder"][2];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['notes']['type']='text';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['notes']['table_type']='multitext';

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
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['manufacturer']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['is_template']['table']='glpi_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['is_template']['field']='is_template';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['is_template']['name']=$LANG["common"][13];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['is_template']['type']='integer';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['tplname']['table']='glpi_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['tplname']['field']='tplname';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['tplname']['name']=$LANG["common"][6];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['tplname']['type']='text';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['FK_groups']['table']='glpi_groups';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['FK_groups']['field']='name';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['FK_groups']['name']=$LANG["common"][35];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['FK_groups']['linkfield']='FK_groups';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['FK_groups']['type']='text';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['FK_groups']['table_type']='single';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['tech_num']['table']='glpi_users';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['tech_num']['field']='name';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['tech_num']['name']=$LANG["common"][10];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['tech_num']['linkfield']='tech_num';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['tech_num']['type']='text';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['tech_num']['table_type']='user';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['initial_pages']['table']='glpi_printers';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['initial_pages']['field']='initial_pages';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['initial_pages']['name']=$LANG["printers"][30];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['initial_pages']['type']='integer';

$DATA_INJECTION_MAPPING[PRINTER_TYPE]['FK_users']['table']='glpi_users';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['FK_users']['field']='name';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['FK_users']['name']=$LANG["common"][34];
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['FK_users']['linkfield']='FK_users';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['FK_users']['type']='text';
$DATA_INJECTION_MAPPING[PRINTER_TYPE]['FK_users']['table_type']='user';

// ----------------------------------------------------------------------
//PHONE MAPPING
// ----------------------------------------------------------------------

$DATA_INJECTION_MAPPING[PHONE_TYPE]['name']['table']='glpi_phones';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['name']['field']='name';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['name']['name']=$LANG["common"][16];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['name']['type']='text';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['serial']['table']='glpi_phones';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['serial']['field']='serial';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['serial']['name']=$LANG["common"][19];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['serial']['type']='text';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['otherserial']['table']='glpi_phones';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['otherserial']['field']='otherserial';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['otherserial']['name']=$LANG["common"][20];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['otherserial']['type']='text';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['location']['table']='glpi_dropdown_locations';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['location']['field']='name';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['location']['name']=$LANG["common"][15];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['location']['linkfield']='location';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['location']['type']='text';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['location']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['contact']['table']='glpi_phones';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['contact']['field']='contact';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['contact']['name']=$LANG["common"][18];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['contact']['type']='text';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['contactnum']['table']='glpi_phones';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['contactnum']['field']='contact_num';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['contactnum']['name']=$LANG["common"][21];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['contactnum']['type']='text';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['comments']['table']='glpi_phones';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['comments']['field']='comments';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['comments']['name']=$LANG["common"][25];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['comments']['type']='text';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['comments']['table_type']='multitext';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['notes']['table']='glpi_phones';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['notes']['field']='notes';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['notes']['name']=$LANG["reminder"][2];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['notes']['type']='text';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['notes']['table_type']='multitext';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['state']['field']='name';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['state']['table_type']='dropdown';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['state']['type']='text';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['type']['table']='glpi_type_phones';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['type']['field']='name';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['type']['name']=$LANG["common"][17];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['type']['type']='text';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['type']['linkfield']='type';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['type']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['model']['table']='glpi_dropdown_model_phones';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['model']['field']='name';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['model']['name']=$LANG["common"][22];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['model']['type']='text';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['model']['linkfield']='model';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['model']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['manufacturer']['table']='glpi_dropdown_manufacturer';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['manufacturer']['field']='name';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['manufacturer']['name']=$LANG["common"][5];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['manufacturer']['type']='text';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['manufacturer']['linkfield']='FK_glpi_enterprise';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['manufacturer']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['is_template']['table']='glpi_phones';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['is_template']['field']='is_template';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['is_template']['name']=$LANG["common"][13];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['is_template']['type']='integer';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['tplname']['table']='glpi_phones';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['tplname']['field']='tplname';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['tplname']['name']=$LANG["common"][6];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['tplname']['type']='text';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['brand']['table']='glpi_phones';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['brand']['field']='brand';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['brand']['name']=$LANG["peripherals"][18];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['brand']['type']='text';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['power']['table']='glpi_dropdown_phone_power';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['power']['field']='name';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['power']['name']=$LANG["devices"][23];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['power']['type']='text';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['power']['linkfield']='power';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['power']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['number_line']['table']='glpi_phones';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['number_line']['field']='number_line';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['number_line']['name']=$LANG["phones"][40];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['number_line']['type']='text';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['flags_casque']['table']='glpi_phones';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['flags_casque']['field']='flags_casque';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['flags_casque']['name']=$LANG["phones"][38];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['flags_casque']['type']='integer';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['flags_hp']['table']='glpi_phones';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['flags_hp']['field']='flags_hp';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['flags_hp']['name']=$LANG["phones"][39];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['flags_hp']['type']='integer';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['firmware']['table']='glpi_phones';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['firmware']['field']='firmware';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['firmware']['name']=$LANG["phones"][35];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['firmware']['type']='text';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['FK_groups']['table']='glpi_groups';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['FK_groups']['field']='name';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['FK_groups']['name']=$LANG["common"][35];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['FK_groups']['linkfield']='FK_groups';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['FK_groups']['type']='text';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['FK_groups']['table_type']='single';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['tech_num']['table']='glpi_users';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['tech_num']['field']='name';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['tech_num']['name']=$LANG["common"][10];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['tech_num']['linkfield']='tech_num';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['tech_num']['type']='text';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['tech_num']['table_type']='user';

$DATA_INJECTION_MAPPING[PHONE_TYPE]['FK_users']['table']='glpi_users';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['FK_users']['field']='name';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['FK_users']['name']=$LANG["common"][34];
$DATA_INJECTION_MAPPING[PHONE_TYPE]['FK_users']['linkfield']='FK_users';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['FK_users']['type']='text';
$DATA_INJECTION_MAPPING[PHONE_TYPE]['FK_users']['table_type']='user';

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

$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['manufacturer']['table']='glpi_dropdown_manufacturer';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['manufacturer']['field']='name';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['manufacturer']['name']=$LANG["financial"][26];
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
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['budget']['field']='name';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['budget']['name']=$LANG["financial"][87];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['budget']['type']='text';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['budget']['linkfield']='budget';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['budget']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['alert']['table']='glpi_infocoms';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['alert']['field']='alert';
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['alert']['name']=$LANG["setup"][247];
$DATA_INJECTION_MAPPING[INFOCOM_TYPE]['alert']['type']='integer';


// ----------------------------------------------------------------------
//NETWORK MAPPING
// ----------------------------------------------------------------------
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['name']['table']='glpi_networking';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['name']['field']='name';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['name']['name']=$LANG["common"][16];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['name']['type']='text';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['serial']['table']='glpi_networking';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['serial']['field']='serial';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['serial']['name']=$LANG["common"][19];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['serial']['type']='text';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['otherserial']['table']='glpi_networking';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['otherserial']['field']='otherserial';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['otherserial']['name']=$LANG["common"][20];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['otherserial']['type']='text';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['location']['table']='glpi_dropdown_locations';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['location']['field']='name';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['location']['name']=$LANG["common"][15];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['location']['linkfield']='location';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['location']['type']='text';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['location']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['contact']['table']='glpi_networking';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['contact']['field']='contact';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['contact']['name']=$LANG["common"][18];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['contact']['type']='text';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['contactnum']['table']='glpi_networking';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['contactnum']['field']='contact_num';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['contactnum']['name']=$LANG["common"][21];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['contactnum']['type']='text';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['comments']['table']='glpi_networking';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['comments']['field']='comments';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['comments']['name']=$LANG["common"][25];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['comments']['type']='text';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['comments']['table_type']='multitext';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['ram']['table']='glpi_networking';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['ram']['field']='ram';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['ram']['name']=$LANG["networking"][5];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['ram']['type']='text';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['type']['table']='glpi_type_networking';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['type']['field']='name';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['type']['name']=$LANG["common"][17];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['type']['type']='text';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['type']['linkfield']='type';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['type']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['model']['table']='glpi_dropdown_model_networking';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['model']['field']='name';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['model']['name']=$LANG["common"][22];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['model']['type']='text';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['model']['linkfield']='model';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['model']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['network']['table']='glpi_dropdown_network';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['network']['field']='name';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['network']['name']=$LANG["setup"][88];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['network']['type']='text';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['network']['linkfield']='network';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['network']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['domain']['table']='glpi_dropdown_domain';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['domain']['field']='name';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['domain']['name']=$LANG["setup"][89];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['domain']['linkfield']='domain';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['domain']['type']='text';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['domain']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['manufacturer']['table']='glpi_dropdown_manufacturer';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['manufacturer']['field']='name';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['manufacturer']['name']=$LANG["common"][5];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['manufacturer']['type']='text';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['manufacturer']['linkfield']='FK_glpi_enterprise';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['manufacturer']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['is_template']['table']='glpi_networking';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['is_template']['field']='is_template';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['is_template']['name']=$LANG["common"][13];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['is_template']['type']='integer';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['tplname']['table']='glpi_networking';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['tplname']['field']='tplname';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['tplname']['name']=$LANG["common"][6];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['tplname']['type']='text';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['notes']['table']='glpi_networking';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['notes']['field']='notes';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['notes']['name']=$LANG["reminder"][2];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['notes']['type']='text';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['notes']['table_type']='multitext';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['state']['field']='name';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['state']['table_type']='dropdown';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['state']['type']='text';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['firmware']['table']='glpi_networking';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['firmware']['field']='firmware';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['firmware']['name']=$LANG["networking"][49];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['firmware']['type']='text';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['ifmac']['table']='glpi_networking';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['ifmac']['field']='ifmac';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['ifmac']['name']=$LANG["networking"][13];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['ifmac']['type']='mac';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['ifaddr']['table']='glpi_networking';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['ifaddr']['field']='ifaddr';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['ifaddr']['name']=$LANG["networking"][14];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['ifaddr']['type']='ip';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['FK_groups']['table']='glpi_groups';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['FK_groups']['field']='name';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['FK_groups']['name']=$LANG["common"][35];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['FK_groups']['linkfield']='FK_groups';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['FK_groups']['type']='text';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['FK_groups']['table_type']='single';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['tech_num']['table']='glpi_users';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['tech_num']['field']='name';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['tech_num']['name']=$LANG["common"][10];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['tech_num']['linkfield']='tech_num';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['tech_num']['type']='text';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['tech_num']['table_type']='user';

$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['FK_users']['table']='glpi_users';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['FK_users']['field']='name';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['FK_users']['name']=$LANG["common"][34];
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['FK_users']['linkfield']='FK_users';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['FK_users']['type']='text';
$DATA_INJECTION_MAPPING[NETWORKING_TYPE]['FK_users']['table_type']='user';

// ----------------------------------------------------------------------
//USER MAPPING
// ----------------------------------------------------------------------
$DATA_INJECTION_MAPPING[USER_TYPE]['name']['table']='glpi_users';
$DATA_INJECTION_MAPPING[USER_TYPE]['name']['field']='name';
$DATA_INJECTION_MAPPING[USER_TYPE]['name']['name']=$LANG["common"][16];
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
$DATA_INJECTION_MAPPING[USER_TYPE]['comments']['table_type']='multitext';

$DATA_INJECTION_MAPPING[USER_TYPE]['location']['table']='glpi_dropdown_locations';
$DATA_INJECTION_MAPPING[USER_TYPE]['location']['field']='name';
$DATA_INJECTION_MAPPING[USER_TYPE]['location']['name']=$LANG["common"][15];
$DATA_INJECTION_MAPPING[USER_TYPE]['location']['linkfield']='location';
$DATA_INJECTION_MAPPING[USER_TYPE]['location']['type']='text';
$DATA_INJECTION_MAPPING[USER_TYPE]['location']['table_type']='dropdown';

//This mapping needs post processing
$DATA_INJECTION_MAPPING[USER_TYPE]['FK_group']['table']='glpi_groups';
$DATA_INJECTION_MAPPING[USER_TYPE]['FK_group']['field']='name';
$DATA_INJECTION_MAPPING[USER_TYPE]['FK_group']['name']=$LANG["common"][35];
$DATA_INJECTION_MAPPING[USER_TYPE]['FK_group']['type']='text';
$DATA_INJECTION_MAPPING[USER_TYPE]['FK_group']['table_type']='single';
$DATA_INJECTION_MAPPING[USER_TYPE]['FK_group']['linkfield']='FK_group';

// ----------------------------------------------------------------------
//GROUP MAPPING
// ----------------------------------------------------------------------
$DATA_INJECTION_MAPPING[GROUP_TYPE]['name']['table']='glpi_groups';
$DATA_INJECTION_MAPPING[GROUP_TYPE]['name']['field']='name';
$DATA_INJECTION_MAPPING[GROUP_TYPE]['name']['name']=$LANG["common"][16];
$DATA_INJECTION_MAPPING[GROUP_TYPE]['name']['type']='text';

$DATA_INJECTION_MAPPING[GROUP_TYPE]['comments']['table']='glpi_groups';
$DATA_INJECTION_MAPPING[GROUP_TYPE]['comments']['field']='comments';
$DATA_INJECTION_MAPPING[GROUP_TYPE]['comments']['name']=$LANG["common"][25];
$DATA_INJECTION_MAPPING[GROUP_TYPE]['comments']['type']='text';

$DATA_INJECTION_MAPPING[GROUP_TYPE]['ldap_field']['table']='glpi_groups';
$DATA_INJECTION_MAPPING[GROUP_TYPE]['ldap_field']['field']='ldap_field';
$DATA_INJECTION_MAPPING[GROUP_TYPE]['ldap_field']['name']=$LANG["setup"][260];
$DATA_INJECTION_MAPPING[GROUP_TYPE]['ldap_field']['type']='text';

$DATA_INJECTION_MAPPING[GROUP_TYPE]['ldap_value']['table']='glpi_groups';
$DATA_INJECTION_MAPPING[GROUP_TYPE]['ldap_value']['field']='ldap_value';
$DATA_INJECTION_MAPPING[GROUP_TYPE]['ldap_value']['name']=$LANG["setup"][601];
$DATA_INJECTION_MAPPING[GROUP_TYPE]['ldap_value']['type']='text';

$DATA_INJECTION_MAPPING[GROUP_TYPE]['ldap_group_dn']['table']='glpi_groups';
$DATA_INJECTION_MAPPING[GROUP_TYPE]['ldap_group_dn']['field']='ldap_group_dn';
$DATA_INJECTION_MAPPING[GROUP_TYPE]['ldap_group_dn']['name']=$LANG["setup"][261];
$DATA_INJECTION_MAPPING[GROUP_TYPE]['ldap_group_dn']['type']='text';

// ----------------------------------------------------------------------
//CONTRACT MAPPING
// ----------------------------------------------------------------------
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['name']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['name']['field']='name';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['name']['name']=$LANG["common"][16];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['name']['type']='text';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['num']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['num']['field']='num';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['num']['name']=$LANG["financial"][4];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['num']['type']='text';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['cost']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['cost']['field']='cost';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['cost']['name']=$LANG["financial"][5];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['cost']['type']='float';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['contract_type']['table']='glpi_dropdown_contract_type';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['contract_type']['field']='name';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['contract_type']['name']=$LANG["financial"][6];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['contract_type']['linkfield']='contract_type';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['contract_type']['type']='text';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['contract_type']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['begin_date']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['begin_date']['field']='begin_date';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['begin_date']['name']=$LANG["search"][8];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['begin_date']['type']='date';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['duration']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['duration']['field']='duration';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['duration']['name']=$LANG["financial"][8];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['duration']['type']='integer';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['notice']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['notice']['field']='notice';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['notice']['name']=$LANG["financial"][10];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['notice']['type']='integer';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['bill_type']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['bill_type']['field']='bill_type';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['bill_type']['name']=$LANG["financial"][98];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['bill_type']['type']='integer';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['periodicity']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['periodicity']['field']='periodicity';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['periodicity']['name']=$LANG["financial"][69];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['periodicity']['type']='integer';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['facturation']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['facturation']['field']='facturation';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['facturation']['name']=$LANG["financial"][11];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['facturation']['type']='integer';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['compta_num']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['compta_num']['field']='compta_num';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['compta_num']['name']=$LANG["financial"][13];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['compta_num']['type']='integer';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['week_begin_hour']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['week_begin_hour']['field']='week_begin_hour';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['week_begin_hour']['name']=$LANG["financial"][59]." ".$LANG["financial"][60]." : ".$LANG["reservation"][12];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['week_begin_hour']['type']='date';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['week_end_hour']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['week_end_hour']['field']='week_end_hour';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['week_end_hour']['name']=$LANG["financial"][59]." ".$LANG["financial"][60]." : ".$LANG["reservation"][13];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['week_end_hour']['type']='date';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['saturday']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['saturday']['field']='saturday';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['saturday']['name']=$LANG["financial"][59]." ".$LANG["financial"][61];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['saturday']['type']='integer';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['saturday_begin_hour']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['saturday_begin_hour']['field']='saturday_begin_hour';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['saturday_begin_hour']['name']=$LANG["financial"][59]." ".$LANG["financial"][61]." : ".$LANG["reservation"][12];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['saturday_begin_hour']['type']='date';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['saturday_end_hour']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['saturday_end_hour']['field']='week_end_hour';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['saturday_end_hour']['name']=$LANG["financial"][59]." ".$LANG["financial"][61]." : ".$LANG["reservation"][13];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['saturday_end_hour']['type']='date';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['monday']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['monday']['field']='monday';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['monday']['name']=$LANG["financial"][59]." ".$LANG["financial"][62];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['monday']['type']='integer';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['monday_begin_hour']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['monday_begin_hour']['field']='monday_begin_hour';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['monday_begin_hour']['name']=$LANG["financial"][59]." ".$LANG["financial"][62]." : ".$LANG["reservation"][12];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['monday_begin_hour']['type']='date';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['monday_end_hour']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['monday_end_hour']['field']='monday_end_hour';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['monday_end_hour']['name']=$LANG["financial"][59]." ".$LANG["financial"][62]." : ".$LANG["reservation"][13];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['monday_end_hour']['type']='date';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['device_countmax']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['device_countmax']['field']='device_countmax';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['device_countmax']['name']=$LANG["financial"][83];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['device_countmax']['type']='integer';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['notes']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['notes']['field']='notes';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['notes']['name']=$LANG["reminder"][2];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['notes']['type']='text';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['alert']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['alert']['field']='alert';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['alert']['name']=$LANG["common"][41];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['alert']['type']='integer';

$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['renewal']['table']='glpi_contracts';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['renewal']['field']='renewal';
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['renewal']['name']=$LANG["financial"][107];
$DATA_INJECTION_MAPPING[CONTRACT_TYPE]['renewal']['type']='integer';

// ----------------------------------------------------------------------
//PERIPHERAL MAPPING
// ----------------------------------------------------------------------

$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['name']['table']='glpi_peripherals';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['name']['field']='name';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['name']['name']=$LANG["common"][16];
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['name']['type']='text';

$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['serial']['table']='glpi_peripherals';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['serial']['field']='serial';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['serial']['name']=$LANG["common"][19];
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['serial']['type']='text';

$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['otherserial']['table']='glpi_peripherals';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['otherserial']['field']='otherserial';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['otherserial']['name']=$LANG["common"][20];
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['otherserial']['type']='text';

$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['location']['table']='glpi_dropdown_locations';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['location']['field']='name';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['location']['name']=$LANG["common"][15];
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['location']['linkfield']='location';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['location']['type']='text';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['location']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['contact']['table']='glpi_peripherals';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['contact']['field']='contact';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['contact']['name']=$LANG["common"][18];
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['contact']['type']='text';

$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['contactnum']['table']='glpi_peripherals';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['contactnum']['field']='contact_num';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['contactnum']['name']=$LANG["common"][21];
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['contactnum']['type']='text';

$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['comments']['table']='glpi_peripherals';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['comments']['field']='comments';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['comments']['name']=$LANG["common"][25];
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['comments']['type']='text';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['comments']['table_type']='multitext';

$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['notes']['table']='glpi_peripherals';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['notes']['field']='notes';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['notes']['name']=$LANG["reminder"][2];
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['notes']['type']='text';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['notes']['table_type']='multitext';

$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['state']['field']='name';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['state']['table_type']='dropdown';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['state']['type']='text';

$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['type']['table']='glpi_type_peripherals';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['type']['field']='name';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['type']['name']=$LANG["common"][17];
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['type']['type']='text';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['type']['linkfield']='type';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['type']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['model']['table']='glpi_dropdown_model_peripherals';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['model']['field']='name';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['model']['name']=$LANG["common"][22];
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['model']['type']='text';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['model']['linkfield']='model';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['model']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['manufacturer']['table']='glpi_dropdown_manufacturer';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['manufacturer']['field']='name';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['manufacturer']['name']=$LANG["common"][5];
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['manufacturer']['type']='text';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['manufacturer']['linkfield']='FK_glpi_enterprise';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['manufacturer']['table_type']='dropdown';

$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['is_template']['table']='glpi_peripherals';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['is_template']['field']='is_template';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['is_template']['name']=$LANG["common"][13];
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['is_template']['type']='integer';

$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['tplname']['table']='glpi_peripherals';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['tplname']['field']='tplname';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['tplname']['name']=$LANG["common"][6];
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['tplname']['type']='text';

$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['brand']['table']='glpi_peripherals';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['brand']['field']='brand';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['brand']['name']=$LANG["peripherals"][18];
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['brand']['type']='text';

$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['FK_groups']['table']='glpi_groups';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['FK_groups']['field']='name';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['FK_groups']['name']=$LANG["common"][35];
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['FK_groups']['linkfield']='FK_groups';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['FK_groups']['type']='text';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['FK_groups']['table_type']='single';

$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['FK_users']['table']='glpi_users';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['FK_users']['field']='name';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['FK_users']['name']=$LANG["common"][34];
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['FK_users']['linkfield']='FK_users';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['FK_users']['type']='text';
$DATA_INJECTION_MAPPING[PERIPHERAL_TYPE]['FK_users']['table_type']='user';

?>
