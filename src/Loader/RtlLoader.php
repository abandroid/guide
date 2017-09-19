<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Guide\Loader;

use DateTime;
use stdClass;

class RtlLoader extends AbstractLoader
{
    /**
     * {@inheritdoc}
     */
    public function load(array &$show)
    {
        if (!isset($show['url'])) {
            $show['url'] = 'http://pg.us.rtl.nl/rtlxl/network/a3m/progressive/components/videorecorder/{short_abstract_key}/{abstract_key}/{season_key}/{uuid}.ssm/{uuid}.mp4';
        }

        $url = 'https://search.rtl.nl/?search='.strtolower(str_replace(' ', '%20', $show['label'])).'&page=1&pageSize=12&typeRestriction=tvabstract';
        $response = json_decode(file_get_contents($url));

        $abstract = $response->Abstracts[0];
        foreach ($response->Abstracts as $abstract) {
            if ($abstract->Title == $show['label']) {
                break;
            }
        }

        $show['label'] = $abstract->Title;

        $url = 'http://www.rtl.nl/system/s4m/vfd/version=2/d=pc/output=json/ak='.$abstract->AbstractKey.'/pg=1/cf=allow%20uitzending';
        $response = json_decode(file_get_contents($url));

        $results = [];
        foreach ($response->material as $episode) {
            $date = DateTime::createFromFormat('U', $episode->display_date);
            $results[] = [
                'label' => $date->format('d-m-Y'),
                'url' => $this->replace($show['url'], $episode),
                'date' => $date,
            ];
        }

        return $results;
    }

    /**
     * @param string   $url
     * @param stdClass $episode
     *
     * @return string
     */
    protected function replace($url, $episode)
    {
        $url = str_replace([
            '{uuid}',
            '{short_abstract_key}',
            '{abstract_key}',
            '{season_key}',
        ], [
            $episode->uuid,
            substr($episode->abstract_key, 0, 2),
            $episode->abstract_key,
            $episode->season_key,
        ], $url);

        return $url;
    }
}
