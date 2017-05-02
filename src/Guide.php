<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Guide;

use DateTime;
use Endroid\Guide\Exception\InvalidLoaderException;
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
     * @throws InvalidLoaderException
     */
    public function load()
    {
        $stopwatch = new Stopwatch();

        foreach ($this->shows as $index => &$show) {
            if (!isset($this->loaders[$show['type']])) {
                throw new InvalidLoaderException('No loader available for type '.$show['type']);
            }
            $stopwatch->start($index);
            $show['results'] = $this->processResults($this->loaders[$show['type']]->load($show));
            $show['most_recent'] = $this->getMostRecent($show['results']);
            $event = $stopwatch->stop($index);
            $show['duration'] = $event->getDuration();
        }

        usort($this->shows, function ($show1, $show2) {
            return $show2['most_recent']->format('U') - $show1['most_recent']->format('U');
        });

        return $this->shows;
    }

    /**
     * @param array $results
     * @return array
     */
    protected function processResults(array $results)
    {
        $this->sortResults($results);
        $this->filterResults($results);
        $this->colorResults($results);

        return $results;
    }

    /**
     * @param array $results
     */
    protected function sortResults(array &$results)
    {
        usort($results, function ($result1, $result2) {
            return $result2['date']->format('U') - $result1['date']->format('U');
        });
    }

    /**
     * @param array $results
     */
    protected function colorResults(array &$results)
    {
        $dateToday = new DateTime();
        $dateLastWeek = new DateTime('-1 week');
        foreach ($results as &$result) {
            $result['color'] = $result['date'] > $dateLastWeek ? $result['date'] > $dateToday ? 'warning' : 'success' : 'primary';
        }
    }

    /**
     * @param array $results
     */
    protected function filterResults(array &$results)
    {
        $dateLastMonth = new DateTime('-1 month');
        $dateNextMonth = new DateTime('+1 month');
        $results = array_filter($results, function($result) use ($dateLastMonth, $dateNextMonth) {
            return $result['date'] > $dateLastMonth && $result['date'] < $dateNextMonth;
        });
    }

    /**
     * @param array $results
     * @return DateTime
     */
    protected function getMostRecent(array $results)
    {
        $mostRecent = new DateTime('-2 year');
        $currentDate = new DateTime();
        foreach ($results as $result) {
            if ($result['date'] <= $currentDate && $result['date'] > $mostRecent) {
                $mostRecent = $result['date'];
            }
        }

        return $mostRecent;
    }
}
