<?php

declare(strict_types=1);

namespace byrokrat\accounting\Template;

use byrokrat\accounting\QueryableInterface;
use byrokrat\accounting\Query;

/**
 * @covers \byrokrat\accounting\Template\TemplateRendererFactory
 */
class TemplateRendererFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateFromQuery()
    {
        $this->assertInstanceOf(
            TemplateRenderer::class,
            (new TemplateRendererFactory())->createRenderer($this->createMock(Query::class))
        );
    }

    public function testCreateFromQueryable()
    {
        $this->assertInstanceOf(
            TemplateRenderer::class,
            (new TemplateRendererFactory())->createRenderer($this->createMock(QueryableInterface::class))
        );
    }
}
