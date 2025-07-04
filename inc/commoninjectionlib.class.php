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

use Glpi\Toolbox\Sanitizer;

class PluginDatainjectionCommonInjectionLib
{
    //Injection results
    private $results = [];

    //Values to inject
    private $values = [];

    //Fields mandatory for injection
    private $mandatory_fields = [];

    private $optional_infos = [];

    //Injection class to use
    private $injectionClass;

    //Primary type to inject
    private $primary_type;

    //Store checks to perform on values
    private $checks = [];

    //Store rights
    private $rights = [];

    //Store specific fields formats
    private $formats = [];

    //Entity in which data will be inserted
    private $entity = 0;

    const ACTION_CHECK  = 0;

    //Type of action to perform
    const IMPORT_ADD    = 0;
    const IMPORT_UPDATE = 1;
    const IMPORT_DELETE = 2;


    //Action return constants
    const SUCCESS                        = 10; //Injection OK
    const FAILED                         = 11; //Error during injection
    const WARNING                        = 12; //Injection ok but partial

    //Field check return constants
    const TYPE_MISMATCH                  = 22;
    const MANDATORY                      = 23;
    const ITEM_NOT_FOUND                 = 24;

    //Injection Message
    const ERROR_CANNOT_IMPORT              = 31;
    const ERROR_CANNOT_UPDATE              = 32;
    const WARNING_NOTFOUND                 = 33;
    const ERROR_FIELDSIZE_EXCEEDED         = 37;
    const ERROR_IMPORT_REFUSED             = 39; //Dictionnary explicitly refuse import

    //Empty values
    const EMPTY_VALUE          = '';
    const DROPDOWN_EMPTY_VALUE = 0;

    //Format constants
    const FLOAT_TYPE_COMMA        = 0; //xxxx,xx
    const FLOAT_TYPE_DOT          = 1; //xxxx.xx
    const FLOAT_TYPE_DOT_AND_COM  = 2; //xx,xxx.xx

    //Date management constants
    const DATE_TYPE_DDMMYYYY   = "dd-mm-yyyy";
    const DATE_TYPE_MMDDYYYY   = "mm-dd-yyyy";
    const DATE_TYPE_YYYYMMDD   = "yyyy-mm-dd";

    //Port unicity constants
    const UNICITY_NETPORT_LOGICAL_NUMBER            = 0;
    const UNICITY_NETPORT_NAME                      = 1;
    const UNICITY_NETPORT_MACADDRESS                = 2;
    const UNICITY_NETPORT_LOGICAL_NUMBER_NAME       = 3;
    const UNICITY_NETPORT_LOGICAL_NUMBER_MAC        = 4;
    const UNICITY_NETPORT_LOGICAL_NUMBER_NAME_MAC   = 5;

    //Field status must evolve when ticket #2216 will be resolved
    const FIELD_INJECTABLE     = 1;
    const FIELD_VIRTUAL        = 2;



    /**
    * Set default values for injection parameters
    *
    * @return void nothing
   **/
    public function setDefaultValues(): void
    {

        $this->checks = ['ip'           => false, 'mac'          => false,
            'integer'      => false, 'yes'          => false,
            'bool'         => false, 'date'         => false,
            'float'        => false, 'string'       => false,
            'right_r'      => false, 'right_rw'     => false,
            'interface'    => false, 'auth_method'  => false,
            'port_unicity' => false
        ];

       //Rights options
        $this->rights = ['add_dropdown'              => false,
            'overwrite_notempty_fields' => false,
            'can_add'                   => false,
            'can_update'                => false,
            'can_delete'                => false,
            'replace_multiline_value'   => false,
        ];

       //Field format options
        $this->formats = ['date_format'  => self::DATE_TYPE_YYYYMMDD,
            'float_format' => self::FLOAT_TYPE_COMMA
        ];
    }


    /**
    * Constructor : store all needed options into the library
    *
    * @param PluginDatainjectionInjectionInterface $injectionClass            class which represents the itemtype to injection
    *                                   (in 0.80, will be directly the itemtype class)
    * @param array|null $values              array values to injection into GLPI
    * @param array|null $injection_options   array options that can be used during the injection
    *                                   (maybe an empty array)
    *
    * @return void nothing
   **/
    public function __construct($injectionClass, $values = [], $injection_options = [])
    {

        $this->setDefaultValues();

        if (isset($injection_options['checks'])) {
            foreach ($injection_options['checks'] as $key => $value) {
                $this->checks[$key] = $value;
            }
        }

        if (isset($injection_options['checks'])) {
            foreach ($injection_options['checks'] as $key => $value) {
                $this->checks[$key] = $value;
            }
        }
        if (isset($injection_options['rights'])) {
            foreach ($injection_options['rights'] as $key => $value) {
                $this->rights[$key] = $value;
            }
        }

        if (isset($injection_options['mandatory_fields'])) {
            $this->mandatory_fields = $injection_options['mandatory_fields'];
        }

        if (isset($injection_options['optional_data'])) {
            $this->optional_infos = $injection_options['optional_data'];
        }

       //Split $injection_options array and store data into the rights internal arrays
        if (isset($injection_options['formats'])) {
            $this->formats = $injection_options['formats'];
        } else {
            $this->formats = ['date_format'  => self::DATE_TYPE_YYYYMMDD,
                'float_format' => self::FLOAT_TYPE_DOT
            ];
        }

       //Store values to inject
        $this->values = $values;

       //Store injectClass & primary_type
        $this->injectionClass = $injectionClass;
        $this->primary_type   = self::getItemtypeByInjectionClass($injectionClass);

       //If entity is given stores it, then use root entity
        if (isset($injection_options['entities_id'])) {
            $this->entity = $injection_options['entities_id'];
        } else {
            $this->entity = 0;
        }
    }


    /**
    * Check and add fields for itemtype which depend on other itemtypes
    * (for example SoftwareLicense needs to be linked to a Software)
    *
    * @param PluginDatainjectionInjectionInterface $injectionClass class to use for injection
   **/
    public function areTypeMandatoryFieldsOK($injectionClass)
    {

        $itemtype = self::getItemtypeByInjectionClass($injectionClass);

       //Add more values needed for mandatory fields management
        if (method_exists($injectionClass, 'getValueForAdditionalMandatoryFields')) {
            $this->values = $injectionClass->getValueForAdditionalMandatoryFields($this->values);
        }

       //Add new mandatory fields to check, needed by other itemtypes to inject
        if (method_exists($injectionClass, 'addSpecificMandatoryFields')) {
            $fields = $injectionClass->addSpecificMandatoryFields();
            foreach ($fields as $key => $value) {
                $this->mandatory_fields[$itemtype][$key] = $value;
            }
        }

        $status_check = true;
        if (count($this->mandatory_fields[$itemtype]) > 0) {
            foreach ($this->mandatory_fields[$itemtype] as $field => $value) {
               //Get value associated with the mandatory field
                $value = $this->getValueByItemtypeAndName($itemtype, $field);

               //Get search option associated with the mandatory field
                $option = self::findSearchOption($injectionClass->getOptions($itemtype), $field);

               //If field not defined, or if value is the dropdown's default value
               //If no value found or value is 0 and field is a dropdown,
               //then mandatory field management failed
                if (
                    ($value === false)
                    || (($value == self::DROPDOWN_EMPTY_VALUE)
                    && self::isFieldADropdown($option['displaytype']))
                ) {
                    $status_check = false;
                    $this->results[self::ACTION_CHECK][] = [self::MANDATORY, $option['name']];
                }
            }
        }
        return $status_check;
    }


    /**
    * Check if a field type represents a dropdown or not
    *
    * @param string $field_type the type of field
    *
    * @return boolean true if it's a dropdown type, false if not
   **/
    public static function isFieldADropdown($field_type)
    {

        if (
            !in_array(
                $field_type,
                ['integer', 'decimal', 'tree',
                    'text', 'multiline_text', 'date'
                ]
            )
        ) {
            return true;
        }
        return false;
    }


    /**
    * Return an the class of an item by giving his injection class
    *
    * @param string $injectionClassName the injection class name
    *
    * @return CommonDBTM instance of the itemtype associated to the injection class name
    */
    public static function getItemtypeInstanceByInjection($injectionClassName)
    {

        $injection = self::getItemtypeByInjectionClass(new $injectionClassName());
        return new $injection();
    }


    /**
    * Get an itemtype name by giving his injection class name
    *
    * @param string $injectionClassName the injection class name
    *
    * @return string the itemtype associated
    */
    public static function getItemtypeByInjection($injectionClassName)
    {

        return self::getItemtypeByInjectionClass(new $injectionClassName());
    }


    /**
    * Get an itemtype by giving an injection class object
    *
    * @param object $injectionClass Name the injection class object
    *
    * @return string instance of the itemtype associated to the injection class
    */
    public static function getItemtypeByInjectionClass($injectionClass)
    {

        return Toolbox::ucfirst(getItemTypeForTable($injectionClass->getTable()));
    }


    /**
    * Get an injection class instance for an itemtype
    *
    * @param string $itemtype  the itemtype
    *
    * @return PluginDatainjectionInjectionInterface the injection class instance
    */
    public static function getInjectionClassInstance($itemtype)
    {

        if (!isPluginItemType($itemtype)) {
            $injectionClass = 'PluginDatainjection' . ucfirst($itemtype) . 'Injection';
        } else {
            $injectionClass = ucfirst($itemtype) . 'Injection';
        }
        return new $injectionClass();
    }


    /**
    * Add blacklisted fields for an itemtype
    *
    * @param string $itemtype the itemtype
    *
    * @return array the array of all blacklisted fields
    */
    public static function getBlacklistedOptions($itemtype)
    {
        /** @var array $CFG_GLPI */
        global $CFG_GLPI;

       // 2 : id
       // 19 : date_mod
       // 80 : entity
       // 121 : date_creation
       // 200 : notepad
        $blacklist = [
            2,   // id
            19,  // last update
            80,  // completename
            121, // creation date
            200, // notepad - content
            201, // notepad - creation date
            202, // notepad - writer
            203, // notepad - last update
            204, // notepad - last updater
        ];

        $raw_options_to_blacklist = [];

       //add document fields
        if (in_array($itemtype, $CFG_GLPI["document_types"])) {
            $raw_options_to_blacklist = array_merge(
                $raw_options_to_blacklist,
                Document::rawSearchOptionsToAdd($itemtype)
            );
        }

       //add infocoms fields
        if (in_array($itemtype, $CFG_GLPI["infocom_types"])) {
            $raw_options_to_blacklist = array_merge(
                $raw_options_to_blacklist,
                Infocom::rawSearchOptionsToAdd($itemtype)
            );
        }

       //add contract fields
        if (in_array($itemtype, $CFG_GLPI["contract_types"])) {
            $raw_options_to_blacklist = array_merge(
                $raw_options_to_blacklist,
                Contract::rawSearchOptionsToAdd()
            );
        }

       //add networkport fields
        if (in_array($itemtype, $CFG_GLPI["networkport_types"])) {
            $raw_options_to_blacklist = array_merge(
                $raw_options_to_blacklist,
                NetworkPort::rawSearchOptionsToAdd($itemtype)
            );
        }

        foreach ($raw_options_to_blacklist as $raw_option) {
            if (!is_numeric($raw_option['id'])) {
                continue;
            }
            $blacklist[] = $raw_option['id'];
        }

       //add ticket_types fields
        if (in_array($itemtype, $CFG_GLPI["ticket_types"])) {
            $blacklist[] = 60;
            $blacklist[] = 140;
        }

        return $blacklist;
    }


    /**
    * Find and return the right search option
    *
    * @param array $options the search options array
    * @param string $lookfor the search option we're looking for
    *
    * @return array the search option matching lookfor parameter or false it not found
   **/
    public static function findSearchOption($options, $lookfor)
    {

        $found = false;
        foreach ($options as $option) {
            if (
                isset($option['injectable']) && ($option['injectable'] == self::FIELD_INJECTABLE)
                && isset($option['linkfield']) && ($option['linkfield'] == $lookfor)
            ) {
                $found = $option;
            }
        }

        return $found;
    }


    //--------------------------------------------------//
    //----------- Injection options getters -----------//
    //------------------------------------------------//

    /**
    * Get date format used for injection
    *
    * @return string date format used
   **/
    private function getDateFormat()
    {

        return $this->formats['date_format'];
    }


    /**
    * Get date format used for injection
    *
    * @return string date format used
   **/
    private function getFloatFormat()
    {

        return $this->formats['float_format'];
    }


    /**
    * Get itemtype associated to the injectionClass
    *
    * @return CommonDBTM an itemtype
   **/
    private function getItemInstance()
    {

        $classname = get_class($this->injectionClass);
        return self::getItemtypeInstanceByInjection($classname);
    }


    /**
    * Return injection results
    *
    * @return array which contains the reformat/check/injection logs
   **/
    public function getInjectionResults()
    {

        return $this->results;
    }


    /**
    * Get ID associate to the value from the CSV file is needed (for example for dropdown tables)
    *
    * @return void nothing
   **/
    private function manageFieldValues()
    {

        $blacklisted_fields = ['id'];

        foreach ($this->values as $itemtype => $data) {
            $injectionClass = self::getInjectionClassInstance($itemtype);
            $searchOptions  = $injectionClass->getOptions($this->primary_type);

            foreach ($data as $field => $value) {
                if (!in_array($field, $blacklisted_fields)) {
                    $searchOption = self::findSearchOption($searchOptions, $field);
                   //searchoption relation type is already manage by manageRelations()
                   //skip it
                    if (
                        !empty($searchOption)
                        && ((isset($searchOption['displaytype']) && $searchOption['displaytype'] != 'relation')
                        || !isset($searchOption['displaytype']))
                    ) {
                        $this->getFieldValue($injectionClass, $itemtype, $searchOption, $field, $value);
                    }
                }
            }

           //TODO : This is ugly, need to find why it adds an empty value to the array
            foreach ($this->values[$itemtype] as $field => $value) {
                if ($field == '') {
                    unset($this->values[$itemtype][$field]);
                }
            }
        }
    }


    /**
    * Get the ID associated with a value from the CSV file
    *
    * @param PluginDatainjectionInjectionInterface|null $injectionClass
    * @param string $itemtype               itemtype of the values to inject
    * @param array $searchOption           option associated with the field to check
    * @param string $field                  the field to check
    * @param string $value                  the value coming from the CSV file
    * @param boolean $add                    is insertion (true) or update (false) (true by default)
    *
    * @return void nothing
   **/
    private function getFieldValue(
        $injectionClass,
        $itemtype,
        $searchOption,
        $field,
        $value,
        $add = true
    ) {
        if (isset($searchOption['storevaluein'])) {
            $linkfield = $searchOption['storevaluein'];
        } else {
            $linkfield = $searchOption['linkfield'];
        }

        switch ($searchOption['displaytype']) {
            case 'tree':
                $this->setValueForItemtype($itemtype, $linkfield, $value);
                break;

            case 'decimal':
            case 'text':
                $this->setValueForItemtype($itemtype, $linkfield, $value);
                break;

            case 'password':
               //To add a user password, it's mandatory is give a password and it's confirmation
               //Here we cannot detect if it's an add or update. We'll handle updates later in the process
                if ($add && $itemtype == 'User') {
                    $this->setValueForItemtype($itemtype, $linkfield, $value);
                    //Add field password2 is not already present
                    //(can be present if password was an addtional information)
                    if (!isset($this->values[$itemtype][$field])) {
                        $this->setValueForItemtype($itemtype, $linkfield . "2", $value);
                    }
                }
                break;

            case 'dropdown':
            case 'relation':
                $tmptype = getItemTypeForTable($searchOption['table']);
                $item    = new $tmptype();
                if ($item instanceof CommonTreeDropdown) {
                   // use findID instead of getID
                    $input =  [
                        'completename' => $value,
                        'entities_id'  => $this->entity
                    ];

                    if ($item->getType() == 'Entity') { // Blocks entity creation. The findID method only searches for direct sub-entities of the root, not deeper levels.
                        $crit = 'name';
                        if (strpos($input['completename'], '>')) {
                            $crit = 'completename';
                        }
                        $entity = new Entity();
                        $result = $entity->getFromDBByCrit(
                            [
                                $crit => $input['completename'],
                                'entities_id' => $input['entities_id']
                            ]
                        );

                        if ($result !== false) {
                            $input['entities_id'] = $entity->fields['id'];
                        }

                        $sons = getSonsOf('glpi_entities', $input['entities_id']);
                        if ($result === false && !empty($sons)) {
                            foreach ($sons as $son_id) {
                                $result = $entity->getFromDBByCrit(
                                    [
                                        $crit => $input['completename'],
                                        'entities_id' => $son_id
                                    ]
                                );
                                if ($result !== false) {
                                    $input['entities_id'] = $entity->fields['id'];
                                    break;
                                }
                            }
                        }

                        $id = $input['entities_id'];
                    } else {
                        if ($item->canCreate() && $this->rights['add_dropdown']) {
                            $id = $item->import($input);
                        } else {
                            $id = $item->findID($input);
                        }
                    }
                } else if ($item instanceof CommonDropdown) {
                    if ($item->canCreate() && $this->rights['add_dropdown']) {
                        $canadd = true;
                    } else {
                        $canadd = false;
                    }

                    $id = $item->importExternal(
                        $value,
                        $this->entity,
                        $this->addExternalDropdownParameters($itemtype),
                        '',
                        $canadd
                    );
                } else {
                    $id = self::findSingle($item, $searchOption, $this->entity, $value);
                }
               // Use EMPTY_VALUE for Mandatory field check
                $this->setValueForItemtype($itemtype, $linkfield, ($id > 0 ? $id : self::EMPTY_VALUE));
                if ($value && $id <= 0) {
                    $this->results['status']                     = self::WARNING;
                    $this->results[self::ACTION_CHECK]['status'] = self::WARNING;
                    $this->results[self::ACTION_CHECK][]         = [self::WARNING_NOTFOUND,
                        $searchOption['name'] . "='$value'"
                    ];
                }
                break;

            case 'template':
                $id = self::getTemplateIDByName($itemtype, $value);
                if ($id) {
                    //Template id is stored into the item's id : when adding the object
                    //glpi will understand that it needs to take fields from the template
                    $this->setValueForItemtype($itemtype, '_oldID', $id);
                }
                break;

            case 'contact':
                if ($value != Dropdown::EMPTY_VALUE) {
                    $id = self::findContact($value, $this->entity);
                } else {
                    $id = Dropdown::EMPTY_VALUE;
                }
                $this->setValueForItemtype($itemtype, $linkfield, $id);
                break;

            case 'user':
                if ($value != Dropdown::EMPTY_VALUE) {
                    $id =  self::findUser($value, $this->entity);
                } else {
                    $id =  Dropdown::EMPTY_VALUE;
                }
                $this->setValueForItemtype($itemtype, $linkfield, $id);
                break;

            default:
                if (method_exists($injectionClass, 'getSpecificFieldValue')) {
                    $id = $injectionClass->getSpecificFieldValue(
                        $itemtype,
                        $searchOption,
                        $field,
                        $this->values
                    );
                    $this->setValueForItemtype($itemtype, $linkfield, $id);
                } else {
                    $this->setValueForItemtype($itemtype, $linkfield, $value);
                }
        }
    }

    /**
    * Add additional parameters needed for dropdown import
    *
    * @param string $itemtype dropdrown's itemtype
    *
    * @return array with additional options to be added
   **/
    private function addExternalDropdownParameters($itemtype)
    {
        /** @var DBmysql $DB */
        global $DB;

        $external = [];
        $values   = $this->getValuesForItemtype($itemtype);
        $toadd    = ['manufacturers_id' => 'manufacturer'];

        foreach ($toadd as $field => $addvalue) {
            if (isset($values[$field])) {
                switch ($addvalue) {
                    case 'manufacturer':
                        if (intval($values[$field]) > 0) {
                            $external[$addvalue]
                            = $DB->escape(
                                Dropdown::getDropdownName(
                                    'glpi_manufacturers',
                                    $values[$field]
                                )
                            );
                            break;
                        }
                        // TODO: is fall-through intentional here ?

                    default:
                        $external[$addvalue] = $values[$field];
                }
            } else {
                $external[$addvalue] = '';
            }
        }
        return $external;
    }


    /**
    * Find a user. Look for login OR firstname + lastname OR lastname + firstname
    *
    * @param string $value the user to look for
    * @param int|string $entity the entity where the user should have right
    *
    * @return int|string the user ID if found or ''
   **/
    private static function findUser($value, $entity)
    {
        /** @var DBmysql $DB */
        global $DB;

        $sql = "SELECT `id`
              FROM `glpi_users`
              WHERE LOWER(`name`) = '" . strtolower($value) . "'
                 OR (CONCAT(LOWER(`realname`),' ',LOWER(`firstname`)) = '" . strtolower($value) . "'
                    OR CONCAT(LOWER(`firstname`),' ',LOWER(`realname`)) = '" . strtolower($value) . "')";
        $result = $DB->doQuery($sql);
        if ($DB->numrows($result) > 0) {
           //check if user has right on the current entity
            $ID       = $DB->result($result, 0, "id");
            $entities = Profile_User::getUserEntities($ID, true);

            if (in_array($entity, $entities)) {
                return $ID;
            }
            return self::DROPDOWN_EMPTY_VALUE;
        }
        return self::DROPDOWN_EMPTY_VALUE;
    }


    /**
    * Find a user. Look for login OR firstname + lastname OR lastname + firstname
    *
    * @param string $value the user to look for
    * @param int|string $entity the entity where the user should have right
    *
    * @return int|string the user ID if found or ''
   */
    private static function findContact($value, $entity)
    {
        /** @var DBmysql $DB */
        global $DB;

        $sql = "SELECT `id`
              FROM `glpi_contacts`
              WHERE `entities_id` = '" . $entity . "'
                 AND (LOWER(`name`) = '" . strtolower($value) . "'
                    OR (CONCAT(LOWER(`name`),' ',LOWER(`firstname`)) = '" . strtolower($value) . "'
                       OR CONCAT(LOWER(`firstname`),' ',LOWER(`name`)) = '" . strtolower($value) . "'))";
        $result = $DB->doQuery($sql);

        if ($DB->numrows($result) > 0) {
           //check if user has right on the current entity
            return $DB->result($result, 0, "id");
        }
        return self::DROPDOWN_EMPTY_VALUE;
    }


    /**
    * Find id for a single type
    *
    * @param CommonDBTM $item the CommonDBTM item representing an itemtype
    * @param array $searchOption searchOption related to the item
    * @param int|string $entity the current entity
    * @param string $value the name of the item for which id must be returned
    *
    * @return int|string the id of the item found
   **/
    private static function findSingle($item, $searchOption, $entity, $value)
    {
        /** @var DBmysql $DB */
        global $DB;

        $query = "SELECT `id`
                FROM `" . $item->getTable() . "`
                WHERE 1";

        if ($item->maybeTemplate()) {
            $query .= " AND `is_template` = '0'";
        }

        if ($item->isEntityAssign()) {
            $query .= getEntitiesRestrictRequest(
                " AND",
                $item->getTable(),
                'entities_id',
                $entity,
                $item->maybeRecursive()
            );
        }

        $query .= " AND `" . $searchOption['field'] . "` = '$value'";
        $result = $DB->doQuery($query);

        if ($DB->numrows($result) > 0) {
           //check if user has right on the current entity
            return $DB->result($result, 0, "id");
        } else {
            return self::DROPDOWN_EMPTY_VALUE;
        }
    }

    /**
    * Get values to inject for an itemtype
    *
    * @param string $itemtype
    *
    * @return string|false an array with all values for this itemtype
   **/
    public function getValuesForItemtype($itemtype)
    {

        if (isset($this->values[$itemtype])) {
            return $this->values[$itemtype];
        }
        return false;
    }


    /**
    * Get values to inject for an itemtype
    *
    * @param string $itemtype
    *
    * @return string|false an array with all values for this itemtype
   **/
    private function getValueByItemtypeAndName($itemtype, $field)
    {

        $values = $this->getValuesForItemtype($itemtype);
        if ($values) {
            return (isset($values[$field]) ? $values[$field] : false);
        }
        return false;
    }


    /**
    * Unset a value to inject for an itemtype
    *
    * @param string $itemtype
    *
    * @return void nothing
   **/
    private function unsetValue($itemtype, $field)
    {

        if ($this->getValueByItemtypeAndName($itemtype, $field)) {
            unset($this->values[$itemtype][$field]);
        }
    }


    /**
     * Set values to inject for an itemtype
     *
     * @param string $itemtype
     * @param string $field name
     * @param string|int $value of the field
     * @param boolean $fromdb boolean
     **/
    private function setValueForItemtype($itemtype, $field, $value, $fromdb = false)
    {
        if ($itemtype === User::class && $field === "pdffont" && $fromdb) {
            return;
        }
       // TODO awfull hack, text ftom CSV set more than once, so check if "another" value
        if (isset($this->values[$itemtype][$field]) && $this->values[$itemtype][$field] != $value) {
           // Data set twice (probably CSV + Additional info)
            $injectionClass = self::getInjectionClassInstance($itemtype);
            $option = self::findSearchOption($injectionClass->getOptions($itemtype), $field);

            if (isset($option['displaytype']) && $option['displaytype'] == 'multiline_text') {
                // If replace_multiline_value is true, the old value is replaced with the new value
                // else, the new value is added to the old value
                if (!$this->rights['replace_multiline_value']) {
                    if ($fromdb) {
                        $this->values[$itemtype][$field] = $value . "\n" . $this->values[$itemtype][$field];
                    } else {
                        $this->values[$itemtype][$field] = $this->values[$itemtype][$field] . "\n" . $value;
                    }
                }
            } elseif (
                ($fromdb && $value && !$this->rights['overwrite_notempty_fields'])
                || !$fromdb
            ) {
               // Overwrite value in DB (from CSV) if allowed in the model option.
                $this->values[$itemtype][$field] = $value;
            }
        } else { // First value
            if (empty($value)) {
                $this->values[$itemtype][$field] = "NULL";
            } else {
                $this->values[$itemtype][$field] = $value;
            }
        }
    }


    /**
    * Get a template name by giving his ID
    *
    * @param string $itemtype the objet's type
    * @param string $name the template's name
    *
    * @return string|false name of the template or false is no template found
   **/
    private static function getTemplateIDByName($itemtype, $name)
    {
        /** @var DBmysql $DB */
        global $DB;
        $item = new $itemtype();
        $query = "SELECT `id`
                FROM `" . getTableForItemType($itemtype) . "`
                WHERE `is_template` = '1'
                      AND `template_name` = '$name'";
        $result = $DB->doQuery($query);

        if ($DB->numrows($result) > 0) {
            return $DB->result($result, 0, 'id');
        }
        return false;
    }


    //--------------------------------------------------//
    //----------- Reformat methods --------------------//
    //------------------------------------------------//
    //Several pass are needed to reformat data
    //because dictionnaries need to be process in a specific order

    /**
    * First pass of data reformat : check values like NULL or values coming from dropdown tables
    *
    * @return void nothing
   **/
    private function reformatFirstPass()
    {

       //Browse all fields & values
        foreach ($this->values as $itemtype => $data) {
            $injectionClass = self::getInjectionClassInstance($itemtype);

           //Get search options associated with the injectionClass
            $searchOptions = $injectionClass->getOptions($itemtype);

            // TODO: fix this code
            // foreach ($data as $field => $value) {
            //     if ($value && $value == "NULL") {
            //         if (false) {
            //             $this->values[$itemtype][$field] = self::EMPTY_VALUE;
            //         }
            //     }
            // }
        }
    }


    /**
    * Second pass of reformat : check if the itemtype needs specific reformat (like Software)
    *
    * @return void nothing
   **/
    private function reformatSecondPass()
    {

        foreach ($this->values as $itemtype => $data) {
            $injectionClass = self::getInjectionClassInstance($itemtype);
            if (method_exists($injectionClass, 'reformat')) {
                //Specific reformat action is itemtype needs it
                $injectionClass->reformat($this->values);
            }
        }
    }


    /**
    * Third pass of reformat : data, mac address & floats
   */
    private function reformatThirdPass()
    {
        /** @var array $CFG_GLPI */
        global $CFG_GLPI;

        foreach ($this->values as $itemtype => $data) {
           //Get injectionClass associated with the itemtype
            $injectionClass = self::getInjectionClassInstance($itemtype);

           //Get search options associated with the injectionClass
            $searchOptions = $injectionClass->getOptions($this->primary_type);

            foreach ($data as $field => $value) {
                //Get search option associated with the field
                $option = self::findSearchOption($searchOptions, $field);

                // Check some types
                switch (isset($option['checktype']) ? $option['checktype'] : 'text') {
                    case "date":
                        //If the value is a date, try to reformat it if it's not the good type
                        //(dd-mm-yyyy instead of yyyy-mm-dd)
                        $date = self::reformatDate($value, $this->getDateFormat());
                        $this->setValueForItemtype($itemtype, $field, $date);
                        break;

                    case "mac":
                        $this->setValueForItemtype($itemtype, $field, self::reformatMacAddress($value));
                        break;

                    case "float":
                        $float = self::reformatFloat($value, $this->getFloatFormat());
                        $this->setValueForItemtype($itemtype, $field, (string) $float);
                        break;

                    default:
                        break;
                }
            }
        }
    }


    /**
    * Perform field reformat (if needed)
    * Composed of 3 passes (one is optional, and depends on the itemtype to inject)
    *
    * @return void nothing
   **/
    private function reformat()
    {

        $this->reformatFirstPass();
        $this->reformatSecondPass();
        $this->reformatThirdPass();
    }



    /**
    * Reformat float value. Input could be :
    * xxxx.xx
    * xx,xxx.xx
    * xxxx,xx
    *
    * @param float|array $value : the float to reformat
    * @param string $format the float format
    *
    * @return float|array modified as expected in GLPI
   **/
    private static function reformatFloat($value, $format)
    {

        if ($value == self::EMPTY_VALUE) {
            return $value;
        }

       //TODO : replace str_replace by a regex
        switch ($format) {
            case self::FLOAT_TYPE_COMMA:
                $value = str_replace(
                    [" ", ","],
                    ["","."],
                    $value
                );
                break;

            case self::FLOAT_TYPE_DOT:
                $value = str_replace(" ", "", $value);
                break;

            case self::FLOAT_TYPE_DOT_AND_COM:
                $value = str_replace(",", "", $value);
                break;
        }
        return $value;
    }



    /**
    * Reformat date from dd-mm-yyyy to yyyy-mm-dd
    *
    * @param string $original_date the original date
    *
    * @return string the date reformated, if needed
   **/
    private static function reformatDate($original_date, $date_format)
    {

        if (empty($original_date)) {
            return "NULL"; // required to avoid "0000-00-00" in the DB
        }
        $new_date = "";
        switch ($date_format) {
            case self::DATE_TYPE_YYYYMMDD:
                $new_date = preg_replace(
                    '/(\d{4})[-\/](\d{1,2})[-\/](\d{1,2})/',
                    '\1-\2-\3',
                    $original_date
                );
                break;

            case self::DATE_TYPE_DDMMYYYY:
                $new_date = preg_replace(
                    '/(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})/',
                    '\3-\2-\1',
                    $original_date
                );
                break;

            case self::DATE_TYPE_MMDDYYYY:
                $new_date = preg_replace(
                    '/(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})/',
                    '\3-\1-\2',
                    $original_date
                );
                break;
        }

        if (preg_match('/[0-9]{2,4}-[0-9]{1,2}-[0-9]{1,2}/', $new_date)) {
            return $new_date;
        }
        return $original_date;
    }


    /**
    * Reformat mac adress if mac doesn't contains : or - as seperator
    *
    * @param string $mac the original mac address
    *
    * @return string the mac address modified, if needed
   **/
    private static function reformatMacAddress($mac)
    {

        $pattern  = "/^([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})";
        $pattern .= "([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/";
        preg_match($pattern, $mac, $results);

        if (count($results) > 0) {
            $mac   = "";
            $first = true;
            unset($results[0]);

            foreach ($results as $result) {
                $mac  .= (!$first ? ":" : "") . $result;
                $first = false;
            }
        }
        return $mac;
    }


    //--------------------------------------------------//
    //----------- Check methods -----------------------//
    //------------------------------------------------//

    /**
    * Check all data to be imported
    *
    * @return void nothing
   **/
    private function check()
    {

        $continue = true;

        foreach ($this->values as $itemtype => $fields) {
            $injectionClass = self::getInjectionClassInstance($itemtype);
           //Get search options associated with the injectionClass
            $searchOptions = $injectionClass->getOptions($itemtype);

            foreach ($fields as $field => $value) {
                $mandatory = false;
                if (isset($this->mandatory_fields[$itemtype][$field])) {
                    $mandatory = $this->mandatory_fields[$itemtype][$field];
                }

               //Get search option associated with the field
                $option = self::findSearchOption($searchOptions, $field);

                if ($value == self::EMPTY_VALUE && $mandatory) {
                    $this->results['status']                     = self::FAILED;
                    $this->results[self::ACTION_CHECK]['status'] = self::FAILED;
                    $this->results[self::ACTION_CHECK][]         = [self::MANDATORY, $field];
                    $continue = false;
                } else {
                    $check_result = $this->checkType(
                        $injectionClass,
                        $option,
                        $field,
                        $value,
                        $mandatory
                    );
                    $this->results[self::ACTION_CHECK][] = [$check_result,
                        $field . "='$value'"
                    ];

                    if ($check_result != self::SUCCESS) {
                        $this->results[self::ACTION_CHECK]['status'] = self::FAILED;
                        $this->results['status']                     = self::FAILED;
                        $continue = false;
                    }
                }
            }
        }
    }


    /**
    * Is a value a float ?
    *
    * @param mixed $val the value to check
    *
    * @return boolean true if it's a float, false otherwise
    */
    private function isFloat($val)
    {

        return is_numeric($val) && ($val == floatval($val));
    }

    /**
    * Is a value an integer ?
    *
    * @param mixed $val the value to check
    *
    * @return boolean true if it's an integer, false otherwise
    */
    private function isInteger($val)
    {

        return is_numeric($val) && ($val == intval($val));
    }

    /**
    * Check one data
    *
    * @param PluginDatainjectionInjectionInterface $injectionClass the injection class
    * @param array $option the option associated with the field
    * @param string $field_name the field name
    * @param mixed $data the data to check
    * @param boolean $mandatory is the field mandatory
    *
    * @return int the result of the check
   **/
    private function checkType($injectionClass, $option, $field_name, $data, $mandatory)
    {

        if (!empty($option)) {
            $field_type = (isset($option['checktype']) ? $option['checktype'] : 'text');

           //If no data provided AND this mapping is not mandatory
            if (
                !$mandatory
                && ($data == null || $data == "NULL" || $data == self::EMPTY_VALUE)
            ) {
                return self::SUCCESS;
            }

            switch ($field_type) {
                case 'tree':
                case 'text':
                    if (isset($option['displaytype']) && $option['displaytype'] == 'multiline_text') {
                        return self::SUCCESS;
                    }
                    if (strlen($data) > 255) {
                        return self::ERROR_FIELDSIZE_EXCEEDED;
                    }
                    return self::SUCCESS;

                case 'integer':
                    return (self::isInteger($data) ? self::SUCCESS : self::TYPE_MISMATCH);

                case 'decimal':
                case 'float':
                    return (self::isFloat($data) ? self::SUCCESS : self::TYPE_MISMATCH);

                case 'date':
                    // Date is already "reformat" according to getDateFormat()
                    $pat = '/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$/';
                    $res = preg_match($pat, $data, $regs);
                    return ($res ? self::SUCCESS : self::TYPE_MISMATCH);

                case 'ip':
                    preg_match("/([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/", $data, $regs);
                    return ((count($regs) > 0) ? self::SUCCESS : self::TYPE_MISMATCH);

                case 'mac':
                    preg_match("/([0-9a-fA-F]{2}([:-]|$)){6}$/", $data, $regs);
                    return ((count($regs) > 0) ? self::SUCCESS : self::TYPE_MISMATCH);

                case 'itemtype':
                    return (class_exists($data) ? self::SUCCESS : self::TYPE_MISMATCH);

                case 'bool':
                    //If not numeric => type mismatch
                    if (!is_numeric($data)) {
                        return self::TYPE_MISMATCH;
                    }
                    if ($data == 0 || $data == 1) {
                        return self::SUCCESS;
                    } else {
                        return self::TYPE_MISMATCH;
                    }

                default:
                 //Not a standard check ? Try checks specific to the injection class
                 //Will return SUCCESS if it's not a specific check
                    if (method_exists($injectionClass, 'checkType')) {
                        return $injectionClass->checkType($field_name, $data, $mandatory);
                    }
                    return self::SUCCESS;
            }
        }
        return self::SUCCESS;
    }


    //--------------------------------------------------//
    //------ Pre and post injection methods -----------//
    //------------------------------------------------//

    /**
    * Add fields needed for all type injection
    *
    * @return void nothing
   **/
    private function addNecessaryFields()
    {

        if (!isset($this->values[$this->primary_type]['entities_id'])) {
            $this->setValueForItemtype($this->primary_type, 'entities_id', $this->entity);
        }
        if (method_exists($this->injectionClass, 'addSpecificNeededFields')) {
            $specific_fields = $this->injectionClass->addSpecificNeededFields(
                $this->primary_type,
                $this->values
            );
            foreach ($specific_fields as $field => $value) {
                  $this->setValueForItemtype($this->primary_type, $field, $value);
            }
        }
    }

    /**
    * Add fields needed to inject and itemtype
    *
    * @param object $injectionClass class which represents the object to inject
    * @param string $itemtype the itemtype to inject
    *
    * @return void nothing
   **/
    private function addNeededFields($injectionClass, $itemtype)
    {

       //Add itemtype
        $this->setValueForItemtype($itemtype, 'itemtype', $this->primary_type);

       //Add primary item's id
        $id = $this->getValueByItemtypeAndName($this->primary_type, 'id');
        $this->setValueForItemtype($itemtype, 'items_id', $id);

       //Add entities_id if itemtype can be assigned to an entity
        if ($injectionClass->isEntityAssign()) {
            $entities_id = $this->getValueByItemtypeAndName($this->primary_type, 'entities_id');
            $this->setValueForItemtype($itemtype, 'entities_id', $entities_id);
        }

       //Add is_recursive if itemtype can be assigned recursive
        if ($injectionClass->isRecursive()) {
            $recursive = $this->getValueByItemtypeAndName($this->primary_type, 'is_recursive');
            $this->setValueForItemtype($itemtype, 'is_recursive', $recursive);
        }

        if (method_exists($injectionClass, 'addSpecificNeededFields')) {
            $specific_fields = $injectionClass->addSpecificNeededFields(
                $this->primary_type,
                $this->values
            );
            foreach ($specific_fields as $field => $value) {
                 $this->setValueForItemtype($itemtype, $field, $value);
            }
        }
    }


    /**
    * Check value before processing import. Last change to stop import of data
    *
    * @return bool
   **/
    private function lastCheckBeforeProcess($injectionClass, $values)
    {

       //Specific reformat action is itemtype needs it
        if (method_exists($injectionClass, 'lastCheck')) {
            return $injectionClass->lastCheck($this->values);
        } else {
            return true;
        }
    }

    //--------------------------------------------------//
    //-------- Add /Update/Delete methods -------------//
    //------------------------------------------------//

    /**
    * Process of inject data into GLPI
    *
    * @return array which contains the injection results
   **/
    public function processAddOrUpdate()
    {
        $process  = false;
        $add      = true;
        $accepted = false;

        $this->values = Sanitizer::dbUnescapeRecursive($this->values);
       //Toolbox::logDebug("processAddOrUpdate(), start with", $this->values);

       // Initial value, will be change when problem
        $this->results['status']                     = self::SUCCESS;
        $this->results[self::ACTION_CHECK]['status'] = self::SUCCESS;

       //Manage fields belonging to relations between tables
        $this->manageRelations();

        $accepted = $this->processDictionnariesIfNeeded();

       //Get real value for fields (ie dropdown, etc)
        $this->manageFieldValues();

       //Check if the type to inject requires additional fields
       //(for example to link it with another type)
        if (!$this->areTypeMandatoryFieldsOK($this->injectionClass)) {
            $process                                     = false;
            $this->results['status']                     = self::FAILED;
            $this->results[self::ACTION_CHECK]['status'] = self::FAILED;
        } else {
           //Check is data to be inject still exists in DB (update) or not (add)
            $this->dataAlreadyInDB($this->injectionClass, $this->primary_type);
            $process = true;

           //No item found in DB
            if ($this->getValueByItemtypeAndName($this->primary_type, 'id') == self::ITEM_NOT_FOUND) {
               //Can add item ?
                $this->results['type'] = self::IMPORT_ADD;
                if (!$accepted) {
                    $this->results['status'] = self::ERROR_IMPORT_REFUSED;
                    $process = false;
                } else {
                    if ($this->rights['can_add']) {
                        $add = true;
                        $this->unsetValue($this->primary_type, 'id');
                    } else {
                        $process = false;
                        $this->results['status'] = self::ERROR_CANNOT_IMPORT;
                    }
                }
            } else { //Item found in DB
                $this->results['type'] = self::IMPORT_UPDATE;
                $this->results[$this->primary_type]
                        = $this->getValueByItemtypeAndName($this->primary_type, 'id');

                if ($this->rights['can_update']) {
                    $add = false;
                } else {
                    $process = false;
                    $this->results['status'] = self::ERROR_CANNOT_UPDATE;
                }
            }
        }

        if ($process) {
           //First : reformat data
            $this->reformat();

           //Second : check data
            $this->check();
            if ($this->results['status'] != self::FAILED) {
               //Third : inject data
                $item = $this->getItemInstance();
                $item->getEmpty();

               //Add important fields for injection
                $this->addNecessaryFields();

               //Add aditional infos
                $this->addOptionalInfos();

               //Manage template only when adding an item
                if ($this->results['type'] == self::IMPORT_ADD) {
                    //If needed, manage templates
                    $this->addTemplateFields($this->primary_type);
                }
                $values = $this->getValuesForItemtype($this->primary_type);
                $newID  = $this->effectiveAddOrUpdate($this->injectionClass, $item, $values, $add);

                if (!$newID) {
                    $this->results['status'] = self::WARNING;
                } else {
                  //Store id of the injected item
                    $this->setValueForItemtype($this->primary_type, 'id', $newID);

                  //If type needs it : process more data after type import
                    $this->processAfterInsertOrUpdate($this->injectionClass, $add);
                  //$this->results['status'] = self::SUCCESS;
                    $this->results[get_class($item)] = $newID;

                  //Process other types

                  //change order of items if needed
                    if (isset($this->values['NetworkPort']) && isset($this->values['NetworkName'])) {
                        $nw = $this->values['NetworkPort'];
                        unset($this->values['NetworkPort']);
                        $networkNameIndex = array_search('NetworkName', array_keys($this->values));
                        $this->values = array_merge(
                            array_slice($this->values, 0, $networkNameIndex),
                            array('NetworkPort' => $nw),
                            array_slice($this->values, $networkNameIndex)
                        );
                    }

                    foreach ($this->values as $itemtype => $data) {
                       //Do not process primary_type

                        if ($itemtype != get_class($item)) {
                            $injectionClass = self::getInjectionClassInstance($itemtype);
                            $item           = new $itemtype();

                            $this->addNeededFields($injectionClass, $itemtype);
                            $this->dataAlreadyInDB($injectionClass, $itemtype);

                            if ($this->getValueByItemtypeAndName($itemtype, 'id') == self::ITEM_NOT_FOUND) {
                                 $add = true;
                                 $this->unsetValue($itemtype, 'id');
                            } else {
                                $add = false;
                            }
                            $values = $this->getValuesForItemtype($itemtype);
                            if ($this->lastCheckBeforeProcess($injectionClass, $values)) {
                                $tmpID  = $this->effectiveAddOrUpdate($injectionClass, $item, $values, $add);
                                $this->processAfterInsertOrUpdate($injectionClass, $add);
                            }
                        }
                    }
                }
            }
        }
        return $this->results;
    }


    /**
    * Perform data injection into GLPI DB
    *
    * @param PluginDatainjectionInjectionInterface $injectionClass class which represents the object to inject
    * @param CommonDBTM $item the CommonDBTM object representing the itemtype to inject
    * @param array|string|false $values the values to inject
    * @param boolean $add true to insert an object, false to update an existing object
    *
    * @return int|string the id of the object added or updated
   **/
    private function effectiveAddOrUpdate($injectionClass, $item, $values, $add = true)
    {

       //Insert data using the standard add() method
        $toinject = [];
        $options  = $injectionClass->getOptions();

        foreach ($values as $key => $value) {
            $option = self::findSearchOption($options, $key);
            if (!empty($option) && isset($option['checktype']) && $option['checktype'] == self::FIELD_VIRTUAL) {
                break;
            }

            //CommonDBRelation are managed separately, so related field should be ignored
            // Ex : User -> groups_id -> Group_User
            // groups_id should not be injected in User (field contains group name (string))
            if (!empty($option) && isset($option['displaytype']) && $option['displaytype'] == 'relation') {
                continue;
            }

            if (!empty($option) && self::isFieldADropdown($option['displaytype']) && $value == self::EMPTY_VALUE) {
                //If field is a dropdown and value is '', then replace it by 0
                $toinject[$key] = self::DROPDOWN_EMPTY_VALUE;
            } else {
                $toinject[$key] = $value;
            }

            //for CommonDBRelation, keep items_id and itemtype and items_id_1
            if (
                $item instanceof CommonDBRelation
                && in_array($key, [
                    'items_id',
                    'itemtype',
                    $item::$items_id_1
                ])
            ) {
                $toinject[$key] = $value;
            }

            //keep id in case of update
            if (!$add && $key === 'id') {
                $toinject[$key] = $value;
            }
        }

        $toinject = Sanitizer::dbEscapeRecursive($toinject);

        $newID = null;
        if (method_exists($injectionClass, 'customimport')) {
            $newID = call_user_func(
                [$injectionClass, 'customimport'],
                $toinject,
                $add,
                $this->rights
            );
        } elseif ($item instanceof CommonDropdown && $add) {
            $newID = $item->import($toinject);
        } else {
            if ($add) {
                if ($newID = $item->add($toinject)) {
                    self::logAddOrUpdate($item, $add);
                }
            } else {
               // allow to update locked fields from datainjection
               // for dynamic item if needed
                if ($item->maybeDynamic()) {
                    unset($toinject['is_dynamic']);
                }
                if ($item->update($toinject)) {
                    $newID = $toinject['id'];
                    self::logAddOrUpdate($item, $add);
                }
            }
        }
        $this->setValueForItemtype(get_class($item), 'id', $newID);
        return $newID;
    }


    /**
    * Add optional informations filled by the user
    *
    * @return void nothing
   **/
    private function addOptionalInfos()
    {

        foreach ($this->optional_infos as $itemtype => $data) {
            foreach ($data as $field => $value) {
                //Exception for template management
                //We've got the template id, not let's add the template name
                if ($field == 'templates_id') {
                    $item = new $itemtype();
                    if ($item->getFromDB($value)) {
                        $this->setValueForItemtype(
                            $itemtype,
                            'template_name',
                            $item->fields['template_name']
                        );
                         $this->setValueForItemtype($itemtype, '_oldID', $value);
                    }
                } else {
                    $this->setValueForItemtype($itemtype, $field, $value);
                }
                $this->addSpecificOptionalInfos($itemtype, $field, $value);
            }
        }
    }

    /**
    * If an optional info need more processing (for example password)
    *
    * @param  $itemtype being injected
    * @param  $field the optional info field
    * @param  $value the optional info value
    * @return void nothing
    */
    protected function addSpecificOptionalInfos($itemtype, $field, $value)
    {
    }

    /**
    * Process dictionnaries if needed
    *
    * @since  2.1.6
    * @return bool
    */
    protected function processDictionnariesIfNeeded()
    {

       //If itemtype implements special process after type injection
        if (method_exists($this->injectionClass, 'processDictionnariesIfNeeded')) {
           //Invoke it
            return $this->injectionClass->processDictionnariesIfNeeded($this->values);
        } else {
            return true;
        }
    }

    /**
    * Manage fields tagged as relations
    *
    * @return void nothing
   **/
    private function manageRelations()
    {

        foreach ($this->values as $itemtype => $data) {
            $injectionClass = self::getInjectionClassInstance($itemtype);
           //Get search options associated with the injectionClass
            $searchOptions = $injectionClass->getOptions($this->primary_type);

            foreach ($searchOptions as $id => $option) {
                //If it's a relation
                if (
                    isset($option['displaytype'])
                    && $option['displaytype'] == 'relation'
                    && isset($this->values[$itemtype][$option['linkfield']])
                ) {
                   //Get the relation object associated with the field
                   //Add a new array for the relation object
                    $value = $this->getValueByItemtypeAndName($itemtype, $option['linkfield']);
                    $this->getFieldValue(
                        null,
                        $option['relationclass'],
                        $option,
                        $option['linkfield'],
                        $value,
                        true
                    );
                }
            }
        }
    }


    /**
    * Function to check if the data to inject already exists in DB
    *
    * @param object $injectionClass which represents type to inject
    * @param string $itemtype the itemtype to inject
    *
    * @return void nothing
   **/
    private function dataAlreadyInDB($injectionClass, $itemtype)
    {
        /** @var DBmysql $DB */
        global $DB;

        $where    = "";
        $continue = true;

        $searchOptions = $injectionClass->getOptions($this->primary_type);

       //Add sql request checks specific to this itemtype
        $options['checks']   = $this->checks;
        $options['itemtype'] = $this->primary_type;

       //If injectionClass has a method to check if needed parameters are present
        $values = $this->getValuesForItemtype($itemtype);

       //If an itemtype needs special treatment (for example entity)
        if (method_exists($injectionClass, 'customDataAlreadyInDB')) {
            if ($res = $injectionClass->customDataAlreadyInDB($injectionClass, $values, $options)) {
                $this->setValueForItemtype($itemtype, 'id', $res);
            } else {
                $this->setValueForItemtype($itemtype, 'id', self::ITEM_NOT_FOUND);
            }
        } else {
            if (method_exists($injectionClass, 'checkParameters')) {
                $continue = $injectionClass->checkParameters($values, $options);
            }

           //Needed parameters are not present : not found
            if (!$continue) {
                $this->values[$itemtype]['id'] = self::ITEM_NOT_FOUND;
            } else {
                $sql = "SELECT *
                    FROM `" . $injectionClass->getTable() . "`";

                $item = new $itemtype();
               //If it's a computer device
                if ($item instanceof CommonDevice) {
                    $sql .= " WHERE `designation` = '" .
                    $this->getValueByItemtypeAndName($itemtype, 'designation') . "'";
                } elseif ($item instanceof CommonDBRelation) {
                   //Type is a relation : check it this relation still exists
                   //Define the side of the relation to use

                    if (method_exists($injectionClass, 'relationSide')) {
                        $side = $injectionClass->relationSide();
                    } else {
                        $side = true;
                    }

                    if ($side) {
                        $source_id            = $item::$items_id_2;
                        $destination_id       = $item::$items_id_1;
                        $source_itemtype      = $item::$itemtype_2;
                        $destination_itemtype = $item::$itemtype_1;
                    } else {
                        $source_id            = $item::$items_id_1;
                        $destination_id       = $item::$items_id_2;
                        $source_itemtype      = $item::$itemtype_1;
                        $destination_itemtype = $item::$itemtype_2;
                    }

                    $where .= " AND `$source_id`='" .
                    $this->getValueByItemtypeAndName($itemtype, $source_id) . "'";

                    if ($item->isField('itemtype')) {
                        $where .= " AND `$source_itemtype`='" .
                        $this->getValueByItemtypeAndName($itemtype, $source_itemtype) . "'";
                    }

                    $where .= " AND `" . $destination_id . "`='" .
                    $this->getValueByItemtypeAndName($itemtype, $destination_id) . "'";
                    $sql   .= " WHERE 1 " . $where;
                } else {
                   //Type is not a relation

                   //Type can be deleted
                    if ($injectionClass->maybeDeleted()) {
                        $where .= " AND `is_deleted` = '0' ";
                    }

                   //Type can be a template
                    if ($injectionClass->maybeTemplate()) {
                        $where .= " AND `is_template` = '0' ";
                    }

                   //Type can be assigned to an entity
                    if ($injectionClass->isEntityAssign()) {
                       //Type can be recursive
                        if ($injectionClass->maybeRecursive()) {
                            $where_entity = getEntitiesRestrictRequest(
                                " AND",
                                $injectionClass->getTable(),
                                "entities_id",
                                $this->getValueByItemtypeAndName(
                                    $itemtype,
                                    'entities_id'
                                ),
                                true
                            );
                        } else {
                           //Type cannot be recursive
                            $where_entity = " AND `entities_id` = '" .
                            $this->getValueByItemtypeAndName($itemtype, 'entities_id') . "'";
                        }
                    } else { //If no entity assignment for this itemtype
                        $where_entity = "";
                    }

                   //Add mandatory fields to the query only if it's the primary_type to be injected
                    if ($itemtype == $this->primary_type) {
                        foreach ($this->mandatory_fields[$itemtype] as $field => $is_mandatory) {
                            if ($is_mandatory) {
                                if ($item instanceof User && $field == "useremails_id") {
                                    $email = $DB->escape($this->getValueByItemtypeAndName($itemtype, $field));
                                    $where .= " AND `id` IN (SELECT `users_id` FROM glpi_useremails WHERE `email` = '$email') ";
                                } else {
                                    $where .= " AND `" . $field . "`='" .
                                    Sanitizer::dbEscape((string) $this->getValueByItemtypeAndName($itemtype, $field)) . "'";
                                }
                            }
                        }
                    } else {
                       //Table contains an itemtype field
                        if ($injectionClass->isField('itemtype')) {
                            $where .= " AND `itemtype` = '" . $this->getValueByItemtypeAndName(
                                $itemtype,
                                'itemtype'
                            ) . "'";
                        }

                       //Table contains an items_id field
                        if ($injectionClass->isField('items_id')) {
                            $where .= " AND `items_id` = '" . $this->getValueByItemtypeAndName(
                                $itemtype,
                                'items_id'
                            ) . "'";
                        }
                    }

                   //Add additional parameters specific to this itemtype (or function checkPresent exists)
                    if (method_exists($injectionClass, 'checkPresent')) {
                        $where .= $injectionClass->checkPresent($this->values, $options);
                    }
                    $sql .= " WHERE 1 " . $where_entity . " " . $where;
                }
                $result = $DB->doQuery($sql);
                if ($DB->numrows($result) > 0) {
                    $db_fields = $DB->fetchAssoc($result);
                    foreach ($db_fields as $key => $value) {
                        $this->setValueForItemtype($itemtype, $key, $value, true);
                    }
                    $this->setValueForItemtype($itemtype, 'id', $DB->result($result, 0, 'id'));
                } else {
                    $this->setValueForItemtype($itemtype, 'id', self::ITEM_NOT_FOUND);
                }
            }
        }
    }


    /**
    * Add fields coming for a template to the values to be injected
    *
    * @param string $itemtype the itemtype to inject
    *
    * @return void
   **/
    private function addTemplateFields($itemtype)
    {

       //If data inserted is not a template
        if (!$this->getValueByItemtypeAndName($itemtype, 'is_template')) {
            $template    = new $itemtype();
            $template_id = $this->getValueByItemtypeAndName($itemtype, '_oldID');

            if ($template->getFromDB($template_id) && $itemtype != 'Entity') {
                unset($template->fields["id"]);
                unset($template->fields["date_mod"]);
                unset($template->fields["is_template"]);
                unset($template->fields["entities_id"]);

                foreach ($template->fields as $key => $value) {
                    if (
                        $value != self::EMPTY_VALUE
                        && (!isset($this->values[$itemtype][$key])
                        || $this->values[$itemtype][$key] == self::EMPTY_VALUE
                        || $this->values[$itemtype][$key] == self::DROPDOWN_EMPTY_VALUE)
                    ) {
                        //$value = Toolbox::addslashes_deep($value);
                        $this->setValueForItemtype($itemtype, $key, $value);
                    }
                }
                if (isset($this->values[$itemtype]['name'])) {
                    $name = autoName(
                        $this->values[$itemtype]['name'],
                        "name",
                        true,
                        $itemtype,
                        $this->values[$itemtype]['entities_id']
                    );
                    $this->setValueForItemtype($itemtype, 'name', $name);
                }
                if (isset($this->values[$itemtype]['otherserial'])) {
                    $otherserial = autoName(
                        $this->values[$itemtype]['otherserial'],
                        "otherserial",
                        true,
                        $itemtype,
                        $this->values[$itemtype]['entities_id']
                    );
                    $this->setValueForItemtype($itemtype, 'otherserial', $otherserial);
                }
            }
        }
    }


    /**
    * Log event into the history
    *
    * @param object $item the item to log
    * @param boolean $add true if it's an add, false if it's an update
    *
    * @return void nothing
   **/
    public static function logAddOrUpdate($item, $add = true)
    {

        if ($item->dohistory) {
            $changes[0] = 0;

            if ($add) {
                $changes[2] = __('Add from CSV file', 'datainjection');
            } else {
                $changes[2] = __('Update from CSV file', 'datainjection');
            }
            $changes[1] = "";
            Log::history($item->fields['id'], get_class($item), $changes);
        }
    }


    /**
    * Get label associated with an injection action
    *
    * @param string $action code as defined in the head of this file
    *
    * @return string label associated with the code
   **/
    public static function getActionLabel($action)
    {

        $actions = [self::IMPORT_ADD    => __('Add'),
            self::IMPORT_UPDATE => __('Update'),
            self::IMPORT_DELETE => __('Delete')
        ];

        if (isset($actions[$action])) {
            return  $actions[$action];
        }
        return "";
    }


    /**
    * Get label associated with an injection result
    *
    * @param string|int $type code as defined in the head of this file
    *
    * @return string label associated with the code
   **/
    public static function getLogLabel($type)
    {

        $message = "";

        switch ($type) {
            case self::ERROR_CANNOT_IMPORT:
                $message = __('No right to import data', 'datainjection');
                break;

            case self::ERROR_CANNOT_UPDATE:
                $message = __('No right to update data', 'datainjection');
                break;

            case self::ERROR_FIELDSIZE_EXCEEDED:
                $message = __('Size of the inserted value is to expansive', 'datainjection');
                break;

            case self::ERROR_IMPORT_REFUSED:
                $message = __('Import not allowed', 'datainjection');
                break;

            case self::FAILED:
                $message = __('Import failed', 'datainjection');
                break;

            case self::MANDATORY:
                $message = __('At least one mandatory field is not present', 'datainjection');
                break;

            case self::SUCCESS:
                $message = __('Data to insert are correct', 'datainjection');
                break;

            case self::TYPE_MISMATCH:
                $message = __('One data is not the good type', 'datainjection');
                break;

            case self::WARNING:
                $message = __('Warning', 'datainjection');
                break;

            case self::WARNING_NOTFOUND:
                $message = __('Data not found', 'datainjection');
                break;

            default:
                $message = __('Undetermined', 'datainjection');
                break;
        }

        return $message;
    }

    /**
    * Manage search options
   **/
    public static function addToSearchOptions(
        array $type_searchOptions,
        array $options,
        PluginDatainjectionInjectionInterface $injectionClass
    ) {

        self::addTemplateSearchOptions($injectionClass, $type_searchOptions);

       //Add linkfield for theses fields : no massive action is allowed in the core, but they can be
       //imported using the commonlib
        $add_linkfield = ['comment' => 'comment',
            'notepad' => 'notepad'
        ];

        foreach ($type_searchOptions as $id => $tmp) {
            if (!is_array($tmp) || !isset($tmp['field']) || in_array($id, $options['ignore_fields'])) {
                unset($type_searchOptions[$id]);
            } else {
                if (in_array($tmp['field'], $add_linkfield)) {
                    $type_searchOptions[$id]['linkfield'] = $add_linkfield[$tmp['field']];
                }

                if (!in_array($id, $options['ignore_fields']) && $id < 1000) {
                    if (!isset($tmp['linkfield'])) {
                        $type_searchOptions[$id]['injectable'] = self::FIELD_VIRTUAL;
                    } else {
                        $type_searchOptions[$id]['injectable'] = self::FIELD_INJECTABLE;
                    }

                    //Some injection.class files are missing dropdown options. Set displaytype as dropdown if datatype is dropdown
                    //$tmp['displaytype'] is still empty. Set to prevent overwriting on next IF
                    if ((isset($tmp['datatype']) && $tmp['datatype'] == 'dropdown') && !isset($tmp['displaytype'])) {
                        $type_searchOptions[$id]['displaytype'] = 'dropdown';
                        $tmp['displaytype'] = 'dropdown';
                    }

                    if (isset($tmp['linkfield']) && !isset($tmp['displaytype'])) {
                        $type_searchOptions[$id]['displaytype'] = 'text';
                    }

                    if (isset($tmp['linkfield']) && !isset($tmp['checktype'])) {
                        $type_searchOptions[$id]['checktype'] = 'text';
                    }
                }
            }
        }

       // Drop not injectable fields
        foreach ($type_searchOptions as $id => $tmp) {
            if (!isset($tmp['injectable']) || $tmp['injectable'] <= 0) {
                unset($type_searchOptions[$id]);
            }
        }

        foreach (['displaytype', 'checktype'] as $paramtype) {
            if (isset($options[$paramtype])) {
                foreach ($options[$paramtype] as $type => $tabsID) {
                    foreach ($tabsID as $tabID) {
                        $type_searchOptions[$tabID][$paramtype] = $type;
                    }
                }
            }
        }

       /*
       * Preserve only one option per linkfield, as mapping process is based on arrays indexed by linkfield.
       * This is a hack to handle behaviour explained in issue https://github.com/pluginsGLPI/datainjection/issues/121.
       *
       * Preserved option is "complename" if existing, or "name" if existing,
       * or first founded option for each linkfield.
       */
        $linkfield_preserved_option = array_fill_keys(array_column($type_searchOptions, 'linkfield'), null);
        foreach ($type_searchOptions as $option) {
            if (!array_key_exists('linkfield', $option)) {
                continue;
            }

            $linkfield = $option['linkfield'];

            if (
                null === $linkfield_preserved_option[$linkfield]
                || ('name' === $option['field'] && 'completename' !== $linkfield_preserved_option[$linkfield]['field'])
                || 'completename' === $option['field']
            ) {
                $linkfield_preserved_option[$linkfield] = $option;
            }
        }

        $type_searchOptions = array_filter(
            $type_searchOptions,
            function ($option) use ($linkfield_preserved_option) {
                return in_array($option, $linkfield_preserved_option);
            }
        );

        $main_itemtype = self::getItemtypeByInjectionClass($injectionClass);
        foreach ($type_searchOptions as $key => &$value) {
            $value_itemtype = getItemTypeForTable($value['table']);
            $value_itemtype_name_singular = $value_itemtype::getTypeName(1);
            $value_itemtype_name_plural   = $value_itemtype::getTypeName(Session::getPluralNumber());
            if (
                $main_itemtype === $value_itemtype
                || $value['name'] === $value_itemtype_name_singular
                || $value['name'] === $value_itemtype_name_plural
            ) {
                continue;
            }
            $value['name'] = $value['name'] . " (" . $value_itemtype_name_singular . ")";
        }

        return $type_searchOptions;
    }


    /**
    * Add necessary search options for template management
    *
    * @param PluginDatainjectionInjectionInterface $injectionClass the injection class to use
    * @param array $tab the options tab, as an array (passed as a reference)
    *
    * @return void nothing
   **/
    public static function addTemplateSearchOptions($injectionClass, &$tab)
    {

        $itemtype = self::getItemtypeByInjectionClass($injectionClass);
        $item     = new $itemtype();

        if ($item->maybeTemplate()) {
            $tab[300]['table']       = $item->getTable();
            $tab[300]['field']       = 'is_template';
            $tab[300]['linkfield']   = 'is_template';
            $tab[300]['name']        = __('is') . " " . __('Template') . " ?";
            $tab[300]['type']        = 'integer';
            $tab[300]['injectable']  = 1;
            $tab[300]['checktype']   = 'integer';
            $tab[300]['displaytype'] = 'bool';

            $tab[301]['table']       = $item->getTable();
            $tab[301]['field']       = 'template_name';
            $tab[301]['name']        = __('Template');
            $tab[301]['injectable']  = 1;
            $tab[301]['checktype']   = 'text';
            $tab[301]['displaytype'] = 'template';
            $tab[301]['linkfield']   = 'templates_id';
        }
    }


    /**
    * If itemtype injection needs to process things after data is written in DB
    *
    * @param PluginDatainjectionInjectionInterface $injectionClass the injection class to use
    * @param  $add true if an item is created, false if it's an update
    *
    * @return void nothing
   **/
    private function processAfterInsertOrUpdate($injectionClass, $add = true)
    {

       //If itemtype implements special process after type injection
        if (method_exists($injectionClass, 'processAfterInsertOrUpdate')) {
           //Invoke it
            $injectionClass->processAfterInsertOrUpdate($this->values, $add, $this->rights);
        }
    }
}
