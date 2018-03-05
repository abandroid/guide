<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Guide\Loader;

use DateInterval;
use DateTime;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\DomCrawler\Crawler;

class EpguidesLoader extends AbstractLoader
{
    protected $cache;

    public function __construct(AdapterInterface $cache)
    {
        $this->cache = $cache;
    }

    public function load(array &$show): array
    {
        $cache = $this->cache->getItem('endroid_guide.epguides.'.$show['label']);

        if ($cache->isHit()) {
            return $cache->get();
        }

        $show['url'] = 'http://epguides.com/'.str_replace(' ', '', $show['label']).'/';

        $html = file_get_contents($show['url']);
        $crawler = new Crawler($html);

        $list = $crawler->filter('#eplist')->text();
        preg_match_all('#[0-9]+\.\s+([0-9]+)-([0-9]+)\s+([0-9]{2}\s[a-z]{3}\s[0-9]{2})\s+(.*)#i', $list, $matches);

        $results = [];
        $matchCount = count($matches[0]);
        $currentDate = new DateTime();
        $dateLastWeek = clone $currentDate;
        $dateLastWeek->sub(new DateInterval('P7D'));
        $dateNextWeek = clone $currentDate;
        $dateNextWeek->add(new DateInterval('P7D'));
        for ($i = 0; $i < $matchCount; ++$i) {
            $date = DateTime::createFromFormat('d M y H:i', $matches[3][$i].' 23:00');
            $episodeName = 'S'.str_pad($matches[1][$i], 2, '0', STR_PAD_LEFT).'E'.str_pad($matches[2][$i], 2, '0', STR_PAD_LEFT);
            $result = [
                'label' => $episodeName.' ('.$date->format('d-m-Y').')',
                'url' => 'https://thepiratebay.org/search/'.str_replace(' ', '%20', $show['label']).'%20'.$episodeName.'/0/99/0',
                'date' => $date,
            ];
            $results[] = $result;
        }

        $cache->set($results);
        $cache->expiresAfter(7 * 24 * 3600);
        $this->cache->save($cache);

        return $results;
    }
}
