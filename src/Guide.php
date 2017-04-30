<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Guide;

use Endroid\Guide\Loader\LoaderInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class Guide
{
    /**
     * @var array
     */
    protected $shows;

    /**
     * @var LoaderInterface[]
     */
    protected $loaders;

    /**
     * @param array $shows
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
     * @return array
     */
    public function load()
    {
        $stopwatch = new Stopwatch();

        foreach ($this->shows as &$show) {
            $stopwatch->start($show['label']);
            $show['results'] = $this->loaders[$show['type']]->load($show);
            $event = $stopwatch->stop($show['label']);
            $show['duration'] = $event->getDuration();
        }

        return $this->shows;
    }
}
