<?php

namespace GlpiPlugin\Datainjection\Tests\Unit;

use Glpi\Tests\DbTestCase;
use Manufacturer;
use PluginDatainjectionCommonInjectionLib;

require_once dirname(__DIR__, 2) . '/inc/injectioninterface.class.php';
require_once dirname(__DIR__, 2) . '/inc/commoninjectionlib.class.php';
require_once dirname(__DIR__, 2) . '/inc/manufacturerinjection.class.php';

final class CommonInjectionLibDataAlreadyInDbTest extends DbTestCase
{
    public function testDataAlreadyInDbHandlesApostropheInMandatoryFieldValue(): void
    {
        $manufacturer_name = "O'Brien Manufacturer";
        $manufacturer = $this->createItem(Manufacturer::class, [
            'name' => $manufacturer_name,
        ]);

        $injection_class = new \PluginDatainjectionManufacturerInjection();
        $common_injection_lib = new PluginDatainjectionCommonInjectionLib(
            $injection_class,
            [
                'Manufacturer' => [
                    'name' => $manufacturer_name,
                ],
            ],
            [
                'mandatory_fields' => [
                    'Manufacturer' => [
                        'name' => true,
                    ],
                ],
            ],
        );

        $invoke_data_already_in_db = \Closure::bind(
            function ($injection_class, string $itemtype): void {
                $this->dataAlreadyInDB($injection_class, $itemtype);
            },
            $common_injection_lib,
            PluginDatainjectionCommonInjectionLib::class,
        );

        $invoke_data_already_in_db($injection_class, 'Manufacturer');

        $values = $common_injection_lib->getValuesForItemtype('Manufacturer');
        self::assertIsArray($values);
        self::assertSame($manufacturer->getID(), $values['id']);
    }
}



