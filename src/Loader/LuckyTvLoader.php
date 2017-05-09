<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Guide\Loader;

use DateTime;
use Symfony\Component\DomCrawler\Crawler;

class LuckyTvLoader extends AbstractLoader
{
    /**
     * @var array
     */
    protected $months = [
        'jan' => '01',
        'feb' => '02',
        'mrt' => '03',
        'apr' => '04',
        'mei' => '05',
        'jun' => '06',
        'jul' => '07',
        'aug' => '08',
        'sep' => '09',
        'okt' => '10',
        'nov' => '11',
        'dec' => '12'
    ];

    /**
     * {@inheritdoc}
     */
    public function load(array &$show)
    {
        $html = file_get_contents('http://www.luckytv.nl/afleveringen/');

        $crawler = new Crawler($html);
        $results = $crawler->filter('.video')->each(function ($node) {
            $url = $node->filter('a')->attr('href');
            $date = str_replace(array_keys($this->months), array_values($this->months), substr($node->filter('.video__date')->text(), 3));
            $date = DateTime::createFromFormat('j m Y H:i', $date.' 22:00');

            return [
                'label' => $date->format('Y-m-d'),
                'url' => $url,
                'date' => $date,
            ];
        });

        return $results;
    }
}
