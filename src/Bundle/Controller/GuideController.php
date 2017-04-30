<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Guide\Bundle\Controller;

use Endroid\Guide\Guide;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GuideController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $shows = $this->getGuide()->load();

        return [
            'shows' => $shows
        ];
    }

    /**
     * @return Guide
     */
    protected function getGuide()
    {
        return $this->get('endroid_guide.guide');
    }
}
