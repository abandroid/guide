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

class NpoLoader extends AbstractLoader
{
    /**
     * {@inheritdoc}
     */
    public function load(array &$show)
    {
        $url = 'https://www.npo.nl/suggesties?q='.strtolower(str_replace(' ', '+', $show['label']));

        $abstract = json_decode(file_get_contents($url))[0];

        $show['label'] = $abstract->titleMain;

        $html = file_get_contents($abstract->url.'/search');
        $crawler = new Crawler($html);
        $results = $crawler->filter('.row-fluid .image a')->each(function ($node) {
            $url = 'http://www.npo.nl'.$node->attr('href');

            preg_match_all('#[0-9]{2}-[0-9]{2}-[0-9]{4}#', $url, $matches);

            if (count($matches[0]) == 0) {
                return null;
            }

            $title = $matches[0][0];

            return [
                'label' => $title,
                'url' => $url,
                'date' => new DateTime($title)
            ];
        });

        $results = array_filter($results, function($result) {
            return $result !== null;
        });

        return $results;
    }
}
