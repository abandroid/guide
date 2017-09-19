<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Guide\Loader;

interface LoaderInterface
{
    /**
     * @param array $show
     *
     * @return array
     */
    public function load(array &$show);

    /**
     * @return string
     */
    public function getName();
}
