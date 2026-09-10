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

final class InjectionWriteRightTest extends DbTestCase
{
    private function injectData(
        object $injection_class,
        array $injected_data,
        array $mandatory_fields
    ): array {
        $lib = new PluginDatainjectionCommonInjectionLib(
            $injection_class,
            $injected_data,
            [
                'rights' => [
                    'can_add'      => true,
                    'can_update'   => true,
                    'add_dropdown' => true,
                ],
                'mandatory_fields' => $mandatory_fields,
                'entities_id'      => 0,
            ],
        );

        $lib->processAddOrUpdate();

        return $lib->getInjectionResults();
    }

    public function testInjectedUserPasswordIsUsableAndRaisesNoError(): void
    {
        global $CFG_GLPI;
        $CFG_GLPI["event_loglevel"] = 0;

        $this->login();

        $login    = 'test_injected_user_' . random_int(1, PHP_INT_MAX);
        $password = 'Ohbah7ohw!aeK3';

        $results = $this->injectData(
            new PluginDatainjectionUserInjection(),
            ['User' => ['name' => $login, 'password' => $password]],
            ['User' => ['name' => true]],
        );

        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);
        self::assertEmpty($_SESSION['MESSAGE_AFTER_REDIRECT'][ERROR] ?? []);

        $user = new User();
        self::assertTrue($user->getFromDB($results['User']));
        self::assertTrue(Auth::checkPassword($password, $user->fields['password']));
    }

    public function testInjectedUserPasswordIsUpdatedOnExistingUser(): void
    {
        global $CFG_GLPI;
        $CFG_GLPI["event_loglevel"] = 0;

        $this->login();

        $login        = 'test_injected_user_' . random_int(1, PHP_INT_MAX);
        $password     = 'Ohbah7ohw!aeK3';
        $new_password = 'Eiy4ohn!ohGh1o';

        $results = $this->injectData(
            new PluginDatainjectionUserInjection(),
            ['User' => ['name' => $login, 'password' => $password]],
            ['User' => ['name' => true]],
        );
        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);

        $results = $this->injectData(
            new PluginDatainjectionUserInjection(),
            ['User' => ['name' => $login, 'password' => $new_password]],
            ['User' => ['name' => true]],
        );
        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);
        self::assertEmpty($_SESSION['MESSAGE_AFTER_REDIRECT'][ERROR] ?? []);

        $user = new User();
        self::assertTrue($user->getFromDB($results['User']));
        self::assertTrue(Auth::checkPassword($new_password, $user->fields['password']));
    }

    public function testInjectionIsRejectedWithoutUpdateRightOnExistingItem(): void
    {
        global $CFG_GLPI;
        $CFG_GLPI["event_loglevel"] = 0;

        $this->login();

        $computer = $this->createItem(Computer::class, [
            'name'        => 'Test_Computer_write_right_' . random_int(1, PHP_INT_MAX),
            'entities_id' => 0,
        ]);
        $comment = $computer->fields['comment'];

        $_SESSION['glpiactiveprofile'][Computer::$rightname] = READ;

        $results = $this->injectData(
            new PluginDatainjectionComputerInjection(),
            ['Computer' => ['name' => $computer->fields['name'], 'comment' => 'Injected comment']],
            ['Computer' => ['name' => true]],
        );

        self::assertSame(PluginDatainjectionCommonInjectionLib::WARNING, $results['status']);

        self::assertTrue($computer->getFromDB($computer->getID()));
        self::assertSame($comment, $computer->fields['comment']);
    }

    public function testInjectionIsRejectedWithoutCreateRight(): void
    {
        global $CFG_GLPI;
        $CFG_GLPI["event_loglevel"] = 0;

        $this->login();

        $name = 'Test_Computer_no_create_' . random_int(1, PHP_INT_MAX);

        $_SESSION['glpiactiveprofile'][Computer::$rightname] = READ;

        $results = $this->injectData(
            new PluginDatainjectionComputerInjection(),
            ['Computer' => ['name' => $name]],
            ['Computer' => ['name' => true]],
        );

        self::assertSame(PluginDatainjectionCommonInjectionLib::WARNING, $results['status']);
        self::assertSame(0, countElementsInTable('glpi_computers', ['name' => $name]));
    }
}
