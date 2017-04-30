<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Guide\Loader;

use ReflectionClass;

abstract class AbstractLoader implements LoaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        $reflectionClass = new ReflectionClass($this);
        $name = strtolower(str_replace('Loader', '', $reflectionClass->getShortName()));

        return $name;
    }
}
