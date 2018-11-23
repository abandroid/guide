<?php

declare(strict_types=1);

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
    private $shows;
    private $loaders;

    public function __construct(array $shows = [])
    {
        $this->shows = $shows;
    }

    public function addLoaders(iterable $loaders): void
    {
        foreach ($loaders as $loader) {
            $this->addLoader($loader);
        }
    }

    public function addLoader(LoaderInterface $loader): void
    {
        $this->loaders[$loader->getName()] = $loader;
    }

    public function load(): array
    {
        foreach ($this->shows as $index => &$show) {
            $show = $this->loadShow($show);
        }

        usort($this->shows, function ($show1, $show2) {
            $showDate1 = $show1['most_recent']->format('U');
            $showDate2 = $show2['most_recent']->format('U');

            if ($showDate1 === $showDate2) {
                return count($show2['results']) - count($show1['results']);
            }

            return $showDate2 - $showDate1;
        });

        return $this->shows;
    }

    public function loadShow(array $show): array
    {
        if (!isset($this->loaders[$show['type']])) {
            throw new InvalidLoaderException('No loader available for type '.$show['type']);
        }
        $stopwatch = new Stopwatch();
        $stopwatch->start('show');
        $show['results'] = $this->processResults($this->loaders[$show['type']]->load($show));
        $show['most_recent'] = $this->getMostRecent($show['results']);
        $event = $stopwatch->stop('show');
        $show['duration'] = $event->getDuration();

        return $show;
    }

    private function processResults(array $results): array
    {
        $this->sortResults($results);
        $this->filterResults($results);
        $this->colorResults($results);

        return $results;
    }

    private function sortResults(array &$results): void
    {
        usort($results, function ($result1, $result2) {
            return $result2['date']->format('U') - $result1['date']->format('U');
        });
    }

    private function colorResults(array &$results): void
    {
        $dateToday = new DateTime();
        $dateLastWeek = new DateTime('-1 week');
        foreach ($results as &$result) {
            $result['color'] = $result['date'] > $dateLastWeek ? $result['date'] > $dateToday ? 'warning' : 'success' : 'primary';
        }
    }

    private function filterResults(array &$results): void
    {
        $dateLastMonth = new DateTime('-1 month');
        $dateNextMonth = new DateTime('+1 month');
        $results = array_filter($results, function ($result) use ($dateLastMonth, $dateNextMonth) {
            return $result['date'] > $dateLastMonth && $result['date'] < $dateNextMonth;
        });
    }

    private function getMostRecent(array $results): DateTime
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
