<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Guide\Bundle\GuideBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LoaderPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container)
    {
        if (!$container->has('endroid_guide.guide')) {
            return;
        }

        $guideDefinition = $container->findDefinition('endroid_guide.guide');

        $loaders = $this->findAndSortTaggedServices('endroid_guide.loader', $container);
        foreach ($loaders as $loader) {
            $guideDefinition->addMethodCall('registerLoader', [$loader]);
        }
    }
}
