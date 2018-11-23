<?php

declare(strict_types=1);

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
    public function load(array &$show): array
    {
        $url = 'https://www.npo.nl/search?query='.strtolower(str_replace(' ', '+', $show['label']));

        $context = stream_context_create(['http' => ['header' => 'X-Requested-With: XMLHttpRequest']]);
        $response = file_get_contents($url, false, $context);

        $crawler = new Crawler($response);
        $node = $crawler->filter('.npo-tile-link')->first();

        $show['url'] = $node->attr('href');
        $show['label'] = $node->attr('title');

        $html = file_get_contents($show['url']);
        $crawler = new Crawler($html);

        $results = $crawler->filter('#component-grid-episodes .npo-tile-link')->each(function ($node) {
            $url = $node->attr('href');

            preg_match_all('#[0-9]{2}-[0-9]{2}-[0-9]{4}#', $url, $matches);

            if (0 == count($matches[0])) {
                return null;
            }

            $title = $matches[0][0];

            return [
                'label' => $title,
                'url' => $url,
                'date' => new DateTime($title),
            ];
        });

        $results = array_filter($results, function ($result) {
            return null !== $result;
        });

        return $results;
    }
}
