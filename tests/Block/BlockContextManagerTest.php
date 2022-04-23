<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\BlockBundle\Tests\Block;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\BlockContextManager;
use Sonata\BlockBundle\Block\BlockLoaderInterface;
use Sonata\BlockBundle\Block\BlockServiceManagerInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Sonata\BlockBundle\Model\BlockInterface;

final class BlockContextManagerTest extends TestCase
{
    public function testGetWithValidData(): void
    {
        $service = $this->createMock(AbstractBlockService::class);

        $service->expects(static::once())->method('configureSettings');

        $blockLoader = $this->createMock(BlockLoaderInterface::class);

        $serviceManager = $this->createMock(BlockServiceManagerInterface::class);
        $serviceManager->expects(static::once())->method('get')->willReturn($service);

        $block = $this->createMock(BlockInterface::class);
        $block->expects(static::once())->method('getSettings')->willReturn([]);

        $manager = new BlockContextManager($blockLoader, $serviceManager);

        $settings = ['template' => 'custom.html.twig'];

        $blockContext = $manager->get($block, $settings);

        static::assertInstanceOf(BlockContextInterface::class, $blockContext);

        static::assertSame([
            'attr' => [],
            'template' => 'custom.html.twig',
        ], $blockContext->getSettings());
    }

    public function testGetWithSettings(): void
    {
        $service = $this->createMock(AbstractBlockService::class);
        $service->expects(static::once())->method('configureSettings');

        $blockLoader = $this->createMock(BlockLoaderInterface::class);

        $serviceManager = $this->createMock(BlockServiceManagerInterface::class);
        $serviceManager->expects(static::once())->method('get')->willReturn($service);

        $block = $this->createMock(BlockInterface::class);
        $block->expects(static::once())->method('getSettings')->willReturn([]);

        $manager = new BlockContextManager($blockLoader, $serviceManager);

        $settings = ['template' => 'custom.html.twig'];

        $blockContext = $manager->get($block, $settings);

        static::assertInstanceOf(BlockContextInterface::class, $blockContext);

        static::assertSame([
            'attr' => [],
            'template' => 'custom.html.twig',
        ], $blockContext->getSettings());
    }

    public function testWithInvalidSettings(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(static::once())->method('error');

        $service = $this->createMock(AbstractBlockService::class);
        $service->expects(static::exactly(2))->method('configureSettings');

        $blockLoader = $this->createMock(BlockLoaderInterface::class);

        $serviceManager = $this->createMock(BlockServiceManagerInterface::class);
        $serviceManager->expects(static::exactly(2))->method('get')->willReturn($service);

        $block = $this->createMock(BlockInterface::class);
        $block->expects(static::once())->method('getSettings')->willReturn([
            'template' => 'custom.html.twig',
            'attr' => 'shouldBeAnArray',
        ]);
        $block->expects(static::once())->method('getSetting')->with('template')->willReturn('custom.html.twig');

        $manager = new BlockContextManager($blockLoader, $serviceManager, $logger);

        $blockContext = $manager->get($block);

        static::assertInstanceOf(BlockContextInterface::class, $blockContext);
    }
}
