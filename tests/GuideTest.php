<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Guide\Tests;

use Endroid\Guide\Exception\InvalidLoaderException;
use Endroid\Guide\Guide;
use PHPUnit\Framework\TestCase;

class GuideTest extends TestCase
{
    public function testInvalidLoader()
    {
        $guide = new Guide([
            [
                'type' => 'invalid'
            ]
        ]);

        $this->expectException(InvalidLoaderException::class);

        $guide->load();
    }
}
