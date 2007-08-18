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
global $LANG,$DATA_INJECTION_MAPPING,$IMPORT_TYPES;
//Store all the type of items that could be imported
$IMPORT_TYPES = array(COMPUTER_TYPE);

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
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['os_sp']['name']=$LANG["computers"][52];
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
$DATA_INJECTION_MAPPING[COMPUTER_TYPE]['domain']['name']=$LANG["common"][15];
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


// ----------------------------------------------------------------------
//MONITOR MAPPING
// ----------------------------------------------------------------------


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

?>
