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
//INFORMATIONS
// ----------------------------------------------------------------------
// ----------------------------------------------------------------------
global $LANG,$DATA_INJECTION_INFOS,$INJECTION_INFOS_TYPES;
$INJECTION_INFOS_TYPES = array(COMPUTER_TYPE, MONITOR_TYPE, PRINTER_TYPE,PHONE_TYPE, INFOCOM_TYPE, NETWORKING_TYPE, PERIPHERAL_TYPE);

// ----------------------------------------------------------------------
//INFOCOM INFOS
// ----------------------------------------------------------------------
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['buy_date']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['buy_date']['field']='buy_date';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['buy_date']['name']=$LANG["financial"][14];
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['buy_date']['type']='date';

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['use_date']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['use_date']['field']='use_date';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['use_date']['name']=$LANG["financial"][76];
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['use_date']['type']='date';

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['warranty_duration']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['warranty_duration']['field']='warranty_duration';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['warranty_duration']['name']=$LANG["financial"][15];
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['warranty_duration']['type']='integer';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['warranty_duration']['input_type']='dropdown';

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['warranty_info']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['warranty_info']['field']='warranty_info';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['warranty_info']['name']=$LANG["financial"][16];

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['warranty_value']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['warranty_value']['field']='warranty_value';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['warranty_value']['name']=$LANG["financial"][78];

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['FK_enterprise']['table']='glpi_enterprises';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['FK_enterprise']['field']='name';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['FK_enterprise']['name']=$LANG["common"][5];
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['FK_enterprise']['linkfield']='FK_enterprise';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['FK_enterprise']['input_type']='dropdown';

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['num_commande']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['num_commande']['field']='num_commande';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['num_commande']['name']=$LANG["financial"][18];

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['bon_livraison']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['bon_livraison']['field']='bon_livraison';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['bon_livraison']['name']=$LANG["financial"][19];

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['num_immo']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['num_immo']['field']='num_immo';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['num_immo']['name']=$LANG["financial"][20];

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['value']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['value']['field']='value';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['value']['name']=$LANG["financial"][21];

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_time']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_time']['field']='amort_time';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_time']['name']=$LANG["financial"][23];
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_time']['type']='integer';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_time']['input_type']='dropdown';

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_type']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_type']['field']='amort_type';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_type']['name']=$LANG["financial"][22];
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_type']['type']='integer';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_type']['input_type']='dropdown';

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_coeff']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_coeff']['field']='amort_coeff';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_coeff']['name']=$LANG["financial"][77];
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_coeff']['type']='float';

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['comments']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['comments']['field']='comments';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['comments']['name']=$LANG["common"][25];
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['comments']['table_type']='multitext';

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['facture']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['facture']['field']='facture';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['facture']['name']=$LANG["financial"][82];

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['budget']['table']='glpi_dropdown_budget';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['budget']['field']='budget';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['budget']['name']=$LANG["financial"][87];
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['budget']['linkfield']='FK_enterprise';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['budget']['input_type']='dropdown';

// ----------------------------------------------------------------------
//COMPUTER INFOS
// ----------------------------------------------------------------------
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['state']['field']='name';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['state']['input_type']='dropdown';

$DATA_INJECTION_INFOS[COMPUTER_TYPE]['location']['table']='glpi_dropdown_locations';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['location']['field']='name';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['location']['name']=$LANG["common"][15];
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['location']['linkfield']='location';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['location']['input_type']='dropdown';

$DATA_INJECTION_INFOS[COMPUTER_TYPE]['FK_glpi_enterprise']['table']='glpi_dropdown_manufacturer';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['FK_glpi_enterprise']['field']='name';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['FK_glpi_enterprise']['name']=$LANG["common"][5];
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['FK_glpi_enterprise']['linkfield']='FK_glpi_enterprise';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['FK_glpi_enterprise']['input_type']='dropdown';

$DATA_INJECTION_INFOS[COMPUTER_TYPE]['type']['table']='glpi_type_computers';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['type']['field']='name';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['type']['name']=$LANG["common"][17];
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['type']['linkfield']='type';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['type']['input_type']='dropdown';

$DATA_INJECTION_INFOS[COMPUTER_TYPE]['model']['table']='glpi_dropdown_model';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['model']['field']='name';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['model']['name']=$LANG["common"][22];
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['model']['linkfield']='model';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['model']['input_type']='dropdown';

$DATA_INJECTION_INFOS[COMPUTER_TYPE]['FK_groups']['table']='glpi_groups';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['FK_groups']['field']='name';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['FK_groups']['name']=$LANG["common"][35];
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['FK_groups']['linkfield']='FK_groups';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['FK_groups']['input_type']='dropdown';

$DATA_INJECTION_INFOS[COMPUTER_TYPE]['tech_num']['table']='glpi_users';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['tech_num']['field']='name';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['tech_num']['name']=$LANG["common"][10];
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['tech_num']['linkfield']='tech_num';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['tech_num']['input_type']='dropdown';

$DATA_INJECTION_INFOS[COMPUTER_TYPE]['comments']['table']='glpi_computers';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['comments']['field']='comments';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['comments']['name']=$LANG["common"][25];
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['comments']['table_type']='multitext';

// ----------------------------------------------------------------------
//MONITOR INFOS
// ----------------------------------------------------------------------
$DATA_INJECTION_INFOS[MONITOR_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['state']['field']='name';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_INFOS[MONITOR_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['state']['input_type']='dropdown';

$DATA_INJECTION_INFOS[MONITOR_TYPE]['location']['table']='glpi_dropdown_locations';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['location']['field']='name';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['location']['name']=$LANG["common"][15];
$DATA_INJECTION_INFOS[MONITOR_TYPE]['location']['linkfield']='location';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['location']['input_type']='dropdown';

$DATA_INJECTION_INFOS[MONITOR_TYPE]['FK_glpi_enterprise']['table']='glpi_dropdown_manufacturer';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['FK_glpi_enterprise']['field']='name';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['FK_glpi_enterprise']['name']=$LANG["common"][5];
$DATA_INJECTION_INFOS[MONITOR_TYPE]['FK_glpi_enterprise']['linkfield']='FK_enterprise';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['FK_glpi_enterprise']['input_type']='dropdown';

$DATA_INJECTION_INFOS[MONITOR_TYPE]['type']['table']='glpi_type_monitors';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['type']['field']='name';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['type']['name']=$LANG["common"][17];
$DATA_INJECTION_INFOS[MONITOR_TYPE]['type']['linkfield']='type';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['type']['input_type']='dropdown';

$DATA_INJECTION_INFOS[MONITOR_TYPE]['model']['table']='glpi_dropdown_model';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['model']['field']='name';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['model']['name']=$LANG["common"][22];
$DATA_INJECTION_INFOS[MONITOR_TYPE]['model']['linkfield']='model';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['model']['input_type']='dropdown';

$DATA_INJECTION_INFOS[MONITOR_TYPE]['FK_groups']['table']='glpi_groups';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['FK_groups']['field']='name';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['FK_groups']['name']=$LANG["common"][35];
$DATA_INJECTION_INFOS[MONITOR_TYPE]['FK_groups']['linkfield']='FK_groups';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['FK_groups']['input_type']='dropdown';

$DATA_INJECTION_INFOS[MONITOR_TYPE]['tech_num']['table']='glpi_users';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['tech_num']['field']='name';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['tech_num']['name']=$LANG["common"][10];
$DATA_INJECTION_INFOS[MONITOR_TYPE]['tech_num']['linkfield']='tech_num';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['tech_num']['input_type']='dropdown';

$DATA_INJECTION_INFOS[MONITOR_TYPE]['comments']['table']='glpi_monitors';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['comments']['field']='comments';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['comments']['name']=$LANG["common"][25];
$DATA_INJECTION_INFOS[MONITOR_TYPE]['comments']['table_type']='multitext';

// ----------------------------------------------------------------------
//PRINTER INFOS
// ----------------------------------------------------------------------
$DATA_INJECTION_INFOS[PRINTER_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['state']['field']='name';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_INFOS[PRINTER_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['state']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PRINTER_TYPE]['location']['table']='glpi_dropdown_locations';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['location']['field']='name';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['location']['name']=$LANG["common"][15];
$DATA_INJECTION_INFOS[PRINTER_TYPE]['location']['linkfield']='location';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['location']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PRINTER_TYPE]['FK_glpi_enterprise']['table']='glpi_dropdown_manufacturer';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['FK_glpi_enterprise']['field']='name';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['FK_glpi_enterprise']['name']=$LANG["common"][5];
$DATA_INJECTION_INFOS[PRINTER_TYPE]['FK_glpi_enterprise']['linkfield']='FK_enterprise';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['FK_glpi_enterprise']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PRINTER_TYPE]['type']['table']='glpi_type_printers';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['type']['field']='name';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['type']['name']=$LANG["common"][17];
$DATA_INJECTION_INFOS[PRINTER_TYPE]['type']['linkfield']='type';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['type']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PRINTER_TYPE]['model']['table']='glpi_dropdown_model';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['model']['field']='name';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['model']['name']=$LANG["common"][22];
$DATA_INJECTION_INFOS[PRINTER_TYPE]['model']['linkfield']='model';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['model']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PRINTER_TYPE]['FK_groups']['table']='glpi_groups';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['FK_groups']['field']='name';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['FK_groups']['name']=$LANG["common"][35];
$DATA_INJECTION_INFOS[PRINTER_TYPE]['FK_groups']['linkfield']='FK_groups';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['FK_groups']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PRINTER_TYPE]['tech_num']['table']='glpi_users';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['tech_num']['field']='name';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['tech_num']['name']=$LANG["common"][10];
$DATA_INJECTION_INFOS[PRINTER_TYPE]['tech_num']['linkfield']='tech_num';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['tech_num']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PRINTER_TYPE]['comments']['table']='glpi_printers';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['comments']['field']='comments';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['comments']['name']=$LANG["common"][25];
$DATA_INJECTION_INFOS[PRINTER_TYPE]['comments']['table_type']='multitext';

// ----------------------------------------------------------------------
//PHONE INFOS
// ----------------------------------------------------------------------
$DATA_INJECTION_INFOS[PHONE_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_INFOS[PHONE_TYPE]['state']['field']='name';
$DATA_INJECTION_INFOS[PHONE_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_INFOS[PHONE_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_INFOS[PHONE_TYPE]['state']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PHONE_TYPE]['location']['table']='glpi_dropdown_locations';
$DATA_INJECTION_INFOS[PHONE_TYPE]['location']['field']='name';
$DATA_INJECTION_INFOS[PHONE_TYPE]['location']['name']=$LANG["common"][15];
$DATA_INJECTION_INFOS[PHONE_TYPE]['location']['linkfield']='location';
$DATA_INJECTION_INFOS[PHONE_TYPE]['location']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PHONE_TYPE]['FK_glpi_enterprise']['table']='glpi_dropdown_manufacturer';
$DATA_INJECTION_INFOS[PHONE_TYPE]['FK_glpi_enterprise']['field']='name';
$DATA_INJECTION_INFOS[PHONE_TYPE]['FK_glpi_enterprise']['name']=$LANG["common"][5];
$DATA_INJECTION_INFOS[PHONE_TYPE]['FK_glpi_enterprise']['linkfield']='FK_enterprise';
$DATA_INJECTION_INFOS[PHONE_TYPE]['FK_glpi_enterprise']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PHONE_TYPE]['type']['table']='glpi_type_phones';
$DATA_INJECTION_INFOS[PHONE_TYPE]['type']['field']='name';
$DATA_INJECTION_INFOS[PHONE_TYPE]['type']['name']=$LANG["common"][17];
$DATA_INJECTION_INFOS[PHONE_TYPE]['type']['linkfield']='type';
$DATA_INJECTION_INFOS[PHONE_TYPE]['type']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PHONE_TYPE]['model']['table']='glpi_dropdown_model';
$DATA_INJECTION_INFOS[PHONE_TYPE]['model']['field']='name';
$DATA_INJECTION_INFOS[PHONE_TYPE]['model']['name']=$LANG["common"][22];
$DATA_INJECTION_INFOS[PHONE_TYPE]['model']['linkfield']='model';
$DATA_INJECTION_INFOS[PHONE_TYPE]['model']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PHONE_TYPE]['FK_groups']['table']='glpi_groups';
$DATA_INJECTION_INFOS[PHONE_TYPE]['FK_groups']['field']='name';
$DATA_INJECTION_INFOS[PHONE_TYPE]['FK_groups']['name']=$LANG["common"][35];
$DATA_INJECTION_INFOS[PHONE_TYPE]['FK_groups']['linkfield']='FK_groups';
$DATA_INJECTION_INFOS[PHONE_TYPE]['FK_groups']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PHONE_TYPE]['tech_num']['table']='glpi_users';
$DATA_INJECTION_INFOS[PHONE_TYPE]['tech_num']['field']='name';
$DATA_INJECTION_INFOS[PHONE_TYPE]['tech_num']['name']=$LANG["common"][10];
$DATA_INJECTION_INFOS[PHONE_TYPE]['tech_num']['linkfield']='tech_num';
$DATA_INJECTION_INFOS[PHONE_TYPE]['tech_num']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PHONE_TYPE]['comments']['table']='glpi_phones';
$DATA_INJECTION_INFOS[PHONE_TYPE]['comments']['field']='comments';
$DATA_INJECTION_INFOS[PHONE_TYPE]['comments']['name']=$LANG["common"][25];
$DATA_INJECTION_INFOS[PHONE_TYPE]['comments']['table_type']='multitext';

// ----------------------------------------------------------------------
//PERIPHERAL INFOS
// ----------------------------------------------------------------------
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['state']['field']='name';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['state']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['location']['table']='glpi_dropdown_locations';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['location']['field']='name';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['location']['name']=$LANG["common"][15];
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['location']['linkfield']='location';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['location']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['FK_glpi_enterprise']['table']='glpi_dropdown_manufacturer';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['FK_glpi_enterprise']['field']='name';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['FK_glpi_enterprise']['name']=$LANG["common"][5];
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['FK_glpi_enterprise']['linkfield']='FK_enterprise';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['FK_glpi_enterprise']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['type']['table']='glpi_type_peripherals';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['type']['field']='name';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['type']['name']=$LANG["common"][17];
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['type']['linkfield']='type';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['type']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['model']['table']='glpi_dropdown_model';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['model']['field']='name';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['model']['name']=$LANG["common"][22];
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['model']['linkfield']='model';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['model']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['FK_groups']['table']='glpi_groups';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['FK_groups']['field']='name';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['FK_groups']['name']=$LANG["common"][35];
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['FK_groups']['linkfield']='FK_groups';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['FK_groups']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['tech_num']['table']='glpi_users';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['tech_num']['field']='name';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['tech_num']['name']=$LANG["common"][10];
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['tech_num']['linkfield']='tech_num';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['tech_num']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['comments']['table']='glpi_peripherals';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['comments']['field']='comments';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['comments']['name']=$LANG["common"][25];
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['comments']['table_type']='multitext';

// ----------------------------------------------------------------------
//NETWORKING INFOS
// ----------------------------------------------------------------------
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['state']['field']='name';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['state']['input_type']='dropdown';

$DATA_INJECTION_INFOS[NETWORKING_TYPE]['location']['table']='glpi_dropdown_locations';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['location']['field']='name';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['location']['name']=$LANG["common"][15];
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['location']['linkfield']='location';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['location']['input_type']='dropdown';

$DATA_INJECTION_INFOS[NETWORKING_TYPE]['FK_glpi_enterprise']['table']='glpi_dropdown_manufacturer';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['FK_glpi_enterprise']['field']='name';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['FK_glpi_enterprise']['name']=$LANG["common"][5];
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['FK_glpi_enterprise']['linkfield']='FK_enterprise';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['FK_glpi_enterprise']['input_type']='dropdown';

$DATA_INJECTION_INFOS[NETWORKING_TYPE]['type']['table']='glpi_type_networking';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['type']['field']='name';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['type']['name']=$LANG["common"][17];
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['type']['linkfield']='type';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['type']['input_type']='dropdown';

$DATA_INJECTION_INFOS[NETWORKING_TYPE]['model']['table']='glpi_dropdown_model';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['model']['field']='name';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['model']['name']=$LANG["common"][22];
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['model']['linkfield']='model';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['model']['input_type']='dropdown';

$DATA_INJECTION_INFOS[NETWORKING_TYPE]['FK_groups']['table']='glpi_groups';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['FK_groups']['field']='name';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['FK_groups']['name']=$LANG["common"][35];
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['FK_groups']['linkfield']='FK_groups';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['FK_groups']['input_type']='dropdown';

$DATA_INJECTION_INFOS[NETWORKING_TYPE]['tech_num']['table']='glpi_users';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['tech_num']['field']='name';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['tech_num']['name']=$LANG["common"][10];
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['tech_num']['linkfield']='tech_num';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['tech_num']['input_type']='dropdown';

$DATA_INJECTION_INFOS[NETWORKING_TYPE]['comments']['table']='glpi_networking';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['comments']['field']='comments';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['comments']['name']=$LANG["common"][25];
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['comments']['table_type']='multitext';

?>
