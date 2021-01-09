<?php

declare(strict_types=1);

namespace byrokrat\accounting\Template;

use byrokrat\accounting\Container;

/**
 * @covers \byrokrat\accounting\Template\TemplateRendererFactory
 */
class TemplateRendererFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateRenderer()
    {
        $this->assertInstanceOf(
            TemplateRenderer::class,
            (new TemplateRendererFactory())->createRenderer(new Container())
        );
    }
}
