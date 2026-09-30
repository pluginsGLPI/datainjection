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

use Glpi\Tests\DbTestCase;
use PluginDatainjectionClientInjection;
use ReflectionMethod;
use Safe\Exceptions\FilesystemException;

require_once dirname(__DIR__, 2) . '/inc/clientinjection.class.php';

final class ClientInjectionWriteErrorsCsvTest extends DbTestCase
{
    /** @var string[] */
    private array $created_files = [];

    public function tearDown(): void
    {
        foreach ($this->created_files as $created_file) {
            if (file_exists($created_file)) {
                unlink($created_file);
            }
        }

        $this->created_files = [];

        parent::tearDown();
    }

    private function writeErrorsCsv(array $error_lines, array $headers, string $dir = PLUGIN_DATAINJECTION_UPLOAD_DIR): string
    {
        $write_errors_csv = new ReflectionMethod(PluginDatainjectionClientInjection::class, 'writeErrorsCsv');
        $file             = $write_errors_csv->invoke(null, $dir, $error_lines, $headers, ';');
        $this->created_files[] = $file;

        return $file;
    }

    public function testFileIsCreatedInUploadDirWithErrPrefix(): void
    {
        $file = $this->writeErrorsCsv([['a', 'b']], []);

        $this->assertSame(realpath(PLUGIN_DATAINJECTION_UPLOAD_DIR), realpath(dirname($file)));
        $this->assertStringStartsWith('ERR', basename($file));
    }

    public function testMissingDirDoesNotFallBackToSystemTempDir(): void
    {
        $this->expectException(FilesystemException::class);

        $this->writeErrorsCsv([['a']], [], PLUGIN_DATAINJECTION_UPLOAD_DIR . '/missing_dir');
    }

    public function testEachCallUsesADistinctFile(): void
    {
        $first  = $this->writeErrorsCsv([['a']], []);
        $second = $this->writeErrorsCsv([['a']], []);

        $this->assertNotSame($first, $second);
    }

    public function testContentContainsHeadersAndEscapedLines(): void
    {
        $file = $this->writeErrorsCsv([['=SUM(A1)', 'safe']], ['name', 'serial']);

        $this->assertSame("name;serial\n'=SUM(A1);safe\n", file_get_contents($file));
    }

    public function testDiscardClosesHandleAndRemovesFile(): void
    {
        $file   = $this->writeErrorsCsv([['a']], []);
        $handle = fopen($file, 'r');

        $this->discardErrorsCsv($handle, $file);

        $this->assertFalse(is_resource($handle));
        $this->assertFileDoesNotExist($file);
    }

    public function testDiscardIgnoresCleanupFailures(): void
    {
        $missing_file = PLUGIN_DATAINJECTION_UPLOAD_DIR . '/missing_file.csv';

        $this->discardErrorsCsv(null, $missing_file);

        $this->assertFileDoesNotExist($missing_file);
    }

    private function discardErrorsCsv($handle, string $file): void
    {
        $discard_errors_csv = new ReflectionMethod(PluginDatainjectionClientInjection::class, 'discardErrorsCsv');
        $discard_errors_csv->invoke(null, $handle, $file);
    }
}
