<?php

declare(strict_types=1);

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Guide\Loader;

class Sbs6Loader extends AbstractLoader
{
    public function load(array &$show): array
    {
        $url = 'https://api.sbs6.nl/v1/search/search/?query='.strtolower(str_replace(' ', '+', $show['label']));

        $results = json_decode(file_get_contents($url), true);
        foreach ($results as $result) {
            if (false !== strpos($result['title'], $show['label'])) {
                break;
            }
        }

        if (!$result) {
            return [];
        }

        $url = 'https://www.sbs6.nl'.$result['uri']['path'].'videos/';
        $context = stream_context_create(['http' => ['header' => 'X-Requested-With: XMLHttpRequest']]);
        $response = file_get_contents($url, false, $context);

        // now accept cookies...

        return [];
    }
}
