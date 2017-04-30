<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Guide;

use Endroid\Guide\Loader\LoaderInterface;

class Guide
{
    /**
     * @var Show[]
     */
    protected $shows;

    /**
     * @var LoaderInterface[]
     */
    protected $loaders;

    /**
     * @param Show[] $shows
     */
    public function __construct(array $shows = [])
    {
        $this->shows = $shows;
    }

    /**
     * @param LoaderInterface $loader
     */
    public function registerLoader(LoaderInterface $loader)
    {
        $this->loaders[$loader->getName()] = $loader;
    }

    /**
     * @return Show[]
     */
    public function load()
    {
        foreach ($this->shows as $show) {
            $this->loaders[$show->getType()]->load($show);
        }

        return $this->shows;
    }
}