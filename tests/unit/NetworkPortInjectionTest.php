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

namespace GlpiPlugin\Datainjection\Tests\Unit;

use Computer;
use Glpi\Tests\DbTestCase;
use NetworkEquipment;
use NetworkPort;
use NetworkPort_NetworkPort;
use PHPUnit\Framework\Attributes\DataProvider;
use PluginDatainjectionCommonInjectionLib;
use PluginDatainjectionComputerInjection;

final class NetworkPortInjectionTest extends DbTestCase
{
    private function injectComputerWithPort(array $port, ?string $computer_name = null): array
    {
        $entities_id = $this->getTestRootEntity(true);
        if ($computer_name === null || countElementsInTable(Computer::getTable(), ['name' => $computer_name]) === 0) {
            $computer_name ??= 'Test_Computer_port_' . random_int(1, PHP_INT_MAX);
            $this->createItem(Computer::class, ['name' => $computer_name, 'entities_id' => $entities_id]);
        }

        $lib = new PluginDatainjectionCommonInjectionLib(
            new PluginDatainjectionComputerInjection(),
            [
                'Computer'    => ['name' => $computer_name, 'entities_id' => $entities_id],
                'NetworkPort' => $port,
            ],
            [
                'rights'           => ['can_add' => true, 'can_update' => true],
                'mandatory_fields' => ['Computer' => ['name' => true]],
                'entities_id'      => $entities_id,
                'checks'           => ['port_unicity' => PluginDatainjectionCommonInjectionLib::UNICITY_NETPORT_MACADDRESS],
            ],
        );
        $lib->processAddOrUpdate();

        return $lib->getInjectionResults();
    }

    public static function noConnectionProvider(): iterable
    {
        yield 'connection fields not mapped'   => [[]];
        yield 'connection fields mapped empty' => [['netname' => '', 'netport' => '', 'netmac' => '']];
    }

    #[DataProvider('noConnectionProvider')]
    public function testPortWithoutConnectionToSwitchIsNotAWarning(array $connection): void
    {
        $this->login();

        $results = $this->injectComputerWithPort(['mac' => 'AA:11:BB:22:CC:33'] + $connection);

        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);
        self::assertSame(1, countElementsInTable(NetworkPort::getTable(), [
            'itemtype' => 'Computer',
            'items_id' => $results['Computer'],
            'mac'      => 'aa:11:bb:22:cc:33',
        ]));
    }

    public static function switchCriteriaProvider(): iterable
    {
        yield 'switch name and port number' => [fn(string $switch, string $mac) => ['netname' => $switch, 'netport' => '5']];
        yield 'switch port mac'             => [fn(string $switch, string $mac) => ['netmac' => $mac]];
    }

    #[DataProvider('switchCriteriaProvider')]
    public function testPortIsConnectedToTheRequestedSwitchPort(callable $criteria): void
    {
        $this->login();

        $suffix      = random_int(1, PHP_INT_MAX);
        $switch_name = 'Test_Switch_' . $suffix;
        $switch_mac  = sprintf('02:00:%02x:%02x:%02x:%02x', ...array_values(unpack('C4', pack('N', $suffix))));
        $switch      = $this->createItem(NetworkEquipment::class, [
            'name'        => $switch_name,
            'entities_id' => $this->getTestRootEntity(true),
        ]);
        $switch_port = $this->createItem(NetworkPort::class, [
            'itemtype'           => NetworkEquipment::class,
            'items_id'           => $switch->getID(),
            'entities_id'        => $this->getTestRootEntity(true),
            'logical_number'     => 5,
            'mac'                => $switch_mac,
            'instantiation_type' => 'NetworkPortEthernet',
        ]);

        $results = $this->injectComputerWithPort(['mac' => '02:aa:bb:cc:dd:ee'] + $criteria($switch_name, $switch_mac));

        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status']);
        $computer_port = new NetworkPort();
        self::assertTrue($computer_port->getFromDBByCrit(['itemtype' => 'Computer', 'items_id' => $results['Computer']]));
        self::assertSame(
            $switch_port->getID(),
            (new NetworkPort_NetworkPort())->getOppositeContact($computer_port->getID()),
        );
    }

    private function assertPortNotConnected(array $results, int $code): void
    {
        self::assertSame(PluginDatainjectionCommonInjectionLib::WARNING, $results['status']);
        self::assertContains([$code, NetworkPort::class], $results[PluginDatainjectionCommonInjectionLib::ACTION_CHECK]);

        // The port itself is still created, only the connection is missing
        $computer_port = new NetworkPort();
        self::assertTrue($computer_port->getFromDBByCrit(['itemtype' => 'Computer', 'items_id' => $results['Computer']]));
        self::assertFalse((new NetworkPort_NetworkPort())->getOppositeContact($computer_port->getID()));
    }

    private function createSwitchPort(string $switch_name, int $logical_number): NetworkPort
    {
        $switch = new NetworkEquipment();
        if (!$switch->getFromDBByCrit(['name' => $switch_name])) {
            $switch = $this->createItem(NetworkEquipment::class, [
                'name'        => $switch_name,
                'entities_id' => $this->getTestRootEntity(true),
            ]);
        }

        return $this->createItem(NetworkPort::class, [
            'itemtype'           => NetworkEquipment::class,
            'items_id'           => $switch->getID(),
            'entities_id'        => $this->getTestRootEntity(true),
            'logical_number'     => $logical_number,
            'instantiation_type' => 'NetworkPortEthernet',
        ]);
    }

    public function testPortIsReportedWhenTheRequestedSwitchPortIsNotFoundOrAmbiguous(): void
    {
        $this->login();

        // Not found: no switch with this name
        $results = $this->injectComputerWithPort(['mac' => '02:aa:bb:cc:dd:01', 'netname' => 'Unknown_Switch_' . random_int(1, PHP_INT_MAX)]);
        $this->assertPortNotConnected($results, PluginDatainjectionCommonInjectionLib::WARNING_NETPORT_NOT_FOUND);
        self::assertStringContainsString(
            'no matching network equipment port found',
            PluginDatainjectionCommonInjectionLib::getLogLabel(PluginDatainjectionCommonInjectionLib::WARNING_NETPORT_NOT_FOUND),
        );

        // Ambiguous: the switch has two ports and only its name is given
        $switch_name = 'Test_Switch_' . random_int(1, PHP_INT_MAX);
        $this->createSwitchPort($switch_name, 1);
        $this->createSwitchPort($switch_name, 2);

        $results = $this->injectComputerWithPort(['mac' => '02:aa:bb:cc:dd:02', 'netname' => $switch_name]);
        $this->assertPortNotConnected($results, PluginDatainjectionCommonInjectionLib::WARNING_NETPORT_AMBIGUOUS);
    }

    public function testPortIsReportedWhenTheSwitchPortIsAlreadyConnectedElsewhere(): void
    {
        $this->login();

        $switch_name = 'Test_Switch_' . random_int(1, PHP_INT_MAX);
        $this->createSwitchPort($switch_name, 5);
        $connection  = ['netname' => $switch_name, 'netport' => '5'];

        $first = $this->injectComputerWithPort(['mac' => '02:aa:bb:cc:dd:03'] + $connection);
        self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $first['status']);

        $results = $this->injectComputerWithPort(['mac' => '02:aa:bb:cc:dd:04'] + $connection);
        $this->assertPortNotConnected($results, PluginDatainjectionCommonInjectionLib::WARNING_NETPORT_ALREADY_CONNECTED);
    }

    public function testReimportingAnAlreadyConnectedPortIsNotAWarning(): void
    {
        $this->login();

        $switch_name   = 'Test_Switch_' . random_int(1, PHP_INT_MAX);
        $switch_port   = $this->createSwitchPort($switch_name, 5);
        $computer_name = 'Test_Computer_port_' . random_int(1, PHP_INT_MAX);
        $port          = ['mac' => '02:aa:bb:cc:dd:05', 'netname' => $switch_name, 'netport' => '5'];

        foreach ([1, 2] as $run) {
            $results = $this->injectComputerWithPort($port, $computer_name);
            self::assertSame(PluginDatainjectionCommonInjectionLib::SUCCESS, $results['status'], 'run ' . $run);
        }

        self::assertSame(1, countElementsInTable(NetworkPort::getTable(), ['itemtype' => 'Computer', 'items_id' => $results['Computer']]));
        self::assertSame(1, countElementsInTable(NetworkPort_NetworkPort::getTable(), ['networkports_id_2' => $switch_port->getID()]));
    }
}
