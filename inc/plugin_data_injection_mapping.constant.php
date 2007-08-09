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
//COMPUTER MAPPING
// ----------------------------------------------------------------------
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['name']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['name']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['name']['name']=$LANG["common"][16];

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['serial']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['serial']['field']='serial';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['serial']['name']=$LANG["common"][19];

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['otherserial']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['otherserial']['field']='otherserial';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['otherserial']['name']=$LANG["common"][20];

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_number']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_number']['field']='os_license_number';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_number']['name']=$LANG["computers"][10];

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_id']['table']='glpi_computers';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_id']['field']='os_license_number';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_license_id']['name']=$LANG["computers"][11];

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os']['table']='glpi_dropdown_os';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os']['name']=$LANG["computers"][9];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os']['linkfield']='os';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_sp']['table']='glpi_dropdown_os';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_sp']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_sp']['name']=$LANG["computers"][52];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_sp']['linkfield']='os_sp';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['location']['table']='glpi_dropdown_location';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['location']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['location']['name']=$LANG["common"][15];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['location']['linkfield']='location';

$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['domain']['table']='glpi_dropdown_domain';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['domain']['field']='name';
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['domain']['name']=$LANG["common"][15];
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['domain']['linkfield']='domain';

// ----------------------------------------------------------------------
//MONITOR MAPPING
// ----------------------------------------------------------------------


// ----------------------------------------------------------------------
//USER MAPPING
// ----------------------------------------------------------------------


$DATA_INJECTION_INFOS[FINANCIAL]['inventory']['table']='glpi_dropdown_domain';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['domain']['field']='name';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['domain']['name']=$LANG["common"][15];
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['domain']['linkfield']='domain';
$DATA_INJECTION_INFOS[COMPUTER_TYPE]['domain']['type']='dropdown';

?>
