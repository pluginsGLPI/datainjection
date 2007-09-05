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
//MONITOR MAPPING
// ----------------------------------------------------------------------


// ----------------------------------------------------------------------
//USER MAPPING
// ----------------------------------------------------------------------


// ----------------------------------------------------------------------
// ----------------------------------------------------------------------
//INFORMATIONS
// ----------------------------------------------------------------------
// ----------------------------------------------------------------------
global $LANG,$DATA_INJECTION_INFOS;
$INJECTION_INFOS_TYPES = array(COMPUTER_TYPE, MONITOR_TYPE, PRINTER_TYPE,PHONE_TYPE, INFOCOM_TYPE, NETWORKING_TYPE, PERIPHERAL_TYPE);

// ----------------------------------------------------------------------
//INFOCOM MAPPING
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

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['warranty_info']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['warranty_info']['field']='warranty_info';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['warranty_info']['name']=$LANG["financial"][16];

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['warranty_value']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['warranty_value']['field']='warranty_value';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['warranty_value']['name']=$LANG["financial"][78];

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['manufacturer']['table']='glpi_dropdown_manufacturer';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['manufacturer']['field']='name';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['manufacturer']['name']=$LANG["common"][5];
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['manufacturer']['linkfield']='FK_enterprise';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['manufacturer']['input_type']='dropdown';

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

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_type']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_type']['field']='amort_type';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_type']['name']=$LANG["financial"][22];
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_type']['type']='integer';

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_coeff']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_coeff']['field']='amort_coeff';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_coeff']['name']=$LANG["financial"][77];
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['amort_coeff']['type']='float';

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['comments']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['comments']['field']='comments';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['comments']['name']=$LANG["common"][25];

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['facture']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['facture']['field']='facture';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['facture']['name']=$LANG["financial"][82];

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['budget']['table']='glpi_dropdown_budget';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['budget']['field']='budget';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['budget']['name']=$LANG["financial"][87];
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['budget']['linkfield']='FK_enterprise';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['budget']['input_type']='dropdown';

$DATA_INJECTION_INFOS[INFOCOM_TYPE]['alert']['table']='glpi_infocoms';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['alert']['field']='alert';
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['alert']['name']=$LANG["setup"][247];
$DATA_INJECTION_INFOS[INFOCOM_TYPE]['alert']['type']='integer';

$DATA_INJECTION_INFOS[COMPUTER_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['state']['field']='name';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['state']['input_type']='dropdown';

$DATA_INJECTION_INFOS[MONITOR_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['state']['field']='name';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_INFOS[MONITOR_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_INFOS[MONITOR_TYPE]['state']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PRINTER_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['state']['field']='name';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_INFOS[PRINTER_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_INFOS[PRINTER_TYPE]['state']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PHONE_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_INFOS[PHONE_TYPE]['state']['field']='name';
$DATA_INJECTION_INFOS[PHONE_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_INFOS[PHONE_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_INFOS[PHONE_TYPE]['state']['input_type']='dropdown';

$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['state']['field']='name';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_INFOS[PERIPHERAL_TYPE]['state']['input_type']='dropdown';

$DATA_INJECTION_INFOS[NETWORKING_TYPE]['state']['table']='glpi_dropdown_state';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['state']['field']='name';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['state']['name']=$LANG["joblist"][0];
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['state']['linkfield']='state';
$DATA_INJECTION_INFOS[NETWORKING_TYPE]['state']['input_type']='dropdown';

?>
