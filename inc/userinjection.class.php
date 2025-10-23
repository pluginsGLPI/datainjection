<?php

/**
 * -------------------------------------------------------------------------
 * DataInjection plugin for GLPI
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of DataInjection.
 *
 * DataInjection is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * DataInjection is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DataInjection. If not, see <http://www.gnu.org/licenses/>.
 * -------------------------------------------------------------------------
 * @copyright Copyright (C) 2007-2023 by DataInjection plugin team.
 * @license   GPLv2 https://www.gnu.org/licenses/gpl-2.0.html
 * @link      https://github.com/pluginsGLPI/datainjection
 * -------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}

use Glpi\Toolbox\Sanitizer;

class PluginDatainjectionUserInjection extends User implements PluginDatainjectionInjectionInterface
{
    public static function getTable($classname = null)
    {

        $parenttype = get_parent_class(__CLASS__);
        return $parenttype::getTable();
    }


    public function isPrimaryType()
    {

        return true;
    }


    public function connectedTo()
    {

        return [];
    }

    public function isNullable($field)
    {
        // Fields that cannot accept empty string values and should use their default values instead
        $non_nullable_fields = [
            'use_mode',
            'highcontrast_css',
            'default_central_tab',
            'entities_id',
            'profiles_id',
            'is_active',
            'is_deleted',
            'authtype',
            'auths_id'
        ];

        return !in_array($field, $non_nullable_fields);
    }


    /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::getOptions()
   **/
    public function getOptions($primary_type = '')
    {

        $tab                       = Search::getOptions(get_parent_class($this));

       //Specific to location
        $tab[1]['linkfield']       = 'name';
        $tab[3]['linkfield']       = 'locations_id';

       //Manage password
        $tab[4]['table']           = $this->getTable();
        $tab[4]['field']           = 'password';
        $tab[4]['linkfield']       = 'password';
        $tab[4]['name']            = __('Password');
        $tab[4]['displaytype']     = 'password';

        $tab[5]['displaytype']     = 'text';

       //To manage groups : relies on a CommonDBRelation object !
        $tab[100]['name']          = __('Group');
        $tab[100]['field']         = 'name';
        $tab[100]['table']         = getTableForItemType('Group');
        $tab[100]['linkfield']     = getForeignKeyFieldForTable($tab[100]['table']);
        $tab[100]['displaytype']   = 'relation';
        $tab[100]['relationclass'] = 'Group_User';
        $tab[100]['relationfield'] = $tab[100]['linkfield'];

       //To manage groups : relies on a CommonDBRelation object !
        $tab[101]['name']          = __('Profile');
        $tab[101]['field']         = 'name';
        $tab[101]['table']         = getTableForItemType('Profile');
        $tab[101]['linkfield']     = getForeignKeyFieldForTable($tab[101]['table']);
        $tab[101]['displaytype']   = 'relation';
        $tab[101]['relationclass'] = 'Profile_User';
        $tab[101]['relationfield'] = $tab[101]['linkfield'];

        // Add email option to make it importable with the correct linkfield
        $tab[5]['linkfield'] = 'useremails_id';  // Map email field to useremails_id for injection

       //Remove some options because some fields cannot be imported
        $blacklist     = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
        $notimportable = [13, 14, 15, 17, 20, 23, 30, 31, 60, 61, 91, 92, 93];

        $options['ignore_fields']  = array_merge($blacklist, $notimportable);

       //Add displaytype value
        $options['displaytype']    = [
            "dropdown"       => [
                3, // location
                77, // default entity
                79, // default profile
                81, // title
                82 // category
            ],
            "multiline_text" => [16],
            "bool"           => [8],
            "password"       => [4]
        ];

        return PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);
    }


    /**
    * @see plugins/datainjection/inc/PluginDatainjectionInjectionInterface::addOrUpdateObject()
   **/
    public function addOrUpdateObject($values = [], $options = [])
    {

        $lib = new PluginDatainjectionCommonInjectionLib($this, $values, $options);
        $lib->processAddOrUpdate();
        return $lib->getInjectionResults();
    }


    /**
    * @param string $primary_type
    * @param array $values
   **/
    public function addSpecificNeededFields($primary_type, $values)
    {

        if (isset($values[$primary_type]['name'])) {
            $fields['name'] = $values[$primary_type]['name'];
        } else {
            $fields['name'] = "none";
        }
        return $fields;
    }


    /**
    * @param array $values
    * @param boolean $add                (true by default)
    * @param array|null $rights    array
    */
    public function processAfterInsertOrUpdate($values, $add = true, $rights = [])
    {
        /** @var DBmysql $DB */
        global $DB;

       //Manage user emails (both for add and update)
        if (
            isset($values['User']['useremails_id'])
            && !empty($values['User']['useremails_id'])
            && isset($values['User']['id'])
            && $rights['add_dropdown']
            && Session::haveRight('user', UPDATE)
        ) {
            $email = trim($values['User']['useremails_id']);

            // Validate email format
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Check if this email doesn't already exist for this user
                if (
                    !countElementsInTable(
                        "glpi_useremails",
                        [
                            'users_id' => $values['User']['id'],
                            'email'    => $email,
                        ]
                    )
                ) {
                    $useremail = new UserEmail();
                    $tmp = [
                        'users_id'   => $values['User']['id'],
                        'email'      => $email,
                        'is_default' => 1  // Set as default email if it's the first one
                    ];

                    // Check if user already has emails, if so don't set as default
                    if (countElementsInTable("glpi_useremails", ['users_id' => $values['User']['id']])) {
                        $tmp['is_default'] = 0;
                    }

                    $useremail->add($tmp);
                }
            }
        }

        if (isset($values['User']['password']) && ($values['User']['password'] != '')) {
           //We use an SQL request because updating the password is unesasy
           //(self reset password process in $user->prepareInputForUpdate())
            $password = sha1(Sanitizer::unsanitize($values['User']["password"]));

            $query = "UPDATE `glpi_users`
                   SET `password` = '" . $password . "'
                   WHERE `id` = '" . $values['User']['id'] . "'";
            $DB->doQuery($query);
        }
    }


    /**
     * @param string $itemtype
     * @param string $field
     * @param array $value
     **/
    protected function addSpecificOptionalInfos($itemtype, $field, $value)
    {
        // If info is a password, then fill also password2, needed for prepareInputForAdd
        if ($field == 'password') {
            // TODO: this method doesn't exist, investigate
            // $this->setValueForItemtype($itemtype, "password2", $value);
        }
    }
}
